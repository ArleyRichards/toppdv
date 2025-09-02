<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\FornecedorModel;
use App\Models\GarantiaModel;
use App\Models\ProdutoModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de produtos.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class ProdutoController extends ResourceController{

    use ResponseTrait;

    protected $modelName = 'App\Models\ProdutoModel';
    protected $format    = 'json';

    public function __construct()
    {
        helper(['form', 'url']);
    }

    /**
     * Parse a money string coming from jQuery.Mask (e.g. "1.234,56") into float.
     * Accepts numeric values as well.
     * @param mixed $value
     * @return float
     */
    private function parseMoney($value)
    {
        if ($value === null || $value === '') {
            return 0.00;
        }

        // If already numeric (int/float or numeric string like "1234.56"), return float cast
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Normalize mask format: remove thousands separators (.) and convert decimal comma to dot
        $v = (string) $value;
        // Remove non-digit except comma and dot first
        $v = preg_replace('/[^0-9\.,-]/', '', $v);
        // Remove dots used as thousand separators
        $v = str_replace('.', '', $v);
        // Replace comma decimal separator with dot
        $v = str_replace(',', '.', $v);

        // Final cleanup: allow negative and dot and digits only
        $v = preg_replace('/[^0-9\.-]/', '', $v);

        return $v === '' ? 0.00 : (float) $v;
    }

    /**
     * Retorna a view principal de gerenciamento de produtos
     * Método GET: /produtos
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function index()
    {
        //listagem de categorias
        $categoriaModel = new CategoriaModel();
        $categorias = $categoriaModel->findAll();

        //listagem de fornecedores
        $fornecedorModel = new FornecedorModel();
        $fornecedores = $fornecedorModel->findAll();

        //listagem de garantias
        $garantiaModel = new GarantiaModel();
        $garantias = $garantiaModel->findAll();

        $data = [
            'title' => 'Gerenciamento de Produtos',
            'categorias' => $categorias,
            'fornecedores' => $fornecedores,
            'garantias' => $garantias,
        ];

        return view('produtos', $data);
    }

    /**
     * Lista todos os produtos cadastrados.
     * Método GET: /produtos/list
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()    
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todos os produtos ativos (não deletados) com dados do fornecedor e categoria
        $produtos = $this->model
            ->select('p1_produtos.p1_id, p1_produtos.p1_imagem_produto, p1_produtos.p1_nome_produto, p1_produtos.p1_codigo_produto, p1_produtos.p1_fornecedor_id, p1_produtos.p1_categoria_id, p1_produtos.p1_garantia_id, p1_produtos.p1_quantidade_produto, p1_produtos.p1_preco_unitario_produto, p1_produtos.p1_preco_compra_produto, p1_produtos.p1_preco_venda_produto, p1_produtos.p1_preco_total_em_produto, p1_produtos.p1_created_at, p1_produtos.p1_updated_at, f1_fornecedores.f1_nome_fantasia, c1_categorias.c1_categoria, g1_garantias.g1_nome')
            ->join('f1_fornecedores', 'p1_produtos.p1_fornecedor_id = f1_fornecedores.f1_id', 'left')
            ->join('c1_categorias', 'p1_produtos.p1_categoria_id = c1_categorias.c1_id', 'left')
            ->join('g1_garantias', 'p1_produtos.p1_garantia_id = g1_garantias.g1_id', 'left')
            ->where('p1_produtos.p1_deleted_at', null)
            ->findAll();

        $data = [];
        foreach ($produtos as $produto) {
            $data[] = [
                'p1_id' => $produto->p1_id,
                'p1_imagem_produto' => $produto->p1_imagem_produto,
                'p1_nome_produto' => $produto->p1_nome_produto,
                'p1_codigo_produto' => $produto->p1_codigo_produto,
                'p1_fornecedor_id' => $produto->p1_fornecedor_id,
                'f1_nome_fantasia' => isset($produto->f1_nome_fantasia) ? $produto->f1_nome_fantasia : null,
                'p1_categoria_id' => $produto->p1_categoria_id,
                'c1_categoria' => isset($produto->c1_categoria) ? $produto->c1_categoria : null,
                'p1_garantia_id' => $produto->p1_garantia_id,
                'g1_nome' => isset($produto->g1_nome) ? $produto->g1_nome : null,
                'p1_quantidade_produto' => $produto->p1_quantidade_produto,
                'p1_preco_unitario_produto' => $produto->p1_preco_unitario_produto,
                'p1_preco_compra_produto' => $produto->p1_preco_compra_produto,
                'p1_preco_venda_produto' => $produto->p1_preco_venda_produto,
                'p1_preco_total_em_produto' => $produto->p1_preco_total_em_produto,
                'p1_created_at' => $produto->p1_created_at,
                'p1_updated_at' => $produto->p1_updated_at
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de um produto pelo ID.
     * Método GET: /produtos/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null){
        if (!$id) {
            return $this->failNotFound('Produto não encontrado');
        }

        $produto = $this->model
            ->select('p1_produtos.p1_id, p1_produtos.p1_imagem_produto, p1_produtos.p1_nome_produto, p1_produtos.p1_codigo_produto, p1_produtos.p1_fornecedor_id, p1_produtos.p1_categoria_id, p1_produtos.p1_garantia_id, p1_produtos.p1_quantidade_produto, p1_produtos.p1_preco_unitario_produto, p1_produtos.p1_preco_compra_produto, p1_produtos.p1_preco_venda_produto, p1_produtos.p1_preco_total_em_produto, p1_produtos.p1_created_at, p1_produtos.p1_updated_at, f1_fornecedores.f1_nome_fantasia, c1_categorias.c1_categoria, g1_garantias.g1_nome')
            ->join('f1_fornecedores', 'p1_produtos.p1_fornecedor_id = f1_fornecedores.f1_id', 'left')
            ->join('c1_categorias', 'p1_produtos.p1_categoria_id = c1_categorias.c1_id', 'left')
            ->join('g1_garantias', 'p1_produtos.p1_garantia_id = g1_garantias.g1_id', 'left')
            ->where('p1_produtos.p1_deleted_at', null)
            ->where('p1_produtos.p1_id', $id)
            ->first();

        if ($produto) {
            $data = [
                'p1_id' => $produto->p1_id,
                'p1_imagem_produto' => $produto->p1_imagem_produto,
                'p1_nome_produto' => $produto->p1_nome_produto,
                'p1_codigo_produto' => $produto->p1_codigo_produto,
                'p1_fornecedor_id' => $produto->p1_fornecedor_id,
                'f1_nome_fantasia' => isset($produto->f1_nome_fantasia) ? $produto->f1_nome_fantasia : null,
                'p1_categoria_id' => $produto->p1_categoria_id,
                'c1_categoria' => isset($produto->c1_categoria) ? $produto->c1_categoria : null,
                'p1_garantia_id' => $produto->p1_garantia_id,
                'g1_nome' => isset($produto->g1_nome) ? $produto->g1_nome : null,
                'p1_quantidade_produto' => $produto->p1_quantidade_produto,
                'p1_preco_unitario_produto' => $produto->p1_preco_unitario_produto,
                'p1_preco_compra_produto' => $produto->p1_preco_compra_produto,
                'p1_preco_venda_produto' => $produto->p1_preco_venda_produto,
                'p1_preco_total_em_produto' => $produto->p1_preco_total_em_produto,
                'p1_created_at' => $produto->p1_created_at,
                'p1_updated_at' => $produto->p1_updated_at
            ];

            return $this->respond($data);
        }

        return $this->failNotFound('Produto não encontrado');
    }

    /**
     * Cadastra um novo produto.
     * Método POST: /produtos
     * Aceita multipart/form-data (arquivo img-product) ou form fields.
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()
    {
        try {
            // Obter dados do POST (FormData)
            $request = service('request');
            $post = $request->getPost();

            // Mapear campos do formulário para colunas da tabela p1_produtos
            $data = [
                'p1_nome_produto' => $post['product-name'] ?? null,
                'p1_codigo_produto' => $post['product-code'] ?? null,
                'p1_fornecedor_id' => isset($post['product-supplier']) ? (int) $post['product-supplier'] : null,
                'p1_categoria_id' => isset($post['categoria_id']) ? (int) $post['categoria_id'] : null,
                'p1_garantia_id' => isset($post['garantia_id']) ? (int) $post['garantia_id'] : null,
                'p1_quantidade_produto' => isset($post['product-qnt']) ? (int) $post['product-qnt'] : 0,
                'p1_preco_unitario_produto' => isset($post['product-unit-price']) ? $this->parseMoney($post['product-unit-price']) : 0.00,
                'p1_preco_compra_produto' => isset($post['product-purchase-price']) ? $this->parseMoney($post['product-purchase-price']) : 0.00,
                'p1_preco_venda_produto' => isset($post['product-sale-price']) ? $this->parseMoney($post['product-sale-price']) : 0.00,
                'p1_preco_total_em_produto' => isset($post['total-price-on-product']) ? $this->parseMoney($post['total-price-on-product']) : 0.00,
                'p1_created_at' => date('Y-m-d H:i:s')
            ];

            // Validação básica dos campos exigidos
            $errors = [];
            if (empty($data['p1_nome_produto'])) $errors['product-name'] = 'Nome do produto é obrigatório.';
            if (empty($data['p1_codigo_produto'])) $errors['product-code'] = 'Código do produto é obrigatório.';
            if (empty($data['p1_categoria_id'])) $errors['categoria_id'] = 'Categoria é obrigatória.';
            if (empty($data['p1_fornecedor_id'])) $errors['product-supplier'] = 'Fornecedor é obrigatório.';
            if (empty($data['p1_garantia_id'])) $errors['garantia_id'] = 'Garantia é obrigatória.';

            if (!empty($errors)) {
                return $this->failValidationErrors($errors);
            }

            // Processar upload de imagem (campo 'img-product')
            $imageName = 'default-product.webp';
            $file = $request->getFile('img-product');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $targetDir = FCPATH . 'public/assets/img/products/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $newName = $file->getRandomName();
                $file->move($targetDir, $newName);
                $imageName = $newName;
            }
            $data['p1_imagem_produto'] = $imageName;

            // Inserir no banco
            $id = $this->model->insert($data);
            if ($id) {
                $produto = $this->model->find($id);
                return $this->respondCreated($produto, 'Produto criado com sucesso');
            }

            return $this->fail('Falha ao criar produto');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um produto existente.
     * Método PUT/PATCH: /produtos/{id}
     * Aceita multipart/form-data (arquivo img-product) ou JSON/form fields.
     * @param int $id
     * @return 
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->failNotFound('Produto não encontrado');
        }

        $produto = $this->model->find($id);
        if (!$produto) {
            return $this->failNotFound('Produto não encontrado');
        }

        try {
            $request = service('request');

            // Aceitar tanto form-data quanto JSON
            $post = $request->getPost();
            if (empty($post)) {
                $json = $request->getJSON(true); // retorna array
                if ($json) $post = $json;
            }

            // Mesclar valores enviados com os valores atuais do produto
            $current = is_array($produto) ? $produto : (array) $produto;

            $data = [
                'p1_nome_produto' => $post['product-name'] ?? ($current['p1_nome_produto'] ?? null),
                'p1_codigo_produto' => $post['product-code'] ?? ($current['p1_codigo_produto'] ?? null),
                'p1_fornecedor_id' => isset($post['product-supplier']) ? (int) $post['product-supplier'] : ($current['p1_fornecedor_id'] ?? null),
                'p1_categoria_id' => isset($post['categoria_id']) ? (int) $post['categoria_id'] : ($current['p1_categoria_id'] ?? null),
                'p1_garantia_id' => isset($post['garantia_id']) ? (int) $post['garantia_id'] : ($current['p1_garantia_id'] ?? null),
                'p1_quantidade_produto' => isset($post['product-qnt']) ? (int) $post['product-qnt'] : ($current['p1_quantidade_produto'] ?? 0),
                'p1_preco_unitario_produto' => isset($post['product-unit-price']) ? $this->parseMoney($post['product-unit-price']) : ((isset($current['p1_preco_unitario_produto']) ? $this->parseMoney($current['p1_preco_unitario_produto']) : 0.00)),
                'p1_preco_compra_produto' => isset($post['product-purchase-price']) ? $this->parseMoney($post['product-purchase-price']) : ((isset($current['p1_preco_compra_produto']) ? $this->parseMoney($current['p1_preco_compra_produto']) : 0.00)),
                'p1_preco_venda_produto' => isset($post['product-sale-price']) ? $this->parseMoney($post['product-sale-price']) : ((isset($current['p1_preco_venda_produto']) ? $this->parseMoney($current['p1_preco_venda_produto']) : 0.00)),
                'p1_preco_total_em_produto' => isset($post['total-price-on-product']) ? $this->parseMoney($post['total-price-on-product']) : ((isset($current['p1_preco_total_em_produto']) ? $this->parseMoney($current['p1_preco_total_em_produto']) : 0.00)),
                'p1_updated_at' => date('Y-m-d H:i:s')
            ];

            // Ensure primary key present in data so model validation placeholders like {p1_id} work
            $data['p1_id'] = (int) $id;

            // Validação básica
            $errors = [];
            if (empty($data['p1_nome_produto'])) $errors['product-name'] = 'Nome do produto é obrigatório.';
            if (empty($data['p1_codigo_produto'])) $errors['product-code'] = 'Código do produto é obrigatório.';
            if (empty($data['p1_categoria_id'])) $errors['categoria_id'] = 'Categoria é obrigatória.';
            if (empty($data['p1_fornecedor_id'])) $errors['product-supplier'] = 'Fornecedor é obrigatório.';
            if (empty($data['p1_garantia_id'])) $errors['garantia_id'] = 'Garantia é obrigatória.';

            if (!empty($errors)) {
                return $this->failValidationErrors($errors);
            }

            // Processar upload de imagem (campo 'img-product')
            $file = $request->getFile('img-product');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $targetDir = FCPATH . 'public/assets/img/products/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                // remover imagem antiga se existir e não for a imagem padrão
                $oldImage = $current['p1_imagem_produto'] ?? null;
                if ($oldImage && $oldImage !== 'default-product.webp') {
                    $oldPath = FCPATH . 'public/assets/img/products/' . $oldImage;
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $newName = $file->getRandomName();
                $file->move($targetDir, $newName);
                $data['p1_imagem_produto'] = $newName;
            }

            // Run model validation explicitly
            try {
                $isValid = true;
                if (method_exists($this->model, 'validate')) {
                    $isValid = $this->model->validate($data);
                }
            } catch (\Exception $e) {
                // ignore validation exception here; proceed to update attempt
                $isValid = true;
            }

            if ($isValid === false) {
                $modelErrors = $this->model->errors();
                return $this->failValidationErrors($modelErrors ?: ['error' => 'Validação falhou (details não disponíveis)']);
            }

            // Atualizar registro
            if ($this->model->update($id, $data)) {
                $produtoAtualizado = $this->model->find($id);
                return $this->respondUpdated($produtoAtualizado, 'Produto atualizado com sucesso');
            }

            // If update failed but there were no model validation errors, capture DB error and return diagnostics
            $modelErrors = $this->model->errors();
            if (!empty($modelErrors)) {
                return $this->failValidationErrors($modelErrors);
            }

            // Get DB error info
            $db = \Config\Database::connect();
            $dbError = [];
            try {
                $dbError = $db->error();
            } catch (\Exception $e) {
                $dbError = ['code' => null, 'message' => $e->getMessage()];
            }

            // Return diagnostic info to help debugging (temporary)
            return $this->fail(['error' => 'Falha ao atualizar produto', 'db_error' => $dbError, 'data_keys' => array_keys($data), 'post_keys' => array_keys((array)$post)]);
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um produto pelo ID.
     * Método DELETE: /produtos/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null){
        // Verificar se o produto existe
        $produto = $this->model->find($id);
        if (!$produto) {
            return $this->failNotFound('Produto não encontrado');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['id' => $id], 'Produto excluído com sucesso');
            }

            return $this->fail('Falha ao excluir produto');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }
}
