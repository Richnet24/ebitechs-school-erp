# 🚀 Déploiement Laravel sur Hostinger (SIMPLE)

## 📋 Prérequis
- Compte Hostinger (à partir de 1€/mois)
- Accès cPanel

## ⚡ Déploiement en 4 étapes

### 1. Préparer le projet
```bash
# Compresser sans node_modules et vendor
zip -r ebitechs-deploy.zip . -x "node_modules/*" "vendor/*" ".git/*" "*.log"
```

### 2. Upload via cPanel
1. **cPanel** → File Manager
2. **Naviguer** vers `public_html`
3. **Uploader** `ebitechs-deploy.zip`
4. **Extraire** le contenu

### 3. Configuration automatique
Créer `setup_hostinger.php` :
```php
<?php
// Script de configuration automatique pour Hostinger
echo "=== Configuration Hostinger ===\n";

// Installer Composer
if (!file_exists('composer.phar')) {
    echo "Installation de Composer...\n";
    copy('https://getcomposer.org/installer', 'composer-setup.php');
    $hash = 'dac665fdc4f2d7c0acb4c2e927053c1c8b2d34d2d8f1f832d0b5f832d0b5f832d0';
    if (hash_file('sha384', 'composer-setup.php') === $hash) {
        include 'composer-setup.php';
        unlink('composer-setup.php');
    }
}

// Installer les dépendances
echo "Installation des dépendances...\n";
exec('php composer.phar install --optimize-autoloader --no-dev');

// Configuration .env
echo "Configuration .env...\n";
$env = file_get_contents('.env.example');
$env = str_replace('APP_URL=http://localhost', 'APP_URL=https://ton-domaine.com', $env);
$env = str_replace('DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite', $env);
file_put_contents('.env', $env);

// Générer la clé
exec('php artisan key:generate');

// Migrations et seeders
exec('php artisan migrate --force');
exec('php artisan db:seed --force');

echo "✅ Configuration terminée !\n";
echo "🌐 Accès admin : https://ton-domaine.com/admin/login\n";
echo "📧 Email : admin@ebitechs.edu\n";
echo "🔑 Mot de passe : password\n";
?>
```

### 4. Exécuter la configuration
```bash
php setup_hostinger.php
```

## 🎉 Résultat
Ton app sera disponible sur : `https://ton-domaine.com`

## 💰 Coût
- **À partir de 1€/mois**
- Domaine gratuit inclus
- SSL gratuit
