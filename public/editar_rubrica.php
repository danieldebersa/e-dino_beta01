<?php
require_once '../core/models/manage_classes.php';

try {
    if (!isset($_GET['rubrica_id']) || empty($_GET['rubrica_id'])) {
        throw new Exception("ID de rúbrica no proporcionado.");
    }

    $rubrica_id = intval($_GET['rubrica_id']);
    $manageClasses = new ManageClasses();

    $rubrica = $manageClasses->getRubricById($rubrica_id);

    if (!$rubrica) {
        throw new Exception("Rúbrica no encontrada.");
    }

    $criterios = $manageClasses->getCriteriaByRubricId($rubrica_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rubrica_name = $_POST['rubrica_name'];

        $updateResult = $manageClasses->updateRubric($rubrica_id, $rubrica_name, $criterios);
        
        if ($updateResult) {
            header("Location: gestionar_clase.php?clase_id=" . $rubrica['clase_id']);
            exit();
        } else {
            $error = "Error al actualizar la rúbrica.";
        }
    }

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
    <title>Editar Rúbrica - E-Dino</title>
</head>
<body>
    <header>
        <h1>Editar Rúbrica: <?php echo htmlspecialchars($rubrica['nombre']); ?></h1>
    </header>

    <main>
        <form action="" method="POST">
            <label for="rubrica_name">Nombre de la Rúbrica:</label>
            <input type="text" id="rubrica_name" name="rubrica_name" value="<?php echo htmlspecialchars($rubrica['nombre']); ?>" required>

            <button type="submit">Actualizar Rúbrica</button>
        </form>

        <?php if (isset($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
