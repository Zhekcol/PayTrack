<?php

require_once __DIR__ . '/../models/Movimiento.php';

class MovimientosController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new MovimientoModel($pdo);
    }

    public function index()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id_usuario'];

        return $this->model->obtenerPorUsuario($idUsuario);
    }
}