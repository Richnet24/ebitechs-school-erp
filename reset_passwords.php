<?php
/**
 * Script pour réinitialiser le mot de passe admin
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Réinitialisation des mots de passe admin ===\n";

try {
    // Réinitialiser admin@ebitechs.edu
    $ebitechsUser = User::where('email', 'admin@ebitechs.edu')->first();
    if ($ebitechsUser) {
        $ebitechsUser->password = Hash::make('password');
        $ebitechsUser->save();
        echo "✅ Mot de passe réinitialisé pour admin@ebitechs.edu\n";
        echo "   Email: admin@ebitechs.edu\n";
        echo "   Mot de passe: password\n";
    } else {
        echo "❌ Utilisateur admin@ebitechs.edu non trouvé\n";
    }
    
    // Réinitialiser admin@example.com
    $exampleUser = User::where('email', 'admin@example.com')->first();
    if ($exampleUser) {
        $exampleUser->password = Hash::make('password');
        $exampleUser->save();
        echo "✅ Mot de passe réinitialisé pour admin@example.com\n";
        echo "   Email: admin@example.com\n";
        echo "   Mot de passe: password\n";
    } else {
        echo "❌ Utilisateur admin@example.com non trouvé\n";
    }
    
    // Créer un nouvel utilisateur admin si nécessaire
    $newAdmin = User::firstOrCreate(
        ['email' => 'admin@test.com'],
        [
            'name' => 'Admin Test',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]
    );
    
    echo "✅ Utilisateur admin@test.com créé/vérifié\n";
    echo "   Email: admin@test.com\n";
    echo "   Mot de passe: password\n";
    
    echo "\n🔗 URLs de test:\n";
    echo "   http://127.0.0.1:8000/admin/login\n";
    echo "   http://localhost:8000/admin/login\n";
    
    echo "\n📋 Identifiants à essayer:\n";
    echo "   1. admin@ebitechs.edu / password\n";
    echo "   2. admin@example.com / password\n";
    echo "   3. admin@test.com / password\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
