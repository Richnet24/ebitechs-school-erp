# 🚀 Guide de Déploiement - Ebitechs School ERP

## 📋 Prérequis Hébergement

### Hébergeur recommandé
- **OVH** (Kimsufi, Web Hosting)
- **Hostinger** 
- **Ionos** (1&1)
- **Infomaniak**

### Configuration requise
- **PHP 8.2+** avec extensions : `mbstring`, `openssl`, `pdo`, `sqlite3`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `curl`, `zip`
- **Composer** (ou accès Terminal cPanel)
- **cPanel** avec File Manager
- **SSL gratuit** (Let's Encrypt)

## 🛠️ Étapes de Déploiement

### Étape 1 : Préparation locale
```bash
# Dans ton projet local
# 1. Compresser le projet (sans node_modules, vendor, .git)
zip -r ebitechs-deploy.zip . -x "node_modules/*" "vendor/*" ".git/*" "*.log"
```

### Étape 2 : Upload sur le serveur
1. **Connexion cPanel** → File Manager
2. **Naviguer vers** `/home/ton_compte/`
3. **Créer un dossier** `app` (ou utiliser le dossier public_html)
4. **Uploader** le fichier `ebitechs-deploy.zip`
5. **Extraire** le contenu dans le dossier `app`

### Étape 3 : Configuration du domaine
1. **cPanel** → Domains → Addon Domains (ou Subdomains)
2. **Créer** `ton-domaine.com` pointant vers `/home/ton_compte/app/public`
3. **Attendre** la propagation DNS (5-30 minutes)

### Étape 4 : Déploiement automatisé
```bash
# Via Terminal cPanel ou SSH
cd /home/ton_compte/app
php deploy.php
```

### Étape 5 : Configuration finale

#### A. Fichier .env
Éditer le fichier `.env` avec tes informations :
```env
APP_NAME="Ebitechs School ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-domaine.com

DB_CONNECTION=sqlite
DB_DATABASE=/home/ton_compte/app/database/database.sqlite

# Email SMTP (remplacer par tes infos)
MAIL_MAILER=smtp
MAIL_HOST=mail.ton-domaine.com
MAIL_PORT=587
MAIL_USERNAME=noreply@ton-domaine.com
MAIL_PASSWORD=ton_mot_de_passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ton-domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### B. SSL Let's Encrypt
1. **cPanel** → SSL/TLS → Let's Encrypt
2. **Sélectionner** ton domaine
3. **Activer** SSL automatiquement

#### C. Cron Jobs (optionnel)
**cPanel** → Cron Jobs → Ajouter :
```bash
# Toutes les minutes
* * * * * /usr/bin/php /home/ton_compte/app/artisan schedule:run >> /dev/null 2>&1
```

## 🔧 Configuration Avancée

### Base de données MySQL (optionnel)
Si tu préfères MySQL au lieu de SQLite :

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ton_nom_bdd
DB_USERNAME=ton_utilisateur
DB_PASSWORD=ton_mot_de_passe
```

Puis créer la base via **cPanel** → MySQL Databases

### Optimisations Performance
```bash
# Cache des configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimisation Composer
composer install --optimize-autoloader --no-dev
```

## 🧪 Test du Déploiement

### Vérifications
1. **Accès site** : `https://ton-domaine.com`
2. **Accès admin** : `https://ton-domaine.com/admin`
3. **Connexion** : `admin@ebitechs.edu` / `password`
4. **Upload fichiers** : Tester l'upload d'images
5. **Emails** : Tester l'envoi d'emails

### Identifiants par défaut
- **Email** : `admin@ebitechs.edu`
- **Mot de passe** : `password`

⚠️ **IMPORTANT** : Change ces identifiants dès la première connexion !

**Note** : Si tu ne peux pas te connecter, exécute `php create_admin_user.php` sur le serveur pour créer un utilisateur admin avec l'email `admin@example.com`.

## 🚨 Résolution de Problèmes

### Erreur 500
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Permissions
chmod -R 755 storage bootstrap/cache
chmod 644 database/database.sqlite
```

### Problème de permissions
```bash
# Réparer les permissions
find storage -type f -exec chmod 644 {} \;
find storage -type d -exec chmod 755 {} \;
```

### Base de données corrompue
```bash
# Recréer la base
rm database/database.sqlite
php artisan migrate --force
php artisan db:seed --force
```

## 📞 Support

Si tu rencontres des problèmes :
1. **Vérifier** les logs dans `storage/logs/laravel.log`
2. **Contacter** le support de ton hébergeur
3. **Tester** en local d'abord [[memory:5371624]]

## 🎯 Prochaines Étapes

Après le déploiement réussi :
1. **Configurer** les emails SMTP
2. **Ajouter** tes données réelles
3. **Configurer** les sauvegardes automatiques
4. **Monitorer** les performances
5. **Mettre à jour** régulièrement

---

**🎉 Félicitations ! Ton application Ebitechs School ERP est maintenant en ligne !**
