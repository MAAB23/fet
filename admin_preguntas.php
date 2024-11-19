<?php
include 'db.php'; // Incluir archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos del formulario
    $email = $_POST['email'];  // Usamos el correo en lugar del ID
    $question1 = $_POST['question_1'];
    $question2 = $_POST['question_2'];
    $answer1 = $_POST['answer_1'];
    $answer2 = $_POST['answer_2'];

    // Actualizar las preguntas y respuestas de seguridad en la base de datos usando el correo electrónico
    $sql = "UPDATE users SET 
            security_question_1 = :question_1, 
            security_question_2 = :question_2, 
            security_answer_1 = :answer_1, 
            security_answer_2 = :answer_2 
            WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    // Bind de los parámetros
    $stmt->bindParam(':question_1', $question1, PDO::PARAM_STR);
    $stmt->bindParam(':question_2', $question2, PDO::PARAM_STR);
    $stmt->bindParam(':answer_1', $answer1, PDO::PARAM_STR);
    $stmt->bindParam(':answer_2', $answer2, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        echo "<script>alert('Preguntas de seguridad y respuestas actualizadas correctamente.');</script>";
    } else {
        echo "<script>alert('Hubo un error al actualizar las preguntas o respuestas.');</script>";
    }
} else {
    // Si el formulario no se envía, mostrar preguntas existentes (si las hay)
    if (isset($_GET['email'])) {
        $email = $_GET['email'];

        // Obtener las preguntas y respuestas actuales del usuario
        $sql = "SELECT security_question_1, security_question_2, security_answer_1, security_answer_2 FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $question1 = $user['security_question_1'];
            $question2 = $user['security_question_2'];
            $answer1 = $user['security_answer_1'];
            $answer2 = $user['security_answer_2'];
        } else {
            echo "<script>alert('No se encontraron preguntas para este usuario.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Preguntas de Seguridad</title>
    <link rel="stylesheet" href="admin.css"> <!-- Asegúrate de incluir tu archivo CSS -->
</head>
<body>
    <h2>Modificar Preguntas de Seguridad</h2>
    <form action="admin_preguntas.php" method="post">
        <label for="email">Correo Electrónico del Usuario:</label>
        <input type="email" name="email" value="<?php echo $email ?? ''; ?>" required>
        <br>

        <label for="question_1">Pregunta de seguridad 1:</label>
        <input type="text" name="question_1" value="<?php echo $question1 ?? ''; ?>" required>
        <br>

        <label for="question_2">Pregunta de seguridad 2:</label>
        <input type="text" name="question_2" value="<?php echo $question2 ?? ''; ?>" required>
        <br>

        <label for="answer_1">Respuesta 1:</label>
        <input type="text" name="answer_1" value="<?php echo $answer1 ?? ''; ?>" required>
        <br>

        <label for="answer_2">Respuesta 2:</label>
        <input type="text" name="answer_2" value="<?php echo $answer2 ?? ''; ?>" required>
        <br>

<input type="submit" value="Actualizar Preguntas">
</form>

<!-- Botón de Volver -->
<form action="start2.php" method="get">
<button type="submit">Volver</button>
</form>
</body>
</html>