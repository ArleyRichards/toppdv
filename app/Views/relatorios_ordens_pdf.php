<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Ordens de Serviço</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 11px; 
            margin: 0; 
            padding: 20px;
            color: #333;
        }
        /* reuse same styles as vendas template */
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
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10px; }
        th { background: linear-gradient(to bottom, #f8f9fa, #e9ecef); color: #495057; font-weight: bold; padding: 12px 8px; border: 1px solid #dee2e6; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 10px 8px; border: 1px solid #dee2e6; vertical-align: middle; }
        tbody tr:nth-child(even) { background-color: #f8f9fa; }
        tbody tr:hover { background-color: #e3f2fd; }
        .text-right { text-align: right; font-weight: bold; }
        .text-center { text-align: center; }
        .status { padding: 4px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-aguardando { background: #fff3cd; color: #856404; }
        .status-concluido { background: #d1edff; color: #0c5460; }
        .status-cancelado { background: #e2e3e5; color: #383d41; }
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
$logo = ConfigHelper::get('c3_logo_path') ?? null;
$emitidoEm = date('d/m/Y H:i');

// Resolve logo similarly (prefer controller to pass logo if available)
$logoDataUri = null;
if (!empty($logo)) {
    if (filter_var($logo, FILTER_VALIDATE_URL)) {
        $logoDataUri = $logo;
    } else {
        $possible = [];
        $possible[] = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($logo, DIRECTORY_SEPARATOR);
        $possible[] = rtrim(APPPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($logo, DIRECTORY_SEPARATOR);
        $possible[] = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($logo, DIRECTORY_SEPARATOR);
        foreach ($possible as $p) {
            if (file_exists($p) && is_readable($p)) {
                $data = @file_get_contents($p);
                if ($data !== false) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $p);
                    finfo_close($finfo);
                    $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode($data);
                    break;
                }
            }
        }
        if (!$logoDataUri) $logoDataUri = base_url($logo);
    }
}

$totalOrdens = count($ordens ?? []);
$valorTotal = 0;
if (!empty($ordens)) {
    foreach ($ordens as $o) {
        $valorTotal += $o['valor_total'] ?? ($o['valor_total'] ?? 0);
    }
}

function formatStatusOrdem($status) {
    $class = 'status ';
    switch (strtolower($status)) {
        case 'aguardando': $class .= 'status-aguardando'; break;
        case 'concluído':
        case 'concluido': $class .= 'status-concluido'; break;
        case 'cancelado': $class .= 'status-cancelado'; break;
        default: $class .= 'status-aguardando';
    }
    return '<span class="' . $class . '">' . esc($status) . '</span>';
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
        <div class="report-title">Relatório de Ordens de Serviço</div>
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

<?php if (!empty($ordens) && is_array($ordens)): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Ordem Nº</th>
                <th style="width: 14%;">Data Entrada</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 30%;">Cliente</th>
                <th style="width: 20%;">Técnico</th>
                <th style="width: 12%; text-align: right;">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordens as $i => $o): ?>
                <tr>
                    <td class="text-center"><?= esc($o['numero_ordem'] ?? ($o['id'] ?? '-')) ?></td>
                    <td class="text-center"><?= isset($o['data']) ? date('d/m/Y H:i', strtotime($o['data'])) : '-' ?></td>
                    <td class="text-center"><?= formatStatusOrdem($o['status'] ?? '-') ?></td>
                    <td><?= esc($o['cliente_nome'] ?? '-') ?></td>
                    <td><?= esc($o['tecnico_nome'] ?? '-') ?></td>
                    <td class="text-right">R$ <?= number_format($o['valor_total'] ?? 0, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>

            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL GERAL:</strong></td>
                <td class="text-right"><strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-title">Resumo do Relatório</div>
        <div class="summary-item">
            <strong>Total de Ordens:</strong>
            <span class="summary-value"><?= $totalOrdens ?></span>
        </div>
        <div class="summary-item">
            <strong>Valor Total:</strong>
            <span class="summary-value">R$ <?= number_format($valorTotal, 2, ',', '.') ?></span>
        </div>
    </div>

<?php else: ?>
    <div class="no-data">
        <strong>Nenhuma ordem encontrada</strong><br>
        Não foram encontradas ordens para o período e filtros selecionados.
    </div>
<?php endif; ?>

<div class="footer">
    <p>Relatório gerado automaticamente pelo sistema <?= esc($empresa) ?> em <?= date('d/m/Y \à\s H:i') ?></p>
    <p>Este documento é confidencial e destinado exclusivamente ao uso interno.</p>
</div>
</body>
</html>