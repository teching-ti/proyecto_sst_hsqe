<?php
class Modalidad{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getModalidad(){
        $query = "SELECT id, nombre FROM tb_modalidad_trabajo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>