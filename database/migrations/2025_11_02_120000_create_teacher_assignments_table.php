<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transactions for this migration to avoid PostgreSQL transaction issues
     */
    public $withinTransaction = false;
    
    public function up(): void
    {
        if (! Schema::hasTable('teacher_assignments')) {
            Schema::create('teacher_assignments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->unsignedBigInteger('group_id')->nullable();
                $table->unsignedBigInteger('period_id')->nullable();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamps();

                $table->index('teacher_id');
                $table->index('subject_id');
                $table->index('group_id');
                $table->index('period_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('teacher_assignments')) {
            Schema::dropIfExists('teacher_assignments');
        }
    }
};
