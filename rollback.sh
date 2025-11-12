#!/bin/bash

# BITNET Trainee - Rollback Script
# This script rolls back to a previous backup

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
    exit 1
fi

# List available backups
print_header "Available Backups"
BACKUPS=$($SSH_CMD "cd $REMOTE_PROJECT_PATH/backups 2>/dev/null && ls -t backup_*.tar.gz 2>/dev/null || echo ''")

if [ -z "$BACKUPS" ]; then
    print_error "No backups found on server!"
    exit 1
fi

echo "$BACKUPS" | nl -w2 -s') '

# Get backup to restore
BACKUP_TO_RESTORE=""

if [ -n "$1" ]; then
    # Backup timestamp provided as argument
    BACKUP_TO_RESTORE="backup_$1.tar.gz"

    if ! echo "$BACKUPS" | grep -q "$BACKUP_TO_RESTORE"; then
        print_error "Backup not found: $BACKUP_TO_RESTORE"
        exit 1
    fi
else
    # Interactive selection
    echo ""
    read -p "Enter the number of the backup to restore (or 'q' to quit): " selection

    if [ "$selection" = "q" ]; then
        print_info "Rollback cancelled"
        exit 0
    fi

    BACKUP_TO_RESTORE=$(echo "$BACKUPS" | sed -n "${selection}p")

    if [ -z "$BACKUP_TO_RESTORE" ]; then
        print_error "Invalid selection"
        exit 1
    fi
fi

# Confirmation
print_warning "You are about to rollback to: $BACKUP_TO_RESTORE"
read -p "Are you sure you want to continue? (yes/no): " confirmation

if [ "$confirmation" != "yes" ]; then
    print_info "Rollback cancelled"
    exit 0
fi

print_header "🔄 Starting Rollback"

# Step 1: Enable maintenance mode
if [ "$ENABLE_MAINTENANCE_MODE" = "true" ]; then
    print_info "Enabling maintenance mode..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose exec -T app php artisan down --render='errors::503' --retry=60 || true" \
        > /dev/null 2>&1
    print_success "Maintenance mode enabled"
fi

# Step 2: Create a backup of current state before rollback
print_info "Creating backup of current state..."
ROLLBACK_TIME=$(date +%Y%m%d_%H%M%S)
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    tar -czf backups/before_rollback_${ROLLBACK_TIME}.tar.gz \
    --exclude='backups' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs' \
    . || true"
print_success "Current state backed up as: before_rollback_${ROLLBACK_TIME}.tar.gz"

# Step 3: Stop containers
print_info "Stopping Docker containers..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && docker-compose down" || {
    print_warning "Failed to stop containers gracefully"
}

# Step 4: Restore backup
print_info "Restoring backup: $BACKUP_TO_RESTORE"
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    rm -rf app database docker resources routes 2>/dev/null || true && \
    tar -xzf backups/$BACKUP_TO_RESTORE" || {
    print_error "Failed to restore backup!"

    # Try to restore from pre-rollback backup
    print_warning "Attempting to restore from pre-rollback backup..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        tar -xzf backups/before_rollback_${ROLLBACK_TIME}.tar.gz" || {
        print_error "Failed to restore pre-rollback backup! Manual intervention required!"
        exit 1
    }

    exit 1
}
print_success "Backup restored successfully"

# Step 5: Restart containers
print_info "Starting Docker containers..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && docker-compose up -d" || {
    print_error "Failed to start containers!"
    exit 1
}
print_success "Containers started"

# Step 6: Wait for services to be ready
print_info "Waiting for services to be ready..."
sleep 10

# Step 7: Run migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    print_info "Running database migrations..."
    $SSH_CMD "cd $REMOTE_PROJECT_PATH && \
        docker-compose exec -T app php artisan migrate --force" || {
        print_warning "Failed to run migrations (this might be expected for older backups)"
    }
fi

# Step 8: Clear caches
print_info "Clearing caches..."
$SSH_CMD "cd $REMOTE_PROJECT_PATH && \
    docker-compose exec -T app php artisan cache:clear && \
    docker-compose exec -T app php artisan config:clear && \
    docker-compose exec -T app php artisan route:clear && \
    docker-compose exec -T app php artisan view:clear" || {
    print_warning "Failed to clear some caches"
}

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

# Final success message
print_header "✅ Rollback Completed Successfully"
print_success "Application rolled back to: $BACKUP_TO_RESTORE"
print_info "Previous state saved as: before_rollback_${ROLLBACK_TIME}.tar.gz"
echo ""
print_info "To revert this rollback, run:"
echo -e "  ${CYAN}./rollback.sh ${ROLLBACK_TIME}${NC}"
echo ""
