<?php
session_start();
require 'db.php';

$carrera_seleccionada = '';
$tipo_votante = 'Estudiante'; // Valor por defecto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carrera_seleccionada = $_POST['carrera'];
    $tipo_votante = $_POST['tipo_votante'];
}

$resultados = [];

if (!empty($carrera_seleccionada)) {
    if ($tipo_votante === 'Estudiante') {
        $stmt = $pdo->prepare("
            SELECT c.nombre AS nombre_candidato, COUNT(v.id) AS total_votos
            FROM votos v
            INNER JOIN candidatos c ON v.candidato_id = c.id
            WHERE c.carrera = ?
            GROUP BY c.nombre
            ORDER BY total_votos DESC
        ");
    } else { // Es Docente
        $stmt = $pdo->prepare("
            SELECT c.nombre_doce AS nombre_candidato, COUNT(v.id) AS total_votos
            FROM votos v
            INNER JOIN candidatos_docentes c ON v.candidato_id = c.id_doce
            WHERE c.programa_doce = ?
            GROUP BY c.nombre_doce
            ORDER BY total_votos DESC
        ");
    }
    
    $stmt->execute([$carrera_seleccionada]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Convertir los resultados en JSON para Chart.js
$data_json = json_encode($resultados);

$carreras = ['Ingeniería Eléctrica', 'Ingeniería de Software', 'Ingeniería de Alimentos', 'SST', 'Ingeniería Ambiental'];
$tipos_votante = ['Estudiante', 'Docente']; // Tipos de votante
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Votación</title>
    <!-- Estilo Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Archivo de estilos personalizado -->
    <link rel="stylesheet" href="res.css">
</head>
<body>
<div class="container">
    <h1 class="text-center">Resultados de Votación</h1>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="carrera" class="form-label">Selecciona una carrera:</label>
            <select name="carrera" id="carrera" class="form-select">
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo htmlspecialchars($carrera); ?>" <?php echo (isset($carrera_seleccionada) && $carrera_seleccionada == $carrera) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($carrera); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo_votante" class="form-label">Selecciona un tipo de votante:</label>
            <select name="tipo_votante" id="tipo_votante" class="form-select">
                <?php foreach ($tipos_votante as $tipo): ?>
                    <option value="<?php echo htmlspecialchars($tipo); ?>" <?php echo ($tipo_votante == $tipo) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tipo); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Ver Resultados</button>
        <button type="button" onclick="window.location.href='index.php'" class="btn btn-success w-100">Volver</button>
    </form>

    <?php if (!empty($resultados)): ?>
        <h2 class="text-center">Resultados para <?php echo htmlspecialchars($carrera_seleccionada); ?> (<?php echo htmlspecialchars($tipo_votante); ?>)</h2>
        
        <!-- Gráfico -->
        <canvas id="graficoResultados" width="400" height="200"></canvas>
        <script>
            const data = <?php echo $data_json; ?>;
            const labels = data.map(item => item.nombre_candidato);
            const valores = data.map(item => item.total_votos);

            const ctx = document.getElementById('graficoResultados').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(40, 167, 69, 0.8)');
            gradient.addColorStop(1, 'rgba(72, 201, 176, 0.2)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total de Votos',
                        data: valores,
                        backgroundColor: gradient,
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#333',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: ${tooltipItem.raw} votos`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#333',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            ticks: {
                                beginAtZero: true,
                                color: '#333',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)'
                            },
                            display: false  // No mostrar el eje Y
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>
</body>
</html>
