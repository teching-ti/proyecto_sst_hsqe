<?php
class Documentos{
    private $conn;
    private $table = 'tb_documentos';

    public $nombre;
    public $catdocumento;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function registerDocuments($nombre, $catdocumento){
        $query = "INSERT INTO " . $this->table . " (nombre, cat_documento) VALUES (:nombre, :cat_documento)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':cat_documento', $catdocumento);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

}
?>