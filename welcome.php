<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir la conexión a la base de datos
require 'db.php';

// Obtener el rol del usuario desde la sesión
$rol = $_SESSION['rol'] ?? ''; // 'estudiante' o 'docente'
$carrera = $_SESSION['carrera'] ?? ''; // Para estudiantes
$programa_doce = $_SESSION['programa'] ?? ''; // Para docentes (corregido)

// Verificar que el rol esté establecido correctamente
if (empty($rol)) {
    echo "Error: Rol no definido.";
    exit;
}

// Obtener la lista de candidatos según el rol
if ($rol === 'estudiante') {
    $stmt = $pdo->prepare("SELECT id, nombre, descripcion, foto FROM candidatos WHERE carrera = ?");
    $stmt->execute([$carrera]);
    $candidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else if ($rol === 'docente') {
    // Usar la variable $programa_doce, que es la correcta
    $stmt = $pdo->prepare("SELECT id_doce, nombre_doce, descripcion_doce, foto_doce, programa_doce FROM candidatos_docentes WHERE programa_doce LIKE ?");
    $stmt->execute(['%' . $programa_doce . '%']); // Usar $programa_doce
    $candidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error: Rol no reconocido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fot.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Lista de Candidatos</h1>
    <p>Rol: <?php echo htmlspecialchars($rol); ?></p>

    <form action="procesar_voto.php" method="POST">
        <div class="row">
            <?php if (!empty($candidatos)): ?>
                <?php foreach ($candidatos as $candidato): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <!-- Imagen -->
                            <img src="<?php echo htmlspecialchars($rol == 'estudiante' ? $candidato['foto'] : $candidato['foto_doce']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($rol == 'estudiante' ? $candidato['nombre'] : $candidato['nombre_doce']); ?>">

                            <div class="card-body">
                                <!-- Nombre y descripción -->
                                <h5 class="card-title"><?php echo htmlspecialchars($rol == 'estudiante' ? $candidato['nombre'] : $candidato['nombre_doce']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($rol == 'estudiante' ? $candidato['descripcion'] : $candidato['descripcion_doce']); ?></p>
                                
                                <!-- Radio button para selección -->
                                <input type="radio" name="candidato_id" value="<?php echo htmlspecialchars($rol == 'estudiante' ? $candidato['id'] : $candidato['id_doce']); ?>" required>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay candidatos disponibles para mostrar.</p>
            <?php endif; ?>
        </div>
        
        <!-- Botón de votar -->
        <button type="submit" class="btn btn-primary">Votar</button>
    </form>
    
    <!-- Botón para cerrar sesión -->
    <a href="login.php" class="btn btn-danger">Cerrar sesión</a>
</div>
</body>
</html>









