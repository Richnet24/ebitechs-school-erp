<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        try {
            return [
                Stat::make('Total des Filières', $this->getBranchCount())
                    ->description('Filières actives')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('primary'),
                
                Stat::make('Total des Classes', $this->getClassCount())
                    ->description('Classes actives')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('success'),
                
                Stat::make('Total des Élèves', $this->getStudentCount())
                    ->description('Élèves actifs')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),
                
                Stat::make('Total des Enseignants', $this->getTeacherCount())
                    ->description('Enseignants actifs')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('warning'),
                
                Stat::make('Total des Utilisateurs', $this->getUserCount())
                    ->description('Utilisateurs actifs')
                    ->descriptionIcon('heroicon-m-user')
                    ->color('secondary'),
                
                Stat::make('Taux d\'Occupation', $this->getOccupancyRate())
                    ->description('Moyenne des classes')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('danger'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Base de données en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }

    private function getBranchCount(): int
    {
        try {
            return Branch::where('is_active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getClassCount(): int
    {
        try {
            return ClassModel::where('is_active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getStudentCount(): int
    {
        try {
            return Student::where('status', 'active')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTeacherCount(): int
    {
        try {
            return Teacher::where('status', 'active')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUserCount(): int
    {
        try {
            return User::where('is_active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getOccupancyRate(): string
    {
        $classes = ClassModel::where('is_active', true)->get();
        
        if ($classes->isEmpty()) {
            return '0%';
        }

        $totalCapacity = $classes->sum('capacity');
        $totalStudents = $classes->sum(function ($class) {
            return $class->students()->count();
        });

        if ($totalCapacity === 0) {
            return '0%';
        }

        $rate = round(($totalStudents / $totalCapacity) * 100, 1);
        return $rate . '%';
    }
}
