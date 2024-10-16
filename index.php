<?php
session_start();

// if (isset($_SESSION['user_id'])) {
//     $page = isset($_GET['page']) ? $_GET['page'] : 'main';
// } else {
//     $page = 'main';
// }

$page = isset($_GET['page']) ? $_GET['page'] : 'main';

switch ($page) {
    case 'inicio':
        include 'src/views/main.php';
        break;
    case 'login':
        include 'src/views/login.php';
        break;
    case 'registro':
        include 'src/views/registro.php';
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