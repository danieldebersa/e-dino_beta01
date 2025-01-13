<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Prueba</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="hidden" name="rol" value="2"> <!-- Alumno -->
        <button type="submit">Registrar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once 'User.php';

        $user = new User();

        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $rol = (int)$_POST['rol'];

        $userId = $user->createUser($nombre, $email, $password, $rol);
        
        if ($userId) {
            echo "Usuario registrado con éxito. ID: " . htmlspecialchars($userId);
        } else {
            echo "Error al registrar el usuario.";
        }

        $user->closeConnection();
    }
    ?>
</body>
</html>
