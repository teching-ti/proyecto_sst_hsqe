<?php
class Role{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getRoles(){
        $query = "SELECT id, nombre FROM tb_rol";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>