<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $auth = session()->get('admin_auth') ?? [];

        return view('admin/dashboard', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
        ]);
    }
}
