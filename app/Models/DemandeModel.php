<?php

namespace App\Models;

use CodeIgniter\Model;

class DemandeModel extends Model
{
    protected $table = 'demande';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'employe_id',
        'type_id',
        'statut_id',
        'date_debut',
        'date_fin',
        'motif'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 🔍 récupérer toutes les demandes avec jointures
    public function getDemandesWithDetails()
    {
        return $this->select('demande.*, employes.nom, employes.prenom, statut.libelle as statut')
            ->join('employes', 'employes.id = demande.employe_id')
            ->join('statut', 'statut.id = demande.statut_id')
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
        return $this->update($id, ['statut_id' => $statut_id]);
    }

    // ❌ supprimer
    public function deleteDemande($id)
    {
        return $this->delete($id);
    }
}