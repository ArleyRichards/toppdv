<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de usuários.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class UsuarioController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UsuarioModel';
    protected $format    = 'json';

    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de usuários',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('usuarios', $data);
    }

    /**
     * Lista todos os usuários cadastrados.
     * Método GET: /usuarios/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        // Permitir requisições GET
        $request = service('request');
        // Retorna todos os usuários ativos (não deletados)
        $users = $this->model->where('u1_deleted_at', null)->findAll();

        $data = [];
        foreach ($users as $u) {
            $data[] = [
                'u1_id' => $u->u1_id,
                'u1_cpf' => $u->u1_cpf,
                'u1_nome' => $u->u1_nome,
                'u1_email' => $u->u1_email,
                'u1_usuario_acesso' => $u->u1_usuario_acesso,
                'u1_tipo_permissao' => $u->u1_tipo_permissao,
                'u1_data_ultimo_acesso' => $u->u1_data_ultimo_acesso,
                'u1_created_at' => $u->u1_created_at ?? null,
                'u1_updated_at' => $u->u1_updated_at ?? null,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de um usuário pelo ID.
     * Método GET: /usuarios/{id}
     * @param int $id
     * @return json
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null)
    {
        // Busca o usuário e ignora registros deletados (soft delete)
        $user = $this->model->where('u1_deleted_at', null)->find($id);

        if (!$user) {
            return $this->failNotFound('Usuário não encontrado');
        }

        // Normaliza e retorna apenas os campos necessários para a view
        $data = [
            'u1_id' => $user->u1_id,
            'u1_cpf' => $user->u1_cpf,
            'u1_nome' => $user->u1_nome,
            'u1_email' => $user->u1_email,
            'u1_usuario_acesso' => $user->u1_usuario_acesso,
            'u1_tipo_permissao' => $user->u1_tipo_permissao,
            'u1_data_ultimo_acesso' => $user->u1_data_ultimo_acesso ?? null,
            'u1_created_at' => $user->u1_created_at ?? null,
            'u1_updated_at' => $user->u1_updated_at ?? null,
        ];

        return $this->respond($data);
    }

    /**
     * Cadastra um novo usuário.
     * Método POST: /usuarios
     * Aceita JSON ou form-data com os campos: u1_cpf, u1_nome, u1_email, u1_usuario_acesso, u1_senha_usuario, u1_tipo_permissao
     * @return json
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()
    {
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            $input = $request->getPost();
        }

        // Mapear e sanitizar campos esperados
        $cpf = $input['u1_cpf'] ?? $input['cpf'] ?? null;
        // $cpf = $cpf ? preg_replace('/[^0-9]/', '', $cpf) : null;

        $data = [
            'u1_cpf' => $cpf,
            'u1_nome' => $input['u1_nome'] ?? $input['nome'] ?? null,
            'u1_email' => $input['u1_email'] ?? $input['email'] ?? null,
            'u1_usuario_acesso' => $input['u1_usuario_acesso'] ?? $input['usuario'] ?? null,
            'u1_senha_usuario' => $input['u1_senha_usuario'] ?? $input['senha'] ?? null,
            'u1_tipo_permissao' => $input['u1_tipo_permissao'] ?? $input['tipo_permissao'] ?? 'usuario',
        ];

        // Normalizar tipo de permissão para lowercase
        if (!empty($data['u1_tipo_permissao'])) {
            $data['u1_tipo_permissao'] = strtolower($data['u1_tipo_permissao']);
        }

        // Verificações manuais de unicidade (robusta contra diferentes formatações de CPF)
        // Checamos CPF comparando a versão limpa (somente dígitos) com os valores no banco também limpos via REPLACE
        if (!empty($data['u1_cpf'])) {
            $cpfClean = preg_replace('/[^0-9]/', '', $data['u1_cpf']);
            $db = $this->model->db;
            $escaped = $db->escape($cpfClean);
            $exists = $this->model->where('u1_deleted_at', null)
                ->where("REPLACE(REPLACE(REPLACE(u1_cpf, '.', ''), '-', ''), ' ', '') = $escaped")
                ->first();
            if ($exists) {
                return $this->failValidationErrors(['u1_cpf' => 'CPF já cadastrado']);
            }
        }

        if (!empty($data['u1_email'])) {
            $email = trim($data['u1_email']);
            $db = $this->model->db;
            $escapedEmail = $db->escape(mb_strtolower($email));
            $existsEmail = $this->model->where('u1_deleted_at', null)
                ->where("LOWER(u1_email) = $escapedEmail")
                ->first();
            if ($existsEmail) {
                return $this->failValidationErrors(['u1_email' => 'Email já cadastrado']);
            }
        }

        // Verificar unicidade do nome de usuário (login)
        if (!empty($data['u1_usuario_acesso'])) {
            $usuario = trim($data['u1_usuario_acesso']);
            $db = $this->model->db;
            $escapedUser = $db->escape(mb_strtolower($usuario));
            $existsUser = $this->model->where('u1_deleted_at', null)
                ->where("LOWER(u1_usuario_acesso) = $escapedUser")
                ->first();
            if ($existsUser) {
                return $this->failValidationErrors(['u1_usuario_acesso' => 'Este nome de usuário já está em uso']);
            }
        }

        // Tenta inserir usando as validações do model
        try {
            $id = $this->model->insert($data);

            if ($id === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $user = $this->model->find($id);
            return $this->respondCreated($user, 'Usuário criado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um usuário existente.
     * Método PUT: /usuarios/{id}
     * Aceita JSON ou form-data com campos u1_* (u1_nome, u1_cpf, u1_email, u1_usuario_acesso, u1_senha_usuario, u1_tipo_permissao)
     * @param int $id
     * @return json
     */
    public function update($id = null)
    {
        // Verificar se o usuário existe e não está deletado
        $user = $this->model->where('u1_deleted_at', null)->find($id);
        if (!$user) {
            return $this->failNotFound('Usuário não encontrado');
        }

        // Aceitar JSON ou form-data
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            // Fallbacks: raw input ou post
            $raw = $request->getRawInput();
            $input = is_array($raw) && !empty($raw) ? $raw : $request->getPost();
        }

        // Sanitize CPF if provided
        $cpf = $input['u1_cpf'] ?? $input['cpf'] ?? null;
        // if (!empty($cpf)) {
            // $cpf = preg_replace('/[^0-9]/', '', $cpf);
        // }

        // Construir payload respeitando valores atuais quando ausentes
        $data = [
            'u1_cpf' => $cpf ?? $user->u1_cpf,
            'u1_nome' => $input['u1_nome'] ?? $input['nome'] ?? $user->u1_nome,
            'u1_email' => $input['u1_email'] ?? $input['email'] ?? $user->u1_email,
            'u1_usuario_acesso' => $input['u1_usuario_acesso'] ?? $input['usuario'] ?? $user->u1_usuario_acesso,
            'u1_tipo_permissao' => isset($input['u1_tipo_permissao']) ? strtolower($input['u1_tipo_permissao']) : ($user->u1_tipo_permissao ?? 'usuario'),
        ];

        // Atualizar senha somente se fornecida e não vazia
        $senha = $input['u1_senha_usuario'] ?? $input['senha'] ?? null;
        if (!empty($senha)) {
            $data['u1_senha_usuario'] = $senha;
        }

        // Avoid unique validation failures when the value didn't change:
        // if cpf or email in payload equals the current user's value, remove it so the model won't treat it as duplicate
        if (isset($data['u1_cpf']) && $data['u1_cpf'] !== null) {
            // If the provided CPF equals stored CPF (potentially with same formatting) keep it out of update
            if ($data['u1_cpf'] === $user->u1_cpf) {
                unset($data['u1_cpf']);
            } else {
                // Check uniqueness against other users using cleaned comparison
                $cpfClean = preg_replace('/[^0-9]/', '', $data['u1_cpf']);
                $db = $this->model->db;
                $escaped = $db->escape($cpfClean);
                $exists = $this->model->where('u1_deleted_at', null)
                    ->where("REPLACE(REPLACE(REPLACE(u1_cpf, '.', ''), '-', ''), ' ', '') = $escaped")
                    ->where('u1_id <>', $id)
                    ->first();
                if ($exists) {
                    return $this->failValidationErrors(['u1_cpf' => 'CPF já cadastrado']);
                }
            }
        }

        if (isset($data['u1_email']) && $data['u1_email'] !== null) {
            $currentEmail = $user->u1_email ?? '';
            if (mb_strtolower($data['u1_email']) === mb_strtolower($currentEmail)) {
                unset($data['u1_email']);
            } else {
                // uniqueness check for email ignoring case and current id
                $db = $this->model->db;
                $escapedEmail = $db->escape(mb_strtolower($data['u1_email']));
                $existsEmail = $this->model->where('u1_deleted_at', null)
                    ->where("LOWER(u1_email) = $escapedEmail")
                    ->where('u1_id <>', $id)
                    ->first();
                if ($existsEmail) {
                    return $this->failValidationErrors(['u1_email' => 'Email já cadastrado']);
                }
            }
        }

        // username uniqueness handling for update
        if (isset($data['u1_usuario_acesso']) && $data['u1_usuario_acesso'] !== null) {
            $currentUsuario = $user->u1_usuario_acesso ?? '';
            if (mb_strtolower($data['u1_usuario_acesso']) === mb_strtolower($currentUsuario)) {
                unset($data['u1_usuario_acesso']);
            } else {
                $db = $this->model->db;
                $escapedUser = $db->escape(mb_strtolower($data['u1_usuario_acesso']));
                $existsUser = $this->model->where('u1_deleted_at', null)
                    ->where("LOWER(u1_usuario_acesso) = $escapedUser")
                    ->where('u1_id <>', $id)
                    ->first();
                if ($existsUser) {
                    return $this->failValidationErrors(['u1_usuario_acesso' => 'Este nome de usuário já está em uso']);
                }
            }
        }

        try {
            $updated = $this->model->update($id, $data);

            if ($updated === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $user = $this->model->find($id);
            return $this->respondUpdated($user, 'Usuário atualizado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um usuario pelo ID (soft delete quando o model estiver configurado).
     * Método DELETE: /usuarios/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null)
    {
        // Verificar se o usuário existe e não está deletado (soft delete)
        $user = $this->model->where('u1_deleted_at', null)->find($id);
        if (!$user) {
            return $this->failNotFound('Usuário não encontrado');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Usuário excluído com sucesso');
            }

            return $this->fail('Falha ao excluir usuário');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

}
