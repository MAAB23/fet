<?php 
// start2.php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="start2.css"> 
    <script>
        function redirectToLogin(role) {
            if (role === "admin") {
                window.location.href = "register.php"; // Redirigir a register.php
            } else if (role === "user") {
                window.location.href = "register_voter.php"; // Redirigir a register_voter.php
           // }else if (role === "index") {
              //  window.location.href = "index.php"; // Redirigir a index.php
            }
            
        }


    </script>
</head>
<body>
    <div class="container text-center">
        <h2>Bienvenido Administrador</h2>
        <div class="role-container">
            <div class="role-block admin-block" onclick="redirectToLogin('admin')">
                <img src="https://cdn-icons-png.flaticon.com/256/1177/1177577.png" alt="Administrador" class="role-icon">
                <p>REGISTRAR VOTANTE</p>
            </div>
            <div class="role-block user-block" onclick="redirectToLogin('user')">
                <img src="https://static.vecteezy.com/system/resources/previews/000/550/731/non_2x/user-icon-vector.jpg" alt="Docente/Estudiante" class="role-icon">
                <p>REGISTRAR CANDIDATO</p>
            </div>
            <a href="resultados.php" class="btn-resultados">Ver Resultados</a>
            <a href="index.php" class="ii">volver</a>
            <a href="admin_preguntas.php" class="btn-link">agregar preguntas</a>
</body>
</html>
