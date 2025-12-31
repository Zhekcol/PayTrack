<?php
session_start();

require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/UserModel.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /auth/login');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$authController = new AuthController();
$respuesta = $authController->login($email, $password);

$tipo = $respuesta['status'] === 'success' ? 'success' : 'danger';
$mensaje = urlencode($respuesta['mensaje']);

if ($respuesta['status'] === 'success') {
    header("Location: /dashboard");
} else {
    header("Location: /auth/login?mensaje=$mensaje&tipo=$tipo");
}
exit;
