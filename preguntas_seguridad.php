<?php
include 'db.php'; // Incluir el archivo de conexión que contiene la variable $pdo

// Verifica si la conexión PDO está funcionando
if (!$pdo) {
    die("Error en la conexión a la base de datos.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];

    // Consulta para buscar al usuario y obtener las preguntas de seguridad
    $sql = "SELECT id, security_question_1, security_question_2 FROM users WHERE username = :username_or_email OR email = :username_or_email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username_or_email', $usernameOrEmail, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Recupera los resultados
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $user['id'];
        $question1 = $user['security_question_1'];
        $question2 = $user['security_question_2'];

        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Preguntas de Seguridad</title>
            <link rel="stylesheet" href="preguntas.css"> <!-- Incluye el archivo CSS -->
            <script>
                function showSuccessAlert() {
                    alert("Por favor, responde las preguntas de seguridad.");
                }
            </script>
        </head>
        <body onload="showSuccessAlert()">
            <h2>Responde las preguntas de seguridad</h2>
            <form action="verificar_respuestas.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <label><?php echo $question1; ?></label>
                <input type="text" name="answer_1" required>
                <br>
                <label><?php echo $question2; ?></label>
                <input type="text" name="answer_2" required>
                <br>
                <input type="submit" value="Validar">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Usuario no encontrado.');</script>";
    } // <- Este corchete faltaba para cerrar el bloque del `else`
}
?>
