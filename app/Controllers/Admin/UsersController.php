<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminRoleModel;
use App\Models\AdminUserModel;
use CodeIgniter\I18n\Time;

class UsersController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $auth = session()->get('admin_auth') ?? [];
        $userModel = new AdminUserModel();
        $search = trim((string) $this->request->getGet('search'));

        return view('admin/users', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'currentPage' => 'users',
            'search' => $search,
            'usuarios' => $userModel->listForAdmin($search),
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

        $userModel = new AdminUserModel();
        $user = $userModel->find($id);

        if ($user === null) {
            return redirect()->to(site_url('admin/users'))
                ->with('status', 'No encontramos el usuario solicitado.');
        }

        return $this->renderForm('edit', $user);
    }

    public function save(): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $userId = (int) $this->request->getPost('id');
        $passwordRule = $userId > 0 ? 'permit_empty|min_length[8]|max_length[72]' : 'required|min_length[8]|max_length[72]';
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|max_length[190]',
            'role_id' => 'required|integer',
            'password' => $passwordRule,
            'estado' => 'required|in_list[activo,inactivo]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url($userId > 0 ? 'admin/users/edit/' . $userId : 'admin/users/create'))
                ->with('errors', $this->validator->getErrors())
                ->with('old', $this->request->getPost());
        }

        $userModel = new AdminUserModel();
        $existingUser = $userId > 0 ? $userModel->find($userId) : null;
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $duplicateEmail = $userModel->where('email', $email)
            ->where('id !=', $userId)
            ->first();

        if ($duplicateEmail !== null) {
            return redirect()->to(site_url($userId > 0 ? 'admin/users/edit/' . $userId : 'admin/users/create'))
                ->with('errors', ['email' => 'Ya existe un usuario administrador con ese correo.'])
                ->with('status', 'No pudimos guardar el usuario porque el correo ya esta en uso.')
                ->with('old', $this->request->getPost());
        }

        $now = Time::now()->toDateTimeString();
        $data = [
            'role_id' => (int) $this->request->getPost('role_id'),
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => $email,
            'estado' => (string) $this->request->getPost('estado'),
            'fecha_actualizacion' => $now,
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        } elseif ($existingUser !== null) {
            $data['password_hash'] = $existingUser['password_hash'];
        }

        try {
            if ($userId > 0) {
                $userModel->update($userId, $data);

                return redirect()->to(site_url('admin/users'))
                    ->with('status', 'Usuario administrador actualizado correctamente.');
            }

            $data['fecha_creacion'] = $now;
            $userModel->insert($data);

            return redirect()->to(site_url('admin/users'))
                ->with('status', 'Usuario administrador creado correctamente.');
        } catch (\Throwable $exception) {
            log_message('error', 'Admin users save failed: {error}', ['error' => $exception->getMessage()]);

            return redirect()->to(site_url($userId > 0 ? 'admin/users/edit/' . $userId : 'admin/users/create'))
                ->with('status', 'Ocurrio un error al guardar el usuario administrador.')
                ->with('old', $this->request->getPost());
        }
    }

    public function toggle(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $auth = session()->get('admin_auth') ?? [];
        if ((int) ($auth['id'] ?? 0) === $id) {
            return redirect()->to(site_url('admin/users'))
                ->with('status', 'No puedes cambiar el estado de tu propio usuario desde esta pantalla.');
        }

        $userModel = new AdminUserModel();
        $user = $userModel->find($id);

        if ($user === null) {
            return redirect()->to(site_url('admin/users'))
                ->with('status', 'No encontramos el usuario solicitado.');
        }

        $userModel->update($id, [
            'estado' => ($user['estado'] ?? 'activo') === 'activo' ? 'inactivo' : 'activo',
            'fecha_actualizacion' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to(site_url('admin/users'))
            ->with('status', 'Estado del usuario actualizado.');
    }

    private function ensureAccess()
    {
        $auth = session()->get('admin_auth') ?? [];
        $role = (string) ($auth['role_code'] ?? '');

        if ($role !== 'super_admin') {
            return redirect()->to(site_url('admin'))
                ->with('status', 'Solo el super administrador puede gestionar usuarios admin.');
        }

        return null;
    }

    private function renderForm(string $mode, ?array $user = null): string
    {
        $auth = session()->get('admin_auth') ?? [];
        $roleModel = new AdminRoleModel();

        return view('admin/user_form', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'errors' => session()->getFlashdata('errors') ?? [],
            'old' => session()->getFlashdata('old') ?? [],
            'roles' => $roleModel->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'user' => $user,
            'mode' => $mode,
            'currentPage' => 'users',
        ]);
    }
}
