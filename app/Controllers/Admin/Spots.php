<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\UserModel;
use App\Models\CidadeModel;
use App\Models\RamoModel;
use App\Traits\AuthTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Spots extends BaseController
{
    use AuthTrait;

    protected SpotModel $spotModel;

    public function __construct()
    {
        $this->spotModel = new SpotModel();
    }

    public function index()
    {
        $user = $this->getCurrentUser();

        $builder = $this->spotModel->orderBy('id', 'DESC');

        // Se for vendedor, mostra apenas seus próprios spots
        if ($user && $user['perfil'] === 'vendedor') {
            $builder = $builder->where('vendedor_id', $user['id']);
        }

        $spots = $builder->findAll();

        // Se for admin, carrega nomes dos vendedores para exibir na lista
        $vendedoresMap = [];
        if ($user && $user['perfil'] === 'admin' && ! empty($spots)) {
            $userModel = new UserModel();
            $vendedoresIds = array_filter(array_unique(array_column($spots, 'vendedor_id')));
            if (! empty($vendedoresIds)) {
                // Usa Query Builder para buscar múltiplos IDs
                $db = \Config\Database::connect();
                $vendedores = $db->table('usuarios')
                    ->whereIn('id', $vendedoresIds)
                    ->get()
                    ->getResultArray();
                foreach ($vendedores as $v) {
                    $vendedoresMap[(int) $v['id']] = $v['nome'];
                }
            }
        }

        $data['spots'] = $spots;
        $data['vendedoresMap'] = $vendedoresMap;
        $data['isAdmin'] = $user && $user['perfil'] === 'admin';

        return view('admin/spots/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod(true) === 'POST') {
            return $this->saveSpot();
        }

        $user = $this->getCurrentUser();
        $data['spot'] = null;
        $data['vendedores'] = [];
        $data['isAdmin'] = $user && $user['perfil'] === 'admin';

        // Se for admin, carrega lista de vendedores para o select
        if ($data['isAdmin']) {
            $userModel = new UserModel();
            $data['vendedores'] = $userModel
                ->where('perfil', 'vendedor')
                ->where('ativo', 1)
                ->orderBy('nome', 'ASC')
                ->findAll();
        }

        // Carrega lista de cidades para o select (com cache)
        $data['cidades'] = $this->getCidadesCached();

        // Carrega lista de ramos para o select (com cache)
        $data['ramos'] = $this->getRamosCached();

        return view('admin/spots/form', $data);
    }

    public function edit(int $id)
    {
        $spot = $this->spotModel->find($id);

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        // Bloqueia acesso se o vendedor tentar editar spot de outro vendedor
        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            return $this->saveSpot($id);
        }

        $user = $this->getCurrentUser();
        $data['spot'] = $spot;
        $data['vendedores'] = [];
        $data['isAdmin'] = $user && $user['perfil'] === 'admin';

        // Se for admin, carrega lista de vendedores para o select
        if ($data['isAdmin']) {
            $userModel = new UserModel();
            $data['vendedores'] = $userModel
                ->where('perfil', 'vendedor')
                ->where('ativo', 1)
                ->orderBy('nome', 'ASC')
                ->findAll();
        }

        // Carrega lista de cidades para o select (com cache)
        $data['cidades'] = $this->getCidadesCached();

        // Carrega lista de ramos para o select (com cache)
        $data['ramos'] = $this->getRamosCached();

        return view('admin/spots/form', $data);
    }

    public function delete(int $id)
    {
        $spot = $this->spotModel->find($id);

        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        $this->spotModel->delete($id);

        return redirect()->to(site_url('admin/spots'))->with('message', 'Spot removido com sucesso.');
    }

    /**
     * Salva o spot (create/update).
     * A lista de cidades é armazenada em JSON no próprio registro do spot
     * e será usada apenas para compor o conteúdo e a área de atendimento.
     */
    protected function saveSpot(?int $id = null)
    {
        $user = $this->getCurrentUser();

        $post = $this->request->getPost();

        // Validação básica de campos obrigatórios
        if (empty($post['nome'])) {
            return redirect()->back()->withInput()->with('errors', ['O campo Nome é obrigatório.']);
        }

        $cidadesRaw   = $post['cidades_atendidas'] ?? '';
        $cidades      = $this->parseCidades($cidadesRaw);

        $slug = $post['slug'] ?: url_title($post['nome'], '-', true);
        
        // Validação de slug
        if (empty($slug) || strlen($slug) < 3) {
            return redirect()->back()->withInput()->with('errors', ['O slug gerado é inválido. Verifique o nome do spot.']);
        }

        // Validação de URLs (site, facebook, instagram)
        $urlErrors = [];
        if (!empty($post['site']) && !filter_var($post['site'], FILTER_VALIDATE_URL)) {
            $urlErrors[] = 'URL do site inválida. Use um formato válido (ex: https://www.exemplo.com.br).';
        }
        if (!empty($post['facebook']) && !filter_var($post['facebook'], FILTER_VALIDATE_URL)) {
            $urlErrors[] = 'URL do Facebook inválida. Use um formato válido (ex: https://www.facebook.com/pagina).';
        }
        if (!empty($post['instagram']) && !filter_var($post['instagram'], FILTER_VALIDATE_URL)) {
            $urlErrors[] = 'URL do Instagram inválida. Use um formato válido (ex: https://www.instagram.com/perfil).';
        }
        if (!empty($urlErrors)) {
            return redirect()->back()->withInput()->with('errors', $urlErrors);
        }

        // Validação de CPF/CNPJ (se informado)
        if (!empty($post['cpf_cnpj'])) {
            if (!$this->validarCPFCNPJ($post['cpf_cnpj'])) {
                return redirect()->back()->withInput()->with('errors', ['CPF/CNPJ inválido. Verifique os dados informados.']);
            }
        }

        // Tratamento do upload de logo
        $logoPath = $post['logo_atual'] ?? null;
        $logoFile = $this->request->getFile('logo');

        if ($logoFile && $logoFile->isValid() && ! $logoFile->hasMoved()) {
            // Valida tipo de arquivo
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $mimeType = $logoFile->getMimeType();
            if (! in_array($mimeType, $allowedTypes, true)) {
                return redirect()->back()->withInput()->with('errors', ['Tipo de arquivo inválido. Use apenas imagens (JPG, PNG, GIF ou WEBP).']);
            }

            // Valida tamanho (máximo 5MB)
            if ($logoFile->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('errors', ['Arquivo muito grande. O tamanho máximo é 5MB.']);
            }

            // Valida dimensões da imagem (máximo 4000x4000px)
            $imageInfo = @getimagesize($logoFile->getTempName());
            if ($imageInfo !== false) {
                $maxWidth = 4000;
                $maxHeight = 4000;
                if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                    return redirect()->back()->withInput()->with('errors', ['Imagem muito grande. Dimensões máximas: ' . $maxWidth . 'x' . $maxHeight . ' pixels.']);
                }
            }

            // Salva o logo dentro da pasta public/uploads/logos
            $uploadDir = FCPATH . 'uploads/logos';

            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
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
            'ramo'                      => $post['ramo'] ?? null, // mantém para compatibilidade
            'ramo_id'                   => $post['ramo_id'] !== '' ? (int) $post['ramo_id'] : null,
            'cidade_id'                 => $post['cidade_id'] !== '' ? (int) $post['cidade_id'] : null,
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
            'mapa_embed'                => $this->sanitizeMapaEmbed($post['mapa_embed'] ?? null),
            'cidades_atendidas'         => json_encode($cidades),
            'max_produtos'              => $post['max_produtos'] !== '' ? (int) $post['max_produtos'] : null,
            'max_servicos'              => $post['max_servicos'] !== '' ? (int) $post['max_servicos'] : null,
            'ativo'                     => isset($post['ativo']) ? 1 : 0,
        ];

        // Define o vendedor responsável pelo spot
        if ($user && $user['perfil'] === 'vendedor') {
            // Vendedor sempre cria spots para si mesmo
            $spotData['vendedor_id'] = $user['id'];
        } elseif ($user && $user['perfil'] === 'admin') {
            // Admin pode escolher o vendedor (ou deixar null)
            $vendedorId = $post['vendedor_id'] ?? null;
            if ($vendedorId !== '' && $vendedorId !== null) {
                $spotData['vendedor_id'] = (int) $vendedorId;
            } else {
                $spotData['vendedor_id'] = null;
            }
        }

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
     * Sanitiza o campo mapa_embed para permitir apenas iframes seguros do Google Maps.
     * Remove qualquer conteúdo malicioso e valida que é um iframe do Google Maps.
     *
     * @param string|null $mapaEmbed
     * @return string|null
     */
    protected function sanitizeMapaEmbed(?string $mapaEmbed): ?string
    {
        if (empty($mapaEmbed)) {
            return null;
        }

        // Remove espaços em branco
        $mapaEmbed = trim($mapaEmbed);

        // Verifica se contém um iframe do Google Maps
        // Padrão: iframe com src contendo google.com/maps/embed
        if (preg_match('/<iframe[^>]*src=["\'](https?:\/\/www\.google\.com\/maps\/embed[^"\']*)["\'][^>]*><\/iframe>/i', $mapaEmbed, $matches)) {
            // Extrai apenas o iframe válido e sanitiza
            $src = $matches[1];
            
            // Valida que a URL é realmente do Google Maps
            if (filter_var($src, FILTER_VALIDATE_URL) && strpos($src, 'google.com/maps/embed') !== false) {
                // Retorna apenas o iframe sanitizado (esc() já sanitiza para HTML)
                return '<iframe src="' . esc($src) . '" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
            }
        }

        // Se não for um iframe válido do Google Maps, retorna null
        return null;
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
     * Retorna lista de cidades com cache (1 hora)
     */
    protected function getCidadesCached(): array
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'cidades_lista_ativa';

        $cidades = $cache->get($cacheKey);

        if ($cidades === null) {
            $cidadeModel = new CidadeModel();
            $cidades = $cidadeModel
                ->where('ativo', 1)
                ->orderBy('uf', 'ASC')
                ->orderBy('nome', 'ASC')
                ->findAll();

            // Cache por 1 hora (3600 segundos)
            $cache->save($cacheKey, $cidades, 3600);
        }

        return $cidades;
    }

    /**
     * Retorna lista de ramos com cache (1 hora)
     */
    protected function getRamosCached(): array
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'ramos_lista_ativa';

        $ramos = $cache->get($cacheKey);

        if ($ramos === null) {
            $ramoModel = new RamoModel();
            $ramos = $ramoModel
                ->where('ativo', 1)
                ->orderBy('ordem', 'ASC')
                ->orderBy('nome', 'ASC')
                ->findAll();

            // Cache por 1 hora (3600 segundos)
            $cache->save($cacheKey, $ramos, 3600);
        }

        return $ramos;
    }

    /**
     * Valida CPF ou CNPJ
     * 
     * @param string $cpfCnpj CPF ou CNPJ (pode conter formatação)
     * @return bool True se válido, false caso contrário
     */
    protected function validarCPFCNPJ(string $cpfCnpj): bool
    {
        // Remove formatação (pontos, traços, barras, espaços)
        $cpfCnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);
        
        // Valida CPF (11 dígitos)
        if (strlen($cpfCnpj) === 11) {
            return $this->validarCPF($cpfCnpj);
        }
        
        // Valida CNPJ (14 dígitos)
        if (strlen($cpfCnpj) === 14) {
            return $this->validarCNPJ($cpfCnpj);
        }
        
        return false;
    }

    /**
     * Valida CPF
     * 
     * @param string $cpf CPF sem formatação (11 dígitos)
     * @return bool True se válido, false caso contrário
     */
    protected function validarCPF(string $cpf): bool
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Valida primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        if ((int) $cpf[9] !== $digito1) {
            return false;
        }
        
        // Valida segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        return (int) $cpf[10] === $digito2;
    }

    /**
     * Valida CNPJ
     * 
     * @param string $cnpj CNPJ sem formatação (14 dígitos)
     * @return bool True se válido, false caso contrário
     */
    protected function validarCNPJ(string $cnpj): bool
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Valida primeiro dígito verificador
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += (int) $cnpj[$i] * $pesos[$i];
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        if ((int) $cnpj[12] !== $digito1) {
            return false;
        }
        
        // Valida segundo dígito verificador
        $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += (int) $cnpj[$i] * $pesos[$i];
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        return (int) $cnpj[13] === $digito2;
    }
}

