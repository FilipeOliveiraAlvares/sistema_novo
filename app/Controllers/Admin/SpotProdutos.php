<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\SpotProdutoModel;
use App\Traits\AuthTrait;

class SpotProdutos extends BaseController
{
    use AuthTrait;

    protected SpotModel $spotModel;
    protected SpotProdutoModel $produtoModel;

    public function __construct()
    {
        $this->spotModel    = new SpotModel();
        $this->produtoModel = new SpotProdutoModel();
    }

    public function index(int $spotId)
    {
        $spot = $this->spotModel->find($spotId);
        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        $produtos = $this->produtoModel
            ->where('spot_id', $spotId)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $data = [
            'spot'     => $spot,
            'produtos' => $produtos,
        ];

        return view('admin/spot_produtos/index', $data);
    }

    public function create(int $spotId)
    {
        $spot = $this->spotModel->find($spotId);
        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            return $this->save($spot);
        }

        return view('admin/spot_produtos/form', [
            'spot'    => $spot,
            'produto' => null,
        ]);
    }

    public function edit(int $spotId, int $id)
    {
        $spot = $this->spotModel->find($spotId);
        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        $produto = $this->produtoModel
            ->where('spot_id', $spotId)
            ->find($id);

        if (! $produto) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produto não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            return $this->save($spot, $id, $produto);
        }

        return view('admin/spot_produtos/form', [
            'spot'    => $spot,
            'produto' => $produto,
        ]);
    }

    public function delete(int $spotId, int $id)
    {
        $spot = $this->spotModel->find($spotId);
        if (! $spot) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        if (! $this->canAccessSpot($spot)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Spot não encontrado');
        }

        $produto = $this->produtoModel
            ->where('spot_id', $spotId)
            ->find($id);

        if (! $produto) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produto não encontrado');
        }

        $this->produtoModel
            ->where('spot_id', $spotId)
            ->delete($id);

        return redirect()->to(site_url('admin/spots/' . $spotId . '/produtos'))
            ->with('message', 'Produto removido com sucesso.');
    }

    protected function save(array $spot, ?int $id = null, ?array $produtoAtual = null)
    {
        $post = $this->request->getPost();

        // Checa limite de produtos por spot, se houver
        if ($id === null && ! empty($spot['max_produtos'])) {
            $total = $this->produtoModel->where('spot_id', $spot['id'])->countAllResults();
            if ($total >= (int) $spot['max_produtos']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', ['Limite de produtos atingido para este spot.']);
            }
        }

        $nome = $post['nome'] ?? '';
        $slug = $post['slug'] ?: url_title($nome, '-', true);

        // imagens atuais (edição)
        $imagensAtuais = [];
        if ($produtoAtual && ! empty($produtoAtual['imagens'])) {
            $decoded = json_decode($produtoAtual['imagens'], true);
            if (is_array($decoded)) {
                $imagensAtuais = $decoded;
            }
        }

        $imagens = $imagensAtuais;
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        // Primeira passagem: valida todos os arquivos ANTES de mover qualquer um
        $arquivosValidos = [];
        for ($i = 1; $i <= 3; $i++) {
            $file = $this->request->getFile('imagem' . $i);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                // Valida tipo de arquivo
                $mimeType = $file->getMimeType();
                if (! in_array($mimeType, $allowedTypes, true)) {
                    return redirect()->back()->withInput()->with('errors', ['Tipo de arquivo inválido na imagem ' . $i . '. Use apenas imagens (JPG, PNG, GIF ou WEBP).']);
                }

                // Valida tamanho (máximo 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->withInput()->with('errors', ['Imagem ' . $i . ' muito grande. O tamanho máximo é 5MB.']);
                }

                // Valida dimensões da imagem (máximo 4000x4000px)
                $imageInfo = @getimagesize($file->getTempName());
                if ($imageInfo !== false) {
                    $maxWidth = 4000;
                    $maxHeight = 4000;
                    if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                        return redirect()->back()->withInput()->with('errors', ['Imagem ' . $i . ' muito grande. Dimensões máximas: ' . $maxWidth . 'x' . $maxHeight . ' pixels.']);
                    }
                }

                // Armazena o arquivo válido para mover depois
                $arquivosValidos[$i] = $file;
            }
        }

        // Segunda passagem: move apenas os arquivos que passaram na validação
        $uploadDir = FCPATH . 'uploads/produtos';
        if (! empty($arquivosValidos) && ! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($arquivosValidos as $i => $file) {
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $imagens[$i - 1] = 'uploads/produtos/' . $newName;
        }
        // remove vazios e reorganiza índices
        $imagens = array_values(array_filter($imagens));

        $data = [
            'spot_id'         => $spot['id'],
            'nome'            => $nome,
            'slug'            => $slug,
            'descricao_curta' => $post['descricao_curta'] ?? null,
            'descricao_longa' => $post['descricao_longa'] ?? null,
            'preco'           => $post['preco'] !== '' ? $post['preco'] : null,
            'ordem'           => $post['ordem'] !== '' ? (int) $post['ordem'] : 0,
            'ativo'           => isset($post['ativo']) ? 1 : 0,
        ];

        $data['imagens'] = $imagens ? json_encode($imagens) : null;

        if ($id === null) {
            $ok = $this->produtoModel->insert($data);
        } else {
            $ok = $this->produtoModel
                ->where('spot_id', $spot['id'])
                ->update($id, $data);
        }

        if ($ok === false) {
            return redirect()->back()->withInput()->with('errors', $this->produtoModel->errors());
        }

        return redirect()->to(site_url('admin/spots/' . $spot['id'] . '/produtos'))
            ->with('message', 'Produto salvo com sucesso.');
    }
}


