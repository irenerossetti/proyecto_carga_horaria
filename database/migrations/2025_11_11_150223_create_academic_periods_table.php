<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // No hacer nada si la tabla ya existe
        // La migración 2025_10_31_100000 ya creó la tabla
        // La migración 2025_11_12_033233 agregará las columnas faltantes
        if (Schema::hasTable('academic_periods')) {
            return;
        }
        
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['planned', 'active', 'closed'])->default('planned');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_periods');
    }
};