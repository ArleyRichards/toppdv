<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de serviços.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class ServicoController extends ResourceController
{

    use ResponseTrait;

    protected $modelName = 'App\Models\ServicoModel';
    protected $format    = 'json';
    
    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de serviços',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('servicos', $data);
    }

    /**
     * Lista todos os serviços cadastrados.
     * Método GET: /servicos/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todos os serviços ativos (não deletados)
        $servicos = $this->model->where('s1_deleted_at', null)->findAll();

        $data = [];
        foreach ($servicos as $servico) {
            $data[] = [
                's1_id' => $servico->s1_id,
                's1_codigo_servico' => $servico->s1_codigo_servico ?? null,
                's1_nome_servico' => $servico->s1_nome_servico ?? null,
                's1_descricao' => $servico->s1_descricao ?? null,
                's1_valor' => isset($servico->s1_valor) ? (float)$servico->s1_valor : 0.00,
                's1_tempo_medio' => isset($servico->s1_tempo_medio) ? (int)$servico->s1_tempo_medio : null,
                's1_categoria' => $servico->s1_categoria ?? null,
                's1_garantia' => isset($servico->s1_garantia) ? (int)$servico->s1_garantia : 0,
                's1_status' => $servico->s1_status ?? 'Ativo',
                's1_created_at' => $servico->s1_created_at ?? null,
                's1_updated_at' => $servico->s1_updated_at ?? null,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de uma categoria pelo ID.
     * Retorna os dados de um serviço pelo ID.
     * Método GET: /servico/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null)
    {
        // Busca o serviço, ignorando registros soft-deleted (s1_deleted_at)
        if (empty($id)) {
            return $this->failNotFound('Serviço não encontrado');
        }

        $servico = $this->model->where('s1_deleted_at', null)->find($id);

        if (!$servico) {
            return $this->failNotFound('Serviço não encontrado');
        }

        // Normaliza e retorna apenas os campos necessários para a view
        $data = [
            's1_id' => $servico->s1_id,
            's1_codigo_servico' => $servico->s1_codigo_servico ?? null,
            's1_nome_servico' => $servico->s1_nome_servico ?? null,
            's1_descricao' => $servico->s1_descricao ?? null,
            's1_valor' => isset($servico->s1_valor) ? (float)$servico->s1_valor : 0.00,
            's1_tempo_medio' => isset($servico->s1_tempo_medio) ? (int)$servico->s1_tempo_medio : null,
            's1_categoria' => $servico->s1_categoria ?? null,
            's1_garantia' => isset($servico->s1_garantia) ? (int)$servico->s1_garantia : 0,
            's1_status' => $servico->s1_status ?? 'Ativo',
            's1_created_at' => $servico->s1_created_at ?? null,
            's1_updated_at' => $servico->s1_updated_at ?? null,
        ];

        return $this->respond($data);
    }

    /**
     * Cadastra uma nova categoria.
     * Método POST: /categorias
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()
    {
        // Aceita JSON ou form-data
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            $input = $request->getPost();
        }

        // Mapear campos do frontend para os nomes da tabela s1_servicos
        $data = [
            's1_codigo_servico' => $input['codigo'] ?? $input['s1_codigo_servico'] ?? null,
            's1_nome_servico' => $input['nome'] ?? $input['s1_nome_servico'] ?? null,
            's1_descricao' => $input['descricao'] ?? $input['s1_descricao'] ?? null,
            's1_valor' => isset($input['valor']) ? $input['valor'] : ($input['s1_valor'] ?? 0.00),
            's1_tempo_medio' => isset($input['tempo_medio']) ? $input['tempo_medio'] : ($input['s1_tempo_medio'] ?? null),
            's1_categoria' => $input['categoria'] ?? $input['s1_categoria'] ?? null,
            's1_garantia' => isset($input['garantia']) ? $input['garantia'] : ($input['s1_garantia'] ?? 0),
            's1_status' => $input['status'] ?? $input['s1_status'] ?? 'Ativo',
            's1_created_at' => date('Y-m-d H:i:s')
        ];

        // Limpeza e cast de campos numéricos
        $data['s1_valor'] = $data['s1_valor'] !== null ? preg_replace('/[^0-9\.,-]/', '', (string)$data['s1_valor']) : '0.00';
        // Substituir vírgula por ponto e forçar formato numérico
        $data['s1_valor'] = (float) str_replace(',', '.', $data['s1_valor']);

        if (!empty($data['s1_tempo_medio'])) {
            $data['s1_tempo_medio'] = (int) $data['s1_tempo_medio'];
        } else {
            $data['s1_tempo_medio'] = null;
        }

        $data['s1_garantia'] = isset($data['s1_garantia']) ? (int) $data['s1_garantia'] : 0;

        // Se não veio um código, gere um simples (SVC + timestamp) para manter unicidade
        if (empty($data['s1_codigo_servico'])) {
            $data['s1_codigo_servico'] = 'SVC' . time();
        }

        // Normaliza status para os valores permitidos
        $data['s1_status'] = in_array($data['s1_status'], ['Ativo', 'Inativo']) ? $data['s1_status'] : 'Ativo';

        // Tenta inserir usando as validações do model
        try {
            $id = $this->model->insert($data);

            if ($id === false) {
                // Retorna erros de validação do model
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $servico = $this->model->find($id);
            return $this->respondCreated($servico, 'Serviço criado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de uma servico existente.
     * Método PUT: /servicos/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function update($id = null)
    {
        // Verificar se o serviço existe e não está deletado
        if (empty($id)) {
            return $this->failNotFound('Serviço não encontrado');
        }

        $servico = $this->model->where('s1_deleted_at', null)->find($id);
        if (!$servico) {
            return $this->failNotFound('Serviço não encontrado');
        }

        // Aceitar JSON ou form-data
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            $raw = $request->getRawInput();
            $input = is_array($raw) && !empty($raw) ? $raw : $request->getPost();
        }

        // Mapear campos recebidos para os nomes da tabela s1_
        $data = [
            's1_codigo_servico' => $input['codigo'] ?? $input['s1_codigo_servico'] ?? $servico->s1_codigo_servico,
            's1_nome_servico' => $input['nome'] ?? $input['s1_nome_servico'] ?? $servico->s1_nome_servico,
            's1_descricao' => $input['descricao'] ?? $input['s1_descricao'] ?? $servico->s1_descricao,
            's1_valor' => isset($input['valor']) ? $input['valor'] : ($input['s1_valor'] ?? $servico->s1_valor),
            's1_tempo_medio' => isset($input['tempo_medio']) ? $input['tempo_medio'] : ($input['s1_tempo_medio'] ?? $servico->s1_tempo_medio),
            's1_categoria' => $input['categoria'] ?? $input['s1_categoria'] ?? $servico->s1_categoria,
            's1_garantia' => isset($input['garantia']) ? $input['garantia'] : ($input['s1_garantia'] ?? $servico->s1_garantia),
            's1_status' => $input['status'] ?? $input['s1_status'] ?? $servico->s1_status,
            's1_updated_at' => date('Y-m-d H:i:s')
        ];

        // Limpeza e cast de campos numéricos
        $data['s1_valor'] = $data['s1_valor'] !== null ? preg_replace('/[^0-9\.,-]/', '', (string)$data['s1_valor']) : '0.00';
        $data['s1_valor'] = (float) str_replace(',', '.', $data['s1_valor']);

        if (!empty($data['s1_tempo_medio'])) {
            $data['s1_tempo_medio'] = (int) $data['s1_tempo_medio'];
        } else {
            $data['s1_tempo_medio'] = null;
        }

        $data['s1_garantia'] = isset($data['s1_garantia']) ? (int) $data['s1_garantia'] : 0;

        // Normaliza status
        $data['s1_status'] = in_array($data['s1_status'], ['Ativo', 'Inativo']) ? $data['s1_status'] : ($servico->s1_status ?? 'Ativo');

        try {
            $updated = $this->model->update($id, $data);

            if ($updated === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $servico = $this->model->find($id);
            return $this->respondUpdated($servico, 'Serviço atualizado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui uma servico pelo ID (soft delete quando o model estiver configurado).
     * Método DELETE: /servicos/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null)
    {
        // Verificar se o serviço existe e não está deletado
        if (empty($id)) {
            return $this->failNotFound('Serviço não encontrado');
        }

        $servico = $this->model->where('s1_deleted_at', null)->find($id);
        if (!$servico) {
            return $this->failNotFound('Serviço não encontrado');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Serviço excluído com sucesso');
            }

            return $this->fail('Falha ao excluir serviço');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }
}
