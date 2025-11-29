<?php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $pdo = Database::getConnection();
        
        $this->userModel = new UserModel($pdo);
    }

    public function registrar()
    {
        // Validar datos
        if (!isset($_POST['nombre'], $_POST['email'], $_POST['password'])) {
            header("Location: /auth/register?mensaje=Faltan campos&tipo=error");
            exit;
        }

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Registrar usuario
        $resultado = $this->userModel->registrarUsuario($nombre, $email, $password);

        if ($resultado) {
            header("Location: /auth/register?mensaje=Usuario creado exitosamente&tipo=success");
        } else {
            header("Location: /auth/register?mensaje=El correo ya está registrado&tipo=error");
        }
    }

    public function login()
    {
        echo "Login por implementar";
    }
}
