<?php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function createUser($nombre, $email, $password, $rol_id) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("sssi", $nombre, $email, $passwordHash, $rol_id);

        if (!$stmt->execute()) {
            error_log("Error al insertar el usuario: " . $stmt->error);
            return false;
        }

        $insertId = $stmt->insert_id;
        $stmt->close();

        return $insertId;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, nombre, password, rol_id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $nombre, $hashed_password, $rol_id);
        $stmt->fetch();
        $stmt->close();

        if ($user_id && password_verify($password, $hashed_password)) {
            return ['id' => $user_id, 'nombre' => $nombre, 'rol_id' => $rol_id];
        }

        return false;
    }

    public function createRubric($rubrica_name, $criterios, $clase_id) {
        $stmt = $this->conn->prepare("INSERT INTO rubricas (nombre, clase_id) VALUES (?, ?)");
        $stmt->bind_param("si", $rubrica_name, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    

    public function isUserInClass($user_id, $class_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ii", $user_id, $class_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function getClassDetails($class_id) {
        $stmt = $this->conn->prepare("SELECT nombre, descripcion FROM clases WHERE id = ?");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($nombre, $descripcion);
        $stmt->fetch();
        $stmt->close();

        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];
    }

    public function getClassMembers($class_id) {
        $stmt = $this->conn->prepare("SELECT u.nombre, u.email FROM usuarios u JOIN clases_usuarios cu ON u.id = cu.usuario_id WHERE cu.clase_id = ?");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $miembros = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $miembros;
    }

    public function getClassMaterials($class_id) {
        $stmt = $this->conn->prepare("SELECT id, titulo, descripcion, valor, fecha_limite FROM materiales_clase WHERE clase_id = ?");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $materiales = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $materiales;
    }

    // Método para obtener las rúbricas por ID de clase
    public function getRubricsByClassId($class_id) {
        $stmt = $this->conn->prepare("SELECT id, nombre FROM rubricas WHERE clase_id = ?");
        
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rubricas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rubricas;
    }

    public function closeConnection() {
        $this->conn->close();
    }
    // User.php
public function leaveClass($usuario_id, $clase_id) {
    $sql = "DELETE FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $clase_id);
    return $stmt->execute();
}

}