<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SucursalModel;
use CodeIgniter\I18n\Time;

class BranchesController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $auth = session()->get('admin_auth') ?? [];
        $branchModel = new SucursalModel();
        $search = trim((string) $this->request->getGet('search'));
        $editId = (int) $this->request->getGet('edit');

        return view('admin/branches', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'currentPage' => 'branches',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old' => session()->getFlashdata('old') ?? [],
            'search' => $search,
            'sucursales' => $branchModel->listForAdmin($search),
            'editBranch' => $editId > 0 ? $branchModel->find($editId) : null,
        ]);
    }

    public function save(): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $rules = [
            'codigo' => 'required|min_length[2]|max_length[30]',
            'nombre' => 'required|min_length[3]|max_length[150]',
            'ciudad' => 'permit_empty|max_length[100]',
            'direccion' => 'permit_empty|max_length[1000]',
            'estado' => 'required|in_list[activa,inactiva]',
        ];

        $id = (int) $this->request->getPost('id');

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/branches?edit=' . $id))
                ->with('errors', $this->validator->getErrors())
                ->with('old', $this->request->getPost());
        }

        $branchModel = new SucursalModel();
        $now = Time::now()->toDateTimeString();
        $codigo = strtoupper(trim((string) $this->request->getPost('codigo')));
        $nombre = trim((string) $this->request->getPost('nombre'));

        $duplicateCode = $branchModel->where('codigo', $codigo)
            ->where('id !=', $id)
            ->first();

        if ($duplicateCode !== null) {
            return redirect()->to(site_url('admin/branches' . ($id > 0 ? '?edit=' . $id : '')))
                ->with('errors', ['codigo' => 'Ya existe una sucursal con ese codigo.'])
                ->with('status', 'No pudimos guardar la sucursal porque el codigo ya esta en uso.')
                ->with('old', $this->request->getPost());
        }

        $duplicateName = $branchModel->where('nombre', $nombre)
            ->where('id !=', $id)
            ->first();

        if ($duplicateName !== null) {
            return redirect()->to(site_url('admin/branches' . ($id > 0 ? '?edit=' . $id : '')))
                ->with('errors', ['nombre' => 'Ya existe una sucursal con ese nombre.'])
                ->with('status', 'No pudimos guardar la sucursal porque el nombre ya esta registrado.')
                ->with('old', $this->request->getPost());
        }

        $data = [
            'codigo' => $codigo,
            'nombre' => $nombre,
            'ciudad' => trim((string) $this->request->getPost('ciudad')) ?: null,
            'direccion' => trim((string) $this->request->getPost('direccion')) ?: null,
            'estado' => (string) $this->request->getPost('estado'),
            'fecha_actualizacion' => $now,
        ];

        try {
            if ($id > 0) {
                $branchModel->update($id, $data);

                return redirect()->to(site_url('admin/branches'))
                    ->with('status', 'Sucursal actualizada correctamente.');
            }

            $data['fecha_registro'] = $now;
            $branchModel->insert($data);

            return redirect()->to(site_url('admin/branches'))
                ->with('status', 'Sucursal creada correctamente.');
        } catch (\Throwable $exception) {
            log_message('error', 'Admin branches save failed: {error}', ['error' => $exception->getMessage()]);

            return redirect()->to(site_url('admin/branches' . ($id > 0 ? '?edit=' . $id : '')))
                ->with('status', 'Ocurrio un error al guardar la sucursal. Intenta nuevamente.')
                ->with('old', $this->request->getPost());
        }
    }

    public function toggle(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $branchModel = new SucursalModel();
        $branch = $branchModel->find($id);

        if ($branch === null) {
            return redirect()->to(site_url('admin/branches'))
                ->with('status', 'No encontramos la sucursal solicitada.');
        }

        $branchModel->update($id, [
            'estado' => ($branch['estado'] ?? 'activa') === 'activa' ? 'inactiva' : 'activa',
            'fecha_actualizacion' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to(site_url('admin/branches'))
            ->with('status', 'Estado de la sucursal actualizado.');
    }

    private function ensureAccess()
    {
        $auth = session()->get('admin_auth') ?? [];
        $role = (string) ($auth['role_code'] ?? '');

        if (! in_array($role, ['super_admin', 'admin_operaciones'], true)) {
            return redirect()->to(site_url('admin'))
                ->with('status', 'Tu rol no tiene permisos para administrar sucursales.');
        }

        return null;
    }
}
