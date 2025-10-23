<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthRecord;
use App\Models\PsychologicalRecord;
use App\Models\Club;
use App\Models\DisciplineRecord;
use App\Models\Student;
use Carbon\Carbon;

class WellbeingDataSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();

        // Créer des dossiers de santé
        foreach ($students as $student) {
            HealthRecord::create([
                'student_id' => $student->id,
                'record_date' => Carbon::now()->subMonths(rand(1, 6)),
                'type' => 'medical_checkup',
                'description' => 'Bilan de santé général - Élève en bonne santé',
                'diagnosis' => 'Aucun problème de santé détecté',
                'treatment' => 'Aucun traitement nécessaire',
                'medication' => 'Aucun médicament prescrit',
                'notes' => 'Élève en bonne santé générale, vaccinations à jour',
                'recorded_by' => 4, // Infirmière
            ]);
        }

        // Créer des dossiers psychologiques
        foreach ($students as $student) {
            if (rand(0, 1)) { // 50% des étudiants ont un dossier psychologique
                PsychologicalRecord::create([
                    'student_id' => $student->id,
                    'session_date' => Carbon::now()->subDays(rand(1, 90)),
                    'type' => 'assessment',
                    'description' => 'Évaluation psychologique standard - Élève bien adapté',
                    'observations' => 'Comportement normal en classe, performance académique satisfaisante',
                    'recommendations' => 'Continuer le suivi régulier',
                    'follow_up_actions' => 'Rendez-vous de suivi dans 3 mois',
                    'notes' => 'Élève en bonne santé psychologique',
                    'psychologist_id' => 5, // Psychologue
                ]);
            }
        }

        // Créer des clubs
        $clubs = [
            [
                'name' => 'Club de Programmation',
                'description' => 'Club pour les passionnés de programmation et développement',
                'category' => 'académique',
                'meeting_schedule' => 'Mercredi 15h-17h',
                'location' => 'Salle informatique A',
                'supervisor_id' => 1,
                'max_members' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Club de Théâtre',
                'description' => 'Club d\'art dramatique et d\'expression',
                'category' => 'culturel',
                'meeting_schedule' => 'Vendredi 14h-16h',
                'location' => 'Salle polyvalente',
                'supervisor_id' => 2,
                'max_members' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Club de Football',
                'description' => 'Club de football pour les amateurs de sport',
                'category' => 'sportif',
                'meeting_schedule' => 'Mardi et Jeudi 16h-18h',
                'location' => 'Terrain de sport',
                'supervisor_id' => 3,
                'max_members' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Club de Débat',
                'description' => 'Club de débat et d\'éloquence',
                'category' => 'académique',
                'meeting_schedule' => 'Lundi 15h-17h',
                'location' => 'Salle de conférence',
                'supervisor_id' => 4,
                'max_members' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Club de Musique',
                'description' => 'Club de musique et chant',
                'category' => 'culturel',
                'meeting_schedule' => 'Jeudi 15h-17h',
                'location' => 'Salle de musique',
                'supervisor_id' => 5,
                'max_members' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($clubs as $clubData) {
            Club::create($clubData);
        }

        // Créer des dossiers de discipline
        $disciplineTypes = [
            'retard', 'absence', 'comportement', 'travail', 'respect', 'autre'
        ];

        $disciplineSeverities = ['léger', 'modéré', 'grave'];
        $disciplineActions = [
            'avertissement', 'retenue', 'travaux d\'intérêt général', 
            'convocation parents', 'exclusion temporaire', 'exclusion définitive'
        ];

        foreach ($students as $student) {
            if (rand(0, 3) === 0) { // 25% des étudiants ont un dossier de discipline
                $incidentCount = rand(1, 3);
                
                for ($i = 0; $i < $incidentCount; $i++) {
                    DisciplineRecord::create([
                        'student_id' => $student->id,
                        'incident_date' => Carbon::now()->subDays(rand(1, 60)),
                        'incident_type' => $disciplineTypes[rand(0, count($disciplineTypes) - 1)],
                        'description' => 'Incident de discipline - ' . $disciplineTypes[rand(0, count($disciplineTypes) - 1)],
                        'severity' => $disciplineSeverities[rand(0, count($disciplineSeverities) - 1)],
                        'action_taken' => $disciplineActions[rand(0, count($disciplineActions) - 1)],
                        'teacher_reported' => 'Enseignant ' . ($i + 1),
                        'witnesses' => 'Élèves présents',
                        'student_response' => 'Reconnaissance de la faute',
                        'parent_notified' => rand(0, 1),
                        'follow_up_required' => rand(0, 1),
                        'follow_up_date' => rand(0, 1) ? Carbon::now()->addDays(rand(1, 30)) : null,
                        'resolved' => rand(0, 1),
                        'resolved_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                        'created_by' => rand(1, 3),
                    ]);
                }
            }
        }

        // Créer quelques dossiers de discipline supplémentaires
        for ($i = 0; $i < 5; $i++) {
            DisciplineRecord::create([
                'student_id' => $students->random()->id,
                'incident_date' => Carbon::now()->subDays(rand(1, 30)),
                'incident_type' => $disciplineTypes[rand(0, count($disciplineTypes) - 1)],
                'description' => 'Retard répété en cours',
                'severity' => 'léger',
                'action_taken' => 'avertissement',
                'teacher_reported' => 'Enseignant responsable',
                'witnesses' => 'Élèves de la classe',
                'student_response' => 'Promesse d\'amélioration',
                'parent_notified' => false,
                'follow_up_required' => true,
                'follow_up_date' => Carbon::now()->addDays(7),
                'resolved' => false,
                'resolved_date' => null,
                'created_by' => rand(1, 3),
            ]);
        }
    }
}