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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // sport, culture, académique, etc.
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade'); // Superviseur
            $table->integer('max_members')->default(30);
            $table->string('meeting_schedule')->nullable(); // Horaire des réunions
            $table->string('location')->nullable(); // Lieu des réunions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};