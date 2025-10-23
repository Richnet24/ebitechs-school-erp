<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Créer le rôle Super Admin s'il n'existe pas
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        // Créer l'utilisateur admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ebitechs.cd'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@ebitechs.cd',
                'password' => bcrypt('password'),
                'phone' => '+243 999 999 999',
                'address' => 'Bukavu, RDC',
                'is_active' => true,
            ]
        );

        // Assigner le rôle
        $admin->assignRole('Super Admin');

        $this->info('Utilisateur admin créé avec succès !');
        $this->info('Email: admin@ebitechs.cd');
        $this->info('Mot de passe: password');
    }
}