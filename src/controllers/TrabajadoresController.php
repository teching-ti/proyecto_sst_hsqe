<?php
require_once 'src/models/Trabajadores.php';

class TrabajadoresController{
    private $trabajadoresModel;

    public function __construct($conn){
        $this->trabajadoresModel = new Trabajadores($conn);
    }

    public function mostrarTrabajadores($ids = []){
        $listadoTrabajadores = $this->trabajadoresModel->getTrabajadores($ids);
        return $listadoTrabajadores;
    }

    public function mostrarPersonalAdministrativo() {
        $personalAdministrativo = $this->trabajadoresModel->getPersonalAdministrativoConDocumentos();
        return $personalAdministrativo;
    }

    public function mostrarPersonalOperativo() {
        $personalOperativo = $this->trabajadoresModel->getPersonalOperativoConDocumentos();
        return $personalOperativo;
    }

    public function insertarTrabajador($datos){
        return $this->trabajadoresModel->setTrabajador($datos);
    }

    public function getDistribucionTrabajadores() {
        $datos = $this->trabajadoresModel->contarTrabajadoresPorTipo();
    
        $respuesta = [];
        foreach ($datos as $fila) {
            $respuesta[$fila['nombre']] = $fila['total'];
        }
    
        echo json_encode($respuesta);
    }
    
    public function editarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $fecha_ingreso, $correo, $tipo_contrato, $telefono) {
        return $this->trabajadoresModel->actualizarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $fecha_ingreso, $correo, $tipo_contrato, $telefono);
    }

}
?>