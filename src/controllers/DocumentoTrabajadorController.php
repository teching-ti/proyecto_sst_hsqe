<?php
require_once "src/models/DocumentoTrabajador.php";

class DocumentoTrabajadorController{
    private $conn;
    private $documentoTrabajadorModel;

    public function __construct($conn){
        $this->conn = $conn;
        $this->documentoTrabajadorModel = new DocumentoTrabajador($conn);
    }

    public function registerDocumentoTrabajador($trabajadorId, $docName, $archivoNombre, $archivoLink) {
        $documentoTrabajador = new DocumentoTrabajador($this->conn);

        $idDocumento = $documentoTrabajador->findDocumentoIdByName($docName);

        if (!$idDocumento) {
            error_log("Error: No se encontró el id_documento para el nombre '$docName'");
            return false;
        }

        //es imporante cargar la fecha en este formato para poder obtener la fecha más reciente en que fue subido un documento
        $horaZona  = new DateTimeZone('America/Lima');
        $fechaHoraActual = new DateTime('now', $horaZona);
        $fechaSubida = $fechaHoraActual->format('Y-m-d H:i:s');

        // campo estado del documento
        $estado = null;

        $result = $this->documentoTrabajadorModel->insertDocumentoTrabajador($idDocumento, $trabajadorId, $archivoNombre, $fechaSubida, $archivoLink, $estado);
    
        if (!$result) {
            error_log("Error: No se pudo insertar el registro en tb_doctrabajadores.");
        } else {
            error_log("Registro insertado correctamente en tb_doctrabajadores.");
        }
        
        return $result;
    }

    public function obtenerDocumentosPorTrabajador($id_trabajador) {
        $listaDocumentosTrabajador = $this->documentoTrabajadorModel->listarDocumentosPorTrabajador($id_trabajador);
        return $listaDocumentosTrabajador;
    }

    public function obtenerNombreDocumento($id_documento) {
        $nombreDocumento = $this->documentoTrabajadorModel->listarNombreDocumento($id_documento);
        return $nombreDocumento;
    }

    public function obtenerNombreTrabajador($id_trabajador) {
        return $this->documentoTrabajadorModel->obtenerNombreTrabajador($id_trabajador);
    }
}
?>
