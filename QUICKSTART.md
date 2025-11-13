# Laravel Bootcamp PRO - Guía de Inicio Rápido

## Instalación Automática (Recomendado)

```bash
# 1. Ejecutar script de setup
./setup-bootcamp.sh

# 2. Iniciar frontend (en otra terminal)
cd frontend
npm run dev

# 3. Abrir en navegador
# http://localhost:3000
```

## Instalación Manual

### Backend
```bash
# 1. Copiar configuración
cp .env.example .env

# 2. Levantar Docker
docker-compose up -d

# 3. Instalar dependencias
docker-compose exec app composer install

# 4. Configurar Laravel
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=LaravelBootcampSeeder

# 5. Crear trainee
docker-compose exec app php artisan tinker
>>> $trainee = \App\Models\Trainee::create(['name' => 'Tu Nombre', 'email' => 'tu@email.com', 'github' => 'tu-usuario', 'motivation' => 'Aprender Laravel']);
>>> echo $trainee->id; // Anota este ID
```

### Frontend
```bash
# 1. Ir al directorio frontend
cd frontend

# 2. Copiar configuración
cp .env.example .env.local

# 3. Instalar dependencias
npm install

# 4. Iniciar servidor
npm run dev

# 5. Abrir http://localhost:3000
```

## Uso Básico

### 1. Acceder al Bootcamp
1. Abre http://localhost:3000
2. Ingresa tu Trainee ID
3. Haz clic en "Comenzar Bootcamp"

### 2. Navegar por los Módulos
- El dashboard muestra todos los módulos disponibles
- Haz clic en un módulo para ver sus lecciones
- Lee el contenido y completa los ejercicios
- Realiza los quizzes al final de cada módulo

### 3. Sistema de Progreso
- **Lecciones:** Marca como completadas automáticamente
- **Ejercicios:** Envía tu código y recibe feedback
- **Quizzes:** Mínimo 70-80% para aprobar
- **Certificado:** Se genera automáticamente al 100%

## Estructura de Aprendizaje

### 📚 7 Módulos
1. **Fundamentos** (20h) - Laravel básico, MVC, routing
2. **Base de Datos** (25h) - Eloquent, migraciones, optimización
3. **Seguridad** (18h) - Autenticación, autorización, roles
4. **APIs** (22h) - RESTful, validación, recursos
5. **Testing** (20h) - Unit tests, TDD, cobertura
6. **Performance** (18h) - Caching, queues, optimización
7. **Proyecto Final** (40h) - ERP completo con 3 roles

### 🎯 Total: 143 horas de contenido

## Comandos Útiles

### Backend
```bash
# Ver logs
docker-compose logs -f app

# Acceder al contenedor
docker-compose exec app bash

# Ejecutar tests
docker-compose exec app php artisan test

# Resetear base de datos
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan db:seed --class=LaravelBootcampSeeder

# Ver rutas API
docker-compose exec app php artisan route:list --path=api/bootcamp
```

### Frontend
```bash
# Modo desarrollo
npm run dev

# Build producción
npm run build
npm start

# Linting
npm run lint

# Type checking
npm run type-check
```

## API Endpoints Principales

```bash
# Listar módulos
curl http://localhost:8080/api/bootcamp/modules

# Ver módulo específico
curl http://localhost:8080/api/bootcamp/modules/1

# Dashboard del trainee
curl http://localhost:8080/api/trainees/1/dashboard

# Completar lección
curl -X POST http://localhost:8080/api/bootcamp/lessons/1/complete \
  -H "Content-Type: application/json" \
  -d '{"trainee_id": 1, "time_spent_minutes": 45}'

# Enviar ejercicio
curl -X POST http://localhost:8080/api/bootcamp/exercises/1/submit \
  -H "Content-Type: application/json" \
  -d '{"trainee_id": 1, "submitted_code": "<?php ..."}'

# Iniciar quiz
curl -X POST http://localhost:8080/api/bootcamp/quizzes/1/start \
  -H "Content-Type: application/json" \
  -d '{"trainee_id": 1}'
```

## Problemas Comunes

### Backend no responde
```bash
docker-compose restart
docker-compose logs app
```

### Error de base de datos
```bash
docker-compose down -v
docker-compose up -d
# Esperar 10 segundos
docker-compose exec app php artisan migrate --seed
```

### Frontend no conecta
1. Verificar `.env.local` tiene `NEXT_PUBLIC_API_URL=http://localhost:8080/api`
2. Verificar backend está corriendo en puerto 8080
3. Limpiar cache del navegador

### Puerto ocupado
```bash
# Cambiar puertos en docker-compose.yml
# Por ejemplo: 8080 -> 8081
```

## Recursos

- **Documentación completa:** Ver `BOOTCAMP_README.md`
- **Laravel Docs:** https://laravel.com/docs/11.x
- **Next.js Docs:** https://nextjs.org/docs

## Soporte

Si encuentras problemas:
1. Revisa los logs: `docker-compose logs`
2. Consulta la documentación completa
3. Abre un issue en el repositorio

---

**¡Comienza tu viaje para convertirte en un desarrollador Laravel profesional!** 🚀
