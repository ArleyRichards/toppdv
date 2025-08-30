<?php 
namespace App\Models;

use CodeIgniter\Model;

class FornecedorModel extends Model
{
    protected $table = 'f1_fornecedores';
    protected $primaryKey = 'f1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'f1_created_at';
    protected $updatedField = 'f1_updated_at';
    protected $deletedField = 'f1_deleted_at';
    
    protected $allowedFields = [
        'f1_razao_social',
        'f1_nome_fantasia',
        'f1_cnpj',
        'f1_cep',
        'f1_cidade',
        'f1_uf',
        'f1_endereco',
        'f1_bairro',
        'f1_complemento',
        'f1_numero',
        'f1_ponto_referencia',
        'f1_telefone',
        'f1_celular',
        'f1_email',
        'f1_created_at',
        'f1_updated_at',
        'f1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'f1_razao_social' => 'required|min_length[3]|max_length[255]',
        'f1_nome_fantasia' => 'required|min_length[3]|max_length[255]',
        'f1_cnpj' => 'required|min_length[14]|max_length[18]|is_unique[f1_fornecedores.f1_cnpj,f1_id,{f1_id}]',
        'f1_cep' => 'required|min_length[8]|max_length[9]',
        'f1_cidade' => 'required|min_length[3]|max_length[100]',
        'f1_uf' => 'required|exact_length[2]|alpha',
        'f1_endereco' => 'required|min_length[5]|max_length[255]',
        'f1_bairro' => 'required|min_length[3]|max_length[100]',
        'f1_complemento' => 'permit_empty|max_length[255]',
        'f1_numero' => 'permit_empty|max_length[10]',
        'f1_ponto_referencia' => 'permit_empty|max_length[255]',
        'f1_telefone' => 'permit_empty|max_length[15]',
        'f1_celular' => 'required|min_length[10]|max_length[15]',
        'f1_email' => 'permit_empty|valid_email|max_length[100]'
    ];
    
    protected $validationMessages = [
        'f1_cnpj' => [
            'is_unique' => 'Este CNPJ já está cadastrado no sistema'
        ],
        'f1_uf' => [
            'exact_length' => 'UF deve ter exatamente 2 caracteres',
            'alpha' => 'UF deve conter apenas letras'
        ],
        'f1_celular' => [
            'min_length' => 'Celular deve ter pelo menos 10 dígitos',
            'max_length' => 'Celular não pode exceder 15 dígitos'
        ]
    ];
    
    // Callbacks para formatação automática
    protected $beforeInsert = ['formatarCnpj', 'formatarTelefones', 'formatarCep'];
    protected $beforeUpdate = ['formatarCnpj', 'formatarTelefones', 'formatarCep'];
    
    /**
     * Formata CNPJ removendo caracteres especiais
     */
    protected function formatarCnpj(array $data)
    {
        if (!empty($data['data']['f1_cnpj'])) {
            $cnpj = preg_replace('/[^0-9]/', '', $data['data']['f1_cnpj']);
            $data['data']['f1_cnpj'] = $cnpj;
        }
        return $data;
    }
    
    /**
     * Formata telefones removendo caracteres especiais
     */
    protected function formatarTelefones(array $data)
    {
        if (!empty($data['data']['f1_telefone'])) {
            $data['data']['f1_telefone'] = preg_replace('/[^0-9]/', '', $data['data']['f1_telefone']);
        }
        
        if (!empty($data['data']['f1_celular'])) {
            $data['data']['f1_celular'] = preg_replace('/[^0-9]/', '', $data['data']['f1_celular']);
        }
        
        return $data;
    }
    
    /**
     * Formata CEP removendo caracteres especiais
     */
    protected function formatarCep(array $data)
    {
        if (!empty($data['data']['f1_cep'])) {
            $cep = preg_replace('/[^0-9]/', '', $data['data']['f1_cep']);
            $data['data']['f1_cep'] = $cep;
        }
        return $data;
    }
    
