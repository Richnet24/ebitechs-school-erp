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
        Schema::create('discipline_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('incident_date');
            $table->enum('type', ['minor', 'major', 'serious'])->default('minor');
            $table->string('title');
            $table->text('description');
            $table->text('actions_taken')->nullable();
            $table->text('consequences')->nullable();
            $table->text('follow_up')->nullable();
            $table->enum('status', ['open', 'resolved', 'escalated'])->default('open');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_records');
    }
};