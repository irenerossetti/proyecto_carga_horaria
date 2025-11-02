<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // teacher_assignments FKs
        if (Schema::hasTable('teacher_assignments')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                // Add FK to teachers if table exists and constraint not already present
                if (Schema::hasTable('teachers')) {
                    try {
                        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
                    } catch (\Exception $e) {
                        // ignore if constraint exists
                    }
                }

                if (Schema::hasTable('subjects')) {
                    try {
                        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('groups')) {
                    try {
                        $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('academic_periods')) {
                    try {
                        $table->foreign('period_id')->references('id')->on('academic_periods')->onDelete('set null');
                    } catch (\Exception $e) {}
                }
            });
        }

        // schedules FKs
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if (Schema::hasTable('groups')) {
                    try {
                        $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
                    } catch (\Exception $e) {}
                }
                if (Schema::hasTable('rooms')) {
                    try {
                        $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
                    } catch (\Exception $e) {}
                }
                if (Schema::hasTable('teachers')) {
                    try {
                        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
                    } catch (\Exception $e) {}
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('teacher_assignments')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                try { $table->dropForeign(['teacher_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['subject_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['group_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['period_id']); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                try { $table->dropForeign(['group_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['room_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['teacher_id']); } catch (\Exception $e) {}
            });
        }
    }
};
