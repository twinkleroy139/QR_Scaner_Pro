<?php
// init_tables.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

echo "<h2>Executing Database Setup on Aiven...</h2>";

$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;",

    "scan_history" => "CREATE TABLE IF NOT EXISTS scan_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        scan_type ENUM('scan', 'generate') NOT NULL DEFAULT 'scan',
        content_type ENUM('url', 'text', 'image', 'other') NOT NULL DEFAULT 'text',
        qr_data TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;"
];

foreach ($tables as $name => $sql) {
    try {
        $pdo->exec($sql);
        echo "<p style='color: green;'>✔ Table <strong>$name</strong> created successfully.</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✖ Failed creating <strong>$name</strong>: " . $e->getMessage() . "</p>";
    }
}

echo "<h3>Setup Complete! <a href='auth.php'>Go to Login / Register</a></h3>";