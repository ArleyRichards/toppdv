<?php 
namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table = 'c1_categorias';
    protected $primaryKey = 'c1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'c1_created_at';
    protected $updatedField = 'c1_updated_at';
    protected $deletedField = 'c1_deleted_at';
    
    protected $allowedFields = [
        'c1_categoria',
        'c1_comissao',
        'c1_created_at',
        'c1_updated_at',
        'c1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
    'c1_categoria' => 'required|min_length[3]|max_length[100]|is_unique[c1_categorias.c1_categoria,c1_id,{c1_id}]',
    'c1_comissao' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
    // Placeholder field used by is_unique when updating an existing record
    'c1_id' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'c1_categoria' => [
            'required' => 'O campo categoria é obrigatório',
            'min_length' => 'A categoria deve ter pelo menos 3 caracteres',
            'max_length' => 'A categoria não pode exceder 100 caracteres',
            'is_unique' => 'Esta categoria já existe no sistema'
        ],
        'c1_comissao' => [
            'required' => 'O campo comissão é obrigatório',
            'decimal' => 'A comissão deve ser um valor decimal válido',
            'greater_than_equal_to' => 'A comissão não pode ser negativa',
            'less_than_equal_to' => 'A comissão não pode ser superior a 100%'
        ]
    ];
    
    // Formata a comissão antes de salvar
    protected $beforeInsert = ['formatarComissao'];
    protected $beforeUpdate = ['formatarComissao'];
    
    /**
     * Formata o valor da comissão para garantir 2 casas decimais
     */
    protected function formatarComissao(array $data)
    {
        if (!empty($data['data']['c1_comissao'])) {
            $data['data']['c1_comissao'] = number_format((float)$data['data']['c1_comissao'], 2, '.', '');
        }
        return $data;
    }
    
    /**
     * Busca categoria por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
    
    /**
     * Busca categoria pelo nome
     */
    public function buscarPorCategoria($categoria)
    {
        return $this->where('c1_categoria', $categoria)->first();
    }
    
    /**
     * Busca categorias ativas (não deletadas)
     */
    public function buscarAtivas()
    {
        return $this->orderBy('c1_categoria', 'ASC')->findAll();
    }
    
    /**
     * Busca categorias com comissão específica
     */
    public function buscarPorComissao($comissaoMinima = 0, $comissaoMaxima = 100)
    {
        return $this->where('c1_comissao >=', $comissaoMinima)
                   ->where('c1_comissao <=', $comissaoMaxima)
                   ->orderBy('c1_comissao', 'DESC')
                   ->findAll();
    }
    
    /**
     * Busca categorias com produtos associados
     */
    public function buscarComProdutos()
    {
        $produtoModel = new \App\Models\ProdutoModel();
        
        return $this->select('c1_categorias.*, COUNT(p1_produtos.p1_id) as total_produtos')
                   ->join('p1_produtos', 'p1_produtos.p1_categoria_id = c1_categorias.c1_id', 'left')
                   ->groupBy('c1_categorias.c1_id')
                   ->orderBy('c1_categoria', 'ASC')
                   ->findAll();
    }
    
    /**
     * Verifica se a categoria pode ser deletada (não tem produtos associados)
     */
    public function podeDeletar($categoriaId)
    {
        $produtoModel = new \App\Models\ProdutoModel();
        $produtos = $produtoModel->where('p1_categoria_id', $categoriaId)->countAllResults();
        
        return $produtos === 0;
    }
    
    /**
     * Calcula estatísticas das categorias
     */
    public function getEstatisticas()
    {
        $totalCategorias = $this->countAllResults();
        $categoriasAtivas = $this->where('c1_deleted_at', null)->countAllResults();
        
        $comissaoMedia = $this->selectAvg('c1_comissao', 'media_comissao')
                            ->where('c1_deleted_at', null)
                            ->first();
        
        return [
            'total_categorias' => $totalCategorias,
            'categorias_ativas' => $categoriasAtivas,
            'media_comissao' => $comissaoMedia->media_comissao ?? 0
        ];
    }
    
    /**
     * Busca categorias para select dropdown
     */
    public function buscarParaSelect()
    {
        $categorias = $this->select('c1_id, c1_categoria')
                          ->where('c1_deleted_at', null)
                          ->orderBy('c1_categoria', 'ASC')
                          ->findAll();
        
        $options = ['' => 'Selecione uma categoria...'];
        
        foreach ($categorias as $categoria) {
            $options[$categoria->c1_id] = $categoria->c1_categoria;
        }
        
        return $options;
    }
    
    /**
     * Atualiza comissão de uma categoria
     */
    public function atualizarComissao($categoriaId, $novaComissao)
    {
        $dados = [
            'c1_comissao' => $novaComissao
        ];
        
        return $this->update($categoriaId, $dados);
    }
    
    /**
     * Busca categorias com paginação
     */
    public function buscarPaginados($perPage = 10, $page = 1)
    {
        return $this->where('c1_deleted_at', null)
                   ->orderBy('c1_categoria', 'ASC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca categorias com filtro por nome
     */
    public function buscarComFiltro($filtro = '', $perPage = 10)
    {
        $builder = $this->where('c1_deleted_at', null);
        
        if (!empty($filtro)) {
            $builder->like('c1_categoria', $filtro);
        }
        
        return $builder->orderBy('c1_categoria', 'ASC')
                      ->paginate($perPage);
    }
}