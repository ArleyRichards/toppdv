<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Vendas</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todas as vendas efetuadas no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addVendaModal">
                    <i class="fa-solid fa-plus me-1"></i> Novo
                </button>
                <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-home me-1"></i> Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 animate-fade-in" id="filtersContainer" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-filter me-2"></i> Filtros de Pesquisa</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Primeira linha -->
                <div class="col-lg-2 col-md-6">
                    <label for="filterNumero" class="form-label">Número Venda</label>
                    <input type="text" class="form-control" id="filterNumero" placeholder="Ex: 1001">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterCliente" class="form-label">Cliente</label>
                    <input type="text" class="form-control" id="filterCliente" placeholder="Nome do cliente">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterVendedor" class="form-label">Vendedor</label>
                    <input type="text" class="form-control" id="filterVendedor" placeholder="Nome do vendedor">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos</option>
                        <option value="Em Aberto">Em Aberto</option>
                        <option value="Faturado">Faturado</option>
                        <option value="Atrasado">Atrasado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterTipoPagamento" class="form-label">Tipo Pagamento</label>
                    <select class="form-select" id="filterTipoPagamento">
                        <option value="">Todos</option>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="cartao_credito">Cartão Crédito</option>
                        <option value="cartao_debito">Cartão Débito</option>
                        <option value="pix">PIX</option>
                        <option value="transferencia">Transferência</option>
                        <option value="boleto">Boleto</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterValorMin" class="form-label">Valor Mínimo</label>
                    <input type="text" class="form-control" id="filterValorMin" placeholder="R$ 0,00">
                </div>

                <!-- Segunda linha -->
                <div class="col-lg-2 col-md-6">
                    <label for="filterValorMax" class="form-label">Valor Máximo</label>
                    <input type="text" class="form-control" id="filterValorMax" placeholder="R$ 0,00">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterDataInicial" class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" id="filterDataInicial" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterDataFinal" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="filterDataFinal" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="col-lg-2 col-md-6 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser me-2"></i>Limpar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de vendas -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Vendas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Número Venda</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Tipo Pagamento</th>
                            <th>Status</th>
                            <th class="text-end">Valor Total</th>
                            <th class="text-end">Valor a Pagar</th>
                            <th>Data Venda</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="vendasTableBody">
                        <!-- Será preenchido via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginação -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <label for="itemsPerPage" class="form-label me-2">Itens por página:</label>
                    <select class="form-select form-select-sm" id="itemsPerPage" style="width: auto;">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div id="paginationInfo" class="text-muted"></div>
            </div>

            <!-- Paginação -->
            <nav aria-label="Navegação da página" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Será preenchido via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal de visualização -->
