<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Usuarios del ERP (gerente, empleado, cliente)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('client'); // manager, employee, client
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Clientes (perfil extendido)
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->decimal('current_balance', 10, 2)->default(0);
            $table->timestamps();
        });

        // Empleados (perfil extendido)
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code')->unique();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Proyectos
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->decimal('budget', 10, 2)->default(0);
            $table->decimal('actual_cost', 10, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
        });

        // Tareas
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->integer('estimated_hours')->default(0);
            $table->integer('actual_hours')->default(0);
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Servicios/Productos
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('type')->default('service'); // service, product
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Facturas
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('status')->default('pending'); // pending, paid, overdue, cancelled
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Líneas de factura
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });

        // Pagos
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_method')->default('transfer'); // cash, transfer, card, other
            $table->string('reference')->nullable();
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Actividad/Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('entity_type')->nullable(); // project, task, invoice, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('services');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('users');
    }
};
