#!/bin/bash

# Laravel Bootcamp PRO - Script de Instalación Automática
# Este script configura todo el entorno del bootcamp

set -e

echo "=================================="
echo "Laravel Bootcamp PRO - Setup"
echo "=================================="
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir con color
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}➜ $1${NC}"
}

# Verificar requisitos
print_info "Verificando requisitos del sistema..."

if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose no está instalado"
    exit 1
fi

if ! command -v node &> /dev/null; then
    print_error "Node.js no está instalado"
    exit 1
fi

print_success "Todos los requisitos están instalados"
echo ""

# Configurar Backend
print_info "Configurando Backend Laravel..."

if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Archivo .env creado"
else
    print_info "Archivo .env ya existe"
fi

# Levantar contenedores
print_info "Levantando contenedores Docker..."
docker-compose up -d

# Esperar a que la base de datos esté lista
print_info "Esperando a que la base de datos esté lista..."
sleep 10

# Instalar dependencias de Composer
print_info "Instalando dependencias de Composer..."
docker-compose exec -T app composer install

# Generar app key
print_info "Generando application key..."
docker-compose exec -T app php artisan key:generate

# Ejecutar migraciones
print_info "Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate --force

# Ejecutar seeder del bootcamp
print_info "Cargando contenido del bootcamp..."
docker-compose exec -T app php artisan db:seed --class=LaravelBootcampSeeder --force

# Crear un trainee de ejemplo
print_info "Creando trainee de ejemplo..."
docker-compose exec -T app php artisan tinker --execute="
\$trainee = \App\Models\Trainee::firstOrCreate(
    ['email' => 'demo@bootcamp.local'],
    [
        'name' => 'Demo User',
        'github' => 'demo',
        'motivation' => 'Learning Laravel with Bootcamp PRO'
    ]
);
echo 'Trainee ID: ' . \$trainee->id . PHP_EOL;
echo 'Email: ' . \$trainee->email . PHP_EOL;
" > trainee_info.txt

TRAINEE_ID=$(grep "Trainee ID:" trainee_info.txt | cut -d':' -f2 | tr -d ' ')

print_success "Backend configurado correctamente"
echo ""

# Configurar Frontend
print_info "Configurando Frontend Next.js..."

cd frontend

if [ ! -f .env.local ]; then
    cp .env.example .env.local
    print_success "Archivo .env.local creado"
else
    print_info "Archivo .env.local ya existe"
fi

# Instalar dependencias de npm
print_info "Instalando dependencias de npm..."
npm install

print_success "Frontend configurado correctamente"
echo ""

# Resumen
cd ..
echo "=================================="
echo "✓ Instalación Completada"
echo "=================================="
echo ""
echo "Backend Laravel:"
echo "  URL: http://localhost:8080"
echo "  API: http://localhost:8080/api/bootcamp/modules"
echo ""
echo "Frontend Next.js:"
echo "  URL: http://localhost:3000"
echo ""
echo "Base de datos:"
echo "  Host: localhost:3306"
echo "  Database: trainee"
echo "  User: trainee"
echo "  Password: secret"
echo ""
echo "MailHog (Email testing):"
echo "  URL: http://localhost:8025"
echo ""
echo "Trainee de prueba:"
cat trainee_info.txt
echo ""
echo "Para iniciar el frontend, ejecuta:"
echo "  cd frontend && npm run dev"
echo ""
echo "Para ver logs del backend:"
echo "  docker-compose logs -f app"
echo ""
echo "¡Disfruta del Laravel Bootcamp PRO!"
