<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">

    <?php if (!$gasto): ?>

        <div class="alert alert-danger">
            Gasto no encontrado
        </div>

        <a href="/gastos" class="btn btn-secondary mt-3">Volver</a>

    <?php else: ?>

        <div class="card shadow">
            <div class="card-body">

                <h4 class="mb-4">Editar gasto</h4>

                <form action="/gastos/update" method="POST">

                    <input type="hidden" name="id" value="<?= $gasto['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <input 
                            type="text"
                            name="categoria"
                            class="form-control"
                            value="<?= htmlspecialchars($gasto['categoria']); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input 
                            type="text"
                            name="descripcion"
                            class="form-control"
                            value="<?= htmlspecialchars($gasto['descripcion']); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input 
                            type="number"
                            name="monto"
                            class="form-control"
                            value="<?= intval($gasto['monto']); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input 
                            type="date"
                            name="fecha"
                            class="form-control"
                            value="<?= $gasto['fecha']; ?>"
                            required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/gastos" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button class="btn btn-success">
                            Guardar cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
