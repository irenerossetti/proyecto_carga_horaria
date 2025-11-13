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
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create rooms table if missing
        if (! Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('capacity')->nullable();
                $table->string('location')->nullable();
                // resources will store JSON with equipment flags/details
                $table->json('resources')->nullable();
                $table->timestamps();
            });
        }

        // Create subjects table if missing
        if (! Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->integer('credits')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create groups table if missing
        if (! Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
                $table->string('code');
                $table->string('name');
                $table->integer('capacity')->nullable();
                $table->string('schedule')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('groups')) {
            Schema::dropIfExists('groups');
        }

        if (Schema::hasTable('subjects')) {
            Schema::dropIfExists('subjects');
        }

        // Do not drop rooms automatically to avoid accidental data loss in production
        if (Schema::hasTable('rooms')) {
            // keep rooms table (non-destructive by default)
        }
    }
};
