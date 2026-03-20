<?php

class MovimientoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerPorUsuario($idUsuario)
    {
        $sql = "SELECT fecha, 'Ingreso' AS tipo, descripcion, monto
                FROM ingresos
                WHERE id_usuario = :id_usuario

                UNION ALL

                SELECT fecha, 'Gasto' AS tipo, descripcion, monto
                FROM gastos
                WHERE id_usuario = :id_usuario

                ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrarMovimientos($idUsuario, $texto, $tipo, $mes, $fechaDesde, $fechaHasta)
    {
        $sql = "
            SELECT fecha, 'Ingreso' as tipo, descripcion, monto
            FROM ingresos
            WHERE id_usuario = :id_usuario

            UNION ALL

            SELECT fecha, 'Gasto' as tipo, descripcion, monto
            FROM gastos
            WHERE id_usuario = :id_usuario
        ";

        $params = ['id_usuario' => $idUsuario];

        // filtros dinámicos
        $sql = "SELECT * FROM ($sql) as movimientos WHERE 1=1";

        // búsqueda
        if (!empty($texto)) {
            $sql .= " AND descripcion LIKE :texto";
            $params['texto'] = "%$texto%";
        }

        // tipo
        if (!empty($tipo)) {
            $sql .= " AND LOWER(tipo) = :tipo";
            $params['tipo'] = strtolower($tipo);
        }

        // mes
        if (!empty($mes)) {
            $sql .= " AND MONTH(fecha) = :mes";
            $params['mes'] = (int)$mes;
        }

        if (!empty($fechaDesde)) {
            $sql .= " AND fecha >= :fechaDesde";
            $params['fechaDesde'] = $fechaDesde;
        }

        if (!empty($fechaHasta)) {
            $sql .= " AND fecha <= :fechaHasta";
            $params['fechaHasta'] = $fechaHasta;
        }

        $sql .= " ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}