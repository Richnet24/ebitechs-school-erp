# 🚀 GUIDE ULTRA-SIMPLE : Déployer Laravel sur Heroku

## ⚡ En 10 minutes chrono !

### Étape 1 : Créer les fichiers (2 min)
Créer `Procfile` (sans extension) :
```
web: vendor/bin/heroku-php-apache2 public/
```

### Étape 2 : Upload sur GitHub (3 min)
```bash
git init
git add .
git commit -m "Deploy to Heroku"
git push origin main
```

### Étape 3 : Déployer sur Heroku (5 min)
1. Aller sur [heroku.com](https://heroku.com)
2. "Create new app"
3. "Connect to GitHub"
4. Sélectionner ton repository
5. "Deploy branch"

### Étape 4 : Configurer (1 min)
Dans Heroku Dashboard → Settings → Config Vars :
```
APP_NAME=Ebitechs School ERP
APP_URL=https://ton-app.herokuapp.com
```

## 🎉 C'est tout !
Ton app sera sur : `https://ton-app.herokuapp.com`

## 🔑 Identifiants
- Email : `admin@ebitechs.edu`
- Mot de passe : `password`

## 💡 Conseils
- Le nom de l'app doit être unique (ex: `ebitechs-school-jm`)
- Heroku crée automatiquement la base de données
- Les migrations se lancent automatiquement
