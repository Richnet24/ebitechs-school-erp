<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class FinancialOverviewChart extends ChartWidget
{
    protected ?string $heading = 'Revenus Mensuels';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        try {
            $months = [];
            $revenues = [];
            
            // Générer les 6 derniers mois
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->format('M Y');
                
                $revenue = Payment::whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->sum('amount');
                
                $revenues[] = $revenue;
            }
            
            return [
                'datasets' => [
                    [
                        'label' => 'Revenus ($)',
                        'data' => $revenues,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'borderColor' => '#3B82F6',
                        'borderWidth' => 2,
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $months,
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [
                    [
                        'label' => 'Aucune donnée',
                        'data' => [0],
                        'backgroundColor' => 'rgba(107, 114, 128, 0.1)',
                        'borderColor' => '#6B7280',
                    ],
                ],
                'labels' => ['Configuration en cours'],
            ];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Revenus: $" + context.parsed.y.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}