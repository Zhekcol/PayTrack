<?php

require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/UserModel.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';

session_start();

// 1. Recibir los datos del formulario
$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// 2. Instanciar UserModel
$userModel = new UserModel($pdo);

// 3. Instanciar UserController
$userController = new UserController($userModel);

// 4. Ejecutar registro
$respuesta = $userController->registrar($nombre, $email, $password);

// 5. Redirigir con mensaje
$tipo = $respuesta['status'] === 'success' ? 'success' : 'danger';
$mensaje = urlencode($respuesta['mensaje']);

header("Location: /auth/register?mensaje=$mensaje&tipo=$tipo");
exit;
