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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];

            $user = new User($this->conn);
            
            if ($user->login($usuario, $contrasena)) {
                $_SESSION['usuario'] = $user->usuario;
                $_SESSION['nombres'] = $user->nombres;
                $_SESSION['apellidos'] = $user->apellidos;
                $_SESSION['rol'] = $user->rol;
                //header('Location: index.php?page=inicio');
                header('Location: inicio');
                exit;
            } else {
                echo '<script>alert("Datos incorrectos")</script>';
            }
        }
        include 'src/views/login.php';
    }

    // manejo de logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?page=inicio');
        exit;
    }
}