<?php
// test_db.php

// Enable error reporting to see detailed error messages if something goes wrong
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require_once 'config/db.php';
    echo "<h3 style='color: green;'>Success: Database connection is working!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Database Connection Failed:</h3> " . $e->getMessage();
}