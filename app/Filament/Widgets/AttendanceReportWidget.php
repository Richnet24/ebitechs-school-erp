<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceReportWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Rapport de Présence';

    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        try {
            $totalStudents = Student::where('status', 'active')->count();
            $today = now()->toDateString();
            
            // Présences d'aujourd'hui
            $presentToday = Attendance::where('attendance_date', $today)
                ->where('status', 'present')
                ->count();
            
            // Absences d'aujourd'hui
            $absentToday = Attendance::where('attendance_date', $today)
                ->where('status', 'absent')
                ->count();
            
            // Retards d'aujourd'hui
            $lateToday = Attendance::where('attendance_date', $today)
                ->where('status', 'late')
                ->count();
            
            // Taux de présence global (7 derniers jours)
            $weekStart = now()->subDays(7)->toDateString();
            $totalAttendance = Attendance::where('attendance_date', '>=', $weekStart)->count();
            $totalPresent = Attendance::where('attendance_date', '>=', $weekStart)
                ->where('status', 'present')
                ->count();
            
            $attendanceRate = $totalAttendance > 0 ? round(($totalPresent / $totalAttendance) * 100, 1) : 0;
            
            return [
                Stat::make('Présents Aujourd\'hui', $presentToday)
                    ->description('Sur ' . $totalStudents . ' élèves')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                
                Stat::make('Absents Aujourd\'hui', $absentToday)
                    ->description('Absences non justifiées')
                    ->descriptionIcon('heroicon-m-x-circle')
                    ->color('danger'),
                
                Stat::make('Retards Aujourd\'hui', $lateToday)
                    ->description('Élèves en retard')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                
                Stat::make('Taux de Présence', $attendanceRate . '%')
                    ->description('7 derniers jours')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color($attendanceRate >= 90 ? 'success' : ($attendanceRate >= 80 ? 'warning' : 'danger')),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Module de présence en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }
}
