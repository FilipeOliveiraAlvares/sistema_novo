<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod(true) === 'POST') {
            return $this->attemptLogin();
        }

        return view('auth/login');
    }

    protected function attemptLogin()
    {
        $post = $this->request->getPost();
        $email    = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';

        // Rate limiting: máximo 5 tentativas por minuto por IP
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();
        
        // Sanitiza o IP para usar como chave de cache (remove caracteres reservados)
        $ipKey = str_replace([':', '/', '\\', '@', '{', '}', '(', ')'], '_', $ipAddress);
        
        if ($throttler->check('login_' . $ipKey, 5, 60) === false) {
            log_message('warning', "Rate limiting bloqueado para IP: {$ipAddress} ao tentar fazer login com email: {$email}");
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Muitas tentativas de login. Aguarde 1 minuto antes de tentar novamente.');
        }

        // Validação básica
        if (empty($email) || empty($password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email e senha são obrigatórios.');
        }

        // Validação de email
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email inválido.');
        }

        $userModel = new UserModel();
        $user      = $userModel
            ->where('email', strtolower($email))
            ->where('ativo', 1)
            ->first();

        if (! $user) {
            log_message('error', "Tentativa de login falhou: usuário não encontrado ou inativo. Email: {$email}, IP: {$ipAddress}");
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Usuário não encontrado ou inativo.');
        }

        if (! password_verify($password, $user['senha_hash'] ?? '')) {
            log_message('error', "Tentativa de login falhou: senha inválida. Email: {$email}, IP: {$ipAddress}, User ID: {$user['id']}");
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Senha inválida.');
        }

        // Login bem-sucedido: remove o throttler para este IP
        $throttler->remove('login_' . $ipKey);

        $session = session();
        $session->set([
            'user_id'    => $user['id'],
            'user_nome'  => $user['nome'],
            'user_email' => $user['email'],
            'user_perfil'=> $user['perfil'],
            'is_logged_in' => true,
        ]);

        log_message('info', "Login bem-sucedido. User ID: {$user['id']}, Email: {$user['email']}, Perfil: {$user['perfil']}, IP: {$ipAddress}");

        return redirect()->to(site_url('admin/spots'));
    }

    public function logout()
    {
        $session = session();
        $userId = $session->get('user_id');
        $userEmail = $session->get('user_email');
        $ipAddress = $this->request->getIPAddress();
        
        $session->destroy();

        if ($userId) {
            log_message('info', "Logout realizado. User ID: {$userId}, Email: {$userEmail}, IP: {$ipAddress}");
        }

        return redirect()->to(site_url('login'));
    }
}


