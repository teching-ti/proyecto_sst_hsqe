<?php
class Sede{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getSede(){
        $query = "SELECT id, nombre FROM tb_sede";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>