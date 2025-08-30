<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'c2_clientes';
    protected $primaryKey = 'c2_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'c2_created_at';
    protected $updatedField = 'c2_updated_at';
    protected $deletedField = 'c2_deleted_at';
    
    protected $allowedFields = [
        'c2_nome',
        'c2_cpf',
        'c2_rg',
        'c2_data_nascimento',
        'c2_idade',
        'c2_cep',
        'c2_cidade',
        'c2_uf',
        'c2_endereco',
        'c2_bairro',
        'c2_complemento',
        'c2_numero',
        'c2_ponto_referencia',
        'c2_telefone',
        'c2_celular',
        'c2_email',
        'c2_situacao',
        'c2_created_at',
        'c2_updated_at',
        'c2_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'c2_nome' => 'required|min_length[3]|max_length[100]',
        'c2_cpf' => 'required|min_length[11]|max_length[14]|is_unique[c2_clientes.c2_cpf,c2_id,{c2_id}]',
        'c2_rg' => 'permit_empty|max_length[20]',
        'c2_data_nascimento' => 'required|valid_date',
        'c2_idade' => 'required|integer|greater_than[0]|less_than[150]',
        'c2_cep' => 'required|min_length[8]|max_length[9]',
        'c2_cidade' => 'required|min_length[3]|max_length[100]',
        'c2_uf' => 'required|exact_length[2]|alpha',
        'c2_endereco' => 'required|min_length[5]|max_length[255]',
        'c2_bairro' => 'required|min_length[3]|max_length[100]',
        'c2_complemento' => 'permit_empty|max_length[255]',
        'c2_numero' => 'permit_empty|max_length[10]',
        'c2_ponto_referencia' => 'permit_empty|max_length[255]',
        'c2_telefone' => 'permit_empty|max_length[15]',
        'c2_celular' => 'required|min_length[10]|max_length[15]',
        'c2_email' => 'permit_empty|valid_email|max_length[100]',
        'c2_situacao' => 'permit_empty|in_list[Ativo,Inativo,Pendente,Bloqueado]'
    ];
    
    protected $validationMessages = [
        'c2_cpf' => [
            'is_unique' => 'Este CPF já está cadastrado no sistema'
        ],
        'c2_uf' => [
            'exact_length' => 'UF deve ter exatamente 2 caracteres',
            'alpha' => 'UF deve conter apenas letras'
        ],
        'c2_celular' => [
            'min_length' => 'Celular deve ter pelo menos 10 dígitos',
            'max_length' => 'Celular não pode exceder 15 dígitos'
        ],
        'c2_situacao' => [
            'in_list' => 'Situação deve ser: Ativo, Inativo, Pendente ou Bloqueado'
        ]
    ];
    
    // Situações disponíveis
    const SITUACAO_ATIVO = 'Ativo';
    const SITUACAO_INATIVO = 'Inativo';
    const SITUACAO_PENDENTE = 'Pendente';
    const SITUACAO_BLOQUEADO = 'Bloqueado';
    
    // Callbacks para cálculos automáticos
    protected $beforeInsert = ['calcularIdade', 'formatarCpf', 'formatarTelefones'];
    protected $beforeUpdate = ['calcularIdade', 'formatarCpf', 'formatarTelefones'];
    
    /**
     * Calcula idade automaticamente a partir da data de nascimento
     */
    protected function calcularIdade(array $data)
    {
        if (!empty($data['data']['c2_data_nascimento'])) {
            $nascimento = new \DateTime($data['data']['c2_data_nascimento']);
            $hoje = new \DateTime();
            $idade = $hoje->diff($nascimento)->y;
            $data['data']['c2_idade'] = $idade;
        }
        return $data;
    }
    
    /**
     * Formata CPF removendo caracteres especiais
     */
    protected function formatarCpf(array $data)
    {
        if (!empty($data['data']['c2_cpf'])) {
            $cpf = preg_replace('/[^0-9]/', '', $data['data']['c2_cpf']);
            $data['data']['c2_cpf'] = $cpf;
        }
        return $data;
    }
    
    /**
     * Formata telefones removendo caracteres especiais
     */
    protected function formatarTelefones(array $data)
    {
        if (!empty($data['data']['c2_telefone'])) {
            $data['data']['c2_telefone'] = preg_replace('/[^0-9]/', '', $data['data']['c2_telefone']);
        }
        
        if (!empty($data['data']['c2_celular'])) {
            $data['data']['c2_celular'] = preg_replace('/[^0-9]/', '', $data['data']['c2_celular']);
        }
        
        return $data;
    }
    
    /**
     * Busca cliente por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
    
    /**
     * Busca cliente por CPF
     */
    public function buscarPorCpf($cpf)
    {
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        return $this->where('c2_cpf', $cpfLimpo)->first();
    }
    
    /**
     * Busca cliente por email
     */
    public function buscarPorEmail($email)
    {
        return $this->where('c2_email', $email)->first();
    }
    
    /**
     * Busca clientes por nome (like)
     */
    public function buscarPorNome($nome, $limit = 10)
    {
        return $this->like('c2_nome', $nome)
                   ->orderBy('c2_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca clientes por situação
     */
    public function buscarPorSituacao($situacao, $limit = 100)
    {
        return $this->where('c2_situacao', $situacao)
                   ->orderBy('c2_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca clientes por cidade
     */
    public function buscarPorCidade($cidade, $limit = 50)
    {
        return $this->where('c2_cidade', $cidade)
                   ->orderBy('c2_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca clientes ativos
     */
    public function buscarAtivos($limit = 100)
    {
        return $this->where('c2_situacao', self::SITUACAO_ATIVO)
                   ->orderBy('c2_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca clientes com paginação
     */
    public function buscarPaginados($perPage = 15, $page = 1)
    {
        return $this->where('c2_deleted_at', null)
                   ->orderBy('c2_nome', 'ASC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca clientes com filtros
     */
    public function buscarComFiltro($filtro = '', $situacao = '', $cidade = '', $perPage = 15)
    {
        $builder = $this->where('c2_deleted_at', null);
        
        if (!empty($filtro)) {
            $builder->groupStart()
                   ->like('c2_nome', $filtro)
                   ->orLike('c2_cpf', $filtro)
                   ->orLike('c2_email', $filtro)
                   ->groupEnd();
        }
        
        if (!empty($situacao)) {
            $builder->where('c2_situacao', $situacao);
        }
        
        if (!empty($cidade)) {
            $builder->where('c2_cidade', $cidade);
        }
        
        return $builder->orderBy('c2_nome', 'ASC')
                      ->paginate($perPage);
    }
    
    /**
     * Calcula estatísticas dos clientes
     */
    public function getEstatisticas()
    {
        $totalClientes = $this->countAllResults();
        $clientesAtivos = $this->where('c2_situacao', self::SITUACAO_ATIVO)->countAllResults();
        
        $porSituacao = $this->select('c2_situacao, COUNT(*) as total')
                          ->groupBy('c2_situacao')
                          ->findAll();
        
        $porCidade = $this->select('c2_cidade, COUNT(*) as total')
                         ->groupBy('c2_cidade')
                         ->orderBy('total', 'DESC')
                         ->limit(10)
                         ->findAll();
        
        return [
            'total_clientes' => $totalClientes,
            'clientes_ativos' => $clientesAtivos,
            'por_situacao' => $porSituacao,
            'top_cidades' => $porCidade
        ];
    }
    
    /**
     * Atualiza situação do cliente
     */
    public function atualizarSituacao($clienteId, $situacao)
    {
        $situacoesValidas = [self::SITUACAO_ATIVO, self::SITUACAO_INATIVO, self::SITUACAO_PENDENTE, self::SITUACAO_BLOQUEADO];
        
        if (!in_array($situacao, $situacoesValidas)) {
            return false;
        }
        
        return $this->update($clienteId, ['c2_situacao' => $situacao]);
    }
    
    /**
     * Verifica se CPF é válido
     */
    public function validarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se foi informada uma sequência de digitos repetidos
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Faz o cálculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Busca clientes para select dropdown
     */
    public function buscarParaSelect()
    {
        $clientes = $this->select('c2_id, c2_nome, c2_cpf')
                        ->where('c2_situacao', self::SITUACAO_ATIVO)
                        ->where('c2_deleted_at', null)
                        ->orderBy('c2_nome', 'ASC')
                        ->findAll();
        
        $options = ['' => 'Selecione um cliente...'];
        
        foreach ($clientes as $cliente) {
            $options[$cliente->c2_id] = "{$cliente->c2_nome} ({$cliente->c2_cpf})";
        }
        
        return $options;
    }
    
    /**
     * Busca total de compras por cliente
     */
    public function getTotalCompras($clienteId)
    {
        $vendaModel = new \App\Models\VendaModel();
        return $vendaModel->where('v1_cliente_id', $clienteId)
                         ->where('v1_status', 'Faturado')
                         ->countAllResults();
    }
    
    /**
     * Busca valor total gasto por cliente
     */
    public function getValorTotalCompras($clienteId)
    {
        $vendaModel = new \App\Models\VendaModel();
        $total = $vendaModel->selectSum('v1_valor_total', 'total_gasto')
                           ->where('v1_cliente_id', $clienteId)
                           ->where('v1_status', 'Faturado')
                           ->first();
        
        return $total->total_gasto ?? 0;
    }
}