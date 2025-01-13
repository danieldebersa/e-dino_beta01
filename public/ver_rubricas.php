<?php
require_once '../core/models/manage_classes.php';

try {
    if (!isset($_GET['clase_id']) || empty($_GET['clase_id'])) {
        throw new Exception("ID de clase no proporcionado.");
    }

    $class_id = intval($_GET['clase_id']); // Convierte a entero

    $manageClasses = new ManageClasses();
    $rubricas = $manageClasses->getRubricsByClassId($class_id);
    $manageClasses->closeConnection();

} catch (Exception $e) {
    echo "Se produjo un error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Rúbricas - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/manage_classes.css">
    <link rel="icon" href="../assets/images/logo.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>

<body>
    <header class="header">
        <h1>Rúbricas de la Clase</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Volver al Dashboard</a></li>
                <li><a href="gestionar_clase.php?clase_id=<?php echo $class_id; ?>">Volver a la Clase</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section class="rubric-list">
            <h2>Mis Rúbricas</h2>
            <?php if (!empty($rubricas) && is_array($rubricas)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre de la Rúbrica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rubricas as $rubrica): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rubrica['nombre']); ?></td>
                                <td>
                                    <button onclick="window.location.href='editar_rubrica.php?rubrica_id=<?php echo $rubrica['id']; ?>'">Editar</button>
                                    <button onclick="window.location.href='detalles_rubrica.php?rubrica_id=<?php echo $rubrica['id']; ?>'">Ver Detalles</button>
                                    <a href="eliminar_rubrica.php?rubrica_id=<?php echo $rubrica['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta rúbrica?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay rúbricas disponibles para esta clase.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>

    <script src="../assets/js/gestionar_clase.js"></script>
</body>

</html>
