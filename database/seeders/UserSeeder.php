<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur admin par défaut
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@ebitechs.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Créer des utilisateurs supplémentaires
        $users = [
            [
                'name' => 'Directeur Académique',
                'email' => 'directeur@ebitechs.edu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Comptable',
                'email' => 'comptable@ebitechs.edu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Secrétaire',
                'email' => 'secretaire@ebitechs.edu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Infirmière',
                'email' => 'infirmiere@ebitechs.edu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Psychologue',
                'email' => 'psychologue@ebitechs.edu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}