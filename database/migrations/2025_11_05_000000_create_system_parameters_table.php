<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable wrapping this migration in a transaction to avoid Postgres index/DDL issues
     */
    public $withinTransaction = false;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('system_parameters')) return;

        Schema::create('system_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_parameters');
    }
};
