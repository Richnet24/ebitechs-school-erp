<?php

namespace App\Filament\Actions;

use App\Models\Student;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ExportStudentsReportAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'export_students_report';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Exporter Rapport Élèves')
            ->icon('heroicon-o-document-arrow-down')
            ->color('success')
            ->form([
                Select::make('format')
                    ->label('Format d\'export')
                    ->options([
                        'excel' => 'Excel (.xlsx)',
                        'csv' => 'CSV (.csv)',
                        'pdf' => 'PDF (.pdf)',
                    ])
                    ->required()
                    ->default('excel'),
                
                Select::make('branch_id')
                    ->label('Filière')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Toutes les filières'),
                
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actifs',
                        'inactive' => 'Inactifs',
                        'graduated' => 'Diplômés',
                        'transferred' => 'Transférés',
                        'suspended' => 'Suspendus',
                    ])
                    ->placeholder('Tous les statuts'),
                
                DatePicker::make('admission_date_from')
                    ->label('Date d\'admission (début)'),
                
                DatePicker::make('admission_date_to')
                    ->label('Date d\'admission (fin)'),
            ])
            ->action(function (array $data) {
                try {
                    $query = Student::with(['user', 'class.branch', 'parent']);
                    
                    if ($data['branch_id'] ?? null) {
                        $query->whereHas('class', function ($q) use ($data) {
                            $q->where('branch_id', $data['branch_id']);
                        });
                    }
                    
                    if ($data['status'] ?? null) {
                        $query->where('status', $data['status']);
                    }
                    
                    if ($data['admission_date_from'] ?? null) {
                        $query->where('admission_date', '>=', $data['admission_date_from']);
                    }
                    
                    if ($data['admission_date_to'] ?? null) {
                        $query->where('admission_date', '<=', $data['admission_date_to']);
                    }
                    
                    $students = $query->get();
                    
                    $filename = 'rapport_eleves_' . now()->format('Y-m-d_H-i-s');
                    
                    switch ($data['format']) {
                        case 'excel':
                            $this->exportToExcel($students, $filename);
                            break;
                        case 'csv':
                            $this->exportToCsv($students, $filename);
                            break;
                        case 'pdf':
                            $this->exportToPdf($students, $filename);
                            break;
                    }
                    
                    Notification::make()
                        ->title('Export réussi')
                        ->body('Le rapport a été exporté avec succès')
                        ->success()
                        ->send();
                        
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Erreur d\'export')
                        ->body('Une erreur est survenue lors de l\'export: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    private function exportToExcel($students, $filename)
    {
        $data = $students->map(function ($student) {
            return [
                'Numéro' => $student->student_number,
                'Nom' => $student->user->name,
                'Email' => $student->user->email,
                'Téléphone' => $student->user->phone,
                'Classe' => $student->class->name,
                'Filière' => $student->class->branch->name,
                'Date d\'admission' => $student->admission_date->format('d/m/Y'),
                'Date de naissance' => $student->birth_date->format('d/m/Y'),
                'Nationalité' => $student->nationality,
                'Statut' => $student->status,
                'Parent/Tuteur' => $student->parent?->name,
            ];
        });
        
        // Pour l'instant, on simule l'export Excel
        // En production, vous utiliseriez Laravel Excel
        $this->downloadFile($data, $filename . '.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    private function exportToCsv($students, $filename)
    {
        $data = $students->map(function ($student) {
            return [
                $student->student_number,
                $student->user->name,
                $student->user->email,
                $student->user->phone,
                $student->class->name,
                $student->class->branch->name,
                $student->admission_date->format('d/m/Y'),
                $student->birth_date->format('d/m/Y'),
                $student->nationality,
                $student->status,
                $student->parent?->name,
            ];
        });
        
        $csv = "Numéro,Nom,Email,Téléphone,Classe,Filière,Date d'admission,Date de naissance,Nationalité,Statut,Parent/Tuteur\n";
        foreach ($data as $row) {
            $csv .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }
        
        $this->downloadFile($csv, $filename . '.csv', 'text/csv');
    }

    private function exportToPdf($students, $filename)
    {
        // Pour l'instant, on simule l'export PDF
        // En production, vous utiliseriez DomPDF ou TCPDF
        $html = '<h1>Rapport des Élèves</h1>';
        $html .= '<table border="1"><tr><th>Numéro</th><th>Nom</th><th>Classe</th><th>Statut</th></tr>';
        
        foreach ($students as $student) {
            $html .= '<tr>';
            $html .= '<td>' . $student->student_number . '</td>';
            $html .= '<td>' . $student->user->name . '</td>';
            $html .= '<td>' . $student->class->name . '</td>';
            $html .= '<td>' . $student->status . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        $this->downloadFile($html, $filename . '.pdf', 'application/pdf');
    }

    private function downloadFile($content, $filename, $mimeType)
    {
        return Response::stream(function() use ($content) {
            echo $content;
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
