<?php
session_start();
include('db.php'); // Incluimos tu archivo de conexión con PDO

// Recibir datos del formulario
$tipo_candidato = $_POST['tipo_candidato'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$foto = $_POST['foto'];
$carrera_programa = $_POST['carrera_programa'];

try {
    if ($tipo_candidato === 'estudiante') {
        // Insertar en la tabla de candidatos (estudiantes)
        $query = "INSERT INTO candidatos (nombre, descripcion, foto, carrera) VALUES (:nombre, :descripcion, :foto, :carrera)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':carrera', $carrera_programa);
    } elseif ($tipo_candidato === 'docente') {
        // Insertar en la tabla de candidatos_docentes (docentes)
        $query = "INSERT INTO candidatos_docentes (nombre_doce, descripcion_doce, foto_doce, programa_doce) VALUES (:nombre, :descripcion, :foto, :programa)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':programa', $carrera_programa);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Mostrar alerta de éxito y redirigir al usuario
        echo "<script>
                alert('Candidato registrado exitosamente.');
                window.location.href = 'register_voter.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar el candidato.');
                window.history.back();
              </script>";
    }
} catch (PDOException $e) {
    echo "<script>
            alert('Error en la operación: " . $e->getMessage() . "');
            window.history.back();
          </script>";
}

?>