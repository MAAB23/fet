<?php
$host = 'localhost';
$dbname = 'login_php';
$username = 'root';  // Cambia por tu usuario de MySQL
$password = '';      // Cambia por tu contraseña de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
?>