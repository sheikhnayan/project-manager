<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('holiday_date');
            $table->timestamps();
            
            // Ensure a user can't have duplicate holiday dates
            $table->unique(['user_id', 'holiday_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_holidays');
    }
};
