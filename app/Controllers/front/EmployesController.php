<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\MesureModel;
use App\Models\RoleModel;
use App\Models\UtilisateurModel;
use App\Models\GenreModel;

class AuthController extends BaseController
{
    private EmployeModel $utilisateurModel;


    public function __construct()
    {
       
    }

    public function login()
    {
        return view('front/auth/login');
    }

   

   
}
