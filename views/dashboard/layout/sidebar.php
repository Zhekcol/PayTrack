<div class="col-12 col-md-2 bg-dark text-white p-3">
    <h4 class="mb-4">PayTrack</h4>

    <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item">
            <a href="/dashboard" 
                class="nav-link text-white <?= ($_GET['url'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-line-fill"></i> Resumen
            </a>
        </li>
        <li class="nav-item">
            <a href="/ingresos"
                class="nav-link text-white <?= ($_GET['url'] ?? '') === 'ingresos' ? 'active' : '' ?>">
                <i class="bi bi-cash-coin"></i> Ingresos
            </a>
        </li>
        <li class="nav-item">
            <a href="/gastos"
                class="nav-link text-white <?= ($_GET['url'] ?? '') === 'gastos' ? 'active' : '' ?>">
                <i class="bi bi-cash-stack"></i> Gastos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= ($_GET['url'] ?? '') === 'movimientos' ? 'active' : '' ?>" href="/movimientos">
                <i class="bi bi-layout-text-sidebar"></i> Movimientos
            </a>
        </li>
        <li class="nav-item mt-4">
            <a href="/auth/logout" class="nav-link text-danger"><i class="bi bi-box-arrow-in-right"></i> Cerrar sesión</a>
        </li>
    </ul>
</div>
