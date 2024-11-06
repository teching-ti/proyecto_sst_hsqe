<?php
require_once 'src/models/Trabajadores.php';

class TrabajadoresController{
    private $trabajadoresModel;

    public function __construct($conn){
        $this->trabajadoresModel = new Trabajadores($conn);
    }

    public function mostrarPersonalAdministrativo(){
        $personalAdministrativo = $this->trabajadoresModel->getPersonalAdministrativo();

        include 'src/views/vpersonal_administrativo.php';
    }
}
?>