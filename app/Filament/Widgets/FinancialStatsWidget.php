<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        try {
            return [
                Stat::make('Revenus Totaux', $this->getTotalRevenue())
                    ->description('Ce mois-ci')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
                
                Stat::make('Factures Envoyées', $this->getSentInvoices())
                    ->description('En attente de paiement')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),
                
                Stat::make('Factures En Retard', $this->getOverdueInvoices())
                    ->description('Nécessitent un suivi')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
                
                Stat::make('Budget Utilisé', $this->getBudgetUtilization())
                    ->description('Cette année')
                    ->descriptionIcon('heroicon-m-chart-pie')
                    ->color('warning'),
                
                Stat::make('Paiements Reçus', $this->getPaymentsReceived())
                    ->description('Ce mois-ci')
                    ->descriptionIcon('heroicon-m-credit-card')
                    ->color('primary'),
                
                Stat::make('Taux de Recouvrement', $this->getCollectionRate())
                    ->description('Moyenne générale')
                    ->descriptionIcon('heroicon-m-calculator')
                    ->color('secondary'),
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

    private function getTotalRevenue(): string
    {
        try {
            $total = Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount');
            return '$' . number_format($total, 2);
        } catch (\Exception $e) {
            return '$0.00';
        }
    }

    private function getSentInvoices(): int
    {
        try {
            return Invoice::where('status', 'sent')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getOverdueInvoices(): int
    {
        try {
            return Invoice::where('due_date', '<', now())
                ->where('status', '!=', 'paid')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getBudgetUtilization(): string
    {
        try {
            $totalAllocated = Budget::where('status', 'active')->sum('allocated_amount');
            $totalSpent = Budget::where('status', 'active')->sum('spent_amount');
            
            if ($totalAllocated == 0) {
                return '0%';
            }
            
            $rate = round(($totalSpent / $totalAllocated) * 100, 1);
            return $rate . '%';
        } catch (\Exception $e) {
            return '0%';
        }
    }

    private function getPaymentsReceived(): int
    {
        try {
            return Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCollectionRate(): string
    {
        try {
            $totalInvoices = Invoice::where('status', '!=', 'draft')->count();
            $paidInvoices = Invoice::where('status', 'paid')->count();
            
            if ($totalInvoices == 0) {
                return '0%';
            }
            
            $rate = round(($paidInvoices / $totalInvoices) * 100, 1);
            return $rate . '%';
        } catch (\Exception $e) {
            return '0%';
        }
    }
}
