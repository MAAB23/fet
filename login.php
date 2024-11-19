<?php

session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $carrera_seleccionada = $_POST['carrera'];  // La carrera o programa seleccionada por el usuario

    // Consulta para obtener el usuario según el correo electrónico
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verificar credenciales y la carrera/programa
    if ($user && password_verify($password, $user['password'])) {

        // Verificar si el usuario ya ha votado antes de intentar actualizar
        if ($user['ha_votado'] == 1) {
            echo "<script>alert('Ya has emitido tu voto. No puedes votar nuevamente.'); window.location.href='login.php';</script>";
            exit(); // Detener el script aquí para que no continúe.
        }

        // Actualizar el estado de votación del usuario
        $sql = "UPDATE users SET ha_votado = 1 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user['id']]); // Aquí usamos $user['id'], no $_SESSION['id']
        
        // Validación según el rol del usuario
        if ($user['rol'] == 'estudiante') {
            // Para estudiantes: Verificar que la carrera seleccionada coincida
            if ($user['carrera'] == $carrera_seleccionada) {
                // Guardar la información en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['carrera'] = $user['carrera']; // Guardar la carrera seleccionada en sesión

                // Redirigir al usuario a la página de bienvenida
                header("Location: welcome.php");
                exit();
            } else {
                // Mostrar error si la carrera no coincide
                echo "<script>alert('Credenciales incorrectas: la carrera seleccionada no coincide.'); window.location.href='login.php';</script>";
                exit();
            }
        } elseif ($user['rol'] == 'docente') {
            // Para docentes: Verificar que el programa seleccionado coincida
            if ($user['programa'] == $carrera_seleccionada) {
                // Guardar la información en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['programa'] = $user['programa']; // Guardar el programa del docente en sesión

                // Redirigir al usuario a la página de bienvenida
                header("Location: welcome.php");
                exit();
            } else {
                // Mostrar error si el programa no coincide
                echo "<script>alert('Credenciales incorrectas: el programa seleccionado no coincide.'); window.location.href='login.php';</script>";
                exit();
            }
        }
    } else {
        // Mostrar error si el correo o la contraseña son incorrectos
        echo "<script>alert('Correo electrónico o contraseña incorrectos.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Iniciar sesión</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="carrera" class="form-label">Carrera/Programa</label>
            <select name="carrera" class="form-select" required>
                <option value="">Selecciona tu carrera o programa</option>
                <option value="SST">SST</option>
                <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                <option value="Ingeniería de Software">Ingeniería de Software</option>
                <option value="Ingeniería de Alimentos">Ingeniería de Alimentos</option>
                <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
            </select>
        </div>
        <button type="submit" class="btn-link">Iniciar sesión</button>
        <a href="recuperar.php" class="btn-link">Restablecer contraseñas</a>
        <a href="index.php" class="btn-link">Volver</a>
    </form>
</div>
</body>
</html>
