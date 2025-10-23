<?php

// Script simple pour créer les tables essentielles
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Table branches
$pdo->exec("CREATE TABLE IF NOT EXISTS branches (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    color VARCHAR(255) DEFAULT '#3B82F6',
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Table classes
$pdo->exec("CREATE TABLE IF NOT EXISTS classes (
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
)");

// Table subjects
$pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
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
)");

// Insérer des données de test
$pdo->exec("INSERT OR IGNORE INTO branches (name, code, description, color) VALUES 
    ('Sciences et Technologies', 'ST', 'Branche Sciences et Technologies', '#3B82F6'),
    ('Lettres et Philosophie', 'LP', 'Branche Lettres et Philosophie', '#10B981'),
    ('Économie et Gestion', 'EG', 'Branche Économie et Gestion', '#F59E0B')");

echo "Tables créées avec succès!\n";
