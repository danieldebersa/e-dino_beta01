<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/User.php';

$usuario_id = $_SESSION['user_id'];
$clase_id = isset($_POST['clase_id']) ? intval($_POST['clase_id']) : (isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0);

// Validar clase_id
if ($clase_id <= 0) {
    die("Clase ID inválido.");
}

$user = new User();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rubrica_name = trim($_POST['rubrica_name']);
    $criterios = $_POST['criterios'];

    // Validación de criterios
    $total_nivel = array_sum(array_column($criterios, 'nivel'));
    if ($total_nivel !== 100) {
        $error = "La suma de los porcentajes de los criterios debe ser exactamente 100%.";
    } elseif (count($criterios) > 5) {
        $error = "No puedes añadir más de 5 criterios.";
    } elseif (empty($rubrica_name)) {
        $error = "El nombre de la rúbrica es obligatorio.";
    } else {
        $result = $user->createRubric($rubrica_name, $criterios, $clase_id);

        if ($result) {
            $rubric_id = $user->getLastInsertId();

            foreach ($criterios as $criterio) {
                $criterion_name = trim($criterio['nombre']);
                $description = trim($criterio['descripcion']);
                $nivel = intval($criterio['nivel']);
                $nivel_nombre = trim($criterio['nivel_nombre']);

                $user->addCriterion($rubric_id, $criterion_name, $description, $nivel, $nivel_nombre);
            }

            header("Location: gestionar_clase.php?clase_id=$clase_id");
            exit();
        } else {
            $error = "Error al crear la rúbrica.";
        }
    }
}

$user->closeConnection();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Rúbrica de Evaluación - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/create_material.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const criteriosTable = document.getElementById("criterios-table");
            const addCriterioButton = document.getElementById("add-criterio");

            form.addEventListener("submit", function(e) {
                let totalNivel = 0;
                const niveles = document.querySelectorAll("input[name^='criterios'][name$='[nivel]']");
                niveles.forEach(input => {
                    totalNivel += parseFloat(input.value) || 0;
                });

                if (totalNivel !== 100) {
                    e.preventDefault();
                    alert("La suma de los porcentajes de los criterios debe ser exactamente 100%.");
                }
            });

            addCriterioButton.addEventListener("click", function() {
                const criterioCount = criteriosTable.querySelectorAll("tr.criterio").length;
                if (criterioCount >= 10) {
                    alert("Solo se pueden añadir hasta 10 criterios.");
                    return;
                }

                const newRow = document.createElement("tr");
                newRow.className = "criterio";
                newRow.innerHTML = `
                    <td><input type="text" name="criterios[${criterioCount}][nombre]" required></td>
                    <td><textarea name="criterios[${criterioCount}][descripcion]" required></textarea></td>
                    <td><input type="number" name="criterios[${criterioCount}][nivel]" min="1" max="100" required></td>
                    <td><input type="text" name="criterios[${criterioCount}][nivel_nombre]" required></td>
                    <td><button type="button" onclick="this.closest('tr').remove();">Eliminar</button></td>
                `;
                criteriosTable.appendChild(newRow);
            });
        });
    </script>
</head>

<body>
    <header>
        <h1>Crear Rúbrica de Evaluación</h1>
    </header>

    <main>
        <form action="validaciones_rubrica.php" method="POST">
            <input type="hidden" name="clase_id" value="<?php echo htmlspecialchars($clase_id); ?>">
            <div>
                <label for="rubrica_name">Nombre de la Rúbrica:</label>
                <input type="text" id="rubrica_name" name="rubrica_name" required>
            </div>

            <div id="criterios-wrapper">
                <h2>Agregar Criterios</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Criterio</th>
                            <th>Descripción</th>
                            <th>Nivel (%)</th>
                            <th>Nombre del Nivel</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="criterios-table">
                        <tr class="criterio">
                            <td><input type="text" name="criterios[0][nombre]" required></td>
                            <td><textarea name="criterios[0][descripcion]" required></textarea></td>
                            <td><input type="number" name="criterios[0][nivel]" min="1" max="100" required></td>
                            <td><input type="text" name="criterios[0][nivel_nombre]" required></td>
                            <td><button type="button" onclick="this.closest('tr').remove();">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" id="add-criterio">Agregar Criterio</button>
            <button type="submit">Guardar Rúbrica</button>
        </form>


        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>
</body>

</html>