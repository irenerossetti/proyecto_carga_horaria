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
        if (!Schema::hasColumn('users', 'registration_number')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('registration_number', 9)->nullable()->unique()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'registration_number')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('registration_number');
            });
        }
    }
};
