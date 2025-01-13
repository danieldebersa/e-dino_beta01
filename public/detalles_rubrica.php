<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$rubric_id = isset($_GET['rubric_id']) ? intval($_GET['rubric_id']) : 0;

$user = new User();
$rubrica_detalles = $user->getRubricDetails($rubric_id);

if (!$rubrica_detalles) {
    header("Location: dashboard.php");
    exit();
}

// Mensajes de éxito o error
if (isset($_GET['mensaje'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_GET['mensaje']) . '</p>';
}

if (isset($_GET['error'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
}

$user->closeConnection();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Rúbrica</title>
    <link rel="stylesheet" href="../assets/css/manage_classes.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($rubrica_detalles[0]['nombre']); ?></h1>
        <nav>
            <a href="ver_rubricas.php">Volver a Mis Rúbricas</a>
        </nav>
    </header>
    <main>
        <h2>Criterios</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Criterio</th>
                    <th>Descripción</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rubrica_detalles as $criterio): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($criterio['criterio_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($criterio['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($criterio['nivel']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
