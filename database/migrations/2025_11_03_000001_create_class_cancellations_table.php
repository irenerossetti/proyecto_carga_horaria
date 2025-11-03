<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('class_cancellations')) {
            Schema::create('class_cancellations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('schedule_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('mode'); // 'cancelled' or 'virtual'
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('canceled_by')->nullable();
                $table->timestamps();

                // Add lightweight indexes; foreign keys are optional for legacy DBs
                $table->index('schedule_id');
                $table->index('teacher_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_cancellations');
    }
};
