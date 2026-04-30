<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= ($mode ?? 'create') === 'edit' ? 'Editar sucursal' : 'Nueva sucursal' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $branchForm = is_array($old ?? null) && ! empty($old) ? $old : ($branch ?? []); ?>



        <div class="admin-card p-4 p-lg-5">

            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">

                <div>

                    <span class="admin-badge">Sucursales</span>

                    <h1 class="admin-title mt-4 mb-2 fw-bold"><?= ($mode ?? 'create') === 'edit' ? 'Editar sucursal.' : 'Crear sucursal.' ?></h1>

                    <p class="admin-copy mb-0">Completa los datos de la sucursal y conserva este formulario en una pantalla dedicada para trabajar con más espacio y menos ruido visual.</p>

                </div>

                <a class="btn admin-secondary-btn" href="<?= site_url('admin/branches') ?>">Volver al listado</a>

            </div>



            <?php if (! empty($status)): ?>

                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>

            <?php endif; ?>



            <div class="row justify-content-center mt-1">

                <div class="col-12 col-xl-8">

                    <div class="admin-placeholder p-4 mt-4">

                        <form method="post" action="<?= site_url('admin/branches/save') ?>">

                            <?= csrf_field() ?>

                            <input type="hidden" name="id" value="<?= esc((string) ($branchForm['id'] ?? '')) ?>">



                            <div class="row g-3">

                                <div class="col-12 col-lg-4">

                                    <label class="form-label fw-semibold" for="branch_codigo">Código</label>

                                    <input id="branch_codigo" name="codigo" type="text" class="form-control<?= ! empty($errors['codigo']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['codigo'] ?? '') ?>" required>

                                    <?php if (! empty($errors['codigo'])): ?><div class="invalid-feedback d-block"><?= esc($errors['codigo']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-8">

                                    <label class="form-label fw-semibold" for="branch_nombre">Nombre</label>

                                    <input id="branch_nombre" name="nombre" type="text" class="form-control<?= ! empty($errors['nombre']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['nombre'] ?? '') ?>" required>

                                    <?php if (! empty($errors['nombre'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-6">

                                    <label class="form-label fw-semibold" for="branch_ciudad">Ciudad</label>

                                    <input id="branch_ciudad" name="ciudad" type="text" class="form-control<?= ! empty($errors['ciudad']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['ciudad'] ?? '') ?>">

                                    <?php if (! empty($errors['ciudad'])): ?><div class="invalid-feedback d-block"><?= esc($errors['ciudad']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12 col-lg-6">

                                    <label class="form-label fw-semibold" for="branch_estado">Estado</label>

                                    <select id="branch_estado" name="estado" class="form-select<?= ! empty($errors['estado']) ? ' is-invalid' : '' ?>">

                                        <option value="activa" <?= ($branchForm['estado'] ?? 'activa') === 'activa' ? 'selected' : '' ?>>Activa</option>

                                        <option value="inactiva" <?= ($branchForm['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>

                                    </select>

                                    <?php if (! empty($errors['estado'])): ?><div class="invalid-feedback d-block"><?= esc($errors['estado']) ?></div><?php endif; ?>

                                </div>



                                <div class="col-12">

                                    <label class="form-label fw-semibold" for="branch_direccion">Dirección</label>

                                    <textarea id="branch_direccion" name="direccion" class="form-control<?= ! empty($errors['direccion']) ? ' is-invalid' : '' ?>" rows="4"><?= esc($branchForm['direccion'] ?? '') ?></textarea>

                                    <?php if (! empty($errors['direccion'])): ?><div class="invalid-feedback d-block"><?= esc($errors['direccion']) ?></div><?php endif; ?>

                                </div>

                            </div>



                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <button type="submit" class="btn admin-primary-btn text-white"><?= ($mode ?? 'create') === 'edit' ? 'Actualizar sucursal' : 'Crear sucursal' ?></button>

                                <a class="btn admin-secondary-btn" href="<?= site_url('admin/branches') ?>">Cancelar</a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
<?= $this->endSection() ?>
