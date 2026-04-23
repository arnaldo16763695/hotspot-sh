<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\EventoAccesoModel;
use App\Models\SesionHotspotModel;
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

    public function show(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $auth = session()->get('admin_auth') ?? [];
        $customerModel = new ClienteModel();
        $sessionModel = new SesionHotspotModel();
        $eventModel = new EventoAccesoModel();

        $cliente = $customerModel->findForAdminDetail($id);

        if ($cliente === null) {
            return redirect()->to(site_url('admin/customers'))
                ->with('status', 'No encontramos el cliente solicitado.');
        }

        return view('admin/customer_show', [
            'auth' => is_array($auth) ? $auth : [],
            'currentPage' => 'customers',
            'status' => session()->getFlashdata('status'),
            'cliente' => $cliente,
            'sesiones' => $sessionModel->findByClienteForAdmin($id),
            'eventos' => $eventModel->findByClienteForAdmin($id),
        ]);
    }
}
