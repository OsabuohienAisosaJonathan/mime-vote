<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $config = require __DIR__ . '/config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    echo "DB Connected.\n";
    
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(", ", $tables) . "\n";
    
    if (in_array('roles', $tables)) {
        $stmt = $pdo->query("SELECT count(*) FROM roles");
        echo "Roles count: " . $stmt->fetchColumn() . "\n";
    }
} catch (PDOException $e) {
    echo "PDO Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
}
