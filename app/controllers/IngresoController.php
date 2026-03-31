<?php

require_once __DIR__ . '/../models/IngresoModel.php';

class IngresoController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new IngresoModel($pdo);
    }

        public function index()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id_usuario'];

        $pagina = $_GET['pagina'] ?? 1;
        $limite = 10;

        $offset = ($pagina - 1) * $limite;

        $ingresos = $this->model->obtenerPaginados($idUsuario, $limite, $offset);

        $totalRegistros = $this->model->contar($idUsuario);
        $totalPaginas = ceil($totalRegistros / $limite);

        return [
            'ingresos' => $ingresos,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas
        ];
    }

    public function store()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario'])) {
        echo json_encode([
            "success" => false,
            "error" => "No autorizado"
        ]);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id_usuario'];

    $descripcion = trim($_POST['descripcion'] ?? '');
    $monto = $_POST['monto'] ?? 0;
    $fecha = $_POST['fecha'] ?? '';

    $monto = str_replace('.', '', $monto);
    $monto = str_replace(',', '.', $monto);
    $monto = floatval($monto);

    if (empty($descripcion) || empty($fecha)) {
        echo json_encode([
            "success" => false,
            "error" => "Todos los campos son obligatorios"
        ]);
        exit;
    }

    if (!is_numeric($monto) || $monto <= 0) {
        echo json_encode([
            "success" => false,
            "error" => "El monto debe ser mayor a 0"
        ]);
        exit;
    }

    $ok = $this->model->crear($idUsuario, $descripcion, $monto, $fecha);

    if ($ok) {
        echo json_encode([
            "success" => true
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "error" => "No se pudo guardar el ingreso"
        ]);
    }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode([
                "success" => false,
                "error" => "No autorizado"
            ]);
            exit;
        }

        $id = $_POST['id'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $monto = $_POST['monto'] ?? 0;
        $fecha = $_POST['fecha'] ?? '';

        // limpiar monto
        $monto = str_replace('.', '', $monto);
        $monto = str_replace(',', '.', $monto);
        $monto = floatval($monto);

        $idUsuario = $_SESSION['usuario']['id_usuario'];

        if (!$id) {
            echo json_encode([
                "success" => false,
                "error" => "ID inválido"
            ]);
            exit;
        }

        if (empty($descripcion) || empty($fecha)) {
            echo json_encode([
                "success" => false,
                "error" => "Todos los campos son obligatorios"
            ]);
            exit;
        }

        if (!is_numeric($monto) || $monto <= 0) {
            echo json_encode([
                "success" => false,
                "error" => "El monto debe ser mayor a 0"
            ]);
            exit;
        }

        $ingreso = $this->model->buscarPorId($id, $idUsuario);

        if (!$ingreso) {
            echo json_encode([
                "success" => false,
                "error" => "No tienes permiso para editar este ingreso"
            ]);
            exit;
        }

        $ok = $this->model->actualizar(
            $id,
            $idUsuario,
            $descripcion,
            $monto,
            $fecha
        );

        if ($ok) {
            echo json_encode([
                "success" => true
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "No se pudo actualizar"
            ]);
        }

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
