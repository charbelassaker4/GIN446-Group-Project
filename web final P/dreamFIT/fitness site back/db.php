<?php
// db.php — MySQL connection using PDO for DreamFIT backend

$host = 'localhost';
$db   = 'dreamfit_db';   // make sure this DB exists in phpMyAdmin
$user = 'root';          // XAMPP default
$pass = '';              // XAMPP default (empty password)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
