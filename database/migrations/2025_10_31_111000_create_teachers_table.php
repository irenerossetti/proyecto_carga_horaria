<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            // LA COLUMNA IMPORTANTE QUE FALTABA ANTES
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('name');
            $table->string('email')->unique();
            $table->string('dni')->nullable();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};