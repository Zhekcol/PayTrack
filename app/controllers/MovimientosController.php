<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $idUsuario = $_SESSION['usuario']['id_usuario'];

        // filtros
        $texto = $_GET['texto'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $mes = $_GET['mes'] ?? '';
        $desde = $_GET['desde'] ?? '';
        $hasta = $_GET['hasta'] ?? '';

        // paginado
        $pagina = $_GET['pagina'] ?? 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        // datos
        $movimientos = $this->model->filtrarMovimientosPaginado(
            $idUsuario, $texto, $tipo, $mes, $desde, $hasta, $limite, $offset
        );

        $total = $this->model->contarMovimientos(
            $idUsuario, $texto, $tipo, $mes, $desde, $hasta
        );

        $totalPaginas = ceil($total / $limite);

        return [
            'movimientos' => $movimientos,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas
        ];
    }

    public function exportarExcel()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /auth/login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id_usuario'];
        $texto = $_GET['texto'] ?? '';
        $tipo  = $_GET['tipo'] ?? '';
        $mes   = $_GET['mes'] ?? '';
        $fechaDesde = $_GET['desde'] ?? '';
        $fechaHasta = $_GET['hasta'] ?? '';

        // Convertir formato dd/mm/yyyy → yyyy-mm-dd
        if (!empty($fechaDesde)) {
            $fechaDesde = date('Y-m-d', strtotime(str_replace('/', '-', $fechaDesde)));
        }

        if (!empty($fechaHasta)) {
            $fechaHasta = date('Y-m-d', strtotime(str_replace('/', '-', $fechaHasta)));
        }

        $movimientos = $this->model->filtrarMovimientos(
            $idUsuario, 
            $texto, 
            $tipo, 
            $mes, 
            $fechaDesde, 
            $fechaHasta
        );

        if (empty($movimientos)) {
            echo "<script>
                alert('No hay datos para exportar con los filtros seleccionados');
                window.close();
            </script>";
            exit;
        }

        // IMPORTANTE: cargar PhpSpreadsheet
        require_once __DIR__ . '/../../vendor/autoload.php';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Movimientos");

        // Encabezados
        $sheet->setCellValue('A1', 'Fecha');
        $sheet->setCellValue('B1', 'Tipo');
        $sheet->setCellValue('C1', 'Descripción');
        $sheet->setCellValue('D1', 'Monto');

        // Estilo encabezado
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF212529');

        $sheet->getStyle('A1:D1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // 📏 Ancho columnas
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(18);

        // Datos
        $fila = 2;

        foreach ($movimientos as $mov) {

            $monto = ($mov['tipo'] === 'Ingreso')
                ? $mov['monto']
                : -$mov['monto'];

            $sheet->setCellValue('A' . $fila, $mov['fecha']);
            $sheet->setCellValue('B' . $fila, $mov['tipo']);
            $sheet->setCellValue('C' . $fila, $mov['descripcion']);
            $sheet->setCellValue('D' . $fila, $monto);

            // formato número
            $sheet->getStyle('D' . $fila)
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            // color
            if ($mov['tipo'] === 'Ingreso') {
                $sheet->getStyle('D' . $fila)->getFont()->getColor()->setARGB('FF198754');
            } else {
                $sheet->getStyle('D' . $fila)->getFont()->getColor()->setARGB('FFDC3545');
            }

            $fila++;
        }

        $ultimaFila = $fila - 1;

        // TOTAL
        $filaTotal = $fila + 1;

        $sheet->setCellValue('C' . $filaTotal, 'TOTAL');
        $sheet->setCellValue('D' . $filaTotal, "=SUM(D2:D$ultimaFila)");

        $sheet->getStyle('C' . $filaTotal . ':D' . $filaTotal)
            ->getFont()->setBold(true);

        // FILTRO
        $sheet->setAutoFilter("A1:D$ultimaFila");

        // Descargar
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="movimientos.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }
}