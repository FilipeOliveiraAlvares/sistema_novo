<?php

namespace App\Models;

use CodeIgniter\Model;

class SpotModel extends Model
{
    protected $table            = 'spots';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'vendedor_id',
        'nome',
        'razao_social',
        'nome_fantasia',
        'cpf_cnpj',
        'contrato',
        'data_contrato',
        'vigencia_contrato',
        'slug',
        'categoria',
        'ramo',
        'ramo_id',
        'cidade_id',
        'servico_principal',
        'descricao',
        'texto_empresa',
        'texto_servicos',
        'texto_diferenciais',
        'palavras_chave_principais',
        'telefone',
        'whatsapp',
        'instagram',
        'facebook',
        'site',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade_sede',
        'uf_sede',
        'dias_funcionamento',
        'horarios_funcionamento',
        'obs_extras',
        'imagens',
        'logo',
        'mapa_embed',
        'max_produtos',
        'max_servicos',
        'cidades_atendidas',
        'ativo',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}


