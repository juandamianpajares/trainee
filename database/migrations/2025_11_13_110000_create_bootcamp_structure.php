<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Módulos del bootcamp
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->string('difficulty')->default('beginner'); // beginner, intermediate, advanced
            $table->integer('estimated_hours')->default(0);
            $table->json('learning_objectives')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lecciones dentro de cada módulo
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content'); // Contenido en Markdown
            $table->string('type')->default('theory'); // theory, practice, quiz, project
            $table->integer('order')->default(0);
            $table->integer('estimated_minutes')->default(0);
            $table->json('resources')->nullable(); // Links, videos, docs
            $table->json('prerequisites')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Ejercicios prácticos
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->longText('instructions'); // Markdown con instrucciones detalladas
            $table->text('starter_code')->nullable();
            $table->text('solution_code')->nullable();
            $table->json('test_cases')->nullable();
            $table->integer('points')->default(10);
            $table->string('difficulty')->default('medium');
            $table->timestamps();
        });

        // Quizzes de evaluación
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_score')->default(70);
            $table->integer('time_limit_minutes')->nullable();
            $table->boolean('randomize_questions')->default(true);
            $table->timestamps();
        });

        // Preguntas de quiz
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('multiple_choice'); // multiple_choice, true_false, code
            $table->text('question');
            $table->json('options')->nullable(); // Para múltiple choice
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(10);
            $table->timestamps();
        });

        // Progreso del trainee por lección
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->integer('time_spent_minutes')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
            $table->unique(['trainee_id', 'lesson_id']);
        });

        // Submissions de ejercicios
        Schema::create('exercise_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->longText('submitted_code');
            $table->boolean('is_correct')->default(false);
            $table->integer('score')->default(0);
            $table->json('test_results')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        // Resultados de quizzes
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('max_score')->default(100);
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();
            $table->integer('time_taken_minutes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Progreso general por módulo
        Schema::create('module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->integer('completion_percentage')->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->integer('exercises_completed')->default(0);
            $table->boolean('quiz_passed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['trainee_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_progress');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('exercise_submissions');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
    }
};
