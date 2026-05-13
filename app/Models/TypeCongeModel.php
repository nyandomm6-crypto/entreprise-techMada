<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table      = 'types_conge';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'libelle',
        'jours_annuels',
        'deductible',
    ];

    protected $validationRules = [
        'libelle'       => 'required|max_length[100]',
        'jours_annuels' => 'required|integer|greater_than_equal_to[0]',
        'deductible'    => 'required|in_list[0,1]',
    ];

    // ----------------------------------------------------------------
    // Lecture
    // ----------------------------------------------------------------

    public function getAll(): array
    {
        return $this->orderBy('libelle', 'ASC')->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    /** Uniquement les types qui déduisent du solde */
    public function getDeductibles(): array
    {
        return $this->where('deductible', 1)->findAll();
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
