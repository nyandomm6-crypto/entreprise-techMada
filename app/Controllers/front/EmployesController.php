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
        return view('front/auth/login');
    }

    public function loginPost() {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $employe = $this->employeModel
                    ->where('email', $email)
                    ->first();

        if (!$employe) {
            return redirect()->back()->with('error', 'Email non trouvé');
        }

        if (!password_verify($password, $employe['password'])) {
            return redirect()->back()->with('error', 'Mot de passe incorrect');
        }

        session()->set([
            'employes_id' => $employe['id'],
            'role'        => $employe['role'],
            'isLoggedIn'  => true
        ]);

        switch ($employe['role']) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'rh':
                return redirect()->to('/rh/dashboard');
            default:
                return redirect()->to('/employes/dashboard');
        }


    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
