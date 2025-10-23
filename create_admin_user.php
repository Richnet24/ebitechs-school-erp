<?php
/**
 * Script pour créer un utilisateur admin
 * À exécuter sur le serveur après déploiement
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Création d'un utilisateur admin ===\n";

try {
    // Vérifier si l'utilisateur existe déjà
    $existingUser = User::where('email', 'admin@example.com')->first();
    
    if ($existingUser) {
        echo "✅ Utilisateur admin@example.com existe déjà\n";
        echo "   ID: " . $existingUser->id . "\n";
        echo "   Nom: " . $existingUser->name . "\n";
        echo "   Email vérifié: " . ($existingUser->email_verified_at ? "OUI" : "NON") . "\n";
        
        // Réinitialiser le mot de passe
        $existingUser->password = Hash::make('password');
        $existingUser->email_verified_at = now();
        $existingUser->save();
        echo "   ✅ Mot de passe réinitialisé à 'password'\n";
    } else {
        // Créer un nouvel utilisateur admin
        $user = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Utilisateur admin créé avec succès\n";
        echo "   ID: " . $user->id . "\n";
        echo "   Email: " . $user->email . "\n";
    }
    
    echo "\n🌐 Identifiants de connexion:\n";
    echo "   URL: https://ton-domaine.com/admin\n";
    echo "   Email: admin@example.com\n";
    echo "   Mot de passe: password\n";
    
    echo "\n✅ Script terminé avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}
