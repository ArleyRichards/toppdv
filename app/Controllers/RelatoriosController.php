<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\ClienteModel;
use App\Libraries\PdfService;

use Config\Services;
use CodeIgniter\HTTP\ResponseInterface;

class RelatoriosController extends BaseController
{
    public function index()
    {
        $clienteModel = new ClienteModel();

        $clientes = $clienteModel->select('c2_id as id, c2_nome as nome')->where('c2_deleted_at IS NULL')->orderBy('c2_nome', 'ASC')->findAll();

        // Carregar lista de vendedores (usuários do tipo 'venda' ou administradores)
        $db = \Config\Database::connect();
        $vendedores = $db->table('u1_usuarios')
            ->select('u1_id as id, u1_nome as nome')
            ->where('u1_deleted_at IS NULL')
            ->whereIn('u1_tipo_permissao', ['venda', 'administrador'])
            ->orderBy('u1_nome', 'ASC')
            ->get()->getResult();

        // Status possíveis (mesma lista do banco)
        $statuses = ['Em Aberto', 'Faturado', 'Atrasado', 'Cancelado'];

        $data = [
            'title' => 'Relatórios',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
            'clientes' => $clientes,
            'vendedores' => $vendedores,
            'statuses' => $statuses,
        ];

        return view('relatorios', $data);
    }

