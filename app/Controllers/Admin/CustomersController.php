<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\SucursalModel;

class CustomersController extends BaseController
{
    public function index(): string
    {
        $auth = session()->get('admin_auth') ?? [];
        $filters = [
            'search' => trim((string) $this->request->getGet('search')),
            'estado' => trim((string) $this->request->getGet('estado')),
            'sucursal_id' => trim((string) $this->request->getGet('sucursal_id')),
        ];

        $customerModel = new ClienteModel();
        $branchModel = new SucursalModel();

        $clientes = $customerModel->paginateForAdmin($filters, 15);

        return view('admin/customers', [
            'auth' => is_array($auth) ? $auth : [],
            'currentPage' => 'customers',
            'status' => session()->getFlashdata('status'),
            'clientes' => $clientes,
            'pager' => $customerModel->pager,
            'filters' => $filters,
            'sucursales' => $branchModel->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }
}
