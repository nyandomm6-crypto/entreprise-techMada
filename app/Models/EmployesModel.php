<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployesModel extends Model
{
    protected $table      = 'employes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif',
    ];

    protected $validationRules = [
        'nom'            => 'required|max_length[100]',
        'prenom'         => 'required|max_length[100]',
        'email'          => 'required|valid_email|max_length[150]',
        'password'       => 'required|min_length[8]',
        'role'           => 'required|in_list[employe,rh,admin]',
        'departement_id' => 'permit_empty|integer',
        'date_embauche'  => 'permit_empty|valid_date[Y-m-d]',
    ];

    // ----------------------------------------------------------------
    // Lecture
    // ----------------------------------------------------------------

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    public function getByEmail(string $email)
    {
        return $this->asArray()->where('email', $email)->first();
    }

    /** Tous les employés avec leur département */
    public function getAllWithDetails(): array
    {
        return $this->select('employes.*, departements.nom AS departement_nom')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->orderBy('employes.nom', 'ASC')
                    ->findAll();
    }

    public function getByIdWithDetails(int $id)
    {
        return $this->select('employes.*, departements.nom AS departement_nom')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->where('employes.id', $id)
                    ->first();
    }

    /** Employés actifs uniquement */
    public function getActifs(): array
    {
        return $this->where('actif', 1)->orderBy('nom', 'ASC')->findAll();
    }

    /** Employés par rôle */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role)->where('actif', 1)->findAll();
    }

    /** Employés d'un département */
    public function getByDepartement(int $departementId): array
    {
        return $this->where('departement_id', $departementId)
                    ->where('actif', 1)
                    ->orderBy('nom', 'ASC')
                    ->findAll();
    }

    public function countByRole(string $role): int
    {
        return $this->where('role', $role)->countAllResults();
    }

    // ----------------------------------------------------------------
    // Écriture
    // ----------------------------------------------------------------

    public function creer(array $data)
    {
        $result = $this->insert($data);
        return $result ?: false;
    }

    public function modifier(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    /** Désactiver (soft delete) */
    public function desactiver(int $id): bool
    {
        return parent::update($id, ['actif' => 0]);
    }

    /** Réactiver */
    public function activer(int $id): bool
    {
        return parent::update($id, ['actif' => 1]);
    }

    public function supprimer(int $id): bool
    {
        return parent::delete($id);
    }
}
