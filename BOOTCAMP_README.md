# Laravel Bootcamp PRO - Sistema de Capacitación ERP

## Descripción General

Este es un **bootcamp completo de Laravel** diseñado para enseñar desarrollo profesional de aplicaciones empresariales (ERP) desde cero hasta nivel avanzado. El sistema incluye:

- **7 módulos progresivos** de aprendizaje
- **20+ lecciones** con contenido teórico y práctico
- **15+ ejercicios** interactivos con evaluación automática
- **7 quizzes** de evaluación
- **Sistema de certificación** automático
- **Tracking de progreso** sincronizado con backend
- **Frontend Next.js** moderno y responsive

## Estructura del Bootcamp

### Módulo 1: Fundamentos de Laravel y PHP 8.2+ (20h)
- Arquitectura MVC y ciclo de vida
- Sistema de routing avanzado
- Controllers y middleware
- Service Container y Dependency Injection

### Módulo 2: Base de Datos y Eloquent ORM (25h)
- Migraciones y Schema Builder
- Eloquent ORM y relaciones
- Query Builder y optimización
- Problema N+1 y eager loading

### Módulo 3: Autenticación, Autorización y Seguridad (18h)
- Laravel Sanctum
- Gates, Policies y roles
- Prevención de vulnerabilidades
- Multi-factor authentication

### Módulo 4: APIs RESTful y Validación (22h)
- Diseño de APIs RESTful
- Form Requests avanzados
- API Resources y transformación
- Versionado y documentación

### Módulo 5: Testing y Quality Assurance (20h)
- Unit testing con PHPUnit
- Feature tests
- Test-Driven Development (TDD)
- Cobertura de código

### Módulo 6: Performance y Optimización (18h)
- Caching strategies (Redis, Memcached)
- Queues y jobs asíncronos
- Rate limiting
- Monitorización de performance

### Módulo 7: Proyecto Final - ERP Completo (40h)
- Implementación completa del ERP
- 3 roles: Gerente, Empleado, Cliente
- Módulos: Proyectos, Tareas, Clientes, Facturación
- Deploy a producción

## Instalación

### Requisitos Previos
- Docker y Docker Compose
- Node.js 20+
- Git

### Backend Laravel

1. **Clonar el repositorio y navegar al directorio**
```bash
cd trainee
```

2. **Copiar archivo de configuración**
```bash
cp .env.example .env
```

3. **Configurar variables de entorno en `.env`**
```env
APP_NAME="Laravel Bootcamp PRO"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=trainee
DB_USERNAME=trainee
DB_PASSWORD=secret

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

4. **Levantar los contenedores Docker**
```bash
docker-compose up -d
```

5. **Instalar dependencias dentro del contenedor**
```bash
docker-compose exec app composer install
```

6. **Generar key de aplicación**
```bash
docker-compose exec app php artisan key:generate
```

7. **Ejecutar migraciones**
```bash
docker-compose exec app php artisan migrate
```

8. **Ejecutar seeder del bootcamp**
```bash
docker-compose exec app php artisan db:seed --class=LaravelBootcampSeeder
```

9. **Verificar que el backend esté funcionando**
```bash
curl http://localhost:8080/api/bootcamp/modules
```

### Frontend Next.js

1. **Navegar al directorio frontend**
```bash
cd frontend
```

2. **Copiar archivo de configuración**
```bash
cp .env.example .env.local
```

3. **Configurar variables de entorno en `.env.local`**
```env
NEXT_PUBLIC_API_URL=http://localhost:8080/api
```

4. **Instalar dependencias**
```bash
npm install
```

5. **Iniciar servidor de desarrollo**
```bash
npm run dev
```

6. **Abrir en el navegador**
```
http://localhost:3000
```

## Uso del Sistema

### 1. Registro de Trainee

Primero necesitas crear un trainee en el sistema Laravel:

```bash
# Acceder al contenedor
docker-compose exec app php artisan tinker

# Crear trainee
$trainee = \App\Models\Trainee::create([
    'name' => 'Juan Pérez',
    'email' => 'juan@example.com',
    'github' => 'juanperez',
    'motivation' => 'Aprender Laravel profesionalmente'
]);

