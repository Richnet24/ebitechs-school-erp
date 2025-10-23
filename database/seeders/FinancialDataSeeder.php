<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class FinancialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des budgets
        $budgets = [
            [
                'name' => 'Budget Académique 2024',
                'description' => 'Budget pour les activités académiques',
                'category' => 'academic',
                'allocated_amount' => 50000.00,
                'spent_amount' => 15000.00,
                'remaining_amount' => 35000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'name' => 'Budget Administratif 2024',
                'description' => 'Budget pour les dépenses administratives',
                'category' => 'administrative',
                'allocated_amount' => 30000.00,
                'spent_amount' => 8000.00,
                'remaining_amount' => 22000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'name' => 'Budget Infrastructure 2024',
                'description' => 'Budget pour les travaux d\'infrastructure',
                'category' => 'infrastructure',
                'allocated_amount' => 100000.00,
                'spent_amount' => 25000.00,
                'remaining_amount' => 75000.00,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
            ],
        ];

        foreach ($budgets as $budgetData) {
            Budget::create($budgetData);
        }

        // Créer des factures
        $students = Student::all();
        $invoiceData = [];

        foreach ($students as $student) {
            // Frais de scolarité
            $invoiceData[] = [
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'student_id' => $student->id,
                'description' => 'Frais de scolarité - ' . $student->class->name,
                'amount' => 500.00,
                'tax_amount' => 50.00,
                'total_amount' => 550.00,
                'invoice_date' => Carbon::now()->subDays(rand(1, 30)),
                'due_date' => Carbon::now()->addDays(30),
                'status' => ['draft', 'sent', 'paid', 'overdue'][rand(0, 3)],
                'notes' => 'Paiement à effectuer avant la date d\'échéance',
                'created_by' => 1,
            ];

            // Frais d'examen
            if (rand(0, 1)) {
                $invoiceData[] = [
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'student_id' => $student->id,
                    'description' => 'Frais d\'examen',
                    'amount' => 25.00,
                    'tax_amount' => 2.50,
                    'total_amount' => 27.50,
                    'invoice_date' => Carbon::now()->subDays(rand(1, 15)),
                    'due_date' => Carbon::now()->addDays(15),
                    'status' => ['draft', 'sent', 'paid'][rand(0, 2)],
                    'notes' => 'Frais d\'inscription aux examens',
                    'created_by' => 1,
                ];
            }
        }

        foreach ($invoiceData as $invoice) {
            Invoice::create($invoice);
        }

        // Créer des paiements
        $invoices = Invoice::where('status', 'paid')->get();
        $paymentMethods = ['cash', 'bank_transfer', 'check', 'mobile_money', 'card'];

        foreach ($invoices as $invoice) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_number' => 'PAY-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'amount' => $invoice->total_amount,
                'payment_date' => $invoice->invoice_date->addDays(rand(1, 10)),
                'payment_method' => $paymentMethods[rand(0, 4)],
                'reference_number' => 'REF-' . rand(100000, 999999),
                'notes' => 'Paiement reçu',
                'received_by' => rand(1, 3),
            ]);
        }

        // Créer des dépenses
        $expenseCategories = [
            'academic', 'administrative', 'infrastructure', 'maintenance',
            'equipment', 'personnel', 'utilities', 'transport', 'communication', 'other'
        ];

        $expenseTitles = [
            'Achat de matériel informatique',
            'Maintenance des ordinateurs',
            'Achat de livres et manuels',
            'Frais de transport pour sortie éducative',
            'Achat de fournitures de bureau',
            'Réparation de la toiture',
            'Achat de mobilier scolaire',
            'Frais de communication',
            'Achat de matériel de laboratoire',
            'Frais de nettoyage',
        ];

        $expenseDescriptions = [
            'Achat de nouveaux ordinateurs pour le laboratoire',
            'Maintenance préventive des équipements',
            'Acquisition de nouveaux manuels scolaires',
            'Transport des étudiants pour visite d\'entreprise',
            'Fournitures de bureau pour l\'administration',
            'Réparation urgente de la toiture du bâtiment principal',
            'Nouveau mobilier pour les salles de classe',
            'Frais de téléphone et internet',
            'Équipement pour le laboratoire de sciences',
            'Services de nettoyage mensuel',
        ];

        $expensePaymentMethods = ['cash', 'bank_transfer', 'check', 'credit_card', 'other'];
        
        for ($i = 0; $i < 20; $i++) {
            $amount = rand(50, 2000);
            $status = ['draft', 'pending_approval', 'approved', 'paid'][rand(0, 3)];
            
            Expense::create([
                'expense_number' => 'EXP-' . date('Y') . '-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'title' => $expenseTitles[rand(0, count($expenseTitles) - 1)],
                'description' => $expenseDescriptions[rand(0, count($expenseDescriptions) - 1)],
                'category' => $expenseCategories[rand(0, count($expenseCategories) - 1)],
                'amount' => $amount,
                'currency' => 'USD',
                'expense_date' => Carbon::now()->subDays(rand(1, 60)),
                'status' => $status,
                'payment_method' => $expensePaymentMethods[rand(0, 4)],
                'vendor_name' => 'Fournisseur ' . ($i + 1),
                'vendor_contact' => '+243 123 456 ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'reference_number' => 'REF-' . rand(100000, 999999),
                'notes' => 'Dépense approuvée',
                'budget_id' => rand(1, 3),
                'created_by' => rand(1, 3),
                'approved_by' => $status === 'approved' || $status === 'paid' ? rand(1, 3) : null,
                'approved_at' => $status === 'approved' || $status === 'paid' ? Carbon::now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}