    /**
     * Gera PDF de vendas conforme filtros GET: data_inicial, data_final, clientes[]
     */
    public function vendasPdf()
    {
        $db = \Config\Database::connect();

        $dataInicial = $this->request->getGet('data_inicial') ?? date('Y-m-01');
        $dataFinal = $this->request->getGet('data_final') ?? date('Y-m-d');
        $clientesParam = $this->request->getGet('clientes');
        $vendedoresParam = $this->request->getGet('vendedores');
        $statusParam = $this->request->getGet('status');

        // If the clientes parameter is not present at all, or is an explicit empty selection,
        // return a 400 Bad Request with a helpful message so front-end can show feedback.
        if ($clientesParam === null) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['error' => 'Parâmetro clientes ausente. Selecione ao menos um cliente.']);
        }

        // Normalize clientes; if explicitly empty array, return 400
        if (!is_array($clientesParam)) {
            $clientes = $clientesParam ? explode(',', $clientesParam) : [];
        } else {
            $clientes = $clientesParam;
        }

        if (empty($clientes)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['error' => 'Nenhum cliente selecionado. Selecione ao menos um cliente para gerar o relatório.']);
        }

        // At this point $clientes is a non-empty array; continue to normalize vendedores and query
        // Normalizar vendedores (opcional)
        if (!is_array($vendedoresParam)) {
            $vendedores = $vendedoresParam ? explode(',', $vendedoresParam) : [];
        } else {
            $vendedores = $vendedoresParam ?? [];
        }

        // Query básica para buscar vendas entre datas (usar v1_created_at)
        $builder = $db->table('v1_vendas v')
            ->select("v.v1_id as id, v.v1_numero_venda as v1_numero_venda, v.v1_status as v1_status, v.v1_valor_total as valor_total, v.v1_cliente_id as cliente_id, v.v1_vendedor_id as vendedor_id, v.v1_created_at as data")
            ->where('v.v1_created_at >=', $dataInicial . ' 00:00:00')
            ->where('v.v1_created_at <=', $dataFinal . ' 23:59:59')
            ->whereIn('v.v1_cliente_id', $clientes)
            ->orderBy('v.v1_created_at', 'ASC');

        // aplicar filtro de vendedores se fornecido
        if (!empty($vendedores)) {
            $builder->whereIn('v.v1_vendedor_id', $vendedores);
        }

        // aplicar filtro de status se fornecido
        if (!empty($statusParam)) {
            $builder->where('v.v1_status', $statusParam);
        }

        $vendas = $builder->get()->getResultArray();

        // Fetch client names
        $clientMap = [];
        if (!empty($vendas)) {
            $ids = array_unique(array_column($vendas, 'cliente_id'));
            if (!empty($ids)) {
                $rows = $db->table('c2_clientes')->select('c2_id, c2_nome')->whereIn('c2_id', $ids)->get()->getResultArray();
                foreach ($rows as $r) $clientMap[$r['c2_id']] = $r['c2_nome'];
            }
        }

        // Fetch vendor names
        $vendedorMap = [];
        if (!empty($vendas)) {
            $vendedorIds = array_unique(array_filter(array_column($vendas, 'vendedor_id')));
            if (!empty($vendedorIds)) {
                $vendedorRows = $db->table('u1_usuarios')->select('u1_id, u1_nome')->whereIn('u1_id', $vendedorIds)->get()->getResultArray();
                foreach ($vendedorRows as $v) $vendedorMap[$v['u1_id']] = $v['u1_nome'];
            }
        }

        // Normalize data for view (keep datetime in 'data' so template can format)
        $forView = [];
        foreach ($vendas as $v) {
            $forView[] = [
                'id' => $v['id'],
                'v1_numero_venda' => $v['v1_numero_venda'] ?? ($v['id'] ?? '-'),
                'v1_status' => $v['v1_status'] ?? '-',
                'valor_total' => $v['valor_total'],
                'cliente_nome' => $clientMap[$v['cliente_id']] ?? '-',
                'vendedor_nome' => $vendedorMap[$v['vendedor_id']] ?? '-',
                'data' => $v['data']
            ];
        }

        // Resolve logo here and pass to view so view doesn't need to read filesystem
        $logo = ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png';
        $logoResolved = null;
        if ($logo) {
            if (filter_var($logo, FILTER_VALIDATE_URL)) {
                $logoResolved = $logo;
            } else {
                // Try common paths relative to project to find the file
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
                            $logoResolved = 'data:' . $mime . ';base64,' . base64_encode($data);
                            break;
                        }
                    }
                }

                if (!$logoResolved) {
                    $logoResolved = base_url($logo);
                }
            }
        }

        $html = view('relatorios_vendas_pdf', [
            'vendas' => $forView,
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal,
            'logo' => $logoResolved,
        ]);

        // Gerar PDF
        $pdf = new PdfService();
        $dompdf = $pdf->renderHtml($html, 'A4', 'portrait');
        // Stream inline
        $pdf->stream($dompdf, 'relatorio_vendas_' . date('Ymd') . '.pdf', true);
    }

    // Placeholder: Relatório de Ordens de Serviço (PDF)
    public function ordensPdf()
    {
        $db = \Config\Database::connect();

        $dataInicial = $this->request->getGet('data_inicial') ?? date('Y-m-01');
        $dataFinal = $this->request->getGet('data_final') ?? date('Y-m-d');
        $statusParam = $this->request->getGet('status');
        $clientesParam = $this->request->getGet('clientes');
        $tecnicosParam = $this->request->getGet('tecnicos');

        // Normalize clientes
        if (!is_array($clientesParam)) {
            $clientes = $clientesParam ? explode(',', $clientesParam) : [];
        } else {
            $clientes = $clientesParam;
        }

        // Normalize tecnicos
        if (!is_array($tecnicosParam)) {
            $tecnicos = $tecnicosParam ? explode(',', $tecnicosParam) : [];
        } else {
            $tecnicos = $tecnicosParam;
        }

        $builder = $db->table('o1_ordens o')
            ->select("o.o1_id as id, o.o1_numero_ordem as numero_ordem, o.o1_status as status, o.o1_valor_final as valor_final, o.o1_valor_total as valor_total, o.o1_cliente_id as cliente_id, o.o1_tecnico_id as tecnico_id, o.o1_data_entrada as data")
            ->where('o.o1_data_entrada >=', $dataInicial . ' 00:00:00')
            ->where('o.o1_data_entrada <=', $dataFinal . ' 23:59:59')
            ->orderBy('o.o1_data_entrada', 'ASC');

        if (!empty($statusParam)) {
            $builder->where('o.o1_status', $statusParam);
        }

        if (!empty($clientes)) {
            $builder->whereIn('o.o1_cliente_id', $clientes);
        }

        if (!empty($tecnicos)) {
            $builder->whereIn('o.o1_tecnico_id', $tecnicos);
        }

        $ordens = $builder->get()->getResultArray();

        // Map client names
        $clientMap = [];
        if (!empty($ordens)) {
            $ids = array_unique(array_column($ordens, 'cliente_id'));
            if (!empty($ids)) {
                $rows = $db->table('c2_clientes')->select('c2_id, c2_nome')->whereIn('c2_id', $ids)->get()->getResultArray();
                foreach ($rows as $r) $clientMap[$r['c2_id']] = $r['c2_nome'];
            }
        }

        // Map technician names (optional)
        $tecnicoMap = [];
        if (!empty($ordens)) {
            $tids = array_unique(array_filter(array_column($ordens, 'tecnico_id')));
            if (!empty($tids)) {
                $trows = $db->table('u1_usuarios')->select('u1_id, u1_nome')->whereIn('u1_id', $tids)->get()->getResultArray();
                foreach ($trows as $t) $tecnicoMap[$t['u1_id']] = $t['u1_nome'];
            }
        }

        // Normalize for view
        $forView = [];
        foreach ($ordens as $o) {
            $valor = ($o['valor_final'] !== null && $o['valor_final'] !== '') ? $o['valor_final'] : ($o['valor_total'] ?? 0);
            $forView[] = [
                'id' => $o['id'],
                'numero_ordem' => $o['numero_ordem'] ?? ($o['id'] ?? '-'),
                'status' => $o['status'] ?? '-',
                'valor_total' => $valor,
                'cliente_nome' => $clientMap[$o['cliente_id']] ?? '-',
                'tecnico_nome' => $tecnicoMap[$o['tecnico_id']] ?? '-',
                'data' => $o['data']
            ];
        }

        $html = view('relatorios_ordens_pdf', [
            'ordens' => $forView,
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal
        ]);

        $pdf = new PdfService();
        $dompdf = $pdf->renderHtml($html, 'A4', 'portrait');
        $pdf->stream($dompdf, 'relatorio_ordens_' . date('Ymd') . '.pdf', true);
    }

    // Placeholder: Relatório de Comissões por Vendedor (PDF)
    public function comissoesPdf()
    {
        $db = \Config\Database::connect();

        $dataInicial = $this->request->getGet('data_inicial') ?? date('Y-m-01');
        $dataFinal = $this->request->getGet('data_final') ?? date('Y-m-d');
        $vendedoresParam = $this->request->getGet('vendedores');

        // Normalize vendedores
        if (!is_array($vendedoresParam)) {
            $vendedores = $vendedoresParam ? explode(',', $vendedoresParam) : [];
        } else {
            $vendedores = $vendedoresParam;
        }

        // Query: get sales with products, categories and commissions
        $builder = $db->table('v1_vendas v')
            ->select("v.v1_id as venda_id, v.v1_vendedor_id as vendedor_id, v.v1_vendedor_nome as vendedor_nome, v.v1_created_at as data_venda,
                      pv.p2_quantidade as quantidade, pv.p2_valor_unitario as valor_unitario, pv.p2_subtotal as subtotal,
                      p.p1_nome_produto as produto_nome, p.p1_preco_compra_produto as preco_compra, p.p1_preco_venda_produto as preco_venda,
                      c.c1_categoria as categoria_nome, c.c1_comissao as categoria_comissao")
            ->join('p2_produtos_venda pv', 'v.v1_id = pv.p2_venda_id', 'inner')
            ->join('p1_produtos p', 'pv.p2_produto_id = p.p1_id', 'inner')
            ->join('c1_categorias c', 'p.p1_categoria_id = c.c1_id', 'inner')
            ->where('v.v1_created_at >=', $dataInicial . ' 00:00:00')
            ->where('v.v1_created_at <=', $dataFinal . ' 23:59:59')
            ->where('pv.p2_deleted_at IS NULL')
            ->where('p.p1_deleted_at IS NULL')
            ->where('c.c1_deleted_at IS NULL')
            ->orderBy('v.v1_vendedor_nome', 'ASC')
            ->orderBy('v.v1_created_at', 'ASC');

        if (!empty($vendedores)) {
            $builder->whereIn('v.v1_vendedor_id', $vendedores);
        }

        $resultados = $builder->get()->getResultArray();

        // Calculate commissions
        $comissoesPorVendedor = [];
        $totalGeralComissao = 0;

        foreach ($resultados as $r) {
            $vendedorId = $r['vendedor_id'];
            $vendedorNome = $r['vendedor_nome'];
            $quantidade = $r['quantidade'];
            $precoVenda = $r['preco_venda'] ?? $r['valor_unitario']; // fallback to valor_unitario
            $precoCompra = $r['preco_compra'] ?? 0;
            $categoriaComissao = $r['categoria_comissao'] ?? 0;
            
            // Calculate profit per unit and total profit
            $lucroUnitario = $precoVenda - $precoCompra;
            $lucroTotal = $lucroUnitario * $quantidade;
            
            // Calculate commission: percentage of profit
            $comissaoValor = $lucroTotal * ($categoriaComissao / 100);
            
            if (!isset($comissoesPorVendedor[$vendedorId])) {
                $comissoesPorVendedor[$vendedorId] = [
                    'vendedor_nome' => $vendedorNome,
                    'total_vendas' => 0,
                    'total_lucro' => 0,
                    'total_comissao' => 0,
                    'detalhes' => []
                ];
            }
            
            $comissoesPorVendedor[$vendedorId]['total_vendas'] += $r['subtotal'];
            $comissoesPorVendedor[$vendedorId]['total_lucro'] += $lucroTotal;
            $comissoesPorVendedor[$vendedorId]['total_comissao'] += $comissaoValor;
            $totalGeralComissao += $comissaoValor;
            
            $comissoesPorVendedor[$vendedorId]['detalhes'][] = [
                'venda_id' => $r['venda_id'],
                'data_venda' => $r['data_venda'],
                'produto_nome' => $r['produto_nome'],
                'categoria_nome' => $r['categoria_nome'],
                'quantidade' => $quantidade,
                'preco_venda' => $precoVenda,
                'preco_compra' => $precoCompra,
                'lucro_unitario' => $lucroUnitario,
                'lucro_total' => $lucroTotal,
                'categoria_comissao' => $categoriaComissao,
                'comissao_valor' => $comissaoValor
            ];
        }

        // Resolve logo and pass to view
        $logo = ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png';
        $logoResolved = null;
        if ($logo) {
            if (filter_var($logo, FILTER_VALIDATE_URL)) {
                $logoResolved = $logo;
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
                            $logoResolved = 'data:' . $mime . ';base64,' . base64_encode($data);
                            break;
                        }
                    }
                }

                if (!$logoResolved) {
                    $logoResolved = base_url($logo);
                }
            }
        }

        $html = view('relatorios_comissoes_pdf', [
            'comissoes' => $comissoesPorVendedor,
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal,
            'total_geral_comissao' => $totalGeralComissao,
            'logo' => $logoResolved,
        ]);

        $pdf = new PdfService();
        $dompdf = $pdf->renderHtml($html, 'A4', 'portrait');
        $pdf->stream($dompdf, 'relatorio_comissoes_' . date('Ymd') . '.pdf', true);
    }

    // Placeholder: Livro de Caixa (PDF)
    public function livroCaixaPdf()
    {
        $db = \Config\Database::connect();

        $dataInicial = $this->request->getGet('data_inicial') ?? date('Y-m-01');
        $dataFinal = $this->request->getGet('data_final') ?? date('Y-m-d');
        $usuarioParam = $this->request->getGet('usuario');

        // Query livro de caixa operations
        $builder = $db->table('l3_livro_caixa l')
            ->select("l.l3_id as id, l.l3_data_operacao as data_operacao, l.l3_tipo_operacao as tipo_operacao, 
                      l.l3_valor_inicial as valor_inicial, l.l3_valor_final as valor_final, l.l3_valor_vendas as valor_vendas,
                      l.l3_valor_diferenca as valor_diferenca, l.l3_status_caixa as status_caixa, l.l3_observacoes as observacoes,
                      l.l3_numero_vendas as numero_vendas, l.l3_usuario_id as usuario_id, u.u1_nome as usuario_nome")
            ->join('u1_usuarios u', 'l.l3_usuario_id = u.u1_id', 'left')
            ->where('l.l3_data_operacao >=', $dataInicial . ' 00:00:00')
            ->where('l.l3_data_operacao <=', $dataFinal . ' 23:59:59')
            ->where('l.l3_deleted_at IS NULL')
            ->orderBy('l.l3_data_operacao', 'ASC');

        if (!empty($usuarioParam)) {
            $builder->where('l.l3_usuario_id', $usuarioParam);
        }

        $operacoes = $builder->get()->getResultArray();

        // Calculate totals
        $totalInicial = 0;
        $totalFinal = 0;
        $totalVendas = 0;
        $totalDiferenca = 0;
        $totalOperacoes = count($operacoes);

        foreach ($operacoes as $op) {
            $totalInicial += $op['valor_inicial'] ?? 0;
            $totalFinal += $op['valor_final'] ?? 0;
            $totalVendas += $op['valor_vendas'] ?? 0;
            $totalDiferenca += $op['valor_diferenca'] ?? 0;
        }

        $saldoLiquido = $totalFinal - $totalInicial;

        // Resolve logo and pass to view
        $logo = ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png';
        $logoResolved = null;
        if ($logo) {
            if (filter_var($logo, FILTER_VALIDATE_URL)) {
                $logoResolved = $logo;
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
                            $logoResolved = 'data:' . $mime . ';base64,' . base64_encode($data);
                            break;
                        }
                    }
                }

                if (!$logoResolved) {
                    $logoResolved = base_url($logo);
                }
            }
        }

        $html = view('relatorios_livrocaixa_pdf', [
            'operacoes' => $operacoes,
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal,
            'totais' => [
                'total_inicial' => $totalInicial,
                'total_final' => $totalFinal,
                'total_vendas' => $totalVendas,
                'total_diferenca' => $totalDiferenca,
                'total_operacoes' => $totalOperacoes,
                'saldo_liquido' => $saldoLiquido
            ],
            'logo' => $logoResolved,
        ]);

        $pdf = new PdfService();
        $dompdf = $pdf->renderHtml($html, 'A4', 'portrait');
        $pdf->stream($dompdf, 'relatorio_livrocaixa_' . date('Ymd') . '.pdf', true);
    }
}