# Anotar el ID del trainee
echo $trainee->id;
```

### 2. Acceder al Bootcamp

1. Abre el frontend en `http://localhost:3000`
2. Ingresa tu Trainee ID
3. Haz clic en "Comenzar Bootcamp"

### 3. Navegación por el Bootcamp

**Dashboard Principal:**
- Visualiza tu progreso general
- Ve estadísticas de módulos completados
- Accede a cada módulo

**Dentro de un Módulo:**
- Lee las lecciones teóricas
- Completa los ejercicios prácticos
- Realiza los quizzes de evaluación
- El progreso se sincroniza automáticamente

**Sistema de Progreso:**
- Cada lección completada suma al progreso del módulo
- Los ejercicios correctos suman puntos adicionales
- Debes pasar el quiz del módulo (70-80% mínimo)
- Al completar el 100%, se genera automáticamente tu certificado

## Arquitectura del Sistema

### Backend (Laravel 11)

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── ModuleController.php      # CRUD de módulos
│           ├── LessonController.php      # Gestión de lecciones
│           ├── ExerciseController.php    # Evaluación de ejercicios
│           └── QuizController.php        # Sistema de quizzes
├── Models/
│   ├── Module.php                        # Módulo del bootcamp
│   ├── Lesson.php                        # Lección con contenido
│   ├── Exercise.php                      # Ejercicio práctico
│   ├── Quiz.php                          # Quiz de evaluación
│   ├── QuizQuestion.php                  # Pregunta de quiz
│   ├── LessonProgress.php                # Progreso por lección
│   ├── ModuleProgress.php                # Progreso por módulo
│   ├── ExerciseSubmission.php            # Submission de ejercicio
│   ├── QuizAttempt.php                   # Intento de quiz
│   ├── Trainee.php                       # Usuario del bootcamp
│   └── Certificate.php                   # Certificado generado
database/
├── migrations/
│   ├── 2025_11_13_110000_create_bootcamp_structure.php
│   └── 2025_11_13_120000_create_erp_structure.php
└── seeders/
    └── LaravelBootcampSeeder.php         # Contenido completo
```

### Frontend (Next.js 14)

```
frontend/
├── app/
│   ├── page.tsx                          # Landing page
│   ├── dashboard/
│   │   └── page.tsx                      # Dashboard principal
│   ├── modules/
│   │   └── [id]/
│   │       └── page.tsx                  # Vista de módulo
│   └── lessons/
│       └── [id]/
│           └── page.tsx                  # Vista de lección
├── components/
│   ├── ModuleCard.tsx
│   ├── LessonContent.tsx
│   ├── ExerciseEditor.tsx
│   └── QuizComponent.tsx
└── lib/
    └── api.ts                            # Cliente API con tipos
