<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\EmployesModel;

class EmployesController extends BaseController
{
    private EmployesModel $employeModel;


    public function __construct()
    {
        $this->employeModel = new EmployesModel();
    }

    public function loginView()
    {
        return view('auth/login');
    }
    public function loginPost() {}


    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
