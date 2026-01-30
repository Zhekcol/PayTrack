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
}
