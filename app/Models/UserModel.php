<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nome',
        'email',
        'senha_hash',
        'perfil',
        'ativo',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome'  => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|max_length[255]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}