```

## API Endpoints

### Módulos
```
GET    /api/bootcamp/modules              # Listar todos los módulos
GET    /api/bootcamp/modules/{id}         # Detalle de módulo
GET    /api/bootcamp/modules/{id}/progress # Progreso del trainee
```

### Lecciones
```
GET    /api/bootcamp/lessons/{id}         # Detalle de lección
POST   /api/bootcamp/lessons/{id}/start   # Iniciar lección
POST   /api/bootcamp/lessons/{id}/complete # Completar lección
GET    /api/bootcamp/lessons/{id}/progress # Progreso de lección
```

### Ejercicios
```
GET    /api/bootcamp/exercises/{id}       # Detalle de ejercicio
POST   /api/bootcamp/exercises/{id}/submit # Enviar solución
GET    /api/bootcamp/exercises/{id}/submissions # Historial
```

### Quizzes
```
GET    /api/bootcamp/quizzes/{id}         # Detalle de quiz
POST   /api/bootcamp/quizzes/{id}/start   # Iniciar intento
POST   /api/bootcamp/quizzes/{id}/submit  # Enviar respuestas
GET    /api/bootcamp/quizzes/{id}/attempts # Historial de intentos
```

### Dashboard
```
GET    /api/trainees/{id}/dashboard       # Estadísticas completas
```

## Sistema de Certificación

### Criterios para Certificación

El trainee recibirá automáticamente su certificado cuando:

1. **Progreso general ≥ 100%**
2. Cada módulo se considera completo cuando:
   - 40% Lecciones completadas
   - 40% Ejercicios resueltos correctamente
   - 20% Quiz aprobado (≥70-80% según módulo)

### Generación Automática

El certificado se genera automáticamente en el modelo `Trainee`:

```php
public function updateOverallProgress(): void
{
    $overallProgress = $this->getOverallProgress();
    $this->update(['progress' => $overallProgress]);

    // Certificación automática
    if ($overallProgress >= 100 && !$this->certificate_issued_at) {
        $this->generateCertificate();
    }
}
```

### Descarga de Certificado

```php
Route::get('/certificate/download/{id}',
    [CertificateController::class, 'generate']
)->name('certificate.download');
```

## Personalización del Contenido

### Agregar un Nuevo Módulo

Edita `database/seeders/LaravelBootcampSeeder.php`:

```php
$module8 = Module::create([
    'title' => 'Tu Nuevo Módulo',
    'description' => 'Descripción del módulo',
    'order' => 8,
    'difficulty' => 'advanced',
    'estimated_hours' => 15,
    'learning_objectives' => [
        'Objetivo 1',
        'Objetivo 2'
    ]
]);
```

### Agregar Lecciones

```php
Lesson::create([
    'module_id' => $module8->id,
    'title' => 'Nueva Lección',
    'content' => $this->getContentMarkdown(),
    'type' => 'theory',
    'order' => 1,
    'estimated_minutes' => 60
]);
```

### Agregar Ejercicios

```php
Exercise::create([
    'lesson_id' => $lesson->id,
    'title' => 'Nuevo Ejercicio',
    'instructions' => 'Instrucciones en Markdown',
    'test_cases' => [
        ['pattern' => '/Route::/', 'description' => 'Debe usar Route']
    ],
    'points' => 20
]);
```

## Troubleshooting

### Backend no responde

```bash
# Ver logs
docker-compose logs app

# Reiniciar contenedores
docker-compose restart

# Verificar estado
docker-compose ps
```

### Errores de base de datos

```bash
# Resetear base de datos
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan db:seed --class=LaravelBootcampSeeder
```

### Frontend no conecta con API

1. Verificar que `.env.local` tenga la URL correcta
2. Verificar CORS en Laravel (`config/cors.php`)
3. Revisar logs del navegador (F12)

### Problemas con Docker

```bash
# Limpiar todo y empezar de nuevo
docker-compose down -v
docker-compose up -d --build
```

## Desarrollo y Contribución

### Agregar Nuevos Features

1. **Backend:** Crea migraciones, modelos y controllers
2. **API:** Agrega rutas en `routes/api.php`
3. **Frontend:** Crea componentes en `frontend/components`
4. **Testing:** Escribe tests en `tests/Feature`

### Estructura de Testing

```bash
# Ejecutar tests
docker-compose exec app php artisan test

# Con coverage
docker-compose exec app php artisan test --coverage
```

## Roadmap

### Versión 1.1 (Próxima)
- [ ] Editor de código con syntax highlighting
- [ ] Sandbox para ejecutar código PHP
- [ ] Sistema de badges y logros
- [ ] Foro de discusión por módulo

### Versión 2.0 (Futuro)
- [ ] Bootcamps adicionales (Vue.js, React, etc.)
- [ ] Sistema de mentores
- [ ] Evaluación por pares
- [ ] Certificación con blockchain

## Soporte y Documentación

- **Documentación Laravel:** https://laravel.com/docs/11.x
- **Documentación Next.js:** https://nextjs.org/docs
- **Issues:** Reporta problemas en el repositorio de GitHub

## Licencia

MIT License - Libre para uso educativo y comercial

---

**Generado con rigor científico y metodología profesional para la formación de desarrolladores Laravel de nivel empresarial.**
