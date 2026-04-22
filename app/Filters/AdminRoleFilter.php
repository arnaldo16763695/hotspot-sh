<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminRoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = session()->get('admin_auth');
        $currentRole = (string) ($auth['role_code'] ?? '');
        $allowedRoles = is_array($arguments) ? $arguments : [];

        if ($currentRole === '' || $allowedRoles === []) {
            return redirect()->to(site_url('admin'))
                ->with('status', 'No pudimos validar tus permisos para esta seccion.');
        }

        if (! in_array($currentRole, $allowedRoles, true)) {
            return redirect()->to(site_url('admin'))
                ->with('status', 'Tu usuario no tiene permisos para acceder a esta seccion.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
