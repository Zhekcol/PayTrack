<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">

            <h4 class="mb-4">Editar ingreso</h4>

            <form action="/ingresos/update" method="POST">

                <input type="hidden" name="id" value="<?= $ingreso['id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input 
                        type="text" 
                        name="descripcion" 
                        class="form-control"
                        value="<?= htmlspecialchars($ingreso['descripcion']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto</label>
                    <input 
                        type="number" 
                        name="monto" 
                        class="form-control"
                        value="<?= $ingreso['monto']; ?>"
                        step="0.01"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input 
                        type="date" 
                        name="fecha" 
                        class="form-control"
                        value="<?= $ingreso['fecha']; ?>"
                        required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/ingresos" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button class="btn btn-success">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
