<?php
session_start();

// Vaciar el arreglo de sesión
$_SESSION = array();

// Eliminar la cookie de sesión si es necesario
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al index.php en la raíz del proyecto
header("Location: /index.php");
exit;
?>
