<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpotModel;
use App\Models\SpotServicoModel;
use App\Traits\AuthTrait;

class SpotServicos extends BaseController
{
    use AuthTrait;

    protected SpotModel $spotModel;
    protected SpotServicoModel $servicoModel;

    public function __construct()
    {
        $this->spotModel    = new SpotModel();
        $this->servicoModel = new SpotServicoModel();
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

        $servicos = $this->servicoModel
            ->where('spot_id', $spotId)
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $data = [
            'spot'     => $spot,
            'servicos' => $servicos,
        ];

        return view('admin/spot_servicos/index', $data);
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

        return view('admin/spot_servicos/form', [
            'spot'    => $spot,
            'servico' => null,
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

        $servico = $this->servicoModel
            ->where('spot_id', $spotId)
            ->find($id);

        if (! $servico) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Serviço não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            return $this->save($spot, $id, $servico);
        }

        return view('admin/spot_servicos/form', [
            'spot'    => $spot,
            'servico' => $servico,
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

        $servico = $this->servicoModel
            ->where('spot_id', $spotId)
            ->find($id);

        if (! $servico) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Serviço não encontrado');
        }

        $this->servicoModel
            ->where('spot_id', $spotId)
            ->delete($id);

        return redirect()->to(site_url('admin/spots/' . $spotId . '/servicos'))
            ->with('message', 'Serviço removido com sucesso.');
    }

    protected function save(array $spot, ?int $id = null, ?array $servicoAtual = null)
    {
        $post = $this->request->getPost();

        // Checa limite de serviços por spot, se houver
        if ($id === null && ! empty($spot['max_servicos'])) {
            $total = $this->servicoModel->where('spot_id', $spot['id'])->countAllResults();
            if ($total >= (int) $spot['max_servicos']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', ['Limite de serviços atingido para este spot.']);
            }
        }

        $nome = $post['nome'] ?? '';
        $slug = $post['slug'] ?: url_title($nome, '-', true);

        // imagens atuais (edição)
        $imagensAtuais = [];
        if ($servicoAtual && ! empty($servicoAtual['imagens'])) {
            $decoded = json_decode($servicoAtual['imagens'], true);
            if (is_array($decoded)) {
                $imagensAtuais = $decoded;
            }
        }

        $imagens = $imagensAtuais;
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
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

                $uploadDir = FCPATH . 'uploads/servicos';
                if (! is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }
                $newName = $file->getRandomName();
                $file->move($uploadDir, $newName);
                $imagens[$i - 1] = 'uploads/servicos/' . $newName;
            }
        }
        $imagens = array_values(array_filter($imagens));

        $data = [
            'spot_id'         => $spot['id'],
            'nome'            => $nome,
            'slug'            => $slug,
            'descricao_curta' => $post['descricao_curta'] ?? null,
            'descricao_longa' => $post['descricao_longa'] ?? null,
            'preco_a_partir'  => $post['preco_a_partir'] !== '' ? $post['preco_a_partir'] : null,
            'ordem'           => $post['ordem'] !== '' ? (int) $post['ordem'] : 0,
            'ativo'           => isset($post['ativo']) ? 1 : 0,
        ];

        $data['imagens'] = $imagens ? json_encode($imagens) : null;

        if ($id === null) {
            $ok = $this->servicoModel->insert($data);
        } else {
            $ok = $this->servicoModel
                ->where('spot_id', $spot['id'])
                ->update($id, $data);
        }

        if ($ok === false) {
            return redirect()->back()->withInput()->with('errors', $this->servicoModel->errors());
        }

        return redirect()->to(site_url('admin/spots/' . $spot['id'] . '/servicos'))
            ->with('message', 'Serviço salvo com sucesso.');
    }
}


