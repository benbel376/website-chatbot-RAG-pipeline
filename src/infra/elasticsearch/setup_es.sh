#!/bin/bash

# Define variables
DOMAIN_NAME="ex.biniyambelayneh.com"  # Replace with your actual domain or subdomain
ES_HTTP_PORT="9200"           # Elasticsearch port
ES_CONTAINER_NAME="elasticsearch"
NGINX_CONTAINER_NAME="nginx-proxy"
ES_PASSWORD="elasticsearchpass"  # Set your elastic user password
EMAIL="biniyambelayneh376@gmail.com"  # Replace with your email for Certbot
ES_DATA_PATH="/home/biniyambelayneh376/es-data"  # Elasticsearch data directory
CERT_PATH="/etc/letsencrypt/live/$DOMAIN_NAME"  # Path to check for the existing certificate

# Function to stop and remove container if it's already running or exists
remove_existing_container() {
    CONTAINER_NAME=$1
    if [ "$(docker ps -aq -f name=$CONTAINER_NAME)" ]; then
        echo "Stopping and removing existing container: $CONTAINER_NAME"
        docker rm -f $CONTAINER_NAME
    fi
}

# Ensure the data directory exists and set the correct permissions
prepare_data_directory() {
    if [ ! -d "$ES_DATA_PATH" ]; then
        echo "Creating Elasticsearch data directory at $ES_DATA_PATH"
        mkdir -p "$ES_DATA_PATH"
    fi
    echo "Setting ownership of $ES_DATA_PATH to UID 1000 (Elasticsearch default user)"
    sudo chown -R 1000:1000 "$ES_DATA_PATH"
}

# Prepare the Elasticsearch data directory
prepare_data_directory

# Install Docker if not installed
if ! [ -x "$(command -v docker)" ]; then
  echo "Docker not found. Installing Docker..."
  sudo apt update
  sudo apt install -y apt-transport-https ca-certificates curl software-properties-common
  curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
  sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
  sudo apt update
  sudo apt install -y docker-ce
fi

# Pull Elasticsearch Docker image
echo "Pulling Elasticsearch Docker image..."
docker pull elasticsearch:8.9.0

# Create a network for Elasticsearch and NGINX if it doesn't exist
echo "Creating Docker network if it doesn't exist..."
docker network inspect es-network >/dev/null 2>&1 || docker network create es-network

# Stop and remove existing Elasticsearch container if necessary
remove_existing_container $ES_CONTAINER_NAME

# Start Elasticsearch container
echo "Starting Elasticsearch container..."
docker run -d --name $ES_CONTAINER_NAME \
  --network es-network \
  -p $ES_HTTP_PORT:9200 \
  -e "discovery.type=single-node" \
  -e "ES_JAVA_OPTS=-Xms512m -Xmx512m" \
  -e "ELASTIC_PASSWORD=$ES_PASSWORD" \
  -v "$ES_DATA_PATH":/usr/share/elasticsearch/data \
  elasticsearch:8.9.0

# Install Certbot if not installed
if ! [ -x "$(command -v certbot)" ]; then
  echo "Certbot not found. Installing Certbot..."
  sudo apt update
  sudo apt install -y certbot python3-certbot-nginx
fi

# Stop and remove existing NGINX container if necessary
remove_existing_container $NGINX_CONTAINER_NAME

# Pull and run NGINX container
echo "Pulling and starting NGINX container as a reverse proxy..."
docker run -d --name $NGINX_CONTAINER_NAME \
  --network es-network \
  -p 80:80 -p 443:443 \
  -v /etc/nginx/conf.d:/etc/nginx/conf.d \
  nginx

# Wait for Elasticsearch container to be ready with credentials
echo "Waiting for Elasticsearch to be ready..."
until curl -u elastic:$ES_PASSWORD -s http://localhost:$ES_HTTP_PORT >/dev/null; do
    echo "Waiting for Elasticsearch..."
    sleep 5
done

# Get Elasticsearch container's IP address
ES_IP=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $ES_CONTAINER_NAME)

# Create NGINX configuration for Elasticsearch using the container IP address
NGINX_CONFIG="/etc/nginx/conf.d/$DOMAIN_NAME.conf"

echo "Creating NGINX configuration for Elasticsearch..."
cat <<EOL | sudo tee $NGINX_CONFIG
server {
    listen 80;
    server_name $DOMAIN_NAME;

    location / {
        proxy_pass http://$ES_IP:9200;  # Using IP address of the Elasticsearch container
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOL

# Restart NGINX container to apply the new configuration
echo "Restarting NGINX container..."
docker exec $NGINX_CONTAINER_NAME nginx -s reload || {
    echo "Reload failed, attempting to restart NGINX using systemctl..."
    sudo systemctl restart nginx
}

# Check if a certificate already exists
if [ -d "$CERT_PATH" ]; then
  echo "Certificate already exists for $DOMAIN_NAME. Skipping certificate creation."
else
  # Obtain SSL certificate using Certbot (Let’s Encrypt)
  echo "Obtaining SSL certificate for $DOMAIN_NAME..."
  sudo certbot --nginx -d $DOMAIN_NAME --non-interactive --agree-tos --email $EMAIL
fi

# Update NGINX configuration to use SSL
echo "Updating NGINX configuration to use SSL..."
cat <<EOL | sudo tee -a $NGINX_CONFIG
server {
    listen 443 ssl; # managed by Certbot
    server_name $DOMAIN_NAME;

    ssl_certificate /etc/letsencrypt/live/$DOMAIN_NAME/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN_NAME/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

    location / {
        proxy_pass http://$ES_IP:9200;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOL

# Reload NGINX to apply SSL configuration
echo "Reloading NGINX to apply SSL configuration..."
docker exec $NGINX_CONTAINER_NAME nginx -s reload || {
    echo "Reload failed, attempting to restart NGINX using systemctl..."
    sudo systemctl restart nginx
}

# Ensure certificate renewal process is in place
echo "Setting up automatic certificate renewal..."
sudo certbot renew --dry-run

# Print completion message
echo "Elasticsearch is running and available at https://$DOMAIN_NAME with SSL."
echo "Access it with the elastic user password: $ES_PASSWORD"
