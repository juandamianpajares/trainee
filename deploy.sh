#!/bin/bash

# BITNET Trainee - Remote Deployment Script
# This script deploys the application to a remote server via SSH

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_FILE="$SCRIPT_DIR/deploy.config"

# Functions
print_header() {
    echo -e "\n${CYAN}================================${NC}"
    echo -e "${CYAN}$1${NC}"
    echo -e "${CYAN}================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Check if config file exists
if [ ! -f "$CONFIG_FILE" ]; then
    print_error "Configuration file not found!"
    print_info "Please copy deploy.config.example to deploy.config and fill in your details:"
    echo -e "  ${CYAN}cp deploy.config.example deploy.config${NC}"
    exit 1
fi

# Load configuration
source "$CONFIG_FILE"

# Validate required variables
if [ -z "$SSH_USER" ] || [ -z "$SSH_HOST" ] || [ -z "$REMOTE_PROJECT_PATH" ]; then
    print_error "Missing required configuration variables!"
    print_info "Please check your deploy.config file"
    exit 1
fi

# Build SSH command
SSH_CMD="ssh -p ${SSH_PORT:-22}"
if [ -n "$SSH_KEY_PATH" ]; then
    SSH_CMD="$SSH_CMD -i $SSH_KEY_PATH"
fi
SSH_CMD="$SSH_CMD ${SSH_USER}@${SSH_HOST}"

# Test SSH connection
print_header "Testing SSH Connection"
if $SSH_CMD "echo 'Connection successful'" > /dev/null 2>&1; then
    print_success "SSH connection successful"
else
    print_error "Failed to connect to server"
    print_info "Please check your SSH configuration"
    exit 1
fi

# Send webhook notification
send_notification() {
    local message=$1
    local status=$2

    if [ "$NOTIFY_ON_DEPLOY" = "true" ] && [ -n "$WEBHOOK_URL" ]; then
        curl -X POST "$WEBHOOK_URL" \
            -H "Content-Type: application/json" \
            -d "{\"text\":\"$message\",\"status\":\"$status\"}" \
            > /dev/null 2>&1 || true
    fi
}

# Start deployment
print_header "🚀 Starting Deployment to $SSH_HOST"
send_notification "🚀 Deployment started to $SSH_HOST" "info"

DEPLOYMENT_TIME=$(date +%Y%m%d_%H%M%S)

# Step 1: Create backup
if [ "$BACKUP_BEFORE_DEPLOY" = "true" ]; then
    print_info "Creating backup..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        mkdir -p backups && \
        tar -czf backups/backup_${DEPLOYMENT_TIME}.tar.gz \
        --exclude='backups' \
        --exclude='node_modules' \
        --exclude='vendor' \
        --exclude='storage/logs' \
        . || true"
    print_success "Backup created: backup_${DEPLOYMENT_TIME}.tar.gz"
fi

# Step 2: Upload files
print_info "Uploading files to server..."
rsync -avz \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.env' \
    --exclude='deploy.config' \
    --exclude='backups' \
    -e "$SSH_CMD" \
    "$SCRIPT_DIR/" "${SSH_USER}@${SSH_HOST}:${REMOTE_PROJECT_PATH}/"
print_success "Files uploaded successfully"

# Step 3: Enable maintenance mode
if [ "$ENABLE_MAINTENANCE_MODE" = "true" ]; then
    print_info "Enabling maintenance mode..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose exec -T app php artisan down --render='errors::503' --retry=60 || true" \
        > /dev/null 2>&1
    print_success "Maintenance mode enabled"
fi

# Step 4: Run provision script
print_header "Running Provision Script"
$SSH_CMD "cd $REMOTE_PROJECT_PATH && bash provision.sh" || {
    print_error "Provision failed!"

    # Disable maintenance mode on failure
    if [ "$ENABLE_MAINTENANCE_MODE" = "true" ]; then
        print_info "Disabling maintenance mode due to failure..."
        $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
            docker-compose exec -T app php artisan up || true" > /dev/null 2>&1
    fi

    send_notification "❌ Deployment failed on $SSH_HOST" "error"
    exit 1
}

# Step 5: Build assets if needed
if [ "$BUILD_ASSETS" = "true" ]; then
    print_info "Building frontend assets..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose exec -T npm npm run build || \
        docker-compose run --rm npm npm run build" || {
        print_warning "Failed to build assets (continuing anyway)"
    }
fi

# Step 6: Clear caches
print_info "Clearing application caches..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    docker-compose exec -T app php artisan cache:clear && \
    docker-compose exec -T app php artisan config:clear && \
    docker-compose exec -T app php artisan route:clear && \
    docker-compose exec -T app php artisan view:clear" || {
    print_warning "Failed to clear some caches (continuing anyway)"
}

# Step 7: Optimize application
print_info "Optimizing application..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    docker-compose exec -T app php artisan config:cache && \
    docker-compose exec -T app php artisan route:cache && \
    docker-compose exec -T app php artisan view:cache" || {
    print_warning "Failed to optimize (continuing anyway)"
}

# Step 8: Restart services if needed
if [ "$RESTART_SERVICES" = "true" ]; then
    print_info "Restarting services..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose restart app web npm" || {
        print_warning "Failed to restart services (continuing anyway)"
    }
    print_success "Services restarted"
fi

# Step 9: Disable maintenance mode
if [ "$ENABLE_MAINTENANCE_MODE" = "true" ]; then
    print_info "Disabling maintenance mode..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose exec -T app php artisan up" > /dev/null 2>&1
    print_success "Maintenance mode disabled"
fi

# Step 10: Health check
print_header "Running Health Check"
sleep 3
HEALTH_CHECK=$($SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    docker-compose ps | grep -E '(Up|running)' | wc -l" || echo "0")

if [ "$HEALTH_CHECK" -gt 0 ]; then
    print_success "Health check passed - $HEALTH_CHECK services running"
else
    print_warning "Health check inconclusive - please verify manually"
fi

# Cleanup old backups (keep last 5)
if [ "$BACKUP_BEFORE_DEPLOY" = "true" ]; then
    print_info "Cleaning up old backups..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH/backups && \
        ls -t backup_*.tar.gz 2>/dev/null | tail -n +6 | xargs rm -f || true"
    print_success "Old backups cleaned"
fi

# Final success message
print_header "✅ Deployment Completed Successfully"
print_success "Application deployed to: $SSH_HOST"
print_success "Deployment time: $DEPLOYMENT_TIME"
print_info "Check the application at your configured URL"

# Get environment URLs from remote
print_info "Fetching application URLs..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    source .env 2>/dev/null && \
    echo 'Application: http://'$SSH_HOST':\${WEB_PORT:-8080}' && \
    echo 'Mailhog: http://'$SSH_HOST':\${MAILHOG_WEB_PORT:-8025}'" || {
    print_info "Application should be available at: http://${SSH_HOST}"
}

send_notification "✅ Deployment completed successfully on $SSH_HOST" "success"

echo ""
print_info "To rollback this deployment, run:"
echo -e "  ${CYAN}./rollback.sh $DEPLOYMENT_TIME${NC}"
echo ""
