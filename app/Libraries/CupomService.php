<?php

namespace App\Libraries;

use TCPDF;
use App\Helpers\ConfigHelper;

class CupomService
{
    private $pdf;
    private $largura = 80; // mm - largura padrão impressora térmica
    private $margemLateral = 3; // mm
    private $empresaNome;
    private $appName;
    private $logoPath;
    private $empresaContato;
    private $empresaEndereco;

    public function __construct()
    {
        // Configurar TCPDF para impressora térmica
        $this->pdf = new TCPDF('P', 'mm', array($this->largura, 200), true, 'UTF-8', false);
        
        // Configurações básicas
        $this->pdf->SetCreator('PDV System');
        // Tentar obter nome da empresa via ConfigHelper, se disponível
        try {
            $this->empresaNome = ConfigHelper::empresa();
            $this->appName = ConfigHelper::appName();
            $this->logoPath = ConfigHelper::get('c3_logo_path') ?? (defined('IMG_PATH') ? IMG_PATH . 'logo.png' : null);
            $this->empresaContato = ConfigHelper::get('c3_telefone') ?? null;
            $this->empresaEndereco = ConfigHelper::get('c3_endereco') ?? null;
        } catch (\Throwable $e) {
            $this->empresaNome = 'Empresa';
            $this->appName = 'PDV';
            $this->logoPath = null;
            $this->empresaContato = null;
            $this->empresaEndereco = null;
        }

        $this->pdf->SetAuthor($this->empresaNome);
        $this->pdf->SetTitle('Cupom Fiscal');
        $this->pdf->SetSubject('Comprovante de Venda');
        
        // Remover header e footer padrão
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        
        // Configurar margens
        $this->pdf->SetMargins($this->margemLateral, 5, $this->margemLateral);
        $this->pdf->SetAutoPageBreak(true, 5);
        
    // Ativar subsetting e configurar fonte Unicode (suporta acentos)
    $this->pdf->setFontSubsetting(true);
    $this->pdf->SetFont('dejavusans', '', 8);
    }

    /**
     * Gerar cupom da venda
     */
    public function gerarCupom($venda, $produtos, $configuracoes = [])
    {
        try {
            $this->pdf->AddPage();
            
            // Cabeçalho da empresa
            $this->adicionarCabecalho();
            
            // Dados da venda
            $this->adicionarDadosVenda($venda);
            
            // Lista de produtos
            $this->adicionarProdutos($produtos);
            
            // Totais
            $this->adicionarTotais($venda);
            
            // Forma de pagamento
            $this->adicionarPagamento($venda);
            
            // Cliente (se configurado)
            if ($configuracoes['imprimir_nome_cliente'] ?? false) {
                $this->adicionarCliente($venda);
            }
            
            // Garantias (se configurado)
            if ($configuracoes['imprimir_garantias'] ?? false) {
                $this->adicionarGarantias();
            }
            
            // Rodapé
            $this->adicionarRodape($venda);
            
            return $this->pdf;
            
        } catch (\Exception $e) {
            log_message('error', 'Erro ao gerar cupom: ' . $e->getMessage());
            throw new \Exception('Erro ao gerar cupom: ' . $e->getMessage());
        }
    }

    /**
     * Salvar cupom em arquivo
     */
    public function salvarCupom($venda, $produtos, $configuracoes = [])
    {
        $pdf = $this->gerarCupom($venda, $produtos, $configuracoes);
        
        // Criar diretório se não existir
        // Usar WRITEPATH quando disponível, senão usar diretório temporário local
        if (defined('WRITEPATH')) {
            $diretorio = WRITEPATH . 'uploads/cupons/';
        } else {
            $diretorio = __DIR__ . '/../../writable/uploads/cupons/';
        }
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }
        
        // Nome do arquivo
        $nomeArquivo = 'cupom_venda_' . $venda['v1_id'] . '_' . date('YmdHis') . '.pdf';
        $caminhoCompleto = $diretorio . $nomeArquivo;
        
        // Salvar arquivo
        $pdf->Output($caminhoCompleto, 'F');
        
        $url = null;
        if (function_exists('base_url')) {
            $url = base_url('writable/uploads/cupons/' . $nomeArquivo);
        }

