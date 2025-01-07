<?php

class User{
    private $conn;
    private $table = 'tb_usuarios';

    public $usuario;
    public $apellidos;
    public $nombres;
    public $contrasena;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($usuario, $apellidos, $nombres, $contrasena, $rol) {
        
        // consulta parametrizada sql
        $query = "INSERT INTO " . $this->table . " (usuario, apellidos, nombres, contrasena, rol) VALUES (:usuario, :apellidos, :nombres, :contrasena, :rol)";
        // preparación de consulta, se deberá de usar bindParam
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
        
        // parámetros de consulta sql
        // 'los parámetros deben de cumplir con la cantidad segun lo declarado por le modelo'
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':contrasena', $hashed_password);
        $stmt->bindParam(':rol', $rol);
        

        //ejecución de consulta preparada
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($usuario, $password) {
        $query = "SELECT usuario, nombres, apellidos, contrasena, rol FROM " . $this->table . " WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->usuario = $row['usuario'];
            $this->nombres = $row['nombres'];
            $this->apellidos = $row['apellidos'];
            $this->rol = $row['rol'];
            $hashed_password = $row['contrasena'];

            if (password_verify($password, $hashed_password)) {
                return true;
            }
        }
        return false;
    }
}

?>