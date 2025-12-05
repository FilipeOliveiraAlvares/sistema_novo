<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\SpotProdutoModel;

class SpotProdutos extends BaseController
{
    protected SpotModel $spotModel;
    protected SpotProdutoModel $produtoModel;

    public function __construct()
    {
        $this->spotModel    = new SpotModel();
        $this->produtoModel = new SpotProdutoModel();
    }

    /**
     * Copia simplificada da checagem usada em Admin\Spots
     * para garantir que vendedor só acesse seus próprios spots.
     */
    protected function getCurrentUser(): ?array
    {
        $session = session();

        if (! $session->get('user_id')) {
            return null;
        }

        return [
            'id'     => (int) $session->get('user_id'),
            'nome'   => (string) $session->get('user_nome'),
            'email'  => (string) $session->get('user_email'),
            'perfil' => (string) $session->get('user_perfil'),
        ];
    }

    protected function canAccessSpot(array $spot): bool
    {
        $user = $this->getCurrentUser();

        if (! $user) {
            return false;
        }

        if ($user['perfil'] === 'admin') {
            return true;
        }

        if ($user['perfil'] === 'vendedor' && (int) ($spot['vendedor_id'] ?? 0) === $user['id']) {
            return true;
        }

        return false;
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
        for ($i = 1; $i <= 3; $i++) {
            $file = $this->request->getFile('imagem' . $i);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $uploadDir = FCPATH . 'uploads/produtos';
                if (! is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }
                $newName = $file->getRandomName();
                $file->move($uploadDir, $newName);
                $imagens[$i - 1] = 'uploads/produtos/' . $newName;
            }
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


