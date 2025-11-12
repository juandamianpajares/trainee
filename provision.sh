#!/bin/bash
set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Provisioning BITNET Trainee CERTIFIED...${NC}"

# Check if .env exists, if not create from .env.example
if [ ! -f .env ]; then
    echo -e "${YELLOW}📝 Creating .env file from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${YELLOW}⚠️  .env file already exists, skipping...${NC}"
fi

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

echo -e "${GREEN}🐳 Starting Docker containers...${NC}"
docker-compose up -d --build

echo -e "${YELLOW}⏳ Waiting for containers to be ready...${NC}"
sleep 10

echo -e "${GREEN}📦 Installing Composer dependencies...${NC}"
docker-compose exec -T app bash -c "composer install --no-interaction --prefer-dist --optimize-autoloader" || {
    echo -e "${RED}❌ Failed to install composer dependencies${NC}"
    exit 1
}

echo -e "${GREEN}📦 Installing PDF and QR code packages...${NC}"
docker-compose exec -T app bash -c "composer require barryvdh/laravel-dompdf:^1.0 simplesoftwareio/simple-qrcode --no-interaction" || {
    echo -e "${YELLOW}⚠️  Warning: Failed to install PDF/QR packages${NC}"
}

echo -e "${GREEN}📄 Publishing vendor configurations...${NC}"
docker-compose exec -T app bash -c "php artisan vendor:publish --provider='Barryvdh\\DomPDF\\ServiceProvider' --force" || {
    echo -e "${YELLOW}⚠️  Warning: Failed to publish DomPDF config${NC}"
}

echo -e "${GREEN}🔑 Generating application key...${NC}"
docker-compose exec -T app bash -c "php artisan key:generate --force" || {
    echo -e "${RED}❌ Failed to generate application key${NC}"
    exit 1
}

echo -e "${GREEN}🗄️  Running database migrations...${NC}"
docker-compose exec -T app bash -c "php artisan migrate --force" || {
    echo -e "${RED}❌ Failed to run migrations${NC}"
    exit 1
}

echo -e "${GREEN}🌱 Seeding database...${NC}"
docker-compose exec -T app bash -c "php artisan db:seed --class=DemoSeeder --force" || {
    echo -e "${YELLOW}⚠️  Warning: Failed to seed database${NC}"
}

echo ""
echo -e "${GREEN}✅ Provisioning completed successfully!${NC}"
echo ""
echo -e "${GREEN}🌐 Application: ${NC}http://localhost:${WEB_PORT:-8080}"
echo -e "${GREEN}📧 Mailhog UI:  ${NC}http://localhost:${MAILHOG_WEB_PORT:-8025}"
echo -e "${GREEN}🗄️  Database:    ${NC}localhost:${DB_PORT_EXTERNAL:-3306}"
echo ""
