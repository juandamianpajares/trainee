# BITNET Trainee - Deployment Guide

Complete guide for deploying the BITNET Trainee application to a remote server using automated deployment scripts.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Initial Setup](#initial-setup)
- [Configuration](#configuration)
- [Deployment](#deployment)
- [Rollback](#rollback)
- [Troubleshooting](#troubleshooting)
- [Advanced Options](#advanced-options)

---

## Prerequisites

Before deploying, ensure you have:

### Local Machine Requirements

- Git Bash, WSL, or any Unix-like shell
- SSH client installed
- `rsync` installed (usually comes with Git Bash or WSL)
- SSH access to your remote server

### Remote Server Requirements

- Ubuntu/Debian Linux (recommended) or any Linux distribution
- Docker and Docker Compose installed
- SSH access configured
- Sufficient disk space (at least 5GB recommended)
- Ports 80, 8080, 3306, 8025 available (or configure custom ports)

---

## Initial Setup

### 1. Clone the Repository

```bash
git clone <your-repo-url>
cd trainee
```

### 2. Configure SSH Access

Ensure you can connect to your server via SSH:

```bash
ssh your_username@your-server.com
```

If you need to set up SSH keys:

```bash
# Generate SSH key (if you don't have one)
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

# Copy SSH key to server
ssh-copy-id your_username@your-server.com
```

### 3. Prepare the Server

Connect to your server and ensure Docker is installed:

```bash
# Install Docker (if not installed)
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose (if not installed)
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add your user to docker group
sudo usermod -aG docker $USER
```

Log out and log back in for group changes to take effect.

### 4. Create Project Directory on Server

```bash
# On your server
sudo mkdir -p /var/www/html/trainee
sudo chown $USER:$USER /var/www/html/trainee
```

---

## Configuration

### 1. Create Deployment Configuration

On your local machine:

```bash
# Copy the example configuration
cp deploy.config.example deploy.config
```

### 2. Edit Configuration

Edit `deploy.config` with your server details:

```bash
# SSH Configuration
SSH_USER=your_username
SSH_HOST=your-server.com
SSH_PORT=22
SSH_KEY_PATH=~/.ssh/id_rsa

# Remote paths
REMOTE_PROJECT_PATH=/var/www/html/trainee
REMOTE_DOCKER_COMPOSE_PATH=/var/www/html/trainee

# Deployment options
BACKUP_BEFORE_DEPLOY=true
RUN_MIGRATIONS=true
BUILD_ASSETS=true
RESTART_SERVICES=true

# Maintenance mode
ENABLE_MAINTENANCE_MODE=true
```

### 3. Configure Environment Variables

Edit `.env` (or create from `.env.example`) with your production settings:

```bash
# Copy example if needed
cp .env.example .env
```

**Important environment variables to configure:**

```env
# Application
APP_NAME="BITNET Trainee"
APP_ENV=production  # Change to production!
APP_DEBUG=false     # Set to false for production!
APP_URL=http://your-domain.com

# Database
DB_DATABASE=bitnet
DB_USERNAME=bitnet
DB_PASSWORD=your_secure_password_here  # Change this!
DB_ROOT_PASSWORD=your_root_password    # Change this!

# GitHub Integration
GITHUB_TOKEN=your_actual_github_token

# Service Ports (adjust if needed)
WEB_PORT=8080
DB_PORT_EXTERNAL=3306
MAILHOG_WEB_PORT=8025
```

**Security Notes:**

- The `.env` file is **NOT uploaded** during deployment (it's excluded)
- You need to manually create `.env` on the server or use a secure deployment method
- Never commit `.env` or `deploy.config` to version control

---

## Deployment

### First-Time Deployment

For the first deployment, you need to manually create the `.env` file on the server:

```bash
# Option 1: Upload .env manually
scp .env your_username@your-server.com:/var/www/html/trainee/.env

# Option 2: Create directly on server
ssh your_username@your-server.com
cd /var/www/html/trainee
nano .env  # Paste your configuration
```

### Deploy the Application

From your local machine, in the project directory:

```bash
# Make scripts executable (first time only)
chmod +x deploy.sh rollback.sh provision.sh

# Run deployment
./deploy.sh
```

### What Happens During Deployment

1. **SSH Connection Test** - Verifies server connectivity
2. **Backup Creation** - Creates timestamped backup of current state
3. **File Upload** - Syncs files to server (excludes .git, node_modules, vendor, .env)
4. **Maintenance Mode** - Enables maintenance page (if configured)
5. **Provision Script** - Runs the provision.sh on server which:
   - Builds Docker containers
   - Installs PHP dependencies
   - Installs PDF/QR packages
   - Generates application key
   - Runs database migrations
   - Seeds database
6. **Asset Building** - Compiles frontend assets (if configured)
7. **Cache Operations** - Clears and rebuilds caches
8. **Service Restart** - Restarts Docker services (if configured)
9. **Maintenance Mode Off** - Disables maintenance page
10. **Health Check** - Verifies services are running

### Expected Output

```
================================
Testing SSH Connection
================================

✓ SSH connection successful

================================
🚀 Starting Deployment to your-server.com
================================

ℹ Creating backup...
✓ Backup created: backup_20250112_143022.tar.gz
ℹ Uploading files to server...
✓ Files uploaded successfully
...
================================
✅ Deployment Completed Successfully
================================

✓ Application deployed to: your-server.com
✓ Deployment time: 20250112_143022
```

---

## Rollback

If something goes wrong, you can quickly rollback to a previous backup.

### List Available Backups

```bash
./rollback.sh
```

This will show you all available backups:

```
Available Backups
================================

 1) backup_20250112_143022.tar.gz
 2) backup_20250112_120130.tar.gz
 3) backup_20250111_183045.tar.gz

Enter the number of the backup to restore (or 'q' to quit):
```

### Rollback to Specific Backup

```bash
# Interactive mode
./rollback.sh

# Or specify timestamp directly
./rollback.sh 20250112_143022
```

### What Happens During Rollback

1. **Maintenance Mode** - Enables maintenance page
2. **Current State Backup** - Backs up current state before rollback
3. **Container Stop** - Stops Docker containers
4. **Restore Files** - Restores files from selected backup
5. **Container Start** - Starts Docker containers
6. **Migrations** - Runs database migrations (if configured)
7. **Cache Clear** - Clears application caches
8. **Maintenance Mode Off** - Disables maintenance page
9. **Health Check** - Verifies services are running

---

## Troubleshooting

### SSH Connection Failed

```bash
# Verify SSH access manually
ssh your_username@your-server.com

# Check SSH key permissions
chmod 600 ~/.ssh/id_rsa

# Test with verbose output
ssh -vvv your_username@your-server.com
```

### Docker Not Found on Server

```bash
# Connect to server
ssh your_username@your-server.com

# Verify Docker installation
docker --version
docker-compose --version

# If not installed, install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
```

### Permission Denied Errors

```bash
# On server, fix ownership
sudo chown -R $USER:$USER /var/www/html/trainee

# Fix Docker permissions
sudo usermod -aG docker $USER
# Log out and log back in
```

### Port Already in Use

Edit your `.env` file on the server and change the ports:

```env
WEB_PORT=8081  # Instead of 8080
DB_PORT_EXTERNAL=3307  # Instead of 3306
```

Then restart:

```bash
ssh your_username@your-server.com
cd /var/www/html/trainee
docker-compose down
docker-compose up -d
```

### Application Not Accessible

```bash
# Check firewall rules
sudo ufw status
sudo ufw allow 8080/tcp

# Check if containers are running
ssh your_username@your-server.com
cd /var/www/html/trainee
docker-compose ps

# Check logs
docker-compose logs app
docker-compose logs web
docker-compose logs db
```

### Database Connection Failed

```bash
# Check database logs
docker-compose logs db

# Verify database credentials in .env
cat .env | grep DB_

# Restart database container
docker-compose restart db
```

---

## Advanced Options

### Custom Deployment Configuration

You can customize deployment behavior by editing `deploy.config`:

```bash
# Disable backup before deploy (not recommended)
BACKUP_BEFORE_DEPLOY=false

# Skip migrations
RUN_MIGRATIONS=false

# Skip asset building
BUILD_ASSETS=false

# Keep application online during deployment (risky)
ENABLE_MAINTENANCE_MODE=false
```

### Notifications

Enable Slack or Discord notifications:

```bash
NOTIFY_ON_DEPLOY=true
WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

### Zero-Downtime Deployment

For zero-downtime deployments, you would need:

1. Load balancer with multiple servers
2. Blue-green deployment strategy
3. Modified deployment script

### Automated Deployments (CI/CD)

To automate deployments with GitHub Actions, create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2

      - name: Setup SSH
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa

      - name: Create deploy config
        run: |
          cat > deploy.config << EOF
          SSH_USER=${{ secrets.SSH_USER }}
          SSH_HOST=${{ secrets.SSH_HOST }}
          SSH_PORT=22
          SSH_KEY_PATH=~/.ssh/id_rsa
          REMOTE_PROJECT_PATH=/var/www/html/trainee
          BACKUP_BEFORE_DEPLOY=true
          RUN_MIGRATIONS=true
          BUILD_ASSETS=true
          RESTART_SERVICES=true
          ENABLE_MAINTENANCE_MODE=true
          EOF

      - name: Deploy
        run: ./deploy.sh
```

### Multiple Environments

Create separate config files for different environments:

```bash
# deploy.config.staging
# deploy.config.production

# Deploy to staging
cp deploy.config.staging deploy.config
./deploy.sh

# Deploy to production
cp deploy.config.production deploy.config
./deploy.sh
```

---

## Security Best Practices

1. **Never commit sensitive files:**
   - `deploy.config` (contains SSH details)
   - `.env` (contains passwords and secrets)

2. **Use strong passwords** in production `.env`

3. **Configure firewall** on server:
   ```bash
   sudo ufw enable
   sudo ufw allow 22/tcp
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw allow 8080/tcp
   ```

4. **Use HTTPS** with SSL certificates (Let's Encrypt)

5. **Regular backups** - The deployment script creates backups automatically

6. **Monitor logs** regularly:
   ```bash
   docker-compose logs -f app
   ```

---

## Maintenance Commands

### View Logs

```bash
# Connect to server
ssh your_username@your-server.com
cd /var/www/html/trainee

# View all logs
docker-compose logs

# Follow logs in real-time
docker-compose logs -f

# View specific service logs
docker-compose logs app
docker-compose logs web
docker-compose logs db
```

### Restart Services

```bash
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart app
```

### Access Container Shell

```bash
# Access app container
docker-compose exec app bash

# Access database
docker-compose exec db mysql -u bitnet -p
```

### Manual Database Backup

```bash
# Backup database
docker-compose exec db mysqldump -u bitnet -p bitnet > backup.sql

# Restore database
docker-compose exec -T db mysql -u bitnet -p bitnet < backup.sql
```

---

## Support

For issues or questions:

1. Check the [Troubleshooting](#troubleshooting) section
2. Review application logs
3. Open an issue in the repository

---

## Quick Reference

```bash
# Deploy to server
./deploy.sh

# Rollback deployment
./rollback.sh

# View available backups
./rollback.sh

# Rollback to specific backup
./rollback.sh 20250112_143022

# Check server status
ssh your_username@your-server.com "cd /var/www/html/trainee && docker-compose ps"

# View server logs
ssh your_username@your-server.com "cd /var/www/html/trainee && docker-compose logs -f"
```

---

**Last Updated:** January 2025
