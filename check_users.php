<?php
/**
 * Script pour vérifier les utilisateurs dans la base de données
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Vérification des utilisateurs ===\n";

try {
    $users = User::all();
    
    if ($users->count() === 0) {
        echo "❌ Aucun utilisateur trouvé dans la base de données\n";
        echo "   Solution: Exécuter 'php artisan db:seed'\n";
    } else {
        echo "✅ " . $users->count() . " utilisateur(s) trouvé(s):\n\n";
        
        foreach ($users as $user) {
            echo "   ID: " . $user->id . "\n";
            echo "   Nom: " . $user->name . "\n";
            echo "   Email: " . $user->email . "\n";
            echo "   Email vérifié: " . ($user->email_verified_at ? "OUI" : "NON") . "\n";
            echo "   Créé le: " . $user->created_at . "\n";
            echo "   ---\n";
        }
    }
    
    echo "\n🔧 Commandes utiles:\n";
    echo "   php artisan db:seed --class=UserSeeder\n";
    echo "   php artisan migrate:fresh --seed\n";
    echo "   php create_admin_user.php\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}