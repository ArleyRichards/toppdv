<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Comissões por Vendedor</title>
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
        
        .vendedor-section { margin-bottom: 30px; page-break-inside: avoid; }
        .vendedor-header { background: #2c5aa0; color: white; padding: 10px; font-weight: bold; font-size: 12px; }
        .vendedor-summary { background: #e3f2fd; padding: 10px; font-size: 10px; border: 1px solid #ddd; }
        .summary-item { display: inline-block; margin-right: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9px; }
        th { background: linear-gradient(to bottom, #f8f9fa, #e9ecef); color: #495057; font-weight: bold; padding: 8px 6px; border: 1px solid #dee2e6; text-align: left; font-size: 8px; text-transform: uppercase; }
        td { padding: 8px 6px; border: 1px solid #dee2e6; vertical-align: middle; }
        tbody tr:nth-child(even) { background-color: #f8f9fa; }
        .text-right { text-align: right; font-weight: bold; }
        .text-center { text-align: center; }
        
        .total-section { margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px; border: 1px solid #dee2e6; }
        .total-title { font-weight: bold; color: #2c5aa0; margin-bottom: 10px; font-size: 14px; }
        .total-value { font-size: 16px; font-weight: bold; color: #495057; }
        
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

$totalVendedores = count($comissoes ?? []);
$totalComissao = $total_geral_comissao ?? 0;
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
        <div class="report-title">Relatório de Comissões por Vendedor</div>
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

<?php if (!empty($comissoes) && is_array($comissoes)): ?>
    <?php foreach ($comissoes as $vendedorId => $vendedor): ?>
        <div class="vendedor-section">
            <div class="vendedor-header">
                <?= esc($vendedor['vendedor_nome']) ?>
            </div>
            
            <div class="vendedor-summary">
                <div class="summary-item">
                    <strong>Total de Vendas:</strong> R$ <?= number_format($vendedor['total_vendas'], 2, ',', '.') ?>
                </div>
                <div class="summary-item">
                    <strong>Total de Lucro:</strong> R$ <?= number_format($vendedor['total_lucro'], 2, ',', '.') ?>
                </div>
                <div class="summary-item">
                    <strong>Total de Comissão:</strong> R$ <?= number_format($vendedor['total_comissao'], 2, ',', '.') ?>
                </div>
            </div>
            
            <?php if (!empty($vendedor['detalhes'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%;">Venda</th>
                            <th style="width: 10%;">Data</th>
                            <th style="width: 25%;">Produto</th>
                            <th style="width: 15%;">Categoria</th>
                            <th style="width: 6%;">Qtd</th>
                            <th style="width: 9%;">P. Venda</th>
                            <th style="width: 9%;">P. Compra</th>
                            <th style="width: 8%;">Lucro</th>
                            <th style="width: 6%;">% Com.</th>
                            <th style="width: 10%;">Comissão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendedor['detalhes'] as $detalhe): ?>
                            <tr>
                                <td class="text-center"><?= esc($detalhe['venda_id']) ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($detalhe['data_venda'])) ?></td>
                                <td><?= esc($detalhe['produto_nome']) ?></td>
                                <td><?= esc($detalhe['categoria_nome']) ?></td>
                                <td class="text-center"><?= esc($detalhe['quantidade']) ?></td>
                                <td class="text-right">R$ <?= number_format($detalhe['preco_venda'], 2, ',', '.') ?></td>
                                <td class="text-right">R$ <?= number_format($detalhe['preco_compra'], 2, ',', '.') ?></td>
                                <td class="text-right">R$ <?= number_format($detalhe['lucro_total'], 2, ',', '.') ?></td>
                                <td class="text-center"><?= number_format($detalhe['categoria_comissao'], 2, ',', '.') ?>%</td>
                                <td class="text-right">R$ <?= number_format($detalhe['comissao_valor'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="total-section">
        <div class="total-title">Resumo Geral</div>
        <div class="summary-item">
            <strong>Total de Vendedores:</strong> <span class="total-value"><?= $totalVendedores ?></span>
        </div>
        <div class="summary-item" style="margin-left: 30px;">
            <strong>Total Geral de Comissões:</strong> <span class="total-value">R$ <?= number_format($totalComissao, 2, ',', '.') ?></span>
        </div>
    </div>

<?php else: ?>
    <div class="no-data">
        <strong>Nenhuma comissão encontrada</strong><br>
        Não foram encontradas vendas com produtos para o período e filtros selecionados.
    </div>
<?php endif; ?>

<div class="footer">
    <p>Relatório gerado automaticamente pelo sistema <?= esc($empresa) ?> em <?= date('d/m/Y \à\s H:i') ?></p>
    <p>Este documento é confidencial e destinado exclusivamente ao uso interno.</p>
</div>
</body>
</html>