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

    public function demande()
    {
        return view('employe/demande');
    }

    public function envoyerDemande()
    {
        // Récupérer les données du formulaire
        $typeConge   = $this->request->getPost('type_conge');
        $dateDebut   = $this->request->getPost('date_debut');
        $dateFin     = $this->request->getPost('date_fin');
        $commentaire = $this->request->getPost('commentaire');

        // Vérifier session
        $employeId = session()->get('employes_id');
        if (! $employeId) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter avant de soumettre une demande.');
        }

        // Validation basique
        $rules = [
            'type_conge' => 'required|integer',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin'   => 'required|valid_date[Y-m-d]',
        ];

    

      //  return redirect()->to('/employes/mes-conges')->with('success', 'Votre demande de congé a été envoyée avec succès.');
    }

  
}
