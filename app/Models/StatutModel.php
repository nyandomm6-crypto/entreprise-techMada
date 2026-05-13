<?php

namespace App\Models;

use CodeIgniter\Model;

class StatutModel extends Model
{
    protected $table = 'statut';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'libelle'
    ];

    public $useTimestamps = false;

    public function getAllStatuts()
    {
        return $this->findAll();
    }

    public function getStatut($id)
    {
        return $this->find($id);
    }

    public function getByCode($code)
    {
        return $this->where('code', $code)->first();
    }
}