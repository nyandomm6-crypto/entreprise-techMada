<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\EmployesModel;

class EmployesFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $employesId = session()->get('employes_id');

        if (!$employesId) {
            return redirect()->to('/login');
        }

        $employesModel = new EmployesModel();
        $employes      = $employesModel->getById((int) $employesId);

        if ($employes === null) {
            session()->destroy();
            return redirect()->to('/login');
        }

        $role = (int) ($employes['role'] ?? 0);

        if ($role == "employe") {
            return redirect()->to('employes/dashboard');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // rien
    }
}
