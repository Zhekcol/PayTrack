<?php 

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    
    private $UserModel;

    public function __construct() {
        // Traemos la conexión PDO (de tu database.php)
        $pdo = Database::getConnection();

        // Instanciamos el modelo
        $this->UserModel = new UserModel($pdo);
    }

    public function registrar($nombre, $email, $password) {

    // 1. Validación de campos vacíos
    if (empty($nombre) || empty($email) || empty($password)) {
        return [
            'status' => 'error',
            'mensaje' => 'Todos los campos son obligatorios'
        ];
    }

    // 2. Validación de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'status' => 'error',
            'mensaje' => 'El email no tiene un formato válido'
        ];
    }

    // 3. Verificar si el email ya está registrado
    $usuarioExistente = $this->UserModel->buscarPorEmail($email);

    if ($usuarioExistente) {
        return [
            'status' => 'error',
            'mensaje' => 'El email ya está registrado'
        ];
    }

    // 4. Encriptar la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 5. Llamar al modelo para registrar
    $registrado = $this->UserModel->registrarUsuario($nombre, $email, $passwordHash);

    if ($registrado) {
        return [
            'status' => 'success',
            'mensaje' => 'Usuario registrado correctamente'
        ];
    } else {
        return [
            'status' => 'error',
            'mensaje' => 'Ocurrió un error al registrar el usuario'
        ];
    }
}

public function enviarCodigoRecuperacion()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = trim($_POST['email']);

        // 1. Validar que no esté vacío
        if (empty($email)) {
            $_SESSION['error'] = "Por favor, ingresa tu correo electrónico.";
            header("Location: recuperar.php");
            exit;
        }

        // 2. Verificar si el correo existe
        $usuario = $this->UserModel->buscarPorEmail($email);

        if (!$usuario) {
            $_SESSION['error'] = "Este correo no está registrado.";
            header("Location: recuperar.php");
            exit;
        }

        // 3. Generar código de 6 dígitos
        $codigo = rand(100000, 999999);

        // 4. Guardar el código en la BD
        $guardado = $this->UserModel->guardarCodigoRecuperacion($email, $codigo);

        if (!$guardado) {
            $_SESSION['error'] = "Ocurrió un error al generar el código. Intenta más tarde.";
            header("Location: recuperar.php");
            exit;
        }

        // 5. Enviar el código al correo
        $asunto = "Código para recuperar tu contraseña";
        $mensaje = "Tu código de verificación es: $codigo\n\nEste código expira en 10 minutos.";

        mail($email, $asunto, $mensaje);

        // 6. Redirigir al formulario para validar el código
        $_SESSION['email_recuperacion'] = $email;
        header("Location: verificar_codigo.php");
        exit;
    }
}


}

?>