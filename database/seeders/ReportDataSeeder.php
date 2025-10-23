<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Budget;
use Illuminate\Database\Seeder;

class ReportDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des examens et notes pour les rapports académiques
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Créer des examens
            $exam = Exam::create([
                'course_id' => $course->id,
                'name' => 'Examen ' . $course->subject->name,
                'description' => 'Examen de ' . $course->subject->name,
                'type' => 'quiz',
                'exam_date' => now()->subDays(rand(1, 30)),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'total_marks' => 100,
                'passing_marks' => 50,
                'status' => 'completed',
            ]);
            
            // Créer des notes pour les élèves de cette classe
            $students = $course->class->students;
            foreach ($students as $student) {
                Grade::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'marks_obtained' => rand(30, 100),
                    'total_marks' => 100,
                    'grade_letter' => $this->getGradeLetter(rand(30, 100)),
                    'gpa' => rand(1, 4),
                    'remarks' => 'Bonne performance',
                    'graded_by' => $course->teacher_id,
                    'graded_at' => now(),
                ]);
            }
        }
        
        // Créer des présences pour les rapports d'assiduité
        $courses = Course::all();
        foreach ($courses as $course) {
            $students = $course->class->students;
            
            // Créer des présences pour les 30 derniers jours
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                
                foreach ($students as $student) {
                    $status = ['present', 'absent', 'late', 'excused'][rand(0, 3)];
                    
                    Attendance::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'attendance_date' => $date,
                        'status' => $status,
                        'notes' => $status === 'absent' ? 'Absence non justifiée' : null,
                        'marked_by' => $course->teacher_id,
                    ]);
                }
            }
        }
        
        // Créer des budgets pour les rapports financiers
        $budgets = [
            [
                'name' => 'Budget Académique 2024',
                'description' => 'Budget pour les activités académiques',
                'category' => 'Academic',
                'allocated_amount' => 50000,
                'spent_amount' => 35000,
                'remaining_amount' => 15000,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'name' => 'Budget Infrastructure 2024',
                'description' => 'Budget pour les infrastructures',
                'category' => 'Infrastructure',
                'allocated_amount' => 100000,
                'spent_amount' => 75000,
                'remaining_amount' => 25000,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'name' => 'Budget Personnel 2024',
                'description' => 'Budget pour les salaires du personnel',
                'category' => 'Personnel',
                'allocated_amount' => 200000,
                'spent_amount' => 180000,
                'remaining_amount' => 20000,
                'fiscal_year' => 2024,
                'status' => 'active',
                'created_by' => 1,
            ],
        ];
        
        foreach ($budgets as $budgetData) {
            Budget::create($budgetData);
        }
        
        // Créer des paiements pour les rapports financiers
        $invoices = Invoice::all();
        foreach ($invoices as $invoice) {
            if (rand(0, 1)) { // 50% de chance de paiement
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_number' => 'PAY-' . now()->format('YmdHis') . rand(1000, 9999),
                    'amount' => $invoice->total_amount,
                    'payment_date' => now()->subDays(rand(1, 30)),
                    'payment_method' => ['cash', 'bank_transfer', 'mobile_money'][rand(0, 2)],
                    'reference_number' => 'REF-' . rand(100000, 999999),
                    'notes' => 'Paiement reçu',
                    'received_by' => 1,
                ]);
                
                $invoice->update(['status' => 'paid']);
            }
        }
        
        $this->command->info('✅ Données de rapports créées avec succès !');
        $this->command->info('📊 ' . Exam::count() . ' examens créés');
        $this->command->info('📝 ' . Grade::count() . ' notes créées');
        $this->command->info('📅 ' . Attendance::count() . ' présences créées');
        $this->command->info('💰 ' . Budget::count() . ' budgets créés');
        $this->command->info('💳 ' . Payment::count() . ' paiements créés');
    }
    
    private function getGradeLetter($marks)
    {
        if ($marks >= 90) return 'A';
        if ($marks >= 80) return 'B';
        if ($marks >= 70) return 'C';
        if ($marks >= 60) return 'D';
        return 'F';
    }
}
