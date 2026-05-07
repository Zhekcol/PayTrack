<?php
// Recibir mensajes desde login_action
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$tipo    = isset($_GET['tipo']) ? $_GET['tipo'] : ''; // success | danger
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - PayTrack</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center mb-4">Iniciar sesión</h3>

                    <!-- Mensaje -->
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($tipo); ?>">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/login_action" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Ingresar
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="/auth/forgot" class="text-decoration-none">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <div class="text-center mt-2">
                        <small>
                            ¿No tienes cuenta?
                            <a href="/auth/register">Regístrate aquí</a>
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
