<?php
session_start();

require_once 'src/config/Database.php';
require_once 'src/controllers/UserController.php';

$database = new Database();
$conn = $database->connect();

$userController = new UserController($conn);

// página de carga por defecto
$page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

switch ($page) {
    case 'inicio':
        include 'src/views/main.php';
        break;
    case 'login':
        include 'src/views/login.php';
        break;
    case 'signin':
        include 'src/views/signin.php';
        break;
    case 'registrar':
        $userController->register();
        break;
    case 'personal_administrativo':
        include 'src/views/vpersonal_administrativo.php';
        break;
    case 'personal_operativo':
        include 'src/views/vpersonal_operativo.php';
        break;
    case 'documentos':
        include 'src/views/documentos.php';
        break;
    case 'otros':
        include 'src/views/otros.php';
        break;
    default:
        include 'src/views/error404.php';
        break;
}

?>