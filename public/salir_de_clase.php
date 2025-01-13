// salir_de_clase.php
<?php
session_start();
require_once '../core/models/User.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['clase_id'])) {
    header("Location: dashboard.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$clase_id = intval($_GET['clase_id']);
$user = new User();

if ($user->leaveClass($usuario_id, $clase_id)) {
    header("Location: dashboard.php?mensaje=Has salido de la clase exitosamente.");
} else {
    header("Location: gestionar_clase.php?clase_id=$clase_id&error=No se pudo salir de la clase.");
}
exit();
?>
