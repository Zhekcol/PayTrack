<?php 
    require_once __DIR__ . '/../dashboard/layout/header.php';
    require_once __DIR__ . '/../dashboard/layout/sidebar.php';
?>

<div class="container mt-4">

    <h3 class="mb-4">📊 Historial de Movimientos</h3>

    <div class="card shadow-sm">
        <div class="card-body">

        <div class="mb-3">
            <input 
                type="text" 
                id="buscarMovimiento" 
                class="form-control" 
                placeholder="Buscar por descripción o tipo..."
            >
        </div>
            <table class="table table-striped table-hover" id="tablaMovimientos">

                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($movimientos)): ?>

                <?php foreach($movimientos as $mov): ?>

                    <tr>

                        <td><?= $mov['fecha'] ?></td>

                        <td>
                        <?php if($mov['tipo'] == 'Ingreso'): ?>
                            <span class="badge bg-success">Ingreso</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Gasto</span>
                        <?php endif; ?>
                        </td>

                        <td><?= $mov['descripcion'] ?></td>

                        <td>
                        <?php if($mov['tipo'] == 'Ingreso'): ?>
                            <span class="text-success fw-bold">
                                +$<?= number_format($mov['monto'],0,',','.') ?>
                            </span>
                        <?php else: ?>
                            <span class="text-danger fw-bold">
                                -$<?= number_format($mov['monto'],0,',','.') ?>
                            </span>
                        <?php endif; ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php else: ?>

                <tr>
                <td colspan="4" class="text-center">No hay movimientos registrados.</td>
                </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../dashboard/layout/footer.php'; ?>

<script>

const buscador = document.getElementById("buscarMovimiento");

buscador.addEventListener("keyup", function(){

    const filtro = buscador.value.toLowerCase();

    const filas = document.querySelectorAll("#tablaMovimientos tbody tr");

    filas.forEach(fila => {

        const textoFila = fila.textContent.toLowerCase();

        if(textoFila.includes(filtro)){
            fila.style.display = "";
        }else{
            fila.style.display = "none";
        }

    });

});

</script>