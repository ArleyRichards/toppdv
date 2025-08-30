<?php 
namespace App\Models;

use CodeIgniter\Model;

class GarantiaModel extends Model
{
    protected $table = 'g1_garantias';
    protected $primaryKey = 'g1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'g1_created_at';
    protected $updatedField = 'g1_updated_at';
    protected $deletedField = 'g1_deleted_at';
    
    protected $allowedFields = [
        'g1_data_garantia',
        'g1_nome',
        'g1_observacao',
        'g1_data',
        'g1_descricao',
        'g1_created_at',
        'g1_updated_at',
        'g1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'g1_nome' => 'required|min_length[3]|max_length[255]',
        'g1_data' => 'required|valid_date',
        'g1_descricao' => 'required|min_length[10]',
        'g1_data_garantia' => 'permit_empty|valid_date',
        'g1_observacao' => 'permit_empty'
    ];
    
    protected $validationMessages = [
        'g1_nome' => [
            'required' => 'O campo nome é obrigatório',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres',
            'max_length' => 'O nome não pode exceder 255 caracteres'
        ],
        'g1_data' => [
            'required' => 'O campo data é obrigatório',
            'valid_date' => 'A data informada é inválida'
        ],
        'g1_descricao' => [
            'required' => 'O campo descrição é obrigatório',
            'min_length' => 'A descrição deve ter pelo menos 10 caracteres'
        ],
        'g1_data_garantia' => [
            'valid_date' => 'A data da garantia informada é inválida'
        ]
    ];
    
    // Callbacks para configuração automática
    protected $beforeInsert = ['configurarDataGarantia'];
    protected $beforeUpdate = ['configurarDataGarantia'];
    
    /**
     * Configura a data da garantia se não informada
     */
    protected function configurarDataGarantia(array $data)
    {
        if (empty($data['data']['g1_data_garantia'])) {
            $data['data']['g1_data_garantia'] = date('Y-m-d H:i:s');
        }
        return $data;
    }
    
