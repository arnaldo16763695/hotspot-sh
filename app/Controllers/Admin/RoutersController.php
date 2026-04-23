<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MikrotikRouterModel;
use App\Models\SucursalModel;
use CodeIgniter\I18n\Time;

class RoutersController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $auth = session()->get('admin_auth') ?? [];
        $routerModel = new MikrotikRouterModel();
        $search = trim((string) $this->request->getGet('search'));

        return view('admin/routers', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'currentPage' => 'routers',
            'search' => $search,
            'routers' => $routerModel->listForAdmin($search),
        ]);
    }

    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        return $this->renderForm('create');
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $routerModel = new MikrotikRouterModel();
        $router = $routerModel->find($id);

        if ($router === null) {
            return redirect()->to(site_url('admin/routers'))
                ->with('status', 'No encontramos el router solicitado.');
        }

        return $this->renderForm('edit', $router);
    }

    public function save(): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $routerId = (int) $this->request->getPost('id');
        $passwordRule = $routerId > 0 ? 'permit_empty|max_length[255]' : 'required|max_length[255]';
        $rules = [
            'sucursal_id' => 'required|integer',
            'codigo' => 'required|min_length[2]|max_length[40]',
            'nombre_router' => 'required|min_length[3]|max_length[120]',
            'host' => 'required|max_length[150]',
            'puerto' => 'required|integer|greater_than[0]|less_than_equal_to[65535]',
            'usuario' => 'required|max_length[100]',
            'password' => $passwordRule,
            'estado' => 'required|in_list[activo,inactivo]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url($routerId > 0 ? 'admin/routers/edit/' . $routerId : 'admin/routers/create'))
                ->with('errors', $this->validator->getErrors())
                ->with('old', $this->request->getPost());
        }

        $routerModel = new MikrotikRouterModel();
        $existingRouter = $routerId > 0 ? $routerModel->find($routerId) : null;
        $now = Time::now()->toDateTimeString();
        $codigo = strtoupper(trim((string) $this->request->getPost('codigo')));
        $nombreRouter = trim((string) $this->request->getPost('nombre_router'));
        $host = trim((string) $this->request->getPost('host'));

        $duplicateCode = $routerModel->where('codigo', $codigo)
            ->where('id !=', $routerId)
            ->first();

        if ($duplicateCode !== null) {
            return redirect()->to(site_url($routerId > 0 ? 'admin/routers/edit/' . $routerId : 'admin/routers/create'))
                ->with('errors', ['codigo' => 'Ya existe un router con ese codigo.'])
                ->with('status', 'No pudimos guardar el router porque el codigo ya esta en uso.')
                ->with('old', $this->request->getPost());
        }

        $duplicateName = $routerModel->where('nombre_router', $nombreRouter)
            ->where('id !=', $routerId)
            ->first();

        if ($duplicateName !== null) {
            return redirect()->to(site_url($routerId > 0 ? 'admin/routers/edit/' . $routerId : 'admin/routers/create'))
                ->with('errors', ['nombre_router' => 'Ya existe un router con ese nombre.'])
                ->with('status', 'No pudimos guardar el router porque el nombre ya esta registrado.')
                ->with('old', $this->request->getPost());
        }

        $duplicateHost = $routerModel->where('host', $host)
            ->where('id !=', $routerId)
            ->first();

        if ($duplicateHost !== null) {
            return redirect()->to(site_url($routerId > 0 ? 'admin/routers/edit/' . $routerId : 'admin/routers/create'))
                ->with('errors', ['host' => 'Ya existe un router configurado con ese host.'])
                ->with('status', 'No pudimos guardar el router porque el host ya esta registrado.')
                ->with('old', $this->request->getPost());
        }

        $data = [
            'sucursal_id' => (int) $this->request->getPost('sucursal_id'),
            'codigo' => $codigo,
            'nombre_router' => $nombreRouter,
            'host' => $host,
            'puerto' => (int) $this->request->getPost('puerto'),
            'usuario' => trim((string) $this->request->getPost('usuario')),
            'estado' => (string) $this->request->getPost('estado'),
            'fecha_actualizacion' => $now,
        ];

        $password = trim((string) $this->request->getPost('password'));
        $data['password'] = $password !== '' ? $password : ($existingRouter['password'] ?? null);

        try {
            if ($routerId > 0) {
                $routerModel->update($routerId, $data);

                return redirect()->to(site_url('admin/routers'))
                    ->with('status', 'Router actualizado correctamente.');
            }

            $data['fecha_registro'] = $now;
            $routerModel->insert($data);

            return redirect()->to(site_url('admin/routers'))
                ->with('status', 'Router creado correctamente.');
        } catch (\Throwable $exception) {
            log_message('error', 'Admin routers save failed: {error}', ['error' => $exception->getMessage()]);

            return redirect()->to(site_url($routerId > 0 ? 'admin/routers/edit/' . $routerId : 'admin/routers/create'))
                ->with('status', 'Ocurrio un error al guardar el router. Intenta nuevamente.')
                ->with('old', $this->request->getPost());
        }
    }

    public function toggle(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $routerModel = new MikrotikRouterModel();
        $router = $routerModel->find($id);

        if ($router === null) {
            return redirect()->to(site_url('admin/routers'))
                ->with('status', 'No encontramos el router solicitado.');
        }

        $routerModel->update($id, [
            'estado' => ($router['estado'] ?? 'activo') === 'activo' ? 'inactivo' : 'activo',
            'fecha_actualizacion' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to(site_url('admin/routers'))
            ->with('status', 'Estado del router actualizado.');
    }

    private function ensureAccess()
    {
        $auth = session()->get('admin_auth') ?? [];
        $role = (string) ($auth['role_code'] ?? '');

        if (! in_array($role, ['super_admin', 'admin_operaciones'], true)) {
            return redirect()->to(site_url('admin'))
                ->with('status', 'Tu rol no tiene permisos para administrar routers.');
        }

        return null;
    }

    private function renderForm(string $mode, ?array $router = null): string
    {
        $auth = session()->get('admin_auth') ?? [];
        $branchModel = new SucursalModel();

        return view('admin/router_form', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'errors' => session()->getFlashdata('errors') ?? [],
            'old' => session()->getFlashdata('old') ?? [],
            'router' => $router,
            'mode' => $mode,
            'sucursales' => $branchModel->listForAdmin(),
            'currentPage' => 'routers',
        ]);
    }
}
