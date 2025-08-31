<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class ClienteController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\ClienteModel';
    protected $format    = 'json';

    public function __construct()
    {
        helper(['form', 'url']);
    }

        /**
         * Controller responsável pelas operações de clientes.
         * @author Arley Richards <arleyrichards@gmail.com>
         */

    /**
     * Retorna a view principal de gerenciamento de clientes
     */
    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de Clientes',
            'situacoes' => [
                ClienteModel::SITUACAO_ATIVO,
                ClienteModel::SITUACAO_INATIVO,
                ClienteModel::SITUACAO_PENDENTE,
                ClienteModel::SITUACAO_BLOQUEADO
            ]
        ];

        return view('clientes', $data);
    }

    /**
     * Retorna todos os clientes (para DataTables)
     */
    public function list()
        /**
         * Lista todos os clientes cadastrados.
         * Método GET: /clientes/list
         * @return 
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todos os clientes ativos (não deletados)
        $clientes = $this->model->where('c2_deleted_at', null)->findAll();

        $data = [];
        foreach ($clientes as $cliente) {
            $data[] = [
                'c2_id' => $cliente->c2_id,
                'c2_nome' => $cliente->c2_nome,
                'c2_data_nascimento' => $cliente->c2_data_nascimento,
                'c2_idade' => $this->calcularIdade($cliente->c2_data_nascimento),
                'c2_cpf' => $this->formatarCpf($cliente->c2_cpf),
                'c2_email' => $cliente->c2_email,
                'c2_celular' => $this->formatarTelefone($cliente->c2_celular),
                'c2_cidade' => $cliente->c2_cidade,
                'c2_uf' => $cliente->c2_uf,
                'c2_situacao' => $cliente->c2_situacao
            ];
        }

        return $this->respond($data);
    }

    function calcularIdade($dataNascimento)
    {
        $dataNascimento = new \DateTime($dataNascimento);
        $hoje = new \DateTime();
        $idade = $hoje->diff($dataNascimento)->y;
        return $idade;
    }

    /**
     * Retorna um cliente específico
     */
    public function show($id = null)
        /**
         * Retorna os dados de um cliente pelo ID.
         * Método GET: /clientes/{id}
         * @param int $id
         * @return 
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    {
        $cliente = $this->model->find($id);
        
        if ($cliente) {
            // Formatar dados para exibição
            $cliente->c2_cpf = $this->formatarCpf($cliente->c2_cpf);
            $cliente->c2_telefone = $this->formatarTelefone($cliente->c2_telefone);
            $cliente->c2_celular = $this->formatarTelefone($cliente->c2_celular);
            
            return $this->respond($cliente);
        }
        
        return $this->failNotFound('Cliente não encontrado');
    }

    /**
     * Cria um novo cliente
     */
    public function create()
        /**
         * Cadastra um novo cliente.
         * Método POST: /clientes
         * @return 
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    {
        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();
        
        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $data = (array) $this->request->getJSON();
        
        try {
            $id = $this->model->insert($data);
            
            if ($id) {
                $cliente = $this->model->find($id);
                return $this->respondCreated($cliente, 'Cliente criado com sucesso');
            }
            
            return $this->fail('Falha ao criar cliente');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza um cliente existente
     */
    public function update($id = null)
        /**
         * Atualiza os dados de um cliente existente.
         * Método PUT: /clientes/{id}
         * @param int $id
         * @return 
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    {
        // Verificar se o cliente existe
        $cliente = $this->model->find($id);
        if (!$cliente) {
            return $this->failNotFound('Cliente não encontrado');
        }
        
        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();
        
        // Remover regra unique para o próprio registro na atualização
        if (isset($rules['c2_cpf'])) {
            $rules['c2_cpf'] = str_replace(
                '{c2_id}', 
                $id, 
                $rules['c2_cpf']
            );
        }
        
        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $data = (array) $this->request->getJSON();
        
        try {
            if ($this->model->update($id, $data)) {
                $cliente = $this->model->find($id);
                return $this->respondUpdated($cliente, 'Cliente atualizado com sucesso');
            }
            
            return $this->fail('Falha ao atualizar cliente');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um cliente (soft delete)
     */
    public function delete($id = null)
        /**
         * Exclui um cliente pelo ID.
         * Método DELETE: /clientes/{id}
         * @param int $id
         * @return 
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    {
        // Verificar se o cliente existe
        $cliente = $this->model->find($id);
        if (!$cliente) {
            return $this->failNotFound('Cliente não encontrado');
        }
        
        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Cliente excluído com sucesso');
            }
            
            return $this->fail('Falha ao excluir cliente');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Busca clientes por termo (para autocomplete)
     */
    public function buscar()
    {
        $termo = $this->request->getVar('term');
        
        if (empty($termo)) {
            return $this->respond([]);
        }
        
        $clientes = $this->model->buscarPorNome($termo, 10);
        
        $resultados = [];
        foreach ($clientes as $cliente) {
            $resultados[] = [
                'id' => $cliente->c2_id,
                'text' => "{$cliente->c2_nome} - {$this->formatarCpf($cliente->c2_cpf)}"
            ];
        }
        
        return $this->respond($resultados);
    }

    /**
     * Busca informações de CEP via API
     */
    public function consultarCep()
    {
        $cep = $this->request->getVar('cep');
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return $this->fail('CEP inválido');
        }
        
        // Consulta à API ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $endereco = json_decode($response);
            
            if (isset($endereco->erro)) {
                return $this->fail('CEP não encontrado');
            }
            
            return $this->respond([
                'c2_cep' => $cep,
                'c2_endereco' => $endereco->logradouro,
                'c2_bairro' => $endereco->bairro,
                'c2_cidade' => $endereco->localidade,
                'c2_uf' => $endereco->uf
            ]);
        }
        
        return $this->failServerError('Erro ao consultar CEP');
    }

    /**
     * Retorna estatísticas de clientes
     */
    public function estatisticas()
    {
        $estatisticas = $this->model->getEstatisticas();
        return $this->respond($estatisticas);
    }

    /**
     * Formata CPF para exibição
     */
    private function formatarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }
        
        return $cpf;
    }

    /**
     * Formata telefone para exibição
     */
    private function formatarTelefone($telefone)
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        if (strlen($telefone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif (strlen($telefone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
        
        return $telefone;
    }

    /**
     * Retorna badge HTML para situação
     */
    private function getBadgeSituacao($situacao)
    {
        $classes = [
            ClienteModel::SITUACAO_ATIVO => 'bg-success',
            ClienteModel::SITUACAO_INATIVO => 'bg-secondary',
            ClienteModel::SITUACAO_PENDENTE => 'bg-warning',
            ClienteModel::SITUACAO_BLOQUEADO => 'bg-danger'
        ];
        
        $classe = $classes[$situacao] ?? 'bg-secondary';
        
        return "<span class='badge {$classe}'>{$situacao}</span>";
    }

    /**
     * Retorna botões de ação para DataTables
     */
    private function getBotoesAcao($id)
    {
        return "
            <div class='btn-group'>
                <button class='btn btn-sm btn-primary btn-editar' data-id='{$id}' title='Editar'>
                    <i class='bi bi-pencil'></i>
                </button>
                <button class='btn btn-sm btn-danger btn-excluir' data-id='{$id}' title='Excluir'>
                    <i class='bi bi-trash'></i>
                </button>
            </div>
        ";
    }
}