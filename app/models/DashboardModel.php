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
}
