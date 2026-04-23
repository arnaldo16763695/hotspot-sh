<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SesionHotspotModel;
use App\Models\SucursalModel;

class SessionsController extends BaseController
{
    public function index(): string
    {
        $auth = session()->get('admin_auth') ?? [];
        $filters = [
            'search' => trim((string) $this->request->getGet('search')),
            'autorizado' => (string) $this->request->getGet('autorizado'),
            'sucursal_id' => trim((string) $this->request->getGet('sucursal_id')),
        ];

        $sessionModel = new SesionHotspotModel();
        $branchModel = new SucursalModel();
        $sesiones = $sessionModel->paginateForAdmin($filters, 15);

        return view('admin/sessions', [
            'auth' => is_array($auth) ? $auth : [],
            'currentPage' => 'sessions',
            'status' => session()->getFlashdata('status'),
            'sesiones' => $sesiones,
            'pager' => $sessionModel->pager,
            'filters' => $filters,
            'sucursales' => $branchModel->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }
}
