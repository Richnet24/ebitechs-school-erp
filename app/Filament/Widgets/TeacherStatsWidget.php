<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Student;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TeacherStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Mes Statistiques';

    protected function getStats(): array
    {
        $teacherId = auth()->id();
        
        try {
            $courses = Course::where('teacher_id', $teacherId)->count();
            $students = Student::whereHas('class.courses', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->count();
            
            $todayAttendance = Attendance::whereHas('course', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->where('attendance_date', now()->toDateString())->count();
            
            return [
                Stat::make('Mes Cours', $courses)
                    ->description('Cours assignés')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('primary'),
                
                Stat::make('Mes Élèves', $students)
                    ->description('Élèves dans mes cours')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success'),
                
                Stat::make('Présences Aujourd\'hui', $todayAttendance)
                    ->description('Présences marquées')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('info'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Tableau de bord en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }
}