    /**
     * Busca fornecedor por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
    
    /**
     * Busca fornecedor por CNPJ
     */
    public function buscarPorCnpj($cnpj)
    {
        $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);
        return $this->where('f1_cnpj', $cnpjLimpo)->first();
    }
    
    /**
     * Busca fornecedor por email
     */
    public function buscarPorEmail($email)
    {
        return $this->where('f1_email', $email)->first();
    }
    
    /**
     * Busca fornecedores por nome fantasia (like)
     */
    public function buscarPorNomeFantasia($nome, $limit = 10)
    {
        return $this->like('f1_nome_fantasia', $nome)
                   ->orderBy('f1_nome_fantasia', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca fornecedores por razão social (like)
     */
    public function buscarPorRazaoSocial($razaoSocial, $limit = 10)
    {
        return $this->like('f1_razao_social', $razaoSocial)
                   ->orderBy('f1_razao_social', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca fornecedores por cidade
     */
    public function buscarPorCidade($cidade, $limit = 50)
    {
        return $this->where('f1_cidade', $cidade)
                   ->orderBy('f1_nome_fantasia', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca fornecedores por UF
     */
    public function buscarPorUf($uf, $limit = 50)
    {
        return $this->where('f1_uf', $uf)
                   ->orderBy('f1_nome_fantasia', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca fornecedores ativos (não deletados)
     */
    public function buscarAtivos($limit = 100)
    {
        return $this->where('f1_deleted_at', null)
                   ->orderBy('f1_nome_fantasia', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca fornecedores com paginação
     */
    public function buscarPaginados($perPage = 15, $page = 1)
    {
        return $this->where('f1_deleted_at', null)
                   ->orderBy('f1_nome_fantasia', 'ASC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca fornecedores com filtros
     */
    public function buscarComFiltro($filtro = '', $cidade = '', $uf = '', $perPage = 15)
    {
        $builder = $this->where('f1_deleted_at', null);
        
        if (!empty($filtro)) {
            $builder->groupStart()
                   ->like('f1_nome_fantasia', $filtro)
                   ->orLike('f1_razao_social', $filtro)
                   ->orLike('f1_cnpj', $filtro)
                   ->orLike('f1_email', $filtro)
                   ->groupEnd();
        }
        
        if (!empty($cidade)) {
            $builder->where('f1_cidade', $cidade);
        }
        
        if (!empty($uf)) {
            $builder->where('f1_uf', $uf);
        }
        
        return $builder->orderBy('f1_nome_fantasia', 'ASC')
                      ->paginate($perPage);
    }
    
    /**
     * Calcula estatísticas dos fornecedores
     */
    public function getEstatisticas()
    {
        $totalFornecedores = $this->countAllResults();
        $fornecedoresAtivos = $this->where('f1_deleted_at', null)->countAllResults();
        
        $porUf = $this->select('f1_uf, COUNT(*) as total')
                     ->groupBy('f1_uf')
                     ->orderBy('total', 'DESC')
                     ->findAll();
        
        $porCidade = $this->select('f1_cidade, COUNT(*) as total')
                         ->groupBy('f1_cidade')
                         ->orderBy('total', 'DESC')
                         ->limit(10)
                         ->findAll();
        
        return [
            'total_fornecedores' => $totalFornecedores,
            'fornecedores_ativos' => $fornecedoresAtivos,
            'por_uf' => $porUf,
            'top_cidades' => $porCidade
        ];
    }
    
    /**
     * Verifica se CNPJ é válido
     */
    public function validarCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Verifica se foi informada uma sequência de digitos repetidos
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Validação do CNPJ
        $tamanho = strlen($cnpj) - 2;
        $numeros = substr($cnpj, 0, $tamanho);
        $digitos = substr($cnpj, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        
        $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
        if ($resultado != $digitos[0]) {
            return false;
        }
        
        $tamanho++;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        
        $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
        if ($resultado != $digitos[1]) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Busca fornecedores para select dropdown
     */
    public function buscarParaSelect()
    {
        $fornecedores = $this->select('f1_id, f1_nome_fantasia, f1_cnpj')
                            ->where('f1_deleted_at', null)
                            ->orderBy('f1_nome_fantasia', 'ASC')
                            ->findAll();
        
        $options = ['' => 'Selecione um fornecedor...'];
        
        foreach ($fornecedores as $fornecedor) {
            $options[$fornecedor->f1_id] = "{$fornecedor->f1_nome_fantasia} ({$fornecedor->f1_cnpj})";
        }
        
        return $options;
    }
    
    /**
     * Busca total de produtos por fornecedor
     */
    public function getTotalProdutos($fornecedorId)
    {
        $produtoModel = new \App\Models\ProdutoModel();
        return $produtoModel->where('p1_fornecedor_id', $fornecedorId)
                           ->countAllResults();
    }
    
    /**
     * Busca produtos do fornecedor
     */
    public function getProdutos($fornecedorId, $limit = 50)
    {
        $produtoModel = new \App\Models\ProdutoModel();
        return $produtoModel->where('p1_fornecedor_id', $fornecedorId)
                           ->orderBy('p1_nome_produto', 'ASC')
                           ->limit($limit)
                           ->findAll();
    }
    
    /**
     * Verifica se fornecedor tem produtos associados
     */
    public function temProdutos($fornecedorId)
    {
        return $this->getTotalProdutos($fornecedorId) > 0;
    }
    
    /**
     * Busca fornecedores que mais fornecem produtos
     */
    public function getTopFornecedores($limit = 10)
    {
        $produtoModel = new \App\Models\ProdutoModel();
        
        return $this->select('f1_fornecedores.*, COUNT(p1_produtos.p1_id) as total_produtos')
                   ->join('p1_produtos', 'p1_produtos.p1_fornecedor_id = f1_fornecedores.f1_id', 'left')
                   ->groupBy('f1_fornecedores.f1_id')
                   ->orderBy('total_produtos', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}