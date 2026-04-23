<?php
$auth = $auth ?? [];
$currentPage = $currentPage ?? '';
?>
<nav class="navbar navbar-expand-lg admin-navbar mb-4">
    <div class="container-fluid px-0">
        <a class="navbar-brand admin-navbar-brand" href="<?= site_url('admin') ?>">WiFi Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link admin-nav-link<?= $currentPage === 'dashboard' ? ' active' : '' ?>" href="<?= site_url('admin') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link<?= $currentPage === 'customers' ? ' active' : '' ?>" href="<?= site_url('admin/customers') ?>">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link<?= $currentPage === 'sessions' ? ' active' : '' ?>" href="<?= site_url('admin/sessions') ?>">Sesiones</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle admin-nav-link<?= in_array($currentPage, ['management', 'users', 'roles', 'branches', 'routers'], true) ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administracion
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('admin/users') ?>">Usuarios</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('admin/roles') ?>">Roles</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('admin/branches') ?>">Sucursales</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('admin/routers') ?>">Routers</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="<?= site_url('hotspot') ?>">Portal Hotspot</a>
                </li>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 gap-lg-3">
                <div class="small text-body-secondary">
                    <strong><?= esc($auth['nombre'] ?? 'Administrador') ?></strong>
                    <span class="d-block"><?= esc($auth['role_name'] ?? 'Sin rol') ?></span>
                </div>
                <a class="btn admin-primary-btn text-white" href="<?= site_url('admin/logout') ?>">Cerrar sesion</a>
            </div>
        </div>
    </div>
</nav>
