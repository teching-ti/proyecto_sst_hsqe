<?php
require_once 'src/models/Documentos.php';
require_once 'src/models/Categoriadocumentos.php';

class DocumentosController{
    private $conn;
    private $documentosModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->documentosModel = new Documentos($db);
    }

    // function para mostrar las categorías dentro del formulario
    // de registro de documentos
    public function mostrarRegistroDocumentoForm(){
        $catModel = new Categoriadocumentos($this->conn);
        $categorias = $catModel->getCategoriaDocumentos();
        return $categorias;
        //include 'src/views/documentos.php';
    }

    // register
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // recibiendo los datos del formulario
            $nombre = $_POST['nombre'];
            $catdocumento = $_POST['catdocumento'];

            // instancia con parámetro esperando conexión
            $documento = new Documentos($this->conn);
            
            if ($documento->registerDocuments($nombre, $catdocumento)) {
                header('Location: index.php?page=documentos');
                exit;
            } else {
                echo 'Error al registrar el documento.';
            }
        }
        include 'src/views/register.php';
    }

    // editar
    public function editarDocumento($id, $nombre){
        return $this->documentosModel->actualizarDocumento($id, $nombre);
    }

    //mostrar listado de documentos
    public function mostrarListadoDocumentos(){
        $listaDocumentos = $this->documentosModel->getDocumentos();
        return $listaDocumentos;
    }
    
}
?>