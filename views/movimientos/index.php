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
            <div class="col-md-3">
                <input type="text" id="buscarMovimiento" class="form-control" placeholder="Buscar...">
            </div>

            <!-- Tipo -->
            <div class="col-md-2">
                <select id="filtroTipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="ingreso">Ingresos</option>
                    <option value="gasto">Gastos</option>
                </select>
            </div>

            <!-- Mes -->
            <div class="col-md-2">
                <select id="filtroMes" class="form-select">
                    <option value="">Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>

            <!-- Desde -->
            <div class="col-md-2">
                <input type="date" id="fechaDesde" class="form-control">
            </div>

            <!-- Hasta -->
            <div class="col-md-2">
                <input type="date" id="fechaHasta" class="form-control">
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

function filtrarTabla(){

    const texto = buscador.value.toLowerCase();
    const tipo = filtroTipo.value;
    const mes = filtroMes.value;
    const desde = fechaDesde.value;
    const hasta = fechaHasta.value;

    const filas = document.querySelectorAll("#tablaMovimientos tbody tr");

    filas.forEach(fila => {

        const contenido = fila.textContent.toLowerCase();
        const filaTipo = fila.getAttribute("data-tipo");
        const fecha = fila.getAttribute("data-fecha");
        const filaMes = fecha ? fecha.split("-")[1] : "";

        let mostrar = true;

        // filtro por texto
        if(!contenido.includes(texto)){
            mostrar = false;
        }

        // filtro por tipo
        if(tipo && filaTipo !== tipo){
            mostrar = false;
        }

        // filtro por mes
        if(mes && filaMes !== mes){
            mostrar = false;
        }

        // filtro por rango de fechas
        if(desde && fecha < desde){
            mostrar = false;
        }

        if(hasta && fecha > hasta){
            mostrar = false;
        }

        fila.style.display = mostrar ? "" : "none";

    });

}

// Eventos
buscador.addEventListener("keyup", filtrarTabla);
filtroTipo.addEventListener("change", filtrarTabla);
filtroMes.addEventListener("change", filtrarTabla);
fechaDesde.addEventListener("change", filtrarTabla);
fechaHasta.addEventListener("change", filtrarTabla);

function hayFilasVisibles() {
    const filas = document.querySelectorAll("#tablaMovimientos tbody tr");
    return Array.from(filas).some(fila => fila.style.display !== "none");
}

function exportarExcel(){

    if(!hayFilasVisibles()){
        mostrarAlerta("No hay datos para exportar con los filtros actuales");
        return;
    }

    const texto = buscador.value;
    const tipo = filtroTipo.value;
    const mes = filtroMes.value;
    const desde = fechaDesde.value;
    const hasta = fechaHasta.value;

    let url = `/paytrack/public/index.php?url=movimientos/exportar-excel`;

    url += `&texto=${encodeURIComponent(texto)}`;
    url += `&tipo=${tipo}`;
    url += `&mes=${mes}`;
    url += `&desde=${desde}`;
    url += `&hasta=${hasta}`;

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