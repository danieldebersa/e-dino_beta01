<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] !== 1) {
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/../core/models/class.php';
$user = new User();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['class-name'] ?? '';
    $descripcion = $_POST['class-description'] ?? '';

    $codigo = $user->createClass($_SESSION['user_id'], $nombre, $descripcion);

    if ($codigo) {
        header("Location: dashboard.php?message=Clase creada exitosamente");
    } else {
        $error = "Error al crear la clase.";
    }
}

?>
<php
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Crear Clase - E-Dino</title>
        <link rel="stylesheet" href="../assets/css/crear_clase.css">
        <link rel="icon" href="../assets/images/logo.ico">
    </head>

    <body>
        <header class="dashboard-header">
            <div class="header-container">
                <h1 class="logo">
                    <a href="/../index.php">E-Dino</a>
                </h1>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="dashboard.php"><?php echo htmlspecialchars($nombre_usuario); ?></a></li>
                        <li><a href="dashboard.php">Volver al Dashboard</a></li>
                        <li><a href="logout.php">Cerrar Sesion</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
        <h2>Crear Nueva Clase</h2>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="crear_clase.php">
            <label for="class-name">Nombre de la Clase</label>
            <input type="text" id="class-name" name="class-name" required>

            <label for="class-description">Descripcion</label>
            <textarea id="class-description" name="class-description" required></textarea>

            <button type="submit">Crear Clase</button>
        </form>
        </main>
        <footer class="dashboard-footer">
            <div class="footer-container">
                <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
                <p>Desarrollado con por el equipo de E-Dino.</p>
            </div>
        </footer>
    </body>
    </html>