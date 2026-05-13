<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\EmployeModel;

class AuthController extends BaseController
{
    private EmployeModel $utilisateurModel;


    public function __construct()
    {
        $this->utilisateurModel = new EmployeModel();
    }

    public function loginView()
    {
        return view('front/auth/login');
    }
    public function loginPost() {}



    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
