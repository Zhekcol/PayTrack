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

    public function registrar($nombre, $email, $password)
    {
        if (empty($nombre) || empty($email) || empty($password)) {
            return ['status' => 'danger', 'mensaje' => 'Todos los campos son obligatorios'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'danger', 'mensaje' => 'Correo inválido'];
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{6,}$/', $password)) {
            return [
                'status' => 'danger',
                'mensaje' => 'La contraseña debe tener mínimo 6 caracteres, una mayúscula, una minúscula, un número y un carácter especial'
            ];
        }

        if ($this->userModel->buscarPorEmail($email)) {
            return ['status' => 'danger', 'mensaje' => 'El correo ya está registrado'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if ($this->userModel->registrarUsuario($nombre, $email, $passwordHash)) {
            return ['status' => 'success', 'mensaje' => 'Usuario registrado correctamente'];
        }

        return ['status' => 'danger', 'mensaje' => 'Error al registrar usuario'];
    }


    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return ['status' => 'danger', 'mensaje' => 'Todos los campos son obligatorios'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'danger', 'mensaje' => 'Correo inválido'];
        }

        $usuario = $this->userModel->buscarPorEmail($email);

        if (!$usuario) {
            return ['status' => 'danger', 'mensaje' => 'Credenciales incorrectas'];
        }

        if (!password_verify($password, $usuario['password'])) {
            return ['status' => 'danger', 'mensaje' => 'Credenciales incorrectas'];
        }

        // LOGIN OK → guardar sesión
        $_SESSION['usuario'] = [
            'id'     => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['email']
        ];

        return ['status' => 'success', 'mensaje' => 'Inicio de sesión exitoso'];
    }


    public function enviarCodigoRecuperacion()
    {
        // aquí va TODO lo de recuperar contraseña
    }
}

