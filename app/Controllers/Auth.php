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

        $userModel = new UserModel();
        $user      = $userModel
            ->where('email', $email)
            ->where('ativo', 1)
            ->first();

        if (! $user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Usuário não encontrado ou inativo.');
        }

        if (! password_verify($password, $user['senha_hash'] ?? '')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Senha inválida.');
        }

        $session = session();
        $session->set([
            'user_id'    => $user['id'],
            'user_nome'  => $user['nome'],
            'user_email' => $user['email'],
            'user_perfil'=> $user['perfil'],
            'is_logged_in' => true,
        ]);

        return redirect()->to(site_url('admin/spots'));
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to(site_url('login'));
    }
}


