<?php

namespace App\Controllers\employes;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{


    public function __construct()
    {
      
    }

    public function index()
    {
        return view('employe/dashboard');
    }

  
}
