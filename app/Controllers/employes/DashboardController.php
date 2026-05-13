<?php

namespace App\Controllers\employes;

use App\Controllers\BaseController;
use App\Models\DemandeModel;

class DashboardController extends BaseController
{

    private DemandeModel $demandeModel;
    public function __construct()
    {
        $this->demandeModel = new DemandeModel();
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
            $typeId      = $this->request->getPost('type_id');
        $dateDebut   = $this->request->getPost('date_debut');
        $dateFin     = $this->request->getPost('date_fin');
            $motif       = $this->request->getPost('motif');

        // Vérifier session
        $employeId = session()->get('employes_id');
        if (! $employeId) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter avant de soumettre une demande.');
        }

        // Validation basique
        $rules = [
            'type_id'    => 'required|integer',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin'   => 'required|valid_date[Y-m-d]',
        ];



        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs correctement.');
        }

        // Préparer les données pour la création de la demande (correspond à la table `demande`)
        $data = [
            'employe_id' => (int) $employeId,
            'type_id'    => (int) $typeId,
            'statut_id'  => 1, // en attente
            'date_debut' => $dateDebut,
            'date_fin'   => $dateFin,
            'motif'      => $motif,
        ];

        $this->demandeModel->createDemande($data);

        return redirect()->to('/employes/dashboard')->with('success', 'Votre demande de congé a été envoyée avec succès.');
    }
}
