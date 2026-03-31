<?php 
    require_once __DIR__ . '/../dashboard/layout/header.php';
    require_once __DIR__ . '/../dashboard/layout/sidebar.php';
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4 px-4">

        <h3 class="mb-0"><i class="bi bi-layout-text-sidebar"></i> Historial de Movimientos</h3>

        <button class="btn btn-success" onclick="exportarExcel()">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
        </button>

    </div>
    <!-- <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">

            <h3 class="mb-0"><i class="bi bi-layout-text-sidebar"></i> Historial de Movimientos</h3>

            <button class="btn btn-success" onclick="exportarExcel()">
                📥 Exportar a Excel
            </button>

        </div> -->

    <div class="card shadow-sm">
        <div class="card-body">

        <div id="alertaExportacion"></div>

        <div class="row mb-3">

            <!-- Buscador -->
            <div class="col-md-2">
                <input type="text" id="buscarMovimiento" class="form-control" placeholder="Buscar..." value="<?= $_GET['texto'] ?? '' ?>">
            </div>

            <!-- Tipo -->
            <div class="col-md-2">
                <select id="filtroTipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="ingreso" <?= (($_GET['tipo'] ?? '')=='ingreso')?'selected':'' ?>>Ingresos</option>
                    <option value="gasto" <?= (($_GET['tipo'] ?? '')=='gasto')?'selected':'' ?>>Gastos</option>
                </select>
            </div>

            <!-- Mes -->
            <div class="col-md-2">
                <select id="filtroMes" class="form-select">
                    <option value="">Mes</option>
                    <option value="01" <?= (($_GET['mes'] ?? '')=='01')?'selected':'' ?>>Enero</option>
                    <option value="02" <?= (($_GET['mes'] ?? '')=='02')?'selected':'' ?>>Febrero</option>
                    <option value="03" <?= (($_GET['mes'] ?? '')=='03')?'selected':'' ?>>Marzo</option>
                    <option value="04" <?= (($_GET['mes'] ?? '')=='04')?'selected':'' ?>>Abril</option>
                    <option value="05" <?= (($_GET['mes'] ?? '')=='05')?'selected':'' ?>>Mayo</option>
                    <option value="06" <?= (($_GET['mes'] ?? '')=='06')?'selected':'' ?>>Junio</option>
                    <option value="07" <?= (($_GET['mes'] ?? '')=='07')?'selected':'' ?>>Julio</option>
                    <option value="08" <?= (($_GET['mes'] ?? '')=='08')?'selected':'' ?>>Agosto</option>
                    <option value="09" <?= (($_GET['mes'] ?? '')=='09')?'selected':'' ?>>Septiembre</option>
                    <option value="10" <?= (($_GET['mes'] ?? '')=='10')?'selected':'' ?>>Octubre</option>
                    <option value="11" <?= (($_GET['mes'] ?? '')=='11')?'selected':'' ?>>Noviembre</option>
                    <option value="12" <?= (($_GET['mes'] ?? '')=='12')?'selected':'' ?>>Diciembre</option>
                </select>
            </div>

            <!-- Desde -->
            <div class="col-md-3">
                <input type="date" id="fechaDesde" class="form-control" value="<?= $_GET['desde'] ?? '' ?>">
            </div>

            <!-- Hasta -->
            <div class="col-md-3">
                <input type="date" id="fechaHasta" class="form-control" value="<?= $_GET['hasta'] ?? '' ?>">
            </div>
            
            <div class="col-md-12 d-flex justify-content-end gap-2 mt-2">
                <button class="btn btn-primary" onclick="aplicarFiltros()">
                    <i class="bi bi-funnel-fill"></i> Aplicar filtros
                </button>

                <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
            </div>

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

                    <tr 
                        data-tipo="<?= strtolower($mov['tipo']) ?>" 
                        data-fecha="<?= $mov['fecha'] ?>"
                    >

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

            <?php $query = $_GET; ?>

            <nav>
            <ul class="pagination justify-content-center">

                <!-- Anterior -->
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                    href="?<?= http_build_query(array_merge($query, ['pagina' => $pagina - 1])) ?>">
                    Anterior
                    </a>
                </li>

                <!-- Números -->
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link"
                        href="?<?= http_build_query(array_merge($query, ['pagina' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Siguiente -->
                <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                    <a class="page-link"
                    href="?<?= http_build_query(array_merge($query, ['pagina' => $pagina + 1])) ?>">
                    Siguiente
                    </a>
                </li>

            </ul>
        </nav>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../dashboard/layout/footer.php'; ?>

<script>

const buscador = document.getElementById("buscarMovimiento");
const filtroTipo = document.getElementById("filtroTipo");
const filtroMes = document.getElementById("filtroMes");
const fechaDesde = document.getElementById("fechaDesde");
const fechaHasta = document.getElementById("fechaHasta");

function aplicarFiltros(){

    const texto = buscador.value;
    const tipo = filtroTipo.value;
    const mes = filtroMes.value;
    const desde = fechaDesde.value;
    const hasta = fechaHasta.value;

    let url = `/paytrack/public/index.php?url=movimientos`;

    if(texto) url += `&texto=${encodeURIComponent(texto)}`;
    if(tipo) url += `&tipo=${tipo}`;
    if(mes) url += `&mes=${mes}`;
    if(desde) url += `&desde=${desde}`;
    if(hasta) url += `&hasta=${hasta}`;

    url += `&pagina=1`;

    window.location.href = url;
}

function limpiarFiltros(){

    // limpiar inputs
    buscador.value = "";
    filtroTipo.value = "";
    filtroMes.value = "";
    fechaDesde.value = "";
    fechaHasta.value = "";

    // recargar sin filtros
    window.location.href = `/paytrack/public/index.php?url=movimientos`;
}

buscador.addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        aplicarFiltros();
    }
});

function exportarExcel(){

    const texto = buscador.value;
    const tipo = filtroTipo.value;
    const mes = filtroMes.value;
    const desde = fechaDesde.value;
    const hasta = fechaHasta.value;

    let url = `/paytrack/public/index.php?url=movimientos/exportar-excel`;

    if(texto) url += `&texto=${encodeURIComponent(texto)}`;
    if(tipo) url += `&tipo=${tipo}`;
    if(mes) url += `&mes=${mes}`;
    if(desde) url += `&desde=${desde}`;
    if(hasta) url += `&hasta=${hasta}`;

    window.open(url, '_blank');
}

function mostrarAlerta(mensaje){

    const contenedor = document.getElementById("alertaExportacion");

    contenedor.innerHTML = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

</script>