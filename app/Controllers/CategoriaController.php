<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de categorias.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class CategoriaController extends ResourceController
{

    use ResponseTrait;

    protected $modelName = 'App\Models\CategoriaModel';
    protected $format    = 'json';

    public function __construct()
    {
        helper(['form', 'url']);
    }

    /**
     * Retorna a view principal de gerenciamento de categorias
     */
    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de categorias',           
        ];

        return view('categorias', $data);
    }

    /**
     * Lista todas as categorias cadastradas.
     * Método GET: /categorias/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()    
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todos os categorias ativos (não deletados)
        $categorias = $this->model->where('c1_deleted_at', null)->findAll();

        $data = [];
        foreach ($categorias as $categoria) {
            $data[] = [
                'c1_id' => $categoria->c1_id,
                'c1_categoria' => $categoria->c1_categoria,
                'c1_comissao' => $categoria->c1_comissao,
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
    public function show($id = null){
        $categoria = $this->model->find($id);

        if ($categoria) {
            return $this->respond($categoria);
        }

        return $this->failNotFound('Categoria não encontrada');
    }

    /**
     * Cadastra uma nova categoria.
     * Método POST: /categorias
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()    
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
                $categoria = $this->model->find($id);
                return $this->respondCreated($categoria, 'Categoria criada com sucesso');
            }

            return $this->fail('Falha ao criar categoria');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de uma categoria existente.
     * Método PUT: /categorias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function update($id = null)    
    {
        // Verificar se a categoria existe
        $categoria = $this->model->find($id);
        if (!$categoria) {
            return $this->failNotFound('Categoria não encontrada');
        }

        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();

        // Ajustar placeholder {c1_id} na regra is_unique para evitar conflito com o próprio registro
        if (isset($rules['c1_categoria'])) {
            $rules['c1_categoria'] = str_replace('{c1_id}', $id, $rules['c1_categoria']);
        }

        // Obter dados enviados
        $data = (array) $this->request->getJSON();
        // Garantir que o id esteja presente para validação de placeholders
        $data['c1_id'] = (int) $id;

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            if ($this->model->update($id, $data)) {
                $categoria = $this->model->find($id);
                return $this->respondUpdated($categoria, 'Categoria atualizada com sucesso');
            }

            return $this->fail('Falha ao atualizar categoria');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um cliente pelo ID.
     * Método DELETE: /categorias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null){
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
     * Busca categorias por termo (para autocomplete)
     */
    public function buscar()
    {
        $termo = $this->request->getVar('term');

        if (empty($termo)) {
            return $this->respond([]);
        }

        $categorias = $this->model->buscarPorNome($termo, 10);

        $resultados = [];
        foreach ($categorias as $cliente) {
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
     * Retorna estatísticas de categorias
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
