# Ebitechs School ERP

Un système de gestion scolaire moderne et professionnel développé avec Laravel 11 et Filament v4.

## 🚀 Fonctionnalités

### Modules Principaux

#### 1. **Gestion des Utilisateurs et Rôles**
- Système de rôles basé sur Spatie Laravel Permission
- Rôles : Super Admin, DG, DAF, Direction pédagogique, Direction du bien-être, Enseignant, Parent, Élève, Caissier, Magasinier, Logistique
- Authentification multi-rôles avec tableaux de bord adaptés

#### 2. **Module Académique**
- **Filières** : Gestion des branches d'études
- **Classes** : Organisation des classes par filière et niveau
- **Matières** : Configuration des matières avec crédits et heures
- **Cours** : Attribution des cours aux enseignants et classes
- **Emplois du temps** : Planification des cours
- **Examens et notes** : Gestion des évaluations et bulletins
- **Présences** : Suivi de l'assiduité des élèves

#### 3. **Gestion des Élèves**
- Admissions et inscriptions
- Gestion des familles (liens parent-enfant)
- Cartes d'identité numériques avec QR code
- Dossiers complets (notes, discipline, santé, orientation)
- Statistiques de présence

#### 4. **Gestion des Enseignants**
- Assignations par classe/matière
- Intégration avec le module financier (paie)
- Dossiers du personnel
- Système de messagerie interne

#### 5. **Services Partagés (DSP)**
- Workflow complet d'approvisionnement basé sur RACI
- Réquisition → Devis → Bon de Commande → Livraison → Entrée en stock → Sortie → Facture → Reçu → Inventaire
- Gestion des stocks avec alertes de réapprovisionnement
- Validation des workflows par rôle

#### 6. **Module Financier (DAF)**
- Planification budgétaire et trésorerie
- Facturation automatique des élèves
- Reçus PDF avec QR code
- Journaux de caisse et rapprochements bancaires
- Gestion de la paie
- Rapports financiers (mensuels, annuels, bilan, compte de résultat)

#### 7. **Bien-être et Vie Scolaire**
- **Santé** : Dossiers médicaux, consultations, incidents
- **Psychopédagogie** : Suivi psychologique, plans de remédiation
- **Orientation** : Fiches d'orientation, dossiers professionnels
- **Vie scolaire** : Clubs, parascolaires, discipline, événements
- Tableau de suivi bien-être des élèves

#### 8. **Rapports et Tableaux de Bord**
- Widgets Filament avec graphiques pour les KPIs
- Exports Excel et PDF
- Analytics de performance par classe
- Rapports de consommation et budget vs réel

## 🛠️ Technologies Utilisées

- **Backend** : Laravel 11, PHP 8.2+
- **Frontend** : Filament v4, TailwindCSS
- **Base de données** : PostgreSQL (préféré) ou MySQL
- **Authentification** : Laravel Sanctum
- **Permissions** : Spatie Laravel Permission
- **Architecture** : Clean Architecture (Repositories/Services)
- **Standards** : SOLID, PSR-12

## 📋 Prérequis

- PHP 8.2 ou supérieur
- Composer
- PostgreSQL 12+ ou MySQL 8.0+
- Node.js et NPM (pour les assets)

## 🚀 Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd ebitechs-school-erp
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifier le fichier `.env` :
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ebitechs_school_erp
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

5. **Exécuter les migrations et seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compiler les assets**
```bash
npm run build
```

7. **Démarrer le serveur**
```bash
php artisan serve
```

## 👥 Comptes par Défaut

Après l'installation, vous pouvez vous connecter avec :

- **Super Admin** : `admin@ebitechs.cd` / `password`
- **Directeur Général** : `dg@ebitechs.cd` / `password`
- **DAF** : `daf@ebitechs.cd` / `password`

## 📁 Structure du Projet

```
app/
├── Filament/
│   ├── Resources/          # Ressources Filament
│   └── Widgets/           # Widgets de tableau de bord
├── Models/                # Modèles Eloquent
├── Services/              # Services métier
└── Repositories/          # Repositories

database/
├── migrations/            # Migrations de base de données
├── seeders/              # Seeders pour les données de test
└── factories/            # Factories pour les tests

resources/
├── views/                # Vues Blade
└── lang/                 # Fichiers de traduction
```

## 🔐 Système de Permissions

Le système utilise Spatie Laravel Permission avec des rôles prédéfinis :

- **Super Admin** : Accès complet au système
- **DG** : Vue d'ensemble et rapports
- **DAF** : Gestion financière complète
- **Direction pédagogique** : Gestion académique
- **Direction du bien-être** : Suivi des élèves
- **Enseignant** : Gestion de ses classes et élèves
- **Parent** : Suivi de ses enfants
- **Élève** : Consultation de son dossier
- **Caissier** : Gestion des paiements
- **Magasinier** : Gestion des stocks
- **Logistique** : Workflow d'approvisionnement

## 📊 Workflow RACI

Le système implémente le modèle RACI pour les validations :

- **R**esponsible : Prépare le document
- **A**ccountable : Valide le document
- **C**onsulted : Est notifié pour consultation
- **I**nformed : Reçoit une copie

## 🧪 Tests

```bash
php artisan test
```

## 📈 Monitoring et Logs

- Audit trail complet des opérations
- Système de notifications intégré
- Logs détaillés pour le debugging

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou support, contactez l'équipe de développement.

---

**Ebitechs School ERP** - Système de gestion scolaire moderne et professionnel