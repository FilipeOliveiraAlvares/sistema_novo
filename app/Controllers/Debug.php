<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class Debug extends BaseController
{
    public function db()
    {
        try {
            $db = Database::connect();

            // Testa conexão básica
            $status = $db->connect() ? 'conectado' : 'não conectado';

            // Tenta rodar uma query simples
            $queryOk = false;
            $spotsCount = null;

            try {
                if ($db->tableExists('spots')) {
                    $spotsCount = $db->table('spots')->countAll();
                    $queryOk    = true;
                }
            } catch (\Throwable $e) {
                $queryOk = false;
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status_conexao' => $status,
                    'database'       => $db->getDatabase(),
                    'driver'         => $db->DBDriver,
                    'table_spots_existe' => $db->tableExists('spots'),
                    'spots_count_query_ok' => $queryOk,
                    'spots_count'    => $spotsCount,
                ]);
        } catch (DatabaseException $e) {
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'erro'   => 'Falha ao conectar no banco',
                    'mensagem' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'erro'   => 'Erro inesperado',
                    'mensagem' => $e->getMessage(),
                ]);
        }
    }
}


