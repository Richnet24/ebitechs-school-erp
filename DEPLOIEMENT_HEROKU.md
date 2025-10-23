# 🚀 Déploiement Laravel sur Heroku (ULTRA-SIMPLE)

## 📋 Prérequis
- Compte GitHub (gratuit)
- Compte Heroku (gratuit)

## ⚡ Déploiement en 5 étapes

### 1. Préparer le projet
```bash
# Dans ton projet local
composer install --optimize-autoloader --no-dev
```

### 2. Créer les fichiers Heroku
Créer `Procfile` (sans extension) :
```
web: vendor/bin/heroku-php-apache2 public/
```

Créer `app.json` :
```json
{
  "name": "Ebitechs School ERP",
  "description": "Système de gestion scolaire",
  "keywords": ["laravel", "school", "erp"],
  "website": "https://ton-app.herokuapp.com",
  "repository": "https://github.com/ton-username/ebitechs-school-erp",
  "logo": "https://via.placeholder.com/150",
  "success_url": "/admin/login",
  "scripts": {
    "postdeploy": "php artisan migrate --force && php artisan db:seed --force"
  },
  "env": {
    "APP_ENV": {
      "description": "Environment",
      "value": "production"
    },
    "APP_DEBUG": {
      "description": "Debug mode",
      "value": "false"
    },
    "APP_KEY": {
      "description": "Application key",
      "generator": "secret"
    },
    "DB_CONNECTION": {
      "description": "Database connection",
      "value": "sqlite"
    }
  },
  "formation": {
    "web": {
      "quantity": 1,
      "size": "free"
    }
  },
  "addons": []
}
```

### 3. Upload sur GitHub
```bash
git init
git add .
git commit -m "Initial commit"
git push origin main
```

### 4. Déployer sur Heroku
1. Aller sur [heroku.com](https://heroku.com)
2. Créer une nouvelle app
3. Connecter GitHub
4. Sélectionner ton repository
5. Cliquer "Deploy"

### 5. Configurer les variables
Dans Heroku Dashboard → Settings → Config Vars :
```
APP_NAME=Ebitechs School ERP
APP_URL=https://ton-app.herokuapp.com
MAIL_MAILER=log
```

## 🎉 Résultat
Ton app sera disponible sur : `https://ton-app.herokuapp.com`

## 💰 Coût
- **Gratuit** pour les petites applications
- Limite : 550 heures/mois (suffisant pour un ERP scolaire)
