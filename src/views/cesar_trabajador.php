<?php
require_once './src/controllers/TrabajadoresController.php';
require_once './src/config/database.php';

// instancia del controlador
$database = new Database();
$conn = $database->connect();
$trabajadoresController = new TrabajadoresController($conn);

// primero se debe verificar si los datos han sido enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $tipoMovimiento = "cese";
    // error_log("Datos recibidos: " . json_encode($data));

    if (!empty($data['documento']) && !empty($data['fechaCese']) && !empty($data['motivoCese'])) {
        $resultado = $trabajadoresController->cesarTrabajador($data['documento'], $data['fechaCese'], $tipoMovimiento, $data['motivoCese']);
        //echo json_encode(['success' => true]);
        if (is_array($resultado) && isset($resultado['success']) && !$resultado['success']) {
            echo json_encode($resultado);
        } else {
            echo json_encode(['success' => true, 'message' => 'Cese registrado correctamente.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}
?>