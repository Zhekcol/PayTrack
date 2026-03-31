<?php

class GastoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerPorUsuario($idUsuario)
    {
        $sql = "SELECT * FROM gastos 
                WHERE id_usuario = :id_usuario 
                ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($idUsuario, $categoria, $descripcion, $monto, $fecha)
    {
        $sql = "INSERT INTO gastos (id_usuario, categoria, descripcion, monto, fecha)
                VALUES (:id_usuario, :categoria, :descripcion, :monto, :fecha)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_usuario' => $idUsuario,
            'categoria' => $categoria,
            'descripcion' => $descripcion,
            'monto' => $monto,
            'fecha' => $fecha
        ]);
    }

    public function eliminar($id, $idUsuario)
    {
        $sql = "DELETE FROM gastos 
                WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }

    public function obtenerPorId($id, $idUsuario)
    {
        $sql = "SELECT * FROM gastos 
                WHERE id = :id AND id_usuario = :id_usuario
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function actualizar($id, $idUsuario, $categoria, $descripcion, $monto, $fecha)
    {
        $sql = "UPDATE gastos 
                SET categoria = :categoria, descripcion = :descripcion, monto = :monto, fecha = :fecha
                WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'categoria' => $categoria,
            'descripcion' => $descripcion,
            'monto' => $monto,
            'fecha' => $fecha,
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }

    public function update()
    {
        session_start();

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: /login");
            exit;
        }

        $id = $_POST['id'] ?? null;
        $categoria = $_POST['categoria'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $monto = $_POST['monto'] ?? 0;
        $fecha = $_POST['fecha'] ?? '';
        $idUsuario = $_SESSION['id_usuario'];

        if ($id) {
            $this->pdo->actualizar($id, $idUsuario, $categoria, $descripcion, $monto, $fecha);
            $_SESSION['success'] = "Gasto actualizado correctamente";
        }

        header("Location: /gastos");
        exit;
    }

    public function obtenerPaginados($idUsuario, $limite, $offset)
    {
        $sql = "SELECT * FROM gastos 
                WHERE id_usuario = :id_usuario
                ORDER BY fecha DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contar($idUsuario)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM gastos 
                WHERE id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

}
