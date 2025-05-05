<?php
$host = 'localhost';
$db   = 'quiz_db';  // Your database name
$user = 'root';     // Your MySQL username
$pass = '';         // Your MySQL password
$charset = 'utf8mb4';

// Set up the DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle errors
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
