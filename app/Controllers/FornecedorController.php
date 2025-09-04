<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de categorias.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class FornecedorController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\FornecedorModel';
    protected $format    = 'json';

    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de fornecedores',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('fornecedores', $data);
    }

    /**
     * Lista todos os fornecedores cadastrados.
     * Método GET: /fornecedores/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todos os fornecedores ativos (não deletados)
        $fornecedores = $this->model->where('f1_deleted_at', null)->findAll();

        $data = [];
        foreach ($fornecedores as $f) {
            $data[] = [
                'f1_id' => $f->f1_id,
                'f1_razao_social' => $f->f1_razao_social,
                'f1_nome_fantasia' => $f->f1_nome_fantasia,
                'f1_cnpj' => $f->f1_cnpj,
                'f1_cep' => $f->f1_cep,
                'f1_cidade' => $f->f1_cidade,
                'f1_uf' => $f->f1_uf,
                'f1_endereco' => $f->f1_endereco,
                'f1_bairro' => $f->f1_bairro,
                'f1_complemento' => $f->f1_complemento,
                'f1_numero' => $f->f1_numero,
                'f1_ponto_referencia' => $f->f1_ponto_referencia,
                'f1_telefone' => $f->f1_telefone,
                'f1_celular' => $f->f1_celular,
                'f1_email' => $f->f1_email,
                'f1_created_at' => $f->f1_created_at,
                'f1_updated_at' => $f->f1_updated_at,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de um fornecedor pelo ID.
     * Método GET: /fornecedores/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null)
    {
        // Busca o fornecedor e ignora registros deletados (soft delete)
        $fornecedor = $this->model->where('f1_deleted_at', null)->find($id);

        if (!$fornecedor) {
            return $this->failNotFound('Fornecedor não encontrado');
        }

        // Retorna os campos relevantes da tabela f1_fornecedores
        $data = [
            'f1_id' => $fornecedor->f1_id,
            'f1_razao_social' => $fornecedor->f1_razao_social,
            'f1_nome_fantasia' => $fornecedor->f1_nome_fantasia,
            'f1_cnpj' => $fornecedor->f1_cnpj,
            'f1_cep' => $fornecedor->f1_cep,
            'f1_endereco' => $fornecedor->f1_endereco,
            'f1_bairro' => $fornecedor->f1_bairro,
            'f1_complemento' => $fornecedor->f1_complemento,
            'f1_numero' => $fornecedor->f1_numero,
            'f1_ponto_referencia' => $fornecedor->f1_ponto_referencia,
            'f1_telefone' => $fornecedor->f1_telefone,
            'f1_celular' => $fornecedor->f1_celular,
            'f1_email' => $fornecedor->f1_email,
            'f1_cidade' => $fornecedor->f1_cidade,
            'f1_uf' => $fornecedor->f1_uf,
            'f1_created_at' => $fornecedor->f1_created_at,
            'f1_updated_at' => $fornecedor->f1_updated_at,
        ];

        return $this->respond($data);
    }

    /**
     * Cadastra um novo fornecedor.
     * Método POST: /fornecedores
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

        // Mapear campos do frontend (nomes do modal) para os nomes da tabela f1_*
        $data = [
            'f1_razao_social'   => $input['razao_social']   ?? $input['f1_razao_social']   ?? null,
            'f1_nome_fantasia'  => $input['nome_fantasia']  ?? $input['f1_nome_fantasia']  ?? null,
            'f1_cnpj'           => $input['cnpj']           ?? $input['f1_cnpj']           ?? null,
            'f1_cep'            => $input['cep']            ?? $input['f1_cep']            ?? null,
            'f1_cidade'         => $input['cidade']         ?? $input['f1_cidade']         ?? null,
            'f1_uf'             => $input['uf']             ?? $input['f1_uf']             ?? null,
            'f1_endereco'       => $input['endereco']       ?? $input['f1_endereco']       ?? null,
            'f1_bairro'         => $input['bairro']         ?? $input['f1_bairro']         ?? null,
            'f1_complemento'    => $input['complemento']    ?? $input['f1_complemento']    ?? null,
            'f1_numero'         => $input['numero']         ?? $input['f1_numero']         ?? null,
            'f1_ponto_referencia'=> $input['ponto_referencia'] ?? $input['f1_ponto_referencia'] ?? null,
            'f1_telefone'       => $input['telefone']       ?? $input['f1_telefone']       ?? null,
            'f1_celular'        => $input['celular']        ?? $input['f1_celular']        ?? null,
            'f1_email'          => $input['email']          ?? $input['f1_email']          ?? null,
        ];

        // Sanitizar campos: remover caracteres não numéricos de CNPJ, CEP e telefones
        if (!empty($data['f1_cnpj'])) {
            $clean = preg_replace('/\D/', '', $data['f1_cnpj']);
            // formatar CNPJ com pontuação antes de salvar
            $data['f1_cnpj'] = $this->formatCnpj($clean);
        }
        if (!empty($data['f1_cep'])) {
            $data['f1_cep'] = preg_replace('/\D/', '', $data['f1_cep']);
        }
        if (!empty($data['f1_telefone'])) {
            $data['f1_telefone'] = preg_replace('/\D/', '', $data['f1_telefone']);
        }
        if (!empty($data['f1_celular'])) {
            $data['f1_celular'] = preg_replace('/\D/', '', $data['f1_celular']);
        }

        // Tenta inserir usando as validações do model
        try {
            // DEBUG: log the data state before insert to inspect CNPJ formatting
            if (function_exists('log_message')) {
                log_message('debug', '[FornecedorController::create] before insert f1_cnpj = ' . ($data['f1_cnpj'] ?? ''));
            }

            $id = $this->model->insert($data);

            if ($id === false) {
                // Retorna erros de validação do model
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $fornecedor = $this->model->find($id);
            if (function_exists('log_message')) {
                log_message('debug', '[FornecedorController::create] after insert f1_cnpj = ' . ($fornecedor->f1_cnpj ?? ''));
            }
            return $this->respondCreated($fornecedor, 'Fornecedor criado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um fornecedor.
     * Método PUT: /fornecedores/{id}
     * @param int $id
     */
    public function update($id = null)
    {
        // Verificar se o fornecedor existe e não está deletado
        $fornecedor = $this->model->where('f1_deleted_at', null)->find($id);
        if (!$fornecedor) {
            return $this->failNotFound('Fornecedor não encontrado');
        }

        // Aceitar JSON ou form-data
        $request = service('request');
        $input = $request->getJSON(true);
        if (empty($input)) {
            // Fallbacks: raw input or post
            $raw = $request->getRawInput();
            $input = is_array($raw) && !empty($raw) ? $raw : $request->getPost();
        }

        // Mapear campos recebidos para os nomes da tabela f1_*
        $data = [
            'f1_razao_social'    => $input['razao_social']   ?? $input['f1_razao_social']   ?? $fornecedor->f1_razao_social,
            'f1_nome_fantasia'   => $input['nome_fantasia']  ?? $input['f1_nome_fantasia']  ?? $fornecedor->f1_nome_fantasia,
            'f1_cnpj'            => $input['cnpj']           ?? $input['f1_cnpj']           ?? $fornecedor->f1_cnpj,
            'f1_cep'             => $input['cep']            ?? $input['f1_cep']            ?? $fornecedor->f1_cep,
            'f1_cidade'          => $input['cidade']         ?? $input['f1_cidade']         ?? $fornecedor->f1_cidade,
            'f1_uf'              => $input['uf']             ?? $input['f1_uf']             ?? $fornecedor->f1_uf,
            'f1_endereco'        => $input['endereco']       ?? $input['f1_endereco']       ?? $fornecedor->f1_endereco,
            'f1_bairro'          => $input['bairro']         ?? $input['f1_bairro']         ?? $fornecedor->f1_bairro,
            'f1_complemento'     => $input['complemento']    ?? $input['f1_complemento']    ?? $fornecedor->f1_complemento,
            'f1_numero'          => $input['numero']         ?? $input['f1_numero']         ?? $fornecedor->f1_numero,
            'f1_ponto_referencia'=> $input['ponto_referencia'] ?? $input['f1_ponto_referencia'] ?? $fornecedor->f1_ponto_referencia,
            'f1_telefone'        => $input['telefone']       ?? $input['f1_telefone']       ?? $fornecedor->f1_telefone,
            'f1_celular'         => $input['celular']        ?? $input['f1_celular']        ?? $fornecedor->f1_celular,
            'f1_email'           => $input['email']          ?? $input['f1_email']          ?? $fornecedor->f1_email,
        ];

        // Sanitizar campos numéricos
        if (!empty($data['f1_cnpj'])) {
            $clean = preg_replace('/\D/', '', $data['f1_cnpj']);
            $data['f1_cnpj'] = $this->formatCnpj($clean);
        }
        if (!empty($data['f1_cep'])) {
            $data['f1_cep'] = preg_replace('/\D/', '', $data['f1_cep']);
        }
        if (!empty($data['f1_telefone'])) {
            $data['f1_telefone'] = preg_replace('/\D/', '', $data['f1_telefone']);
        }
        if (!empty($data['f1_celular'])) {
            $data['f1_celular'] = preg_replace('/\D/', '', $data['f1_celular']);
        }

        try {
            // DEBUG: log the data state before update to inspect CNPJ formatting
            if (function_exists('log_message')) {
                log_message('debug', '[FornecedorController::update] before update payload f1_cnpj = ' . ($data['f1_cnpj'] ?? ''));
            }

            $updated = $this->model->update($id, $data);

            if ($updated === false) {
                $errors = $this->model->errors();
                return $this->failValidationErrors($errors ?: ['error' => 'Dados inválidos']);
            }

            $fornecedor = $this->model->find($id);
            if (function_exists('log_message')) {
                log_message('debug', '[FornecedorController::update] after update f1_cnpj = ' . ($fornecedor->f1_cnpj ?? ''));
            }
            return $this->respondUpdated($fornecedor, 'Fornecedor atualizado com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um fornecedor pelo ID (soft delete quando o model estiver configurado).
     * Método DELETE: /fornecedores/{id}
     * @param int $id
     */
    public function delete($id = null)
    {
        // Verificar se o fornecedor existe e não está deletado
        $fornecedor = $this->model->where('f1_deleted_at', null)->find($id);
        if (!$fornecedor) {
            return $this->failNotFound('Fornecedor não encontrado');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Fornecedor excluído com sucesso');
            }

            return $this->fail('Falha ao excluir fornecedor');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Formata uma string numérica de CNPJ (somente dígitos) para máscara 00.000.000/0000-00
     * Se a entrada não tiver 14 dígitos retorna a entrada original.
     * @param string $digits
     * @return string
     */
    private function formatCnpj(string $digits): string
    {
        $only = preg_replace('/\D/', '', $digits);
        if (strlen($only) !== 14) return $digits;
        return sprintf('%s.%s.%s/%s-%s', substr($only,0,2), substr($only,2,3), substr($only,5,3), substr($only,8,4), substr($only,12,2));
    }

    

}
