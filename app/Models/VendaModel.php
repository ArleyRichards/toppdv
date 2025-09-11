<?php 
namespace App\Models;

use CodeIgniter\Model;

class VendaModel extends Model
{
    protected $table = 'v1_vendas';
    protected $primaryKey = 'v1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    protected $createdField = 'v1_created_at';
    protected $updatedField = 'v1_updated_at';
    protected $deletedField = 'v1_deleted_at';
    
    protected $allowedFields = [
        'v1_numero_venda',
        'v1_cliente_id',
        'v1_vendedor_nome',
        'v1_vendedor_id',
        'v1_tipo_de_pagamento',
        'v1_desconto',
        'v1_valor_total',
        'v1_codigo_transacao',
        'v1_valor_a_ser_pago',
        'v1_status',
        'v1_data_pagamento',
        'v1_data_faturamento',
        'v1_observacoes',
        'v1_created_at',
        'v1_updated_at',
        'v1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    protected $validationRules = [
        // Corrigidos nomes de campos para corresponder à tabela
        'v1_numero_venda' => 'required|is_unique[v1_vendas.v1_numero_venda,v1_id,{v1_id}]',
        'v1_cliente_id' => 'required|integer',
        'v1_vendedor_nome' => 'required|min_length[3]|max_length[255]',
        'v1_vendedor_id' => 'required|integer',
        'v1_tipo_de_pagamento' => 'required|in_list[dinheiro,cartao_credito,cartao_debito,pix,transferencia,boleto]',
        'v1_desconto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        // permitir zero: greater_than_equal_to[0]
        'v1_valor_total' => 'required|decimal|greater_than_equal_to[0]',
        'v1_codigo_transacao' => 'permit_empty|max_length[255]',
        'v1_valor_a_ser_pago' => 'required|decimal|greater_than_equal_to[0]',
        'v1_status' => 'required|in_list[Em Aberto,Faturado,Atrasado,Cancelado]',
        'v1_data_pagamento' => 'permit_empty|valid_date',
        'v1_data_faturamento' => 'permit_empty|valid_date',
        'v1_observacoes' => 'permit_empty'
    ];
    
    // Let controller generate final numero_venda to avoid race conditions
    protected $beforeInsert = ['calcularValorTotal'];
    protected $beforeUpdate = ['calcularValorTotal'];
    
    protected function calcularValorTotal(array $data)
    {
        // Se for uma atualização, recalcula o valor total baseado nos produtos
        if (!empty($data['id'])) {
            $produtoVendaModel = new ProdutoVendaModel();
            $total = $produtoVendaModel->calcularTotalVenda($data['id']);
            $data['data']['v1_valor_total'] = $total->total_venda ?? 0;

            // Aplica desconto geral da venda
            $desconto = !empty($data['data']['v1_desconto']) ? (float)$data['data']['v1_desconto'] : 0;
            $data['data']['v1_valor_a_ser_pago'] = $data['data']['v1_valor_total'] - $desconto;
        }
        return $data;
    }
    
    protected function gerarNumeroVenda(array $data)
    {
        // Gera um número no formato VD0001, VD0002 ... e popula v1_numero_venda
        if (empty($data['data']['v1_numero_venda'])) {
            try {
                $last = $this->selectMax('v1_id')->first();
                $lastId = $last->v1_id ?? 0;
                $next = $lastId + 1;
                $data['data']['v1_numero_venda'] = sprintf('VD%04d', $next);
            } catch (\Exception $e) {
                // fallback simples
                $data['data']['v1_numero_venda'] = 'VD' . strtoupper(bin2hex(random_bytes(3)));
            }
        }
        return $data;
    }
    
    public function buscarComRelacionamentos($limit = 100)
    {
    return $this->select('v1_vendas.*, c2_clientes.c2_nome as cliente_nome, u1_usuarios.u1_nome as vendedor_nome_completo')
           ->join('c2_clientes', 'c2_clientes.c2_id = v1_vendas.v1_cliente_id', 'left')
           ->join('u1_usuarios', 'u1_usuarios.u1_id = v1_vendas.v1_vendedor_id')
                   ->orderBy('v1_created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    public function adicionarProduto($vendaId, $produtoId, $quantidade, $valorUnitario, $desconto = 0)
    {
        $produtoVendaModel = new ProdutoVendaModel();
        return $produtoVendaModel->adicionarProduto($vendaId, $produtoId, $quantidade, $valorUnitario, $desconto);
    }
    
    public function listarProdutos($vendaId)
    {
        $produtoVendaModel = new ProdutoVendaModel();
        return $produtoVendaModel->buscarPorVenda($vendaId);
    }
    
    public function calcularTotal($vendaId)
    {
        $produtoVendaModel = new ProdutoVendaModel();
        return $produtoVendaModel->calcularTotalVenda($vendaId);
    }
    
    /**
     * Buscar venda completa com todos os relacionamentos
     */
    public function getVendaCompleta($vendaId)
    {
        // Buscar a venda principal
        $venda = $this->select('v1_vendas.*, c2_clientes.c2_nome as cliente_nome, c2_clientes.c2_cpf as cliente_cpf, u1_usuarios.u1_nome as vendedor_nome_completo')
                      ->join('c2_clientes', 'c2_clientes.c2_id = v1_vendas.v1_cliente_id', 'left')
                      ->join('u1_usuarios', 'u1_usuarios.u1_id = v1_vendas.v1_vendedor_id', 'left')
                      ->where('v1_vendas.v1_id', $vendaId)
                      ->first();
        
        if (!$venda) {
            return null;
        }
        
        // Buscar produtos da venda
        $produtoVendaModel = new ProdutoVendaModel();
        $produtos = $produtoVendaModel->select('p3_produto_venda.*, p1_produtos.p1_nome_produto as produto_nome, p1_produtos.p1_codigo_produto as produto_codigo')
                                    ->join('p1_produtos', 'p1_produtos.p1_id = p3_produto_venda.p3_produto_id')
                                    ->where('p3_venda_id', $vendaId)
                                    ->findAll();
        
        // Adicionar produtos à venda
        $venda->produtos = $produtos;
        
        return $venda;
    }
}