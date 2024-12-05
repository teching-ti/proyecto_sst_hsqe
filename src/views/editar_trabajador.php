<?php
require_once 'src/controllers/TrabajadoresController.php';
require_once 'src/config/database.php';

// instancia del controlador
$database = new Database();
$conn = $database->connect();
$trabajadoresController = new TrabajadoresController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    //error_log("Datos recibidos: " . json_encode($data));

    if (!empty($data['id']) && !empty($data['nombres']) && !empty($data['apellidos']) && !empty($data['cargo'])) {
        
        $resultado = $trabajadoresController->editarTrabajador($data['id'], $data['activo'], $data['id_tipo'], $data['nombres'], 
        $data['apellidos'], $data['cargo'], $data['area'], $data['departamento'], $data['celular'], $data['fecha_ingreso'], $data['correo'],
        $data['tipo_contrato'], $data['telefono']);

        // error_log($resultado);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>