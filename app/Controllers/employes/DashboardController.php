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



        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs correctement.');
        }

        // Préparer les données pour la création de la demande
        $nbJours = (int) floor((strtotime($dateFin) - strtotime($dateDebut)) / 86400) + 1;
        $data = [
            'employe_id'    => $employeId,
            'type_conge_id' => (int) $typeConge,
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'nb_jours'      => $nbJours,
            'motif'         => $commentaire,
            'statut'        => 'en_attente',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $this->demandeModel->createDemande($data);

        return redirect()->to('/employes/dashboard')->with('success', 'Votre demande de congé a été envoyée avec succès.');
    }
}
