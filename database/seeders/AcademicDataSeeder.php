<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des filières
        $branches = [
            ['name' => 'Informatique', 'code' => 'INFO', 'description' => 'Filière en informatique et technologies'],
            ['name' => 'Gestion', 'code' => 'GEST', 'description' => 'Filière en gestion d\'entreprise'],
            ['name' => 'Électronique', 'code' => 'ELEC', 'description' => 'Filière en électronique et télécommunications'],
            ['name' => 'Mécanique', 'code' => 'MECA', 'description' => 'Filière en mécanique industrielle'],
        ];

        foreach ($branches as $branchData) {
            Branch::create($branchData);
        }

        // Créer des classes
        $classes = [
            ['name' => '1ère Année Info', 'code' => 'INFO-1', 'branch_id' => 1, 'level' => 1],
            ['name' => '2ème Année Info', 'code' => 'INFO-2', 'branch_id' => 1, 'level' => 2],
            ['name' => '3ème Année Info', 'code' => 'INFO-3', 'branch_id' => 1, 'level' => 3],
            ['name' => '1ère Année Gestion', 'code' => 'GEST-1', 'branch_id' => 2, 'level' => 1],
            ['name' => '2ème Année Gestion', 'code' => 'GEST-2', 'branch_id' => 2, 'level' => 2],
            ['name' => '1ère Année Électronique', 'code' => 'ELEC-1', 'branch_id' => 3, 'level' => 1],
            ['name' => '1ère Année Mécanique', 'code' => 'MECA-1', 'branch_id' => 4, 'level' => 1],
        ];

        foreach ($classes as $classData) {
            ClassModel::create($classData);
        }

        // Créer des matières
        $subjects = [
            ['name' => 'Programmation', 'code' => 'PROG', 'description' => 'Programmation informatique', 'branch_id' => 1],
            ['name' => 'Base de données', 'code' => 'BDD', 'description' => 'Gestion des bases de données', 'branch_id' => 1],
            ['name' => 'Réseaux', 'code' => 'RES', 'description' => 'Réseaux informatiques', 'branch_id' => 1],
            ['name' => 'Comptabilité', 'code' => 'COMP', 'description' => 'Comptabilité générale', 'branch_id' => 2],
            ['name' => 'Marketing', 'code' => 'MARK', 'description' => 'Marketing et communication', 'branch_id' => 2],
            ['name' => 'Mathématiques', 'code' => 'MATH', 'description' => 'Mathématiques appliquées', 'branch_id' => 1],
            ['name' => 'Électronique', 'code' => 'ELEC', 'description' => 'Électronique fondamentale', 'branch_id' => 3],
            ['name' => 'Mécanique', 'code' => 'MECA', 'description' => 'Mécanique des solides', 'branch_id' => 4],
        ];

        foreach ($subjects as $subjectData) {
            Subject::create($subjectData);
        }

        // Créer des cours
        $courses = [
            [
                'name' => 'Programmation Python',
                'subject_id' => 1,
                'class_id' => 1,
                'teacher_id' => 1,
                'description' => 'Cours de programmation Python pour débutants',
                'credits' => 3,
                'hours_per_week' => 4,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
            ],
            [
                'name' => 'Base de données MySQL',
                'subject_id' => 2,
                'class_id' => 1,
                'teacher_id' => 2,
                'description' => 'Cours de gestion de bases de données MySQL',
                'credits' => 2,
                'hours_per_week' => 3,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
            ],
            [
                'name' => 'Réseaux TCP/IP',
                'subject_id' => 3,
                'class_id' => 2,
                'teacher_id' => 3,
                'description' => 'Cours de réseaux informatiques',
                'credits' => 3,
                'hours_per_week' => 4,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
            ],
            [
                'name' => 'Comptabilité générale',
                'subject_id' => 4,
                'class_id' => 4,
                'teacher_id' => 4,
                'description' => 'Cours de comptabilité générale',
                'credits' => 4,
                'hours_per_week' => 5,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
            ],
            [
                'name' => 'Marketing digital',
                'subject_id' => 5,
                'class_id' => 4,
                'teacher_id' => 5,
                'description' => 'Cours de marketing digital',
                'credits' => 2,
                'hours_per_week' => 3,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }

        // Créer des utilisateurs pour les enseignants
        $teacherUsers = [
            [
                'name' => 'Jean Mukendi',
                'email' => 'jean.mukendi@ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 789',
                'address' => 'Avenue de la Paix, Bukavu',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Marie Kabila',
                'email' => 'marie.kabila@ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 790',
                'address' => 'Quartier Nyawera, Bukavu',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pierre Mubikay',
                'email' => 'pierre.mubikay@ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 791',
                'address' => 'Avenue du 24 Novembre, Bukavu',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Grace Mukamba',
                'email' => 'grace.mukamba@ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 792',
                'address' => 'Quartier Ibanda, Bukavu',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Kambale',
                'email' => 'david.kambale@ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 793',
                'address' => 'Avenue de la République, Bukavu',
                'email_verified_at' => now(),
            ],
        ];

        $teacherUserIds = [];
        foreach ($teacherUsers as $userData) {
            $user = User::create($userData);
            $teacherUserIds[] = $user->id;
        }

        // Créer des enseignants
        $teachers = [
            [
                'user_id' => $teacherUserIds[0],
                'employee_number' => 'EMP-001',
                'specialization' => 'Informatique',
                'qualification' => 'Master en Informatique',
                'hire_date' => '2020-09-01',
                'salary' => 1500.00,
                'employment_type' => 'full_time',
                'status' => 'active',
            ],
            [
                'user_id' => $teacherUserIds[1],
                'employee_number' => 'EMP-002',
                'specialization' => 'Base de données',
                'qualification' => 'Master en Systèmes d\'Information',
                'hire_date' => '2021-01-15',
                'salary' => 1400.00,
                'employment_type' => 'full_time',
                'status' => 'active',
            ],
            [
                'user_id' => $teacherUserIds[2],
                'employee_number' => 'EMP-003',
                'specialization' => 'Réseaux',
                'qualification' => 'Master en Télécommunications',
                'hire_date' => '2019-09-01',
                'salary' => 1600.00,
                'employment_type' => 'full_time',
                'status' => 'active',
            ],
            [
                'user_id' => $teacherUserIds[3],
                'employee_number' => 'EMP-004',
                'specialization' => 'Comptabilité',
                'qualification' => 'Master en Gestion',
                'hire_date' => '2020-01-01',
                'salary' => 1300.00,
                'employment_type' => 'full_time',
                'status' => 'active',
            ],
            [
                'user_id' => $teacherUserIds[4],
                'employee_number' => 'EMP-005',
                'specialization' => 'Marketing',
                'qualification' => 'Master en Marketing',
                'hire_date' => '2021-09-01',
                'salary' => 1200.00,
                'employment_type' => 'full_time',
                'status' => 'active',
            ],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }

        // Créer des utilisateurs pour les étudiants
        $studentUsers = [
            [
                'name' => 'Alice Mukendi',
                'email' => 'alice.mukendi@student.ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 794',
                'address' => 'Quartier Nyawera, Bukavu',
                'date_of_birth' => '2005-03-15',
                'gender' => 'female',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Kabila',
                'email' => 'bob.kabila@student.ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 795',
                'address' => 'Avenue de la Paix, Bukavu',
                'date_of_birth' => '2004-07-22',
                'gender' => 'male',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Claire Mubikay',
                'email' => 'claire.mubikay@student.ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 796',
                'address' => 'Quartier Ibanda, Bukavu',
                'date_of_birth' => '2005-11-08',
                'gender' => 'female',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Daniel Mukamba',
                'email' => 'daniel.mukamba@student.ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 797',
                'address' => 'Avenue du 24 Novembre, Bukavu',
                'date_of_birth' => '2004-01-30',
                'gender' => 'male',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Eve Kambale',
                'email' => 'eve.kambale@student.ebitechs.edu',
                'password' => Hash::make('password'),
                'phone' => '+243 123 456 798',
                'address' => 'Quartier Nyawera, Bukavu',
                'date_of_birth' => '2005-05-12',
                'gender' => 'female',
                'email_verified_at' => now(),
            ],
        ];

        $studentUserIds = [];
        foreach ($studentUsers as $userData) {
            $user = User::create($userData);
            $studentUserIds[] = $user->id;
        }

        // Créer des étudiants
        $students = [
            [
                'user_id' => $studentUserIds[0],
                'student_number' => 'STU-2023-001',
                'class_id' => 1,
                'parent_id' => null,
                'admission_date' => '2023-09-01',
                'birth_date' => '2005-03-15',
                'birth_place' => 'Bukavu',
                'nationality' => 'CD',
                'religion' => 'Catholique',
                'blood_type' => 'A+',
                'medical_notes' => 'Aucune allergie connue',
                'emergency_contact' => 'Maman: +243 123 456 800',
                'emergency_phone' => '+243 123 456 800',
                'status' => 'active',
            ],
            [
                'user_id' => $studentUserIds[1],
                'student_number' => 'STU-2023-002',
                'class_id' => 1,
                'parent_id' => null,
                'admission_date' => '2023-09-01',
                'birth_date' => '2004-07-22',
                'birth_place' => 'Bukavu',
                'nationality' => 'CD',
                'religion' => 'Protestant',
                'blood_type' => 'O+',
                'medical_notes' => 'Aucune allergie connue',
                'emergency_contact' => 'Papa: +243 123 456 801',
                'emergency_phone' => '+243 123 456 801',
                'status' => 'active',
            ],
            [
                'user_id' => $studentUserIds[2],
                'student_number' => 'STU-2022-001',
                'class_id' => 2,
                'parent_id' => null,
                'admission_date' => '2022-09-01',
                'birth_date' => '2005-11-08',
                'birth_place' => 'Bukavu',
                'nationality' => 'CD',
                'religion' => 'Catholique',
                'blood_type' => 'B+',
                'medical_notes' => 'Aucune allergie connue',
                'emergency_contact' => 'Maman: +243 123 456 802',
                'emergency_phone' => '+243 123 456 802',
                'status' => 'active',
            ],
            [
                'user_id' => $studentUserIds[3],
                'student_number' => 'STU-2023-003',
                'class_id' => 4,
                'parent_id' => null,
                'admission_date' => '2023-09-01',
                'birth_date' => '2004-01-30',
                'birth_place' => 'Bukavu',
                'nationality' => 'CD',
                'religion' => 'Musulman',
                'blood_type' => 'AB+',
                'medical_notes' => 'Aucune allergie connue',
                'emergency_contact' => 'Papa: +243 123 456 803',
                'emergency_phone' => '+243 123 456 803',
                'status' => 'active',
            ],
            [
                'user_id' => $studentUserIds[4],
                'student_number' => 'STU-2023-004',
                'class_id' => 4,
                'parent_id' => null,
                'admission_date' => '2023-09-01',
                'birth_date' => '2005-05-12',
                'birth_place' => 'Bukavu',
                'nationality' => 'CD',
                'religion' => 'Catholique',
                'blood_type' => 'A-',
                'medical_notes' => 'Aucune allergie connue',
                'emergency_contact' => 'Maman: +243 123 456 804',
                'emergency_phone' => '+243 123 456 804',
                'status' => 'active',
            ],
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Créer des examens
        $exams = [
            [
                'course_id' => 1,
                'name' => 'Examen de Programmation Python',
                'description' => 'Examen final de programmation Python',
                'type' => 'final',
                'exam_date' => Carbon::now()->addDays(30),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'total_marks' => 100,
                'passing_marks' => 50,
                'room' => 'Salle A1',
                'instructions' => 'Apportez votre ordinateur portable',
                'status' => 'scheduled',
            ],
            [
                'course_id' => 2,
                'name' => 'Contrôle Base de données',
                'description' => 'Contrôle continu sur les bases de données',
                'type' => 'midterm',
                'exam_date' => Carbon::now()->addDays(15),
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'total_marks' => 50,
                'passing_marks' => 25,
                'room' => 'Salle A2',
                'instructions' => 'Examen sur papier',
                'status' => 'scheduled',
            ],
        ];

        foreach ($exams as $examData) {
            Exam::create($examData);
        }

        // Créer des présences
        $students = Student::all();
        $courses = Course::all();
        
        foreach ($courses as $course) {
            foreach ($students as $student) {
                if (rand(0, 10) < 8) { // 80% de présence
                    Attendance::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'attendance_date' => Carbon::now()->subDays(rand(1, 30)),
                        'status' => 'present',
                        'notes' => null,
                        'marked_by' => rand(1, 3),
                    ]);
                }
            }
        }
    }
}