
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('name');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget_total', 15, 2);
            $table->decimal('budget_materials', 15, 2);
            $table->decimal('budget_labor', 15, 2);
            $table->decimal('budget_overhead', 15, 2);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
