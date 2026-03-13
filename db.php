<?php
// Load environment variables from db.env file in the project root
$envPath = __DIR__ . '/db.env';
$env = [];

if (file_exists($envPath)) {
    $env = parse_ini_file($envPath, false, INI_SCANNER_RAW);
}

$DB_HOST = $env['DB_HOST'] ?? 'localhost';
$DB_PORT = isset($env['DB_PORT']) ? (int)$env['DB_PORT'] : 3306;
$DB_NAME = $env['DB_NAME'] ?? 'railway';
$DB_USER = $env['DB_USER'] ?? 'root';
$DB_PASS = $env['DB_PASSWORD'] ?? '';

$dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

