<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\GarantiaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de categorias.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class GarantiaController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\GarantiaModel';
    protected $format    = 'json';

    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de garantias',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('garantias', $data);
    }

    /**
     * Lista todas as garantias cadastradas.
     * Método GET: /garantias/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todas as garantias ativas (não deletadas)
        $garantias = $this->model->where('g1_deleted_at', null)->findAll();

        $data = [];
        foreach ($garantias as $garantia) {
            $data[] = [
                'g1_id' => $garantia->g1_id,
                'g1_nome' => $garantia->g1_nome,
                'g1_data' => $garantia->g1_data,
                'g1_descricao' => $garantia->g1_descricao,
                'g1_observacao' => $garantia->g1_observacao,
                'g1_data_garantia' => $garantia->g1_data_garantia,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de uma categoria pelo ID.
     * Método GET: /categorias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null)
    {
        // Busca a garantia e ignora registros deletados (soft delete)
        $garantia = $this->model->where('g1_deleted_at', null)->find($id);

        if (!$garantia) {
            return $this->failNotFound('Garantia não encontrada');
        }

        // Normaliza e retorna apenas os campos necessários para a view
        $data = [
            'g1_id' => $garantia->g1_id,
            'g1_nome' => $garantia->g1_nome,
            'g1_data' => $garantia->g1_data,
            'g1_descricao' => $garantia->g1_descricao,
            'g1_observacao' => $garantia->g1_observacao,
            'g1_data_garantia' => $garantia->g1_data_garantia,
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

        // Mapear campos do frontend para os nomes da tabela
        $data = [
            'g1_nome' => $input['nome'] ?? $input['g1_nome'] ?? null,
            'g1_data' => $input['data'] ?? $input['g1_data'] ?? date('Y-m-d'),
            'g1_descricao' => $input['descricao'] ?? $input['g1_descricao'] ?? null,
            'g1_observacao' => $input['observacao'] ?? $input['g1_observacao'] ?? null,
            'g1_data_garantia' => $input['data_garantia'] ?? $input['g1_data_garantia'] ?? null,
        ];

        // Normalizar data_garantia (frontend datetime-local -> try parse and format 'Y-m-d H:i:s')
        if (!empty($data['g1_data_garantia'])) {
            $dg = str_replace('T', ' ', $data['g1_data_garantia']);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dg)) {
                $dg .= ':00';
            }
            $ts = strtotime($dg);
            if ($ts !== false) {
                $data['g1_data_garantia'] = date('Y-m-d H:i:s', $ts);
            } else {
                // leave as-is; validation will catch invalid formats
                $data['g1_data_garantia'] = $dg;
            }
        }

        // Tenta inserir usando as validações do model
        try {
            $id = $this->model->insert($data);

            if ($id === false) {
                // Retorna erros de validação do model
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $garantia = $this->model->find($id);
            return $this->respondCreated($garantia, 'Garantia criada com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de uma garantia existente.
     * Método PUT: /garantias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function update($id = null)
    {
        // Verificar se a garantia existe e não está deletada
        $garantia = $this->model->where('g1_deleted_at', null)->find($id);
        if (!$garantia) {
            return $this->failNotFound('Garantia não encontrada');
        }

        // Aceitar JSON ou form-data
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            // Fallbacks: raw input or post
            $raw = $request->getRawInput();
            $input = is_array($raw) && !empty($raw) ? $raw : $request->getPost();
        }

        // Mapear campos recebidos para os nomes da tabela
        $data = [
            'g1_nome' => $input['nome'] ?? $input['g1_nome'] ?? $garantia->g1_nome,
            'g1_data' => $input['data'] ?? $input['g1_data'] ?? $garantia->g1_data,
            'g1_descricao' => $input['descricao'] ?? $input['g1_descricao'] ?? $garantia->g1_descricao,
            'g1_observacao' => $input['observacao'] ?? $input['g1_observacao'] ?? $garantia->g1_observacao,
            'g1_data_garantia' => $input['data_garantia'] ?? $input['g1_data_garantia'] ?? $garantia->g1_data_garantia,
        ];

        // Normalizar data_garantia (frontend datetime-local -> try parse and format 'Y-m-d H:i:s')
        if (!empty($data['g1_data_garantia'])) {
            $dg = str_replace('T', ' ', $data['g1_data_garantia']);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dg)) {
                $dg .= ':00';
            }
            $ts = strtotime($dg);
            if ($ts !== false) {
                $data['g1_data_garantia'] = date('Y-m-d H:i:s', $ts);
            } else {
                $data['g1_data_garantia'] = $dg;
            }
        }

        try {
            $updated = $this->model->update($id, $data);

            if ($updated === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $garantia = $this->model->find($id);
            return $this->respondUpdated($garantia, 'Garantia atualizada com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui uma garantia pelo ID (soft delete quando o model estiver configurado).
     * Método DELETE: /garantias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null)
    {
        // Verificar se a garantia existe e não está deletada
        $garantia = $this->model->where('g1_deleted_at', null)->find($id);
        if (!$garantia) {
            return $this->failNotFound('Garantia não encontrada');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Garantia excluída com sucesso');
            }

            return $this->fail('Falha ao excluir garantia');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

}
