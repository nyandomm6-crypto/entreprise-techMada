<?php

namespace App\Models;

use CodeIgniter\Model;

class DemandeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par',
        'traite_at',
        'created_at'
    ];

    protected $useTimestamps = false;

    // 🔍 récupérer toutes les demandes avec jointures
    public function getDemandesWithDetails()
    {
        return $this->select('conges.*, employes.nom, employes.prenom, employes.email, types_conge.libelle as type_conge')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->findAll();
    }

    // 🔍 demandes d’un employé
    public function getByEmploye($employe_id)
    {
        return $this->where('employe_id', $employe_id)->findAll();
    }

    // ➕ ajouter une demande
    public function createDemande($data)
    {
        return $this->insert($data);
    }

    // ✏️ modifier statut (valider/refuser)
    public function updateStatut($id, $statut_id)
    {
        return $this->update($id, ['statut' => $statut_id]);
    }

    public function deleteDemande($id)
    {
        return $this->delete($id);
    }

    public function creerDemande($employeId, $typeId, $dateDebut, $dateFin, $motif)
    {
        $data = [
            'employe_id'    => $employeId,
            'type_conge_id' => $typeId,
            'statut'        => 'en_attente',
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'motif'         => $motif,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }
}