<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdemModel extends Model
{
    protected $table            = 'o1_ordens';
    protected $primaryKey       = 'o1_id';
    // Table definition does not include AUTO_INCREMENT for o1_id
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'o1_numero_ordem',
        'o1_cliente_id',
        'o1_equipamento',
        'o1_marca',
        'o1_modelo',
        'o1_numero_serie',
        'o1_defeito_relatado',
        'o1_observacoes_entrada',
        'o1_acessorios_entrada',
        'o1_estado_aparente',
        'o1_tecnico_id',
        'o1_status',
        'o1_prioridade',
        'o1_data_entrada',
        'o1_data_previsao',
        'o1_data_conclusao',
        'o1_data_entrega',
        'o1_valor_servicos',
        'o1_valor_produtos',
        'o1_valor_total',
        'o1_desconto',
        'o1_valor_final',
        'o1_laudo_tecnico',
        'o1_observacoes_conclusao',
    'o1_data_faturamento',
        'o1_garantia_servico',
        'o1_created_at',
        'o1_updated_at',
        'o1_deleted_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'o1_id' => 'int',
        'o1_cliente_id' => 'int',
        'o1_tecnico_id' => 'int',
        'o1_valor_servicos' => 'float',
        'o1_valor_produtos' => 'float',
        'o1_valor_total' => 'float',
        'o1_desconto' => 'float',
        'o1_valor_final' => 'float',
        'o1_garantia_servico' => 'int',
    ];
    protected array $castHandlers = [];

    // Dates
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'o1_created_at';
    protected $updatedField  = 'o1_updated_at';
    protected $deletedField  = 'o1_deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
