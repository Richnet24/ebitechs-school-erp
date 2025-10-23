# 🚀 Déploiement Laravel sur Vercel (SIMPLE)

## 📋 Prérequis
- Compte GitHub
- Compte Vercel (gratuit)

## ⚡ Déploiement en 3 étapes

### 1. Préparer le projet
Créer `vercel.json` :
```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "/public/index.php"
    }
  ],
  "env": {
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "DB_CONNECTION": "sqlite"
  }
}
```

### 2. Upload sur GitHub
```bash
git add .
git commit -m "Deploy to Vercel"
git push origin main
```

### 3. Déployer sur Vercel
1. Aller sur [vercel.com](https://vercel.com)
2. "Import Project"
3. Sélectionner ton repository GitHub
4. Cliquer "Deploy"

## 🎉 Résultat
Ton app sera disponible sur : `https://ton-app.vercel.app`

## 💰 Coût
- **Gratuit** pour les projets personnels
- Bandwidth illimité
