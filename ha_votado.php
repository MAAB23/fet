<?php
session_start();
require 'db.php';

// Verificar que el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lógica para registrar el voto...
// Supongamos que ya has registrado el voto aquí

// Actualizar el estado del usuario para marcar que ya votó
$sql = "UPDATE users SET ha_votado = 1 WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);

// Destruir la sesión y redirigir a la página de agradecimiento
session_destroy();
echo "<script>alert('Gracias por votar.'); window.location.href='login.php';</script>";
exit();
?>
