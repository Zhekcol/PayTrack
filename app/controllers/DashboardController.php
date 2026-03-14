<?php
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController {
    private $model;

    public function __construct($pdo) {
        $this->model = new DashboardModel($pdo);
    }

    public function resumen() {

        if (!isset($_SESSION['usuario'])) {
            header('Location: /PayTrack/public/auth/login');
            exit;
        }

        $id_usuario = $_SESSION['usuario']['id_usuario']; 

        $ingresos = $this->model->totalIngresos($id_usuario);
        $gastos   = $this->model->totalGastos($id_usuario);
        $balance  = $ingresos - $gastos;

        require_once __DIR__ . '/../../views/dashboard/overview.php';
    }

   public function obtenerGastosMensuales()
    {
        header('Content-Type: application/json');

        $usuario_id = $_SESSION['usuario']['id_usuario'];

        $datos = $this->model->gastosPorMes($usuario_id);

        if (!$datos) {
            $datos = [];
        }

        echo json_encode($datos);
        exit;
    }

    public function obtenerGastosCategoria()
    {
        header('Content-Type: application/json');

        $usuario_id = $_SESSION['usuario']['id_usuario'];

        $datos = $this->model->gastosPorCategoria($usuario_id);

        if(!$datos){
            $datos = [];
        }

        echo json_encode($datos);
        exit;
    }

    public function obtenerIngresosYGastosMensuales()
    {
        header('Content-Type: application/json');

        $usuario_id = $_SESSION['usuario']['id_usuario'];

        $datos = $this->model->ingresosYGastosPorMes($usuario_id);

        echo json_encode($datos);
        exit;
    }
}
