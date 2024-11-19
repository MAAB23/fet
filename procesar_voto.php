<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$rol = $_SESSION['rol'];
$candidato_id = $_POST['candidato_id'];


$stmt = $pdo->prepare("SELECT * FROM votos WHERE user_id = ? AND rol = ?");
$stmt->execute([$user_id, $rol]);
$voto = $stmt->fetch();

if ($voto) {
    echo "<script>alert('Ya has votado como $rol.')window.location.href='login.php';</script>";
    exit;
}

$stmt = $pdo->prepare("INSERT INTO votos (user_id, candidato_id, rol) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $candidato_id, $rol]);

echo "<script>alert('Voto registrado con éxito.');window.location.href='login.php';</script>";
?>


