<?php
// install_db.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

try {
    $schema = "
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
    ";

    $pdo->exec($schema);
    echo "<h2 style='color: green; font-family: sans-serif;'>Success: All database tables created on Aiven successfully!</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color: red; font-family: sans-serif;'>Database Setup Failed: " . $e->getMessage() . "</h2>";
}