<?php
$host = 'localhost';
$dbname = 'the-art-nook';
$user = 'root';
$pass = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci"
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass, $options);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
