<?php
session_start();

$usuario_logueado = isset($_SESSION['usuario']);
$nombres_usuario = $usuario_logueado ? $_SESSION['nombres'] : null;

require_once 'src/config/Database.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/TrabajadoresController.php';
require_once 'src/controllers/DocumentosController.php';

$database = new Database();
$conn = $database->connect();

$userController = new UserController($conn);
$trabajadoresController = new TrabajadoresController($conn);
$documentosController = new DocumentosController($conn);

// página de carga por defecto
$page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

switch ($page) {
    case 'inicio':
        include 'src/views/main.php';
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->login();
        } else {
            include 'src/views/login.php';
        }
        break;
    case 'signin':
        $userController->mostrarRegistroForm();
        // include 'src/views/signin.php';
        break;
    case 'registrar':
        $userController->register();
        break;
    case 'registrar_documento':
        $documentosController->register();
        break;
    case 'personal_administrativo':
        //include 'src/views/vpersonal_administrativo.php';
        $trabajadoresController->mostrarPersonalAdministrativo();
        break;
    case 'personal_operativo':
        include 'src/views/vpersonal_operativo.php';
        break;
    case 'documentos':
        $documentosController->mostrarRegistroDocumentoForm();
        //include 'src/views/documentos.php';
        break;
    case 'registrar_trabajador':
        include 'src/views/registrar_trabajador.php';
        break;
    case 'otros':
        include 'src/views/otros.php';
        break;
    case 'salir':
        $userController->logout();
        break;
    default:
        include 'src/views/error404.php';
        break;
}

?>