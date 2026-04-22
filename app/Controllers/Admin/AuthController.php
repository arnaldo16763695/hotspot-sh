<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($this->isAuthenticated()) {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/login', [
            'errors' => session()->getFlashdata('errors') ?? [],
            'status' => session()->getFlashdata('status'),
            'old' => session()->getFlashdata('old') ?? [],
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email|max_length[190]',
            'password' => 'required|min_length[6]|max_length[72]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('/'))
                ->with('errors', $this->validator->getErrors())
                ->with('old', ['email' => (string) $this->request->getPost('email')]);
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $userModel = new AdminUserModel();
        $user = $userModel->findActiveByEmail($email);

        if ($user === null || ! password_verify($password, (string) $user['password_hash'])) {
            return redirect()->to(site_url('/'))
                ->with('status', 'Correo o contrasena invalidos.')
                ->with('old', ['email' => $email]);
        }

        session()->set('admin_auth', [
            'id' => (int) $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'role_code' => $user['role_code'],
            'role_name' => $user['role_name'],
        ]);

        $userModel->update((int) $user['id'], [
            'ultimo_login_at' => Time::now()->toDateTimeString(),
            'fecha_actualizacion' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to(site_url('admin'));
    }

    public function logout()
    {
        session()->remove('admin_auth');

        return redirect()->to(site_url('/'))
            ->with('status', 'Sesion cerrada correctamente.');
    }

    private function isAuthenticated(): bool
    {
        $auth = session()->get('admin_auth');

        return is_array($auth) && ! empty($auth['id']);
    }
}
