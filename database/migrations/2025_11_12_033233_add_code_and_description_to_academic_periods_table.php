<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Disable transactions for PostgreSQL
     */
    public $withinTransaction = false;
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta migración ya fue ejecutada manualmente con fix_academic_periods.php
        // No hacer nada para evitar errores
        echo "Migración ya aplicada manualmente\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_periods', function (Blueprint $table) {
            if (Schema::hasColumn('academic_periods', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('academic_periods', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
