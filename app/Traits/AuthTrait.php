<?php

namespace App\Traits;

/**
 * Trait com métodos comuns de autenticação e autorização
 * para controllers admin.
 */
trait AuthTrait
{
    /**
     * Retorna dados básicos do usuário logado a partir da sessão.
     *
     * @return array|null Array com id, nome, email e perfil, ou null se não logado
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

    /**
     * Verifica se o usuário logado pode acessar o spot informado.
     * Regra:
     * - admin: pode tudo
     * - vendedor: apenas spots onde vendedor_id = seu id
     *
     * @param array $spot Array com dados do spot (deve conter vendedor_id)
     * @return bool
     */
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

    /**
     * Exige que o usuário seja admin. Lança exceção se não for.
     *
     * @return void
     * @throws \CodeIgniter\Exceptions\PageNotFoundException
     */
    protected function requireAdmin(): void
    {
        $user = $this->getCurrentUser();

        if (! $user || $user['perfil'] !== 'admin') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Acesso negado.');
        }
    }
}

