<?php

namespace App\Controllers\rh;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\DemandeModel;
use App\Models\EmployesModel;
use App\Models\SoldeModel;

class DashboardController extends BaseController
{
    private CongeModel $congeModel;
    private DemandeModel $demandeModel;
    private EmployesModel $employeModel;
    private SoldeModel $soldeModel;

    public function __construct()
    {
        $this->congeModel   = new CongeModel();
        $this->demandeModel = new DemandeModel();
        $this->employeModel = new EmployesModel();
        $this->soldeModel   = new SoldeModel();
    }

    public function index()
    {
        $employeId = session()->get('employes_id');

        if ($employeId === null) {
            return redirect()->to('/login');
        }

        $employe = $this->employeModel->getByIdWithDetails((int) $employeId);
        $demandes = $this->getDemandesRh();
        // $demandes = $this->demandeModel->findAll();

        foreach ($demandes as &$demande) {
            $annee = (int) date('Y', strtotime($demande['date_debut']));
            $solde = $this->getSoldeDisponible(
                (int) $demande['employe_id'],
                (int) $demande['type_id'],
                $annee
            );

            $demande['solde_disponible'] = $solde['jours_restants'] ?? null;
            $demande['nb_jours'] = $this->calculerNbJours($demande['date_debut'], $demande['date_fin']);
        }
        unset($demande);

        $data = [
            'employe'  => $employe,
            'demandes' => $demandes
        ];

        // echo '<pre>';
        // print_r($data);
        // exit;

        return view('rh/listDemande', $data);
    }

    private function getDemandesRh(): array
    {
        if (db_connect()->tableExists('demande')) {
            return $this->demandeModel->getDemandesWithDetails();
        }

        $conges = $this->congeModel->getAllWithDetails();

        foreach ($conges as &$conge) {
            $conge['nom'] = $conge['employe_nom'] ?? null;
            $conge['prenom'] = $conge['employe_prenom'] ?? null;
            $conge['departement'] = $conge['departement_nom'] ?? null;
            $conge['type'] = $conge['type_libelle'] ?? null;
            $conge['type_id'] = $conge['type_conge_id'] ?? null;
        }
        unset($conge);

        return $conges;
    }

    private function getSoldeDisponible(int $employeId, int $typeId, int $annee): ?array
    {
        $solde = $this->soldeModel->getSolde($employeId, $typeId, $annee);

        if ($solde !== null) {
            return $solde;
        }

        $solde = $this->soldeModel->where('employe_id', $employeId)
            ->where('type_conge_id', $typeId)
            ->orderBy('annee', 'DESC')
            ->first();

        if ($solde === null) {
            return null;
        }

        $solde['jours_restants'] = $solde['jours_attribues'] - $solde['jours_pris'];

        return $solde;
    }

    private function calculerNbJours(string $dateDebut, string $dateFin): int
    {
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);

        return (int) $debut->diff($fin)->days + 1;
    }
}
