<?php

namespace App\Models;

use CodeIgniter\Model;

class LivroCaixaModel extends Model
{
    protected $table = 'l3_livro_caixa';
    protected $primaryKey = 'l3_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'l3_usuario_id',
        'l3_data_operacao',
        'l3_tipo_operacao',
        'l3_valor_inicial',
        'l3_valor_final',
        'l3_valor_vendas',
        'l3_valor_diferenca',
        'l3_status_caixa',
        'l3_observacoes',
        'l3_numero_vendas'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'l3_created_at';
    protected $updatedField = 'l3_updated_at';
    protected $deletedField = 'l3_deleted_at';

    // Validation
    protected $validationRules = [
        'l3_usuario_id' => 'required|is_natural_no_zero',
        'l3_data_operacao' => 'required|valid_date',
        'l3_tipo_operacao' => 'required|in_list[abertura,fechamento,sangria,suprimento]',
        'l3_valor_inicial' => 'required|decimal',
        'l3_valor_final' => 'required|decimal',
        'l3_valor_vendas' => 'required|decimal',
        'l3_valor_diferenca' => 'required|decimal',
        'l3_status_caixa' => 'required|in_list[aberto,fechado,conferido]',
        'l3_numero_vendas' => 'required|is_natural'
    ];

    protected $validationMessages = [
        'l3_usuario_id' => [
            'required' => 'O usuário é obrigatório.',
            'is_natural_no_zero' => 'O usuário deve ser um número válido.'
        ],
        'l3_data_operacao' => [
            'required' => 'A data da operação é obrigatória.',
            'valid_date' => 'A data da operação deve ser válida.'
        ],
        'l3_tipo_operacao' => [
            'required' => 'O tipo de operação é obrigatório.',
            'in_list' => 'O tipo de operação deve ser: abertura, fechamento, sangria ou suprimento.'
        ],
        'l3_valor_inicial' => [
            'required' => 'O valor inicial é obrigatório.',
            'decimal' => 'O valor inicial deve ser um número decimal válido.'
        ],
        'l3_valor_final' => [
            'required' => 'O valor final é obrigatório.',
            'decimal' => 'O valor final deve ser um número decimal válido.'
        ],
        'l3_status_caixa' => [
            'required' => 'O status do caixa é obrigatório.',
            'in_list' => 'O status do caixa deve ser: aberto, fechado ou conferido.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Buscar operações de caixa por período
     */
    public function getOperacoesPorPeriodo($dataInicial, $dataFinal, $usuarioId = null)
    {
        $builder = $this->builder();
        $builder->select('l3_livro_caixa.*, u1_usuarios.u1_nome as usuario_nome')
                ->join('u1_usuarios', 'l3_livro_caixa.l3_usuario_id = u1_usuarios.u1_id', 'left')
                ->where('l3_data_operacao >=', $dataInicial . ' 00:00:00')
                ->where('l3_data_operacao <=', $dataFinal . ' 23:59:59')
                ->orderBy('l3_data_operacao', 'ASC');

        if ($usuarioId) {
            $builder->where('l3_usuario_id', $usuarioId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Calcular saldo do período
     */
    public function calcularSaldoPeriodo($dataInicial, $dataFinal, $usuarioId = null)
    {
        $builder = $this->builder();
        $builder->selectSum('l3_valor_inicial', 'total_inicial')
                ->selectSum('l3_valor_final', 'total_final')
                ->selectSum('l3_valor_vendas', 'total_vendas')
                ->selectSum('l3_valor_diferenca', 'total_diferenca')
                ->selectSum('l3_numero_vendas', 'total_operacoes')
                ->where('l3_data_operacao >=', $dataInicial . ' 00:00:00')
                ->where('l3_data_operacao <=', $dataFinal . ' 23:59:59');

        if ($usuarioId) {
            $builder->where('l3_usuario_id', $usuarioId);
        }

        $result = $builder->get()->getRowArray();
        
        return [
            'total_inicial' => $result['total_inicial'] ?? 0,
            'total_final' => $result['total_final'] ?? 0,
            'total_vendas' => $result['total_vendas'] ?? 0,
            'total_diferenca' => $result['total_diferenca'] ?? 0,
            'total_operacoes' => $result['total_operacoes'] ?? 0,
            'saldo_liquido' => ($result['total_final'] ?? 0) - ($result['total_inicial'] ?? 0)
        ];
    }

    /**
     * Buscar último caixa aberto por usuário
     */
    public function getCaixaAbertoPorUsuario($usuarioId)
    {
        return $this->where('l3_usuario_id', $usuarioId)
                    ->where('l3_status_caixa', 'aberto')
                    ->orderBy('l3_data_operacao', 'DESC')
                    ->first();
    }

    /**
     * Verificar se existe caixa aberto
     */
    public function existeCaixaAberto($usuarioId = null)
    {
        $builder = $this->builder();
        $builder->where('l3_status_caixa', 'aberto');
        
        if ($usuarioId) {
            $builder->where('l3_usuario_id', $usuarioId);
        }
        
        return $builder->countAllResults() > 0;
    }
}