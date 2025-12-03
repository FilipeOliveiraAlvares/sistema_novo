<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Spots extends BaseController
{
    protected SpotModel $spotModel;

    public function __construct()
    {
        $this->spotModel = new SpotModel();
    }

    public function index()
    {
        $data['spots'] = $this->spotModel->orderBy('id', 'DESC')->findAll();

        return view('admin/spots/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod(true) === 'POST') {
            return $this->saveSpot();
        }

        $data['spot'] = null;

        return view('admin/spots/form', $data);
    }

    public function edit(int $id)
    {
        $spot = $this->spotModel->find($id);

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            return $this->saveSpot($id);
        }

        $data['spot'] = $spot;

        return view('admin/spots/form', $data);
    }

    public function delete(int $id)
    {
        $this->spotModel->delete($id);

        return redirect()->to('/admin/spots')->with('message', 'Spot removido com sucesso.');
    }

    /**
     * Salva o spot (create/update).
     * A lista de cidades é armazenada em JSON no próprio registro do spot
     * e será usada apenas para compor o conteúdo e a área de atendimento.
     */
    protected function saveSpot(?int $id = null)
    {
        $post = $this->request->getPost();

        $cidadesRaw   = $post['cidades_atendidas'] ?? '';
        $cidades      = $this->parseCidades($cidadesRaw);
        $servicosRaw  = $post['servicos_lista_bruto'] ?? '';
        $servicosLista = $this->parseServicos($servicosRaw);

        $slug = $post['slug'] ?: url_title($post['nome'], '-', true);

        // Tratamento do upload de logo
        $logoPath = $post['logo_atual'] ?? null;
        $logoFile = $this->request->getFile('logo');

        if ($logoFile && $logoFile->isValid() && ! $logoFile->hasMoved()) {
            // Salva o logo dentro da pasta public/uploads/logos
            $uploadDir = FCPATH . 'uploads/logos';

            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $newName = $logoFile->getRandomName();
            $logoFile->move($uploadDir, $newName);

            // Caminho relativo a partir da raiz pública
            $logoPath = 'uploads/logos/' . $newName;
        }

        $spotData = [
            'nome'                      => $post['nome'] ?? '',
            'razao_social'              => $post['razao_social'] ?? null,
            'nome_fantasia'             => $post['nome_fantasia'] ?? null,
            'cpf_cnpj'                  => $post['cpf_cnpj'] ?? null,
            'contrato'                  => $post['contrato'] ?? null,
            'data_contrato'             => $post['data_contrato'] ?? null,
            'vigencia_contrato'         => $post['vigencia_contrato'] ?? null,
            'slug'                      => $slug,
            'categoria'                 => $post['categoria'] ?? null,
            'servico_principal'         => $post['servico_principal'] ?? null,
            'descricao'                 => $post['descricao'] ?? null,
            'texto_empresa'             => $post['texto_empresa'] ?? null,
            'texto_servicos'            => $post['texto_servicos'] ?? null,
            'texto_diferenciais'        => $post['texto_diferenciais'] ?? null,
            'palavras_chave_principais' => $post['palavras_chave_principais'] ?? null,
            'telefone'                  => $post['telefone'] ?? null,
            'whatsapp'                  => $post['whatsapp'] ?? null,
            'instagram'                 => $post['instagram'] ?? null,
            'facebook'                  => $post['facebook'] ?? null,
            'site'                      => $post['site'] ?? null,
            'cep'                       => $post['cep'] ?? null,
            'logradouro'                => $post['logradouro'] ?? null,
            'numero'                    => $post['numero'] ?? null,
            'complemento'               => $post['complemento'] ?? null,
            'bairro'                    => $post['bairro'] ?? null,
            'cidade_sede'               => $post['cidade_sede'] ?? null,
            'uf_sede'                   => $post['uf_sede'] ?? null,
            'dias_funcionamento'        => $post['dias_funcionamento'] ?? null,
            'horarios_funcionamento'    => $post['horarios_funcionamento'] ?? null,
            'obs_extras'                => $post['obs_extras'] ?? null,
            'imagens'                   => null, // upload de galeria será tratado depois
            'logo'                      => $logoPath,
            'mapa_embed'                => $post['mapa_embed'] ?? null,
            'servicos_lista'            => $servicosLista ? json_encode($servicosLista) : null,
            'cidades_atendidas'         => json_encode($cidades),
            'max_produtos'              => $post['max_produtos'] !== '' ? (int) $post['max_produtos'] : null,
            'max_servicos'              => $post['max_servicos'] !== '' ? (int) $post['max_servicos'] : null,
            'ativo'                     => isset($post['ativo']) ? 1 : 0,
        ];

        // Insere ou atualiza de forma explícita usando o Query Builder,
        // para conseguirmos ver claramente qualquer erro de banco.
        $db      = \Config\Database::connect();
        $builder = $db->table('spots');

        if ($id === null) {
            $ok       = $builder->insert($spotData);
            $insertId = $db->insertID();
        } else {
            $builder->where('id', $id);
            $ok       = $builder->update($spotData);
            $insertId = $id;
        }

        if (! $ok) {
            $dbError  = $db->error();
            $mensagem = ! empty($dbError['message']) ? $dbError['message'] : 'Erro desconhecido ao gravar o spot.';

            $errors = [];

            // Trata erro de slug duplicado de forma amigável
            if (isset($dbError['code']) && (int) $dbError['code'] === 1062) {
                $errors['slug'] = 'Já existe um spot usando esse slug. Altere o nome ou o slug.';
            } else {
                $errors['db'] = 'Erro de banco de dados ao salvar o spot: ' . $mensagem;
            }

            return redirect()->back()->withInput()->with('errors', $errors);
        }

        return redirect()->to(site_url('admin/spots'))->with('message', 'Spot salvo com sucesso.');
    }

    /**
     * Recebe um texto como:
     * "Ribeirão Preto/SP, Sertãozinho/SP, Franca/SP"
     * e retorna um array estruturado.
     */
    protected function parseCidades(string $cidadesRaw): array
    {
        $lista = array_filter(array_map('trim', explode(',', $cidadesRaw)));
        $saida = [];

        foreach ($lista as $item) {
            // Formato esperado: Cidade/UF
            [$cidade, $estado] = array_pad(array_map('trim', explode('/', $item)), 2, '');

            if ($cidade !== '' && $estado !== '') {
                $saida[] = [
                    'cidade' => $cidade,
                    'estado' => strtoupper($estado),
                ];
            }
        }

        return $saida;
    }

    /**
     * Recebe um texto como:
     * "Troca de óleo - Serviço rápido e completo
     * Alinhamento e balanceamento - Equipamentos modernos"
     * e retorna um array estruturado.
     */
    protected function parseServicos(string $servicosRaw): array
    {
        $linhas  = preg_split('/\r\n|\r|\n/', $servicosRaw);
        $linhas  = array_filter(array_map('trim', $linhas));
        $saida   = [];

        foreach ($linhas as $linha) {
            // Formato sugerido: Título - Descrição
            [$titulo, $descricao] = array_pad(array_map('trim', explode(' - ', $linha, 2)), 2, '');

            if ($titulo === '') {
                continue;
            }

            $saida[] = [
                'titulo'     => $titulo,
                'descricao'  => $descricao,
            ];
        }

        return $saida;
    }
}

