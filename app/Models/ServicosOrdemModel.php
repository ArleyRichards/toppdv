<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para serviços da ordem de serviço
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class ServicosOrdemModel extends Model
{
    protected $table = 's2_servicos_ordem';
    protected $primaryKey = 's2_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        's2_ordem_id',
        's2_servico_id',
        's2_quantidade',
        's2_valor_unitario',
        's2_valor_total',
        's2_observacoes',
        's2_status',
        's2_tecnico_id',
        's2_data_inicio',
        's2_data_conclusao'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 's2_created_at';
    protected $updatedField = 's2_updated_at';
    protected $deletedField = 's2_deleted_at';

    // Validation
    protected $validationRules = [
        's2_ordem_id' => 'required|integer',
        's2_servico_id' => 'required|integer',
        's2_quantidade' => 'required|integer|greater_than[0]',
        's2_valor_unitario' => 'required|decimal',
        's2_valor_total' => 'required|decimal',
        's2_status' => 'in_list[Pendente,Executando,Concluído,Cancelado]'
    ];

    protected $validationMessages = [
        's2_ordem_id' => [
            'required' => 'ID da ordem é obrigatório',
            'integer' => 'ID da ordem deve ser um número inteiro'
        ],
        's2_servico_id' => [
            'required' => 'ID do serviço é obrigatório',
            'integer' => 'ID do serviço deve ser um número inteiro'
        ],
        's2_quantidade' => [
            'required' => 'Quantidade é obrigatória',
            'integer' => 'Quantidade deve ser um número inteiro',
            'greater_than' => 'Quantidade deve ser maior que 0'
        ],
        's2_valor_unitario' => [
            'required' => 'Valor unitário é obrigatório',
            'decimal' => 'Valor unitário deve ser um número decimal'
        ],
        's2_valor_total' => [
            'required' => 'Valor total é obrigatório',
            'decimal' => 'Valor total deve ser um número decimal'
        ],
        's2_status' => [
            'in_list' => 'Status deve ser: Pendente, Executando, Concluído ou Cancelado'
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
    protected $afterFind = [];
    protected $afterDelete = [];
}
