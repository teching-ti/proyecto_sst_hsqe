<?php
session_start();

$usuario_logueado = isset($_SESSION['usuario']);
$nombres_usuario = $usuario_logueado ? $_SESSION['nombres'] : null;

require_once 'src/config/Database.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/TrabajadoresController.php';
require_once 'src/controllers/DocumentosController.php';

require_once "src/controllers/DocumentoTrabajadorController.php"; // evaluando

$database = new Database();
$conn = $database->connect();

$userController = new UserController($conn);
$trabajadoresController = new TrabajadoresController($conn);
$documentosController = new DocumentosController($conn);
$documentoTrabajadorController = new DocumentoTrabajadorController($conn);

define('BASE_URL', '/proyecto_sst_hsqe/');

// página de carga por defecto
$page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

$paginas_protegidas = [
    'inicio',
    'signin',
    'personal_administrativo',
    'personal_operativo',
    'documentos',
    'documentos_trabajador',
    'registrar_trabajador',
    'salir',
    'subir_archivo',
    'trabajadores',
    'descargar'
];

// hace que si un usuario intenta acceder a una ruta protegida, sea llevado al login
if (in_array($page, $paginas_protegidas) && !$usuario_logueado) {
    //header('Location: /proyecto_sst_hsqe/login');
    header('Location: ' . BASE_URL . 'login');
    exit;
}

// se evalúa la opción de revisar primero los roles antes de dar acceso
function verificarRol($rolRequerido) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rolRequerido;
}

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
        if(!verificarRol(1)){
            header('Location: ' . BASE_URL . 'inicio');
            exit;
        }
        $userController->mostrarRegistroForm();
        break;
    case 'registrar':
        $userController->register();
        break;
    case 'registrar_documento':
        $documentosController->register();
        break;
    case 'personal_administrativo':
        include 'src/views/vpersonal_administrativo.php';
        break;
    case 'personal_operativo':
        include 'src/views/vpersonal_operativo.php';
        break;
    // esta vista documentos_trabajador servirá para poder obtener información de los documentos cargados
    case 'documentos_trabajador':
        include 'src/views/vdocumentos_trabajador.php';
        break;
    case 'documentos':
        include 'src/views/documentos.php';
        break;
    case 'editar_documento':
        include 'src/views/editar_documento.php';
        break;
    case 'registrar_trabajador':
        include 'src/views/registrar_trabajador.php';
        break;
    case 'salir':
        $userController->logout();
        break;
    case 'subir_archivo':
        include 'src/views/subir_archivo.php';
        break;
    case 'insertar_trabajador':
        include 'src/views/insertar_trabajador.php';
        break;
    case 'editar_trabajador':
        include 'src/views/editar_trabajador.php';
        break;
    case 'trabajadores':
        include 'src/views/vpersonal_general.php';
        break;
    case 'descargar':
        include 'src/views/descargar.php';
        break;
    case 'getDistribucionTrabajadores':
        $trabajadoresController->getDistribucionTrabajadores();
        break;
    case 'getDistribucionPresencialPorSede':
        $trabajadoresController->getDistribucionPresencialPorSede();
        break;
    case 'historial_trabajadores':
        include 'src/views/vhistorial_trabajadores.php';
        break;
    default:
        include 'src/views/error404.php';
        break;
}

?>