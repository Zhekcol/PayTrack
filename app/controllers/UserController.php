<?php 

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function actualizarPerfil($id, $nombre, $email)
    {
        // editar perfil
    }

    public function cambiarPassword($id, $passwordActual, $passwordNueva)
    {
        // cambiar contraseña estando logueado
    }

    public function eliminarCuenta($id)
    {
        // eliminar usuario
    }
}


?>