<?php

class UserModel {

    private $pdo;

    // Recibe la conexión PDO desde el controlador
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* ==========================================================
     * 1. BUSCAR USUARIO POR EMAIL
     * ========================================================== */
    public function buscarPorEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);  // devuelve array o false
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /* ==========================================================
     * 2. BUSCAR USUARIO POR ID
     * ========================================================== */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT id, nombre, email, created_at 
                    FROM usuarios 
                    WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ==========================================================
     * 3. REGISTRAR USUARIO (INSERT)
     * ========================================================== */
    public function registrarUsuario($nombre, $email, $passwordHash) {
        try {
            $sql = "INSERT INTO usuarios (nombre, email, password) 
                    VALUES (:nombre, :email, :password)";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'password' => $passwordHash
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ==========================================================
     * 4. ACTUALIZAR DATOS DEL USUARIO
     * ========================================================== */
    public function actualizarDatos($id, $nombre, $email) {
        try {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, email = :email 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ==========================================================
     * 5. ACTUALIZAR CONTRASEÑA
     * ========================================================== */
    public function actualizarPassword($id, $nuevoPasswordHash) {
        try {
            $sql = "UPDATE usuarios 
                    SET password = :password 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                'password' => $nuevoPasswordHash,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ==========================================================
     * 6. ELIMINAR USUARIO
     * ========================================================== */
    public function eliminarUsuario($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ==========================================================
     * 7. LISTAR TODOS LOS USUARIOS (OPCIONAL)
     * ========================================================== */
    public function listarUsuarios() {
        try {
            $sql = "SELECT id, nombre, email, created_at 
                    FROM usuarios ORDER BY id DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function guardarCodigoRecuperacion($email, $codigo)
    {
        try {
            $sql = "UPDATE usuarios 
                    SET codigo_recuperacion = :codigo, 
                        codigo_expira = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
                    WHERE email = :email";

            $query = $this->pdo->prepare($sql);
            $query->bindParam(':codigo', $codigo);
            $query->bindParam(':email', $email);
            $query->execute();

            return $query->rowCount() > 0; // true si el email existe
        } catch (PDOException $e) {
            return false; // manejarás el error en el controlador
        }
    }

}
?>