    /**
     * Busca garantia por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
    
    /**
     * Busca garantias por nome
     */
    public function buscarPorNome($nome, $limit = 10)
    {
        return $this->like('g1_nome', $nome)
                   ->orderBy('g1_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias por data
     */
    public function buscarPorData($data, $limit = 50)
    {
        return $this->where('g1_data', $data)
                   ->orderBy('g1_nome', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias por período
     */
    public function buscarPorPeriodo($dataInicio, $dataFim, $limit = 100)
    {
        return $this->where('g1_data >=', $dataInicio)
                   ->where('g1_data <=', $dataFim)
                   ->orderBy('g1_data', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias ativas (não deletadas)
     */
    public function buscarAtivas($limit = 100)
    {
        return $this->where('g1_deleted_at', null)
                   ->orderBy('g1_data', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias com paginação
     */
    public function buscarPaginados($perPage = 15, $page = 1)
    {
        return $this->where('g1_deleted_at', null)
                   ->orderBy('g1_data', 'DESC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca garantias com filtros
     */
    public function buscarComFiltro($filtro = '', $dataInicio = '', $dataFim = '', $perPage = 15)
    {
        $builder = $this->where('g1_deleted_at', null);
        
        if (!empty($filtro)) {
            $builder->groupStart()
                   ->like('g1_nome', $filtro)
                   ->orLike('g1_descricao', $filtro)
                   ->orLike('g1_observacao', $filtro)
                   ->groupEnd();
        }
        
        if (!empty($dataInicio)) {
            $builder->where('g1_data >=', $dataInicio);
        }
        
        if (!empty($dataFim)) {
            $builder->where('g1_data <=', $dataFim);
        }
        
        return $builder->orderBy('g1_data', 'DESC')
                      ->paginate($perPage);
    }
    
    /**
     * Calcula estatísticas das garantias
     */
    public function getEstatisticas()
    {
        $totalGarantias = $this->countAllResults();
        $garantiasAtivas = $this->where('g1_deleted_at', null)->countAllResults();
        
        $porMes = $this->select("DATE_FORMAT(g1_data, '%Y-%m') as mes, COUNT(*) as total")
                      ->groupBy('mes')
                      ->orderBy('mes', 'DESC')
                      ->limit(12)
                      ->findAll();
        
        return [
            'total_garantias' => $totalGarantias,
            'garantias_ativas' => $garantiasAtivas,
            'por_mes' => $porMes
        ];
    }
    
    /**
     * Verifica se a garantia está válida
     */
    public function garantiaEstaValida($garantiaId)
    {
        $garantia = $this->find($garantiaId);
        
        if (!$garantia) {
            return false;
        }
        
        $hoje = new \DateTime();
        $dataGarantia = new \DateTime($garantia->g1_data);
        
        // Considera a garantia válida por 1 ano a partir da data
        $dataExpiracao = clone $dataGarantia;
        $dataExpiracao->modify('+1 year');
        
        return $hoje <= $dataExpiracao;
    }
    
    /**
     * Calcula dias restantes da garantia
     */
    public function diasRestantesGarantia($garantiaId)
    {
        $garantia = $this->find($garantiaId);
        
        if (!$garantia) {
            return 0;
        }
        
        $hoje = new \DateTime();
        $dataGarantia = new \DateTime($garantia->g1_data);
        $dataExpiracao = clone $dataGarantia;
        $dataExpiracao->modify('+1 year');
        
        if ($hoje > $dataExpiracao) {
            return 0; // Garantia expirada
        }
        
        $diferenca = $hoje->diff($dataExpiracao);
        return $diferenca->days;
    }
    
    /**
     * Busca garantias que expiram em breve (30 dias ou menos)
     */
    public function buscarExpirandoEmBreve($dias = 30, $limit = 50)
    {
        $dataLimite = new \DateTime();
        $dataLimite->modify("+$dias days");
        
        return $this->where('g1_deleted_at', null)
                   ->where("DATE_ADD(g1_data, INTERVAL 1 YEAR) >=", date('Y-m-d'))
                   ->where("DATE_ADD(g1_data, INTERVAL 1 YEAR) <=", $dataLimite->format('Y-m-d'))
                   ->orderBy("DATE_ADD(g1_data, INTERVAL 1 YEAR)", 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias expiradas
     */
    public function buscarExpiradas($limit = 50)
    {
        return $this->where('g1_deleted_at', null)
                   ->where("DATE_ADD(g1_data, INTERVAL 1 YEAR) <", date('Y-m-d'))
                   ->orderBy("DATE_ADD(g1_data, INTERVAL 1 YEAR)", 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca garantias para select dropdown
     */
    public function buscarParaSelect()
    {
        $garantias = $this->select('g1_id, g1_nome, g1_data')
                         ->where('g1_deleted_at', null)
                         ->orderBy('g1_nome', 'ASC')
                         ->findAll();
        
        $options = ['' => 'Selecione uma garantia...'];
        
        foreach ($garantias as $garantia) {
            $dataFormatada = date('d/m/Y', strtotime($garantia->g1_data));
            $options[$garantia->g1_id] = "{$garantia->g1_nome} ({$dataFormatada})";
        }
        
        return $options;
    }
    
    /**
     * Cria uma nova garantia com base em um produto
     */
    public function criarGarantiaParaProduto($nome, $descricao, $observacao = '')
    {
        $dados = [
            'g1_nome' => $nome,
            'g1_descricao' => $descricao,
            'g1_observacao' => $observacao,
            'g1_data' => date('Y-m-d'),
            'g1_data_garantia' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($dados);
    }
    
    /**
     * Atualiza data da garantia
     */
    public function atualizarDataGarantia($garantiaId, $novaData)
    {
        return $this->update($garantiaId, [
            'g1_data' => $novaData,
            'g1_data_garantia' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Busca garantias recentes
     */
    public function buscarRecentes($limit = 10)
    {
        return $this->where('g1_deleted_at', null)
                   ->orderBy('g1_created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}