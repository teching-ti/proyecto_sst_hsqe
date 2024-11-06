<?php

class Trabajadores{

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }
    
    public function getPersonalAdministrativo(){
        $query = "SELECT activo, apellidos, nombres, id, cargo, area, departamento, celular, fecha_ingreso, correo, tipo_contrato, estado, telefono FROM tb_trabajadores WHERE id_tipo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>