<div class="modal fade" id="vendaModal" tabindex="-1" aria-labelledby="vendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendaModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes da Venda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="vendaModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editVendaBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Venda
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de venda -->
<div class="modal fade" id="addVendaModal" tabindex="-1" aria-labelledby="addVendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVendaModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Abrir Nova Venda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addVendaForm" class="needs-validation" novalidate>
                    <?php if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } ?>
                    <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="action" value="add_venda">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="add-venda-cliente-id" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-venda-cliente-id" name="cliente_id" required>
                                <option value="">Selecione um cliente...</option>
                                <?php if (!empty($clientes) && is_array($clientes)): ?>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= esc($c->c2_id) ?>"><?= esc($c->c2_nome) ?> <?= !empty($c->c2_cpf) ? '(' . esc($c->c2_cpf) . ')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="add-venda-cliente-id-error">Informe o cliente.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="add-venda-vendedor-id" class="form-label">Vendedor <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-venda-vendedor-id" name="vendedor_id" required>
                                <option value="">Selecione um vendedor...</option>
                                <?php if (!empty($vendedores) && is_array($vendedores)): ?>
                                    <?php foreach ($vendedores as $v): ?>
                                        <option value="<?= esc($v->u1_id) ?>" data-nome="<?= esc($v->u1_nome) ?>"><?= esc($v->u1_nome) ?> (<?= esc($v->u1_usuario_acesso) ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="add-venda-vendedor-id-error">Selecione o vendedor.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="add-venda-tipo-pagamento" class="form-label">Tipo de Pagamento <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-venda-tipo-pagamento" name="tipo_de_pagamento" required>
                                <option value="">Selecione...</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao_credito">Cartão Crédito</option>
                                <option value="cartao_debito">Cartão Débito</option>
                                <option value="pix">PIX</option>
                                <option value="transferencia">Transferência</option>
                                <option value="a_prazo">A Prazo</option>
                            </select>
                            <div class="invalid-feedback" id="add-venda-tipo-pagamento-error">Selecione o tipo de pagamento.</div>
                        </div>

                        <!-- Valor Total e Desconto removidos do modal de abertura de venda - calculados via operações ou definidos no servidor -->

                        <!-- Código da Transação -->
                        <div class="col-md-4">
                            <label for="add-venda-codigo-transacao" class="form-label">Código da Transação</label>
                            <input type="text" class="form-control" id="add-venda-codigo-transacao" name="codigo_transacao" placeholder="Código da transação" disabled>
                            <div class="invalid-feedback" id="add-venda-codigo-transacao-error">Campo obrigatório para pagamentos PIX.</div>
                        </div>

                        <div class="col-md-2">
                            <label for="add-venda-data-pagamento" class="form-label">Data de Pagamento</label>
                            <input type="date" class="form-control" id="add-venda-data-pagamento" name="data_pagamento" disabled>
                            <small class="form-text text-muted">Campo habilitado apenas para pagamentos "A Prazo"</small>
                        </div>

                        <!-- Status removido no modal de abertura de venda (usado valor padrão no servidor) -->

                        <div class="col-12">
                            <label for="add-venda-observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="add-venda-observacoes" name="observacoes" rows="3" placeholder="Observações sobre a venda"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveVendaBtn">
                    <i class="fa-solid fa-save me-1"></i>Abrir Venda
                </button>
            </div>
        </div>

<!-- Modal de Edição de Venda -->
<div class="modal fade" id="editVendaModal" tabindex="-1" aria-labelledby="editVendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVendaModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Venda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editVendaForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit-venda-id" name="id">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit-venda-cliente-id" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-venda-cliente-id" name="cliente_id" required>
                                <option value="">Selecione um cliente...</option>
                                <?php if (!empty($clientes) && is_array($clientes)): ?>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= esc($c->c2_id) ?>"><?= esc($c->c2_nome) ?> <?= !empty($c->c2_cpf) ? '(' . esc($c->c2_cpf) . ')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="edit-venda-cliente-id-error">Informe o cliente.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit-venda-vendedor-id" class="form-label">Vendedor <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-venda-vendedor-id" name="vendedor_id" required>
                                <option value="">Selecione um vendedor...</option>
                                <?php if (!empty($vendedores) && is_array($vendedores)): ?>
                                    <?php foreach ($vendedores as $v): ?>
                                        <option value="<?= esc($v->u1_id) ?>" data-nome="<?= esc($v->u1_nome) ?>"><?= esc($v->u1_nome) ?> (<?= esc($v->u1_usuario_acesso) ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="edit-venda-vendedor-id-error">Selecione o vendedor.</div>
                        </div>
                        

                        <div class="col-md-4">
                            <label for="edit-venda-tipo-pagamento" class="form-label">Tipo de Pagamento <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-venda-tipo-pagamento" name="tipo_de_pagamento" required>
                                <option value="">Selecione...</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao_credito">Cartão Crédito</option>
                                <option value="cartao_debito">Cartão Débito</option>
                                <option value="pix">PIX</option>
                                <option value="transferencia">Transferência</option>
                                <option value="a_prazo">A Prazo</option>
                            </select>
                            <div class="invalid-feedback" id="edit-venda-tipo-pagamento-error">Selecione o tipo de pagamento.</div>
                        </div>

                        <div class="col-md-2 d-none">
                            <label for="edit-venda-valor-total" class="form-label">Valor Total</label>
                            <input type="text" class="form-control money-mask" id="edit-venda-valor-total" name="valor_total" value="0,00">
                            <div class="invalid-feedback" id="edit-venda-valor-total-error">Informe o valor total.</div>
                        </div>

                        <div class="col-md-2 d-none">
                            <label for="edit-venda-desconto" class="form-label">Desconto</label>
                            <input type="text" class="form-control money-mask" id="edit-venda-desconto" name="desconto" value="0,00">
                            <div class="invalid-feedback" id="edit-venda-desconto-error">Informe o desconto (ou 0).</div>
                        </div>

                        <!-- Código da Transação -->
                        <div class="col-md-4">
                            <label for="edit-venda-codigo-transacao" class="form-label">Código da Transação</label>
                            <input type="text" class="form-control" id="edit-venda-codigo-transacao" name="codigo_transacao" placeholder="Código da transação" disabled>
                            <div class="invalid-feedback" id="edit-venda-codigo-transacao-error">Campo obrigatório para pagamentos PIX.</div>
                        </div>

                        <div class="col-md-2">
                            <label for="edit-venda-status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-venda-status" name="status" required>
                                <option value="Em Aberto">Em Aberto</option>
                                <option value="Faturado">Faturado</option>
                                <option value="Atrasado">Atrasado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                            <div class="invalid-feedback" id="edit-venda-status-error">Selecione o status.</div>
                        </div>

                        <div class="col-md-2">
                            <label for="edit-venda-data-pagamento" class="form-label">Data de Pagamento</label>
                            <input type="date" class="form-control" id="edit-venda-data-pagamento" name="data_pagamento" disabled>
                            <small class="form-text text-muted">Campo habilitado apenas para pagamentos "A Prazo"</small>
                        </div>
                        <div class="col-md-6 d-none">
                            <label for="edit-venda-data-faturamento" class="form-label">Data de Faturamento</label>
                            <input type="date" class="form-control" id="edit-venda-data-faturamento" name="data_faturamento">
                        </div>

                        <div class="col-12">
                            <label for="edit-venda-observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="edit-venda-observacoes" name="observacoes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="updateVendaBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Venda
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="operacoesModal" tabindex="-1" aria-labelledby="operacoesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="operacoesModalLabel">
                    <i class="fa-solid fa-boxes-stacked text-info me-2"></i>Adicionar Produtos à Venda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="operacoesForm">
                    <input type="hidden" id="operacoes-venda-id" name="venda_id" value="">
                    <div class="mb-3">
                        <small class="text-muted">Adicione produtos à venda.</small>
                    </div>

                    <!-- Card de Produtos -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fa-solid fa-box text-primary me-2"></i>Produtos</h6>
                        </div>
                        <div class="card-body">
                            <!-- Formulário de adição de produto -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="produto-select" class="form-label">Produto</label>
                                    <select class="form-select form-select-sm" id="produto-select">
                                        <option value="">Selecione um produto...</option>
                                        <?php if (!empty($produtos) && is_array($produtos)): ?>
                                            <?php foreach ($produtos as $produto): ?>
                                                <option value="<?= esc($produto->p1_id) ?>" data-preco="<?= esc($produto->p1_preco_venda_produto ?? 0) ?>">
                                                    <?= esc($produto->p1_nome_produto) ?> - R$ <?= number_format($produto->p1_preco_venda_produto ?? 0, 2, ',', '.') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="produto-quantidade" class="form-label">Qtd</label>
                                    <input type="number" class="form-control form-control-sm" id="produto-quantidade" min="1" value="1">
                                </div>
                                <div class="col-md-2">
                                    <label for="produto-preco" class="form-label">Preço Unit.</label>
                                    <input type="text" class="form-control form-control-sm" id="produto-preco" value="0,00" disabled readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="produto-subtotal" class="form-label">Subtotal</label>
                                    <input type="text" class="form-control form-control-sm" id="produto-subtotal" value="0,00" disabled readonly>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="addProdutoBtn">
                                        <i class="fa-solid fa-plus me-1"></i>Adicionar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabela de produtos adicionados -->
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="produtosTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Produto</th>
                                            <th style="width:100px">Qtd</th>
                                            <th style="width:120px">Preço Unit.</th>
                                            <th style="width:120px">Subtotal</th>
                                            <th style="width:80px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- linhas serão adicionadas dinamicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Serviços removidos: modal focado apenas em produtos -->

                    <!-- Totais -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <small>Total Produtos: R$ <span id="produtosTotal">0.00</span></small>
                        </div>
                        <div class="text-end">
                            <div><small class="text-muted">Total Geral</small></div>
                            <h4>R$ <span id="operacoesTotal">0.00</span></h4>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="saveOperacoesBtn">Salvar Operações</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Faturamento -->
<div class="modal fade" id="faturarModal" tabindex="-1" aria-labelledby="faturarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faturarModalLabel"><i class="fa-solid fa-calculator text-success me-2"></i>Faturar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="faturarForm">
                    <input type="hidden" id="faturar-venda-id" name="id" value="">
                    <div class="mb-3">
                        <label for="faturar-data" class="form-label">Data de Faturamento <span class="text-danger">*</span></label>
                        <input type="date" id="faturar-data" name="data_faturamento" class="form-control" required>
                        <div class="invalid-feedback">Informe a data de faturamento.</div>
                    </div>
                    <div class="mb-3">
                        <label for="faturar-observacoes" class="form-label">Observações</label>
                        <textarea id="faturar-observacoes" name="observacoes" class="form-control" rows="3" placeholder="Observações sobre o faturamento (opcional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="faturarSubmitBtn">Faturar</button>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<!-- Select2 dark theme overrides scoped to the add venda modal -->
<!-- Select2 CSS/JS (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Scope to modal so other pages unaffected */
    #addVendaModal .select2-container--default .select2-selection--single {
        background-color: #2b3035;
        /* dark gray */
        color: #e9ecef;
        /* light text */
        border: 1px solid #454d55;
        padding: .375rem .75rem;
        border-radius: .375rem;
        height: auto;
    }

    #addVendaModal .select2-container--default .select2-selection__rendered {
        color: #e9ecef;
    }

    #addVendaModal .select2-container--default .select2-selection__placeholder {
        color: #adb5bd;
        /* muted */
    }

    #addVendaModal .select2-container--default .select2-selection__arrow b {
        border-color: #e9ecef;
    }

    #addVendaModal .select2-dropdown {
        background-color: #212529;
        /* modal darker bg */
        color: #e9ecef;
        border: 1px solid #343a40;
    }

    #addVendaModal .select2-results__option {
        padding: .5rem .75rem;
    }

    #addVendaModal .select2-results__option--highlighted[aria-selected],
    #addVendaModal .select2-results__option[aria-selected='true'] {
        background-color: #0d6efd;
        /* bootstrap primary */
        color: #fff;
    }

    #addVendaModal .select2-container--open .select2-selection--single {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
    }

    #addVendaModal .select2-container .select2-selection__clear {
        color: #adb5bd;
    }

    /* Select2 dark theme overrides scoped to the edit venda modal */
    #editVendaModal .select2-container--default .select2-selection--single {
        background-color: #2b3035;
        /* dark gray */
        color: #e9ecef;
        /* light text */
        border: 1px solid #454d55;
        padding: .375rem .75rem;
        border-radius: .375rem;
        height: auto;
    }

    #editVendaModal .select2-container--default .select2-selection__rendered {
        color: #e9ecef;
    }

    #editVendaModal .select2-container--default .select2-selection__placeholder {
        color: #adb5bd;
        /* muted */
    }

    #editVendaModal .select2-container--default .select2-selection__arrow b {
        border-color: #e9ecef;
    }

    #editVendaModal .select2-dropdown {
        background-color: #212529;
        /* modal darker bg */
        color: #e9ecef;
        border: 1px solid #343a40;
    }

    #editVendaModal .select2-results__option {
        padding: .5rem .75rem;
    }

    #editVendaModal .select2-results__option--highlighted[aria-selected],
    #editVendaModal .select2-results__option[aria-selected='true'] {
        background-color: #0d6efd;
        /* bootstrap primary */
        color: #fff;
    }

    #editVendaModal .select2-container--open .select2-selection--single {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
    }

    #editVendaModal .select2-container .select2-selection__clear {
        color: #adb5bd;
    }

    /* Select2 dark theme overrides scoped to the operacoes modal (produto-select) */
    #operacoesModal .select2-container--default .select2-selection--single {
        background-color: #2b3035;
        color: #e9ecef;
        border: 1px solid #454d55;
        padding: .375rem .75rem;
        border-radius: .375rem;
        height: auto;
    }

    #operacoesModal .select2-container--default .select2-selection__rendered {
        color: #e9ecef;
    }

    #operacoesModal .select2-container--default .select2-selection__placeholder {
        color: #adb5bd;
    }

    #operacoesModal .select2-container--default .select2-selection__arrow b {
        border-color: #e9ecef;
    }

    #operacoesModal .select2-dropdown {
        background-color: #212529;
        color: #e9ecef;
        border: 1px solid #343a40;
    }

    #operacoesModal .select2-results__option {
        padding: .5rem .75rem;
    }

    #operacoesModal .select2-results__option--highlighted[aria-selected],
    #operacoesModal .select2-results__option[aria-selected='true'] {
        background-color: #0d6efd;
        color: #fff;
    }

    #operacoesModal .select2-container--open .select2-selection--single {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
    }

    #operacoesModal .select2-container .select2-selection__clear {
        color: #adb5bd;
    }
</style>

