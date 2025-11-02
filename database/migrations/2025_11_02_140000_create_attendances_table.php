<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // allow running outside transaction in case Postgres connection is in aborted state
    public $withinTransaction = false;
    public function up(): void
    {
        if (! Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('schedule_id')->nullable();
                $table->date('date');
                $table->time('time')->nullable();
                $table->string('status')->default('present'); // present, absent, late
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->index('teacher_id');
                $table->index('schedule_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::dropIfExists('attendances');
        }
    }
};
