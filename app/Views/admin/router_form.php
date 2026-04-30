<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= ($mode ?? 'create') === 'edit' ? 'Editar router' : 'Nuevo router' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $routerForm = is_array($old ?? null) && ! empty($old) ? $old : ($router ?? []); ?>



        <div class="admin-card p-4 p-lg-5">

            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">

                <div>

                    <span class="admin-badge">Routers</span>

                    <h1 class="admin-title mt-4 mb-2 fw-bold"><?= ($mode ?? 'create') === 'edit' ? 'Editar router.' : 'Crear router.' ?></h1>

                    <p class="admin-copy mb-0">Completa la configuración del equipo MikroTik, su sucursal y sus credenciales de integración desde una pantalla dedicada.</p>

                </div>

                <a class="btn admin-secondary-btn" href="<?= site_url('admin/routers') ?>">Volver al listado</a>

            </div>



            <?php if (! empty($status)): ?>

                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>

            <?php endif; ?>



            <div class="row justify-content-center mt-1">

                <div class="col-12 col-xl-9">

                    <div class="admin-placeholder p-4 mt-4">

                        <form method="post" action="<?= site_url('admin/routers/save') ?>">

                            <?= csrf_field() ?>

                            <input type="hidden" name="id" value="<?= esc((string) ($routerForm['id'] ?? '')) ?>">



                            <div class="row g-3">

                                <div class="col-12 col-lg-6">

                                    <label class="form-label fw-semibold" for="router_sucursal_id">Sucursal</label>

                                    <select id="router_sucursal_id" name="sucursal_id" class="form-select<?= ! empty($errors['sucursal_id']) ? ' is-invalid' : '' ?>" required>

                                        <option value="">Selecciona una sucursal</option>

                                        <?php foreach ($sucursales as $sucursal): ?>

                                            <option value="<?= esc((string) $sucursal['id']) ?>" <?= (string) ($routerForm['sucursal_id'] ?? '') === (string) $sucursal['id'] ? 'selected' : '' ?>>

                                                <?= esc($sucursal['nombre']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <?php if (! empty($errors['sucursal_id'])): ?><div class="invalid-feedback d-block"><?= esc($errors['sucursal_id']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-3">

                                    <label class="form-label fw-semibold" for="router_codigo">Código</label>

                                    <input id="router_codigo" name="codigo" type="text" class="form-control<?= ! empty($errors['codigo']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['codigo'] ?? '') ?>" required>

                                    <?php if (! empty($errors['codigo'])): ?><div class="invalid-feedback d-block"><?= esc($errors['codigo']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-3">

                                    <label class="form-label fw-semibold" for="router_estado">Estado</label>

                                    <select id="router_estado" name="estado" class="form-select<?= ! empty($errors['estado']) ? ' is-invalid' : '' ?>">

                                        <option value="activo" <?= ($routerForm['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>

                                        <option value="inactivo" <?= ($routerForm['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>

                                    </select>

                                    <?php if (! empty($errors['estado'])): ?><div class="invalid-feedback d-block"><?= esc($errors['estado']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12">

                                    <label class="form-label fw-semibold" for="router_nombre">Nombre del router</label>

                                    <input id="router_nombre" name="nombre_router" type="text" class="form-control<?= ! empty($errors['nombre_router']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['nombre_router'] ?? '') ?>" required>

                                    <?php if (! empty($errors['nombre_router'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre_router']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-6">

                                    <label class="form-label fw-semibold" for="router_host">Host / IP WireGuard</label>

                                    <input id="router_host" name="host" type="text" class="form-control<?= ! empty($errors['host']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['host'] ?? '') ?>" required>

                                    <?php if (! empty($errors['host'])): ?><div class="invalid-feedback d-block"><?= esc($errors['host']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-3">

                                    <label class="form-label fw-semibold" for="router_puerto">Puerto</label>

                                    <input id="router_puerto" name="puerto" type="number" class="form-control<?= ! empty($errors['puerto']) ? ' is-invalid' : '' ?>" value="<?= esc((string) ($routerForm['puerto'] ?? '443')) ?>" required>

                                    <?php if (! empty($errors['puerto'])): ?><div class="invalid-feedback d-block"><?= esc($errors['puerto']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-3">

                                    <label class="form-label fw-semibold" for="router_usuario">Usuario API</label>

                                    <input id="router_usuario" name="usuario" type="text" class="form-control<?= ! empty($errors['usuario']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['usuario'] ?? '') ?>" required>

                                    <?php if (! empty($errors['usuario'])): ?><div class="invalid-feedback d-block"><?= esc($errors['usuario']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12">

                                    <label class="form-label fw-semibold" for="router_password">Contraseña</label>

                                    <input id="router_password" name="password" type="text" class="form-control<?= ! empty($errors['password']) ? ' is-invalid' : '' ?>" value="" <?= empty($routerForm['id']) ? 'required' : '' ?>>

                                    <div class="form-text">En edición puedes dejarlo vacío para conservar la contraseña actual.</div>

                                    <?php if (! empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= esc($errors['password']) ?></div><?php endif; ?>

                                </div>

                            </div>



                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <button type="submit" class="btn admin-primary-btn text-white"><?= ($mode ?? 'create') === 'edit' ? 'Actualizar router' : 'Crear router' ?></button>

                                <a class="btn admin-secondary-btn" href="<?= site_url('admin/routers') ?>">Cancelar</a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
<?= $this->endSection() ?>
