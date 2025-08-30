<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'p1_produtos';
    protected $primaryKey = 'p1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'p1_created_at';
    protected $updatedField = 'p1_updated_at';
    protected $deletedField = 'p1_deleted_at';
    
    protected $allowedFields = [
        'p1_imagem_produto',
        'p1_nome_produto',
        'p1_codigo_produto',
        'p1_fornecedor_id',
        'p1_categoria_id',
        'p1_garantia_id',
        'p1_quantidade_produto',
        'p1_preco_unitario_produto',
        'p1_preco_compra_produto',
        'p1_preco_venda_produto',
        'p1_preco_total_em_produto',
        'p1_created_at',
        'p1_updated_at',
        'p1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'p1_nome_produto' => 'required|min_length[3]|max_length[255]',
        'p1_codigo_produto' => 'required|min_length[3]|max_length[255]|is_unique[p1_produtos.p1_codigo_produto,p1_id,{p1_id}]',
        'p1_fornecedor_id' => 'required|integer|is_not_unique[f1_fornecedores.f1_id]',
        'p1_categoria_id' => 'required|integer|is_not_unique[c1_categorias.c1_id]',
        'p1_garantia_id' => 'required|integer|is_not_unique[g1_garantias.g1_id]',
        'p1_quantidade_produto' => 'required|integer|greater_than_equal_to[0]',
        'p1_preco_unitario_produto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'p1_preco_compra_produto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'p1_preco_venda_produto' => 'required|decimal|greater_than_equal_to[0]',
        'p1_preco_total_em_produto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'p1_imagem_produto' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'p1_codigo_produto' => [
            'is_unique' => 'Este código de produto já está em uso'
        ],
        'p1_fornecedor_id' => [
            'is_not_unique' => 'O fornecedor selecionado não existe'
        ],
        'p1_categoria_id' => [
            'is_not_unique' => 'A categoria selecionada não existe'
        ],
        'p1_garantia_id' => [
            'is_not_unique' => 'A garantia selecionada não existe'
        ],
        'p1_quantidade_produto' => [
            'greater_than_equal_to' => 'A quantidade não pode ser negativa'
        ]
    ];
    
    // Callbacks para cálculos automáticos
    protected $beforeInsert = ['calcularPrecoTotal', 'gerarCodigoSeNecessario'];
    protected $beforeUpdate = ['calcularPrecoTotal'];
    
    /**
     * Calcula preço total baseado na quantidade e preço unitário
     */
    protected function calcularPrecoTotal(array $data)
    {
        if (!empty($data['data']['p1_quantidade_produto']) && !empty($data['data']['p1_preco_unitario_produto'])) {
            $quantidade = (int)$data['data']['p1_quantidade_produto'];
            $precoUnitario = (float)$data['data']['p1_preco_unitario_produto'];
            $data['data']['p1_preco_total_em_produto'] = $quantidade * $precoUnitario;
        }
        return $data;
    }
    
    /**
     * Gera código automático se não informado
     */
    protected function gerarCodigoSeNecessario(array $data)
    {
        if (empty($data['data']['p1_codigo_produto'])) {
            $data['data']['p1_codigo_produto'] = 'PROD_' . time() . '_' . rand(100, 999);
        }
        return $data;
    }
    
    /**
     * Busca produto por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
    
    /**
     * Busca produto por código
     */
    public function buscarPorCodigo($codigo)
    {
        return $this->where('p1_codigo_produto', $codigo)->first();
    }
    
    /**
     * Busca produtos por nome
     */
    public function buscarPorNome($nome, $limit = 10)
    {
        return $this->like('p1_nome_produto', $nome)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca produtos por categoria
     */
    public function buscarPorCategoria($categoriaId, $limit = 100)
    {
        return $this->where('p1_categoria_id', $categoriaId)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca produtos por fornecedor
     */
    public function buscarPorFornecedor($fornecedorId, $limit = 100)
    {
        return $this->where('p1_fornecedor_id', $fornecedorId)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca produtos por garantia
     */
    public function buscarPorGarantia($garantiaId, $limit = 50)
    {
        return $this->where('p1_garantia_id', $garantiaId)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca produtos com estoque baixo
     */
    public function buscarEstoqueBaixo($limite = 10)
    {
        return $this->where('p1_quantidade_produto <=', $limite)
                   ->orderBy('p1_quantidade_produto', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca produtos sem estoque
     */
    public function buscarSemEstoque()
    {
        return $this->where('p1_quantidade_produto', 0)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca produtos com join nas tabelas relacionadas
     */
    public function buscarComRelacionamentos($limit = 100)
    {
        return $this->select('p1_produtos.*, f1_fornecedores.f1_nome_fantasia, c1_categorias.c1_categoria, g1_garantias.g1_nome as garantia_nome')
                   ->join('f1_fornecedores', 'f1_fornecedores.f1_id = p1_produtos.p1_fornecedor_id')
                   ->join('c1_categorias', 'c1_categorias.c1_id = p1_produtos.p1_categoria_id')
                   ->join('g1_garantias', 'g1_garantias.g1_id = p1_produtos.p1_garantia_id')
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca produtos para venda (com estoque positivo)
     */
    public function buscarParaVenda()
    {
        return $this->where('p1_quantidade_produto >', 0)
                   ->where('p1_preco_venda_produto >', 0)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->findAll();
    }
    
    /**
     * Atualiza estoque do produto
     */
    public function atualizarEstoque($produtoId, $quantidade, $operacao = 'entrada')
    {
        $produto = $this->find($produtoId);
        
        if (!$produto) {
            return false;
        }
        
        $novoEstoque = $operacao === 'entrada' 
            ? $produto->p1_quantidade_produto + $quantidade
            : $produto->p1_quantidade_produto - $quantidade;
        
        if ($novoEstoque < 0) {
            return false; // Não permite estoque negativo
        }
        
        return $this->update($produtoId, ['p1_quantidade_produto' => $novoEstoque]);
    }
    
    /**
     * Diminui estoque (venda)
     */
    public function diminuirEstoque($produtoId, $quantidade)
    {
        return $this->atualizarEstoque($produtoId, $quantidade, 'saida');
    }
    
    /**
     * Aumenta estoque (compra/devolução)
     */
    public function aumentarEstoque($produtoId, $quantidade)
    {
        return $this->atualizarEstoque($produtoId, $quantidade, 'entrada');
    }
    
    /**
     * Busca produtos com paginação
     */
    public function buscarPaginados($perPage = 15, $page = 1)
    {
        return $this->where('p1_deleted_at', null)
                   ->orderBy('p1_nome_produto', 'ASC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca produtos com filtros
     */
    public function buscarComFiltros($filtro = '', $categoriaId = '', $fornecedorId = '', $comEstoque = true, $perPage = 15)
    {
        $builder = $this->where('p1_deleted_at', null);
        
        if (!empty($filtro)) {
            $builder->groupStart()
                   ->like('p1_nome_produto', $filtro)
                   ->orLike('p1_codigo_produto', $filtro)
                   ->groupEnd();
        }
        
        if (!empty($categoriaId)) {
            $builder->where('p1_categoria_id', $categoriaId);
        }
        
        if (!empty($fornecedorId)) {
            $builder->where('p1_fornecedor_id', $fornecedorId);
        }
        
        if ($comEstoque) {
            $builder->where('p1_quantidade_produto >', 0);
        }
        
        return $builder->orderBy('p1_nome_produto', 'ASC')
                      ->paginate($perPage);
    }
    
    /**
     * Calcula valor total do estoque
     */
    public function calcularValorTotalEstoque()
    {
        $result = $this->selectSum('p1_preco_total_em_produto', 'valor_total')
                      ->where('p1_deleted_at', null)
                      ->first();
        
        return $result->valor_total ?? 0;
    }
    
    /**
     * Busca produtos mais vendidos
     */
    public function buscarMaisVendidos($limit = 10)
    {
        $produtoVendaModel = new ProdutoVendaModel();
        
        return $produtoVendaModel->select('p2_produto_id, p1_produtos.p1_nome_produto, SUM(p2_quantidade) as total_vendido')
                               ->join('p1_produtos', 'p1_produtos.p1_id = p2_produtos_venda.p2_produto_id')
                               ->groupBy('p2_produto_id')
                               ->orderBy('total_vendido', 'DESC')
                               ->limit($limit)
                               ->findAll();
    }
    
    /**
     * Busca produtos para select dropdown
     */
    public function buscarParaSelect()
    {
        $produtos = $this->select('p1_id, p1_nome_produto, p1_codigo_produto, p1_preco_venda_produto')
                        ->where('p1_quantidade_produto >', 0)
                        ->where('p1_deleted_at', null)
                        ->orderBy('p1_nome_produto', 'ASC')
                        ->findAll();
        
        $options = ['' => 'Selecione um produto...'];
        
        foreach ($produtos as $produto) {
            $preco = number_format($produto->p1_preco_venda_produto, 2, ',', '.');
            $options[$produto->p1_id] = "{$produto->p1_nome_produto} ({$produto->p1_codigo_produto}) - R$ {$preco}";
        }
        
        return $options;
    }
}