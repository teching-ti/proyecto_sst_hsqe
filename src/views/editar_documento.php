<?php
require_once 'src/config/Database.php';
require_once 'src/controllers/DocumentosController.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'],'application/json') !== false){

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    error_log("Datos recibidos: " . json_encode($data));

    if(!empty($data['id']) && !empty($data['nombreDoc'])){
        //
        $resultado = $documentosController->editarDocumento($data['id'], $data['nombreDoc']);
        echo json_encode(['success' => $resultado]);

        error_log($resultado);
    }else{
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}else{
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>