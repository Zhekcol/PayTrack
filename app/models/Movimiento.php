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
}