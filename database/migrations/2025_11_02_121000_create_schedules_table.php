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
        if (! Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('group_id');
                $table->unsignedBigInteger('room_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('day_of_week', 10); // e.g. monday, tuesday
                $table->time('start_time');
                $table->time('end_time');
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamps();

                $table->index('group_id');
                $table->index('room_id');
                $table->index('teacher_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('schedules')) {
            Schema::dropIfExists('schedules');
        }
    }
};
