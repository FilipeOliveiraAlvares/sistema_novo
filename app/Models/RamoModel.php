<?php

namespace App\Models;

use CodeIgniter\Model;

class RamoModel extends Model
{
    protected $table            = 'ramos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nome',
        'slug',
        'ativo',
        'ordem',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}

