<?php

namespace App\Models;

use CodeIgniter\Model;

class SpotCidadeModel extends Model
{
    protected $table            = 'spot_cidades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'spot_id',
        'cidade',
        'estado',
        'url_slug',
        'titulo_seo',
        'descricao_seo',
        'conteudo_html',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'spot_id' => 'required|integer',
        'cidade'  => 'required|min_length[2]|max_length[150]',
        'estado'  => 'required|min_length[2]|max_length[2]',
        'url_slug'=> 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}


