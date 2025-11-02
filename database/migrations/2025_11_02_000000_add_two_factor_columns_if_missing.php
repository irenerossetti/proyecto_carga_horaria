<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Note: previously executed outside transaction to address an aborted transaction state during deploy.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $targets = ['users', 'usuarios'];

        foreach ($targets as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                // Add columns only if they don't exist to be non-destructive.
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $targets = ['users', 'usuarios'];

        foreach ($targets as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) {
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
};
