<?php

require_once __DIR__ . '/../models/IngresoModel.php';

class IngresoController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new IngresoModel($pdo);
    }

    public function index($idUsuario)
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        return $this->model->listarPorUsuario($idUsuario);
    }

    public function store()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $id_usuario = $_SESSION['usuario']['id_usuario']; 

        $descripcion = $_POST['descripcion'];
        $monto = $_POST['monto'];
        $fecha = $_POST['fecha'];

        $this->model->crear($id_usuario, $descripcion, $monto, $fecha);

        header("Location: /ingresos");
        exit;
    }

    public function edit()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ingresos");
            exit;
        }

        return $this->model->buscarPorId(
            $id,
            $_SESSION['usuario']['id_usuario']
        );
    }

    public function update()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $id = $_POST['id'];
        $descripcion = $_POST['descripcion'];
        $monto = $_POST['monto'];
        $fecha = $_POST['fecha'];

        $this->model->actualizar(
            $id,
            $_SESSION['usuario']['id_usuario'],
            $descripcion,
            $monto,
            $fecha
        );

        $_SESSION['success'] = "Ingreso actualizado correctamente";

        header("Location: /ingresos");
        exit;
    }


    public function delete()
    {

        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idIngreso = $_GET['id'] ?? null; 
        $idUsuario = $_SESSION['usuario']['id_usuario']; 

        if ($idIngreso) {
            $this->model->eliminar($idIngreso, $idUsuario);
        }

        header("Location: /ingresos");
        exit;
    }

}
