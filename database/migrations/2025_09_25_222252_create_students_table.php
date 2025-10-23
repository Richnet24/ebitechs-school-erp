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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_number')->unique();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('admission_date');
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('nationality')->default('CD');
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('medical_notes')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred', 'suspended'])->default('active');
            $table->string('qr_code')->nullable(); // QR code pour carte d'identité
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