<script>
(function() {
    'use strict';
    
    // Namespace para evitar conflitos globais
    window.VendasManager = window.VendasManager || {};
    
    // Configuração e constantes
    const CONFIG = {
    // Garantir que BASE_URL termine com '/' para concatenações seguras
    BASE_URL: <?= json_encode(rtrim(site_url('vendas'), '/') . '/') ?>,
        CSRF_TOKEN: <?= json_encode(csrf_token()) ?>,
    CUPOM_URL: <?= json_encode(site_url('pdv/downloadCupom/')) ?>,
        DATE_FORMAT: 'pt-BR',
        MONEY_MASK: '000.000.000.000.000,00'
    };
    
    // Estado da aplicação
    const STATE = {
        vendasData: [],
        filteredVendas: [],
        currentPage: 1,
        itemsPerPage: 25,
        isInitialized: false
    };

    // Verificar se jQuery está carregado
    if (typeof jQuery === 'undefined') {
        console.error('jQuery não foi carregado. Tentando carregar novamente...');
        // Tentar carregar jQuery novamente se não estiver disponível
        var script = document.createElement('script');
        script.src = 'https://code.jquery.com/jquery-3.7.1.min.js';
        script.onload = function() {
            console.log('jQuery carregado com sucesso');
            initializeVendasPage();
        };
        script.onerror = function() {
            console.error('Falha ao carregar jQuery');
        };
        document.head.appendChild(script);
    } else {
        // jQuery já está carregado, inicializar a página
        initializeVendasPage();
    }

    function initializeVendasPage() {
        // Verificar se já foi inicializado
        if (STATE.isInitialized) {
            return;
        }
        
        // Aguardar DOM estar carregado
        $(document).ready(function() {
            try {
                // Inicializar componentes
                initializeSelect2Components();
                setupEventListeners();
                setupFormValidation();
                setupMasks();
                loadVendas();
                
                STATE.isInitialized = true;
                console.log('Vendas page initialized successfully');
            } catch (error) {
                console.error('Error initializing vendas page:', error);
                showAlert('error', 'Erro ao inicializar a página. Recarregue e tente novamente.');
            }
        });
    }

    // Inicializar componentes Select2 de forma segura
    function initializeSelect2Components() {
        try {
            // Select2 para cliente (add modal)
            if ($('#add-venda-cliente-id').length) {
                $('#add-venda-cliente-id').select2({
                    placeholder: 'Selecione um cliente...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addVendaModal')
                });
            }

            // Select2 para vendedor (add modal)
            if ($('#add-venda-vendedor-id').length) {
                $('#add-venda-vendedor-id').select2({
                    placeholder: 'Selecione um vendedor...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addVendaModal')
                });
            }

            // Select2 para cliente (edit modal)
            if ($('#edit-venda-cliente-id').length) {
                $('#edit-venda-cliente-id').select2({
                    placeholder: 'Selecione um cliente...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editVendaModal')
                });
            }

            // Select2 para vendedor (edit modal)
            if ($('#edit-venda-vendedor-id').length) {
                $('#edit-venda-vendedor-id').select2({
                    placeholder: 'Selecione um vendedor...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editVendaModal')
                });
            }
        } catch (error) {
            console.warn('Select2 não foi carregado ou erro na inicialização:', error);
        }
    }

    // Configurar event listeners de forma segura
    function setupEventListeners() {
        // Event listeners para filtros
        $('#filterNumero').off('input.vendas').on('input.vendas', applyFilters);
        $('#filterCliente').off('input.vendas').on('input.vendas', applyFilters);
        $('#filterVendedor').off('input.vendas').on('input.vendas', applyFilters);
        $('#filterStatus').off('change.vendas').on('change.vendas', applyFilters);
        $('#filterTipoPagamento').off('change.vendas').on('change.vendas', applyFilters);
        $('#filterValorMin').off('input.vendas').on('input.vendas', applyFilters);
        $('#filterValorMax').off('input.vendas').on('input.vendas', applyFilters);
        $('#filterDataInicial').off('change.vendas').on('change.vendas', applyFilters);
        $('#filterDataFinal').off('change.vendas').on('change.vendas', applyFilters);
        $('#clearFilters').off('click.vendas').on('click.vendas', clearFilters);

        // Event listeners para paginação
        $('#itemsPerPage').off('change.vendas').on('change.vendas', function() {
            STATE.itemsPerPage = parseInt($(this).val()) || 25;
            STATE.currentPage = 1;
            renderTable();
        });

        // Event listeners para botões de ação
        $('#saveVendaBtn').off('click.vendas').on('click.vendas', function(e) {
            e.preventDefault();
            saveVenda();
        });

        $('#updateVendaBtn').off('click.vendas').on('click.vendas', function(e) {
            e.preventDefault();
            updateVenda();
        });

        $('#editVendaBtn').off('click.vendas').on('click.vendas', function() {
            const id = $(this).data('venda-id');
            if (id) {
                closeModal('vendaModal');
                openEditModal(id);
            }
        });

        // Event listeners para operações
        $(document).off('click.vendas', '#addProdutoBtn').on('click.vendas', '#addProdutoBtn', function(e) {
            e.preventDefault();
            addProduto();
        });

        $(document).off('click.vendas', '#saveOperacoesBtn').on('click.vendas', '#saveOperacoesBtn', function(e) {
            e.preventDefault();
            saveOperacoes();
        });

        // Event listeners para produto select (modal operações)
        $(document).off('change.vendas', '#produto-select').on('change.vendas', '#produto-select', function() {
            const preco = $(this).find('option:selected').data('preco') || 0;
            $('#produto-preco').val(formatMoney(preco));
            updateProdutoSubtotal();
        });

        // Tipo de pagamento change handlers for add/edit modals
        $(document).off('change.vendas', '#add-venda-tipo-pagamento').on('change.vendas', '#add-venda-tipo-pagamento', function() {
            const tipo = $(this).val() || '';
            try { handleTipoPagamentoFields('add', tipo); } catch (e) { console.warn('handleTipoPagamentoFields not available yet', e); }
        });

        $(document).off('change.vendas', '#edit-venda-tipo-pagamento').on('change.vendas', '#edit-venda-tipo-pagamento', function() {
            const tipo = $(this).val() || '';
            try { handleTipoPagamentoFields('edit', tipo); } catch (e) { console.warn('handleTipoPagamentoFields not available yet', e); }
        });

        $(document).off('input.vendas', '#produto-quantidade').on('input.vendas', '#produto-quantidade', function() {
            updateProdutoSubtotal();
        });
    }

    // Atualizar subtotal do produto no modal
    function updateProdutoSubtotal() {
        try {
            const quantidade = parseInt($('#produto-quantidade').val()) || 1;
            const precoText = $('#produto-preco').val() || '0,00';
            const preco = parseMoney(precoText);
            const subtotal = quantidade * preco;
            $('#produto-subtotal').val(formatMoney(subtotal));
        } catch (error) {
            console.error('Erro ao atualizar subtotal do produto:', error);
        }
    }

    // Fechar modal de forma segura
    function closeModal(modalId) {
        try {
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        } catch (error) {
            console.error('Erro ao fechar modal:', error);
        }
    }

    // Toggle dos Filtros com jQuery (função global necessária para onclick)
    window.toggleFilters = function() {
        try {
            const $container = $('#filtersContainer');
            const $btn = $('button').filter(function() {
                return $(this).attr('onclick') === 'toggleFilters()';
            });

            if ($container.length && $btn.length) {
                if ($container.css('display') === 'none' || $container.css('display') === '') {
                    $container.show();
                    $btn.html('<i class="fa-solid fa-filter me-1"></i>Ocultar Filtros');
                    $btn.removeClass('btn-outline-secondary').addClass('btn-warning');
                } else {
                    $container.hide();
                    $btn.html('<i class="fa-solid fa-filter me-1"></i>Filtros');
                    $btn.removeClass('btn-warning').addClass('btn-outline-secondary');
                }
            }
        } catch (error) {
            console.error('Erro ao toggle filtros:', error);
        }
    };

    // Carregar dados das vendas de forma segura
    async function loadVendas() {
        try {
            const response = await $.ajax({
                url: CONFIG.BASE_URL + 'list',
                method: 'GET',
                dataType: 'json',
                timeout: 10000
            });

            if (Array.isArray(response)) {
                STATE.vendasData = response.map(venda => sanitizeVendaData(venda));
                STATE.filteredVendas = [...STATE.vendasData];
                renderTable();
            } else {
                throw new Error('Resposta inválida do servidor');
            }
        } catch (error) {
            console.error('Erro ao carregar vendas:', error);
            showAlert('error', 'Erro ao carregar dados das vendas. Verifique sua conexão.');
        }
    }

    // Sanitizar dados da venda
    function sanitizeVendaData(venda) {
        return {
            id: venda.id || '',
            numero_da_venda: venda.numero_da_venda || '-',
            cliente_id: venda.cliente_id || '',
            cliente_nome: venda.cliente_nome || '-',
            vendedor_id: venda.vendedor_id || '',
            vendedor_nome: venda.vendedor_nome || '-',
            vendedor_nome_completo: venda.vendedor_nome_completo || '-',
            tipo_de_pagamento: venda.tipo_de_pagamento || '-',
            desconto: parseFloat(venda.desconto) || 0,
            valor_total: parseFloat(venda.valor_total) || 0,
            valor_a_ser_pago: parseFloat(venda.valor_a_ser_pago) || 0,
            status: venda.status || '-',
            created_at: venda.created_at || '',
            data_pagamento: venda.data_pagamento || '',
            data_faturamento: venda.data_faturamento || '',
            observacoes: venda.observacoes || '',
            codigo_transacao: venda.codigo_transacao || '',
            updated_at: venda.updated_at || ''
        };
    }

    // Aplicar filtros de vendas de forma segura
    function applyFilters() {
        try {
            const filters = {
                numero: ($('#filterNumero').val() || '').toLowerCase(),
                cliente: ($('#filterCliente').val() || '').toLowerCase(),
                vendedor: ($('#filterVendedor').val() || '').toLowerCase(),
                status: $('#filterStatus').val() || '',
                tipoPagamento: $('#filterTipoPagamento').val() || '',
                valorMin: $('#filterValorMin').val() || '',
                valorMax: $('#filterValorMax').val() || '',
                dataInicial: $('#filterDataInicial').val() || '',
                dataFinal: $('#filterDataFinal').val() || ''
            };

            STATE.filteredVendas = STATE.vendasData.filter(venda => {
                return matchesFilters(venda, filters);
            });

            STATE.currentPage = 1;
            renderTable();
        } catch (error) {
            console.error('Erro ao aplicar filtros:', error);
        }
    }

    // Verificar se venda atende aos filtros
    function matchesFilters(venda, filters) {
        try {
            // Filtro de número da venda
            if (filters.numero && !String(venda.numero_da_venda).toLowerCase().includes(filters.numero)) {
                return false;
            }

            // Filtro de cliente
            if (filters.cliente && !String(venda.cliente_nome).toLowerCase().includes(filters.cliente)) {
                return false;
            }

            // Filtro de vendedor
            if (filters.vendedor && !String(venda.vendedor_nome).toLowerCase().includes(filters.vendedor)) {
                return false;
            }

            // Filtro de status
            if (filters.status && !String(venda.status).includes(filters.status)) {
                return false;
            }

            // Filtro de tipo de pagamento
            if (filters.tipoPagamento && !String(venda.tipo_de_pagamento).includes(filters.tipoPagamento)) {
                return false;
            }

            // Filtro de valor mínimo
            if (filters.valorMin) {
                const valorMin = parseFloat(filters.valorMin.replace(',', '.')) || 0;
                if (venda.valor_total < valorMin) {
                    return false;
                }
            }

            // Filtro de valor máximo
            if (filters.valorMax) {
                const valorMax = parseFloat(filters.valorMax.replace(',', '.')) || 0;
                if (venda.valor_total > valorMax) {
                    return false;
                }
            }

            // Filtro de período
            if (filters.dataInicial || filters.dataFinal) {
                const dataVenda = new Date(venda.created_at);
                if (filters.dataInicial) {
                    const dataInicial = new Date(filters.dataInicial);
                    if (dataVenda < dataInicial) return false;
                }
                if (filters.dataFinal) {
                    const dataFinal = new Date(filters.dataFinal);
                    if (dataVenda > dataFinal) return false;
                }
            }

            return true;
        } catch (error) {
            console.error('Erro ao verificar filtros:', error);
            return true; // Em caso de erro, incluir o item
        }
    }

    // Limpar filtros de vendas
    function clearFilters() {
        try {
            $('#filterNumero').val('');
            $('#filterCliente').val('');
            $('#filterVendedor').val('');
            $('#filterStatus').val('');
            $('#filterTipoPagamento').val('');
            $('#filterValorMin').val('');
            $('#filterValorMax').val('');
            $('#filterDataInicial').val(<?= json_encode(date('Y-m-d')) ?>);
            $('#filterDataFinal').val(<?= json_encode(date('Y-m-d')) ?>);
            
            STATE.filteredVendas = [...STATE.vendasData];
            STATE.currentPage = 1;
            renderTable();
        } catch (error) {
            console.error('Erro ao limpar filtros:', error);
        }
    }

    // Renderizar tabela de vendas de forma segura
    function renderTable() {
        try {
            const startIndex = (STATE.currentPage - 1) * STATE.itemsPerPage;
            const endIndex = startIndex + STATE.itemsPerPage;
            const pageData = STATE.filteredVendas.slice(startIndex, endIndex);

            const $tbody = $('#vendasTableBody');
            $tbody.empty();

            if (pageData.length === 0) {
                $tbody.html(`
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="fa-solid fa-inbox me-2"></i>
                            Nenhuma venda encontrada
                        </td>
                    </tr>
                `);
            } else {
                pageData.forEach(venda => {
                    const row = createTableRow(venda);
                    $tbody.append(row);
                });
            }

            updatePaginationInfo();
            renderPagination();
        } catch (error) {
            console.error('Erro ao renderizar tabela:', error);
            showAlert('error', 'Erro ao exibir dados da tabela');
        }
    }

    // Criar linha da tabela de vendas de forma segura
    function createTableRow(venda) {
        try {
            const id = escapeHtml(venda.id);
            const numero = escapeHtml(venda.numero_da_venda);
            const cliente = escapeHtml(venda.cliente_nome);
            const vendedor = escapeHtml(venda.vendedor_nome);
            const tipoPagamento = escapeHtml(venda.tipo_de_pagamento);
            const valorTotal = formatMoney(venda.valor_total);
            const valorPago = formatMoney(venda.valor_a_ser_pago);
            const status = escapeHtml(venda.status);
            const dataCriacao = venda.created_at ? formatDate(venda.created_at) : '-';

            return `
                <tr>
                    <td>${numero}</td>
                    <td>${cliente}</td>
                    <td>${vendedor}</td>
                    <td>${tipoPagamento}</td>
                    <td><span class="badge bg-${getStatusColor(venda.status)}">${status}</span></td>
                    <td class="text-end">R$ ${valorTotal}</td>
                    <td class="text-end">R$ ${valorPago}</td>
                    <td>${dataCriacao}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary btn-action" onclick="viewVenda(${id})" title="Visualizar">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    ${venda.status === 'Em Aberto' ? `
                                    <button type="button" class="btn btn-info btn-action" onclick="openOperacoesModal(${id})" title="Operações">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </button>
                                    ` : ''}
                                    <button type="button" class="btn btn-secondary btn-action" onclick="printCupom(${id})" title="Imprimir">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                    ${venda.status === 'Em Aberto' ? `
                                    <button type="button" class="btn btn-success btn-action" onclick="openFaturarModal(${id})" data-bs-toggle="modal" data-bs-target="#faturarModal" data-venda-id="${id}" title="Faturar">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-action" onclick="editVenda(${id})" title="Editar">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    ` : ''}
                                    ${venda.status === 'Em Aberto' ? `
                                    <button type="button" class="btn btn-danger btn-action" onclick="deleteVenda(${id}, '${escapeForJs(numero)}')" title="Excluir">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    ` : ''}
                                </div>
                            </td>
                </tr>
            `;
        } catch (error) {
            console.error('Erro ao criar linha da tabela:', error);
            return '<tr><td colspan="9" class="text-danger">Erro ao exibir dados</td></tr>';
        }
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
        try {
            const total = STATE.filteredVendas.length;
            if (total === 0) {
                $('#paginationInfo').text('Mostrando 0 a 0 de 0 registros');
                return;
            }
            const start = Math.min((STATE.currentPage - 1) * STATE.itemsPerPage + 1, total);
            const end = Math.min(STATE.currentPage * STATE.itemsPerPage, total);

            $('#paginationInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);
        } catch (error) {
            console.error('Erro ao atualizar info da paginação:', error);
        }
    }

    // Renderizar paginação
    function renderPagination() {
        try {
            const totalPages = Math.ceil(STATE.filteredVendas.length / STATE.itemsPerPage);
            const $pagination = $('#pagination');
            $pagination.empty();

            if (totalPages <= 1) return;

            // Botão anterior
            const prevDisabled = STATE.currentPage === 1 ? 'disabled' : '';
            $pagination.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${STATE.currentPage - 1}); return false;">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            `);

            // Páginas
            const startPage = Math.max(1, STATE.currentPage - 2);
            const endPage = Math.min(totalPages, STATE.currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                const active = i === STATE.currentPage ? 'active' : '';
                $pagination.append(`
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>
                `);
            }

            // Botão próximo
            const nextDisabled = STATE.currentPage === totalPages ? 'disabled' : '';
            $pagination.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${STATE.currentPage + 1}); return false;">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            `);
        } catch (error) {
            console.error('Erro ao renderizar paginação:', error);
        }
    }

    // Mudar página (função global necessária para onclick)
    window.changePage = function(page) {
        try {
            const totalPages = Math.ceil(STATE.filteredVendas.length / STATE.itemsPerPage);
            if (page >= 1 && page <= totalPages) {
                STATE.currentPage = page;
                renderTable();
            }
        } catch (error) {
            console.error('Erro ao mudar página:', error);
        }
        return false;
    };

    // Funções utilitárias de formatação e escape
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return String(unsafe || '');
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function escapeForJs(str) {
        if (typeof str !== 'string') return String(str || '');
        return str.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    function formatMoney(value) {
        try {
            const numValue = parseFloat(value) || 0;
            return numValue.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } catch (error) {
            console.error('Erro ao formatar dinheiro:', error);
            return '0,00';
        }
    }

    function parseMoney(value) {
        try {
            if (!value || value === '0,00') return 0;
            const cleanValue = value.toString().replace(/\./g, '').replace(',', '.');
            const result = parseFloat(cleanValue) || 0;
            return isNaN(result) ? 0 : result;
        } catch (error) {
            console.error('Erro ao parsear dinheiro:', error);
            return 0;
        }
    }

    // Controla campos dependentes do tipo de pagamento (PIX, A Prazo, etc.)
    function handleTipoPagamentoFields(context, tipo) {
        try {
            // context: 'add' or 'edit'
            const prefix = context === 'edit' ? '#edit-venda-' : '#add-venda-';
            const codigoSel = $(prefix + 'codigo-transacao');
            const dataPagamentoSel = $(prefix + 'data-pagamento');

            // Normalizar valor
            const t = (tipo || '').toString().toLowerCase();

            // PIX requires código da transação
            if (t === 'pix') {
                codigoSel.prop('disabled', false).closest('.col-md-4').find('.invalid-feedback').show();
                codigoSel.prop('required', true);
            } else {
                codigoSel.prop('disabled', true).prop('required', false).val('');
                codigoSel.closest('.col-md-4').find('.invalid-feedback').hide();
            }

            // 'A Prazo' habilita a data de pagamento
            if (t === 'a_prazo' || t === 'a prazo' || t === 'aprazo') {
                dataPagamentoSel.prop('disabled', false).closest('div').find('small').show();
                dataPagamentoSel.prop('required', true);
            } else {
                dataPagamentoSel.prop('disabled', true).prop('required', false).val('');
                // keep explanatory small visible but ensure consistent placement
                dataPagamentoSel.closest('div').find('small').show();
            }
        } catch (error) {
            console.error('Erro em handleTipoPagamentoFields:', error);
        }
    }

    function formatDate(dateString) {
        try {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        } catch (error) {
            console.error('Erro ao formatar data:', error);
            return '-';
        }
    }

    function formatDateTime(dateString) {
        try {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (error) {
            console.error('Erro ao formatar data/hora:', error);
            return '-';
        }
    }

    function getStatusColor(status) {
        const colors = {
            'Em Aberto': 'warning',
            'Faturado': 'success',
            'Atrasado': 'danger',
            'Cancelado': 'secondary',
            'Aguardando': 'info',
            'Em Andamento': 'primary',
            'Aguardando Peças': 'warning',
            'Concluído': 'success',
            'Entregue': 'info'
        };
        return colors[status] || 'secondary';
    }

    function getServicoStatusColor(status) {
        const colors = {
            'Pendente': 'secondary',
            'Executando': 'primary',
            'Concluído': 'success',
            'Cancelado': 'danger'
        };
        return colors[status] || 'secondary';
    }

    function showAlert(type, message) {
        try {
            // Remover alertas existentes
            $('.alert').remove();

            // Criar novo alerta
            const alertClass = type === 'error' ? 'danger' : type;
            const title = type === 'error' ? 'Erro!' : type === 'warning' ? 'Atenção!' : 'Sucesso!';
            
            const $alertDiv = $(`
                <div class="alert alert-${alertClass} alert-dismissible fade show">
                    <strong>${title}</strong> ${escapeHtml(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `).css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                zIndex: '9999',
                minWidth: '300px'
            });

            $('body').append($alertDiv);

            // Remover automaticamente após 5 segundos
            setTimeout(() => {
                $alertDiv.alert('close');
            }, 5000);
        } catch (error) {
            console.error('Erro ao exibir alerta:', error);
        }
    }

    // Expor showAlert para escopo global para que blocos de script fora do IIFE possam usá-la
    try {
        window.showAlert = showAlert;
        window.VendasManager = window.VendasManager || {};
        window.VendasManager.showAlert = showAlert;
    } catch (e) {
        console.warn('Não foi possível expor showAlert globalmente', e);
    }

    // Funções globais necessárias para onclick dos botões da tabela
    window.viewVenda = async function(id) {
        try {
            const response = await $.ajax({
                url: CONFIG.BASE_URL + id,
                method: 'GET',
                dataType: 'json',
                timeout: 10000
            });
            
            if (response) {
                showVendaDetails(response);
            } else {
                throw new Error('Resposta inválida do servidor');
            }
        } catch (error) {
            console.error('Erro ao visualizar venda:', error);
            showAlert('error', 'Erro ao carregar dados da venda');
        }
    };

    window.editVenda = function(id) {
        openEditModal(id);
    };

    window.deleteVenda = function(id, numero) {
        deleteVendaConfirm(id, numero);
    };

    // Mostrar detalhes da venda de forma segura
    function showVendaDetails(data) {
        try {
            const venda = data.venda || data;
            const produtos = data.produtos || [];
            const servicos = data.servicos || [];
            const totais = data.totais || {};

            let html = buildVendaDetailsHTML(venda, produtos, servicos, totais);

            $('#vendaModalBody').html(html);
            $('#editVendaBtn').data('venda-id', venda.id);
            // Mostrar/ocultar botão de editar com base no status da venda
            try {
                if (venda.status === 'Em Aberto') {
                    $('#editVendaBtn').show();
                } else {
                    $('#editVendaBtn').hide();
                }
            } catch (e) { /* ignore */ }

            const viewEl = document.getElementById('vendaModal');
            if (viewEl) {
                try {
                    new bootstrap.Modal(viewEl).show();
                } catch (e) {
                    bootstrap.Modal.getOrCreateInstance(viewEl).show();
                }
            }
        } catch (error) {
            console.error('Erro ao mostrar detalhes da venda:', error);
            showAlert('error', 'Erro ao exibir detalhes da venda');
        }
    }

    // Construir HTML dos detalhes da venda
    function buildVendaDetailsHTML(venda, produtos, servicos, totais) {
        let html = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary mb-0">
                            <i class="fa-solid fa-shopping-cart me-2"></i>
                            Venda #${escapeHtml(venda.numero_da_venda)}
                        </h5>
                        <span class="badge bg-${getStatusColor(venda.status)}">${escapeHtml(venda.status)}</span>
                    </div>
                    <hr>
                </div>

                <div class="col-12">
                    <h6 class="text-info"><i class="fa-solid fa-info-circle me-2"></i>Informações Gerais</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Data da Venda:</strong><br>${formatDateTime(venda.created_at)}</div>
                        <div class="col-md-6"><strong>Tipo de Pagamento:</strong><br>${escapeHtml(venda.tipo_de_pagamento)}</div>
                        <div class="col-md-6"><strong>Data de Pagamento:</strong><br>${venda.data_pagamento ? formatDate(venda.data_pagamento) : '-'}</div>
                        <div class="col-md-6"><strong>Data de Faturamento:</strong><br>${venda.data_faturamento ? formatDate(venda.data_faturamento) : '-'}</div>
                        <div class="col-md-6"><strong>Código da Transação:</strong><br>${escapeHtml(venda.codigo_transacao) || '-'}</div>
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="text-success"><i class="fa-solid fa-user me-2"></i>Dados do Cliente</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Nome:</strong><br>${escapeHtml(venda.cliente_nome)}</div>
                        <div class="col-md-6"><strong>Vendedor:</strong><br>${escapeHtml(venda.vendedor_nome)}</div>
                    </div>
                </div>`;

        // Produtos
        if (produtos.length > 0) {
            html += buildProdutosHTML(produtos);
        }

        // Serviços
        if (servicos.length > 0) {
            html += buildServicosHTML(servicos);
        }

        // Totais
        html += buildTotaisHTML(totais);

        html += `
                <div class="col-12">
                    <h6 class="text-secondary"><i class="fa-solid fa-comment me-2"></i>Observações</h6>
                    <p class="mb-0">${escapeHtml(venda.observacoes) || 'Nenhuma observação'}</p>
                </div>
            </div>`;

        return html;
    }

    function buildProdutosHTML(produtos) {
        let html = `
            <div class="col-12">
                <h6 class="text-success"><i class="fa-solid fa-box me-2"></i>Produtos Utilizados (${produtos.length})</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Produto</th>
                                <th>Código</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-end">Valor Unit.</th>
                                <th class="text-end">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>`;

        produtos.forEach(produto => {
            html += `
                <tr>
                    <td>${escapeHtml(produto.produto_nome)}</td>
                    <td>${escapeHtml(produto.produto_codigo) || '-'}</td>
                    <td class="text-center">${escapeHtml(produto.p3_quantidade)}</td>
                    <td class="text-end">R$ ${formatMoney(produto.p3_valor_unitario)}</td>
                    <td class="text-end">R$ ${formatMoney(produto.p3_valor_total)}</td>
                </tr>`;
        });

        html += `
                        </tbody>
                    </table>
                </div>
            </div>`;

        return html;
    }

    function buildServicosHTML(servicos) {
        let html = `
            <div class="col-12">
                <h6 class="text-info"><i class="fa-solid fa-wrench me-2"></i>Serviços Executados (${servicos.length})</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Serviço</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-end">Valor Unit.</th>
                                <th class="text-end">Valor Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>`;

        servicos.forEach(servico => {
            html += `
                <tr>
                    <td>${escapeHtml(servico.servico_nome)}</td>
                    <td class="text-center">${escapeHtml(servico.s2_quantidade)}</td>
                    <td class="text-end">R$ ${formatMoney(servico.s2_valor_unitario)}</td>
                    <td class="text-end">R$ ${formatMoney(servico.s2_valor_total)}</td>
                    <td class="text-center"><span class="badge bg-${getServicoStatusColor(servico.s2_status)}">${escapeHtml(servico.s2_status)}</span></td>
                </tr>`;
        });

        html += `
                        </tbody>
                    </table>
                </div>
            </div>`;

        return html;
    }

    function buildTotaisHTML(totais) {
        return `
            <div class="col-12">
                <h6 class="text-primary"><i class="fa-solid fa-calculator me-2"></i>Resumo Financeiro</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Valor Produtos:</strong></span>
                                    <span class="text-success">R$ ${formatMoney(totais.valor_produtos)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Valor Serviços:</strong></span>
                                    <span class="text-info">R$ ${formatMoney(totais.valor_servicos)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-warning">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Valor Total:</strong></span>
                                    <span class="text-warning">R$ ${formatMoney(totais.valor_total)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Desconto:</strong></span>
                                    <span class="text-danger">R$ ${formatMoney(totais.desconto)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0"><strong>Valor Final:</strong></h5>
                                    <h5 class="mb-0 text-primary">R$ ${formatMoney(totais.valor_final)}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // Abrir modal de edição de forma segura
    async function openEditModal(id) {
        try {
            console.log('openEditModal called for id:', id);
            const response = await $.ajax({
                url: CONFIG.BASE_URL + id,
                method: 'GET',
                dataType: 'json',
                timeout: 10000
            });
            console.log('openEditModal: response received', response);
            if (response && response.venda) {
                console.log('openEditModal: filling form for venda id', response.venda.id);
                fillEditForm(response.venda);
                
                // Reinitializar Select2 para os selects do modal de edição
                initializeEditModalSelect2();
                console.log('openEditModal: initialized Select2 for edit modal');
                
                const editEl = document.getElementById('editVendaModal');
                if (editEl) {
                    console.log('openEditModal: found edit modal element', editEl);
                    // Mover modal para o body para evitar possíveis problemas de z-index/backdrop
                    if (editEl.parentElement !== document.body) {
                        document.body.appendChild(editEl);
                        console.log('openEditModal: moved edit modal to document.body');
                    }

                    try {
                        // Tentar API moderna do Bootstrap
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(editEl) || new bootstrap.Modal(editEl);
                        modalInstance.show();
                    } catch (e) {
                        console.warn('openEditModal: falha ao usar bootstrap.Modal API, tentando fallback', e);
                        try {
                            // Fallback manual: adicionar classes para exibir modal e backdrop
                            editEl.classList.add('show');
                            editEl.style.display = 'block';
                            editEl.removeAttribute('aria-hidden');
                            editEl.setAttribute('aria-modal', 'true');

                            // garantir que o body esteja marcado como modal-open para evitar scroll/overlay issues
                            if (!document.body.classList.contains('modal-open')) {
                                document.body.classList.add('modal-open');
                                // prevenir scroll
                                document.body.style.overflow = 'hidden';
                            }

                            // criar backdrop se necessário
                            let backdrop = document.querySelector('.modal-backdrop');
                            if (!backdrop) {
                                backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                document.body.appendChild(backdrop);
                            } else {
                                backdrop.classList.add('show');
                            }

                            // tentar focar o primeiro elemento focável dentro do modal
                            const focusable = editEl.querySelector('button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])');
                            if (focusable) {
                                try { focusable.focus(); } catch (fErr) { /* ignore */ }
                            }
                        } catch (manualErr) {
                            console.error('openEditModal: fallback de exibição do modal falhou', manualErr);
                        }
                    }
                } else {
                    throw new Error('Elemento #editVendaModal não encontrado no DOM');
                }
            } else {
                throw new Error('Dados da venda não encontrados');
            }
        } catch (error) {
            console.error('Erro ao abrir modal de edição:', error);
            showAlert('error', 'Erro ao carregar dados da venda');
        }
    }

    // Inicializar Select2 para modal de edição
    function initializeEditModalSelect2() {
        try {
            if ($('#edit-venda-cliente-id').length && !$('#edit-venda-cliente-id').hasClass('select2-hidden-accessible')) {
                $('#edit-venda-cliente-id').select2({
                    placeholder: 'Selecione um cliente...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editVendaModal')
                });
            }

            if ($('#edit-venda-vendedor-id').length && !$('#edit-venda-vendedor-id').hasClass('select2-hidden-accessible')) {
                $('#edit-venda-vendedor-id').select2({
                    placeholder: 'Selecione um vendedor...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editVendaModal')
                });
            }
        } catch (error) {
            console.warn('Erro ao inicializar Select2 para edição:', error);
        }
    }

    // Preencher formulário de edição
    function fillEditForm(venda) {
        try {
            $('#edit-venda-id').val(venda.id || '');
            $('#edit-venda-cliente-id').val(venda.cliente_id || '').trigger('change');
            $('#edit-venda-vendedor-id').val(venda.vendedor_id || '').trigger('change');
            $('#edit-venda-tipo-pagamento').val(venda.tipo_de_pagamento || '');
            $('#edit-venda-valor-total').val(formatMoney(venda.valor_total || 0));
            $('#edit-venda-desconto').val(formatMoney(venda.desconto || 0));
            $('#edit-venda-codigo-transacao').val(venda.codigo_transacao || '');
            $('#edit-venda-status').val(venda.status || '');
            $('#edit-venda-data-pagamento').val(venda.data_pagamento ? venda.data_pagamento.split(' ')[0] : '');
            $('#edit-venda-data-faturamento').val(venda.data_faturamento ? venda.data_faturamento.split(' ')[0] : '');
            $('#edit-venda-observacoes').val(venda.observacoes || '');

            // Controlar estado dos campos baseado no tipo de pagamento
            handleTipoPagamentoFields('edit', venda.tipo_de_pagamento);
        } catch (error) {
            console.error('Erro ao preencher formulário de edição:', error);
        }
    }

    // Salvar nova venda
    async function saveVenda() {
        try {
            const $form = $('#addVendaForm');
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            clearFieldErrors('add');

            const formData = $form.serializeArray();
            const data = {};
            $.each(formData, function(i, field) {
                data[field.name] = field.value;
            });

            // Converter valores monetários
            data.valor_total = parseMoney(data.valor_total) || 0;
            data.desconto = parseMoney(data.desconto) || 0;

            // Normalizar BASE_URL para não terminar com '/' para evitar RewriteRule que redireciona URLs com trailing slash
            const baseNoSlash = (CONFIG.BASE_URL || '').replace(/\/$/, '');
            const postUrl = baseNoSlash || CONFIG.BASE_URL;
            const response = await $.ajax({
                url: postUrl,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                timeout: 15000
            });

            if (response) {
                showAlert('success', 'Venda cadastrada com sucesso!');
                    // Fechar modal de abertura
                    closeModal('addVendaModal');
                    $form.removeClass('was-validated');
                    $form[0].reset();
                    $('#add-venda-cliente-id').val('').trigger('change');
                    $('#add-venda-vendedor-id').val('').trigger('change');

                    // Abrir modal de operações imediatamente para a venda recém-criada
                    try {
                        // Extrair id com maior tolerância a formatos diferentes
                        const vendaId = response.venda_id || response.id || (response.venda && response.venda.id) || null;

                        if (vendaId) {
                            // preencher campo oculto utilizado pelo modal de operações
                            $('#operacoes-venda-id').val(vendaId);

                            console.log('Tentando abrir modal de operações para venda:', vendaId);

                            // Preferir chamar o gerenciador exposto (mais resiliente)
                            try {
                                if (window.VendasManager && typeof window.VendasManager.openOperacoesModal === 'function') {
                                    const p = window.VendasManager.openOperacoesModal(vendaId, response.venda || null);
                                    if (p && typeof p.catch === 'function') p.catch(err => console.error('openOperacoesModal error:', err));
                                } else if (typeof openOperacoesModal === 'function') {
                                    const p = openOperacoesModal(vendaId, response.venda || null);
                                    if (p && typeof p.catch === 'function') p.catch(err => console.error('openOperacoesModal error:', err));
                                } else {
                                    const el = document.getElementById('operacoesModal');
                                    if (el) {
                                        try { new bootstrap.Modal(el).show(); } catch (e) { try { bootstrap.Modal.getOrCreateInstance(el).show(); } catch(_){console.warn('Não foi possível abrir o modal #operacoesModal');} }
                                    } else {
                                        console.warn('Elemento #operacoesModal não encontrado no DOM');
                                    }
                                }
                            } catch (invokeErr) {
                                console.error('Erro ao invocar openOperacoesModal:', invokeErr);
                            }
                        } else {
                            console.warn('saveVenda: vendaId não encontrado no response', response);
                        }
                    } catch (err) {
                        console.error('Erro ao abrir modal de operações após criar venda:', err);
                    }

                    // Recarregar lista de vendas em background (não bloquear abertura do modal)
                    loadVendas().catch(err => console.warn('loadVendas falhou (ignorando):', err));
            } else {
                throw new Error('Resposta inválida do servidor');
            }
        } catch (error) {
            try {
                console.error('Erro ao salvar venda:', error);
                // jQuery AJAX error object may contain xhr details
                if (error && error.status !== undefined) {
                    console.error('AJAX status:', error.status);
                }
                if (error && error.responseText) {
                    console.error('AJAX responseText:', error.responseText);
                }
                if (error && error.responseJSON) {
                    console.error('AJAX responseJSON:', error.responseJSON);
                }
                if (error && error.message) {
                    console.error('Error message:', error.message);
                }
            } catch (logErr) {
                console.error('Erro ao logar erro de saveVenda:', logErr);
            }

            if (error && error.responseJSON && error.responseJSON.messages) {
                showFieldErrors(error.responseJSON.messages, 'add');
            } else if (error && error.responseJSON && error.responseJSON.message) {
                showAlert('error', error.responseJSON.message);
            } else {
                showAlert('error', 'Erro ao cadastrar venda (verifique console para detalhes)');
            }
        }
    }

    // Atualizar venda existente
    async function updateVenda() {
        try {
            const $form = $('#editVendaForm');
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            clearFieldErrors('edit');

            const formData = $form.serializeArray();
            const data = {};
            $.each(formData, function(i, field) {
                data[field.name] = field.value;
            });

            const vendaId = data.id;
            if (!vendaId) {
                throw new Error('ID da venda não encontrado');
            }

            // Converter valores monetários
            data.valor_total = parseMoney(data.valor_total) || 0;
            data.desconto = parseMoney(data.desconto) || 0;

            const response = await $.ajax({
                url: CONFIG.BASE_URL + vendaId,
                method: 'POST',
                headers: { 'X-HTTP-Method-Override': 'PUT' },
                contentType: 'application/json',
                data: JSON.stringify(data),
                timeout: 15000
            });

            if (response) {
                showAlert('success', 'Venda atualizada com sucesso!');
                closeModal('editVendaModal');
                $form.removeClass('was-validated');
                await loadVendas();
            } else {
                throw new Error('Resposta inválida do servidor');
            }
        } catch (error) {
            try {
                console.error('Erro ao atualizar venda:', error);
                if (error && error.status !== undefined) {
                    console.error('AJAX status:', error.status);
                }
                if (error && error.responseText) {
                    console.error('AJAX responseText:', error.responseText);
                }
                if (error && error.responseJSON) {
                    console.error('AJAX responseJSON:', error.responseJSON);
                }
                if (error && error.message) {
                    console.error('Error message:', error.message);
                }
            } catch (logErr) {
                console.error('Erro ao logar erro de updateVenda:', logErr);
            }

            if (error && error.responseJSON && error.responseJSON.messages) {
                showFieldErrors(error.responseJSON.messages, 'edit');
            } else if (error && error.responseJSON && error.responseJSON.message) {
                showAlert('error', error.responseJSON.message);
            } else {
                showAlert('error', 'Erro ao atualizar venda (verifique console para detalhes)');
            }
        }
    }

    // Limpar mensagens de erro de campos
    function clearFieldErrors(type) {
        try {
            const formSelector = type === 'add' ? '#addVendaForm' : '#editVendaForm';
            $(formSelector).find('.is-invalid').removeClass('is-invalid');
            $(formSelector).find('[id$="-error"]').text('').hide();
        } catch (error) {
            console.error('Erro ao limpar erros de campo:', error);
        }
    }

    // Exibir mensagens de erro retornadas pelo servidor
    function showFieldErrors(messages, type) {
        try {
            const mapping = {
                'cliente_id': {
                    add: '#add-venda-cliente-id',
                    edit: '#edit-venda-cliente-id',
                    errorAdd: '#add-venda-cliente-id-error',
                    errorEdit: '#edit-venda-cliente-id-error'
                },
                'vendedor_id': {
                    add: '#add-venda-vendedor-id',
                    edit: '#edit-venda-vendedor-id',
                    errorAdd: '#add-venda-vendedor-id-error',
                    errorEdit: '#edit-venda-vendedor-id-error'
                },
                'tipo_de_pagamento': {
                    add: '#add-venda-tipo-pagamento',
                    edit: '#edit-venda-tipo-pagamento',
                    errorAdd: '#add-venda-tipo-pagamento-error',
                    errorEdit: '#edit-venda-tipo-pagamento-error'
                },
                'valor_total': {
                    add: '#add-venda-valor-total',
                    edit: '#edit-venda-valor-total',
                    errorAdd: '#add-venda-valor-total-error',
                    errorEdit: '#edit-venda-valor-total-error'
                },
                'codigo_transacao': {
                    add: '#add-venda-codigo-transacao',
                    edit: '#edit-venda-codigo-transacao',
                    errorAdd: '#add-venda-codigo-transacao-error',
                    errorEdit: '#edit-venda-codigo-transacao-error'
                },
                'status': {
                    edit: '#edit-venda-status',
                    errorEdit: '#edit-venda-status-error'
                }
            };

            for (const field in messages) {
                if (!messages.hasOwnProperty(field)) continue;
                const message = messages[field];
                const map = mapping[field];
                if (map) {
                    const selector = type === 'add' ? map.add : map.edit;
                    const errorSelector = type === 'add' ? map.errorAdd : map.errorEdit;
                    if (selector && $(selector).length) {
                        $(selector).addClass('is-invalid');
                    }
                    if (errorSelector && $(errorSelector).length) {
                        $(errorSelector).text(message).show();
                    }
                } else {
                    // fallback: show generic alert
                    showAlert('error', message);
                }
            }
        } catch (error) {
            console.error('Erro ao exibir erros de campo:', error);
        }
    }

    // Excluir venda com confirmação
    function deleteVendaConfirm(id, numero) {
        try {
            if (typeof Swal === 'undefined') {
                if (confirm('Tem certeza que deseja excluir a venda "' + numero + '"? Esta ação não pode ser desfeita.')) {
                    deleteVendaExecute(id);
                }
                return;
            }

            Swal.fire({
                title: 'Tem certeza que deseja excluir a venda "' + escapeHtml(numero) + '"?',
                text: 'Esta ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteVendaExecute(id);
                }
            });
        } catch (error) {
            console.error('Erro ao confirmar exclusão:', error);
            showAlert('error', 'Erro ao processar exclusão');
        }
    }

    // Executar exclusão da venda
    async function deleteVendaExecute(id) {
        try {
            const response = await $.ajax({
                url: CONFIG.BASE_URL + id,
                method: 'DELETE',
                timeout: 10000
            });

            if (response && response.success) {
                showAlert('success', 'Venda excluída com sucesso!');
                await loadVendas();
            } else {
                throw new Error(response.message || 'Erro ao excluir venda');
            }
        } catch (error) {
            console.error('Erro ao excluir venda (tentativa DELETE):', error);
            // Se o servidor não aceitar DELETE, tentar fallback via POST para rota /vendas/{id}/delete
            try {
                const fallback = await $.ajax({
                    url: CONFIG.BASE_URL + id + '/delete',
                    method: 'POST',
                    timeout: 10000
                });

                if (fallback && fallback.success) {
                    showAlert('success', 'Venda excluída com sucesso (fallback POST)!');
                    await loadVendas();
                    return;
                }
            } catch (fbErr) {
                console.error('Fallback POST /vendas/{id}/delete falhou:', fbErr);
            }

            if (error && error.responseJSON && error.responseJSON.message) {
                showAlert('error', error.responseJSON.message);
            } else {
                showAlert('error', 'Erro ao excluir venda');
            }
        }
    }

    // Configurar máscaras de dinheiro (função auxiliar)
    function setupMoneyMasks() {
        try {
            if ($.fn && $.fn.mask) {
                $('.money-mask').mask(CONFIG.MONEY_MASK, { reverse: true });
            }
        } catch (error) {
            console.warn('Erro ao aplicar máscaras:', error);
        }
    }

    // Abrir modal de operações e carregar produtos já presentes na venda
    async function openOperacoesModal(vendaId, vendaData = null) {
        try {
            // Garantir que o elemento exista
            const el = document.getElementById('operacoesModal');
            if (!el) {
                console.warn('openOperacoesModal: elemento #operacoesModal não encontrado');
                return;
            }

            // Preencher campo oculto
            try { $('#operacoes-venda-id').val(vendaId); } catch (e) { /* ignore */ }

            // Mover modal para o body para evitar problemas de z-index/backdrop
            if (el.parentElement !== document.body) {
                document.body.appendChild(el);
            }

            // Inicializações leves para o modal de operações
            try {
                if ($('#produto-select').length && !$('#produto-select').hasClass('select2-hidden-accessible') && $.fn && $.fn.select2) {
                    $('#produto-select').select2({ placeholder: 'Selecione um produto...', width: '100%', dropdownParent: $('#operacoesModal') });
                }
            } catch (e) {
                console.warn('openOperacoesModal: falha ao inicializar select2 do produto', e);
            }

            // Limpar tabelas (estado inicial)
            try { $('#produtosTable tbody').empty(); } catch (e) { /* ignore */ }
            try { $('#produtosTotal').text('0.00'); } catch (e) { /* ignore */ }
            try { $('#operacoesTotal').text('0.00'); } catch (e) { /* ignore */ }

            // Mostrar modal de forma resiliente
            try {
                new bootstrap.Modal(el).show();
            } catch (e) {
                try { bootstrap.Modal.getOrCreateInstance(el).show(); } catch (err) { console.error('openOperacoesModal: erro ao mostrar modal', err); }
            }

            // Carregar produtos já associados à venda e renderizá-los no modal
            try {
                const response = await $.ajax({
                    url: CONFIG.BASE_URL + vendaId,
                    method: 'GET',
                    dataType: 'json',
                    timeout: 10000
                });

                if (response && Array.isArray(response.produtos)) {
                    // Preencher tabela com itens existentes
                    response.produtos.forEach(item => {
                        try {
                            const raw = item.raw || {};
                            const produtoId = raw.p2_produto_id || raw.p1_id || '';
                            const itemId = raw.p2_id || null;
                            const nome = item.produto_nome || raw.p1_nome_produto || '';
                            const quantidade = item.p3_quantidade || raw.p2_quantidade || 0;
                            const valorUnitario = (typeof item.p3_valor_unitario !== 'undefined') ? parseFloat(item.p3_valor_unitario) : parseFloat(raw.p2_valor_unitario || 0);
                            const subtotal = (typeof item.p3_valor_total !== 'undefined') ? parseFloat(item.p3_valor_total) : parseFloat(raw.p2_valor_com_desconto || raw.p2_subtotal || 0);

                            // Renderizar linha como item existente (não será enviado ao salvar)
                            const row = `
                                <tr data-item-id="${itemId}" data-produto-id="${produtoId}" class="existing-item">
                                    <td>${escapeHtml(nome)}</td>
                                    <td class="text-center">${escapeHtml(String(quantidade))}</td>
                                    <td class="text-end">R$ ${formatMoney(valorUnitario)}</td>
                                    <td class="text-end">R$ ${formatMoney(subtotal)}</td>
                                    <td class="text-center">&nbsp;</td>
                                </tr>`;

                            $('#produtosTable tbody').append(row);
                        } catch (errRow) {
                            console.warn('openOperacoesModal: falha ao renderizar item existente', errRow);
                        }
                    });

                    // Atualizar totais considerando itens existentes
                    updateTotais();
                }
            } catch (err) {
                console.warn('openOperacoesModal: não foi possível carregar itens existentes da venda', err);
            }

            // Devolver vendaData para possíveis usos futuros
            return vendaData;
        } catch (error) {
            console.error('Erro em openOperacoesModal:', error);
        }
    }

    // Adicionar produto ao modal de operações
    function addProduto() {
        try {
            const produtoId = $('#produto-select').val();
            const quantidade = parseInt($('#produto-quantidade').val()) || 1;
            
            if (!produtoId) {
                showAlert('warning', 'Selecione um produto');
                return;
            }

            const produtoText = $('#produto-select option:selected').text();
            const preco = parseFloat($('#produto-preco').val().replace(',', '.')) || 0;
            const subtotal = quantidade * preco;

            // Adicionar linha à tabela
            const row = `
                <tr data-produto-id="${produtoId}">
                    <td>${produtoText}</td>
                    <td class="text-center">${quantidade}</td>
                    <td class="text-end">R$ ${formatMoney(preco)}</td>
                    <td class="text-end">R$ ${formatMoney(subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProduto(this)">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;

            $('#produtosTable tbody').append(row);

            // Limpar formulário
            $('#produto-select').val('').trigger('change');
            $('#produto-quantidade').val('1');
            $('#produto-preco').val('0,00');
            $('#produto-subtotal').val('0,00');

            // Atualizar totais
            updateTotais();
        } catch (error) {
            console.error('Erro ao adicionar produto:', error);
            showAlert('error', 'Erro ao adicionar produto');
        }
    }

    // Remover produto da tabela
    function removeProduto(button) {
        try {
            $(button).closest('tr').remove();
            updateTotais();
        } catch (error) {
            console.error('Erro ao remover produto:', error);
        }
    }

    // Atualizar totais do modal de operações
    function updateTotais() {
        try {
            let totalProdutos = 0;

            $('#produtosTable tbody tr').each(function() {
                const subtotalText = $(this).find('td:eq(3)').text().replace('R$ ', '').replace('.', '').replace(',', '.');
                totalProdutos += parseFloat(subtotalText) || 0;
            });

            $('#produtosTotal').text(formatMoney(totalProdutos));
            $('#operacoesTotal').text(formatMoney(totalProdutos));
        } catch (error) {
            console.error('Erro ao atualizar totais:', error);
        }
    }

    // Salvar operações (produtos adicionados à venda)
    async function saveOperacoes() {
        try {
            const vendaId = $('#operacoes-venda-id').val();
            if (!vendaId) {
                showAlert('error', 'ID da venda não encontrado');
                return;
            }

            // Coletar apenas produtos adicionados nesta sessão (linhas sem a classe .existing-item)
            const produtos = [];
            $('#produtosTable tbody tr').not('.existing-item').each(function() {
                const produtoId = $(this).data('produto-id');
                const quantidade = parseInt($(this).find('td:eq(1)').text()) || 1;
                const precoText = $(this).find('td:eq(2)').text().replace('R$ ', '').replace('\.', '').replace(',', '.');
                const preco = parseFloat(precoText) || 0;

                produtos.push({
                    produto_id: produtoId,
                    quantidade: quantidade,
                    preco_unitario: preco
                });
            });

            if (produtos.length === 0) {
                showAlert('warning', 'Adicione pelo menos um produto');
                return;
            }

            const response = await $.ajax({
                url: CONFIG.BASE_URL + vendaId + '/produtos',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ produtos: produtos }),
                timeout: 15000
            });

            if (response && response.success) {
                showAlert('success', 'Produtos salvos na venda com sucesso!');
                closeModal('operacoesModal');
                await loadVendas();
            } else {
                throw new Error(response.message || 'Erro ao salvar produtos');
            }
        } catch (error) {
            console.error('Erro ao salvar operações:', error);
            if (error && error.responseJSON && error.responseJSON.message) {
                showAlert('error', error.responseJSON.message);
            } else {
                showAlert('error', 'Erro ao salvar produtos na venda');
            }
        }
    }

    // Configurar validação de formulários
    function setupFormValidation() {
        try {
            // Bootstrap validation já está configurada nos formulários
            console.log('Form validation setup completed');
        } catch (error) {
            console.warn('Erro ao configurar validação:', error);
        }
    }

    // Configurar máscaras
    function setupMasks() {
        try {
            setupMoneyMasks();
            console.log('Masks setup completed');
        } catch (error) {
            console.warn('Erro ao configurar máscaras:', error);
        }
    }

    // Abrir/Imprimir cupom da venda
    async function printCupom(id) {
        try {
            if (!id) {
                showAlert('error', 'ID da venda inválido para impressão');
                return;
            }

            // Abrir em nova aba a rota que entrega o PDF inline
            const url = CONFIG.CUPOM_URL + id;
            // Abrir janela/aba a partir de ação do usuário evita bloqueio de popup
            window.open(url, '_blank');
        } catch (error) {
            console.error('Erro ao abrir cupom:', error);
            showAlert('error', 'Erro ao abrir cupom da venda');
        }
    }

    // Expose essential functions to global scope for backward compatibility
    window.VendasManager = {
        editVenda: editVenda,
        deleteVenda: deleteVenda,
        openOperacoesModal: openOperacoesModal,
        loadVendas: loadVendas,
        saveVenda: saveVenda,
        updateVenda: updateVenda,
        changePage: changePage,
        addProduto: addProduto,
        saveOperacoes: saveOperacoes,
    updateTotais: updateTotais,
    printCupom: printCupom
    };

    // Alias used by table buttons for vendas (global functions for HTML onclick)
    window.editVenda = editVenda;
    window.deleteVenda = deleteVenda;
    window.changePage = changePage;
    window.removeProduto = removeProduto;
    // Garantir compatibilidade com chamadas inline
    window.openOperacoesModal = openOperacoesModal;
    window.printCupom = printCupom;

    // Inicializar a página quando o script for carregado
    console.log('Vendas Manager initialized successfully');

})(); // Fim do IIFE
</script>

<!-- Additional script: faturar handlers are appended to the IIFE via injection to keep file structure simple -->
<script>
(function(){
    'use strict';

    function openFaturarModal(id) {
        try {
            console.log('openFaturarModal called for id:', id);
            if (!id) return;

            // Preparar campos com valores padrões enquanto carregamos os dados
            $('#faturar-venda-id').val(id);
            $('#faturar-data').val(new Date().toISOString().slice(0,10));
            $('#faturar-observacoes').val('');

            const el = document.getElementById('faturarModal');
            if (!el) {
                console.warn('openFaturarModal: elemento #faturarModal não encontrado');
                return;
            }

            // Função que exibe o modal (mantém fallback já existente)
            const showModal = function() {
                try {
                    // Mover para body para evitar problemas de z-index/backdrop
                    if (el.parentElement !== document.body) {
                        try { document.body.appendChild(el); } catch (moveErr) { console.warn('openFaturarModal: não foi possível mover modal para body', moveErr); }
                    }

                    // Tentar usar API do Bootstrap
                    try {
                        const mi = bootstrap.Modal.getOrCreateInstance(el) || new bootstrap.Modal(el);
                        mi.show();
                        return;
                    } catch (apiErr) {
                        console.warn('openFaturarModal: falha ao usar bootstrap.Modal API, tentando fallback', apiErr);
                    }

                    // Fallback manual
                    el.classList.add('show');
                    el.style.display = 'block';
                    el.removeAttribute('aria-hidden');
                    el.setAttribute('aria-modal', 'true');
                    if (!document.body.classList.contains('modal-open')) {
                        document.body.classList.add('modal-open');
                        document.body.style.overflow = 'hidden';
                    }
                    let backdrop = document.querySelector('.modal-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    } else {
                        backdrop.classList.add('show');
                    }
                    const focusable = el.querySelector('button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])');
                    if (focusable) try { focusable.focus(); } catch(e){}

                } catch (manualErr) {
                    console.error('openFaturarModal: fallback manual falhou', manualErr);
                }
            };

            // Buscar dados da venda para preencher observações/data se existirem
            const BASE_URL = <?= json_encode(rtrim(site_url('vendas'), '/') . '/') ?>;
            $.ajax({
                url: BASE_URL + id,
                method: 'GET',
                dataType: 'json',
                timeout: 8000,
                success: function(resp) {
                    try {
                        if (resp && resp.venda) {
                            const venda = resp.venda;
                            // Preencher observações se houver
                            if (venda.observacoes) {
                                $('#faturar-observacoes').val(venda.observacoes);
                            } else {
                                $('#faturar-observacoes').val('');
                            }
                            // Preencher data de faturamento se existir no registro
                            if (venda.data_faturamento) {
                                // Normalizar para YYYY-MM-DD
                                const dateOnly = venda.data_faturamento.split(' ')[0];
                                $('#faturar-data').val(dateOnly);
                            }
                        }
                    } catch (fillErr) {
                        console.warn('openFaturarModal: falha ao preencher dados da venda', fillErr);
                    } finally {
                        // Mostrar o modal após tentativa de preenchimento
                        showModal();
                    }
                },
                error: function(xhr, status, err) {
                    console.warn('openFaturarModal: não foi possível carregar venda, exibindo modal com valores padrão', status, err);
                    showModal();
                }
            });

        } catch (err) {
            console.error('openFaturarModal error:', err);
            showAlert('error', 'Erro ao abrir modal de faturamento');
        }
    }

    async function submitFaturar() {
        try {
            const vendaId = $('#faturar-venda-id').val();
            const dataFaturamento = $('#faturar-data').val();
            const observacoes = $('#faturar-observacoes').val() || null;

            if (!vendaId) { showAlert('error', 'ID da venda inválido'); return; }
            if (!dataFaturamento) { $('#faturar-data')[0].reportValidity(); return; }

            const payload = { data_faturamento: dataFaturamento, observacoes: observacoes };

            const resp = await $.ajax({
                url: <?= json_encode(rtrim(site_url('vendas'), '/') . '/') ?> + vendaId + '/faturar',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                timeout: 15000
            });

            if (resp && resp.success) {
                // Fechar modal e recarregar lista
                try { const el = document.getElementById('faturarModal'); if (el) { bootstrap.Modal.getInstance(el)?.hide(); } } catch(e){}
                showAlert('success', 'Venda faturada com sucesso');
                // refresh
                try { if (window.VendasManager && typeof window.VendasManager.loadVendas === 'function') window.VendasManager.loadVendas(); else if (typeof loadVendas === 'function') loadVendas(); } catch(e){}
            } else {
                const msg = resp && resp.message ? resp.message : 'Erro ao faturar venda';
                showAlert('error', msg);
            }

        } catch (error) {
            console.error('submitFaturar error:', error);
            showAlert('error', 'Erro ao faturar venda (verifique console)');
        }
    }

    // wire up button
    $(document).off('click.faturar', '#faturarSubmitBtn').on('click.faturar', '#faturarSubmitBtn', function(e){ e.preventDefault(); submitFaturar(); });

    // Expose globally
    window.openFaturarModal = openFaturarModal;
    window.submitFaturar = submitFaturar;
    window.VendasManager = window.VendasManager || {};
    window.VendasManager.openFaturarModal = openFaturarModal;
    window.VendasManager.submitFaturar = submitFaturar;

})();
</script>

<?= $this->endSection() ?>