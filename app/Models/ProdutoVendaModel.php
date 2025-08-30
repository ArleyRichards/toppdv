<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProdutoVendaModel extends Model
{
    protected $table = 'p2_produtos_venda';
    protected $primaryKey = 'p2_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    protected $createdField = 'p2_created_at';
    protected $updatedField = 'p2_updated_at';
    protected $deletedField = 'p2_deleted_at';
    
    protected $allowedFields = [
        'p2_venda_id',
        'p2_produto_id',
        'p2_quantidade',
        'p2_valor_unitario',
        'p2_subtotal',
        'p2_desconto',
        'p2_valor_com_desconto',
        'p2_created_at',
        'p2_updated_at',
        'p2_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    protected $validationRules = [
        'p2_venda_id' => 'required|integer|is_not_unique[v1_vendas.v1_id]',
        'p2_produto_id' => 'required|integer|is_not_unique[p1_produtos.p1_id]',
        'p2_quantidade' => 'required|integer|greater_than[0]',
        'p2_valor_unitario' => 'required|decimal|greater_than[0]',
        'p2_subtotal' => 'required|decimal|greater_than[0]',
        'p2_desconto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'p2_valor_com_desconto' => 'required|decimal|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'p2_venda_id' => [
            'is_not_unique' => 'A venda selecionada não existe'
        ],
        'p2_produto_id' => [
            'is_not_unique' => 'O produto selecionado não existe'
        ],
        'p2_quantidade' => [
            'greater_than' => 'A quantidade deve ser maior que zero'
        ]
    ];
    
    protected $beforeInsert = ['calcularValores'];
    protected $beforeUpdate = ['calcularValores'];
    
    /**
     * Calcula valores automaticamente
     */
    protected function calcularValores(array $data)
    {
        if (!empty($data['data']['p2_quantidade']) && !empty($data['data']['p2_valor_unitario'])) {
            $quantidade = (int)$data['data']['p2_quantidade'];
            $valorUnitario = (float)$data['data']['p2_valor_unitario'];
            $desconto = !empty($data['data']['p2_desconto']) ? (float)$data['data']['p2_desconto'] : 0;
            
            // Calcula subtotal
            $subtotal = $quantidade * $valorUnitario;
            $data['data']['p2_subtotal'] = $subtotal;
            
            // Calcula valor com desconto
            $valorComDesconto = $subtotal - $desconto;
            $data['data']['p2_valor_com_desconto'] = $valorComDesconto;
        }
        return $data;
    }
    
    /**
     * Busca itens por venda
     */
    public function buscarPorVenda($vendaId)
    {
        return $this->select('p2_produtos_venda.*, p1_produtos.p1_nome_produto, p1_produtos.p1_codigo_produto')
                   ->join('p1_produtos', 'p1_produtos.p1_id = p2_produtos_venda.p2_produto_id')
                   ->where('p2_venda_id', $vendaId)
                   ->orderBy('p2_id', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca itens por produto
     */
    public function buscarPorProduto($produtoId, $limit = 50)
    {
        return $this->where('p2_produto_id', $produtoId)
                   ->orderBy('p2_created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Calcula total da venda
     */
    public function calcularTotalVenda($vendaId)
    {
        return $this->selectSum('p2_valor_com_desconto', 'total_venda')
                   ->where('p2_venda_id', $vendaId)
                   ->first();
    }
    
    /**
     * Calcula quantidade total vendida por produto
     */
    public function calcularTotalVendidoPorProduto($produtoId)
    {
        return $this->selectSum('p2_quantidade', 'total_vendido')
                   ->where('p2_produto_id', $produtoId)
                   ->first();
    }
    
    /**
     * Adiciona produto à venda
     */
    public function adicionarProduto($vendaId, $produtoId, $quantidade, $valorUnitario, $desconto = 0)
    {
        $dados = [
            'p2_venda_id' => $vendaId,
            'p2_produto_id' => $produtoId,
            'p2_quantidade' => $quantidade,
            'p2_valor_unitario' => $valorUnitario,
            'p2_desconto' => $desconto
        ];
        
        return $this->insert($dados);
    }
    
    /**
     * Remove produto da venda
     */
    public function removerProduto($itemId)
    {
        return $this->delete($itemId);
    }
    
    /**
     * Atualiza quantidade do produto na venda
     */
    public function atualizarQuantidade($itemId, $quantidade)
    {
        $item = $this->find($itemId);
        if (!$item) return false;
        
        return $this->update($itemId, ['p2_quantidade' => $quantidade]);
    }
    
    /**
     * Atualiza desconto do produto na venda
     */
    public function atualizarDesconto($itemId, $desconto)
    {
        return $this->update($itemId, ['p2_desconto' => $desconto]);
    }
    
    /**
     * Verifica se produto já está na venda
     */
    public function produtoJaNaVenda($vendaId, $produtoId)
    {
        return $this->where('p2_venda_id', $vendaId)
                   ->where('p2_produto_id', $produtoId)
                   ->first();
    }
    
    /**
     * Busca vendas por período com totais
     */
    public function buscarVendasPorPeriodo($dataInicio, $dataFim)
    {
        $vendaModel = new \App\Models\VendaModel();
        
        return $vendaModel->select('v1_vendas.*, SUM(p2_valor_com_desconto) as total_venda')
                         ->join('p2_produtos_venda', 'p2_produtos_venda.p2_venda_id = v1_vendas.v1_id')
                         ->where('v1_created_at >=', $dataInicio)
                         ->where('v1_created_at <=', $dataFim)
                         ->where('v1_status', 'Faturado')
                         ->groupBy('v1_vendas.v1_id')
                         ->orderBy('v1_created_at', 'DESC')
                         ->findAll();
    }
    
    /**
     * Busca produtos mais vendidos
     */
    public function buscarProdutosMaisVendidos($limit = 10, $periodo = '30 days')
    {
        $dataInicio = date('Y-m-d', strtotime("-$periodo"));
        
        return $this->select('p2_produto_id, p1_produtos.p1_nome_produto, SUM(p2_quantidade) as total_vendido, SUM(p2_valor_com_desconto) as total_valor')
                   ->join('p1_produtos', 'p1_produtos.p1_id = p2_produtos_venda.p2_produto_id')
                   ->join('v1_vendas', 'v1_vendas.v1_id = p2_produtos_venda.p2_venda_id')
                   ->where('v1_vendas.v1_created_at >=', $dataInicio)
                   ->where('v1_vendas.v1_status', 'Faturado')
                   ->groupBy('p2_produto_id')
                   ->orderBy('total_vendido', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca itens de venda com informações completas
     */
    public function buscarItensCompletos($vendaId)
    {
        return $this->select('p2_produtos_venda.*, p1_produtos.p1_nome_produto, p1_produtos.p1_codigo_produto, p1_produtos.p1_imagem_produto')
                   ->join('p1_produtos', 'p1_produtos.p1_id = p2_produtos_venda.p2_produto_id')
                   ->where('p2_venda_id', $vendaId)
                   ->orderBy('p2_id', 'ASC')
                   ->findAll();
    }
}