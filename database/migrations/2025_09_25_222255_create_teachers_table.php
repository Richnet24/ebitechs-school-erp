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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->string('specialization')->nullable();
            $table->string('qualification')->nullable();
            $table->date('hire_date');
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'substitute'])->default('full_time');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
