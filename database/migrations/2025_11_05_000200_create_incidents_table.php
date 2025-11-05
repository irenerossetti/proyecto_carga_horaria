<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        if (Schema::hasTable('incidents')) return;

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->text('description');
            $table->string('status')->default('open'); // open, in_progress, resolved
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('room_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
