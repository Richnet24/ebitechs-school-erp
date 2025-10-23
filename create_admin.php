<?php
/**
 * Script pour créer un utilisateur admin de test
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Création de l'utilisateur admin ===\n";

try {
    // Créer ou mettre à jour l'utilisateur admin
    $user = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Administrateur',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]
    );
    
    echo "✅ Utilisateur admin créé/mis à jour:\n";
    echo "   Email: " . $user->email . "\n";
    echo "   Nom: " . $user->name . "\n";
    echo "   Mot de passe: password\n";
    
    // Vérifier s'il y a des données de test
    $branchesCount = \App\Models\Branch::count();
    $studentsCount = \App\Models\Student::count();
    $teachersCount = \App\Models\Teacher::count();
    
    echo "\n📊 Données dans la base:\n";
    echo "   Branches: $branchesCount\n";
    echo "   Étudiants: $studentsCount\n";
    echo "   Enseignants: $teachersCount\n";
    
    if ($branchesCount == 0) {
        echo "\n⚠️  Aucune donnée de test trouvée.\n";
        echo "   Exécutez: php create_tables.php pour ajouter des données de base\n";
    }
    
    echo "\n🔗 URLs d'accès:\n";
    echo "   Application: http://localhost:8000\n";
    echo "   Admin: http://localhost:8000/admin\n";
    echo "   Connexion: http://localhost:8000/admin/login\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
