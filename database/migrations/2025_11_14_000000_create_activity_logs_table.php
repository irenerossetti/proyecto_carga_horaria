<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transactions for this migration
     */
    public $withinTransaction = false;
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->index('user_id');
            $table->string('user_name')->nullable(); // Guardar nombre por si se elimina el usuario
            $table->string('user_email')->nullable();
            $table->string('user_role')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('action'); // login, logout, create, update, delete, view
            $table->string('module'); // users, schedules, attendance, etc.
            $table->string('description');
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->json('old_values')->nullable(); // Valores anteriores (para updates)
            $table->json('new_values')->nullable(); // Valores nuevos
            $table->timestamp('created_at');
            
            // Índices para búsquedas rápidas
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
            $table->index('ip_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activity_logs')) {
            Schema::dropIfExists('activity_logs');
        }
    }
};
