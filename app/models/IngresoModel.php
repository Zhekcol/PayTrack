<?php

class IngresoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarPorUsuario($idUsuario)
    {
        $sql = "SELECT * FROM ingresos 
                WHERE id_usuario = :id 
                ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($idUsuario, $descripcion, $monto, $fecha)
    {
        $sql = "INSERT INTO ingresos (id_usuario, descripcion, monto, fecha)
                VALUES (:id_usuario, :descripcion, :monto, :fecha)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_usuario' => $idUsuario,
            'descripcion' => $descripcion,
            'monto' => $monto,
            'fecha' => $fecha
        ]);
    }

    public function buscarPorId($id, $idUsuario)
    {
        $sql = "SELECT * FROM ingresos 
                WHERE id = :id AND id_usuario = :id_usuario
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $idUsuario, $descripcion, $monto, $fecha)
    {
        $sql = "UPDATE ingresos 
                SET descripcion = :descripcion, 
                    monto = :monto, 
                    fecha = :fecha
                WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'descripcion' => $descripcion,
            'monto' => $monto,
            'fecha' => $fecha,
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }



    public function eliminar($id, $idUsuario)
    {
        $sql = "DELETE FROM ingresos 
                WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }
}
