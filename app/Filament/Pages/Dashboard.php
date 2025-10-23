<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StudentEnrollmentChart;
use App\Filament\Widgets\FinancialOverviewChart;
use App\Filament\Widgets\AcademicPerformanceChart;
use App\Filament\Widgets\RecentActivitiesTable;
use App\Filament\Widgets\WellbeingStatsWidget;
use App\Filament\Widgets\DSPStatsWidget;
use App\Filament\Widgets\AttendanceReportWidget;
use App\Filament\Widgets\FinancialReportWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Tableau de Bord';

    protected static ?string $title = 'Ebitechs School ERP';
    
    public static function getNavigationGroup(): ?string
    {
        return 'Tableau de Bord';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            StudentEnrollmentChart::class,
            FinancialOverviewChart::class,
            AcademicPerformanceChart::class,
            WellbeingStatsWidget::class,
            DSPStatsWidget::class,
            AttendanceReportWidget::class,
            FinancialReportWidget::class,
            RecentActivitiesTable::class,
        ];
    }

    public function getColumns(): array|int
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
