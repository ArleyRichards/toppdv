<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para produtos da ordem de serviço
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class ProdutosOrdemModel extends Model
{
    protected $table = 'p3_produtos_ordem';
    protected $primaryKey = 'p3_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'p3_ordem_id',
        'p3_produto_id',
        'p3_quantidade',
        'p3_valor_unitario',
        'p3_valor_total',
        'p3_observacoes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'p3_created_at';
    protected $updatedField = 'p3_updated_at';
    protected $deletedField = 'p3_deleted_at';

    // Validation
    protected $validationRules = [
        'p3_ordem_id' => 'required|integer',
        'p3_produto_id' => 'required|integer',
        'p3_quantidade' => 'required|integer|greater_than[0]',
        'p3_valor_unitario' => 'required|decimal',
        'p3_valor_total' => 'required|decimal'
    ];

    protected $validationMessages = [
        'p3_ordem_id' => [
            'required' => 'ID da ordem é obrigatório',
            'integer' => 'ID da ordem deve ser um número inteiro'
        ],
        'p3_produto_id' => [
            'required' => 'ID do produto é obrigatório',
            'integer' => 'ID do produto deve ser um número inteiro'
        ],
        'p3_quantidade' => [
            'required' => 'Quantidade é obrigatória',
            'integer' => 'Quantidade deve ser um número inteiro',
            'greater_than' => 'Quantidade deve ser maior que 0'
        ],
        'p3_valor_unitario' => [
            'required' => 'Valor unitário é obrigatório',
            'decimal' => 'Valor unitário deve ser um número decimal'
        ],
        'p3_valor_total' => [
            'required' => 'Valor total é obrigatório',
            'decimal' => 'Valor total deve ser um número decimal'
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
