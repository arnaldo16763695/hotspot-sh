<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminRoleModel;

class RolesController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($guard = $this->ensureAccess()) {
            return $guard;
        }

        $auth = session()->get('admin_auth') ?? [];
        $roleModel = new AdminRoleModel();

        return view('admin/roles', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'currentPage' => 'roles',
            'roles' => $roleModel->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    private function ensureAccess()
    {
        $auth = session()->get('admin_auth') ?? [];
        $role = (string) ($auth['role_code'] ?? '');

        if ($role !== 'super_admin') {
            return redirect()->to(site_url('admin'))
                ->with('status', 'Solo el super administrador puede consultar los roles administrativos.');
        }

        return null;
    }
}
