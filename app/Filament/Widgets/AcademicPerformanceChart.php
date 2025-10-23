<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use Filament\Widgets\ChartWidget;

class AcademicPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Performance Académique';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        try {
            $grades = Grade::all();
            
            if ($grades->isEmpty()) {
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
            
            // Calculer les moyennes par matière
            $subjectAverages = $grades->groupBy('exam.course.subject.name')
                ->map(function ($subjectGrades) {
                    return $subjectGrades->avg('marks_obtained');
                })
                ->sortDesc()
                ->take(8); // Top 8 matières
            
            return [
                'datasets' => [
                    [
                        'label' => 'Moyenne (%)',
                        'data' => $subjectAverages->values()->toArray(),
                        'backgroundColor' => [
                            '#10B981', // Vert
                            '#3B82F6', // Bleu
                            '#F59E0B', // Jaune
                            '#EF4444', // Rouge
                            '#8B5CF6', // Violet
                            '#06B6D4', // Cyan
                            '#84CC16', // Lime
                            '#F97316', // Orange
                        ],
                        'borderColor' => [
                            '#059669',
                            '#1E40AF',
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
                'labels' => $subjectAverages->keys()->toArray(),
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
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Moyenne: " + context.parsed.y.toFixed(1) + "%"; }',
                    ],
                ],
            ],
        ];
    }
}