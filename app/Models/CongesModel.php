<?php

namespace App\Models;

use CodeIgniter\Model;

class CongesModel extends Model
{
    protected $table      = 'conges';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

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
        'created_at',
    ];

    protected $useTimestamps = false;
}
