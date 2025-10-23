<?php
/**
 * Script pour tester l'authentification
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== Test d'authentification ===\n";

try {
    // Test avec admin@ebitechs.edu
    $user = User::where('email', 'admin@ebitechs.edu')->first();
    if ($user) {
        $passwordCheck = Hash::check('password', $user->password);
        echo "✅ admin@ebitechs.edu trouvé\n";
        echo "   Mot de passe 'password' valide: " . ($passwordCheck ? "OUI" : "NON") . "\n";
        
        if ($passwordCheck) {
            // Test de connexion
            Auth::login($user);
            echo "   Connexion réussie: " . (Auth::check() ? "OUI" : "NON") . "\n";
            Auth::logout();
        }
    }
    
    // Test avec admin@example.com
    $user2 = User::where('email', 'admin@example.com')->first();
    if ($user2) {
        $passwordCheck2 = Hash::check('password', $user2->password);
        echo "✅ admin@example.com trouvé\n";
        echo "   Mot de passe 'password' valide: " . ($passwordCheck2 ? "OUI" : "NON") . "\n";
    }
    
    // Vérifier la configuration Filament
    echo "\n🔧 Configuration Filament:\n";
    echo "   Panel ID: " . config('filament.default_filesystem_disk') . "\n";
    echo "   Auth guard: " . config('filament.auth_guard') . "\n";
    
    // Vérifier les routes
    $routes = app('router')->getRoutes();
    $adminRoutes = collect($routes)->filter(function($route) {
        return str_contains($route->uri(), 'admin');
    });
    
    echo "   Routes admin: " . $adminRoutes->count() . "\n";
    
    echo "\n🌐 URLs à tester:\n";
    echo "   http://127.0.0.1:8000/admin\n";
    echo "   http://localhost:8000/admin\n";
    echo "   http://127.0.0.1:8000/admin/login\n";
    echo "   http://localhost:8000/admin/login\n";
    
    echo "\n📋 Identifiants mis à jour:\n";
    echo "   1. admin@ebitechs.edu / password\n";
    echo "   2. admin@example.com / password\n";
    echo "   3. admin@test.com / password\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
