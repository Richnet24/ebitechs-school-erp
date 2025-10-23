<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\StockItem;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DSPStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        try {
            return [
                Stat::make('Réquisitions En Attente', $this->getPendingRequisitions())
                    ->description('Nécessitent une approbation')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                
                Stat::make('Bons de Commande Actifs', $this->getActivePurchaseOrders())
                    ->description('En cours de traitement')
                    ->descriptionIcon('heroicon-m-shopping-cart')
                    ->color('info'),
                
                Stat::make('Articles en Stock Bas', $this->getLowStockItems())
                    ->description('Nécessitent un réapprovisionnement')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
                
                Stat::make('Valeur Totale du Stock', $this->getTotalStockValue())
                    ->description('Valeur actuelle')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                
                Stat::make('Mouvements Ce Mois', $this->getMonthlyMovements())
                    ->description('Entrées et sorties')
                    ->descriptionIcon('heroicon-m-arrows-right-left')
                    ->color('primary'),
                
                Stat::make('Taux de Service', $this->getServiceRate())
                    ->description('Réquisitions traitées')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('secondary'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Module DSP en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }

    private function getPendingRequisitions(): int
    {
        try {
            return Requisition::whereIn('status', ['draft', 'submitted'])->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActivePurchaseOrders(): int
    {
        try {
            return PurchaseOrder::whereIn('status', ['sent', 'confirmed'])->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getLowStockItems(): int
    {
        try {
            return StockItem::whereRaw('current_stock <= minimum_stock')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalStockValue(): string
    {
        try {
            $totalValue = StockItem::sum(\DB::raw('current_stock * unit_cost'));
            return '$' . number_format($totalValue, 2);
        } catch (\Exception $e) {
            return '$0.00';
        }
    }

    private function getMonthlyMovements(): int
    {
        try {
            return StockMovement::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getServiceRate(): string
    {
        try {
            $totalRequisitions = Requisition::where('status', '!=', 'draft')->count();
            $completedRequisitions = Requisition::where('status', 'completed')->count();
            
            if ($totalRequisitions == 0) {
                return '0%';
            }
            
            $rate = round(($completedRequisitions / $totalRequisitions) * 100, 1);
            return $rate . '%';
        } catch (\Exception $e) {
            return '0%';
        }
    }
}