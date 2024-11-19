
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Candidatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register_voter.css">
</head>
<body>
<div class="container">
    <h2>Registro de Candidato</h2>
    <form action="process_register_voter.php" method="post">
        
        <!-- Seleccionar Tipo de Candidato -->
        <div class="mb-3">
            <label for="tipo_candidato" class="form-label">Tipo de Candidato</label>
            <select id="tipo_candidato" name="tipo_candidato" class="form-select" required>
                <option value="">Selecciona un tipo</option>
                <option value="estudiante">Estudiante</option>
                <option value="docente">Docente</option>
            </select>
        </div>

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>

        <!-- Foto -->
        <div class="mb-3">
            <label for="foto" class="form-label">URL de la Foto</label>
            <input type="text" class="form-control" id="foto" name="foto" required>
        </div>

        <!-- Carrera o Programa -->
        <div class="mb-3">
            <label for="carrera_programa" class="form-label">Carrera o Programa</label>
            <input type="text" class="form-control" id="carrera_programa" name="carrera_programa" required>
        </div>

        <button type="submit" class="btn-link">Registrar</button>
        <a href="start2.php" class="btn-link">volver</a>
    </form>
</div>
</body>
</html>
