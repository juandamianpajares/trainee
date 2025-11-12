#!/bin/bash
set -e
echo "🚀 Provisioning BITNET Trainee PRO..."
cp .env.example .env || true
docker-compose up -d --build
echo "Waiting for containers to initialize..."
sleep 8
docker-compose exec -T app bash -lc "composer install --no-interaction || true"
docker-compose exec -T app bash -lc "php artisan key:generate || true"
docker-compose exec -T app bash -lc "php artisan migrate --force || true"
docker-compose exec -T app bash -lc "php artisan db:seed --class=DemoSeeder || true"
echo "✅ Ready: http://localhost:8080 (Mailhog: http://localhost:8025)"
