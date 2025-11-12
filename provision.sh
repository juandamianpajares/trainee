#!/bin/bash
set -e
echo "🚀 Provisioning BITNET Trainee CERTIFIED..."
cp .env.example .env || true
docker-compose up -d --build
echo "Waiting for containers..."
sleep 10
# Install composer deps including dompdf and qrcode packages
docker-compose exec -T app bash -lc "composer require barryvdh/laravel-dompdf:^1.0 simplesoftwareio/simple-qrcode --no-interaction || true"
docker-compose exec -T app bash -lc "composer install --no-interaction || true"
# Publish dompdf config (optional)
docker-compose exec -T app bash -lc "php artisan vendor:publish --provider='Barryvdh\\DomPDF\\ServiceProvider' || true"
docker-compose exec -T app bash -lc "php artisan key:generate || true"
docker-compose exec -T app bash -lc "php artisan migrate --force || true"
docker-compose exec -T app bash -lc "php artisan db:seed --class=DemoSeeder || true"
echo "✅ Ready: http://localhost:8080 (Mailhog: http://localhost:8025)"
