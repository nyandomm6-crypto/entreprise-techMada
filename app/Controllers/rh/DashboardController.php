<?php

namespace App\Controllers\rh;

use App\Controllers\BaseController;
use App\Models\DemandeModel;
use App\Models\StatutModel;
use App\Models\EmployesModel;

class DashboardController extends BaseController
{
    private StatutModel $statutModel;
    private DemandeModel $demandeModel;
    private EmployesModel $employeModel;

    public function __construct()
    {
        $this->statutModel  = new StatutModel();
        $this->demandeModel = new DemandeModel();
        $this->employeModel = new EmployesModel();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();

        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // récupérer employé connecté
        $employe = $this->employeModel->find($utilisateurId);

        // récupérer toutes les demandes avec jointures
        $demandes = $this->demandeModel->getDemandesWithDetails();

        $data = [
            'employe'  => $employe,
            'demandes' => $demandes
        ];

        return view('rh/listDemande', $data);
    }
}