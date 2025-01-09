<?php
require_once 'src/models/Trabajadores.php';
require_once 'src/models/Modalidad.php';
require_once 'src/models/Sede.php';

class TrabajadoresController{
    private $conn;
    private $trabajadoresModel;
    
    public function __construct($db){
        $this->conn = $db;
        $this->trabajadoresModel = new Trabajadores($db);
    }

    public function mostrarTrabajadores($ids = []){
        $listadoTrabajadores = $this->trabajadoresModel->getTrabajadores($ids);
        return $listadoTrabajadores;
    }

    public function mostrarModalidadTrabajo(){
        $catModalidad = new Modalidad($this->conn);
        $categorias = $catModalidad->getModalidad();
        return $categorias;
    }

    public function mostrarSede(){
        $catSede = new Sede($this->conn);
        $categorias = $catSede->getSede();
        return $categorias;
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
        $resultado = $this->trabajadoresModel->setTrabajador($datos);

        if($resultado){
            // la fecha de ingreso deberá ser colocada de forma manual
            $fechaIngreso = date('Y-m-d H:i:s'); // fecha actual /* Modificar
            $movimientoRegistrado = $this->trabajadoresModel->registrarMovimientoLaboral(
                $datos['dni'], // El ID del trabajador (DNI)
                $datos['fecha_ingreso'], 
                'ingreso', 
                $datos['motivo']
            );
            if (!$movimientoRegistrado) {
                throw new Exception('Error al registrar el movimiento laboral.');
            }
        }

        return $resultado;
    }

    public function getDistribucionTrabajadores() {
        $datos = $this->trabajadoresModel->contarTrabajadoresPorTipo();
    
        $respuesta = [];
        foreach ($datos as $fila) {
            $respuesta[$fila['nombre']] = $fila['total'];
        }
    
        echo json_encode($respuesta);
    }
    
    public function getDistribucionPresencialPorSede() {
        $datos = $this->trabajadoresModel->getTrabajadoresPresencialesPorSede();
        echo json_encode($datos);
    }

    public function getIngresosUltimosMeses() {
        $datos = $this->trabajadoresModel->getIngresosUltimosMeses();
        echo json_encode($datos);
    }
    
    public function editarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $correo, $tipo_contrato, $modalidad, $sede) {
        return $this->trabajadoresModel->actualizarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $correo, $tipo_contrato, $modalidad, $sede);
    }

    public function obtenerTrabajadoresPorFecha($mes, $anio) {
        return $this->trabajadoresModel->getTrabajadoresPorFecha($anio, $mes);
    }

    public function cesarTrabajador($documento, $fechaCese, $tipoMovimiento, $motivoCese) {
        try {
            return $this->trabajadoresModel->setInactivoTrabajador($documento, $fechaCese, $tipoMovimiento, $motivoCese);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

}
?>