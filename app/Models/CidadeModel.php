<?php

namespace App\Models;

use CodeIgniter\Model;

class CidadeModel extends Model
{
    protected $table            = 'cidades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nome',
        'uf',
        'slug',
        'ativo',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[100]',
        'uf'   => 'required|min_length[2]|max_length[2]',
        'slug' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}

