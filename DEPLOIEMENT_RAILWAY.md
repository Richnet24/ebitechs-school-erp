# 🚀 Déploiement Laravel sur Railway (MODERNE)

## 📋 Prérequis
- Compte GitHub
- Compte Railway (gratuit)

## ⚡ Déploiement en 2 étapes

### 1. Préparer le projet
Créer `railway.json` :
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "healthcheckPath": "/",
    "healthcheckTimeout": 100,
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

### 2. Déployer sur Railway
1. Aller sur [railway.app](https://railway.app)
2. "Deploy from GitHub repo"
3. Sélectionner ton repository
4. Railway détecte automatiquement Laravel
5. Cliquer "Deploy"

## 🎉 Résultat
Ton app sera disponible sur : `https://ton-app.railway.app`

## 💰 Coût
- **Gratuit** : $5 de crédit/mois
- Suffisant pour un ERP scolaire
