<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('trainees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('github')->nullable();
            $table->text('motivation')->nullable();
            $table->integer('progress')->default(0);
            $table->string('certificate_code')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('trainees'); }
};
