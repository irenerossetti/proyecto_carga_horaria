<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transaction wrapping for this migration to avoid issues on some DB drivers
     */
    public $withinTransaction = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('conflicts')) {
            Schema::create('conflicts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('schedule_a_id');
                $table->unsignedBigInteger('schedule_b_id');
                $table->string('type')->nullable(); // e.g. teacher, room
                $table->boolean('resolved')->default(false);
                $table->text('resolution_note')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index(['schedule_a_id']);
                $table->index(['schedule_b_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conflicts');
    }
};
