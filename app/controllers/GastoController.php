<?php

require_once __DIR__ . '/../models/GastoModel.php';

class GastoController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new GastoModel($pdo);
    }

    public function index()
{
    $idUsuario = $_SESSION['usuario']['id_usuario'];
    return $this->model->obtenerPorUsuario($idUsuario);
}

    public function store()
    {
        $idUsuario = $_SESSION['usuario']['id_usuario'];

        $descripcion = $_POST['descripcion'];
        $monto = $_POST['monto'];
        $fecha = $_POST['fecha'];

        $this->model->crear($idUsuario, $descripcion, $monto, $fecha);

        header("Location: /gastos?success=1");
        exit;
    }

    public function edit()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id_usuario'];
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /gastos");
            exit;
        }

        return $this->model->obtenerPorId($id, $idUsuario);
    }


    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $id = $_POST['id'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $monto = $_POST['monto'] ?? 0;
        $fecha = $_POST['fecha'] ?? '';

        $idUsuario = $_SESSION['usuario']['id_usuario']; 

        if ($id) {
            $this->model->actualizar(
                $id,
                $idUsuario,
                $descripcion,
                $monto,
                $fecha
            );

            $_SESSION['success'] = "Gasto actualizado correctamente";
        }

        header("Location: /gastos");
        exit;
    }



    public function delete()
    {

        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idIngreso = $_GET['id'] ?? null; // 👈 ID DEL INGRESO
        $idUsuario = $_SESSION['usuario']['id_usuario']; // 👈 ID DEL USUARIO

        if ($idIngreso) {
            $this->model->eliminar($idIngreso, $idUsuario);
        }

        header("Location: /gastos");
        exit;
    }
}
