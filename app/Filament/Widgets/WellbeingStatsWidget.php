<?php

namespace App\Filament\Widgets;

use App\Models\Club;
use App\Models\DisciplineRecord;
use App\Models\HealthRecord;
use App\Models\PsychologicalRecord;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WellbeingStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        try {
            return [
                Stat::make('Dossiers Médicaux', $this->getHealthRecordsCount())
                    ->description('Consultations médicales')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('success'),
                
                Stat::make('Sessions Psychologiques', $this->getPsychologicalRecordsCount())
                    ->description('Sessions de suivi')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('info'),
                
                Stat::make('Clubs Actifs', $this->getActiveClubsCount())
                    ->description('Clubs scolaires')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('primary'),
                
                Stat::make('Incidents Disciplinaires', $this->getDisciplineRecordsCount())
                    ->description('Incidents en cours')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('warning'),
                
                Stat::make('Élèves en Suivi', $this->getStudentsInFollowUpCount())
                    ->description('Suivi médical/psychologique')
                    ->descriptionIcon('heroicon-m-eye')
                    ->color('secondary'),
                
                Stat::make('Taux de Participation', $this->getClubParticipationRate())
                    ->description('Dans les clubs')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('success'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Configuration', 'En cours...')
                    ->description('Module Bien-être en cours de configuration')
                    ->descriptionIcon('heroicon-m-cog-6-tooth')
                    ->color('warning'),
            ];
        }
    }

    private function getHealthRecordsCount(): int
    {
        try {
            return HealthRecord::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getPsychologicalRecordsCount(): int
    {
        try {
            return PsychologicalRecord::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActiveClubsCount(): int
    {
        try {
            return Club::where('is_active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDisciplineRecordsCount(): int
    {
        try {
            return DisciplineRecord::where('status', 'open')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getStudentsInFollowUpCount(): int
    {
        try {
            // Élèves avec des dossiers médicaux ou psychologiques récents
            $healthStudents = HealthRecord::where('record_date', '>=', now()->subDays(30))
                ->distinct('student_id')
                ->count();
            
            $psychStudents = PsychologicalRecord::where('session_date', '>=', now()->subDays(30))
                ->distinct('student_id')
                ->count();
            
            return max($healthStudents, $psychStudents);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getClubParticipationRate(): string
    {
        try {
            $totalStudents = Student::where('status', 'active')->count();
            $clubMembers = \DB::table('club_members')
                ->where('is_active', true)
                ->distinct('student_id')
                ->count();
            
            if ($totalStudents === 0) {
                return '0%';
            }
            
            $rate = round(($clubMembers / $totalStudents) * 100, 1);
            return $rate . '%';
        } catch (\Exception $e) {
            return '0%';
        }
    }
}