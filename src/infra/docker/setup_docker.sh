#!/bin/bash

# Define variables
DOCKER_VERSION="latest"

# Update the apt package index
echo "Updating package index..."
sudo apt-get update -y

# Install packages to allow apt to use a repository over HTTPS
echo "Installing packages for HTTPS..."
sudo apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common

# Add Docker’s official GPG key
echo "Adding Docker's official GPG key..."
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

# Set up the stable repository
echo "Adding Docker's APT repository..."
sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
   $(lsb_release -cs) \
   stable"

# Update the apt package index again with Docker's repository
echo "Updating package index again after adding Docker repo..."
sudo apt-get update -y

# Install Docker
echo "Installing Docker..."
sudo apt-get install -y docker-ce docker-ce-cli containerd.io

# Verify Docker is installed correctly
echo "Verifying Docker installation..."
sudo docker --version

# Allow current user to run Docker without sudo
echo "Adding current user to Docker group..."
sudo usermod -aG docker $USER

# Enable Docker to start on boot
echo "Enabling Docker to start on boot..."
sudo systemctl enable docker

# Start Docker service
echo "Starting Docker service..."
sudo systemctl start docker

# Installation complete
echo "Docker has been successfully installed and started."
echo "You may need to log out and log back in for Docker group changes to take effect."
