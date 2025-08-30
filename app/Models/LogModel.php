<?php 
namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'l2_logs';
    protected $primaryKey = 'l2_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'l2_created_at';
    protected $updatedField = 'l2_updated_at';
    protected $deletedField = 'l2_deleted_at';
    
    protected $allowedFields = [
        'l2_id_usuario',
        'l2_tipo_log',
        'l2_acao',
        'l2_detalhes',
        'l2_valor_envolvido',
        'l2_id_referencia',
        'l2_ip_address',
        'l2_user_agent',
        'l2_status',
        'l2_data_hora',
        'l2_sessao_id',
        'l2_created_at',
        'l2_updated_at',
        'l2_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'l2_id_usuario' => 'required|integer|is_not_unique[u1_usuarios.u1_id]',
        'l2_tipo_log' => 'required|in_list[login,logout,caixa_abertura,caixa_fechamento,venda_iniciada,venda_finalizada,venda_cancelada,produto_adicionado,produto_removido,cliente_cadastrado,cliente_editado,produto_cadastrado,produto_editado,fornecedor_cadastrado,fornecedor_editado,categoria_cadastrada,categoria_editada,usuario_cadastrado,usuario_editado,senha_alterada,relatorio_gerado,backup_realizado,erro_sistema,acesso_negado,configuracao_alterada]',
        'l2_acao' => 'required|max_length[500]',
        'l2_detalhes' => 'permit_empty',
        'l2_valor_envolvido' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'l2_id_referencia' => 'permit_empty|integer',
        'l2_ip_address' => 'permit_empty|max_length[45]|valid_ip',
        'l2_user_agent' => 'permit_empty',
        'l2_status' => 'permit_empty|in_list[sucesso,erro,pendente,cancelado]',
        'l2_sessao_id' => 'permit_empty|max_length[255]',
        'l2_data_hora' => 'permit_empty|valid_date'
    ];
    
    protected $validationMessages = [
        'l2_id_usuario' => [
            'is_not_unique' => 'O usuário selecionado não existe'
        ],
        'l2_tipo_log' => [
            'in_list' => 'Tipo de log inválido'
        ],
        'l2_status' => [
            'in_list' => 'Status deve ser: sucesso, erro, pendente ou cancelado'
        ],
        'l2_ip_address' => [
            'valid_ip' => 'Endereço IP inválido'
        ]
    ];
    
    // Tipos de log disponíveis
    const TIPO_LOGIN = 'login';
    const TIPO_LOGOUT = 'logout';
    const TIPO_CAIXA_ABERTURA = 'caixa_abertura';
    const TIPO_CAIXA_FECHAMENTO = 'caixa_fechamento';
    const TIPO_VENDA_INICIADA = 'venda_iniciada';
    const TIPO_VENDA_FINALIZADA = 'venda_finalizada';
    const TIPO_VENDA_CANCELADA = 'venda_cancelada';
    const TIPO_PRODUTO_ADICIONADO = 'produto_adicionado';
    const TIPO_PRODUTO_REMOVIDO = 'produto_removido';
    const TIPO_CLIENTE_CADASTRADO = 'cliente_cadastrado';
    const TIPO_CLIENTE_EDITADO = 'cliente_editado';
    const TIPO_PRODUTO_CADASTRADO = 'produto_cadastrado';
    const TIPO_PRODUTO_EDITADO = 'produto_editado';
    const TIPO_FORNECEDOR_CADASTRADO = 'fornecedor_cadastrado';
    const TIPO_FORNECEDOR_EDITADO = 'fornecedor_editado';
    const TIPO_CATEGORIA_CADASTRADA = 'categoria_cadastrada';
    const TIPO_CATEGORIA_EDITADA = 'categoria_editada';
    const TIPO_USUARIO_CADASTRADO = 'usuario_cadastrado';
    const TIPO_USUARIO_EDITADO = 'usuario_editado';
    const TIPO_SENHA_ALTERADA = 'senha_alterada';
    const TIPO_RELATORIO_GERADO = 'relatorio_gerado';
    const TIPO_BACKUP_REALIZADO = 'backup_realizado';
    const TIPO_ERRO_SISTEMA = 'erro_sistema';
    const TIPO_ACESSO_NEGADO = 'acesso_negado';
    const TIPO_CONFIGURACAO_ALTERADA = 'configuracao_alterada';
    
    // Status disponíveis
    const STATUS_SUCESSO = 'sucesso';
    const STATUS_ERRO = 'erro';
    const STATUS_PENDENTE = 'pendente';
    const STATUS_CANCELADO = 'cancelado';
    
    // Callback para configurar dados automáticos
    protected $beforeInsert = ['configurarDadosAutomaticos'];
    
    /**
     * Configura dados automáticos antes de inserir
     */
    protected function configurarDadosAutomaticos(array $data)
    {
        $request = service('request');
        
        // Configura IP address se não informado
        if (empty($data['data']['l2_ip_address'])) {
            $data['data']['l2_ip_address'] = $request->getIPAddress();
        }
        
        // Configura User Agent se não informado
        if (empty($data['data']['l2_user_agent'])) {
            $data['data']['l2_user_agent'] = $request->getUserAgent();
        }
        
        // Configura Session ID se não informado
        if (empty($data['data']['l2_sessao_id']) && session_status() === PHP_SESSION_ACTIVE) {
            $data['data']['l2_sessao_id'] = session_id();
        }
        
        // Configura data/hora se não informada
        if (empty($data['data']['l2_data_hora'])) {
            $data['data']['l2_data_hora'] = date('Y-m-d H:i:s');
        }
        
        return $data;
    }
    
    /**
     * Registra um novo log no sistema
     */
    public function registrarLog($idUsuario, $tipoLog, $acao, $detalhes = null, $valor = null, $idReferencia = null, $status = 'sucesso')
    {
        $dados = [
            'l2_id_usuario' => $idUsuario,
            'l2_tipo_log' => $tipoLog,
            'l2_acao' => $acao,
            'l2_detalhes' => $detalhes,
            'l2_valor_envolvido' => $valor,
            'l2_id_referencia' => $idReferencia,
            'l2_status' => $status
        ];
        
        return $this->insert($dados);
    }
    
    /**
     * Busca logs por usuário
     */
    public function buscarPorUsuario($idUsuario, $limit = 100)
    {
        return $this->where('l2_id_usuario', $idUsuario)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs por tipo
     */
    public function buscarPorTipo($tipoLog, $limit = 100)
    {
        return $this->where('l2_tipo_log', $tipoLog)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs por período
     */
    public function buscarPorPeriodo($dataInicio, $dataFim, $limit = 1000)
    {
        return $this->where('l2_data_hora >=', $dataInicio)
                   ->where('l2_data_hora <=', $dataFim)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs por status
     */
    public function buscarPorStatus($status, $limit = 100)
    {
        return $this->where('l2_status', $status)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs por ID de referência
     */
    public function buscarPorReferencia($idReferencia, $limit = 50)
    {
        return $this->where('l2_id_referencia', $idReferencia)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs com join na tabela de usuários
     */
    public function buscarComUsuarios($limit = 100)
    {
        return $this->select('l2_logs.*, u1_usuarios.u1_nome, u1_usuarios.u1_usuario_acesso')
                   ->join('u1_usuarios', 'u1_usuarios.u1_id = l2_logs.l2_id_usuario')
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs com paginação
     */
    public function buscarPaginados($perPage = 50, $page = 1)
    {
        return $this->select('l2_logs.*, u1_usuarios.u1_nome')
                   ->join('u1_usuarios', 'u1_usuarios.u1_id = l2_logs.l2_id_usuario')
                   ->orderBy('l2_data_hora', 'DESC')
                   ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Busca logs com filtros avançados
     */
    public function buscarComFiltros($filtros = [], $perPage = 50)
    {
        $builder = $this->select('l2_logs.*, u1_usuarios.u1_nome')
                       ->join('u1_usuarios', 'u1_usuarios.u1_id = l2_logs.l2_id_usuario');
        
        if (!empty($filtros['usuario_id'])) {
            $builder->where('l2_id_usuario', $filtros['usuario_id']);
        }
        
        if (!empty($filtros['tipo_log'])) {
            $builder->where('l2_tipo_log', $filtros['tipo_log']);
        }
        
        if (!empty($filtros['status'])) {
            $builder->where('l2_status', $filtros['status']);
        }
        
        if (!empty($filtros['data_inicio'])) {
            $builder->where('l2_data_hora >=', $filtros['data_inicio']);
        }
        
        if (!empty($filtros['data_fim'])) {
            $builder->where('l2_data_hora <=', $filtros['data_fim']);
        }
        
        if (!empty($filtros['busca'])) {
            $builder->groupStart()
                   ->like('l2_acao', $filtros['busca'])
                   ->orLike('l2_detalhes', $filtros['busca'])
                   ->orLike('u1_usuarios.u1_nome', $filtros['busca'])
                   ->groupEnd();
        }
        
        return $builder->orderBy('l2_data_hora', 'DESC')
                      ->paginate($perPage);
    }
    
    /**
     * Calcula estatísticas dos logs
     */
    public function getEstatisticas($periodo = '30 days')
    {
        $dataInicio = date('Y-m-d H:i:s', strtotime("-$periodo"));
        
        $totalLogs = $this->where('l2_data_hora >=', $dataInicio)->countAllResults();
        
        $porTipo = $this->select('l2_tipo_log, COUNT(*) as total')
                       ->where('l2_data_hora >=', $dataInicio)
                       ->groupBy('l2_tipo_log')
                       ->orderBy('total', 'DESC')
                       ->findAll();
        
        $porStatus = $this->select('l2_status, COUNT(*) as total')
                         ->where('l2_data_hora >=', $dataInicio)
                         ->groupBy('l2_status')
                         ->findAll();
        
        $porUsuario = $this->select('u1_usuarios.u1_nome, COUNT(*) as total')
                          ->join('u1_usuarios', 'u1_usuarios.u1_id = l2_logs.l2_id_usuario')
                          ->where('l2_data_hora >=', $dataInicio)
                          ->groupBy('l2_id_usuario')
                          ->orderBy('total', 'DESC')
                          ->limit(10)
                          ->findAll();
        
        return [
            'total_logs' => $totalLogs,
            'por_tipo' => $porTipo,
            'por_status' => $porStatus,
            'top_usuarios' => $porUsuario
        ];
    }
    
    /**
     * Limpa logs antigos (mais de 90 dias)
     */
    public function limparLogsAntigos($dias = 90)
    {
        $dataLimite = date('Y-m-d H:i:s', strtotime("-$dias days"));
        return $this->where('l2_data_hora <', $dataLimite)->delete();
    }
    
    /**
     * Busca logs de erro do sistema
     */
    public function buscarErrosSistema($limit = 100)
    {
        return $this->where('l2_tipo_log', self::TIPO_ERRO_SISTEMA)
                   ->orWhere('l2_status', self::STATUS_ERRO)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca atividades recentes de um usuário
     */
    public function buscarAtividadesRecentesUsuario($idUsuario, $limit = 20)
    {
        return $this->where('l2_id_usuario', $idUsuario)
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs de acesso (login/logout)
     */
    public function buscarLogsAcesso($limit = 100)
    {
        return $this->whereIn('l2_tipo_log', [self::TIPO_LOGIN, self::TIPO_LOGOUT])
                   ->orderBy('l2_data_hora', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Busca logs de vendas
     */
    public function buscarLogsVendas($limit = 100)
    {
        return $this->whereIn('l2_tipo_log', [
                    self::TIPO_VENDA_INICIADA, 
                    self::TIPO_VENDA_FINALIZADA, 
                    self::TIPO_VENDA_CANCELADA
                ])
                ->orderBy('l2_data_hora', 'DESC')
                ->limit($limit)
                ->findAll();
    }
}