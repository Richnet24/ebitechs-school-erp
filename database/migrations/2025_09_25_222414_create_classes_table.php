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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->integer('level')->default(1); // Niveau de classe (1, 2, 3, etc.)
            $table->integer('capacity')->default(40); // Capacité maximale
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Professeur principal
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
