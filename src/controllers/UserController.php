<?php
require_once 'src/models/User.php';
require_once 'src/models/Role.php';

class UserController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function mostrarRegistroForm(){
        $roleModel = new Role($this->conn);
        $roles = $roleModel->getRoles();

        include 'src/views/signin.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // recibiendo los datos del formulario
            $usuario = $_POST['usuario'];
            $apellidos = $_POST['apellidos'];
            $nombres = $_POST['nombres'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];

            // instancia con parámetro esperando conexión
            $user = new User($this->conn);
            
            if ($user->register($usuario, $apellidos, $nombres, $contrasena, $rol)) {
                /*
                    - Se deberá de substituir el uso directo de header por un cambio de ruta
                */
                header('Location: index.php?page=login');
                exit;
            } else {
                echo 'Error al registrar el usuario.';
            }
        }
        include 'src/views/register.php';
    }

    // login de usuarios, aún en proceso
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];

            $user = new User($this->conn);
            
            if ($user->login($usuario, $contrasena)) {
                $_SESSION['user_id'] = $user->id;
                header('Location: index.php?page=main');
                exit;
            } else {
                echo 'Usuario o contraseña incorrectos';
            }
        }

        include 'src/views/login.php';
    }

    // manejo de logout
    public function logout() {
        session_start();
        session_destroy();
        /*
            - Se deberá de substituir el uso de ruta directa
        */
        header('Location: index.php?page=inicio');
        exit;
    }
}