        return [
            'arquivo' => $nomeArquivo,
            'caminho' => $caminhoCompleto,
            'url' => $url
        ];
    }

    /**
     * Adicionar cabeçalho da empresa
     */
    private function adicionarCabecalho()
    {
        $empresa = $this->empresaNome;
        $appName = $this->appName;

        // Se existir logo, desenhar centralizada (ajustar largura para 40mm)
        if (!empty($this->logoPath) && file_exists($this->logoPath)) {
            try {
                $imgW = 40; // mm
                $this->pdf->Image($this->logoPath, ($this->largura - $imgW) / 2, 6, $imgW, 0, '', '', '', false, 300);
                $this->pdf->Ln(18);
            } catch (\Throwable $e) {
                // Ignorar erro de imagem
            }
        } else {
            $this->pdf->Ln(4);
        }

        // Nome da empresa e sistema centralizado com estilo
        $this->pdf->SetFont('dejavusans', 'B', 11);
        $this->centralizarTexto($empresa ?: $appName);
        $this->pdf->Ln(1);
        $this->pdf->SetFont('dejavusans', '', 8);
        if (!empty($this->empresaEndereco)) {
            $this->centralizarTexto($this->empresaEndereco);
        }
        if (!empty($this->empresaContato)) {
            $this->centralizarTexto('Tel: ' . $this->empresaContato);
        }
        $this->pdf->Ln(2);

        // Linha separadora
        $this->adicionarLinhaSeparadora();
        $this->pdf->Ln(3);
    }

    /**
     * Adicionar dados da venda
     */
    private function adicionarDadosVenda($venda)
    {
    $this->pdf->SetFont('dejavusans', '', 8);
        
        // Número da venda
        $this->pdf->Cell(0, 3, 'CUPOM NAO FISCAL', 0, 1, 'C');
        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 3, 'VENDA: ' . $venda['v1_numero_venda'], 0, 1, 'L');
        
        // Data e hora
        $dataVenda = date('d/m/Y H:i:s', strtotime($venda['v1_created_at']));
        $this->pdf->Cell(0, 3, 'DATA: ' . $dataVenda, 0, 1, 'L');
        
        // Vendedor
        if (!empty($venda['v1_vendedor_nome'])) {
            $vendedorNome = function_exists('mb_strtoupper') ? mb_strtoupper($venda['v1_vendedor_nome'], 'UTF-8') : strtoupper($venda['v1_vendedor_nome']);
            $this->pdf->Cell(0, 3, 'VENDEDOR: ' . $vendedorNome, 0, 1, 'L');
        }
        
        $this->pdf->Ln(2);
        $this->adicionarLinhaSeparadora();
        $this->pdf->Ln(2);
    }

    /**
     * Adicionar lista de produtos
     */
    private function adicionarProdutos($produtos)
    {
    $this->pdf->SetFont('dejavusans', 'B', 8);
        $this->pdf->Cell(0, 3, 'PRODUTOS', 0, 1, 'C');
        $this->pdf->Ln(1);
        
    $this->pdf->SetFont('dejavusans', '', 7);
        
        foreach ($produtos as $produto) {
            // Nome do produto (quebrar linha se necessário)
            $nomeProduto = $produto['nome_produto'] ?? 'Produto';
            $nomeExibicao = function_exists('mb_strtoupper') ? mb_strtoupper($nomeProduto, 'UTF-8') : strtoupper($nomeProduto);
            $this->pdf->Cell(0, 3, $nomeExibicao, 0, 1, 'L');
            
            // Quantidade x Preço = Subtotal
            $quantidade = number_format($produto['p2_quantidade'], 0, ',', '.');
            $precoUnit = number_format($produto['p2_valor_unitario'], 2, ',', '.');
            $subtotal = number_format($produto['p2_subtotal'], 2, ',', '.');
            
            $linha = sprintf('%s x R$ %s = R$ %s', 
                str_pad($quantidade, 3, ' ', STR_PAD_LEFT),
                str_pad($precoUnit, 8, ' ', STR_PAD_LEFT),
                str_pad($subtotal, 10, ' ', STR_PAD_LEFT)
            );
            
            $this->pdf->Cell(0, 3, $linha, 0, 1, 'L');
            $this->pdf->Ln(1);
        }
        
        $this->adicionarLinhaSeparadora();
        $this->pdf->Ln(2);
    }

    /**
     * Adicionar totais da venda
     */
    private function adicionarTotais($venda)
    {
    $this->pdf->SetFont('dejavusans', '', 8);
        
        // Subtotal (se houver desconto)
        if ($venda['v1_desconto'] > 0) {
            $subtotal = $venda['v1_valor_total'] + $venda['v1_desconto'];
            $this->pdf->Cell(0, 3, 'SUBTOTAL: R$ ' . number_format($subtotal, 2, ',', '.'), 0, 1, 'R');
            $this->pdf->Cell(0, 3, 'DESCONTO: R$ ' . number_format($venda['v1_desconto'], 2, ',', '.'), 0, 1, 'R');
        }
        
        // Total
    $this->pdf->SetFont('dejavusans', 'B', 10);
        $this->pdf->Cell(0, 4, 'TOTAL: R$ ' . number_format($venda['v1_valor_total'], 2, ',', '.'), 0, 1, 'R');
        $this->pdf->Ln(2);
    }

    /**
     * Adicionar forma de pagamento
     */
    private function adicionarPagamento($venda)
    {
    $this->pdf->SetFont('dejavusans', '', 8);
        
        // Traduzir tipo de pagamento
        $tiposPagamento = [
            'dinheiro' => 'DINHEIRO',
            'cartao_credito' => 'CARTAO CREDITO',
            'cartao_debito' => 'CARTAO DEBITO',
            'pix' => 'PIX',
            'transferencia' => 'TRANSFERENCIA',
            'boleto' => 'BOLETO',
            'a_prazo' => 'A PRAZO'
        ];
        
    $tipoPagamento = $tiposPagamento[$venda['v1_tipo_de_pagamento']] ?? (function_exists('mb_strtoupper') ? mb_strtoupper($venda['v1_tipo_de_pagamento'], 'UTF-8') : strtoupper($venda['v1_tipo_de_pagamento']));
            $this->pdf->Cell(0, 3, 'PAGAMENTO: ' . $tipoPagamento, 0, 1, 'L');
        
        // Valor pago
        $valorPago = $venda['v1_valor_a_ser_pago'] ?? $venda['v1_valor_total'];
        $this->pdf->Cell(0, 3, 'VALOR PAGO: R$ ' . number_format($valorPago, 2, ',', '.'), 0, 1, 'L');
        
        // Troco (se houver)
        if ($valorPago > $venda['v1_valor_total']) {
            $troco = $valorPago - $venda['v1_valor_total'];
            $this->pdf->Cell(0, 3, 'TROCO: R$ ' . number_format($troco, 2, ',', '.'), 0, 1, 'L');
        }
        
        $this->pdf->Ln(2);
        $this->adicionarLinhaSeparadora();
        $this->pdf->Ln(2);
    }

    /**
     * Adicionar dados do cliente
     */
    private function adicionarCliente($venda)
    {
        if (empty($venda['v1_cliente_id'])) {
            return;
        }
        
        // Buscar dados do cliente (tentar, mas não falhar em ambiente sem CI)
        try {
            $clienteModel = new \App\Models\ClienteModel();
            $cliente = $clienteModel->find($venda['v1_cliente_id']);
        } catch (\Throwable $e) {
            $cliente = null;
        }

        if ($cliente) {
            $this->pdf->SetFont('dejavusans', 'B', 8);
            $this->pdf->Cell(0, 3, 'CLIENTE', 0, 1, 'C');
            $this->pdf->Ln(1);
            
            $this->pdf->SetFont('dejavusans', '', 7);
            $clienteNome = function_exists('mb_strtoupper') ? mb_strtoupper($cliente->c2_nome, 'UTF-8') : strtoupper($cliente->c2_nome);
            $this->pdf->Cell(0, 3, 'NOME: ' . $clienteNome, 0, 1, 'L');
            
            if (!empty($cliente->c2_cpf)) {
                $this->pdf->Cell(0, 3, 'CPF: ' . $cliente->c2_cpf, 0, 1, 'L');
            }
            
            $this->pdf->Ln(2);
            $this->adicionarLinhaSeparadora();
            $this->pdf->Ln(2);
        }
    }

    /**
     * Adicionar informações de garantias
     */
    private function adicionarGarantias()
    {
    $this->pdf->SetFont('dejavusans', 'B', 8);
        $this->pdf->Cell(0, 3, 'GARANTIA', 0, 1, 'C');
        $this->pdf->Ln(1);
        
    $this->pdf->SetFont('dejavusans', '', 7);
        $this->pdf->Cell(0, 3, 'GARANTIA DE 90 DIAS CONFORME', 0, 1, 'L');
        $this->pdf->Cell(0, 3, 'CODIGO DE DEFESA DO CONSUMIDOR', 0, 1, 'L');
        $this->pdf->Cell(0, 3, 'GUARDE ESTE CUPOM', 0, 1, 'L');
        
        $this->pdf->Ln(2);
        $this->adicionarLinhaSeparadora();
        $this->pdf->Ln(2);
    }

    /**
     * Adicionar rodapé
     */
    private function adicionarRodape($venda)
    {
    $this->pdf->SetFont('dejavusans', '', 7);
        
        // Mensagem de agradecimento
        $this->centralizarTexto('OBRIGADO PELA PREFERENCIA!');
        $this->pdf->Ln(2);
        $this->centralizarTexto('VOLTE SEMPRE!');
        $this->pdf->Ln(3);
        
        // Informações técnicas
    $this->pdf->SetFont('dejavusans', '', 6);
    $this->centralizarTexto('Sistema: ' . $this->appName);
        $this->centralizarTexto('ID Venda: ' . $venda['v1_id']);
    }

    /**
     * Centralizar texto
     */
    private function centralizarTexto($texto)
    {
        $this->pdf->Cell(0, 3, $texto, 0, 1, 'C');
    }

    /**
     * Adicionar linha separadora
     */
    private function adicionarLinhaSeparadora()
    {
        $larguraLinha = $this->largura - (2 * $this->margemLateral);
        $linha = str_repeat('-', intval($larguraLinha / 1.5));
        $this->centralizarTexto($linha);
    }
}
