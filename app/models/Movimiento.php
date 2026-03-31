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

    public function filtrarMovimientosPaginado($idUsuario, $texto, $tipo, $mes, $desde, $hasta, $limit, $offset)
    {
        $sqlBase = "
            SELECT fecha, 'Ingreso' as tipo, descripcion, monto
            FROM ingresos
            WHERE id_usuario = :id_usuario

            UNION ALL

            SELECT fecha, 'Gasto' as tipo, descripcion, monto
            FROM gastos
            WHERE id_usuario = :id_usuario
        ";

        $params = ['id_usuario' => $idUsuario];

        $sql = "SELECT * FROM ($sqlBase) as movimientos WHERE 1=1";

        // búsqueda
        if (!empty($texto)) {
            $sql .= " AND (
                descripcion LIKE LOWER(:texto)
                OR tipo LIKE LOWER(:texto)
                OR CAST(monto AS CHAR) LIKE :texto
                OR DATE_FORMAT(fecha, '%Y-%m-%d') LIKE :texto
            )";
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

        // rango fechas
        if (!empty($desde)) {
            $sql .= " AND fecha >= :desde";
            $params['desde'] = $desde;
        }

        if (!empty($hasta)) {
            $sql .= " AND fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        // orden + paginado
        $sql .= " ORDER BY fecha DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarMovimientos($idUsuario, $texto, $tipo, $mes, $desde, $hasta)
    {
        $sqlBase = "
            SELECT fecha, 'Ingreso' as tipo, descripcion, monto
            FROM ingresos
            WHERE id_usuario = :id_usuario

            UNION ALL

            SELECT fecha, 'Gasto' as tipo, descripcion, monto
            FROM gastos
            WHERE id_usuario = :id_usuario
        ";

        $params = ['id_usuario' => $idUsuario];

        $sql = "SELECT COUNT(*) as total FROM ($sqlBase) as movimientos WHERE 1=1";

        if (!empty($texto)) {
            $sql .= " AND descripcion LIKE :texto";
            $params['texto'] = "%$texto%";
        }

        if (!empty($tipo)) {
            $sql .= " AND LOWER(tipo) = :tipo";
            $params['tipo'] = strtolower($tipo);
        }

        if (!empty($mes)) {
            $sql .= " AND MONTH(fecha) = :mes";
            $params['mes'] = (int)$mes;
        }

        if (!empty($desde)) {
            $sql .= " AND fecha >= :desde";
            $params['desde'] = $desde;
        }

        if (!empty($hasta)) {
            $sql .= " AND fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}