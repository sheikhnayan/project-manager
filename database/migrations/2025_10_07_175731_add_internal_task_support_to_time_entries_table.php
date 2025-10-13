<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            // Add fields to support both project and internal tasks
            $table->enum('task_type', ['project', 'internal'])->default('project')->after('task_id');
            $table->foreignId('internal_task_id')->nullable()->after('task_type')->constrained()->onDelete('cascade');
            $table->text('description')->nullable()->after('internal_task_id'); // For internal task descriptions
            
            // Make existing project and task fields nullable for internal entries
            $table->foreignId('project_id')->nullable()->change();
            $table->foreignId('task_id')->nullable()->change();
            
            // Add indexes for performance
            $table->index(['task_type', 'internal_task_id']);
            $table->index(['user_id', 'task_type', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn(['task_type', 'internal_task_id', 'description']);
            
            // Restore original constraints (make project_id and task_id required again)
            $table->foreignId('project_id')->nullable(false)->change();
            $table->foreignId('task_id')->nullable(false)->change();
        });
    }
};
