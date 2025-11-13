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
        // Remove two-factor columns if they exist in either users or usuarios (legacy) table.
        $targets = ['users', 'usuarios'];

        foreach ($targets as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    $drops = [];

                    if (Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_secret')) {
                        $drops[] = 'two_factor_secret';
                    }

                    if (Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_recovery_codes')) {
                        $drops[] = 'two_factor_recovery_codes';
                    }

                    if (Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_confirmed_at')) {
                        $drops[] = 'two_factor_confirmed_at';
                    }

                    if (! empty($drops)) {
                        $tableBlueprint->dropColumn($drops);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the columns as nullable in both tables if they exist (non-destructive reverse).
        $targets = ['users', 'usuarios'];

        foreach ($targets as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    if (! Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_secret')) {
                        $tableBlueprint->text('two_factor_secret')->nullable()->after('password');
                    }

                    if (! Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_recovery_codes')) {
                        $tableBlueprint->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
                    }

                    if (! Schema::hasColumn($tableBlueprint->getTable(), 'two_factor_confirmed_at')) {
                        $tableBlueprint->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
                    }
                });
            }
        }
    }
};
