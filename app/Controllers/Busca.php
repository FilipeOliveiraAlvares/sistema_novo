<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\CidadeModel;
use App\Models\RamoModel;

class Busca extends BaseController
{
    public function index()
    {
        $spotModel = new SpotModel();
        $cidadeModel = new CidadeModel();

        // Parâmetros de busca
        $q = trim($this->request->getGet('q') ?? ''); // texto livre
        $ramoId = $this->request->getGet('ramo_id') ?? '';
        $cidadeId = $this->request->getGet('cidade_id') ?? '';

        // Carrega todas as cidades para o filtro (com cache)
        $cache = \Config\Services::cache();
        $cacheKey = 'cidades_lista_ativa';
        $cidades = $cache->get($cacheKey);
        
        if ($cidades === null) {
            $cidades = $cidadeModel
                ->where('ativo', 1)
                ->orderBy('uf', 'ASC')
                ->orderBy('nome', 'ASC')
                ->findAll();
            // Cache por 1 hora (3600 segundos)
            $cache->save($cacheKey, $cidades, 3600);
        }

        // Agrupa cidades por UF para o select
        $cidadesPorUf = [];
        foreach ($cidades as $cidade) {
            $uf = $cidade['uf'] ?? '';
            if (! isset($cidadesPorUf[$uf])) {
                $cidadesPorUf[$uf] = [];
            }
            $cidadesPorUf[$uf][] = $cidade;
        }
        ksort($cidadesPorUf);

        // Busca os spots com filtros
        $spotModel->where('ativo', 1);

        // Filtro por texto livre (busca em nome, descrição, texto_empresa, texto_servicos)
        if ($q !== '') {
            $spotModel->groupStart()
                ->like('nome', $q)
                ->orLike('nome_fantasia', $q)
                ->orLike('descricao', $q)
                ->orLike('texto_empresa', $q)
                ->orLike('texto_servicos', $q)
                ->orLike('texto_diferenciais', $q)
                ->orLike('servico_principal', $q)
                ->groupEnd();
        }

        // Filtro por ramo
        if ($ramoId !== '') {
            $spotModel->where('ramo_id', (int) $ramoId);
        }

        // Filtro por cidade
        if ($cidadeId !== '') {
            $spotModel->where('cidade_id', (int) $cidadeId);
        }

        // Ordenação
        $spotModel->orderBy('nome', 'ASC');

        // Paginação: 20 resultados por página
        $perPage = 20;
        $spots = $spotModel->paginate($perPage, 'default');
        
        // O pager é automaticamente criado e disponível via $spotModel->pager
        $pager = $spotModel->pager;

        // Carrega nomes das cidades para exibir
        $cidadesMap = [];
        if (! empty($spots)) {
            $cidadesIds = array_filter(array_unique(array_column($spots, 'cidade_id')));
            if (! empty($cidadesIds)) {
                $db = \Config\Database::connect();
                $cidadesEncontradas = $db->table('cidades')
                    ->whereIn('id', $cidadesIds)
                    ->get()
                    ->getResultArray();
                foreach ($cidadesEncontradas as $c) {
                    $cidadesMap[(int) $c['id']] = $c;
                }
            }
        }

        // Lista de ramos para o filtro (da tabela ramos) - com cache
        $ramoModel = new RamoModel();
        $cacheKeyRamos = 'ramos_lista_ativa';
        $ramosList = $cache->get($cacheKeyRamos);
        
        if ($ramosList === null) {
            $ramosList = $ramoModel
                ->where('ativo', 1)
                ->orderBy('ordem', 'ASC')
                ->orderBy('nome', 'ASC')
                ->findAll();
            // Cache por 1 hora (3600 segundos)
            $cache->save($cacheKeyRamos, $ramosList, 3600);
        }

        // Carrega nomes dos ramos para exibir nos resultados
        $ramosMap = [];
        if (! empty($spots)) {
            $ramosIds = array_filter(array_unique(array_column($spots, 'ramo_id')));
            if (! empty($ramosIds)) {
                $db = \Config\Database::connect();
                $ramosEncontrados = $db->table('ramos')
                    ->whereIn('id', $ramosIds)
                    ->get()
                    ->getResultArray();
                foreach ($ramosEncontrados as $r) {
                    $ramosMap[(int) $r['id']] = $r;
                }
            }
        }

        $data = [
            'q'            => $q,
            'ramo_id'      => $ramoId,
            'cidade_id'    => $cidadeId,
            'spots'        => $spots,
            'cidades'      => $cidadesPorUf,
            'cidadesMap'   => $cidadesMap,
            'ramos'        => $ramosList,
            'ramosMap'     => $ramosMap,
            'total'        => $pager->getTotal(),
            'pager'        => $pager,
        ];

        return view('busca/index', $data);
    }
}

