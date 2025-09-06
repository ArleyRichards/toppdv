<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\TecnicoModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de técnicos.
 */
class TecnicoController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\TecnicoModel';
    protected $format    = 'json';

    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de técnicos',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('tecnicos', $data);
    }

    /**
     * Lista todos os técnicos cadastrados.
     * Método GET: /tecnicos/list
     */
    public function list()
    {
        $request = service('request');

        $tecnicos = $this->model->where('t1_deleted_at', null)->findAll();

        $data = [];
        foreach ($tecnicos as $t) {
            $data[] = [
                't1_id' => $t->t1_id,
                't1_nome' => $t->t1_nome,
                't1_cpf' => $t->t1_cpf ?? null,
                't1_telefone' => $t->t1_telefone ?? null,
                't1_email' => $t->t1_email ?? null,
                't1_created_at' => $t->t1_created_at ?? null,
                't1_updated_at' => $t->t1_updated_at ?? null,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de um técnico pelo ID.
     */
    public function show($id = null)
    {
        $tecnico = $this->model->where('t1_deleted_at', null)->find($id);
        if (!$tecnico) {
            return $this->failNotFound('Técnico não encontrado');
        }

        $data = [
            't1_id' => $tecnico->t1_id,
            't1_nome' => $tecnico->t1_nome,
            't1_cpf' => $tecnico->t1_cpf ?? null,
            't1_telefone' => $tecnico->t1_telefone ?? null,
            't1_email' => $tecnico->t1_email ?? null,
            't1_observacao' => $tecnico->t1_observacao ?? null,
            't1_created_at' => $tecnico->t1_created_at ?? null,
            't1_updated_at' => $tecnico->t1_updated_at ?? null,
        ];

        return $this->respond($data);
    }

    /**
     * Cadastra um novo técnico.
     * Método POST: /tecnicos
     */
    public function create()
    {
        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            $input = $request->getPost() ?: [];
        }
        $data = (array) $input;

        try {
            $id = $this->model->insert($data);
            if ($id === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Falha ao criar técnico']);
            }

            $tecnico = $this->model->find($id);
            return $this->respondCreated($tecnico, 'Técnico criado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um técnico existente.
     * Método PUT: /tecnicos/{id}
     */
    public function update($id = null)
    {
        $tecnico = $this->model->find($id);
        if (!$tecnico) {
            return $this->failNotFound('Técnico não encontrado');
        }

        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();

        // Ajustar placeholders {t1_id} em quaisquer regras (ex: is_unique[...] with {t1_id})
        foreach ($rules as $k => $r) {
            if (is_string($r) && strpos($r, '{t1_id}') !== false) {
                $rules[$k] = str_replace('{t1_id}', $id, $r);
            }
        }

        // To avoid CodeIgniter placeholder issues, remove is_unique rule from t1_cpf
        // and perform a manual uniqueness check by comparing only digits.
        $rulesToValidate = $rules;
        if (isset($rulesToValidate['t1_cpf']) && is_string($rulesToValidate['t1_cpf'])) {
            // remove any is_unique[...] token
            $rulesToValidate['t1_cpf'] = preg_replace('/\|?is_unique\[[^\]]+\]\|?/', '|', $rulesToValidate['t1_cpf']);
            // collapse multiple pipes and trim
            $rulesToValidate['t1_cpf'] = preg_replace('/\|+/', '|', trim($rulesToValidate['t1_cpf'], '|'));
        }

        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            $raw = $request->getRawInput();
            $input = is_array($raw) && !empty($raw) ? $raw : $request->getPost();
        }
        $data = (array) $input;
        $data['t1_id'] = (int) $id; // para placeholders

        // Ensure the placeholder field used in rules (e.g. {t1_id}) has a validation rule.
        // CodeIgniter requires a rule for placeholder fields (see userguide).
        if (!isset($rulesToValidate['t1_id'])) {
            $rulesToValidate['t1_id'] = 'required|is_natural_no_zero';
        }

        // Manual uniqueness check for CPF (compare only digits) to replace is_unique behavior
        if (!empty($data['t1_cpf'])) {
            $inputCpfDigits = preg_replace('/\D/', '', $data['t1_cpf']);
            if ($inputCpfDigits) {
                $all = $this->model->where('t1_deleted_at', null)->findAll();
                foreach ($all as $other) {
                    if ((int)$other->t1_id === (int)$id) continue;
                    $otherDigits = preg_replace('/\D/', '', (string)$other->t1_cpf);
                    if ($otherDigits === $inputCpfDigits) {
                        return $this->failValidationErrors(['t1_cpf' => 'Este CPF já está cadastrado como técnico']);
                    }
                }
            }
        }

        if (!$this->validate($rulesToValidate, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $updated = $this->model->update($id, $data);
            if ($updated === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Falha ao atualizar técnico']);
            }

            $tecnico = $this->model->find($id);
            return $this->respondUpdated($tecnico, 'Técnico atualizado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um técnico pelo ID (soft delete quando o model estiver configurado).
     * Método DELETE: /tecnicos/{id}
     */
    public function delete($id = null)
    {
        $tecnico = $this->model->where('t1_deleted_at', null)->find($id);
        if (!$tecnico) {
            return $this->failNotFound('Técnico não encontrado');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Técnico excluído com sucesso');
            }

            return $this->fail('Falha ao excluir técnico');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }
}
