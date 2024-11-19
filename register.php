<?php
session_start();
require 'db.php';

// Código para registrar, actualizar y eliminar usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registro de nuevo usuario
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $rol = $_POST['rol'];
        $carrera = $rol === 'estudiante' ? $_POST['carrera'] : null;
        $programa = $rol === 'docente' ? $_POST['programa'] : null;

        if (!preg_match("/^[A-Za-z ]+$/", $username)) {
            echo "<script>alert('El nombre de usuario solo puede contener letras y espacios.');</script>";
            exit;
        }

        // Verificar si el usuario ya está registrado
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            echo "<script>alert('El usuario ya está registrado.');</script>";
        } else {
            $sql = "INSERT INTO users (username, password, email, rol, carrera, programa) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$username, $password, $email, $rol, $carrera, $programa])) {
                echo "<script>alert('Usuario registrado con éxito.');</script>";
            } else {
                echo "<script>alert('Error al registrar usuario.');</script>";
            }
        }
    }
    // Actualizar usuario existente
    elseif (isset($_POST['update']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $username = isset($_POST['username']) ? trim($_POST['username']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $rol = isset($_POST['rol']) ? $_POST['rol'] : null;
        $carrera = $rol === 'estudiante' && isset($_POST['carrera']) ? $_POST['carrera'] : null;
        $programa = $rol === 'docente' && isset($_POST['programa']) ? $_POST['programa'] : null;

        if ($username && $email && $rol) {
            $sql = "UPDATE users SET username = ?, email = ?, rol = ?, carrera = ?, programa = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$username, $email, $rol, $carrera, $programa, $id])) {
                echo "<script>alert('Usuario actualizado con éxito.');</script>";
            } else {
                echo "<script>alert('Error al actualizar usuario.');</script>";
            }
        } else {
            echo "<script>alert('Faltan datos para actualizar el usuario.');</script>";
        }
    }
}

// Eliminar usuario
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo "<script>alert('Usuario eliminado correctamente.');</script>";
}

// Obtener todos los usuarios para mostrarlos en la tabla
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario y gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <script>
        function toggleFields() {
            var rol = document.getElementById("rol").value;
            var carreraField = document.getElementById("carrera");
            var programaField = document.getElementById("programa");

            if (rol === "estudiante") {
                carreraField.required = true;
                programaField.required = false;
                programaField.value = '';
                programaField.disabled = true;
                carreraField.disabled = false;
            } else if (rol === "docente") {
                carreraField.required = false;
                carreraField.value = '';
                carreraField.disabled = true;
                programaField.required = true;
                programaField.disabled = false;
            } else {
                carreraField.required = false;
                programaField.required = false;
                carreraField.disabled = true;
                programaField.disabled = true;
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2 class="mt-5">Registro de Usuario</h2>
    <form action="register.php" method="POST">
        <input type="hidden" name="register" value="1">
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" id="username" name="username" pattern="[A-Za-z]+" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select" onchange="toggleFields()" required>
                <option value="">Selecciona tu rol</option>
                <option value="estudiante">Estudiante</option>
                <option value="docente">Docente</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="carrera" class="form-label">Carrera</label>
            <select name="carrera" id="carrera" class="form-select" disabled>
                <option value="">Selecciona tu carrera (solo si eres estudiante)</option>
                <option value="Ingeniería de Software">Ingeniería de Software</option>
                <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
                <option value="SST">SST</option>
                <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                <option value="Ingeniería de Alimentos">Ingeniería de Alimentos</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="programa" class="form-label">Programa</label>
            <select name="programa" id="programa" class="form-select" disabled>
                <option value="">Selecciona tu programa (solo si eres docente)</option>
                <option value="Ingeniería de Software">Ingeniería de Software</option>
                <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
                <option value="SST">SST</option>
                <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                <option value="Ingeniería de Alimentos">Ingeniería de Alimentos</option>
            </select>
        </div>
        <button type="submit" class="btn-link">Registrar</button>
        <a href="start2.php" class="btn-link">volver</a>
    </form>

    <?php
    // Mostrar formulario de edición si se recibe el parámetro `edit`
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if ($user) {
            ?>
            <h2 class="mt-5">Editar Usuario</h2>
            <form action="register.php" method="POST">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" id="rol" class="form-select" onchange="toggleFields()" required>
                        <option value="estudiante" <?= $user['rol'] === 'estudiante' ? 'selected' : ''; ?>>Estudiante</option>
                        <option value="docente" <?= $user['rol'] === 'docente' ? 'selected' : ''; ?>>Docente</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="carrera" class="form-label">Carrera</label>
                    <select name="carrera" id="carrera" class="form-select" <?= $user['rol'] === 'estudiante' ? '' : 'disabled'; ?>>
                        <option value="">Selecciona tu carrera (solo si eres estudiante)</option>
                        <option value="Ingeniería de Software" <?= $user['carrera'] === 'Ingeniería de Software' ? 'selected' : ''; ?>>Ingeniería de Software</option>
                        <option value="Ingeniería Ambiental" <?= $user['carrera'] === 'Ingeniería Ambiental' ? 'selected' : ''; ?>>Ingeniería Ambiental</option>
                        <option value="SST" <?= $user['carrera'] === 'SST' ? 'selected' : ''; ?>>SST</option>
                        <option value="Ingeniería Eléctrica" <?= $user['carrera'] === 'Ingeniería Eléctrica' ? 'selected' : ''; ?>>Ingeniería Eléctrica</option>
                        <option value="Ingeniería de Alimentos" <?= $user['carrera'] === 'Ingeniería de Alimentos' ? 'selected' : ''; ?>>Ingeniería de Alimentos</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="programa" class="form-label">Programa</label>
                    <select name="programa" id="programa" class="form-select" <?= $user['rol'] === 'docente' ? '' : 'disabled'; ?>>
                        <option value="">Selecciona tu programa (solo si eres docente)</option>
                        <option value="Ingeniería de Software" <?= $user['programa'] === 'Ingeniería de Software' ? 'selected' : ''; ?>>Ingeniería de Software</option>
                        <option value="Ingeniería Ambiental" <?= $user['programa'] === 'Ingeniería Ambiental' ? 'selected' : ''; ?>>Ingeniería Ambiental</option>
                        <option value="SST" <?= $user['programa'] === 'SST' ? 'selected' : ''; ?>>SST</option>
                        <option value="Ingeniería Eléctrica" <?= $user['programa'] === 'Ingeniería Eléctrica' ? 'selected' : ''; ?>>Ingeniería Eléctrica</option>
                        <option value="Ingeniería de Alimentos" <?= $user['programa'] === 'Ingeniería de Alimentos' ? 'selected' : ''; ?>>Ingeniería de Alimentos</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
            <?php
        } // Esta llave cierra el if ($user)
    } // Esta llave cierra el if (isset($_GET['edit']))
    ?>

    <h2 class="mt-5">Lista de Usuarios</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Carrera</th>
            <th>Programa</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['rol']); ?></td>
                <td><?= htmlspecialchars($user['carrera']); ?></td>
                <td><?= htmlspecialchars($user['programa']); ?></td>
                <td>
                    <a href="register.php?edit=<?= $user['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="register.php?delete=<?= $user['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
 
</body>
</html>
