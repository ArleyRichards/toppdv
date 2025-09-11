<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de vendas.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */
class VendaController extends BaseController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\VendaModel';
    protected $format    = 'json';

    public function index()
    {
        // Carregar dados necessários para o formulário
        $clienteModel = new \App\Models\ClienteModel();
        $usuarioModel = new \App\Models\UsuarioModel();
    $produtoModel = new \App\Models\ProdutoModel();

        $clientes = $clienteModel->where('c2_deleted_at', null)->orderBy('c2_nome', 'ASC')->findAll();
        $vendedores = $usuarioModel->buscarVendedores();
    $produtos = $produtoModel->buscarParaVenda();
        // Log para debug: confirmar quantidade de produtos carregados para a view
        try {
            $produtosCount = is_countable($produtos) ? count($produtos) : 0;
            log_message('debug', 'VendaController::index - produtos carregados: ' . $produtosCount);
        } catch (\Throwable $t) {
            log_message('warning', 'VendaController::index - falha ao contar produtos: ' . $t->getMessage());
        }

        $data = [
            'title' => 'Gerenciamento de vendas',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
            'clientes' => $clientes,
            'vendedores' => $vendedores,
            'produtos' => $produtos,
        ];

        return view('vendas', $data);
    }

    /**
     * Lista todas as vendas cadastradas.
     * Método GET: /vendas/list
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        try {
            // Instanciar o model de vendas
            $vendaModel = new \App\Models\VendaModel();

            // Buscar vendas com relacionamentos (cliente e vendedor)
            $vendas = $vendaModel->buscarComRelacionamentos();

            $data = [];
            foreach ($vendas as $venda) {
                $data[] = [
                    'id' => $venda->v1_id,
                    'numero_da_venda' => $venda->v1_numero_venda,
                    'cliente_id' => $venda->v1_cliente_id,
                    'cliente_nome' => $venda->cliente_nome ?? '-',
                    'vendedor_id' => $venda->v1_vendedor_id,
                    'vendedor_nome' => $venda->v1_vendedor_nome,
                    'vendedor_nome_completo' => $venda->vendedor_nome_completo ?? 'Vendedor não encontrado',
                    'tipo_de_pagamento' => $venda->v1_tipo_de_pagamento,
                    'desconto' => number_format($venda->v1_desconto, 2, ',', '.'),
                    'valor_total' => number_format($venda->v1_valor_total, 2, ',', '.'),
                    'codigo_transacao' => $venda->v1_codigo_transacao,
                    'valor_a_ser_pago' => number_format($venda->v1_valor_a_ser_pago, 2, ',', '.'),
                    'status' => $venda->v1_status,
                    'created_at' => $venda->v1_created_at,
                    'data_pagamento' => $venda->v1_data_pagamento,
                    'data_faturamento' => $venda->v1_data_faturamento,
                    'observacoes' => $venda->v1_observacoes,
                    'updated_at' => $venda->v1_updated_at,
                ];
            }

            return $this->respond($data);

        } catch (\Exception $e) {
            log_message('error', 'Erro ao listar vendas: ' . $e->getMessage());
            return $this->failServerError('Erro interno do servidor ao listar vendas');
        }
    }

    /**
     * Cria uma nova venda.
     * Método POST: /vendas
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()
    {
        try {
            // Log request basics to help diagnose why POST may return HTML
            log_message('debug', 'VendaController::create called; method=' . $this->request->getMethod());
            log_message('debug', 'Request Content-Type: ' . $this->request->getHeaderLine('Content-Type'));

            // Tentar obter JSON via getJSON(); se falhar, usar fallback para o body bruto
            // e também aceitar form-encoded POSTs (application/x-www-form-urlencoded)
            $data = $this->request->getJSON();
            if (empty($data)) {
                $raw = $this->request->getBody();
                log_message('debug', 'Raw request body: ' . substr($raw, 0, 4000));
                $decoded = json_decode($raw);
                if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                    $data = $decoded;
                    log_message('debug', 'Decoded JSON payload: ' . print_r($data, true));
                } else {
                    // Tentar obter como form post
                    $post = $this->request->getPost();
                    if (!empty($post)) {
                        $data = (object) $post;
                        log_message('debug', 'Using POST payload: ' . print_r($post, true));
                    } else {
                        log_message('debug', 'No JSON or POST payload found');
                        $data = null;
                    }
                }
            } else {
                log_message('debug', 'getJSON() returned payload: ' . print_r($data, true));
            }

            // Validações básicas
            if (!is_object($data) || !isset($data->cliente_id) || empty($data->cliente_id)) {
                return $this->failValidationErrors(['cliente_id' => 'Cliente é obrigatório']);
            }

            if (!is_object($data) || !isset($data->vendedor_id) || empty($data->vendedor_id)) {
                return $this->failValidationErrors(['vendedor_id' => 'Vendedor é obrigatório']);
            }

            if (!is_object($data) || !isset($data->tipo_de_pagamento) || empty($data->tipo_de_pagamento)) {
                return $this->failValidationErrors(['tipo_de_pagamento' => 'Tipo de pagamento é obrigatório']);
            }

            // valor_total e status podem vir ausentes do formulário de abertura de venda;
            // aplicar defaults razoáveis
            $valorTotal = 0;
            if (isset($data->valor_total) && $data->valor_total !== '') {
                $valorTotal = (float) $data->valor_total;
                if ($valorTotal < 0) {
                    return $this->failValidationErrors(['valor_total' => 'Valor total deve ser maior ou igual a zero']);
                }
            }

            $status = 'Em Aberto';
            if (isset($data->status) && $data->status !== '') {
                $status = $data->status;
            }

            // Se for PIX, exigir código da transação
            if (isset($data->tipo_de_pagamento) && strtolower($data->tipo_de_pagamento) === 'pix') {
                if (!isset($data->codigo_transacao) || empty($data->codigo_transacao)) {
                    return $this->failValidationErrors(['codigo_transacao' => 'Código da transação é obrigatório para pagamentos PIX']);
                }
            }

            // Preparar dados para inserção
            // Preencher nome do vendedor (necessário para validação e exibição)
            $usuarioModel = new \App\Models\UsuarioModel();
            $vendedorRecord = $usuarioModel->find((int) ($data->vendedor_id ?? 0));
            $vendedorNome = '';
            if ($vendedorRecord && isset($vendedorRecord->u1_nome)) {
                $vendedorNome = $vendedorRecord->u1_nome;
            } else {
                log_message('warning', 'VendaController::create - vendedor não encontrado: ' . print_r($data->vendedor_id ?? null, true));
            }

            $vendaData = [
                // Não definir v1_numero_venda aqui; será calculado após o insert usando o id
                'v1_cliente_id' => (int) $data->cliente_id,
                'v1_vendedor_id' => (int) $data->vendedor_id,
                'v1_vendedor_nome' => $vendedorNome,
                'v1_tipo_de_pagamento' => $data->tipo_de_pagamento,
                'v1_desconto' => isset($data->desconto) ? (float) $data->desconto : 0,
                'v1_valor_total' => $valorTotal,
                'v1_codigo_transacao' => isset($data->codigo_transacao) ? $data->codigo_transacao : null,
                'v1_valor_a_ser_pago' => isset($data->valor_a_ser_pago) ? (float) $data->valor_a_ser_pago : $valorTotal,
                'v1_status' => $status,
                'v1_data_pagamento' => isset($data->data_pagamento) && !empty($data->data_pagamento) ? $data->data_pagamento : null,
                'v1_data_faturamento' => isset($data->data_faturamento) && !empty($data->data_faturamento) ? $data->data_faturamento : null,
                'v1_observacoes' => isset($data->observacoes) ? $data->observacoes : null,
            ];

            // Inserir venda sem numero (será preenchido logo depois usando o id gerado)
            $vendaModel = new \App\Models\VendaModel();
            $tmpData = $vendaData;

            log_message('debug', 'VendaController::create - dados para insert: ' . print_r($tmpData, true));

            // Insert e pegar id
            // Pular validação aqui porque v1_numero_venda é gerado após o insert
            $vendaModel->skipValidation(true);
            try {
                $vendaId = $vendaModel->insert($tmpData);
                log_message('debug', 'VendaController::create - insert returned id: ' . var_export($vendaId, true));
            } catch (\Throwable $th) {
                log_message('error', 'VendaController::create - exception on insert: ' . $th->getMessage());
                log_message('error', $th->getTraceAsString());
                $vendaId = false;
            }

            if (!$vendaId) {
                // Logar erros do model e do DB para diagnóstico
                $modelErrors = method_exists($vendaModel, 'errors') ? $vendaModel->errors() : [];
                log_message('error', 'VendaController::create - insert failed. Model errors: ' . print_r($modelErrors, true));
                $dbError = [];
                if (isset($vendaModel->db)) {
                    $dbError = $vendaModel->db->error();
                    log_message('error', 'VendaController::create - DB error: ' . print_r($dbError, true));
                }

                // Retornar detalhes de erro em JSON (temporário para debug)
                return $this->respond([
                    'error' => 'Erro ao cadastrar venda',
                    'modelErrors' => $modelErrors,
                    'dbError' => $dbError,
                ], 500);
            }

            if ($vendaId) {
                // Gerar numero definitivo com base no id auto-increment (VD0001)
                $numero = sprintf('VD%04d', $vendaId);
                $updateData = ['v1_numero_venda' => $numero];
                $vendaModel->update($vendaId, $updateData);

                return $this->respondCreated([
                    'success' => true,
                    'message' => 'Venda cadastrada com sucesso',
                    'venda_id' => $vendaId,
                    'id' => $vendaId,
                    'numero_venda' => $numero,
                    'venda' => [
                        'id' => $vendaId,
                        'numero_da_venda' => $numero,
                        'cliente_id' => $vendaData['v1_cliente_id'] ?? null,
                        'vendedor_id' => $vendaData['v1_vendedor_id'] ?? null,
                        'tipo_de_pagamento' => $vendaData['v1_tipo_de_pagamento'] ?? null,
                    ]
                ]);
            } else {
                return $this->failServerError('Erro ao cadastrar venda');
            }

        } catch (\Exception $e) {
            log_message('error', 'Erro ao criar venda: ' . $e->getMessage());
            return $this->failServerError('Erro interno do servidor ao criar venda');
        }
    }

    /**
     * Retorna detalhes de uma venda, incluindo produtos e totais.
     * Método GET: /vendas/{id}
     */
    public function show($id = null)
    {
        try {
            if (empty($id)) {
                return $this->failNotFound('ID da venda não informado');
            }

            $vendaModel = new \App\Models\VendaModel();
            $venda = $vendaModel->find($id);

            if (!$venda) {
                return $this->failNotFound('Venda não encontrada');
            }

            // Produtos da venda
            $produtoModel = new \App\Models\ProdutoVendaModel();
            $produtos = [];
            try {
                $rawProdutos = $produtoModel->buscarItensCompletos($id);
                // Mapear campos para o formato esperado pelo frontend
                $produtos = [];
                foreach ($rawProdutos as $it) {
                    $produtos[] = (object) [
                        'produto_nome' => $it->p1_nome_produto ?? ($it->p1_produto_nome ?? null),
                        'produto_codigo' => $it->p1_codigo_produto ?? null,
                        'p3_quantidade' => isset($it->p2_quantidade) ? (int) $it->p2_quantidade : (isset($it->p3_quantidade) ? (int)$it->p3_quantidade : 0),
                        'p3_valor_unitario' => isset($it->p2_valor_unitario) ? (float) $it->p2_valor_unitario : (isset($it->p3_valor_unitario) ? (float)$it->p3_valor_unitario : 0.0),
                        'p3_valor_total' => isset($it->p2_valor_com_desconto) ? (float) $it->p2_valor_com_desconto : (isset($it->p2_subtotal) ? (float)$it->p2_subtotal : 0.0),
                        // manter referências originais caso sejam necessárias no frontend
                        'raw' => $it
                    ];
                }
            } catch (\Throwable $t) {
                log_message('warning', 'VendaController::show - erro ao buscar produtos: ' . $t->getMessage());
                $produtos = [];
            }

            // Serviços: não há modelo explícito para serviços vinculados à venda neste projeto;
            // retornar array vazio para compatibilidade com frontend. Podemos preencher isto
            // no futuro se existir um modelo de serviços por venda.
            $servicos = [];

            // Totais: calcular total de produtos via model de produtos
            $totais = [
                'valor_produtos' => 0,
                'valor_servicos' => 0,
                'valor_total' => 0,
                'desconto' => (float) ($venda->v1_desconto ?? 0),
                'valor_final' => 0
            ];

            try {
                $totalProd = $produtoModel->calcularTotalVenda($id);
                $valorProdutos = (float) ($totalProd->total_venda ?? 0);
                $totais['valor_produtos'] = $valorProdutos;
            } catch (\Throwable $t) {
                log_message('warning', 'VendaController::show - erro ao calcular total de produtos: ' . $t->getMessage());
            }

            // valor_servicos permanece 0 por enquanto
            $totais['valor_total'] = (float) ($venda->v1_valor_total ?? ($totais['valor_produtos'] + $totais['valor_servicos']));
            $totais['valor_final'] = $totais['valor_total'] - $totais['desconto'];

            // Mapear campos da venda para o formato esperado pelo frontend
            $vendaPayload = [
                'id' => $venda->v1_id,
                'numero_da_venda' => $venda->v1_numero_venda,
                'cliente_id' => $venda->v1_cliente_id,
                'cliente_nome' => method_exists($venda, 'cliente_nome') ? $venda->cliente_nome : null,
                'vendedor_id' => $venda->v1_vendedor_id,
                'vendedor_nome' => $venda->v1_vendedor_nome,
                'tipo_de_pagamento' => $venda->v1_tipo_de_pagamento,
                'desconto' => (float) ($venda->v1_desconto ?? 0),
                'valor_total' => (float) ($venda->v1_valor_total ?? 0),
                'valor_a_ser_pago' => (float) ($venda->v1_valor_a_ser_pago ?? $venda->v1_valor_total ?? 0),
                'status' => $venda->v1_status,
                'created_at' => $venda->v1_created_at ?? null,
                'data_pagamento' => $venda->v1_data_pagamento ?? null,
                'data_faturamento' => $venda->v1_data_faturamento ?? null,
                'observacoes' => $venda->v1_observacoes ?? null,
                'codigo_transacao' => $venda->v1_codigo_transacao ?? null,
                'updated_at' => $venda->v1_updated_at ?? null
            ];

            return $this->respond([
                'venda' => $vendaPayload,
                'produtos' => $produtos,
                'servicos' => $servicos,
                'totais' => $totais
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erro em VendaController::show: ' . $e->getMessage());
            return $this->failServerError('Erro interno do servidor ao buscar venda');
        }
    }

    /**
     * Exclui uma venda existente.
     * Método DELETE: /vendas/{id}
     */
    public function delete($id = null)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                return $this->failValidationErrors(['id' => 'ID da venda inválido']);
            }

            $vendaModel = new \App\Models\VendaModel();
            $venda = $vendaModel->find($id);
            if (!$venda) {
                return $this->failNotFound('Venda não encontrada');
            }

            // Tentar deletar
            try {
                $deleted = $vendaModel->delete((int)$id);
            } catch (\Throwable $t) {
                log_message('error', 'VendaController::delete - DB error deleting id ' . $id . ' - ' . $t->getMessage());
                $deleted = false;
            }

            if ($deleted) {
                return $this->respondDeleted(['success' => true, 'message' => 'Venda excluída com sucesso']);
            }

            // If we reach here something went wrong
            $dbError = [];
            if (isset($vendaModel->db)) {
                $dbError = $vendaModel->db->error();
                log_message('error', 'VendaController::delete - DB error: ' . print_r($dbError, true));
            }

            return $this->respond([ 'success' => false, 'message' => 'Não foi possível excluir a venda', 'dbError' => $dbError ], 500);
        } catch (\Exception $e) {
            log_message('error', 'VendaController::delete - exception: ' . $e->getMessage());
            return $this->failServerError('Erro interno ao excluir venda');
        }
    }

    /**
     * Atualiza uma venda existente.
     * Suporta PUT/PATCH e POST (quando servidores bloqueiam PUT e usam X-HTTP-Method-Override).
     * Método PUT: /vendas/{id}  (rotas configuradas para aceitar POST como override)
     */
    public function update($id = null)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                return $this->failValidationErrors(['id' => 'ID da venda inválido']);
            }

            // Tentar obter payload JSON; fallback para post/form
            $payload = $this->request->getJSON(true);
            if (empty($payload)) {
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $payload = $post;
                } else {
                    // tentar raw input (por exemplo application/json sem parse automático)
                    $raw = $this->request->getBody();
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                        $payload = $decoded;
                    } else {
                        $payload = [];
                    }
                }
            }

            if (empty($payload) || !is_array($payload)) {
                return $this->failValidationErrors(['payload' => 'Payload inválido para atualização']);
            }

            $vendaModel = new \App\Models\VendaModel();
            $venda = $vendaModel->find($id);
            if (!$venda) {
                return $this->failNotFound('Venda não encontrada');
            }

            // Campos permitidos para atualização
            $allowed = [
                'cliente_id' => 'v1_cliente_id',
                'vendedor_id' => 'v1_vendedor_id',
                'tipo_de_pagamento' => 'v1_tipo_de_pagamento',
                'desconto' => 'v1_desconto',
                'valor_total' => 'v1_valor_total',
                'codigo_transacao' => 'v1_codigo_transacao',
                'valor_a_ser_pago' => 'v1_valor_a_ser_pago',
                'status' => 'v1_status',
                'data_pagamento' => 'v1_data_pagamento',
                'data_faturamento' => 'v1_data_faturamento',
                'observacoes' => 'v1_observacoes'
            ];

            $updateData = [];
            foreach ($allowed as $in => $dbField) {
                if (array_key_exists($in, $payload)) {
                    $val = $payload[$in];
                    // Cast numéricos quando aplicável
                    if (in_array($in, ['cliente_id', 'vendedor_id'])) {
                        $updateData[$dbField] = (int) $val;
                    } elseif (in_array($in, ['desconto', 'valor_total', 'valor_a_ser_pago'])) {
                        $updateData[$dbField] = $val === '' || $val === null ? 0 : (float) $val;
                    } else {
                        $updateData[$dbField] = $val;
                    }
                }
            }

            if (empty($updateData)) {
                return $this->failValidationErrors(['payload' => 'Nenhum campo válido para atualizar']);
            }

            try {
                // Alguns ambientes/requests enviam apenas campos parciais.
                // O model possui regras de validação que exigem campos como v1_numero_venda
                // e v1_vendedor_nome; para updates parciais pulamos a validação aqui
                // e confiamos na camada de aplicação para enviar campos corretos.
                $vendaModel->skipValidation(true);
                $ok = $vendaModel->update((int)$id, $updateData);
            } catch (\Throwable $t) {
                log_message('error', 'VendaController::update - update error: ' . $t->getMessage());
                $ok = false;
            }

            if ($ok) {
                return $this->respond(['success' => true, 'message' => 'Venda atualizada com sucesso']);
            }

            // Se update retornou false, tentar obter erros de validação do model
            $modelErrors = [];
            if (method_exists($vendaModel, 'errors')) {
                $modelErrors = $vendaModel->errors();
            }
            log_message('error', 'VendaController::update - falha ao atualizar. Model errors: ' . print_r($modelErrors, true));

            $dbError = [];
            if (isset($vendaModel->db)) {
                $dbError = $vendaModel->db->error();
            }

            return $this->respond(['success' => false, 'message' => 'Não foi possível atualizar a venda', 'modelErrors' => $modelErrors, 'dbError' => $dbError], 500);

        } catch (\Exception $e) {
            log_message('error', 'VendaController::update - exception: ' . $e->getMessage());
            return $this->failServerError('Erro interno ao atualizar venda');
        }
    }

    /**
     * Gera um número único para a venda
     * @return int
     */
    private function gerarNumeroVenda()
    {
        // Gerar número sequencial com prefixo 'VD' (ex: VD0001)
        try {
            $vendaModel = new \App\Models\VendaModel();

            // Buscar última venda que possua número iniciando com 'VD'
            $last = $vendaModel->like('v1_numero_venda', 'VD', 'after')->orderBy('v1_id', 'DESC')->first();

            if ($last && !empty($last->v1_numero_venda)) {
                $lastNum = $last->v1_numero_venda;
                // Extrair parte numérica
                $numPart = preg_replace('/\D/', '', $lastNum);
                $next = intval($numPart) + 1;
            } else {
                $next = 1;
            }

            return sprintf('VD%04d', $next);
        } catch (\Exception $e) {
            // Em caso de erro, fallback para timestamp + random
            $timestamp = time();
            $random = rand(100, 999);
            return 'VD' . substr($timestamp . $random, -8);
        }
    }

    /**
     * Salva operações (produtos) para uma venda existente.
     * Recebe JSON: { produtos: [ { produto_id, quantidade, preco_unitario, desconto? } ] }
     * Método POST: /vendas/{id}/operacoes  (rota já existe) and also accept /vendas/{id}/produtos alias
     */
    public function saveOperacoes($vendaId = null)
    {
        try {
            if (empty($vendaId) || !is_numeric($vendaId)) {
                return $this->failValidationErrors(['venda_id' => 'ID da venda inválido']);
            }

            // Verificar se a venda existe
            $vendaModelCheck = new \App\Models\VendaModel();
            $vendaExists = $vendaModelCheck->find((int)$vendaId);
            if (!$vendaExists) {
                return $this->failNotFound('Venda não encontrada: ' . $vendaId);
            }

            // Ler payload JSON com fallback
            $payload = $this->request->getJSON(true); // assoc array
            if (empty($payload) || !isset($payload['produtos']) || !is_array($payload['produtos'])) {
                return $this->failValidationErrors(['produtos' => 'Payload inválido. Esperado { produtos: [...] }']);
            }

            $produtoVendaModel = new \App\Models\ProdutoVendaModel();
            $produtoModel = new \App\Models\ProdutoModel();
            $inserts = [];

            foreach ($payload['produtos'] as $item) {
                // Normalizar campos
                $produtoId = isset($item['produto_id']) ? (int)$item['produto_id'] : null;
                $quantidade = isset($item['quantidade']) ? (int)$item['quantidade'] : 0;
                $preco = isset($item['preco_unitario']) ? (float)$item['preco_unitario'] : 0.0;
                $desconto = isset($item['desconto']) ? (float)$item['desconto'] : 0.0;

                if (empty($produtoId) || $quantidade <= 0 || $preco <= 0) {
                    return $this->failValidationErrors(['produtos' => 'Cada item deve conter produto_id, quantidade > 0 e preco_unitario > 0']);
                }

                // Checar existência do produto e estoque disponível
                $produtoRecord = $produtoModel->find($produtoId);
                if (!$produtoRecord) {
                    return $this->failNotFound('Produto não encontrado: ' . $produtoId);
                }

                if (isset($produtoRecord->p1_quantidade_produto) && $produtoRecord->p1_quantidade_produto < $quantidade) {
                    return $this->fail(['message' => 'Estoque insuficiente para o produto: ' . ($produtoRecord->p1_nome_produto ?? $produtoId)], 400);
                }

                // Inserir item na venda (model fará cálculos de subtotal/valor com desconto via callbacks)
                $newId = $produtoVendaModel->adicionarProduto((int)$vendaId, $produtoId, $quantidade, $preco, $desconto);
                if (!$newId) {
                    $errors = method_exists($produtoVendaModel, 'errors') ? $produtoVendaModel->errors() : [];
                    $dbError = [];
                    if (isset($produtoVendaModel->db)) {
                        $dbError = $produtoVendaModel->db->error();
                    }
                    log_message('error', 'VendaController::saveOperacoes - falha ao inserir item. Model errors: ' . print_r($errors, true) . ' DB error: ' . print_r($dbError, true));
                    // Retornar detalhes de erro para diagnóstico
                    return $this->respond([
                        'error' => 'Erro ao adicionar produto à venda',
                        'modelErrors' => $errors,
                        'dbError' => $dbError,
                    ], 500);
                }

                // Diminuir estoque do produto
                $estoqueOK = $produtoModel->diminuirEstoque($produtoId, $quantidade);
                if (!$estoqueOK) {
                    // Se não conseguiu atualizar estoque (por exemplo estoque negativo), reverter a inserção
                    try {
                        $produtoVendaModel->delete($newId);
                    } catch (\Throwable $t) {
                        log_message('error', 'VendaController::saveOperacoes - falha ao reverter item inserido: ' . $t->getMessage());
                    }
                    return $this->fail(['message' => 'Não foi possível atualizar o estoque para o produto: ' . $produtoId], 500);
                }

                $inserts[] = $newId;
            }

            // Opcional: recalcular valor total da venda e atualizar o registro de venda
            try {
                $totalObj = $produtoVendaModel->calcularTotalVenda($vendaId);
                $valorProdutos = isset($totalObj->total_venda) ? (float)$totalObj->total_venda : 0.0;
                $vendaModel = new \App\Models\VendaModel();
                $vendaRecord = $vendaModel->find($vendaId);
                if ($vendaRecord) {
                    $novoTotal = $valorProdutos + 0; // serviços ainda são 0
                    $vendaModel->update($vendaId, ['v1_valor_total' => $novoTotal, 'v1_valor_a_ser_pago' => $novoTotal]);
                }
            } catch (\Throwable $t) {
                log_message('warning', 'VendaController::saveOperacoes - falha ao recalcular total: ' . $t->getMessage());
            }

            return $this->respond(['success' => true, 'inserted_ids' => $inserts]);

        } catch (\Exception $e) {
            log_message('error', 'VendaController::saveOperacoes - exception: ' . $e->getMessage());
            return $this->failServerError('Erro interno ao salvar operações');
        }
    }

    /**
     * Faturar uma venda: define data de faturamento, observações e atualiza status para 'Faturado'.
     * Método POST: /vendas/{id}/faturar
     */
    public function faturar($id = null)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                return $this->failValidationErrors(['id' => 'ID da venda inválido']);
            }

            // Ler payload (aceitar JSON ou form)
            $payload = $this->request->getJSON(true);
            if (empty($payload)) {
                $post = $this->request->getPost();
                $payload = is_array($post) ? $post : [];
            }

            $dataFaturamento = $payload['data_faturamento'] ?? ($payload['data'] ?? null);
            $observacoes = $payload['observacoes'] ?? null;

            if (empty($dataFaturamento)) {
                return $this->failValidationErrors(['data_faturamento' => 'Data de faturamento é obrigatória']);
            }

            // Buscar venda
            $vendaModel = new \App\Models\VendaModel();
            $venda = $vendaModel->find($id);
            if (!$venda) {
                return $this->failNotFound('Venda não encontrada');
            }

            // Atualizar campos
            $update = [
                'v1_data_faturamento' => $dataFaturamento,
                'v1_observacoes' => $observacoes,
                'v1_status' => 'Faturado'
            ];

            try {
                $ok = $vendaModel->update((int)$id, $update);
            } catch (\Throwable $t) {
                log_message('error', 'VendaController::faturar - update error: ' . $t->getMessage());
                $ok = false;
            }

            if ($ok) {
                return $this->respond(['success' => true, 'message' => 'Venda faturada com sucesso']);
            }

            $dbError = [];
            if (isset($vendaModel->db)) {
                $dbError = $vendaModel->db->error();
            }
            return $this->respond(['success' => false, 'message' => 'Não foi possível faturar a venda', 'dbError' => $dbError], 500);

        } catch (\Exception $e) {
            log_message('error', 'VendaController::faturar - exception: ' . $e->getMessage());
            return $this->failServerError('Erro interno ao faturar venda');
        }
    }
}
