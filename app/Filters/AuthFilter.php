<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('user_id')) {
            return redirect()->to(site_url('login'));
        }

        // Aqui no futuro podemos checar perfil (admin/vendedor) usando $arguments.
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada por enquanto.
        return $response;
    }
}


