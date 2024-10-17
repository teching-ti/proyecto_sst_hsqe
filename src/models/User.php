<?php

class User{
    private $conn;
    private $table = 'tb_usuarios';

    public $id;
    public $usuario;
    public $apellidos;
    public $nombres;
    public $contrasena;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($usuario, $apellidos, $nombres, $contrasena, $rol) {
        $query = "INSERT INTO " . $this->table . " (usuario, apellidos, nombres, contrasena, rol) VALUES (:usuario, :apellidos, :nombres, :contrasena, :rol)";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':contrasena', $hashed_password);
        $stmt->bindParam(':rol', $rol);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // login aún en proceso
    public function login($username, $password) {
        $query = "SELECT id, usuario, contrasena FROM " . $this->table . " WHERE usuario = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $hashed_password = $row['contrasena'];

            if (password_verify($password, $hashed_password)) {
                return true;
            }
        }
        return false;
    }
}

?>