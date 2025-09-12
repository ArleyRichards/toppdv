<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ConfigHelper;
use App\Models\ClienteModel;
use App\Models\ProdutoModel;
use App\Models\LivroCaixaModel;
use App\Libraries\CupomService;
use CodeIgniter\HTTP\ResponseInterface;

class PdvController extends BaseController
{
    protected $clienteModel;
    protected $produtoModel;
    protected $livroCaixaModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->produtoModel = new ProdutoModel();
        $this->livroCaixaModel = new LivroCaixaModel();
    }

    /**
     * Exibir a interface do PDV
     */
    public function index()
    {
        try {
            // Verificar se existem dados nas tabelas
            $totalClientes = $this->clienteModel->where('c2_deleted_at IS NULL')->countAllResults();
            $totalProdutos = $this->produtoModel->where('p1_deleted_at IS NULL')->countAllResults();

            log_message('info', "PDV: Total de clientes ativos: {$totalClientes}");
            log_message('info', "PDV: Total de produtos ativos: {$totalProdutos}");



            // Carregar dados necessários para o PDV
            $data = [
                'appName' => ConfigHelper::appName(),
                'empresa' => ConfigHelper::empresa(),
                'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
                'title' => 'PDV - ' . ConfigHelper::appName(),
                'clientes' => $this->getClientes(),
                'produtos' => $this->getProdutos(),
                'caixa_status' => $this->getCaixaStatus(),
                'usuario' => $this->getUsuarioSession(),
                'debug_info' => [
                    'total_clientes' => $totalClientes,
                    'total_produtos' => $totalProdutos
                ]
            ];

            return view('pdv', $data);
        } catch (\Exception $e) {
            log_message('error', 'Erro no PDV index: ' . $e->getMessage());
            return redirect()->to('/home')->with('error', 'Erro ao carregar o PDV. Tente novamente.');
        }
    }

    /**
     * Buscar clientes para autocomplete
     */
    public function buscarClientes()
    {
        try {
            $termo = $this->request->getGet('termo');

            if (empty($termo) || strlen($termo) < 2) {
                return $this->response->setJSON([
                    'success' => true,
                    'clientes' => []
                ]);
            }

            $clientes = $this->clienteModel
                ->select('c2_id as id, c2_nome as nome, c2_cpf as cpf, c2_telefone as telefone, c2_celular as celular')
                ->groupStart()
                ->like('c2_nome', $termo)
                ->orLike('c2_cpf', $termo)
                ->orLike('c2_telefone', $termo)
                ->orLike('c2_celular', $termo)
                ->groupEnd()
                ->where('c2_deleted_at IS NULL')
                ->orderBy('c2_nome', 'ASC')
                ->limit(10)
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'clientes' => $clientes
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao buscar clientes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Buscar produtos para autocomplete
     */
    public function buscarProdutos()
    {
        try {
            $termo = $this->request->getGet('termo');

            if (empty($termo) || strlen($termo) < 2) {
                return $this->response->setJSON([
                    'success' => true,
                    'produtos' => []
                ]);
            }

            // Verificar se existe a coluna p1_codigo_barras
            $db = \Config\Database::connect();
            $hasCodigoBarras = $db->fieldExists('p1_codigo_barras', 'p1_produtos');

            $selectFields = 'p1_id as id, p1_nome_produto as nome, p1_codigo_produto as codigo, 
                           p1_preco_venda_produto as preco, p1_imagem_produto as imagem, 
                           p1_quantidade_produto as estoque';

            $query = $this->produtoModel
                ->select($selectFields)
                ->groupStart()
                ->like('p1_nome_produto', $termo)
                ->orLike('p1_codigo_produto', $termo);

            // Se existir código de barras, incluir na busca
            if ($hasCodigoBarras) {
                $query->orLike('p1_codigo_barras', $termo);
            }

            $produtos = $query->groupEnd()
                ->where('p1_deleted_at IS NULL')
                ->where('p1_quantidade_produto >', 0)
                ->orderBy('p1_nome_produto', 'ASC')
                ->limit(20)
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'produtos' => $produtos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao buscar produtos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Processar venda do PDV
     */
    public function processarVenda()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!$data) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos'
                ]);
            }

            // Validar dados obrigatórios
            $validation = \Config\Services::validation();
            $validation->setRules([
                'cliente_id' => 'permit_empty|numeric',
                'produtos' => 'required',
                'tipo_pagamento' => 'required|in_list[dinheiro,cartao_credito,cartao_debito,pix,transferencia,boleto,a_prazo]',
                'valor_total' => 'required|decimal|greater_than[0]',
                'valor_pago' => 'permit_empty|decimal',
                'desconto' => 'permit_empty|decimal',
                'observacoes' => 'permit_empty|string|max_length[500]',
                'imprimir_nome_cliente' => 'permit_empty',
                'imprimir_garantias' => 'permit_empty'
            ]);

            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Validação manual dos produtos
            if (empty($data['produtos']) || !is_array($data['produtos'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Lista de produtos é obrigatória'
                ]);
            }

            // Validar cada produto individualmente
            foreach ($data['produtos'] as $index => $produto) {
                if (empty($produto['produto_id']) || !is_numeric($produto['produto_id'])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "ID do produto é obrigatório no item " . ($index + 1)
                    ]);
                }

                if (empty($produto['quantidade']) || !is_numeric($produto['quantidade']) || $produto['quantidade'] <= 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Quantidade inválida no item " . ($index + 1)
                    ]);
                }

                if (empty($produto['preco_unitario']) || !is_numeric($produto['preco_unitario']) || $produto['preco_unitario'] <= 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Preço unitário inválido no item " . ($index + 1)
                    ]);
                }
            }

            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Obter nome do vendedor da sessão
                $vendedorNome = session('name_user') ?? 'Vendedor';

                // Garantir que cliente_id seja NULL se não fornecido (para vendas sem cliente)
                $clienteId = $data['cliente_id'] ?? null;                

                // Gerar um número temporário para a venda (será atualizado após inserção)
                $numeroVendaTemp = 'TEMP_' . time();

                // Criar a venda na tabela v1_vendas
                $vendaData = [
                    'v1_numero_venda' => $numeroVendaTemp,
                    'v1_cliente_id' => $clienteId,
                    'v1_vendedor_nome' => $vendedorNome,
                    'v1_vendedor_id' => session('user_id'),
                    'v1_tipo_de_pagamento' => $data['tipo_pagamento'],
                    'v1_desconto' => floatval($data['desconto'] ?? 0),
                    'v1_valor_total' => floatval($data['valor_total']),
                    'v1_codigo_transacao' => $data['codigo_transacao'] ?? null,
                    'v1_valor_a_ser_pago' => floatval($data['valor_pago'] ?? $data['valor_total']),
                    'v1_status' => $this->determinarStatusVenda($data),
                    'v1_created_at' => date('Y-m-d H:i:s'),
                    'v1_data_pagamento' => $this->determinarDataPagamento($data),
                    'v1_data_faturamento' => date('Y-m-d'),
                    'v1_observacoes' => $this->montarObservacoes($data),
                    'v1_updated_at' => date('Y-m-d H:i:s')
                ];

                // Log dos dados para debug
                log_message('debug', 'Dados da venda: ' . json_encode($vendaData));

                // Inserir venda diretamente na tabela
                $insertResult = $db->table('v1_vendas')->insert($vendaData);
                $vendaId = $db->insertID();

                // Log do resultado da inserção
                log_message('debug', 'Insert result: ' . ($insertResult ? 'true' : 'false'));
                log_message('debug', 'Venda ID: ' . $vendaId);
                log_message('debug', 'DB Error: ' . $db->error()['message']);

                if (!$vendaId) {
                    throw new \Exception('Erro ao criar venda - Insert ID: ' . $vendaId . ' - DB Error: ' . $db->error()['message']);
                }

                // Gerar número da venda
                $numeroVenda = 'VD' . str_pad($vendaId, 6, '0', STR_PAD_LEFT);
                $db->table('v1_vendas')->where('v1_id', $vendaId)->update(['v1_numero_venda' => $numeroVenda]);

                // Adicionar produtos à venda na tabela p2_produtos_venda
                foreach ($data['produtos'] as $produto) {
                    $subtotal = $produto['quantidade'] * $produto['preco_unitario'];
                    $descontoProduto = 0; // Pode ser implementado por produto no futuro
                    $valorComDesconto = $subtotal - $descontoProduto;

                    $produtoVendaData = [
                        'p2_venda_id' => $vendaId,
                        'p2_produto_id' => $produto['produto_id'],
                        'p2_quantidade' => $produto['quantidade'],
                        'p2_valor_unitario' => $produto['preco_unitario'],
                        'p2_subtotal' => $subtotal,
                        'p2_desconto' => $descontoProduto,
                        'p2_valor_com_desconto' => $valorComDesconto,
                        'p2_created_at' => date('Y-m-d H:i:s'),
                        'p2_updated_at' => date('Y-m-d H:i:s')
                    ];

                    $db->table('p2_produtos_venda')->insert($produtoVendaData);

                    // Atualizar estoque do produto
                    $this->atualizarEstoque($produto['produto_id'], $produto['quantidade']);
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Erro ao processar transação');
                }

                // Buscar a venda completa para retorno
                $vendaCompleta = $this->getVendaCompleta($vendaId);

                // Gerar cupom automaticamente
                $cupomInfo = null;
                try {
                    $configuracoesCupom = [
                        'imprimir_nome_cliente' => $data['imprimir_nome_cliente'] ?? false,
                        'imprimir_garantias' => $data['imprimir_garantias'] ?? false
                    ];

                    $cupomService = new CupomService();
                    $cupomInfo = $cupomService->salvarCupom($vendaCompleta, $vendaCompleta['produtos'], $configuracoesCupom);

                    log_message('info', 'Cupom gerado automaticamente: ' . $cupomInfo['arquivo']);
                } catch (\Exception $e) {
                    log_message('error', 'Erro ao gerar cupom automaticamente: ' . $e->getMessage());
                    // Não falhar a venda por erro no cupom
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Venda processada com sucesso!',
                    'venda_id' => $vendaId,
                    'numero_venda' => $numeroVenda,
                    'venda' => $vendaCompleta,
                    'cupom' => $cupomInfo
                ]);
            } catch (\Exception $e) {
                $db->transRollback();
                throw $e;
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao processar venda PDV: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao processar venda: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Abrir caixa
     */
    public function abrirCaixa()
    {
        try {
            $data = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            $validation->setRules([
                'valor_inicial' => 'required|decimal|greater_than_equal_to[0]',
                'observacoes' => 'permit_empty|string|max_length[500]'
            ]);

            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Verificar se já existe um caixa aberto
            if (session('caixa_aberto')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Já existe um caixa aberto'
                ]);
            }

            $usuarioId = session('user_id');
            $valorInicial = floatval($data['valor_inicial']);
            $observacoes = $data['observacoes'] ?? null;
            $dataOperacao = date('Y-m-d H:i:s');

            // Registrar abertura no livro de caixa
            $dadosLivroCaixa = [
                'l3_usuario_id' => $usuarioId,
                'l3_data_operacao' => $dataOperacao,
                'l3_tipo_operacao' => 'abertura',
                'l3_valor_inicial' => $valorInicial,
                'l3_valor_final' => $valorInicial, // No início, final = inicial
                'l3_valor_vendas' => 0,
                'l3_numero_vendas' => 0,
                'l3_valor_diferenca' => 0,
                'l3_status_caixa' => 'aberto',
                'l3_observacoes' => $observacoes
            ];

            $livroCaixaId = $this->livroCaixaModel->insert($dadosLivroCaixa);

            if (!$livroCaixaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao registrar abertura do caixa'
                ]);
            }

            // Salvar na sessão
            session()->set([
                'caixa_aberto' => true,
                'caixa_livro_id' => $livroCaixaId,
                'caixa_valor_inicial' => $valorInicial,
                'caixa_data_abertura' => $dataOperacao,
                'caixa_usuario_id' => $usuarioId,
                'caixa_observacoes_abertura' => $observacoes
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Caixa aberto com sucesso!',
                'data_abertura' => $dataOperacao,
                'valor_inicial' => $valorInicial,
                'livro_caixa_id' => $livroCaixaId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao abrir caixa: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao abrir caixa: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fechar caixa
     */
    public function fecharCaixa()
    {
        try {
            $data = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            $validation->setRules([
                'valor_final' => 'required|decimal|greater_than_equal_to[0]',
                'observacoes' => 'permit_empty|string|max_length[500]'
            ]);

            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Verificar se existe um caixa aberto
            if (!session('caixa_aberto')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Não há caixa aberto para fechar'
                ]);
            }

            $usuarioId = session('caixa_usuario_id');
            $valorInicial = session('caixa_valor_inicial') ?? 0;
            $valorFinal = floatval($data['valor_final']);
            $observacoes = $data['observacoes'] ?? null;
            $dataOperacao = date('Y-m-d H:i:s');
            $dataAbertura = session('caixa_data_abertura');

            // Calcular vendas do período (entre abertura e fechamento)
            $vendas = $this->calcularVendasPeriodo($dataAbertura, $dataOperacao, $usuarioId);
            $valorVendas = $vendas['total_vendas'];
            $numeroVendas = $vendas['numero_vendas'];

            // Calcular diferença (valor final - valor inicial - valor vendas)
            $valorEsperado = $valorInicial + $valorVendas;
            $diferenca = $valorFinal - $valorEsperado;

            // Atualizar o registro de abertura com dados do fechamento
            $livroCaixaId = session('caixa_livro_id');
            if ($livroCaixaId) {
                $dadosAtualizacao = [
                    'l3_valor_final' => $valorFinal,
                    'l3_valor_vendas' => $valorVendas,
                    'l3_numero_vendas' => $numeroVendas,
                    'l3_valor_diferenca' => $diferenca,
                    'l3_status_caixa' => 'fechado',
                    'l3_data_fechamento' => $dataOperacao,
                    'l3_observacoes_fechamento' => $observacoes,
                    'l3_updated_at' => $dataOperacao
                ];

                $this->livroCaixaModel->update($livroCaixaId, $dadosAtualizacao);
            }

            // Criar registro de fechamento separado para histórico
            $dadosFechamento = [
                'l3_usuario_id' => $usuarioId,
                'l3_data_operacao' => $dataOperacao,
                'l3_tipo_operacao' => 'fechamento',
                'l3_valor_inicial' => $valorInicial,
                'l3_valor_final' => $valorFinal,
                'l3_valor_vendas' => $valorVendas,
                'l3_numero_vendas' => $numeroVendas,
                'l3_valor_diferenca' => $diferenca,
                'l3_status_caixa' => 'fechado',
                'l3_observacoes' => $observacoes,
                'l3_data_fechamento' => $dataOperacao
            ];

            $this->livroCaixaModel->insert($dadosFechamento);

            // Limpar sessão do caixa
            session()->remove([
                'caixa_aberto',
                'caixa_livro_id',
                'caixa_valor_inicial',
                'caixa_data_abertura',
                'caixa_usuario_id',
                'caixa_observacoes_abertura'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Caixa fechado com sucesso!',
                'resumo' => [
                    'valor_inicial' => $valorInicial,
                    'valor_final' => $valorFinal,
                    'valor_vendas' => $valorVendas,
                    'numero_vendas' => $numeroVendas,
                    'valor_esperado' => $valorEsperado,
                    'diferenca' => $diferenca,
                    'data_fechamento' => $dataOperacao,
                    'periodo' => [
                        'abertura' => $dataAbertura,
                        'fechamento' => $dataOperacao
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao fechar caixa: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao fechar caixa: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Realizar sangria (retirada de dinheiro do caixa)
     */
    public function sangriaCaixa()
    {
        try {
            $data = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            $validation->setRules([
                'valor' => 'required|decimal|greater_than[0]',
                'observacoes' => 'permit_empty|string|max_length[500]'
            ]);

            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Verificar se existe um caixa aberto
            if (!session('caixa_aberto')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Não há caixa aberto para realizar sangria'
                ]);
            }

            $usuarioId = session('caixa_usuario_id');
            $valor = floatval($data['valor']);
            $observacoes = $data['observacoes'] ?? null;
            $dataOperacao = date('Y-m-d H:i:s');

            // Registrar sangria no livro de caixa
            $dadosSangria = [
                'l3_usuario_id' => $usuarioId,
                'l3_data_operacao' => $dataOperacao,
                'l3_tipo_operacao' => 'sangria',
                'l3_valor_inicial' => $valor,
                'l3_valor_final' => 0,
                'l3_valor_vendas' => 0,
                'l3_numero_vendas' => 0,
                'l3_valor_diferenca' => -$valor, // Negativo porque é uma retirada
                'l3_status_caixa' => 'aberto',
                'l3_observacoes' => $observacoes
            ];

            $sangriaId = $this->livroCaixaModel->insert($dadosSangria);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sangria realizada com sucesso!',
                'sangria_id' => $sangriaId,
                'valor' => $valor,
                'data_operacao' => $dataOperacao
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao realizar sangria: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao realizar sangria: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Realizar suprimento (depósito de dinheiro no caixa)
     */
    public function suprimentoCaixa()
    {
        try {
            $data = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            $validation->setRules([
                'valor' => 'required|decimal|greater_than[0]',
                'observacoes' => 'permit_empty|string|max_length[500]'
            ]);

            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Verificar se existe um caixa aberto
            if (!session('caixa_aberto')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Não há caixa aberto para realizar suprimento'
                ]);
            }

            $usuarioId = session('caixa_usuario_id');
            $valor = floatval($data['valor']);
            $observacoes = $data['observacoes'] ?? null;
            $dataOperacao = date('Y-m-d H:i:s');

            // Registrar suprimento no livro de caixa
            $dadosSuprimento = [
                'l3_usuario_id' => $usuarioId,
                'l3_data_operacao' => $dataOperacao,
                'l3_tipo_operacao' => 'suprimento',
                'l3_valor_inicial' => 0,
                'l3_valor_final' => $valor,
                'l3_valor_vendas' => 0,
                'l3_numero_vendas' => 0,
                'l3_valor_diferenca' => $valor, // Positivo porque é um depósito
                'l3_status_caixa' => 'aberto',
                'l3_observacoes' => $observacoes
            ];

            $suprimentoId = $this->livroCaixaModel->insert($dadosSuprimento);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Suprimento realizado com sucesso!',
                'suprimento_id' => $suprimentoId,
                'valor' => $valor,
                'data_operacao' => $dataOperacao
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao realizar suprimento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao realizar suprimento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Método de teste para verificar dados das tabelas
     */
    public function testarDados()
    {
        try {
            // Testar produtos
            $produtos = $this->produtoModel->limit(5)->findAll();
            $totalProdutos = $this->produtoModel->countAllResults();

            // Testar clientes
            $clientes = $this->clienteModel->limit(5)->findAll();
            $totalClientes = $this->clienteModel->countAllResults();

            return $this->response->setJSON([
                'success' => true,
                'produtos' => [
                    'total' => $totalProdutos,
                    'sample' => $produtos
                ],
                'clientes' => [
                    'total' => $totalClientes,
                    'sample' => $clientes
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cancelar venda em andamento
     */
    public function cancelarVenda()
    {
        try {
            // Limpar dados da venda da sessão se necessário
            session()->remove(['venda_atual']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Venda cancelada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao cancelar venda: ' . $e->getMessage()
            ]);
        }
    }

    public function gerarCupom($vendaId)
    {
        try {
            $db = \Config\Database::connect();

            // Buscar dados da venda
            $venda = $db->table('v1_vendas')->where('v1_id', $vendaId)->get()->getRowArray();

            if (!$venda) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Venda não encontrada'
                ]);
            }

            // Buscar produtos da venda
            $produtos = $db->table('p2_produtos_venda pv')
                ->select('pv.*, p.p1_nome_produto as nome_produto, p.p1_codigo_produto as codigo_produto')
                ->join('p1_produtos p', 'p.p1_id = pv.p2_produto_id', 'left')
                ->where('pv.p2_venda_id', $vendaId)
                ->where('pv.p2_deleted_at IS NULL')
                ->get()
                ->getResultArray();

            // Extrair configurações das observações
            $configuracoes = $this->extrairConfiguracoesCupom($venda['v1_observacoes']);

            // Gerar cupom
            $cupomService = new CupomService();
            $arquivoCupom = $cupomService->salvarCupom($venda, $produtos, $configuracoes);

            // Verificar se o arquivo foi gerado com sucesso
            if ($arquivoCupom && file_exists($arquivoCupom)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Cupom gerado com sucesso!',
                    'cupom' => $arquivoCupom,
                    'download_url' => base_url('vendas/downloadCupom/' . $vendaId) // URL para download
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao salvar o arquivo do cupom'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao gerar cupom: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao gerar cupom: ' . $e->getMessage()
            ]);
        }
    }

    public function downloadCupom($vendaId)
    {
        try {
            $db = \Config\Database::connect();

            // Buscar dados da venda
            $venda = $db->table('v1_vendas')->where('v1_id', $vendaId)->get()->getRowArray();

            if (!$venda) {
                return redirect()->back()->with('error', 'Venda não encontrada');
            }

            // Buscar produtos da venda
            $produtos = $db->table('p2_produtos_venda pv')
                ->select('pv.*, p.p1_nome_produto as nome_produto, p.p1_codigo_produto as codigo_produto')
                ->join('p1_produtos p', 'p.p1_id = pv.p2_produto_id', 'left')
                ->where('pv.p2_venda_id', $vendaId)
                ->where('pv.p2_deleted_at IS NULL')
                ->get()
                ->getResultArray();

            // Extrair configurações das observações
            $configuracoes = $this->extrairConfiguracoesCupom($venda['v1_observacoes']);

            // Gerar cupom
            $cupomService = new CupomService();
            $pdf = $cupomService->gerarCupom($venda, $produtos, $configuracoes);

            // Configurar headers para download
            $nomeArquivo = 'cupom_venda_' . $venda['v1_numero_venda'] . '.pdf';

            // Configurar headers
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $nomeArquivo . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            // Output do PDF
            $pdf->Output($nomeArquivo, 'I');
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Erro ao fazer download do cupom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar cupom para download');
        }
    }

    // ===============================
    // MÉTODOS PRIVADOS DE APOIO
    // ===============================

    /**
     * Obter lista de clientes
     */
    private function getClientes()
    {
        try {
            return $this->clienteModel
                ->select('c2_id as id, c2_nome as nome, c2_cpf as cpf, c2_telefone as telefone, c2_celular as celular')
                ->where('c2_deleted_at IS NULL')
                ->orderBy('c2_nome', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Erro ao carregar clientes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obter lista de produtos
     */
    private function getProdutos()
    {
        try {
            return $this->produtoModel
                ->select('p1_id as id, p1_nome_produto as nome, p1_codigo_produto as codigo, 
                         p1_preco_venda_produto as preco, p1_imagem_produto as imagem, 
                         p1_quantidade_produto as estoque')
                ->where('p1_deleted_at IS NULL')
                ->where('p1_quantidade_produto >', 0)
                ->orderBy('p1_nome_produto', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Erro ao carregar produtos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obter status do caixa
     */
    private function getCaixaStatus()
    {
        $caixaAberto = session('caixa_aberto') ?? false;
        
        // Se não há caixa aberto na sessão, verificar no banco de dados
        if (!$caixaAberto) {
            $usuarioId = session('user_id');
            $caixaAberto = $this->livroCaixaModel->verificarCaixaAberto($usuarioId);
            
            // Se encontrou caixa aberto no banco, restaurar na sessão
            if ($caixaAberto) {
                session()->set([
                    'caixa_aberto' => true,
                    'caixa_livro_id' => $caixaAberto['l3_id'],
                    'caixa_valor_inicial' => $caixaAberto['l3_valor_inicial'],
                    'caixa_data_abertura' => $caixaAberto['l3_data_operacao'],
                    'caixa_usuario_id' => $caixaAberto['l3_usuario_id']
                ]);
            }
        }

        return [
            'aberto' => (bool)$caixaAberto,
            'valor_inicial' => session('caixa_valor_inicial') ?? 0,
            'data_abertura' => session('caixa_data_abertura'),
            'usuario_id' => session('caixa_usuario_id'),
            'livro_caixa_id' => session('caixa_livro_id')
        ];
    }

    /**
     * Obter dados do usuário da sessão
     */
    private function getUsuarioSession()
    {
        return [
            'id' => session('user_id'),
            'nome' => session('name_user'),
            'usuario' => session('access_user'),
            'perfil' => session('profile_user')
        ];
    }

    /**
     * Determinar status da venda baseado no tipo de pagamento
     */
    private function determinarStatusVenda($data)
    {
        switch ($data['tipo_pagamento']) {
            case 'a_prazo':
                return 'Em Aberto';
            case 'dinheiro':
            case 'cartao_credito':
            case 'cartao_debito':
            case 'pix':
            case 'transferencia':
            case 'boleto':
                return 'Faturado';
            default:
                return 'Em Aberto';
        }
    }

    /**
     * Determinar data de pagamento baseado no tipo
     */
    private function determinarDataPagamento($data)
    {
        // Para pagamentos à vista, data é hoje
        if (in_array($data['tipo_pagamento'], ['dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'transferencia'])) {
            return date('Y-m-d');
        }

        // Para outros tipos, pode ser null (a prazo) ou especificado
        return $data['data_pagamento'] ?? null;
    }

    /**
     * Montar observações incluindo configurações do cupom
     */
    private function montarObservacoes($data)
    {
        $observacoes = [];

        // Observações do usuário
        if (!empty($data['observacoes'])) {
            $observacoes[] = $data['observacoes'];
        }

        // Configurações do cupom
        $configCupom = [];
        if (isset($data['imprimir_nome_cliente'])) {
            $configCupom[] = 'Nome cliente: ' . ($data['imprimir_nome_cliente'] ? 'SIM' : 'NÃO');
        }
        if (isset($data['imprimir_garantias'])) {
            $configCupom[] = 'Garantias: ' . ($data['imprimir_garantias'] ? 'SIM' : 'NÃO');
        }

        if (!empty($configCupom)) {
            $observacoes[] = 'Cupom: ' . implode(', ', $configCupom);
        }

        return implode(' | ', $observacoes);
    }

    /**
     * Buscar venda completa com produtos
     */
    private function getVendaCompleta($vendaId)
    {
        $db = \Config\Database::connect();

        // Buscar dados da venda
        $venda = $db->table('v1_vendas')->where('v1_id', $vendaId)->get()->getRowArray();

        if (!$venda) {
            return null;
        }

        // Buscar produtos da venda
        $produtos = $db->table('p2_produtos_venda pv')
            ->select('pv.*, p.p1_nome_produto as nome_produto, p.p1_codigo_produto as codigo_produto')
            ->join('p1_produtos p', 'p.p1_id = pv.p2_produto_id', 'left')
            ->where('pv.p2_venda_id', $vendaId)
            ->where('pv.p2_deleted_at IS NULL')
            ->get()
            ->getResultArray();

        $venda['produtos'] = $produtos;

        return $venda;
    }

    /**
     * Atualizar estoque do produto
     */
    private function atualizarEstoque($produtoId, $quantidade)
    {
        try {
            $produto = $this->produtoModel->find($produtoId);
            if ($produto) {
                $novoEstoque = $produto->p1_quantidade_produto - $quantidade;
                $this->produtoModel->update($produtoId, [
                    'p1_quantidade_produto' => max(0, $novoEstoque),
                    'p1_updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao atualizar estoque: ' . $e->getMessage());
        }
    }

    /**
     * Extrair configurações do cupom das observações
     */
    private function extrairConfiguracoesCupom($observacoes)
    {
        $configuracoes = [
            'imprimir_nome_cliente' => false,
            'imprimir_garantias' => false
        ];

        if (empty($observacoes)) {
            return $configuracoes;
        }

        // Procurar padrões nas observações
        if (strpos($observacoes, 'Nome cliente: SIM') !== false) {
            $configuracoes['imprimir_nome_cliente'] = true;
        }

        if (strpos($observacoes, 'Garantias: SIM') !== false) {
            $configuracoes['imprimir_garantias'] = true;
        }

        return $configuracoes;
    }

    /**
     * Calcular vendas realizadas no período do caixa
     */
    private function calcularVendasPeriodo($dataInicio, $dataFim, $usuarioId = null)
    {
        try {
            $db = \Config\Database::connect();
            
            $builder = $db->table('v1_vendas')
                ->select('
                    COUNT(*) as numero_vendas,
                    COALESCE(SUM(v1_valor_total), 0) as total_vendas
                ')
                ->where('v1_created_at >=', $dataInicio)
                ->where('v1_created_at <=', $dataFim)
                ->where('v1_deleted_at IS NULL')
                ->where('v1_status !=', 'Cancelado');

            // Filtrar por usuário se especificado
            if ($usuarioId) {
                $builder->where('v1_vendedor_id', $usuarioId);
            }

            $resultado = $builder->get()->getRowArray();

            return [
                'numero_vendas' => (int)($resultado['numero_vendas'] ?? 0),
                'total_vendas' => (float)($resultado['total_vendas'] ?? 0.0)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Erro ao calcular vendas do período: ' . $e->getMessage());
            return [
                'numero_vendas' => 0,
                'total_vendas' => 0.0
            ];
        }
    }
}
