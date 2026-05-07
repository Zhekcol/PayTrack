<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class AuthController
{
    private UserModel $userModel;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();

        $this->userModel = new UserModel($this->pdo);
    }
    public function registrar(string $nombre, string $email, string $password)
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
            'id_usuario' => $usuario['id_usuario'], 
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email']
        ];


        return ['status' => 'success', 'mensaje' => 'Inicio de sesión exitoso'];
    }


    public function forgotPassword(){
        require_once __DIR__ . '/../../views/auth/forgot.php';
    }

    public function enviarCodigo()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $email = trim($_POST['email'] ?? '');

        if(empty($email)){
            $_SESSION['error'] = "El correo es obligatorio";

            header("Location: /paytrack/public/index.php?url=auth/forgot");
            exit;
        }

        $usuario = $this->userModel->buscarPorEmail($email);

        if(!$usuario){
            $_SESSION['error'] = "El correo no existe";

            header("Location: /paytrack/public/index.php?url=auth/forgot");
            exit;
        }

        $codigo = rand(100000, 999999);

        $guardado = $this->userModel->guardarCodigoRecuperacion(
            $email,
            $codigo
        );

        if(!$guardado){
            $_SESSION['error'] = "No se pudo generar el código";

            header("Location: /paytrack/public/index.php?url=auth/forgot");
            exit;
        }

        $_SESSION['correo_recuperacion'] = $email;

        $mail = new PHPMailer(true);

        try {

            // CONFIG SMTP
            $env = parse_ini_file(__DIR__ . '/../../.env');
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = $env['MAIL_HOST'];
            $mail->SMTPAuth   = true;

            $mail->Username   = $env['MAIL_USERNAME'];
            $mail->Password   = $env['MAIL_PASSWORD'];
            $mail->Port       = $env['MAIL_PORT'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // REMITENTE
            $mail->setFrom($env['MAIL_USERNAME'], 'PayTrack');

            // DESTINATARIO
            $mail->addAddress($email);

            // CONTENIDO
            $mail->isHTML(true);

            $mail->Subject = 'Recuperación de contraseña - PayTrack';

            $mail->Body = "
                <h2>Recuperación de contraseña</h2>

                <p>Tu código de recuperación es:</p>

                <h1>$codigo</h1>

                <p>El código expirará en 10 minutos.</p>
            ";

            $mail->send();

            $_SESSION['success'] = "Código enviado correctamente";

            header("Location: /paytrack/public/index.php?url=auth/verificar-codigo");
            exit;

        } catch (Exception $e) {

            $_SESSION['error'] = "No se pudo enviar el correo";

            header("Location: /paytrack/public/index.php?url=auth/forgot");
            exit;
        }
    }
    
    public function verificarCodigo()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $codigo = trim($_POST['codigo'] ?? '');

        if(empty($codigo)){
            $_SESSION['error'] = "Debes ingresar el código";

            header("Location: /paytrack/public/index.php?url=auth/verificar-codigo");
            exit;
        }

        if(!isset($_SESSION['correo_recuperacion'])){
            $_SESSION['error'] = "Sesión inválida";

            header("Location: /paytrack/public/index.php?url=auth/forgot");
            exit;
        }

        $email = $_SESSION['correo_recuperacion'];

        $usuario = $this->userModel->verificarCodigoRecuperacion(
            $email,
            $codigo
        );

        if(!$usuario){
            $_SESSION['error'] = "Código inválido o expirado";

            header("Location: /paytrack/public/index.php?url=auth/verificar-codigo");
            exit;
        }

        require_once __DIR__ . '/../../views/auth/nueva_password.php';
    }

    public function resetPassword()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $password = trim($_POST['password'] ?? '');

        if(empty($password)){
            $_SESSION['error'] = "La contraseña es obligatoria";

            header("Location: /paytrack/public/index.php?url=auth/nueva-password");
            exit;
        }

        if (
            !preg_match(
                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/',
                $password
            )
        ) {

            $_SESSION['error'] =
            "La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";

            header("Location: /paytrack/public/index.php?url=auth/nueva-password");
            exit;
        }

        $email = $_SESSION['correo_recuperacion'];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $ok = $this->userModel->actualizarPasswordPorEmail(
            $email,
            $passwordHash
        );

        if($ok){

            unset($_SESSION['correo_recuperacion']);

            $_SESSION['success'] = "Contraseña actualizada correctamente";

            header("Location: /paytrack/public/index.php?url=auth/login");
            exit;
        }

        $_SESSION['error'] = "No se pudo actualizar la contraseña";

        header("Location: /paytrack/public/index.php?url=auth/nueva-password");
    }

    public function nuevaPassword()
    {
        require_once __DIR__ . '/../../views/auth/nueva_password.php';
    }
}

