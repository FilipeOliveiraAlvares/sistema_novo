<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\SpotProdutoModel;
use App\Models\SpotServicoModel;

class Spot extends BaseController
{
    /**
     * Página pública de um spot (uma página por cliente).
     *
     * URL: /spot/{slug-do-cliente}
     */
    public function view(string $slug)
    {
        $spotModel = new SpotModel();
        $spot      = $spotModel
            ->where('slug', $slug)
            ->where('ativo', 1)
            ->first();

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Página do spot não encontrada');
        }

        $cidades = json_decode($spot['cidades_atendidas'] ?? '[]', true) ?: [];
        $cidadePrincipal = $cidades[0] ?? null;

        // Itens em destaque para a landing (até 3 de cada, leves)
        $servicoModel       = new SpotServicoModel();
        $produtoModel       = new SpotProdutoModel();
        $servicosDestaque   = $servicoModel
            ->where('spot_id', $spot['id'])
            ->where('ativo', 1)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->limit(3)
            ->find();
        $produtosDestaque   = $produtoModel
            ->where('spot_id', $spot['id'])
            ->where('ativo', 1)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->limit(3)
            ->find();

        // Geração de SEO com base em nome fantasia, serviço principal e cidade
        $nomeBase = $spot['nome_fantasia'] ?: $spot['nome'];

        $tituloSeo = $nomeBase;
        if (! empty($spot['servico_principal'])) {
            $tituloSeo .= ' - ' . $spot['servico_principal'];
        }

        // Cidade principal: prioriza cidade_sede, depois primeira cidade atendida
        $cidadeSeo = $spot['cidade_sede'] ?: ($cidadePrincipal['cidade'] ?? null);
        $ufSeo     = $spot['uf_sede'] ?: ($cidadePrincipal['estado'] ?? null);

        if ($cidadeSeo) {
            $tituloSeo .= ' em ' . $cidadeSeo;
            if ($ufSeo) {
                $tituloSeo .= ' - ' . $ufSeo;
            }
        }

        if (! empty($spot['categoria'])) {
            $tituloSeo .= ' | ' . $spot['categoria'];
        }

        // Monta uma descrição curta incluindo algumas cidades atendidas
        $nomesCidades = array_map(static function ($c) {
            return ($c['cidade'] ?? '') . (isset($c['estado']) ? ' - ' . $c['estado'] : '');
        }, $cidades);

        $listaCidadesTexto = '';
        if (! empty($nomesCidades)) {
            $limite = array_slice($nomesCidades, 0, 5);
            $listaCidadesTexto = implode(', ', $limite);
            if (count($nomesCidades) > 5) {
                $listaCidadesTexto .= ' e região';
            }
        }

        $descricaoSeo = $nomeBase;
        if (! empty($spot['servico_principal'])) {
            $descricaoSeo .= ' - ' . $spot['servico_principal'];
        } elseif (! empty($spot['categoria'])) {
            $descricaoSeo .= ' - ' . $spot['categoria'];
        }

        if ($cidadeSeo) {
            $descricaoSeo .= ' em ' . $cidadeSeo;
            if ($ufSeo) {
                $descricaoSeo .= ' - ' . $ufSeo;
            }
        }

        if ($listaCidadesTexto !== '') {
            $descricaoSeo .= ' atendendo também ' . $listaCidadesTexto;
        }

        $descricaoSeo .= '. Entre em contato para mais informações.';

        $data = [
            'spot'               => $spot,
            'cidades'            => $cidades,
            'titulo_seo'         => $tituloSeo,
            'descricao_seo'      => $descricaoSeo,
            'servicos_destaque'  => $servicosDestaque,
            'produtos_destaque'  => $produtosDestaque,
        ];

        return view('spot/pagina', $data);
    }

    /**
     * Página de serviços do spot.
     *
     * URL: /spot/{slug}/servicos
     */
    public function servicos(string $slug)
    {
        $spotModel = new SpotModel();
        $spot      = $spotModel
            ->where('slug', $slug)
            ->where('ativo', 1)
            ->first();

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Página de serviços não encontrada');
        }

        $cidades   = json_decode($spot['cidades_atendidas'] ?? '[]', true) ?: [];

        $servicoModel = new SpotServicoModel();
        $servicos = $servicoModel
            ->where('spot_id', $spot['id'])
            ->where('ativo', 1)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
        $cidadePrincipal = $cidades[0] ?? null;

        $nomeBase  = $spot['nome_fantasia'] ?: $spot['nome'];
        $cidadeSeo = $spot['cidade_sede'] ?: ($cidadePrincipal['cidade'] ?? null);
        $ufSeo     = $spot['uf_sede'] ?: ($cidadePrincipal['estado'] ?? null);

        $tituloSeo = 'Serviços de ' . $nomeBase;
        if (! empty($spot['servico_principal'])) {
            $tituloSeo .= ' - ' . $spot['servico_principal'];
        }
        if ($cidadeSeo) {
            $tituloSeo .= ' em ' . $cidadeSeo;
            if ($ufSeo) {
                $tituloSeo .= ' - ' . $ufSeo;
            }
        }

        $descricaoSeo = 'Conheça os serviços oferecidos por ' . $nomeBase;
        if ($cidadeSeo) {
            $descricaoSeo .= ' em ' . $cidadeSeo;
        }
        if (! empty($spot['categoria'])) {
            $descricaoSeo .= ' na área de ' . $spot['categoria'];
        }
        $descricaoSeo .= '.';

        $data = [
            'spot'          => $spot,
            'cidades'       => $cidades,
            'servicos'      => $servicos,
            'titulo_seo'    => $tituloSeo,
            'descricao_seo' => $descricaoSeo,
        ];

        return view('spot/servicos', $data);
    }

    /**
     * Página de produtos do spot.
     *
     * URL: /spot/{slug}/produtos
     */
    public function produtos(string $slug)
    {
        $spotModel = new SpotModel();
        $spot      = $spotModel
            ->where('slug', $slug)
            ->where('ativo', 1)
            ->first();

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Página de produtos não encontrada');
        }

        $cidades   = json_decode($spot['cidades_atendidas'] ?? '[]', true) ?: [];

        $produtoModel = new SpotProdutoModel();
        $produtos     = $produtoModel
            ->where('spot_id', $spot['id'])
            ->where('ativo', 1)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $cidadePrincipal = $cidades[0] ?? null;

        $nomeBase  = $spot['nome_fantasia'] ?: $spot['nome'];
        $cidadeSeo = $spot['cidade_sede'] ?: ($cidadePrincipal['cidade'] ?? null);
        $ufSeo     = $spot['uf_sede'] ?: ($cidadePrincipal['estado'] ?? null);

        $tituloSeo = 'Produtos de ' . $nomeBase;
        if ($cidadeSeo) {
            $tituloSeo .= ' em ' . $cidadeSeo;
            if ($ufSeo) {
                $tituloSeo .= ' - ' . $ufSeo;
            }
        }

        $descricaoSeo = 'Veja os produtos disponíveis em ' . $nomeBase;
        if ($cidadeSeo) {
            $descricaoSeo .= ' em ' . $cidadeSeo;
        }
        if (! empty($spot['categoria'])) {
            $descricaoSeo .= ' na área de ' . $spot['categoria'];
        }
        $descricaoSeo .= '.';

        $data = [
            'spot'          => $spot,
            'produtos'      => $produtos,
            'titulo_seo'    => $tituloSeo,
            'descricao_seo' => $descricaoSeo,
        ];

        return view('spot/produtos', $data);
    }
}

