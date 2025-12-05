<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SpotModel;
use App\Traits\AuthTrait;

class Usuarios extends BaseController
{
    use AuthTrait;

    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->requireAdmin();

        $data['usuarios'] = $this->userModel->orderBy('id', 'DESC')->findAll();

        return view('admin/usuarios/index', $data);
    }

    public function create()
    {
        $this->requireAdmin();

        if ($this->request->getMethod(true) === 'POST') {
            return $this->saveUsuario();
        }

        $data['usuario'] = null;

        return view('admin/usuarios/form', $data);
    }

    public function edit(int $id)
    {
        $this->requireAdmin();

        $usuario = $this->userModel->find($id);

        if (! $usuario) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Usuário não encontrado');
        }

        if ($this->request->getMethod(true) === 'POST') {
            // Verifica se é requisição de reatribuição de spots
            if ($this->request->getPost('acao') === 'reatribuir_spots') {
                return $this->reatribuirSpots($id);
            }
            return $this->saveUsuario($id);
        }

        // Se for vendedor, carrega informações sobre seus spots
        $spotModel = new SpotModel();
        $totalSpots = 0;
        $vendedores = [];
        if ($usuario['perfil'] === 'vendedor') {
            $totalSpots = $spotModel->where('vendedor_id', $id)->countAllResults();
            
            // Carrega lista de outros vendedores para reatribuição
            $vendedores = $this->userModel
                ->where('perfil', 'vendedor')
                ->where('ativo', 1)
                ->where('id !=', $id)
                ->orderBy('nome', 'ASC')
                ->findAll();
        }

        $data['usuario'] = $usuario;
        $data['totalSpots'] = $totalSpots;
        $data['vendedores'] = $vendedores;

        return view('admin/usuarios/form', $data);
    }

    public function delete(int $id)
    {
        $this->requireAdmin();

        $usuario = $this->userModel->find($id);

        if (! $usuario) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Usuário não encontrado');
        }

        // Não permitir excluir a si mesmo
        $currentUser = $this->getCurrentUser();
        if ($currentUser && (int) $usuario['id'] === $currentUser['id']) {
            log_message('warning', "Tentativa de excluir próprio usuário bloqueada. User ID: {$currentUser['id']}, Email: {$currentUser['email']}");
            return redirect()->to(site_url('admin/usuarios'))->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $usuarioEmail = $usuario['email'] ?? 'desconhecido';
        $usuarioPerfil = $usuario['perfil'] ?? 'desconhecido';
        $currentUserId = $currentUser ? $currentUser['id'] : 'sistema';
        $currentUserEmail = $currentUser ? $currentUser['email'] : 'sistema';

        $this->userModel->delete($id);

        log_message('warning', "Usuário excluído. Excluído por: User ID {$currentUserId} ({$currentUserEmail}), Usuário excluído ID: {$id}, Email: {$usuarioEmail}, Perfil: {$usuarioPerfil}");

        return redirect()->to(site_url('admin/usuarios'))->with('message', 'Usuário removido com sucesso.');
    }

    protected function saveUsuario(?int $id = null)
    {
        $post = $this->request->getPost();

        // Validação básica
        if (empty($post['nome'])) {
            return redirect()->back()->withInput()->with('errors', ['O campo Nome é obrigatório.']);
        }

        if (empty($post['email'])) {
            return redirect()->back()->withInput()->with('errors', ['O campo Email é obrigatório.']);
        }

        // Validação de email
        if (! filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('errors', ['Email inválido.']);
        }

        // Validação de perfil
        $perfil = $post['perfil'] ?? 'vendedor';
        if (! in_array($perfil, ['admin', 'vendedor'], true)) {
            $perfil = 'vendedor';
        }

        $data = [
            'nome'  => trim($post['nome']),
            'email' => trim(strtolower($post['email'])),
            'perfil' => $perfil,
            'ativo' => isset($post['ativo']) ? 1 : 0,
        ];

        // Se for criação ou se informou nova senha, processa a senha
        $senha = $post['senha'] ?? '';
        if ($id === null || $senha !== '') {
            if ($senha === '') {
                if ($id === null) {
                    return redirect()->back()->withInput()->with('errors', ['Senha é obrigatória para novos usuários.']);
                }
                // Se for edição e não informou senha, mantém a atual
            } else {
                if (strlen($senha) < 6) {
                    return redirect()->back()->withInput()->with('errors', ['A senha deve ter pelo menos 6 caracteres.']);
                }
                $data['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
            }
        }

        // Valida email único (exceto o próprio registro na edição)
        $existing = $this->userModel->where('email', $data['email'])->first();
        if ($existing && (int) ($existing['id'] ?? 0) !== $id) {
            return redirect()->back()->withInput()->with('errors', ['Este email já está cadastrado.']);
        }

        $currentUser = $this->getCurrentUser();
        $currentUserId = $currentUser ? $currentUser['id'] : 'sistema';
        $currentUserEmail = $currentUser ? $currentUser['email'] : 'sistema';

        if ($id === null) {
            $ok = $this->userModel->insert($data);
            $newUserId = $this->userModel->getInsertID();
            if ($ok) {
                log_message('info', "Usuário criado. Criado por: User ID {$currentUserId} ({$currentUserEmail}), Novo usuário ID: {$newUserId}, Email: {$data['email']}, Perfil: {$data['perfil']}");
            }
        } else {
            // Verifica se houve mudança de perfil
            $usuarioAntigo = $this->userModel->find($id);
            $perfilAntigo = $usuarioAntigo['perfil'] ?? 'desconhecido';
            $perfilNovo = $data['perfil'];
            
            $ok = $this->userModel->update($id, $data);
            
            if ($ok) {
                $mudancas = [];
                if ($perfilAntigo !== $perfilNovo) {
                    $mudancas[] = "perfil de '{$perfilAntigo}' para '{$perfilNovo}'";
                }
                if (isset($data['senha_hash'])) {
                    $mudancas[] = "senha alterada";
                }
                if (isset($data['ativo']) && (int) $data['ativo'] !== (int) ($usuarioAntigo['ativo'] ?? 0)) {
                    $status = (int) $data['ativo'] === 1 ? 'ativado' : 'desativado';
                    $mudancas[] = "usuário {$status}";
                }
                
                $mudancasTexto = !empty($mudancas) ? ' (' . implode(', ', $mudancas) . ')' : '';
                log_message('info', "Usuário editado. Editado por: User ID {$currentUserId} ({$currentUserEmail}), Usuário ID: {$id}, Email: {$data['email']}{$mudancasTexto}");
            }
        }

        if ($ok === false) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return redirect()->to(site_url('admin/usuarios'))->with('message', 'Usuário salvo com sucesso.');
    }

    protected function reatribuirSpots(int $vendedorId)
    {
        $vendedor = $this->userModel->find($vendedorId);

        if (! $vendedor || $vendedor['perfil'] !== 'vendedor') {
            return redirect()->back()->with('error', 'Usuário não encontrado ou não é vendedor.');
        }

        $novoVendedorId = (int) ($this->request->getPost('novo_vendedor_id') ?? 0);

        if ($novoVendedorId <= 0) {
            return redirect()->back()->with('error', 'Selecione um vendedor para reatribuir os spots.');
        }

        $novoVendedor = $this->userModel->find($novoVendedorId);
        if (! $novoVendedor || $novoVendedor['perfil'] !== 'vendedor') {
            return redirect()->back()->with('error', 'Vendedor de destino inválido.');
        }

        // Reatribui todos os spots usando transação para garantir atomicidade
        $db = \Config\Database::connect();
        $currentUser = $this->getCurrentUser();
        $currentUserId = $currentUser ? $currentUser['id'] : 'sistema';
        $currentUserEmail = $currentUser ? $currentUser['email'] : 'sistema';
        $vendedorEmail = $vendedor['email'] ?? 'desconhecido';
        $novoVendedorEmail = $novoVendedor['email'] ?? 'desconhecido';

        $db->transStart();
        
        try {
            $builder = $db->table('spots');
            $builder->where('vendedor_id', $vendedorId);
            $builder->set('vendedor_id', $novoVendedorId);
            $builder->update();
            $total = $db->affectedRows();
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                log_message('error', "Falha na reatribuição de spots. Executado por: User ID {$currentUserId} ({$currentUserEmail}), vendedor ID {$vendedorId} para {$novoVendedorId}");
                return redirect()->back()->with('error', 'Erro ao reatribuir spots. Tente novamente.');
            }
            
            log_message('warning', "Reatribuição de spots. Executado por: User ID {$currentUserId} ({$currentUserEmail}), {$total} spot(s) reatribuído(s) do vendedor ID {$vendedorId} ({$vendedorEmail}) para vendedor ID {$novoVendedorId} ({$novoVendedorEmail})");
            
            return redirect()->back()->with('message', "{$total} spot(s) reatribuído(s) com sucesso para {$novoVendedor['nome']}.");
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', "Exceção na reatribuição de spots: " . $e->getMessage() . ". Executado por: User ID {$currentUserId} ({$currentUserEmail})");
            return redirect()->back()->with('error', 'Erro ao reatribuir spots: ' . $e->getMessage());
        }
    }
}

