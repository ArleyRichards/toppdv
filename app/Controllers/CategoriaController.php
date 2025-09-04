<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
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
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
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
    public function show($id = null)
    {
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
     * Exclui uma categoria pelo ID.
     * Método DELETE: /categorias/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null)
    {
        // Verificar se o cliente existe
        $categoria = $this->model->find($id);
        if (!$categoria) {
            return $this->failNotFound('Categoria não encontrada');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Categoria excluída com sucesso');
            }

            return $this->fail('Falha ao excluir categoria');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }    
}
