<?php
// Test script to diagnose backend errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $config = require __DIR__ . '/config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo json_encode(['error' => 'Database "utevs_db" exists but "users" table is missing!']);
        exit;
    }
    
    echo json_encode(['success' => 'Database connected and users table exists!']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'DB Connection Failed: ' . $e->getMessage()]);
}
