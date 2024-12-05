<?php
class DocumentoTrabajador{
    private $conn;        
    private $table = 'tb_doctrabajadores';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findDocumentoIdByName($docName) {
        $query = "SELECT id FROM tb_documentos WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $docName);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$result) {
            error_log("Error: No se encontró un documento con el nombre '$docName'.");
        } else {
            error_log("ID encontrado para '$docName': " . $result['id']);
        }
    
        return $result['id'] ?? null;
    }

    public function insertDocumentoTrabajador($idDocumento, $trabajadorId, $archivoNombre, $fechaSubida, $archivoLink, $estado) {
        $query = "INSERT INTO tb_doctrabajadores (id_documento, id_trabajador, nombre_archivo, fecha_subida, archivo_eval, estado) 
                VALUES (:id_documento, :id_trabajador, :nombre_archivo, :fecha_subida, :archivo_eval, :estado)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_documento', $idDocumento);
        $stmt->bindParam(':id_trabajador', $trabajadorId);
        $stmt->bindParam(':nombre_archivo', $archivoNombre);
        $stmt->bindParam(':fecha_subida', $fechaSubida);
        $stmt->bindParam(':archivo_eval', $archivoLink);
        $stmt->bindParam(':estado', $estado);

        $result = $stmt->execute();
        if (!$result) {
            error_log("Error al insertar en la base de datos: " . implode(" ", $stmt->errorInfo()));
        }

        return $result;
    }

    public function listarDocumentosPorTrabajador($id_trabajador) {
        $query = "SELECT * FROM tb_doctrabajadores WHERE id_trabajador = :id_trabajador";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_trabajador', $id_trabajador);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarNombreDocumento($id_documento) {
        $query = "SELECT nombre FROM tb_documentos WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_documento);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombre'];
    }

    public function obtenerNombreTrabajador($id_trabajador) {
        $query = "SELECT nombres, apellidos FROM tb_trabajadores WHERE id = :id"; // PARAM LIMIT 1
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_trabajador);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombres'] ?? 'No identificado';
    }
}
?>