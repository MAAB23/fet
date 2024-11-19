<?php
include 'db.php'; // Incluir el archivo de conexión que contiene la variable $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos del formulario
    $userId = $_POST['user_id'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verificar que las contraseñas coincidan
    if ($newPassword === $confirmPassword) {
        // Hashear la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE users SET password = :password WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);

        // Bind de los parámetros
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            // Redirigir al login.php después de una actualización exitosa
            echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Hubo un error al actualizar la contraseña.');</script>";
        }
    } else {
        // Mensaje de error si las contraseñas no coinciden
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    }
}
?>
