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
        Schema::create('internal_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('department')->nullable(); // HR, IT, Marketing, Admin, etc.
            $table->string('category')->default('General'); // Meeting, Training, Administrative, etc.
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Optional internal billing rate
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->integer('max_hours_per_day')->nullable(); // Optional daily limit
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade'); // Multi-tenancy
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['company_id', 'is_active']);
            $table->index(['department', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_tasks');
    }
};
