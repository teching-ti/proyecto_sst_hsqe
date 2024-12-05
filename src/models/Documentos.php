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

    public function getDocumentos(){
        $query = "SELECT 
                    d.id,
                    d.nombre as nombre, 
                    c.nombre as cat_documento
                FROM 
                    tb_documentos d
                INNER JOIN 
                    tb_catdocumento c ON d.cat_documento = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>