#!/bin/bash

# Define variables
DOMAIN_NAME="bot.biniyambelayneh.com"  # Replace with your actual domain or subdomain
APP_HTTP_PORT="8000"           # FastAPI port
APP_CONTAINER_NAME="fastapi-app"
NGINX_CONTAINER_NAME="nginx-proxy"
EMAIL="biniyambelayneh376@gmail.com"  # Replace with your email for Certbot
CERT_PATH="/etc/letsencrypt/live/$DOMAIN_NAME"  # Path to check for the existing certificate
DOCKERFILE_PATH="./Dockerfile"  # Path to the local Dockerfile for FastAPI

# Function to stop and remove container if it's already running or exists
remove_existing_container() {
    CONTAINER_NAME=$1
    if [ "$(docker ps -aq -f name=$CONTAINER_NAME)" ]; then
        echo "Stopping and removing existing container: $CONTAINER_NAME"
        docker rm -f $CONTAINER_NAME
    fi
}

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

# Check if Dockerfile exists in the current directory
if [ ! -f "$DOCKERFILE_PATH" ]; then
    echo "Dockerfile not found in the current directory. Exiting..."
    exit 1
fi

# Build FastAPI Docker image locally
echo "Building FastAPI Docker image from the local Dockerfile..."
docker build -t fastapi-app-image .

# Create a network for FastAPI and NGINX if it doesn't exist
echo "Creating Docker network if it doesn't exist..."
docker network inspect app-network >/dev/null 2>&1 || docker network create app-network

# Stop and remove existing FastAPI container if necessary
remove_existing_container $APP_CONTAINER_NAME

# Start FastAPI container
echo "Starting FastAPI container..."
docker run -d --name $APP_CONTAINER_NAME \
  --network app-network \
  -p $APP_HTTP_PORT:8000 \
  fastapi-app-image

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
  --network app-network \
  -p 80:80 -p 443:443 \
  -v /etc/nginx/conf.d:/etc/nginx/conf.d \
  nginx

# Wait for FastAPI container to be ready
echo "Waiting for FastAPI app to be ready..."
until curl -s http://localhost:$APP_HTTP_PORT >/dev/null; do
    echo "Waiting for FastAPI..."
    sleep 5
done

# Get FastAPI container's IP address
APP_IP=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $APP_CONTAINER_NAME)

# Create NGINX configuration for FastAPI using the container IP address
NGINX_CONFIG="/etc/nginx/conf.d/$DOMAIN_NAME.conf"

echo "Creating NGINX configuration for FastAPI..."
cat <<EOL | sudo tee $NGINX_CONFIG
server {
    listen 80;
    server_name $DOMAIN_NAME;

    location / {
        proxy_pass http://$APP_IP:$APP_HTTP_PORT;  # Using IP address of the FastAPI container
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
        proxy_pass http://$APP_IP:$APP_HTTP_PORT;
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
echo "FastAPI app is running and available at https://$DOMAIN_NAME with SSL."
