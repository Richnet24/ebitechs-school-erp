<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\ClassModel;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class StudentEnrollmentChart extends ChartWidget
{
    protected ?string $heading = 'Inscriptions par Filière';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        try {
            $branches = Branch::withCount('students')->get();
            
            return [
                'datasets' => [
                    [
                        'label' => 'Nombre d\'élèves',
                        'data' => $branches->pluck('students_count'),
                        'backgroundColor' => [
                            '#3B82F6', // Bleu
                            '#10B981', // Vert
                            '#F59E0B', // Jaune
                            '#EF4444', // Rouge
                            '#8B5CF6', // Violet
                            '#06B6D4', // Cyan
                            '#84CC16', // Lime
                            '#F97316', // Orange
                        ],
                        'borderColor' => [
                            '#1E40AF',
                            '#059669',
                            '#D97706',
                            '#DC2626',
                            '#7C3AED',
                            '#0891B2',
                            '#65A30D',
                            '#EA580C',
                        ],
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $branches->pluck('name'),
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [
                    [
                        'label' => 'Aucune donnée',
                        'data' => [0],
                        'backgroundColor' => ['#6B7280'],
                    ],
                ],
                'labels' => ['Configuration en cours'],
            ];
        }
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": " + context.parsed + " élèves"; }',
                    ],
                ],
            ],
        ];
    }
}