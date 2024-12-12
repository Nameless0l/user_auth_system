-- Création de la base de données
CREATE DATABASE IF NOT EXISTS user_auth;
USE user_auth;

-- Création de la table users avec les nouveaux champs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(64) DEFAULT NULL,
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(64) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    login_attempts INT DEFAULT 0,
    last_login_attempt DATETIME DEFAULT NULL,
    account_locked BOOLEAN DEFAULT FALSE,
    lock_expires DATETIME DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
