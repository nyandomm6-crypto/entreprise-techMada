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

    public function getDemandesWithDetails()
    {
        return $this->select('
                demande.*,
                employes.nom,
                employes.nom AS employe_nom,
                employes.prenom,
                employes.prenom AS employe_prenom,
                employes.date_embauche,
                departements.nom AS departement,
                departements.nom AS departement_nom,
                types_conge.libelle AS type,
                types_conge.libelle AS type_libelle,
                statut.libelle AS statut
            ')
            ->join('employes', 'employes.id = demande.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = demande.type_id', 'left')
            ->join('statut', 'statut.id = demande.statut_id')
            ->orderBy('demande.created_at', 'DESC')
            ->findAll();
    }

    public function getByEmploye($employe_id)
    {
        return $this->where('employe_id', $employe_id)->findAll();
    }

    public function createDemande($data)
    {
        return $this->insert($data);
    }

    public function updateStatut($id, $statut_id)
    {
        return $this->update($id, ['statut_id' => $statut_id]);
    }

    public function deleteDemande($id)
    {
        return $this->delete($id);
    }

       public function creerDemande($employeId, $typeId, $dateDebut, $dateFin, $motif)
    {
        $data = [
            'employe_id'    => $employeId,
            'type_id'       => $typeId,
            'statut_id'     => 1, // en attente
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'motif'         => $motif
        ];

        return $this->insert($data);
    }
}
