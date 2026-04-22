<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ManagementController extends BaseController
{
    public function index(): string
    {
        $auth = session()->get('admin_auth') ?? [];

        return view('admin/management', [
            'auth' => is_array($auth) ? $auth : [],
            'status' => session()->getFlashdata('status'),
            'currentPage' => 'management',
        ]);
    }
}
