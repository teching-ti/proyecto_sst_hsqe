<?php
require_once 'src/controllers/TrabajadoresController.php';
require_once 'src/config/database.php';

// instancia del controlador
$database = new Database();
$conn = $database->connect();
$trabajadoresController = new TrabajadoresController($conn);

// primero se debe verificar si los datos han sido enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'dni' => trim($_POST['dni']) ?? null,
        'apellidos' => trim($_POST['apellidos']) ?? null,
        'nombres' => trim($_POST['nombres']) ?? null,
        'cargo' => trim($_POST['cargo']) ?? null,
        'area' => trim($_POST['area']) ?? null,
        'departamento' => trim($_POST['departamento']) ?? null,
        'fecha_ingreso' => $_POST['fecha_ingreso'] ?? null,
        'id_tipo' => intval($_POST['id_tipo']) ?? null,
        'estado' => trim($_POST['estado']) ?? null,
        'celular' => trim($_POST['celular']) ?? null,
        'correo' => trim($_POST['correo']) ?? null,
        'tipo_contrato' => trim($_POST['tipo_contrato']) ?? null,
        'activo' => intval($_POST['activo']) ?? null,
        'telefono' => trim($_POST['telefono']) ?? null,
    ];
    
    try{
        $resultado = $trabajadoresController->insertarTrabajador($datos);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al insertar el trabajador.']);
        }
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

}
?>