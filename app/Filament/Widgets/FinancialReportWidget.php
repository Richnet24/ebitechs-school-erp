<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Budget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialReportWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Rapport Financier';

    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        try {
            // Revenus du mois
            $monthlyRevenue = Payment::whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month)
                ->sum('amount');
            
            // Factures en attente
            $pendingInvoices = Invoice::where('status', 'sent')->sum('total_amount');
            
            // Factures en retard
            $overdueInvoices = Invoice::where('status', 'overdue')->sum('total_amount');
            
            // Budget total alloué
            $totalBudget = Budget::where('status', 'active')->sum('allocated_amount');
            
            // Budget dépensé
            $spentBudget = Budget::where('status', 'active')->sum('spent_amount');
            
            // Pourcentage du budget utilisé
            $budgetUsage = $totalBudget > 0 ? round(($spentBudget / $totalBudget) * 100, 1) : 0;
            
            return [
                Stat::make('Revenus du Mois', '$' . number_format($monthlyRevenue, 0))
                    ->description('Paiements reçus')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
                
                Stat::make('Factures en Attente', '$' . number_format($pendingInvoices, 0))
                    ->description('En attente de paiement')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                
                Stat::make('Factures en Retard', '$' . number_format($overdueInvoices, 0))
                    ->description('Paiements en retard')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
                
                Stat::make('Budget Utilisé', $budgetUsage . '%')
                    ->description('Sur ' . number_format($totalBudget, 0) . '$ alloués')
                    ->descriptionIcon('heroicon-m-chart-pie')
                    ->color($budgetUsage > 90 ? 'danger' : ($budgetUsage > 75 ? 'warning' : 'success')),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Module financier en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }
}
