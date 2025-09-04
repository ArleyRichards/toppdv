<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use CodeIgniter\RESTful\ResourceController;

class LicencaController extends ResourceController
{
    protected $format = 'html';

    public function index()
    {
        // Preparar variáveis
        $licenses = [];
        $userLogged = null;

        // Obter usuário logado pelas chaves definidas no AuthController
        $session = session();
        $userId = $session->get('user_id');
        if ($userId) {
            // Construir um objeto leve para a view
            $userLogged = (object)[
                'u1_id' => $userId,
                'u1_usuario_acesso' => $session->get('user_usuario') ?? $session->get('user_usuario') ?? $session->get('user_nome') ?? null
            ];
        }

        // Usar o model de licenças para buscar por usuário
        if (class_exists('\App\\Models\\LicencaModel')) {
            $licencaModel = new \App\Models\LicencaModel();
            if ($userId) {
                $licenses = $licencaModel->buscarPorUsuario($userId);
            } else {
                // fallback: listar todas as licenças
                $licenses = $licencaModel->findAll();
            }
        }

        $data = [
            'title' => 'Licença',
            'result' => $licenses,
            'user_logged' => $userLogged,
            'appName' => ConfigHelper::appName(),
        ];

        return view('licenca', $data);
    }
}
