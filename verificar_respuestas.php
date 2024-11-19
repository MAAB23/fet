<?php
include 'db.php'; // Incluir el archivo de conexión que contiene la variable $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $answer1 = $_POST['answer_1'];
    $answer2 = $_POST['answer_2'];

    // Consulta para obtener las respuestas de seguridad del usuario
    $sql = "SELECT security_answer_1, security_answer_2 FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $correctAnswer1 = $user['security_answer_1'];
        $correctAnswer2 = $user['security_answer_2'];

        // Validar las respuestas
        if (strtolower($answer1) === strtolower($correctAnswer1) && strtolower($answer2) === strtolower($correctAnswer2)) {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Restablecer Contraseña</title>
                <link rel="stylesheet" href="verificar.css"> <!-- Asegúrate de incluir tu archivo CSS -->
                <script>
                    function showSuccessAlert() {
                        alert("Respuestas correctas. Puedes actualizar tu contraseña.");
                    }
                </script>
            </head>
            <body onload="showSuccessAlert()">
                <h2>Restablecer Contraseña</h2>
                <form action="actualizar_contrasena.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="new_password" required>
                    <br>
                    <label>Confirmar Contraseña:</label>
                    <input type="password" name="confirm_password" required>
                    <br>
                    <input type="submit" value="Actualizar Contraseña">
                </form>
            </body>
            </html>
            <?php
        } else {
            echo "<script>alert('Respuestas incorrectas. Intenta nuevamente.');</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.');</script>";
    }
}
?>
