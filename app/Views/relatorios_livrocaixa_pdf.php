<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Livro de Caixa</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 11px; 
            margin: 0; 
            padding: 20px;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }
        .header-left { display: table-cell; vertical-align: middle; width: 120px; }
        .header-right { display: table-cell; vertical-align: middle; padding-left: 20px; }
        .logo { width: 100px; height: 100px; object-fit: contain; border: 1px solid #ddd; border-radius: 5px; padding: 5px; }
        .company-name { font-size: 20px; font-weight: bold; color: #2c5aa0; margin-bottom: 5px; }
        .report-title { font-size: 16px; font-weight: bold; color: #444; margin: 10px 0 5px 0; }
        .meta-info { font-size: 10px; color: #666; line-height: 1.4; }
        .period-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2c5aa0; }
        .period-title { font-weight: bold; color: #2c5aa0; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10px; }
        th { background: linear-gradient(to bottom, #f8f9fa, #e9ecef); color: #495057; font-weight: bold; padding: 12px 8px; border: 1px solid #dee2e6; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 10px 8px; border: 1px solid #dee2e6; vertical-align: middle; }
        tbody tr:nth-child(even) { background-color: #f8f9fa; }
        tbody tr:hover { background-color: #e3f2fd; }
        .text-right { text-align: right; font-weight: bold; }
        .text-center { text-align: center; }
        
        .operacao { padding: 4px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .operacao-abertura { background: #d1edff; color: #0c5460; }
        .operacao-fechamento { background: #d4edda; color: #155724; }
        .operacao-sangria { background: #f8d7da; color: #721c24; }
        .operacao-suprimento { background: #fff3cd; color: #856404; }
        
        .status { padding: 4px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-aberto { background: #fff3cd; color: #856404; }
        .status-fechado { background: #d4edda; color: #155724; }
        .status-conferido { background: #d1edff; color: #0c5460; }
        
        .saldo-positivo { color: #28a745; font-weight: bold; }
        .saldo-negativo { color: #dc3545; font-weight: bold; }
        .saldo-neutro { color: #6c757d; font-weight: bold; }
        
        .total-row { background: #2c5aa0 !important; color: white !important; font-weight: bold; }
        .total-row td { border-color: #2c5aa0; }
        
        .summary { margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; border: 1px solid #dee2e6; }
        .summary-title { font-weight: bold; color: #2c5aa0; margin-bottom: 10px; }
        .summary-item { display: inline-block; margin-right: 30px; font-size: 10px; }
        .summary-value { font-weight: bold; color: #495057; }
        
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #dee2e6; text-align: center; font-size: 9px; color: #6c757d; }
        .no-data { text-align: center; padding: 40px; color: #6c757d; font-style: italic; }
    </style>
</head>
<body>
<?php
use App\Helpers\ConfigHelper;
$empresa = ConfigHelper::empresa();
$emitidoEm = date('d/m/Y H:i');

// Use provided logo or fall back to ConfigHelper
$logoDataUri = $logo ?? null;
if (!$logoDataUri) {
    $logoPath = ConfigHelper::get('c3_logo_path') ?? null;
    if ($logoPath && !filter_var($logoPath, FILTER_VALIDATE_URL)) {
        $logoDataUri = base_url($logoPath);
    } else {
        $logoDataUri = $logoPath;
    }
}

$totalOperacoes = count($operacoes ?? []);

function formatOperacao($tipo) {
    $class = 'operacao ';
    switch (strtolower($tipo)) {
        case 'abertura': $class .= 'operacao-abertura'; break;
        case 'fechamento': $class .= 'operacao-fechamento'; break;
        case 'sangria': $class .= 'operacao-sangria'; break;
        case 'suprimento': $class .= 'operacao-suprimento'; break;
        default: $class .= 'operacao-abertura';
    }
    return '<span class="' . $class . '">' . esc(ucfirst($tipo)) . '</span>';
}

function formatStatus($status) {
    $class = 'status ';
    switch (strtolower($status)) {
        case 'aberto': $class .= 'status-aberto'; break;
        case 'fechado': $class .= 'status-fechado'; break;
        case 'conferido': $class .= 'status-conferido'; break;
        default: $class .= 'status-aberto';
    }
    return '<span class="' . $class . '">' . esc(ucfirst($status)) . '</span>';
}

function formatSaldo($valor) {
    $class = 'saldo-neutro';
    if ($valor > 0) $class = 'saldo-positivo';
    else if ($valor < 0) $class = 'saldo-negativo';
    
    return '<span class="' . $class . '">R$ ' . number_format($valor, 2, ',', '.') . '</span>';
}
?>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <?php if (!empty($logoDataUri)): ?>
            <img src="<?= esc($logoDataUri) ?>" class="logo" alt="Logo da Empresa">
        <?php endif; ?>
    </div>
    <div class="header-right">
        <div class="company-name"><?= esc($empresa) ?></div>
        <div class="report-title">Livro de Caixa</div>
        <div class="meta-info">
            <strong>Emitido em:</strong> <?= esc($emitidoEm) ?><br>
            <strong>Usuário:</strong> Sistema<br>
            <strong>Página:</strong> 1 de 1
        </div>
    </div>
</div>

<!-- Period Info -->
<div class="period-info">
    <div class="period-title">Período do Relatório</div>
    <strong>De:</strong> <?= date('d/m/Y', strtotime($data_inicial)) ?>
    <strong>até:</strong> <?= date('d/m/Y', strtotime($data_final)) ?>
</div>

<?php if (!empty($operacoes) && is_array($operacoes)): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Data/Hora</th>
                <th style="width: 10%;">Operação</th>
                <th style="width: 20%;">Usuário</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 12%;">Valor Inicial</th>
                <th style="width: 12%;">Valor Final</th>
                <th style="width: 12%;">Vendas</th>
                <th style="width: 6%;">Qtd</th>
                <th style="width: 12%;">Diferença</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($operacoes as $i => $op): ?>
                <tr>
                    <td class="text-center"><?= date('d/m/Y H:i', strtotime($op['data_operacao'])) ?></td>
                    <td class="text-center"><?= formatOperacao($op['tipo_operacao'] ?? '-') ?></td>
                    <td><?= esc($op['usuario_nome'] ?? '-') ?></td>
                    <td class="text-center"><?= formatStatus($op['status_caixa'] ?? '-') ?></td>
                    <td class="text-right">R$ <?= number_format($op['valor_inicial'] ?? 0, 2, ',', '.') ?></td>
                    <td class="text-right">R$ <?= number_format($op['valor_final'] ?? 0, 2, ',', '.') ?></td>
                    <td class="text-right">R$ <?= number_format($op['valor_vendas'] ?? 0, 2, ',', '.') ?></td>
                    <td class="text-center"><?= esc($op['numero_vendas'] ?? 0) ?></td>
                    <td class="text-right"><?= formatSaldo($op['valor_diferenca'] ?? 0) ?></td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Linha de Total -->
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAIS:</strong></td>
                <td class="text-right"><strong>R$ <?= number_format($totais['total_inicial'], 2, ',', '.') ?></strong></td>
                <td class="text-right"><strong>R$ <?= number_format($totais['total_final'], 2, ',', '.') ?></strong></td>
                <td class="text-right"><strong>R$ <?= number_format($totais['total_vendas'], 2, ',', '.') ?></strong></td>
                <td class="text-center"><strong><?= $totais['total_operacoes'] ?></strong></td>
                <td class="text-right"><strong>R$ <?= number_format($totais['total_diferenca'], 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-title">Resumo do Período</div>
        <div class="summary-item">
            <strong>Total de Operações:</strong>
            <span class="summary-value"><?= $totais['total_operacoes'] ?></span>
        </div>
        <div class="summary-item">
            <strong>Valor Total Inicial:</strong>
            <span class="summary-value">R$ <?= number_format($totais['total_inicial'], 2, ',', '.') ?></span>
        </div>
        <div class="summary-item">
            <strong>Valor Total Final:</strong>
            <span class="summary-value">R$ <?= number_format($totais['total_final'], 2, ',', '.') ?></span>
        </div>
        <div class="summary-item">
            <strong>Total em Vendas:</strong>
            <span class="summary-value">R$ <?= number_format($totais['total_vendas'], 2, ',', '.') ?></span>
        </div>
        <div class="summary-item">
            <strong>Saldo Líquido:</strong>
            <?= formatSaldo($totais['saldo_liquido']) ?>
        </div>
    </div>

<?php else: ?>
    <div class="no-data">
        <strong>Nenhuma operação de caixa encontrada</strong><br>
        Não foram encontradas operações de caixa para o período selecionado.
    </div>
<?php endif; ?>

<div class="footer">
    <p>Relatório gerado automaticamente pelo sistema <?= esc($empresa) ?> em <?= date('d/m/Y \à\s H:i') ?></p>
    <p>Este documento é confidencial e destinado exclusivamente ao uso interno.</p>
</div>
</body>
</html>