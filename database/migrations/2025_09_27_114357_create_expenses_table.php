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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique(); // Numéro de dépense
            $table->string('title'); // Titre de la dépense
            $table->text('description')->nullable(); // Description détaillée
            $table->enum('category', [
                'academic', 'administrative', 'infrastructure', 
                'maintenance', 'equipment', 'personnel', 'utilities', 
                'transport', 'communication', 'other'
            ]); // Catégorie de dépense
            $table->decimal('amount', 15, 2); // Montant de la dépense
            $table->string('currency', 3)->default('USD'); // Devise
            $table->date('expense_date'); // Date de la dépense
            $table->enum('status', [
                'draft', 'pending_approval', 'approved', 
                'rejected', 'paid', 'cancelled'
            ])->default('draft'); // Statut de la dépense
            $table->enum('payment_method', [
                'cash', 'bank_transfer', 'check', 'credit_card', 'other'
            ])->nullable(); // Méthode de paiement
            $table->string('vendor_name')->nullable(); // Nom du fournisseur
            $table->string('vendor_contact')->nullable(); // Contact du fournisseur
            $table->string('reference_number')->nullable(); // Numéro de référence
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->json('attachments')->nullable(); // Pièces jointes
            $table->foreignId('budget_id')->nullable()->constrained()->onDelete('set null'); // Lien vers le budget
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Créateur
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Approbateur
            $table->timestamp('approved_at')->nullable(); // Date d'approbation
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['expense_date', 'status']);
            $table->index(['category', 'status']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
