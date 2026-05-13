<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table      = 'conges';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useTimestamps = false; // created_at géré manuellement

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'created_at',
        'traite_par',
        'traite_at',
    ];

    protected $validationRules = [
        'employe_id'    => 'required|integer',
        'type_conge_id' => 'required|integer',
        'date_debut'    => 'required|valid_date[Y-m-d]',
        'date_fin'      => 'required|valid_date[Y-m-d]',
        'nb_jours'      => 'required|decimal|greater_than[0]',
        'motif'         => 'permit_empty|max_length[500]',
        'statut'        => 'required|in_list[en_attente,approuvee,refusee,annulee]',
    ];

    // ----------------------------------------------------------------
    // Lecture — employé
    // ----------------------------------------------------------------

    /** Toutes les demandes d'un employé avec détails */
    public function getByEmploye(int $employeId): array
    {
        return $this->select('conges.*, types_conge.libelle AS type_libelle')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.employe_id', $employeId)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /** Demandes d'un employé filtrées par type */
    public function getByEmployeAndType(int $employeId, int $typeCongeId): array
    {
        return $this->select('conges.*, types_conge.libelle AS type_libelle')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.employe_id', $employeId)
                    ->where('conges.type_conge_id', $typeCongeId)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /** Demandes annulables (en_attente) d'un employé */
    public function getAnnulables(int $employeId): array
    {
        return $this->where('employe_id', $employeId)
                    ->where('statut', 'en_attente')
                    ->findAll();
    }

    // ----------------------------------------------------------------
    // Lecture — RH / Admin
    // ----------------------------------------------------------------

    /** Toutes les demandes avec détails complets */
    public function getAllWithDetails(): array
    {
        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              employes.date_embauche AS date_embauche,
                              departements.nom AS departement_nom,
                              types_conge.libelle AS type_libelle,
                              rh.nom AS rh_nom, 
                              rh.prenom AS rh_prenom')
                    ->join('employes',    'employes.id = conges.employe_id',       'left')
                    ->join('departements','departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->join('employes rh', 'rh.id = conges.traite_par',             'left')
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    public function getByIdWithDetails(int $id)
    {
        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              departements.nom AS departement_nom,
                              types_conge.libelle AS type_libelle')
                    ->join('employes',    'employes.id = conges.employe_id',          'left')
                    ->join('departements','departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id',    'left')
                    ->where('conges.id', $id)
                    ->first();
    }

    /** Demandes en attente (pour le RH) */
    public function getEnAttente(): array
    {
        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              departements.nom AS departement_nom,
                              types_conge.libelle AS type_libelle')
                    ->join('employes',    'employes.id = conges.employe_id',          'left')
                    ->join('departements','departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id',    'left')
                    ->where('conges.statut', 'en_attente')
                    ->orderBy('conges.date_debut', 'ASC')
                    ->findAll();
    }

    /** Filtrer par statut */
    public function getByStatut(string $statut): array
    {
        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              types_conge.libelle AS type_libelle')
                    ->join('employes',    'employes.id = conges.employe_id',       'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.statut', $statut)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /** Filtrer par département */
    public function getByDepartement(int $departementId): array
    {
        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              types_conge.libelle AS type_libelle')
                    ->join('employes',    'employes.id = conges.employe_id',          'left')
                    ->join('departements','departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id',    'left')
                    ->where('employes.departement_id', $departementId)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /** Absences du mois en cours (dashboard admin) */
    public function getAbsencesMoisEnCours(): array
    {
        $debut = date('Y-m-01');
        $fin   = date('Y-m-t');

        return $this->select('conges.*, 
                              employes.nom AS employe_nom, 
                              employes.prenom AS employe_prenom,
                              departements.nom AS departement_nom,
                              types_conge.libelle AS type_libelle')
                    ->join('employes',    'employes.id = conges.employe_id',          'left')
                    ->join('departements','departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id',    'left')
                    ->where('conges.statut', 'approuvee')
                    ->where('conges.date_debut <=', $fin)
                    ->where('conges.date_fin >=', $debut)
                    ->orderBy('conges.date_debut', 'ASC')
                    ->findAll();
    }

    // ----------------------------------------------------------------
    // Validation métier
    // ----------------------------------------------------------------

    /**
     * Vérifie si l'employé a déjà une demande active sur la même période.
     * Retourne true si chevauchement détecté.
     */
    public function hasChevauchement(int $employeId, string $dateDebut, string $dateFin, ?int $excludeId = null): bool
    {
        $builder = $this->where('employe_id', $employeId)
                        ->whereIn('statut', ['en_attente', 'approuvee'])
                        ->where('date_debut <=', $dateFin)
                        ->where('date_fin >=', $dateDebut);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Calcule le nombre de jours calendaires entre deux dates (inclusif).
     * Simplification TD : tous les jours, pas uniquement les ouvrables.
     */
    public static function calculerNbJours(string $dateDebut, string $dateFin): int
    {
        $d1 = new \DateTime($dateDebut);
        $d2 = new \DateTime($dateFin);
        return (int) $d1->diff($d2)->days + 1;
    }

    // ----------------------------------------------------------------
    // Écriture
    // ----------------------------------------------------------------

    public function soumettre(array $data)
    {
        $data['statut']     = 'en_attente';
        $data['created_at'] = date('Y-m-d H:i:s');
        $result = $this->insert($data);
        return $result ?: false;
    }

    /**
     * Approuver une demande + déduire le solde.
     * Retourne true si OK, string (message erreur) si solde insuffisant.
     */
    public function approuver(int $id, int $rhId, string $commentaire = ''): bool|string
    {
        $conge = $this->getById($id);

        if (!$conge || $conge['statut'] !== 'en_attente') {
            return 'Demande introuvable ou déjà traitée.';
        }

        $annee     = (int) date('Y', strtotime($conge['date_debut']));
        $soldeModel = model('SoldeModel');

        if (!$soldeModel->isSuffisant($conge['employe_id'], $conge['type_conge_id'], $annee, $conge['nb_jours'])) {
            return 'Solde insuffisant pour approuver cette demande.';
        }

        // Déduire le solde
        $soldeModel->deduire($conge['employe_id'], $conge['type_conge_id'], $annee, $conge['nb_jours']);

        // Mettre à jour la demande
        return parent::update($id, [
            'statut'         => 'approuvee',
            'commentaire_rh' => $commentaire,
            'traite_par'     => $rhId,
            'traite_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Refuser une demande.
     * Si elle était déjà approuvée, recréditer le solde.
     */
    public function refuser(int $id, int $rhId, string $commentaire = ''): bool
    {
        $conge = $this->getById($id);

        if (!$conge) {
            return false;
        }

        // Si déjà approuvée → recréditer
        if ($conge['statut'] === 'approuvee') {
            $annee = (int) date('Y', strtotime($conge['date_debut']));
            model('SoldeModel')->recrediter(
                $conge['employe_id'],
                $conge['type_conge_id'],
                $annee,
                $conge['nb_jours']
            );
        }

        return parent::update($id, [
            'statut'         => 'refusee',
            'commentaire_rh' => $commentaire,
            'traite_par'     => $rhId,
            'traite_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Annuler une demande (par l'employé, uniquement si en_attente).
     */
    public function annuler(int $id, int $employeId): bool
    {
        $conge = $this->where('id', $id)
                      ->where('employe_id', $employeId)
                      ->where('statut', 'en_attente')
                      ->first();

        if (!$conge) {
            return false;
        }

        return parent::update($id, ['statut' => 'annulee']);
    }

    public function modifier(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    public function supprimer(int $id): bool
    {
        return parent::delete($id);
    }
}
