<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Libraries\CupomService;

// Mock venda e produtos
$venda = [
    'v1_id' => 9999,
    'v1_numero_venda' => 'VD9999',
    'v1_created_at' => date('Y-m-d H:i:s'),
    'v1_vendedor_nome' => 'João da Silva',
    'v1_tipo_de_pagamento' => 'dinheiro',
    'v1_valor_total' => 123.45,
    'v1_valor_a_ser_pago' => 123.45,
    'v1_desconto' => 0,
    'v1_observacoes' => 'Cupom: Nome cliente: SIM, Garantias: SIM'
];

$produtos = [
    [
        'p2_produto_id' => 1,
        'p2_quantidade' => 1,
        'p2_valor_unitario' => 100.00,
        'p2_subtotal' => 100.00,
        'nome_produto' => 'Café Expresso – Edição São Paulo'
    ],
    [
        'p2_produto_id' => 2,
        'p2_quantidade' => 1,
        'p2_valor_unitario' => 23.45,
        'p2_subtotal' => 23.45,
        'nome_produto' => 'Pão de Queijo – 4 unidades'
    ]
];

$service = new CupomService();
$result = $service->salvarCupom($venda, $produtos, ['imprimir_nome_cliente' => true, 'imprimir_garantias' => true]);

echo "Gerado: " . $result['caminho'] . PHP_EOL;
