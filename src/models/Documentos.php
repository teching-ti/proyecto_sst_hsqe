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
                    c.id as id_cat,
                    c.nombre as cat_documento
                FROM 
                    tb_documentos d
                INNER JOIN 
                    tb_catdocumento c ON d.cat_documento = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarDocumento($id, $nombre){
        try{
            $sql_check = "SELECT COUNT(*) FROM tb_documentos WHERE id = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id);
            $stmt_check->execute();
    
            if ($stmt_check->fetchColumn() == 0) {
                error_log("El trabajador con ID $id, $nombre no existe.");
                return false;
            }

            $sql = "UPDATE tb_documentos SET nombre = :nombre WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);

            return $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("No se actualizó ninguna fila. ID: $id");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar el documento: " . $e->getMessage());
            return false;
        }
    }

}
?>