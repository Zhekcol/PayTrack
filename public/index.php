<?php
session_start();

require_once __DIR__ . '/../app/config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

switch ($url) {

    case '':
        require_once __DIR__ . '/../views/home.php';
        break;

    case 'auth/register':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'auth/login':
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/controllers/DashboardController.php';

        $controller = new DashboardController($pdo);
        $controller->resumen();
        break;

    case 'auth/login_action':
        require_once __DIR__ . '/../views/actions/login_action.php';
        break;

    case 'auth/register_action':
        require_once __DIR__ . '/../views/actions/register_action.php';
        break;

    case 'auth/logout':
        require_once __DIR__ . '/../views/actions/logout_action.php';
        break;

    case 'ingresos':
        require_once __DIR__ . '/../app/controllers/IngresoController.php';

        $controller = new IngresoController($pdo);
        $data = $controller->index();

        $ingresos = $data['ingresos'];
        $pagina = $data['pagina'];
        $totalPaginas = $data['totalPaginas'];
        require_once __DIR__ . '/../views/dashboard/ingresos/index.php';
        break;

    case 'ingresos/store':
        require_once __DIR__ . '/../app/controllers/IngresoController.php';

        $controller = new IngresoController($pdo);
        $controller->store(); 

        header("Location: /ingresos");
        break;

    case 'ingresos/delete':
        require_once __DIR__ . '/../app/controllers/IngresoController.php';

        $controller = new IngresoController($pdo);
        $controller->delete($_SESSION['usuario']['id_usuario']);
        break;

    case 'ingresos/edit':
        require_once __DIR__ . '/../app/controllers/IngresoController.php';
        $controller = new IngresoController($pdo);
        $ingreso = $controller->edit();
        require_once __DIR__ . '/../views/dashboard/ingresos/edit.php';
        break;

    case 'ingresos/update':
        require_once __DIR__ . '/../app/controllers/IngresoController.php';
        $controller = new IngresoController($pdo);
        $controller->update();
        break;

    case 'gastos':
        require_once __DIR__ . '/../app/controllers/GastoController.php';
        $controller = new GastoController($pdo);
        $data = $controller->index();

        $gastos = $data['gastos'];
        $pagina = $data['pagina'];
        $totalPaginas = $data['totalPaginas'];
        require_once __DIR__ . '/../views/dashboard/gastos/index.php';
        break;

    case 'gastos/store':
        require_once __DIR__ . '/../app/controllers/GastoController.php';
        $controller = new GastoController($pdo);
        $controller->store();
        require_once __DIR__ . '/../views/dashboard/gastos/index.php';
        exit;

    case 'gastos/edit':
        require_once __DIR__ . '/../app/controllers/GastoController.php';
        $controller = new GastoController($pdo);
        $gasto = $controller->edit();
        require_once __DIR__ . '/../views/dashboard/gastos/edit.php';
        break;

    case 'gastos/update':
        require_once __DIR__ . '/../app/controllers/GastoController.php';
        $controller = new GastoController($pdo);
        $controller->update();
        break;

    case 'gastos/delete':
        require_once __DIR__ . '/../app/controllers/GastoController.php';
        $controller = new GastoController($pdo);
        $controller->delete();
        break;

    case 'obtener-gastos-mensuales':
        require_once '../app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->obtenerGastosMensuales();
    exit;

    case 'obtener-gastos-categoria':
        require_once '../app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->obtenerGastosCategoria();
    break;

    case 'obtener-ingresos-gastos-mensuales':
    require_once '../app/controllers/DashboardController.php';
    $controller = new DashboardController($pdo);
    $controller->obtenerIngresosYGastosMensuales();
    break;

    case 'movimientos':
        require_once "../app/controllers/MovimientosController.php";
        $controller = new MovimientosController($pdo);
        $data = $controller->index();

        $movimientos = $data['movimientos'];
        $pagina = $data['pagina'];
        $totalPaginas = $data['totalPaginas'];

        require "../views/movimientos/index.php";
    break;

    case 'movimientos/exportar-excel':
        require_once __DIR__ . '/../app/controllers/MovimientosController.php';

        $controller = new MovimientosController($pdo);
        $controller->exportarExcel();
    break;

    default:
        echo "404 - Página no encontrada";
        break;
}
