<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table      = 'soldes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_attribues',
        'jours_pris',
    ];

    protected $validationRules = [
        'employe_id'      => 'required|integer',
        'type_conge_id'   => 'required|integer',
        'annee'           => 'required|integer',
        'jours_attribues' => 'required|decimal|greater_than_equal_to[0]',
        'jours_pris'      => 'required|decimal|greater_than_equal_to[0]',
    ];

    // ----------------------------------------------------------------
    // Lecture
    // ----------------------------------------------------------------

    /**
     * Solde d'un employé pour un type et une année donnés.
     * Retourne aussi jours_restants calculé côté PHP.
     */
    public function getSolde(int $employeId, int $typeCongeId, int $annee)
    {
        $solde = $this->where('employe_id', $employeId)
                      ->where('type_conge_id', $typeCongeId)
                      ->where('annee', $annee)
                      ->first();

        if ($solde) {
            $solde['jours_restants'] = $solde['jours_attribues'] - $solde['jours_pris'];
        }

        return $solde;
    }

    /** Tous les soldes d'un employé pour une année avec détails type de congé */
    public function getSoldesEmploye(int $employeId, int $annee): array
    {
        $soldes = $this->select('soldes.*, types_conge.libelle AS type_libelle, types_conge.deductible')
                       ->join('types_conge', 'types_conge.id = soldes.type_conge_id', 'left')
                       ->where('soldes.employe_id', $employeId)
                       ->where('soldes.annee', $annee)
                       ->findAll();

        // Calcul côté PHP — jamais stocké en BDD
        foreach ($soldes as &$s) {
            $s['jours_restants'] = $s['jours_attribues'] - $s['jours_pris'];
        }

        return $soldes;
    }

    /** Tous les soldes avec infos employé et type (vue admin/RH) */
    public function getAllWithDetails(int $annee): array
    {
        $soldes = $this->select('soldes.*, employes.nom, employes.prenom, types_conge.libelle AS type_libelle')
                       ->join('employes',    'employes.id = soldes.employe_id',       'left')
                       ->join('types_conge', 'types_conge.id = soldes.type_conge_id', 'left')
                       ->where('soldes.annee', $annee)
                       ->orderBy('employes.nom', 'ASC')
                       ->findAll();

        foreach ($soldes as &$s) {
            $s['jours_restants'] = $s['jours_attribues'] - $s['jours_pris'];
        }

        return $soldes;
    }

    /**
     * Vérifie si le solde est suffisant avant approbation.
     * Retourne true si OK, false sinon.
     */
    public function isSuffisant(int $employeId, int $typeCongeId, int $annee, float $nbJours): bool
    {
        $solde = $this->getSolde($employeId, $typeCongeId, $annee);

        if (!$solde) {
            return false;
        }

        return ($solde['jours_restants'] >= $nbJours);
    }

    // ----------------------------------------------------------------
    // Écriture
    // ----------------------------------------------------------------

    public function initialiser(array $data)
    {
        $result = $this->insert($data);
        return $result ?: false;
    }

    public function ajuster(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    /** Déduire des jours (à l'approbation) */
    public function deduire(int $employeId, int $typeCongeId, int $annee, float $nbJours): bool
    {
        return $this->set('jours_pris', "jours_pris + {$nbJours}", false)
                    ->where('employe_id', $employeId)
                    ->where('type_conge_id', $typeCongeId)
                    ->where('annee', $annee)
                    ->update();
    }

    /** Recréditer des jours (annulation ou refus après approbation) */
    public function recrediter(int $employeId, int $typeCongeId, int $annee, float $nbJours): bool
    {
        return $this->set('jours_pris', "MAX(0, jours_pris - {$nbJours})", false)
                    ->where('employe_id', $employeId)
                    ->where('type_conge_id', $typeCongeId)
                    ->where('annee', $annee)
                    ->update();
    }

    /** Initialiser les soldes annuels pour tous les employés (appel admin) */
    public function initAnnee(int $annee): void
    {
        $employes    = model('EmployeModel')->getActifs();
        $typesConge  = model('TypeCongeModel')->getDeductibles();

        foreach ($employes as $emp) {
            foreach ($typesConge as $type) {
                $existe = $this->where('employe_id', $emp['id'])
                               ->where('type_conge_id', $type['id'])
                               ->where('annee', $annee)
                               ->first();

                if (!$existe) {
                    $this->insert([
                        'employe_id'      => $emp['id'],
                        'type_conge_id'   => $type['id'],
                        'annee'           => $annee,
                        'jours_attribues' => $type['jours_annuels'],
                        'jours_pris'      => 0,
                    ]);
                }
            }
        }
    }
}
