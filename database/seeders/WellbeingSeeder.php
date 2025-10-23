<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\DisciplineRecord;
use App\Models\HealthRecord;
use App\Models\PsychologicalRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class WellbeingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des clubs
        $clubs = [
            [
                'name' => 'Club de Football',
                'description' => 'Club de football pour les élèves passionnés de sport',
                'category' => 'sport',
                'max_members' => 25,
                'meeting_schedule' => 'Mercredi 15h-17h',
                'location' => 'Terrain de sport',
                'is_active' => true,
            ],
            [
                'name' => 'Club de Théâtre',
                'description' => 'Club de théâtre pour développer l\'expression artistique',
                'category' => 'culture',
                'max_members' => 20,
                'meeting_schedule' => 'Vendredi 16h-18h',
                'location' => 'Salle de spectacle',
                'is_active' => true,
            ],
            [
                'name' => 'Club de Sciences',
                'description' => 'Club scientifique pour les passionnés de sciences',
                'category' => 'academic',
                'max_members' => 15,
                'meeting_schedule' => 'Mardi 14h-16h',
                'location' => 'Laboratoire de sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Club de Musique',
                'description' => 'Club de musique pour les mélomanes',
                'category' => 'music',
                'max_members' => 18,
                'meeting_schedule' => 'Jeudi 15h-17h',
                'location' => 'Salle de musique',
                'is_active' => true,
            ],
            [
                'name' => 'Club d\'Environnement',
                'description' => 'Club pour la protection de l\'environnement',
                'category' => 'environment',
                'max_members' => 12,
                'meeting_schedule' => 'Lundi 13h-15h',
                'location' => 'Jardin de l\'école',
                'is_active' => true,
            ],
        ];

        foreach ($clubs as $clubData) {
            // Assigner un superviseur aléatoire parmi les enseignants
            $supervisor = User::whereHas('roles', function ($query) {
                $query->where('name', 'Enseignant');
            })->inRandomOrder()->first();
            
            if ($supervisor) {
                $clubData['supervisor_id'] = $supervisor->id;
                Club::firstOrCreate(
                    ['name' => $clubData['name']],
                    $clubData
                );
            }
        }

        // Créer des dossiers médicaux d'exemple
        $students = Student::with('user')->take(10)->get();
        
        foreach ($students as $student) {
            // Créer quelques dossiers médicaux
            HealthRecord::create([
                'student_id' => $student->id,
                'record_date' => now()->subDays(rand(1, 30)),
                'type' => ['consultation', 'vaccination', 'medical_checkup'][rand(0, 2)],
                'description' => 'Consultation de routine pour ' . $student->user->name,
                'diagnosis' => 'État de santé normal',
                'treatment' => 'Aucun traitement particulier',
                'medication' => null,
                'notes' => 'Élève en bonne santé',
                'recorded_by' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'Direction du bien-être');
                })->first()?->id ?? User::first()->id,
            ]);
        }

        // Créer des sessions psychologiques d'exemple
        $psychologist = User::whereHas('roles', function ($query) {
            $query->where('name', 'Direction du bien-être');
        })->first() ?? User::first();

        foreach ($students->take(5) as $student) {
            PsychologicalRecord::create([
                'student_id' => $student->id,
                'session_date' => now()->subDays(rand(1, 15)),
                'type' => ['assessment', 'counseling', 'follow_up'][rand(0, 2)],
                'description' => 'Session de suivi psychologique pour ' . $student->user->name,
                'observations' => 'Élève motivé et participatif',
                'recommendations' => 'Continuer le suivi régulier',
                'follow_up_actions' => 'Prochaine session dans 2 semaines',
                'notes' => 'Session productive',
                'psychologist_id' => $psychologist->id,
            ]);
        }

        // Créer quelques incidents disciplinaires d'exemple
        foreach ($students->take(3) as $student) {
            DisciplineRecord::create([
                'student_id' => $student->id,
                'incident_date' => now()->subDays(rand(1, 10)),
                'type' => ['minor', 'major'][rand(0, 1)],
                'title' => 'Retard répété',
                'description' => 'L\'élève a été en retard plusieurs fois cette semaine',
                'actions_taken' => 'Entretien avec l\'élève et les parents',
                'consequences' => 'Avertissement écrit',
                'follow_up' => 'Surveillance renforcée',
                'status' => ['open', 'resolved'][rand(0, 1)],
                'reported_by' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'Enseignant');
                })->first()?->id ?? User::first()->id,
                'handled_by' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'Direction pédagogique');
                })->first()?->id ?? User::first()->id,
            ]);
        }

        // Assigner des élèves aux clubs
        $clubs = Club::all();
        $students = Student::all();

        foreach ($clubs as $club) {
            $membersCount = rand(5, min($club->max_members, 15));
            $selectedStudents = $students->random($membersCount);
            
            foreach ($selectedStudents as $student) {
                $club->members()->attach($student->id, [
                    'role' => ['member', 'secretary', 'treasurer'][rand(0, 2)],
                    'joined_date' => now()->subDays(rand(1, 60)),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Module Bien-être configuré avec succès !');
        $this->command->info('📊 ' . Club::count() . ' clubs créés');
        $this->command->info('🏥 ' . HealthRecord::count() . ' dossiers médicaux créés');
        $this->command->info('🧠 ' . PsychologicalRecord::count() . ' sessions psychologiques créées');
        $this->command->info('⚠️ ' . DisciplineRecord::count() . ' incidents disciplinaires créés');
    }
}