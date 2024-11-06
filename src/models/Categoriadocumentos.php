<?php
class Categoriadocumentos{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getCategoriaDocumentos(){
        $query = "SELECT id, nombre FROM tb_catdocumento";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>