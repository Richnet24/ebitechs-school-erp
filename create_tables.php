<?php

// Script pour créer manuellement les tables dans SQLite
echo "=== Création manuelle des tables SQLite ===\n";

try {
    // Connexion à la base de données SQLite
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie\n";
    
    // Créer la table branches
    $sql = "CREATE TABLE IF NOT EXISTS branches (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(255) UNIQUE NOT NULL,
        description TEXT,
        color VARCHAR(255) DEFAULT '#3B82F6',
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Table 'branches' créée\n";
    
    // Créer la table classes
    $sql = "CREATE TABLE IF NOT EXISTS classes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(255) UNIQUE NOT NULL,
        branch_id INTEGER NOT NULL,
        level INTEGER DEFAULT 1,
        capacity INTEGER DEFAULT 40,
        teacher_id INTEGER,
        description TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
        FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($sql);
    echo "Table 'classes' créée\n";
    
    // Créer la table subjects
    $sql = "CREATE TABLE IF NOT EXISTS subjects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(255) UNIQUE NOT NULL,
        description TEXT,
        credits INTEGER DEFAULT 1,
        hours_per_week INTEGER DEFAULT 1,
        branch_id INTEGER NOT NULL,
        color VARCHAR(255) DEFAULT '#10B981',
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "Table 'subjects' créée\n";
    
    // Insérer des données de test
    $sql = "INSERT OR IGNORE INTO branches (name, code, description, color) VALUES 
        ('Sciences et Technologies', 'ST', 'Branche Sciences et Technologies', '#3B82F6'),
        ('Lettres et Philosophie', 'LP', 'Branche Lettres et Philosophie', '#10B981'),
        ('Économie et Gestion', 'EG', 'Branche Économie et Gestion', '#F59E0B')";
    
    $pdo->exec($sql);
    echo "Données de test insérées dans 'branches'\n";
    
    echo "\n=== Configuration terminée avec succès ===\n";
    echo "Les tables principales ont été créées.\n";
    echo "Vous pouvez maintenant accéder à l'application.\n";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
