<?php

class DashboardModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function totalIngresos($id_usuario) {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(monto),0) FROM ingresos WHERE id_usuario = ?"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn();
    }

    public function totalGastos($id_usuario) {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(monto),0) FROM gastos WHERE id_usuario = ?"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn();
    }

    public function gastosPorMes($id_usuario)
    {
        $sql = "SELECT 
                MONTH(fecha) as mes,
                SUM(monto) as total
                FROM gastos
                WHERE id_usuario = :id_usuario
                GROUP BY MONTH(fecha)
                ORDER BY mes";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function gastosPorCategoria($usuario_id)
    {
        $sql = "SELECT 
                    categoria,
                    SUM(monto) as total
                FROM gastos
                WHERE id_usuario = :usuario_id
                GROUP BY categoria
                ORDER BY total DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ingresosYGastosPorMes($usuario_id)
    {
        $sql = "
            SELECT meses.mes,
            IFNULL(i.total_ingresos,0) as ingresos,
            IFNULL(g.total_gastos,0) as gastos
            FROM
            (
                SELECT 1 as mes UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
                UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
            ) meses
            LEFT JOIN (
                SELECT MONTH(fecha) mes, SUM(monto) total_ingresos
                FROM ingresos
                WHERE id_usuario = :usuario_id
                GROUP BY MONTH(fecha)
            ) i ON meses.mes = i.mes
            LEFT JOIN (
                SELECT MONTH(fecha) mes, SUM(monto) total_gastos
                FROM gastos
                WHERE id_usuario = :usuario_id
                GROUP BY MONTH(fecha)
            ) g ON meses.mes = g.mes
            ORDER BY meses.mes
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
