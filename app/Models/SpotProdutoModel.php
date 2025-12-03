<?php

namespace App\Models;

use CodeIgniter\Model;

class SpotProdutoModel extends Model
{
    protected $table            = 'spot_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'spot_id',
        'nome',
        'slug',
        'descricao_curta',
        'descricao_longa',
        'imagens',
        'preco',
        'ordem',
        'ativo',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'spot_id' => 'required|integer',
        'nome'    => 'required|min_length[2]|max_length[255]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}


