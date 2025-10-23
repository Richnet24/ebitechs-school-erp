<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des budgets de démonstration
        $budgets = [
            [
                'name' => 'Budget Académique 2024',
                'description' => 'Budget pour les activités académiques et pédagogiques',
                'category' => 'academic',
                'allocated_amount' => 50000.00,
                'spent_amount' => 15000.00,
                'remaining_amount' => 35000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1, // Super Admin
            ],
            [
                'name' => 'Budget Infrastructure 2024',
                'description' => 'Budget pour la maintenance et amélioration des infrastructures',
                'category' => 'infrastructure',
                'allocated_amount' => 30000.00,
                'spent_amount' => 8000.00,
                'remaining_amount' => 22000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'name' => 'Budget Équipement 2024',
                'description' => 'Budget pour l\'achat d\'équipements informatiques et pédagogiques',
                'category' => 'equipment',
                'allocated_amount' => 25000.00,
                'spent_amount' => 12000.00,
                'remaining_amount' => 13000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
            ],
        ];

        foreach ($budgets as $budgetData) {
            Budget::firstOrCreate(
                ['name' => $budgetData['name'], 'fiscal_year' => $budgetData['fiscal_year']],
                $budgetData
            );
        }

        // Créer des factures de démonstration
        $students = Student::with('user')->take(5)->get();
        
        if ($students->count() > 0) {
            $invoiceTypes = [
                ['description' => 'Frais de scolarité - Trimestre 1', 'amount' => 500.00],
                ['description' => 'Frais de scolarité - Trimestre 2', 'amount' => 500.00],
                ['description' => 'Frais d\'examen', 'amount' => 50.00],
                ['description' => 'Frais de laboratoire', 'amount' => 100.00],
                ['description' => 'Frais de bibliothèque', 'amount' => 25.00],
            ];

            foreach ($students as $index => $student) {
                $invoiceType = $invoiceTypes[$index % count($invoiceTypes)];
                
                $invoice = Invoice::create([
                    'invoice_number' => 'FACT-2024-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'student_id' => $student->id,
                    'description' => $invoiceType['description'],
                    'amount' => $invoiceType['amount'],
                    'tax_amount' => $invoiceType['amount'] * 0.16, // 16% de TVA
                    'total_amount' => $invoiceType['amount'] * 1.16,
                    'invoice_date' => now()->subDays(rand(1, 30)),
                    'due_date' => now()->addDays(rand(1, 30)),
                    'status' => ['draft', 'sent', 'paid', 'overdue'][rand(0, 3)],
                    'notes' => 'Facture générée automatiquement pour démonstration',
                    'created_by' => 1,
                ]);

                // Créer des paiements pour certaines factures
                if (in_array($invoice->status, ['paid', 'sent'])) {
                    $paymentAmount = $invoice->status === 'paid' ? $invoice->total_amount : $invoice->total_amount * 0.5;
                    
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'payment_number' => 'PAY-2024-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'amount' => $paymentAmount,
                        'payment_date' => $invoice->status === 'paid' ? 
                            $invoice->invoice_date->addDays(rand(1, 10)) : 
                            now()->subDays(rand(1, 15)),
                        'payment_method' => ['cash', 'bank_transfer', 'mobile_money'][rand(0, 2)],
                        'reference_number' => 'REF-' . rand(100000, 999999),
                        'notes' => 'Paiement de démonstration',
                        'received_by' => 1,
                    ]);
                }
            }
        }

        $this->command->info('Données financières de démonstration créées avec succès !');
    }
}