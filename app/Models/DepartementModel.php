<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table      = 'departements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'description',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
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

    public function getByNom(string $nom)
    {
        return $this->asArray()->where('nom', $nom)->first();
    }

    /** Departements avec le nombre d'employés rattachés */
    public function getAllWithCount(): array
    {
        return $this->select('departements.*, COUNT(employes.id) AS nb_employes')
                    ->join('employes', 'employes.departement_id = departements.id', 'left')
                    ->groupBy('departements.id')
                    ->orderBy('departements.nom', 'ASC')
                    ->findAll();
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

    public function supprimer(int $id): bool
    {
        return parent::delete($id);
    }
}
