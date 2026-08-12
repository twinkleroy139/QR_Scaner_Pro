CREATE DATABASE IF NOT EXISTS qr_scanner_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qr_scanner_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS scan_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    scan_type ENUM('scan', 'generate') NOT NULL DEFAULT 'scan',
    content_type ENUM('url', 'text', 'image', 'other') NOT NULL DEFAULT 'text',
    qr_data TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;