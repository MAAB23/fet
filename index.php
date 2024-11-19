
<?php
// start.php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="start.css"> <!-- Enlace al archivo CSS corregido -->
    <!-- Enlace a la fuente Poppins de Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <script>
        function redirectToLogin(role) {
            if (role === "admin") {
                window.location.href = "admin_login.php";
            } else if (role === "user") {
                window.location.href = "login.php";
            }
        }
    </script>
</head>
<body>
    <div class="login-container text-center">
        <h2>Bienvenido al Sistema de Votacion FET</h2>
        <p>Selecciona tu rol para continuar</p>
        
        <div class="role-container d-flex justify-content-center">
            <div class="role-block" onclick="redirectToLogin('admin')">
                <img src="https://thumbs.dreamstime.com/z/man-computer-icon-vector-symbol-white-background-eps-151241707.jpg?w=360" alt="Administrador" class="role-icon" >
                <p>ADMINISTRADOR</p>
            </div>
            <div class="role-block" onclick="redirectToLogin('user')">
                <img src="https://th.bing.com/th/id/OIP.whCidPzur2buqy88AwWPhQAAAA?rs=1&pid=ImgDetMain" alt="Docente/Estudiante" class="role-icon">
                <p>DOCENTE/ESTUDIANTE</p>
            </div>
        </div>
    </div>
</body>
</html>