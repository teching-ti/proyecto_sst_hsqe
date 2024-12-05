<?php
require_once 'src/config/Database.php';
require_once 'api-google/vendor/autoload.php';
require_once "src/controllers/DocumentoTrabajadorController.php";

//$database = new Database();
//$conn = $database->connect();
//$documentoTrabajadorController = new DocumentoTrabajadorController($conn);

if (isset($_FILES['archivo']) && isset($_POST['trabajador_id']) && isset($_POST['doc_name'])) {
    $trabajadorId = $_POST['trabajador_id'];
    $docName = $_POST['doc_name'];
    
    // se obtiene el archivo temporal y su nombre
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $archivoNombre = $_FILES['archivo']['name'];
    
    // configuración del Google Client
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/docsstdrive-3b243d95cfa5.json'); 
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google_Service_Drive::DRIVE);
    $driveService = new Google_Service_Drive($client);
    
    // id de la carpeta de Google Drive donde se guardará el archivo
    $carpetaId = '1f_zDfjyqy3zZZV_o31gVkCEiTvSMEniF';
    
    // crear el archivo en Google Drive
    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => $archivoNombre,
        'parents' => [$carpetaId]
    ]);

    $content = file_get_contents($archivoTmp);

    try {
        // se intenta crear el archivo en Google Drive
        $archivoDrive = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($archivoTmp),
            'uploadType' => 'multipart', // se envia el archivo y los metadatos
            'fields' => 'id'
        ]);

        // e caso se llegue a crear correctamente, se retorna el enlace
        $archivoLink = "https://drive.google.com/file/d/{$archivoDrive->id}/view?usp=sharing";
        // se agregó el archivo nombre para guardarlo también en la base de datos
        $success = $documentoTrabajadorController->registerDocumentoTrabajador($trabajadorId, $docName, $archivoNombre, $archivoLink);

        echo json_encode(['success' => $success, 'link' => $archivoLink]);
    } catch (Exception $e) {
        // mensaje de error
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false]);

}