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
        Schema::create('psychological_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('session_date');
            $table->enum('type', ['assessment', 'counseling', 'therapy', 'evaluation', 'follow_up'])->default('assessment');
            $table->text('description');
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('follow_up_actions')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('psychologist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychological_records');
    }
};
