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

        // echo "EMAIL: " . $this->request->getPost('email') . "<br>";
        // echo "PASSWORD: " . $this->request->getPost('password') . "<br>";
        // exit;

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez fournir votre email et mot de passe.');
        }

        $employe = $this->employeModel->getByEmail((string) $email);

        if (!password_verify($password, $employe['password'])) {
            return redirect()->to('/login')->with('error', 'Mot de passe incorrect');
        }

        // echo "<pre>";
        // echo "PASSWORD SAISI: " . $password . "\n";
        // echo "HASH EN BASE: " . $employe['password'] . "\n";

        // var_dump(password_verify($password, $employe['password']));
        // exit;

        // echo "<pre>";
        // print_r($employe);
        // exit;

        if (!$employe) {
            return redirect()->to('/login')->with('error', 'non');
        }
        if (!password_verify($password, $employe['password'])) {
            return redirect()->to('/login')->with('error', 'non');
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
