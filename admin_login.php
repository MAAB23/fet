<?php
// Iniciar la sesión
session_start();

// Valores predeterminados de usuario y contraseña
$default_username = 'admin';
$default_password = 'rootFET2024';

// Lógica del sistema: inicio de sesión, panel y cierre de sesión
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Cerrar sesión
    session_destroy();
    header('Location: admin_login.php');
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar inicio de sesión
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validar credenciales con los valores predeterminados
    if ($username === $default_username && $password === $default_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $default_username;
        header('Location: start2.php'); // Redirigir al archivo start2.php si el inicio de sesión es exitoso
        exit();
    } else {
        // Agregar alerta con mensaje de error de usuario o contraseña incorrectos
        echo '<script>alert("Usuario o contraseña incorrectos.");</script>';
    }
}

// Renderizado de la página
    // Formulario de inicio de sesión
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio de Sesión</title>
        <link rel="stylesheet" href="loginadmin.css">
    </head>
    <body>
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="admin_login.php">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Iniciar sesión</button>
            <a href="index.php" class="btn-link">Volver</a>|
        </form>
    </body>
    </html>
    <?php

?>
