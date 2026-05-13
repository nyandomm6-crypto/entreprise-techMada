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

    public function loginPost()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // validate input
        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez fournir votre email et mot de passe.');
        }

        $employe = $this->employeModel->getByEmail((string) $email);
        if (!$employe) {
            return redirect()->to('/login')->with('error', 'non');
        }
        if (!password_verify($password, $employe['password'])) {
            return redirect()->to('/login')->with('error', 'non');
        }
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
