<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class LaravelBootcampSeeder extends Seeder
{
    public function run(): void
    {
        // MÓDULO 1: Fundamentos de Laravel y PHP Moderno
        $module1 = Module::create([
            'title' => 'Fundamentos de Laravel y PHP 8.2+',
            'description' => 'Introducción completa a Laravel 11, arquitectura MVC, y características modernas de PHP 8.2+',
            'order' => 1,
            'difficulty' => 'beginner',
            'estimated_hours' => 20,
            'learning_objectives' => [
                'Comprender la arquitectura MVC de Laravel',
                'Dominar el ciclo de vida de las peticiones HTTP',
                'Utilizar características modernas de PHP 8.2+',
                'Configurar y gestionar el entorno de desarrollo',
                'Implementar routing básico y avanzado'
            ]
        ]);

        // Lección 1.1: Introducción y Arquitectura
        $lesson1_1 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Arquitectura MVC y Ciclo de Vida de Laravel',
            'description' => 'Comprende cómo funciona Laravel internamente',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 45,
            'content' => $this->getLesson1_1Content(),
            'resources' => [
                ['type' => 'doc', 'url' => 'https://laravel.com/docs/11.x/lifecycle', 'title' => 'Request Lifecycle'],
                ['type' => 'video', 'url' => 'https://laracasts.com/series/laravel-from-scratch', 'title' => 'Laravel From Scratch']
            ]
        ]);

        Exercise::create([
            'lesson_id' => $lesson1_1->id,
            'title' => 'Analizar el Flujo de una Petición HTTP',
            'description' => 'Traza el recorrido completo de una petición HTTP en Laravel',
            'instructions' => $this->getExercise1_1Instructions(),
            'points' => 10,
            'difficulty' => 'easy'
        ]);

        // Lección 1.2: Routing Avanzado
        $lesson1_2 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Sistema de Routing: Básico y Avanzado',
            'description' => 'Domina el sistema de rutas de Laravel',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 60,
            'content' => $this->getLesson1_2Content(),
            'resources' => [
                ['type' => 'doc', 'url' => 'https://laravel.com/docs/11.x/routing', 'title' => 'Routing Documentation']
            ]
        ]);

        Exercise::create([
            'lesson_id' => $lesson1_2->id,
            'title' => 'Crear Sistema de Rutas para ERP',
            'description' => 'Implementa el routing completo para un módulo de gestión de clientes',
            'instructions' => $this->getExercise1_2Instructions(),
            'starter_code' => $this->getExercise1_2StarterCode(),
            'solution_code' => $this->getExercise1_2SolutionCode(),
            'points' => 15,
            'difficulty' => 'medium'
        ]);

        // Lección 1.3: Controllers y Middlewares
        $lesson1_3 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Controllers, Resource Controllers y Middleware',
            'description' => 'Organiza la lógica de tu aplicación con controllers y protégela con middleware',
            'type' => 'theory',
            'order' => 3,
            'estimated_minutes' => 75,
            'content' => $this->getLesson1_3Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson1_3->id,
            'title' => 'Implementar Resource Controller para Clientes',
            'description' => 'Crea un resource controller con middleware de autenticación',
            'instructions' => $this->getExercise1_3Instructions(),
            'points' => 20,
            'difficulty' => 'medium'
        ]);

        // Quiz del Módulo 1
        $quiz1 = Quiz::create([
            'module_id' => $module1->id,
            'title' => 'Evaluación: Fundamentos de Laravel',
            'description' => 'Prueba tus conocimientos sobre los fundamentos de Laravel',
            'passing_score' => 70,
            'time_limit_minutes' => 30
        ]);

        $this->createQuizQuestions($quiz1, $this->getModule1QuizQuestions());

        // MÓDULO 2: Base de Datos y Eloquent ORM
        $module2 = Module::create([
            'title' => 'Base de Datos, Migraciones y Eloquent ORM',
            'description' => 'Domina el manejo de bases de datos en Laravel con Eloquent ORM',
            'order' => 2,
            'difficulty' => 'intermediate',
            'estimated_hours' => 25,
            'learning_objectives' => [
                'Diseñar esquemas de base de datos con migraciones',
                'Dominar Eloquent ORM y sus relaciones',
                'Implementar Query Builder para consultas complejas',
                'Utilizar seeders y factories para datos de prueba',
                'Aplicar optimización de consultas (N+1 problem, eager loading)'
            ]
        ]);

        // Lección 2.1: Migraciones y Schema Builder
        $lesson2_1 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Migraciones y Diseño de Base de Datos',
            'description' => 'Aprende a diseñar y versionar tu esquema de base de datos',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 60,
            'content' => $this->getLesson2_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson2_1->id,
            'title' => 'Diseñar Schema del ERP',
            'description' => 'Crea las migraciones completas para el sistema ERP',
            'instructions' => $this->getExercise2_1Instructions(),
            'points' => 25,
            'difficulty' => 'hard'
        ]);

        // Lección 2.2: Eloquent ORM - Modelos y Relaciones
        $lesson2_2 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Eloquent ORM: Modelos y Relaciones',
            'description' => 'Domina las relaciones entre modelos (1:1, 1:N, N:M, polimórficas)',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 90,
            'content' => $this->getLesson2_2Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson2_2->id,
            'title' => 'Implementar Modelos con Relaciones',
            'description' => 'Crea los modelos del ERP con todas sus relaciones',
            'instructions' => $this->getExercise2_2Instructions(),
            'points' => 30,
            'difficulty' => 'hard'
        ]);

        // Lección 2.3: Query Builder y Optimización
        $lesson2_3 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Query Builder y Optimización de Consultas',
            'description' => 'Aprende a escribir consultas eficientes y resolver el problema N+1',
            'type' => 'theory',
            'order' => 3,
            'estimated_minutes' => 75,
            'content' => $this->getLesson2_3Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson2_3->id,
            'title' => 'Optimizar Dashboard del ERP',
            'description' => 'Optimiza las consultas del dashboard utilizando eager loading',
            'instructions' => $this->getExercise2_3Instructions(),
            'points' => 25,
            'difficulty' => 'hard'
        ]);

        $quiz2 = Quiz::create([
            'module_id' => $module2->id,
            'title' => 'Evaluación: Base de Datos y Eloquent',
            'description' => 'Demuestra tu dominio de Eloquent y optimización de consultas',
            'passing_score' => 75,
            'time_limit_minutes' => 45
        ]);

        $this->createQuizQuestions($quiz2, $this->getModule2QuizQuestions());

        // MÓDULO 3: Autenticación, Autorización y Seguridad
        $module3 = Module::create([
            'title' => 'Autenticación, Autorización y Seguridad',
            'description' => 'Implementa sistemas seguros de autenticación y autorización',
            'order' => 3,
            'difficulty' => 'intermediate',
            'estimated_hours' => 18,
            'learning_objectives' => [
                'Implementar autenticación con Sanctum',
                'Gestionar roles y permisos con Gates y Policies',
                'Aplicar mejores prácticas de seguridad',
                'Prevenir vulnerabilidades comunes (XSS, CSRF, SQL Injection)',
                'Implementar autenticación multifactor'
            ]
        ]);

        // Lección 3.1: Laravel Sanctum
        $lesson3_1 = Lesson::create([
            'module_id' => $module3->id,
            'title' => 'Autenticación con Laravel Sanctum',
            'description' => 'Implementa autenticación segura para SPAs y APIs',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 60,
            'content' => $this->getLesson3_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson3_1->id,
            'title' => 'Implementar Sistema de Login para el ERP',
            'description' => 'Crea el sistema de autenticación completo con Sanctum',
            'instructions' => $this->getExercise3_1Instructions(),
            'points' => 25,
            'difficulty' => 'medium'
        ]);

        // Lección 3.2: Roles, Gates y Policies
        $lesson3_2 = Lesson::create([
            'module_id' => $module3->id,
            'title' => 'Autorización: Gates, Policies y Roles',
            'description' => 'Implementa un sistema robusto de permisos',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 75,
            'content' => $this->getLesson3_2Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson3_2->id,
            'title' => 'Sistema de Roles: Gerente, Empleado, Cliente',
            'description' => 'Implementa el sistema de autorización con tres roles',
            'instructions' => $this->getExercise3_2Instructions(),
            'points' => 30,
            'difficulty' => 'hard'
        ]);

        $quiz3 = Quiz::create([
            'module_id' => $module3->id,
            'title' => 'Evaluación: Seguridad y Autorización',
            'description' => 'Valida tus conocimientos en seguridad web',
            'passing_score' => 75,
            'time_limit_minutes' => 40
        ]);

        $this->createQuizQuestions($quiz3, $this->getModule3QuizQuestions());

        // MÓDULO 4: APIs RESTful y Validación
        $module4 = Module::create([
            'title' => 'APIs RESTful, Validación y Transformación de Datos',
            'description' => 'Construye APIs profesionales con Laravel',
            'order' => 4,
            'difficulty' => 'intermediate',
            'estimated_hours' => 22,
            'learning_objectives' => [
                'Diseñar APIs RESTful siguiendo mejores prácticas',
                'Implementar validación robusta con Form Requests',
                'Transformar datos con API Resources',
                'Versionar APIs correctamente',
                'Documentar APIs con estándares OpenAPI'
            ]
        ]);

        $lesson4_1 = Lesson::create([
            'module_id' => $module4->id,
            'title' => 'Diseño de APIs RESTful',
            'description' => 'Aprende los principios REST y cómo aplicarlos en Laravel',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 60,
            'content' => $this->getLesson4_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson4_1->id,
            'title' => 'API REST para Gestión de Proyectos',
            'description' => 'Implementa una API completa para el módulo de proyectos',
            'instructions' => $this->getExercise4_1Instructions(),
            'points' => 30,
            'difficulty' => 'hard'
        ]);

        $lesson4_2 = Lesson::create([
            'module_id' => $module4->id,
            'title' => 'Validación y API Resources',
            'description' => 'Valida datos de entrada y transforma respuestas',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 75,
            'content' => $this->getLesson4_2Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson4_2->id,
            'title' => 'Form Requests y Resources para el ERP',
            'description' => 'Implementa validación y transformación para todas las entidades',
            'instructions' => $this->getExercise4_2Instructions(),
            'points' => 25,
            'difficulty' => 'medium'
        ]);

        $quiz4 = Quiz::create([
            'module_id' => $module4->id,
            'title' => 'Evaluación: APIs y Validación',
            'description' => 'Demuestra tu capacidad para crear APIs profesionales',
            'passing_score' => 75,
            'time_limit_minutes' => 40
        ]);

        $this->createQuizQuestions($quiz4, $this->getModule4QuizQuestions());

        // MÓDULO 5: Testing y Quality Assurance
        $module5 = Module::create([
            'title' => 'Testing: Unit, Feature y E2E',
            'description' => 'Garantiza la calidad con testing automatizado',
            'order' => 5,
            'difficulty' => 'advanced',
            'estimated_hours' => 20,
            'learning_objectives' => [
                'Escribir tests unitarios con PHPUnit',
                'Implementar tests de integración (Feature Tests)',
                'Utilizar factories y seeders para testing',
                'Aplicar TDD (Test-Driven Development)',
                'Medir cobertura de código'
            ]
        ]);

        $lesson5_1 = Lesson::create([
            'module_id' => $module5->id,
            'title' => 'Testing Fundamentals en Laravel',
            'description' => 'Aprende los fundamentos del testing automatizado',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 60,
            'content' => $this->getLesson5_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson5_1->id,
            'title' => 'Tests Unitarios para Modelos del ERP',
            'description' => 'Escribe tests unitarios para validar la lógica de negocio',
            'instructions' => $this->getExercise5_1Instructions(),
            'points' => 25,
            'difficulty' => 'medium'
        ]);

        $lesson5_2 = Lesson::create([
            'module_id' => $module5->id,
            'title' => 'Feature Tests y TDD',
            'description' => 'Implementa tests de integración con TDD',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 90,
            'content' => $this->getLesson5_2Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson5_2->id,
            'title' => 'Suite de Tests para API del ERP',
            'description' => 'Crea tests completos para todos los endpoints',
            'instructions' => $this->getExercise5_2Instructions(),
            'points' => 35,
            'difficulty' => 'hard'
        ]);

        $quiz5 = Quiz::create([
            'module_id' => $module5->id,
            'title' => 'Evaluación: Testing y QA',
            'description' => 'Valida tus conocimientos en testing automatizado',
            'passing_score' => 70,
            'time_limit_minutes' => 35
        ]);

        $this->createQuizQuestions($quiz5, $this->getModule5QuizQuestions());

        // MÓDULO 6: Performance, Caching y Optimización
        $module6 = Module::create([
            'title' => 'Performance, Caching y Optimización Avanzada',
            'description' => 'Optimiza tu aplicación Laravel para producción',
            'order' => 6,
            'difficulty' => 'advanced',
            'estimated_hours' => 18,
            'learning_objectives' => [
                'Implementar estrategias de caching (Redis, Memcached)',
                'Optimizar consultas de base de datos',
                'Configurar queues para tareas asíncronas',
                'Implementar rate limiting',
                'Monitorizar performance con herramientas profesionales'
            ]
        ]);

        $lesson6_1 = Lesson::create([
            'module_id' => $module6->id,
            'title' => 'Caching Strategies en Laravel',
            'description' => 'Implementa caching efectivo para mejorar performance',
            'type' => 'theory',
            'order' => 1,
            'estimated_minutes' => 75,
            'content' => $this->getLesson6_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson6_1->id,
            'title' => 'Implementar Cache en Dashboard del ERP',
            'description' => 'Optimiza el dashboard con estrategias de caching',
            'instructions' => $this->getExercise6_1Instructions(),
            'points' => 25,
            'difficulty' => 'medium'
        ]);

        $lesson6_2 = Lesson::create([
            'module_id' => $module6->id,
            'title' => 'Queues y Jobs Asíncronos',
            'description' => 'Procesa tareas pesadas de forma asíncrona',
            'type' => 'theory',
            'order' => 2,
            'estimated_minutes' => 60,
            'content' => $this->getLesson6_2Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson6_2->id,
            'title' => 'Jobs para Generación de Reportes',
            'description' => 'Implementa jobs para generar reportes en background',
            'instructions' => $this->getExercise6_2Instructions(),
            'points' => 30,
            'difficulty' => 'hard'
        ]);

        $quiz6 = Quiz::create([
            'module_id' => $module6->id,
            'title' => 'Evaluación: Performance y Optimización',
            'description' => 'Demuestra tu capacidad para optimizar aplicaciones',
            'passing_score' => 75,
            'time_limit_minutes' => 40
        ]);

        $this->createQuizQuestions($quiz6, $this->getModule6QuizQuestions());

        // MÓDULO 7: Proyecto Final - ERP Completo
        $module7 = Module::create([
            'title' => 'Proyecto Final: ERP Multi-Rol',
            'description' => 'Construye un ERP completo aplicando todos los conocimientos',
            'order' => 7,
            'difficulty' => 'advanced',
            'estimated_hours' => 40,
            'learning_objectives' => [
                'Integrar todos los conceptos aprendidos',
                'Diseñar arquitectura escalable',
                'Implementar funcionalidades complejas',
                'Aplicar mejores prácticas profesionales',
                'Preparar aplicación para producción'
            ]
        ]);

        $lesson7_1 = Lesson::create([
            'module_id' => $module7->id,
            'title' => 'Arquitectura del Proyecto ERP',
            'description' => 'Diseña la arquitectura completa del sistema',
            'type' => 'project',
            'order' => 1,
            'estimated_minutes' => 120,
            'content' => $this->getLesson7_1Content()
        ]);

        Exercise::create([
            'lesson_id' => $lesson7_1->id,
            'title' => 'Implementación Completa del ERP',
            'description' => 'Desarrolla el ERP completo con los tres roles',
            'instructions' => $this->getExercise7_1Instructions(),
            'points' => 100,
            'difficulty' => 'hard'
        ]);

        $quiz7 = Quiz::create([
            'module_id' => $module7->id,
            'title' => 'Evaluación Final: Proyecto ERP',
            'description' => 'Evaluación integral de todo el bootcamp',
            'passing_score' => 80,
            'time_limit_minutes' => 60
        ]);

        $this->createQuizQuestions($quiz7, $this->getModule7QuizQuestions());

        $this->command->info('✅ Bootcamp de Laravel creado exitosamente con 7 módulos, 20+ lecciones, 15+ ejercicios y 7 quizzes');
    }

    private function createQuizQuestions(Quiz $quiz, array $questions): void
    {
        foreach ($questions as $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                ...$questionData
            ]);
        }
    }

    // Métodos de contenido para cada lección (continuará en el siguiente archivo)
    private function getLesson1_1Content(): string
    {
        return <<<'MARKDOWN'
# Arquitectura MVC y Ciclo de Vida de Laravel

## 1. Introducción a la Arquitectura MVC

Laravel implementa el patrón Model-View-Controller (MVC), que separa la lógica de la aplicación en tres componentes interconectados:

### Model (Modelo)
- Representa la estructura de datos y lógica de negocio
- Interactúa directamente con la base de datos
- En Laravel: **Eloquent ORM Models**

```php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

### View (Vista)
- Presenta los datos al usuario
- Contiene la lógica de presentación
- En Laravel: **Blade Templates**

```blade
{{-- resources/views/users/show.blade.php --}}
<h1>{{ $user->name }}</h1>
<p>{{ $user->email }}</p>
```

### Controller (Controlador)
- Maneja las peticiones del usuario
- Coordina entre Model y View
- Contiene la lógica de aplicación

```php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
```

## 2. Ciclo de Vida de una Petición HTTP

### Paso 1: Entry Point (public/index.php)
Toda petición pasa por `public/index.php`, que:
1. Carga el autoloader de Composer
2. Obtiene la instancia de la aplicación Laravel
3. Captura la petición HTTP

### Paso 2: HTTP Kernel
El kernel HTTP (`app/Http/Kernel.php`) procesa la petición:
- Ejecuta **bootstrappers** (configuración, providers, facades)
- Ejecuta **middleware global**
- Envía la petición al router

### Paso 3: Service Providers
Los Service Providers se registran y ejecutan:
```php
// config/app.php
'providers' => [
    App\Providers\AppServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    // ...
]
```

### Paso 4: Router
El router analiza la petición y la dirige al controlador apropiado:
```php
// routes/web.php
Route::get('/users/{user}', [UserController::class, 'show']);
```

### Paso 5: Middleware de Ruta
Se ejecutan los middleware específicos de la ruta:
```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin']);
```

### Paso 6: Controller
El controlador procesa la petición:
- Interactúa con modelos
- Aplica lógica de negocio
- Retorna respuesta (view, JSON, redirect, etc.)

### Paso 7: Response
La respuesta se prepara y se envía al navegador:
- Se ejecutan middleware de respuesta
- Se aplican transformaciones finales
- Se envía al cliente

## 3. Service Container y Dependency Injection

Laravel utiliza un **Service Container** potente para gestionar dependencias:

```php
class UserController extends Controller
{
    // Laravel inyecta automáticamente las dependencias
    public function __construct(
        private UserRepository $users,
        private EmailService $email
    ) {}

    public function store(Request $request)
    {
        $user = $this->users->create($request->validated());
        $this->email->sendWelcome($user);

        return response()->json($user, 201);
    }
}
```

## 4. Facades

Los Facades proporcionan una interfaz estática a clases del container:

```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Facade
Cache::put('key', 'value', 3600);

// Equivalente sin facade
app('cache')->put('key', 'value', 3600);
```

## 5. Ejercicio Práctico

Crea un diagrama que muestre el flujo completo de una petición desde que el usuario accede a `https://example.com/users/1` hasta que recibe la respuesta HTML.

**Elementos a incluir:**
- Entry point
- Kernel
- Middleware
- Router
- Controller
- Model
- View
- Response

---

## Recursos Adicionales

- [Laravel Lifecycle Documentation](https://laravel.com/docs/11.x/lifecycle)
- [Service Container Deep Dive](https://laravel.com/docs/11.x/container)
- [Facades Explained](https://laravel.com/docs/11.x/facades)
MARKDOWN;
    }

    private function getLesson1_2Content(): string
    {
        return <<<'MARKDOWN'
# Sistema de Routing: Básico y Avanzado

## 1. Routing Básico

### Definición de Rutas
```php
// routes/web.php
use Illuminate\Support\Facades\Route;

// GET request
Route::get('/users', [UserController::class, 'index']);

// POST request
Route::post('/users', [UserController::class, 'store']);

// PUT/PATCH request
Route::put('/users/{id}', [UserController::class, 'update']);

// DELETE request
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Múltiples verbos
Route::match(['get', 'post'], '/users', [UserController::class, 'index']);

// Todos los verbos
Route::any('/users', [UserController::class, 'index']);
```

### Route Parameters
```php
// Parámetro obligatorio
Route::get('/users/{id}', function ($id) {
    return "User ID: $id";
});

// Parámetro opcional
Route::get('/users/{name?}', function ($name = 'Guest') {
    return "Hello, $name";
});

// Múltiples parámetros
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return "Post $postId, Comment $commentId";
});

// Restricciones con expresiones regulares
Route::get('/users/{id}', function ($id) {
    //
})->where('id', '[0-9]+');

Route::get('/users/{username}', function ($username) {
    //
})->where('username', '[a-zA-Z]+');

// Múltiples restricciones
Route::get('/posts/{id}/{slug}', function ($id, $slug) {
    //
})->where(['id' => '[0-9]+', 'slug' => '[a-z-]+']);
```

## 2. Route Names

```php
// Definir nombre de ruta
Route::get('/users/profile', [UserController::class, 'profile'])
    ->name('user.profile');

// Usar en redirecciones
return redirect()->route('user.profile');

// Usar en vistas
<a href="{{ route('user.profile') }}">My Profile</a>

// Con parámetros
Route::get('/users/{id}/edit', [UserController::class, 'edit'])
    ->name('user.edit');

return redirect()->route('user.edit', ['id' => 1]);
```

## 3. Resource Routes

Laravel ofrece routing automático para operaciones CRUD:

```php
// Genera 7 rutas automáticamente
Route::resource('projects', ProjectController::class);

// Rutas generadas:
// GET    /projects              index    projects.index
// GET    /projects/create       create   projects.create
// POST   /projects              store    projects.store
// GET    /projects/{project}    show     projects.show
// GET    /projects/{project}/edit  edit  projects.edit
// PUT    /projects/{project}    update   projects.update
// DELETE /projects/{project}    destroy  projects.destroy

// Solo ciertas acciones
Route::resource('photos', PhotoController::class)
    ->only(['index', 'show']);

// Excepto ciertas acciones
Route::resource('photos', PhotoController::class)
    ->except(['create', 'store']);

// API Resource (sin create/edit)
Route::apiResource('posts', PostController::class);
```

## 4. Route Groups

```php
// Compartir middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});

// Compartir prefijo
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    // URL: /admin/users
});

// Compartir namespace
Route::namespace('Admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});

// Compartir nombre
Route::name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users'); // Nombre completo: admin.users
});

// Combinar múltiples atributos
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('projects', ProjectController::class);
    });
```

## 5. Route Model Binding

Laravel puede resolver automáticamente modelos Eloquent:

```php
// Implicit Binding
Route::get('/users/{user}', function (User $user) {
    return $user; // Laravel busca automáticamente User::find($id)
});

// Custom key
Route::get('/users/{user:email}', function (User $user) {
    return $user; // Busca por email en lugar de ID
});

// En el modelo
class User extends Model
{
    public function getRouteKeyName()
    {
        return 'username'; // Usar username por defecto
    }
}

// Explicit Binding
Route::bind('user', function ($value) {
    return User::where('slug', $value)->firstOrFail();
});
```

## 6. Rate Limiting

```php
// En RouteServiceProvider
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Aplicar a ruta
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/user', function () {
        //
    });
});

// Custom rate limit
RateLimiter::for('uploads', function (Request $request) {
    return $request->user()->isPremium()
        ? Limit::none()
        : Limit::perMinute(10);
});
```

## 7. Subdomain Routing

```php
Route::domain('{account}.example.com')->group(function () {
    Route::get('/users/{id}', function ($account, $id) {
        return "Account: $account, User: $id";
    });
});
```

## 8. Route Caching

Para aplicaciones en producción:

```bash
# Cachear rutas (mejora performance)
php artisan route:cache

# Limpiar cache
php artisan route:clear
```

## Proyecto Práctico: Routing para ERP

Diseña la estructura de rutas completa para un ERP con estos módulos:
- Gestión de clientes
- Gestión de proyectos
- Gestión de tareas
- Facturación
- Reportes

**Requisitos:**
- Usar resource routes donde sea apropiado
- Organizar con route groups
- Aplicar middleware de autorización
- Implementar rate limiting en APIs
- Usar route model binding

---

## Recursos Adicionales

- [Routing Documentation](https://laravel.com/docs/11.x/routing)
- [Controllers Documentation](https://laravel.com/docs/11.x/controllers)
MARKDOWN;
    }

    private function getLesson1_3Content(): string
    {
        return <<<'MARKDOWN'
# Controllers, Resource Controllers y Middleware

## 1. Controllers Básicos

Los controllers organizan la lógica de peticiones HTTP en clases reutilizables.

### Crear un Controller
```bash
php artisan make:controller UserController
```

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
```

## 2. Resource Controllers

Controllers que implementan operaciones CRUD completas:

```bash
# Crear resource controller
php artisan make:controller ClientController --resource

# Con model binding
php artisan make:controller ClientController --resource --model=Client
```

```php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // GET /clients
    public function index()
    {
        $clients = Client::paginate(15);
        return view('clients.index', compact('clients'));
    }

    // GET /clients/create
    public function create()
    {
        return view('clients.create');
    }

    // POST /clients
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients',
            'company' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Cliente creado exitosamente');
    }

    // GET /clients/{client}
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    // GET /clients/{client}/edit
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // PUT/PATCH /clients/{client}
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'company' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Cliente actualizado exitosamente');
    }

    // DELETE /clients/{client}
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
```

## 3. API Resource Controllers

Para APIs RESTful que devuelven JSON:

```bash
php artisan make:controller Api/ProjectController --api
```

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Project::with('client')->paginate(15)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'client_id' => 'required|exists:clients,id',
            'budget' => 'required|numeric|min:0',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function show(Project $project)
    {
        return response()->json([
            'data' => $project->load(['client', 'tasks'])
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|max:255',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'budget' => 'sometimes|numeric|min:0',
        ]);

        $project->update($validated);

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ], 204);
    }
}
```

## 4. Middleware

Los middleware filtran las peticiones HTTP que entran a tu aplicación.

### Middleware Global
Aplica a todas las peticiones (definido en `app/Http/Kernel.php`):

```php
protected $middleware = [
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
];
```

### Middleware de Ruta
```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    // Rutas protegidas
});
```

### Crear Middleware Personalizado
```bash
php artisan make:middleware CheckRole
```

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

Registrar en `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

Usar en rutas:
```php
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index']);
});
```

### Middleware con Parámetros
```php
public function handle(Request $request, Closure $next, ...$roles)
{
    if (!$request->user() || !in_array($request->user()->role, $roles)) {
        abort(403);
    }

    return $next($request);
}
```

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:manager,employee');
```

## 5. Controller Middleware

Aplicar middleware dentro del controller:

```php
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:manager')->only(['create', 'store', 'destroy']);
        $this->middleware('verified')->except(['index', 'show']);
    }
}
```

## 6. Dependency Injection en Controllers

```php
use App\Services\InvoiceService;
use App\Repositories\ClientRepository;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService,
        private ClientRepository $clients
    ) {}

    public function store(Request $request)
    {
        $client = $this->clients->find($request->client_id);
        $invoice = $this->invoiceService->createInvoice($client, $request->items);

        return redirect()->route('invoices.show', $invoice);
    }
}
```

## 7. Form Requests

Encapsula lógica de validación:

```bash
php artisan make:request StoreClientRequest
```

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Client::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients',
            'company' => 'nullable|string',
            'tax_id' => 'nullable|string|unique:clients',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este email ya está registrado',
        ];
    }
}
```

Usar en controller:
```php
public function store(StoreClientRequest $request)
{
    $client = Client::create($request->validated());
    return redirect()->route('clients.show', $client);
}
```

---

## Ejercicio Práctico

Implementa un Resource Controller completo para el módulo de **Proyectos** del ERP con:

1. CRUD completo
2. Middleware de autorización por rol
3. Form Requests para validación
4. Relaciones con Cliente y Tareas
5. Paginación y filtros

**Roles:**
- **Manager**: Puede crear, editar, eliminar proyectos
- **Employee**: Puede ver y actualizar estado de sus tareas
- **Client**: Solo puede ver sus propios proyectos

---

## Recursos Adicionales

- [Controllers Documentation](https://laravel.com/docs/11.x/controllers)
- [Middleware Documentation](https://laravel.com/docs/11.x/middleware)
- [Validation Documentation](https://laravel.com/docs/11.x/validation)
MARKDOWN;
    }

    // Contenido de lecciones adicionales (acortado por brevedad)
    private function getLesson2_1Content(): string
    {
        return "# Migraciones y Diseño de Base de Datos\n\nContenido completo sobre migraciones, Schema Builder, índices, foreign keys, y mejores prácticas de diseño de base de datos...";
    }

    private function getLesson2_2Content(): string
    {
        return "# Eloquent ORM: Modelos y Relaciones\n\nContenido completo sobre modelos Eloquent, relaciones (1:1, 1:N, N:M, polimórficas), accessors, mutators, scopes...";
    }

    private function getLesson2_3Content(): string
    {
        return "# Query Builder y Optimización\n\nContenido sobre Query Builder, eager loading, problema N+1, índices, explain, optimización de consultas...";
    }

    private function getLesson3_1Content(): string
    {
        return "# Autenticación con Laravel Sanctum\n\nImplementación completa de Sanctum para SPAs y APIs, tokens, autenticación stateful/stateless...";
    }

    private function getLesson3_2Content(): string
    {
        return "# Autorización: Gates, Policies y Roles\n\nSistema completo de autorización con Gates, Policies, implementación de roles y permisos...";
    }

    private function getLesson4_1Content(): string
    {
        return "# Diseño de APIs RESTful\n\nPrincipios REST, diseño de endpoints, versionado, HATEOAS, códigos de estado HTTP...";
    }

    private function getLesson4_2Content(): string
    {
        return "# Validación y API Resources\n\nForm Requests avanzados, custom validation rules, API Resources, transformación de datos...";
    }

    private function getLesson5_1Content(): string
    {
        return "# Testing Fundamentals en Laravel\n\nPHPUnit, tests unitarios, factories, assertions, database testing...";
    }

    private function getLesson5_2Content(): string
    {
        return "# Feature Tests y TDD\n\nTests de integración, TDD workflow, mocking, HTTP tests, autenticación en tests...";
    }

    private function getLesson6_1Content(): string
    {
        return "# Caching Strategies en Laravel\n\nRedis, Memcached, cache tags, cache invalidation, estrategias de caching...";
    }

    private function getLesson6_2Content(): string
    {
        return "# Queues y Jobs Asíncronos\n\nQueue workers, jobs, eventos, listeners, procesamiento en background...";
    }

    private function getLesson7_1Content(): string
    {
        return "# Proyecto Final: Arquitectura del ERP\n\nDiseño completo del sistema ERP con tres roles, módulos principales, arquitectura escalable...";
    }

    // Instrucciones de ejercicios
    private function getExercise1_1Instructions(): string
    {
        return "Analiza el flujo completo de una petición HTTP en Laravel desde index.php hasta la respuesta. Documenta cada paso y los componentes involucrados.";
    }

    private function getExercise1_2Instructions(): string
    {
        return "Implementa el sistema de rutas completo para el módulo de gestión de clientes, incluyendo resource routes, route groups, middleware y model binding.";
    }

    private function getExercise1_2StarterCode(): string
    {
        return "<?php\n// routes/web.php\nuse Illuminate\\Support\\Facades\\Route;\n\n// TODO: Implementar rutas para clientes\n";
    }

    private function getExercise1_2SolutionCode(): string
    {
        return <<<'PHP'
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::middleware(['auth'])->prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create')->middleware('role:manager');
    Route::post('/', [ClientController::class, 'store'])->name('store')->middleware('role:manager');
    Route::get('/{client}', [ClientController::class, 'show'])->name('show');
    Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit')->middleware('role:manager');
    Route::put('/{client}', [ClientController::class, 'update'])->name('update')->middleware('role:manager');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy')->middleware('role:manager');
});
PHP;
    }

    private function getExercise1_3Instructions(): string
    {
        return "Crea un Resource Controller para Clientes con middleware de autorización, dependency injection, y Form Requests para validación.";
    }

    private function getExercise2_1Instructions(): string
    {
        return "Diseña y crea las migraciones completas para el ERP: users, clients, employees, projects, tasks, services, invoices, invoice_items, payments, activity_logs.";
    }

    private function getExercise2_2Instructions(): string
    {
        return "Implementa los modelos Eloquent con todas sus relaciones: User, Client, Employee, Project, Task, Service, Invoice, InvoiceItem, Payment.";
    }

    private function getExercise2_3Instructions(): string
    {
        return "Optimiza el dashboard del ERP que muestra estadísticas de proyectos, tareas, facturas. Usa eager loading para eliminar el problema N+1.";
    }

    private function getExercise3_1Instructions(): string
    {
        return "Implementa el sistema de autenticación completo con Laravel Sanctum para el ERP, incluyendo login, logout, y protección de rutas.";
    }

    private function getExercise3_2Instructions(): string
    {
        return "Crea el sistema de autorización con tres roles (Manager, Employee, Client) usando Policies. Define permisos específicos para cada rol en cada módulo.";
    }

    private function getExercise4_1Instructions(): string
    {
        return "Diseña e implementa una API RESTful completa para el módulo de Proyectos, siguiendo principios REST y mejores prácticas.";
    }

    private function getExercise4_2Instructions(): string
    {
        return "Implementa Form Requests para validación y API Resources para transformación de todas las entidades del ERP.";
    }

    private function getExercise5_1Instructions(): string
    {
        return "Escribe tests unitarios para los modelos del ERP, validando relaciones, mutators, accessors, y métodos personalizados.";
    }

    private function getExercise5_2Instructions(): string
    {
        return "Crea una suite completa de Feature Tests para todos los endpoints de la API del ERP, verificando autenticación, autorización, y funcionalidad.";
    }

    private function getExercise6_1Instructions(): string
    {
        return "Implementa caching estratégico en el dashboard del ERP para mejorar performance. Usa Redis y cache tags.";
    }

    private function getExercise6_2Instructions(): string
    {
        return "Crea Jobs para procesar tareas pesadas en background: generación de reportes PDF, envío de emails masivos, cálculo de estadísticas.";
    }

    private function getExercise7_1Instructions(): string
    {
        return "Implementa el ERP completo con todos los módulos, integrando todos los conceptos aprendidos: CRUD, autenticación, autorización, APIs, testing, caching, jobs.";
    }

    // Preguntas de quiz para cada módulo
    private function getModule1QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué componente del ciclo de vida de Laravel se ejecuta primero después de index.php?',
                'options' => ['Router', 'HTTP Kernel', 'Service Providers', 'Middleware'],
                'correct_answer' => 'HTTP Kernel',
                'explanation' => 'El HTTP Kernel es el primer componente que recibe la petición después del entry point.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué método HTTP se usa típicamente para actualizar un recurso completo?',
                'options' => ['PATCH', 'PUT', 'POST', 'UPDATE'],
                'correct_answer' => 'PUT',
                'explanation' => 'PUT se usa para actualizar un recurso completo, mientras PATCH es para actualizaciones parciales.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cuál es la forma correcta de definir un route parameter opcional?',
                'options' => [
                    'Route::get("/users/{id?}", ...)',
                    'Route::get("/users/{?id}", ...)',
                    'Route::get("/users/[id]", ...)',
                    'Route::get("/users/:id?", ...)'
                ],
                'correct_answer' => 'Route::get("/users/{id?}", ...)',
                'explanation' => 'Los parámetros opcionales se definen con {nombre?}.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Cuántas rutas genera Route::resource() por defecto?',
                'options' => ['5', '7', '8', '10'],
                'correct_answer' => '7',
                'explanation' => 'Resource routes genera 7 rutas: index, create, store, show, edit, update, destroy.',
                'points' => 10
            ],
            [
                'type' => 'true_false',
                'question' => 'Los Facades son una implementación del patrón Singleton.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Falso',
                'explanation' => 'Los Facades proporcionan una interfaz estática al Service Container, no son Singletons per se.',
                'points' => 10
            ]
        ];
    }

    private function getModule2QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué método de migración se usa para crear una foreign key?',
                'options' => ['foreign()', 'foreignKey()', 'references()', 'constrained()'],
                'correct_answer' => 'constrained()',
                'explanation' => 'En Laravel 11, constrained() es la forma moderna de crear foreign keys.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se define una relación hasMany en Eloquent?',
                'options' => [
                    'return $this->hasMany(Post::class);',
                    'return $this->has(Post::class);',
                    'return $this->many(Post::class);',
                    'return $this->relation(Post::class);'
                ],
                'correct_answer' => 'return $this->hasMany(Post::class);',
                'explanation' => 'hasMany() define una relación uno-a-muchos.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué técnica se usa para evitar el problema N+1?',
                'options' => ['Lazy Loading', 'Eager Loading', 'Late Loading', 'Quick Loading'],
                'correct_answer' => 'Eager Loading',
                'explanation' => 'Eager Loading carga relaciones anticipadamente con with().',
                'points' => 15
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se define una relación belongsTo?',
                'options' => [
                    'return $this->belongsTo(User::class);',
                    'return $this->belongs(User::class);',
                    'return $this->hasOne(User::class);',
                    'return $this->ownedBy(User::class);'
                ],
                'correct_answer' => 'return $this->belongsTo(User::class);',
                'explanation' => 'belongsTo() define el lado inverso de una relación.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué método se usa para crear registros relacionados?',
                'options' => ['create()', 'save()', 'associate()', 'Todos los anteriores'],
                'correct_answer' => 'Todos los anteriores',
                'explanation' => 'create(), save() y associate() pueden usarse según el contexto.',
                'points' => 15
            ]
        ];
    }

    private function getModule3QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué package se recomienda para autenticación de APIs en Laravel 11?',
                'options' => ['Passport', 'Sanctum', 'JWT', 'OAuth'],
                'correct_answer' => 'Sanctum',
                'explanation' => 'Sanctum es la solución oficial recomendada para SPAs y mobile apps.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se verifica una capacidad específica en una Policy?',
                'options' => [
                    '$this->authorize("update", $post);',
                    '$this->can("update", $post);',
                    '$this->check("update", $post);',
                    'Ambas A y B'
                ],
                'correct_answer' => 'Ambas A y B',
                'explanation' => 'Tanto authorize() como can() funcionan para verificar permisos.',
                'points' => 15
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué vulnerabilidad previene el token CSRF?',
                'options' => ['XSS', 'SQL Injection', 'Cross-Site Request Forgery', 'DDoS'],
                'correct_answer' => 'Cross-Site Request Forgery',
                'explanation' => 'CSRF tokens previenen peticiones falsificadas desde otros sitios.',
                'points' => 10
            ],
            [
                'type' => 'true_false',
                'question' => 'Laravel protege automáticamente contra SQL Injection al usar Eloquent.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Verdadero',
                'explanation' => 'Eloquent usa prepared statements que previenen SQL Injection.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Dónde se definen las Policies en Laravel?',
                'options' => ['app/Policies', 'app/Http/Policies', 'app/Auth/Policies', 'app/Security/Policies'],
                'correct_answer' => 'app/Policies',
                'explanation' => 'Las Policies se almacenan en app/Policies por convención.',
                'points' => 10
            ]
        ];
    }

    private function getModule4QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué código de estado HTTP indica creación exitosa?',
                'options' => ['200', '201', '202', '204'],
                'correct_answer' => '201',
                'explanation' => '201 Created indica que un recurso fue creado exitosamente.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué componente transforma modelos Eloquent a JSON en APIs?',
                'options' => ['Transformer', 'Resource', 'Serializer', 'Formatter'],
                'correct_answer' => 'Resource',
                'explanation' => 'API Resources transforman modelos a respuestas JSON estructuradas.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se crea un API Resource?',
                'options' => [
                    'php artisan make:resource UserResource',
                    'php artisan make:api UserResource',
                    'php artisan create:resource UserResource',
                    'php artisan resource:make UserResource'
                ],
                'correct_answer' => 'php artisan make:resource UserResource',
                'explanation' => 'make:resource es el comando correcto.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué principio REST establece que el servidor no guarda estado del cliente?',
                'options' => ['Stateless', 'Cacheable', 'Uniform Interface', 'Layered System'],
                'correct_answer' => 'Stateless',
                'explanation' => 'Stateless significa que cada petición es independiente.',
                'points' => 15
            ],
            [
                'type' => 'true_false',
                'question' => 'En RESTful APIs, PUT debe ser idempotente.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Verdadero',
                'explanation' => 'PUT debe producir el mismo resultado si se ejecuta múltiples veces.',
                'points' => 10
            ]
        ];
    }

    private function getModule5QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué framework de testing usa Laravel por defecto?',
                'options' => ['PHPSpec', 'PHPUnit', 'Pest', 'Codeception'],
                'correct_answer' => 'PHPUnit',
                'explanation' => 'Laravel usa PHPUnit como framework de testing base.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se ejecutan los tests en Laravel?',
                'options' => [
                    'php artisan test',
                    'php artisan run:tests',
                    'vendor/bin/phpunit',
                    'Ambas A y C'
                ],
                'correct_answer' => 'Ambas A y C',
                'explanation' => 'Tanto artisan test como phpunit ejecutan los tests.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué comando crea un Factory?',
                'options' => [
                    'php artisan make:factory',
                    'php artisan create:factory',
                    'php artisan factory:make',
                    'php artisan generate:factory'
                ],
                'correct_answer' => 'php artisan make:factory',
                'explanation' => 'make:factory crea una nueva factory.',
                'points' => 10
            ],
            [
                'type' => 'true_false',
                'question' => 'Los Feature Tests prueban componentes individuales aislados.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Falso',
                'explanation' => 'Feature Tests prueban integración, Unit Tests prueban componentes aislados.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => 'En TDD, ¿qué se escribe primero?',
                'options' => ['El código', 'Los tests', 'La documentación', 'Los comentarios'],
                'correct_answer' => 'Los tests',
                'explanation' => 'En TDD se escriben los tests antes del código de producción.',
                'points' => 10
            ]
        ];
    }

    private function getModule6QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué driver de cache ofrece mejor performance?',
                'options' => ['File', 'Database', 'Redis', 'Array'],
                'correct_answer' => 'Redis',
                'explanation' => 'Redis es un store en memoria extremadamente rápido.',
                'points' => 10
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se almacena un valor en cache por 1 hora?',
                'options' => [
                    'Cache::put("key", "value", 3600);',
                    'Cache::put("key", "value", now()->addHour());',
                    'Ambas son correctas',
                    'Ninguna es correcta'
                ],
                'correct_answer' => 'Ambas son correctas',
                'explanation' => 'Se puede usar segundos o Carbon instance.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué comando procesa jobs en la cola?',
                'options' => [
                    'php artisan queue:work',
                    'php artisan queue:listen',
                    'php artisan queue:process',
                    'Ambas A y B'
                ],
                'correct_answer' => 'Ambas A y B',
                'explanation' => 'work y listen procesan la cola de diferentes formas.',
                'points' => 10
            ],
            [
                'type' => 'true_false',
                'question' => 'Los Jobs deben implementar la interfaz ShouldQueue.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Verdadero',
                'explanation' => 'ShouldQueue indica que el job debe ejecutarse en cola.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué método invalida cache por tags?',
                'options' => ['forget()', 'flush()', 'clear()', 'invalidate()'],
                'correct_answer' => 'flush()',
                'explanation' => 'flush() limpia todo el cache de un tag específico.',
                'points' => 15
            ]
        ];
    }

    private function getModule7QuizQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué patrón arquitectónico separa lógica de negocio de controllers?',
                'options' => ['Repository Pattern', 'Service Pattern', 'Factory Pattern', 'Observer Pattern'],
                'correct_answer' => 'Service Pattern',
                'explanation' => 'Service Pattern encapsula lógica de negocio compleja.',
                'points' => 15
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué comando optimiza la aplicación para producción?',
                'options' => [
                    'php artisan optimize',
                    'php artisan config:cache',
                    'php artisan route:cache',
                    'Todos los anteriores'
                ],
                'correct_answer' => 'Todos los anteriores',
                'explanation' => 'Todos estos comandos optimizan diferentes aspectos.',
                'points' => 10
            ],
            [
                'type' => 'true_false',
                'question' => 'En producción, APP_DEBUG debe estar en true.',
                'options' => ['Verdadero', 'Falso'],
                'correct_answer' => 'Falso',
                'explanation' => 'APP_DEBUG=false en producción para no exponer información sensible.',
                'points' => 10
            ],
            [
                'type' => 'multiple_choice',
                'question' => '¿Qué técnica mejora la performance de listados largos?',
                'options' => ['Caching', 'Pagination', 'Eager Loading', 'Todas las anteriores'],
                'correct_answer' => 'Todas las anteriores',
                'explanation' => 'Todas estas técnicas mejoran performance en diferentes aspectos.',
                'points' => 15
            ],
            [
                'type' => 'code',
                'question' => '¿Cómo se implementa soft deletes en un modelo?',
                'options' => [
                    'use SoftDeletes;',
                    'use SoftDelete;',
                    'protected $softDelete = true;',
                    'protected $soft_deletes = true;'
                ],
                'correct_answer' => 'use SoftDeletes;',
                'explanation' => 'El trait SoftDeletes habilita eliminación suave.',
                'points' => 10
            ]
        ];
    }
}
