<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Ordens de Serviço</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todas as ordens de serviço cadastradas no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addOrdemModal">
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
                    <label for="filterNumero" class="form-label">Número Ordem</label>
                    <input type="text" class="form-control" id="filterNumero" placeholder="Ex: OR0001">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterCliente" class="form-label">Cliente</label>
                    <input type="text" class="form-control" id="filterCliente" placeholder="Nome do cliente">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterEquipamento" class="form-label">Equipamento</label>
                    <input type="text" class="form-control" id="filterEquipamento" placeholder="Tipo de equipamento">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos</option>
                        <option value="Aguardando">Aguardando</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Aguardando Peças">Aguardando Peças</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Entregue">Entregue</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterPrioridade" class="form-label">Prioridade</label>
                    <select class="form-select" id="filterPrioridade">
                        <option value="">Todas</option>
                        <option value="Baixa">Baixa</option>
                        <option value="Média">Média</option>
                        <option value="Alta">Alta</option>
                        <option value="Urgente">Urgente</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterMarca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="filterMarca" placeholder="Filtrar por marca">
                </div>

                <!-- Segunda linha -->
                <div class="col-lg-2 col-md-6">
                    <label for="filterModelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="filterModelo" placeholder="Filtrar por modelo">
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

    <!-- Tabela de ordens de serviço -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Ordens de Serviço</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Número Ordem</th>
                            <th>Cliente</th>
                            <th>Equipamento</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Status</th>
                            <th>Prioridade</th>
                            <th class="text-end">Valor Final</th>
                            <th>Data Entrada</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="ordensTableBody">
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
<div class="modal fade" id="garantiaModal" tabindex="-1" aria-labelledby="garantiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="garantiaModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes da Garantia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="garantiaModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editCategoryBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Garantia
                </button>
            </div>
        </div>

            <!-- Modal de Faturamento (ordens) -->
            <div class="modal fade" id="faturarModal" tabindex="-1" aria-labelledby="faturarModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="faturarModalLabel"><i class="fa-solid fa-calculator text-success me-2"></i>Faturar Ordem</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="faturarForm">
                                <input type="hidden" id="faturar-ordem-id" name="id" value="">
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
</div>

<!-- Modal de cadastro de ordem -->
<div class="modal fade" id="addOrdemModal" tabindex="-1" aria-labelledby="addOrdemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrdemModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Abrir Nova Ordem de Serviço
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addOrdemForm" class="needs-validation" novalidate>
                    <?php if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } ?>
                    <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="action" value="add_ordem">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="add-ordem-cliente-id" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-ordem-cliente-id" name="cliente_id" required>
                                <option value="">Selecione um cliente...</option>
                                <?php if (!empty($clientes) && is_array($clientes)): ?>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= esc($c->c2_id) ?>"><?= esc($c->c2_nome) ?> <?= !empty($c->c2_cpf) ? '('.esc($c->c2_cpf).')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="add-ordem-cliente-id-error">Informe o cliente.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-equipamento" class="form-label">Equipamento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-ordem-equipamento" name="equipamento" required>
                            <div class="invalid-feedback" id="add-ordem-equipamento-error">Informe o equipamento.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="add-ordem-marca" name="marca">
                        </div>

                        <div class="col-md-4">
                            <label for="add-ordem-modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="add-ordem-modelo" name="modelo">
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-numero-serie" class="form-label">Número de Série</label>
                            <input type="text" class="form-control" id="add-ordem-numero-serie" name="numero_serie">
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-tecnico" class="form-label">Técnico</label>
                            <select class="form-select" id="add-ordem-tecnico" name="tecnico_id">
                                <option value="">Selecione um técnico...</option>
                                <?php if (!empty($tecnicos) && is_array($tecnicos)): ?>
                                    <?php foreach ($tecnicos as $tecnico): ?>
                                        <option value="<?= esc($tecnico->t1_id) ?>"><?= esc($tecnico->t1_nome) ?> <?= !empty($tecnico->t1_cpf) ? '('.esc($tecnico->t1_cpf).')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="add-ordem-data-entrada" class="form-label">Data Entrada <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="add-ordem-data-entrada" name="data_entrada" required value="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-data-previsao" class="form-label">Data Prevista</label>
                            <input type="date" class="form-control" id="add-ordem-data-previsao" name="data_previsao" value="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="add-ordem-prioridade" class="form-label">Prioridade</label>
                            <select class="form-select" id="add-ordem-prioridade" name="prioridade">
                                <option value="Baixa">Baixa</option>
                                <option value="Média" selected>Média</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="add-ordem-defeito" class="form-label">Defeito Relatado <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="add-ordem-defeito" name="defeito_relatado" rows="3" required></textarea>
                            <div class="invalid-feedback" id="add-ordem-defeito-error">Descreva o defeito relatado.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="add-ordem-observacoes" class="form-label">Observações de Entrada</label>
                            <textarea class="form-control" id="add-ordem-observacoes" name="observacoes_entrada" rows="3"></textarea>
                        </div>
                        <div class="col-md-8">
                            <label for="add-ordem-acessorios" class="form-label">Acessórios</label>
                            <input type="text" class="form-control" id="add-ordem-acessorios" name="acessorios_entrada">
                        </div>

                        <div class="col-md-4">
                            <label for="add-ordem-estado" class="form-label">Estado Aparente</label>
                            <select class="form-select" id="add-ordem-estado" name="estado_aparente">
                                <option value="Novo">Novo</option>
                                <option value="Usado">Usado</option>
                                <option value="Danificado">Danificado</option>
                            </select>
                        </div>
                        <!-- valores e garantia removidos do formulário de abertura de ordem -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveOrdemBtn">
                    <i class="fa-solid fa-save me-1"></i>Abrir Ordem
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Ordem -->
<div class="modal fade" id="editgarantiaModal" tabindex="-1" aria-labelledby="editgarantiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editgarantiaModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Ordem de Serviço
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editGarantiaForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit-garantia-id" name="id">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="edit-ordem-cliente-id" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-ordem-cliente-id" name="cliente_id" required>
                                <option value="">Selecione um cliente...</option>
                                <?php if (!empty($clientes) && is_array($clientes)): ?>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= esc($c->c2_id) ?>"><?= esc($c->c2_nome) ?> <?= !empty($c->c2_cpf) ? '('.esc($c->c2_cpf).')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback" id="edit-ordem-cliente-id-error">Informe o cliente.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-equipamento" class="form-label">Equipamento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-ordem-equipamento" name="equipamento" required>
                            <div class="invalid-feedback" id="edit-ordem-equipamento-error">Informe o equipamento.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="edit-ordem-marca" name="marca">
                        </div>

                        <div class="col-md-4">
                            <label for="edit-ordem-modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="edit-ordem-modelo" name="modelo">
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-numero-serie" class="form-label">Número de Série</label>
                            <input type="text" class="form-control" id="edit-ordem-numero-serie" name="numero_serie">
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-tecnico" class="form-label">Técnico</label>
                            <select class="form-select" id="edit-ordem-tecnico" name="tecnico_id">
                                <option value="">Selecione um técnico...</option>
                                <?php if (!empty($tecnicos) && is_array($tecnicos)): ?>
                                    <?php foreach ($tecnicos as $tecnico): ?>
                                        <option value="<?= esc($tecnico->t1_id) ?>"><?= esc($tecnico->t1_nome) ?> <?= !empty($tecnico->t1_cpf) ? '('.esc($tecnico->t1_cpf).')' : '' ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="edit-ordem-data-entrada" class="form-label">Data Entrada <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit-ordem-data-entrada" name="data_entrada" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-data-previsao" class="form-label">Data Prevista</label>
                            <input type="date" class="form-control" id="edit-ordem-data-previsao" name="data_previsao">
                        </div>
                        <div class="col-md-4">
                            <label for="edit-ordem-prioridade" class="form-label">Prioridade</label>
                            <select class="form-select" id="edit-ordem-prioridade" name="prioridade">
                                <option value="Baixa">Baixa</option>
                                <option value="Média" selected>Média</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="edit-ordem-defeito" class="form-label">Defeito Relatado <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit-ordem-defeito" name="defeito_relatado" rows="3" required></textarea>
                            <div class="invalid-feedback" id="edit-ordem-defeito-error">Descreva o defeito relatado.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="edit-ordem-observacoes" class="form-label">Observações de Entrada</label>
                            <textarea class="form-control" id="edit-ordem-observacoes" name="observacoes_entrada" rows="3"></textarea>
                        </div>
                        <div class="col-md-8">
                            <label for="edit-ordem-acessorios" class="form-label">Acessórios</label>
                            <input type="text" class="form-control" id="edit-ordem-acessorios" name="acessorios_entrada">
                        </div>

                        <div class="col-md-4">
                            <label for="edit-ordem-estado" class="form-label">Estado Aparente</label>
                            <select class="form-select" id="edit-ordem-estado" name="estado_aparente">
                                <option value="Novo">Novo</option>
                                <option value="Usado">Usado</option>
                                <option value="Danificado">Danificado</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="updateGarantiaBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Ordem
                </button>
            </div>
        </div>
    </div>
</div>

            <!-- Modal de Operações (produtos e serviços) -->
            <div class="modal fade" id="operacoesModal" tabindex="-1" aria-labelledby="operacoesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="operacoesModalLabel">
                                <i class="fa-solid fa-boxes-stacked text-info me-2"></i>Adicionar Produtos e Serviços
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="operacoesForm">
                                <input type="hidden" id="operacoes-ordem-id" name="ordem_id" value="">
                                <div class="mb-3">
                                    <small class="text-muted">Adicione produtos e serviços à ordem de serviço.</small>
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

                                <!-- Card de Serviços -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fa-solid fa-tools text-success me-2"></i>Serviços</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Formulário de adição de serviço -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label for="servico-select" class="form-label">Serviço</label>
                                                <select class="form-select form-select-sm" id="servico-select">
                                                    <option value="">Selecione um serviço...</option>
                                                    <?php if (!empty($servicos) && is_array($servicos)): ?>
                                                        <?php foreach ($servicos as $servico): ?>
                                                            <option value="<?= esc($servico->s1_id) ?>" data-preco="<?= esc($servico->s1_valor ?? 0) ?>">
                                                                <?= esc($servico->s1_nome_servico) ?> - R$ <?= number_format($servico->s1_valor ?? 0, 2, ',', '.') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="servico-quantidade" class="form-label">Qtd</label>
                                                <input type="number" class="form-control form-control-sm" id="servico-quantidade" min="1" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="servico-preco" class="form-label">Preço Unit.</label>
                                                <input type="text" class="form-control form-control-sm" id="servico-preco" value="0,00" disabled readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="servico-subtotal" class="form-label">Subtotal</label>
                                                <input type="text" class="form-control form-control-sm" id="servico-subtotal" value="0,00" disabled readonly>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-success btn-sm w-100" id="addServicoBtn">
                                                    <i class="fa-solid fa-plus me-1"></i>Adicionar
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Tabela de serviços adicionados -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="servicosTable">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Serviço</th>
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

                                <!-- Totais -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small>Total Produtos: R$ <span id="produtosTotal">0.00</span></small><br>
                                        <small>Total Serviços: R$ <span id="servicosTotal">0.00</span></small>
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
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<!-- Select2 CSS/JS (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 dark theme overrides scoped to the add order modal -->
<style>
/* Scope to modal so other pages unaffected */
#addOrdemModal .select2-container--default .select2-selection--single {
    background-color: #2b3035; /* dark gray */
    color: #e9ecef; /* light text */
    border: 1px solid #454d55;
    padding: .375rem .75rem;
    border-radius: .375rem;
    height: auto;
}
#addOrdemModal .select2-container--default .select2-selection__rendered {
    color: #e9ecef;
}
#addOrdemModal .select2-container--default .select2-selection__placeholder {
    color: #adb5bd; /* muted */
}
#addOrdemModal .select2-container--default .select2-selection__arrow b {
    border-color: #e9ecef;
}
#addOrdemModal .select2-dropdown {
    background-color: #212529; /* modal darker bg */
    color: #e9ecef;
    border: 1px solid #343a40;
}
#addOrdemModal .select2-results__option {
    padding: .5rem .75rem;
}
#addOrdemModal .select2-results__option--highlighted[aria-selected],
#addOrdemModal .select2-results__option[aria-selected='true'] {
    background-color: #0d6efd; /* bootstrap primary */
    color: #fff;
}
#addOrdemModal .select2-container--open .select2-selection--single {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.15);
}
#addOrdemModal .select2-container .select2-selection__clear {
    color: #adb5bd;
}

/* Select2 dark theme overrides scoped to the edit order modal */
#editgarantiaModal .select2-container--default .select2-selection--single {
    background-color: #2b3035; /* dark gray */
    color: #e9ecef; /* light text */
    border: 1px solid #454d55;
    padding: .375rem .75rem;
    border-radius: .375rem;
    height: auto;
}
#editgarantiaModal .select2-container--default .select2-selection__rendered {
    color: #e9ecef;
}
#editgarantiaModal .select2-container--default .select2-selection__placeholder {
    color: #adb5bd; /* muted */
}
#editgarantiaModal .select2-container--default .select2-selection__arrow b {
    border-color: #e9ecef;
}
#editgarantiaModal .select2-dropdown {
    background-color: #212529; /* modal darker bg */
    color: #e9ecef;
    border: 1px solid #343a40;
}
#editgarantiaModal .select2-results__option {
    padding: .5rem .75rem;
}
#editgarantiaModal .select2-results__option--highlighted[aria-selected],
#editgarantiaModal .select2-results__option[aria-selected='true'] {
    background-color: #0d6efd; /* bootstrap primary */
    color: #fff;
}
#editgarantiaModal .select2-container--open .select2-selection--single {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.15);
}
#editgarantiaModal .select2-container .select2-selection__clear {
    color: #adb5bd;
}

/* Select2 dark theme overrides scoped to the operations modal */
#operacoesModal .select2-container--default .select2-selection--single {
    background-color: #2b3035; /* dark gray */
    color: #e9ecef; /* light text */
    border: 1px solid #454d55;
    padding: .375rem .75rem;
    border-radius: .375rem;
    height: auto;
}
#operacoesModal .select2-container--default .select2-selection__rendered {
    color: #e9ecef;
}
#operacoesModal .select2-container--default .select2-selection__placeholder {
    color: #adb5bd; /* muted */
}
#operacoesModal .select2-container--default .select2-selection__arrow b {
    border-color: #e9ecef;
}
#operacoesModal .select2-dropdown {
    background-color: #212529; /* modal darker bg */
    color: #e9ecef;
    border: 1px solid #343a40;
}
#operacoesModal .select2-results__option {
    padding: .5rem .75rem;
}
#operacoesModal .select2-results__option--highlighted[aria-selected],
#operacoesModal .select2-results__option[aria-selected='true'] {
    background-color: #0d6efd; /* bootstrap primary */
    color: #fff;
}
#operacoesModal .select2-container--open .select2-selection--single {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.15);
}
#operacoesModal .select2-container .select2-selection__clear {
    color: #adb5bd;
}
</style>

<script>
    // Variáveis globais
    let ordensData = [];
    let filteredOrdens = [];
    let currentPage = 1; //PÁGINA ATUAL
    let itemsPerPage = 10; //ITENS POR PÁGINA  

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        // Inicializa Select2 no select de clientes (usa dropdownParent para modal)
        try {
            $('#add-ordem-cliente-id').select2({
                placeholder: 'Selecione um cliente...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addOrdemModal')
            });
        } catch (e) {
            // se Select2 não carregar, ignora
        }

        // Inicializa Select2 no select de técnicos (usa dropdownParent para modal)
        try {
            $('#add-ordem-tecnico').select2({
                placeholder: 'Selecione um técnico...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addOrdemModal')
            });
        } catch (e) {
            // se Select2 não carregar, ignora
        }

        // remove classe de erro quando usuário muda seleção
        $(document).on('change', '#add-ordem-cliente-id', function() {
            $(this).removeClass('is-invalid');
            // também remover erro visual do select2 container se existir
            const $container = $(this).data('select2') ? $(this).next('.select2-container') : $();
            if ($container.length) $container.find('.select2-selection').removeClass('is-invalid');
        });

        // remove classe de erro quando usuário muda seleção do técnico
        $(document).on('change', '#add-ordem-tecnico', function() {
            $(this).removeClass('is-invalid');
            // também remover erro visual do select2 container se existir
            const $container = $(this).data('select2') ? $(this).next('.select2-container') : $();
            if ($container.length) $container.find('.select2-selection').removeClass('is-invalid');
        });

        loadOrdens();
        setupEventListeners();
    });

    // Toggle dos Filtros com jQuery
    function toggleFilters() {
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
    }

    // Configurar event listeners com jQuery
    /**
     * Carrega os dados das categorias via AJAX GET.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    function setupEventListeners() {
        // Filtros: pesquisa
        $('#filterNumero').on('input', applyFilters);
        $('#filterCliente').on('input', applyFilters);
        $('#filterEquipamento').on('input', applyFilters);
        $('#filterStatus').on('change', applyFilters);
        $('#filterPrioridade').on('change', applyFilters);
        $('#filterDataInicial').on('change', applyFilters);
        $('#filterDataFinal').on('change', applyFilters);
        $('#filterMarca').on('input', applyFilters);
        $('#filterModelo').on('input', applyFilters);
        $('#clearFilters').on('click', clearFilters);

        // Paginação
        $('#itemsPerPage').on('change', function() {

            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });

        // Buttons handled via delegated calls to specific functions
        // Bind edit button (quando usado dentro do modal de visualização)
        $('#editCategoryBtn').on('click', function() {
            const id = $(this).data('category-id');
            if (id) {
                // Fecha o modal de visualização caso esteja aberto
                const viewModalEl = document.getElementById('garantiaModal');
                const viewInstance = bootstrap.Modal.getInstance(viewModalEl) || null;
                if (viewInstance) {
                    try { viewInstance.hide(); } catch (e) { /* ignore */ }
                }
                openEditModal(id);
            }
        });
    }

    // (no duplicate handler) - handled inside setupEventListeners

    // Bind action buttons (keep original modal buttons working)
    $(document).on('click', '#saveOrdemBtn', function(e) { e.preventDefault(); saveGarantia(); });
    $(document).on('click', '#updateGarantiaBtn', function(e) { e.preventDefault(); updateGarantia(); });

    // Carregar dados das categorias
    async function loadOrdens() {
        try {
            const response = await $.get('<?= site_url('/ordens/list') ?>');

            if (Array.isArray(response)) {
                ordensData = response.map(o => ({
                    id: o.o1_id,
                    numero: o.o1_numero_ordem,
                    cliente_id: o.o1_cliente_id,
                    cliente_nome: o.cliente_nome,
                    equipamento: o.o1_equipamento,
                    marca: o.o1_marca,
                    modelo: o.o1_modelo,
                    status: o.o1_status,
                    prioridade: o.o1_prioridade,
                    valor_final: o.o1_valor_final,
                    data_entrada: o.o1_data_entrada,
                    defeito: o.o1_defeito_relatado,
                    tecnico_id: o.o1_tecnico_id,
                    tecnico_nome: o.tecnico_nome
                }));

                filteredOrdens = [...ordensData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar ordens');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados das ordens');
        }
    }

    // Aplicar filtros: nome e intervalo de comissão
    function applyFilters() {
        const numeroFilter = ($('#filterNumero').val() || '').toLowerCase();
        const clienteFilter = ($('#filterCliente').val() || '').toLowerCase();
        const equipamentoFilter = ($('#filterEquipamento').val() || '').toLowerCase();
        const statusFilter = $('#filterStatus').val() || '';
        const prioridadeFilter = $('#filterPrioridade').val() || '';
        const dataInicialFilter = $('#filterDataInicial').val() || '';
        const dataFinalFilter = $('#filterDataFinal').val() || '';
        const marcaFilter = ($('#filterMarca').val() || '').toLowerCase();
        const modeloFilter = ($('#filterModelo').val() || '').toLowerCase();

        filteredOrdens = ordensData.filter(o => {
            // Filtro de número da ordem
            const matchesNumero = !numeroFilter || String(o.numero || '').toLowerCase().includes(numeroFilter);

            // Filtro de cliente
            const matchesCliente = !clienteFilter || String(o.cliente_nome || '').toLowerCase().includes(clienteFilter);

            // Filtro de equipamento
            const matchesEquipamento = !equipamentoFilter || String(o.equipamento || '').toLowerCase().includes(equipamentoFilter);

            // Filtro de status
            const matchesStatus = !statusFilter || String(o.status || '').includes(statusFilter);

            // Filtro de prioridade
            const matchesPrioridade = !prioridadeFilter || String(o.prioridade || '').includes(prioridadeFilter);

            // Filtro de período (data inicial e final)
            let matchesPeriodo = true;
            if (dataInicialFilter || dataFinalFilter) {
                const dataOrdem = new Date(o.data_entrada);
                const dataInicial = dataInicialFilter ? new Date(dataInicialFilter) : null;
                const dataFinal = dataFinalFilter ? new Date(dataFinalFilter) : null;

                if (dataInicial && dataOrdem < dataInicial) matchesPeriodo = false;
                if (dataFinal && dataOrdem > dataFinal) matchesPeriodo = false;
            }

            // Filtro de marca
            const matchesMarca = !marcaFilter || String(o.marca || '').toLowerCase().includes(marcaFilter);

            // Filtro de modelo
            const matchesModelo = !modeloFilter || String(o.modelo || '').toLowerCase().includes(modeloFilter);

            return matchesNumero && matchesCliente && matchesEquipamento && matchesStatus &&
                   matchesPrioridade && matchesPeriodo && matchesMarca && matchesModelo;
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterNumero').val('');
        $('#filterCliente').val('');
        $('#filterEquipamento').val('');
        $('#filterStatus').val('');
        $('#filterPrioridade').val('');
        $('#filterDataInicial').val('<?= date('Y-m-d'); ?>');
        $('#filterDataFinal').val('<?= date('Y-m-d'); ?>');
        $('#filterMarca').val('');
        $('#filterModelo').val('');
        filteredOrdens = [...ordensData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
    const pageData = filteredOrdens.slice(startIndex, endIndex);

    const $tbody = $('#ordensTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhuma ordem encontrada
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(ordem => {
                const row = createTableRow(ordem);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    function createTableRow(ordem) {
        const id = ordem.id || '';
        const numero = ordem.numero || '-';
        const cliente = ordem.cliente_nome || '-';
        const equipamento = ordem.equipamento || '-';
        const marca = ordem.marca || '-';
        const modelo = ordem.modelo || '-';
    const status = ordem.status || '-';
    const statusText = ordem.o1_status || ordem.status || '-';
        const prioridade = ordem.prioridade || '-';
        const valor = ordem.valor_final ? Number(ordem.valor_final).toFixed(2) : '0.00';
        const dataEntrada = ordem.data_entrada || '-';
        const isFaturado = String(statusText || '').toLowerCase() === 'faturado';

        const actionsHtml = `
                            <button type="button" class="btn btn-primary btn-action" onclick="viewOrdem(${id})" title="Visualizar">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-secondary btn-action" onclick="printCupomOrdem(${id})" title="Imprimir">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            ${!isFaturado ? `
                            <button type="button" class="btn btn-info btn-action" onclick="openOperacoesModal(${id})" title="Operações">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-action" onclick="openFaturarModal(${id})" data-bs-toggle="modal" data-bs-target="#faturarModal" title="Faturar">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-action" onclick="editOrdem(${id})" title="Editar">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-action" onclick="deleteOrdem(${id}, '${String(numero).replace(/'/g, "\\'")}')" title="Excluir">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            ` : ''}
                        `;

        return `
            <tr>
                <td>${numero}</td>
                <td>${cliente}</td>
                <td>${equipamento}</td>
                <td>${marca}</td>
                <td>${modelo}</td>
                <td><span class="badge bg-${getStatusColor(statusText)}">${statusText}</span></td>
                <td>${prioridade}</td>
                <td class="text-end">${valor}</td>
                <td>${dataEntrada}</td>
                <td>
                    <div class="btn-group" role="group">
                        ${actionsHtml}
                    </div>
                </td>
            </tr>
        `;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
    const total = filteredOrdens.length;
        if (total === 0) {
            $('#paginationInfo').text('Mostrando 0 a 0 de 0 registros');
            return;
        }
        const start = Math.min((currentPage - 1) * itemsPerPage + 1, total);
        const end = Math.min(currentPage * itemsPerPage, total);

        $('#paginationInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);
    }

    // Renderizar paginação
    function renderPagination() {
    const totalPages = Math.ceil(filteredOrdens.length / itemsPerPage);
        const $pagination = $('#pagination');
        $pagination.empty();

        if (totalPages <= 1) return;

        // Botão anterior
        const prevDisabled = currentPage === 1 ? 'disabled' : '';
        $pagination.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </li>
        `);

        // Páginas
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            const active = i === currentPage ? 'active' : '';
            $pagination.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `);
        }

        // Botão próximo
        const nextDisabled = currentPage === totalPages ? 'disabled' : '';
        $pagination.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </li>
        `);
    }

    // Mudar página
    function changePage(page) {
    const totalPages = Math.ceil(filteredOrdens.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar ordem
    async function viewOrdem(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                showOrdemDetails(response);
            } else {
                showAlert('error', 'Erro ao carregar dados da ordem');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da ordem');
        }
    }

    // Mostrar detalhes da ordem
    function showOrdemDetails(data) {
        const ordem = data.ordem;
        const produtos = data.produtos || [];
        const servicos = data.servicos || [];
        const totais = data.totais || {};

        let html = `
            <div class="row g-3">
                <!-- Cabeçalho da Ordem -->
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary mb-0">
                            <i class="fa-solid fa-file-invoice me-2"></i>
                            Ordem de Serviço #${ordem.o1_numero_ordem}
                        </h5>
                        <span class="badge bg-${getStatusColor(ordem.o1_status)}">${ordem.o1_status}</span>
                    </div>
                    <hr>
                </div>

                <!-- Informações Gerais -->
                <div class="col-12">
                    <h6 class="text-info"><i class="fa-solid fa-info-circle me-2"></i>Informações Gerais</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Data de Entrada:</strong><br>${formatDateTime(ordem.o1_data_entrada)}</div>
                        <div class="col-md-6"><strong>Data Prevista:</strong><br>${ordem.o1_data_previsao ? formatDate(ordem.o1_data_previsao) : '-'}</div>
                        <div class="col-md-6"><strong>Data de Conclusão:</strong><br>${ordem.o1_data_conclusao ? formatDateTime(ordem.o1_data_conclusao) : '-'}</div>
                        <div class="col-md-6"><strong>Data de Entrega:</strong><br>${ordem.o1_data_entrega ? formatDateTime(ordem.o1_data_entrega) : '-'}</div>
                        <div class="col-md-6"><strong>Prioridade:</strong><br><span class="badge bg-${getPrioridadeColor(ordem.o1_prioridade)}">${ordem.o1_prioridade}</span></div>
                        <div class="col-md-6"><strong>Estado Aparente:</strong><br>${ordem.o1_estado_aparente}</div>
                    </div>
                </div>

                <!-- Dados do Cliente -->
                <div class="col-12">
                    <h6 class="text-success"><i class="fa-solid fa-user me-2"></i>Dados do Cliente</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Nome:</strong><br>${ordem.cliente_nome}</div>
                        <div class="col-md-6"><strong>CPF:</strong><br>${ordem.cliente_cpf}</div>
                        <div class="col-md-6"><strong>Telefone:</strong><br>${ordem.cliente_telefone}</div>
                        <div class="col-md-6"><strong>Celular:</strong><br>${ordem.cliente_celular}</div>
                        <div class="col-md-12"><strong>E-mail:</strong><br>${ordem.cliente_email || '-'}</div>
                        <div class="col-md-12"><strong>Endereço:</strong><br>${ordem.cliente_endereco || '-'}</div>
                    </div>
                </div>

                <!-- Dados do Equipamento -->
                <div class="col-12">
                    <h6 class="text-warning"><i class="fa-solid fa-tools me-2"></i>Dados do Equipamento</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Equipamento:</strong><br>${ordem.o1_equipamento}</div>
                        <div class="col-md-6"><strong>Marca:</strong><br>${ordem.o1_marca || '-'}</div>
                        <div class="col-md-6"><strong>Modelo:</strong><br>${ordem.o1_modelo || '-'}</div>
                        <div class="col-md-6"><strong>Número de Série:</strong><br>${ordem.o1_numero_serie || '-'}</div>
                    </div>
                </div>

                <!-- Defeito e Observações -->
                <div class="col-12">
                    <h6 class="text-danger"><i class="fa-solid fa-exclamation-triangle me-2"></i>Defeito Relatado</h6>
                    <p class="mb-3">${ordem.o1_defeito_relatado}</p>

                    <h6 class="text-secondary"><i class="fa-solid fa-sticky-note me-2"></i>Observações de Entrada</h6>
                    <p class="mb-3">${ordem.o1_observacoes_entrada || '-'}</p>

                    <h6 class="text-secondary"><i class="fa-solid fa-list me-2"></i>Acessórios de Entrada</h6>
                    <p class="mb-3">${ordem.o1_acessorios_entrada || '-'}</p>
                </div>

                <!-- Dados do Técnico -->
                <div class="col-12">
                    <h6 class="text-primary"><i class="fa-solid fa-user-cog me-2"></i>Dados do Técnico Responsável</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Nome:</strong><br>${ordem.tecnico_nome}</div>
                        <div class="col-md-6"><strong>CPF:</strong><br>${ordem.tecnico_cpf}</div>
                        <div class="col-md-6"><strong>Telefone:</strong><br>${ordem.tecnico_telefone}</div>
                        <div class="col-md-6"><strong>Celular:</strong><br>${ordem.tecnico_celular}</div>
                        <div class="col-md-12"><strong>E-mail:</strong><br>${ordem.tecnico_email || '-'}</div>
                    </div>
                </div>`;

        // Produtos
        if (produtos.length > 0) {
            html += `
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
                        <td>${produto.produto_nome}</td>
                        <td>${produto.produto_codigo || '-'}</td>
                        <td class="text-center">${produto.p3_quantidade}</td>
                        <td class="text-end">R$ ${formatMoney(produto.p3_valor_unitario)}</td>
                        <td class="text-end">R$ ${formatMoney(produto.p3_valor_total)}</td>
                    </tr>`;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>`;
        }

        // Serviços
        if (servicos.length > 0) {
            html += `
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
                        <td>${servico.servico_nome}</td>
                        <td class="text-center">${servico.s2_quantidade}</td>
                        <td class="text-end">R$ ${formatMoney(servico.s2_valor_unitario)}</td>
                        <td class="text-end">R$ ${formatMoney(servico.s2_valor_total)}</td>
                        <td class="text-center"><span class="badge bg-${getServicoStatusColor(servico.s2_status)}">${servico.s2_status}</span></td>
                    </tr>`;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>`;
        }

        // Laudo Técnico e Observações de Conclusão
        if (ordem.o1_laudo_tecnico || ordem.o1_observacoes_conclusao) {
            html += `
                <div class="col-12">
                    <h6 class="text-secondary"><i class="fa-solid fa-clipboard-check me-2"></i>Laudo Técnico</h6>
                    <p class="mb-3">${ordem.o1_laudo_tecnico || '-'}</p>

                    <h6 class="text-secondary"><i class="fa-solid fa-comment me-2"></i>Observações de Conclusão</h6>
                    <p class="mb-3">${ordem.o1_observacoes_conclusao || '-'}</p>
                </div>`;
        }

        // Totais Financeiros
        html += `
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
                </div>

                <!-- Garantia -->
                <div class="col-12">
                    <h6 class="text-secondary"><i class="fa-solid fa-shield-alt me-2"></i>Garantia</h6>
                    <p class="mb-0">${ordem.o1_garantia_servico ? `${ordem.o1_garantia_servico} dias de garantia` : 'Sem garantia'}</p>
                </div>
            </div>`;

        $('#garantiaModalBody').html(html);
        $('#editCategoryBtn').data('category-id', ordem.o1_id);

        const viewEl = document.getElementById('garantiaModal');
        try { new bootstrap.Modal(viewEl).show(); } catch (e) { bootstrap.Modal.getOrCreateInstance(viewEl).show(); }
    }

    // Alias used by table buttons
    function editOrdem(id) { openEditModal(id); }

    // Alias used by table buttons
    function deleteOrdem(id, numero) { deleteOrdemConfirm(id, numero); }

    // Abrir modal de edição (reusa modais existentes mas carrega dados da ordem)
    async function openEditModal(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                const ordem = response.ordem;
                // preencher campos do modal de edição com dados da ordem
                $('#edit-garantia-id').val(ordem.o1_id);
                $('#edit-ordem-cliente-id').val(ordem.o1_cliente_id).trigger('change');
                $('#edit-ordem-equipamento').val(ordem.o1_equipamento || '');
                $('#edit-ordem-marca').val(ordem.o1_marca || '');
                $('#edit-ordem-modelo').val(ordem.o1_modelo || '');
                $('#edit-ordem-numero-serie').val(ordem.o1_numero_serie || '');
                $('#edit-ordem-tecnico').val(ordem.o1_tecnico_id || '').trigger('change');
                $('#edit-ordem-data-entrada').val(ordem.o1_data_entrada ? ordem.o1_data_entrada.split(' ')[0] : '');
                $('#edit-ordem-data-previsao').val(ordem.o1_data_previsao ? ordem.o1_data_previsao.split(' ')[0] : '');
                $('#edit-ordem-prioridade').val(ordem.o1_prioridade || 'Média');
                $('#edit-ordem-defeito').val(ordem.o1_defeito_relatado || '');
                $('#edit-ordem-observacoes').val(ordem.o1_observacoes_entrada || '');
                $('#edit-ordem-acessorios').val(ordem.o1_acessorios_entrada || '');
                $('#edit-ordem-estado').val(ordem.o1_estado_aparente || 'Novo');

                // Inicializar Select2 para os selects do modal de edição
                try {
                    $('#edit-ordem-cliente-id').select2({
                        placeholder: 'Selecione um cliente...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#editgarantiaModal')
                    });
                } catch (e) {
                    // se Select2 não carregar, ignora
                }

                try {
                    $('#edit-ordem-tecnico').select2({
                        placeholder: 'Selecione um técnico...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#editgarantiaModal')
                    });
                } catch (e) {
                    // se Select2 não carregar, ignora
                }

                const editEl = document.getElementById('editgarantiaModal');
                try { new bootstrap.Modal(editEl).show(); } catch (e) { bootstrap.Modal.getOrCreateInstance(editEl).show(); }
            } else {
                showAlert('error', 'Erro ao carregar dados da ordem');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da ordem');
        }
    }

    // Preencher formulário de edição de garantia
    function fillEditForm(garantia) {
        $('#edit-garantia-id').val(garantia.g1_id);
        $('#edit-garantia-nome').val(garantia.g1_nome);
        // Preenche todos os campos disponíveis no formulário de edição
        $('#edit-garantia-descricao').val(garantia.g1_descricao || '');
        $('#edit-garantia-observacao').val(garantia.g1_observacao || '');
        $('#edit-garantia-data').val(garantia.g1_data || '');
        // Convert server datetime ("YYYY-MM-DD HH:MM:SS" or "YYYY-MM-DD HH:MM")
        // into a value accepted by <input type="datetime-local"> ("YYYY-MM-DDTHH:MM").
        let dg = garantia.g1_data_garantia || '';
        if (dg) {
            // replace space with 'T' and strip seconds if present
            dg = String(dg).trim();
            dg = dg.replace(' ', 'T');
            const m = dg.match(/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2})/);
            dg = m ? m[1] : '';
        }
        $('#edit-garantia-data-garantia').val(dg);
    }

    // Salvar nova ordem (envia para /ordens - compatível com OrdemController::create)
    async function saveGarantia() {
        const $form = $('#addOrdemForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }
        // limpar mensagens anteriores
        clearFieldErrors('add');

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        try {
            const response = await $.ajax({
                url: '<?= site_url('/ordens') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

                if (response) {
                    showAlert('success', 'Ordem cadastrada com sucesso!');
                    // hide add modal via Bootstrap API
                    const addEl = document.getElementById('addOrdemModal');
                    try { bootstrap.Modal.getOrCreateInstance(addEl).hide(); } catch (e) { /* ignore */ }
                    $form.removeClass('was-validated');

                    // determine created order id from possible response shapes
                    const createdId = response.o1_id || response.id || response.insertId || response.insertedId || null;

                    // open operations modal immediately to add products/services
                    try {
                        openOperacoesModal(createdId, response);
                    } catch (e) {
                        console.error('Erro ao abrir modal de operações:', e);
                    }

                    // refresh list in background
                    await loadOrdens();
                } else {
                    showAlert('error', response.message || 'Erro ao cadastrar ordem');
                }
        } catch (error) {
            console.error('Erro:', error);
            // tenta ler mensagens de validação
            if (error && error.responseJSON && error.responseJSON.messages) {
                showFieldErrors(error.responseJSON.messages, 'add');
            } else {
                showAlert('error', 'Erro ao cadastrar ordem');
            }
        }
    }

    // Atualizar ordem (reaproveitado para a edição de ordens)
    async function updateGarantia() {
        const $form = $('#editGarantiaForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        // limpar mensagens anteriores
        clearFieldErrors('edit');

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        const ordemId = data.id || data.garantia_id;

        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${ordemId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Ordem atualizada com sucesso!');
                // hide edit modal via Bootstrap API
                const editEl = document.getElementById('editgarantiaModal');
                try {
                    bootstrap.Modal.getOrCreateInstance(editEl).hide();
                } catch (e) {
                    /* ignore */ }
                $form.removeClass('was-validated');
                await loadOrdens();
            } else {
                showAlert('error', response.message || 'Erro ao atualizar ordem');
            }
        } catch (error) {
            console.error('Erro:', error);
            // Try to read validation messages from different possible places
            const respJson = (error && error.responseJSON) ? error.responseJSON : null;
            if (!respJson && error && error.responseText) {
                try {
                    const parsed = JSON.parse(error.responseText);
                    if (parsed && parsed.messages) {
                        showFieldErrors(parsed.messages, 'edit');
                        return;
                    }
                } catch (e) {
                    // ignore parse error
                }
            }

            if (respJson && respJson.messages) {
                showFieldErrors(respJson.messages, 'edit');
            } else {
                showAlert('error', 'Erro ao atualizar ordem');
            }
        }
    }

    // Limpa mensagens de erro de campos em modais (tipo: 'add' ou 'edit')
    function clearFieldErrors(type) {
        if (type === 'add') {
                $('#addOrdemForm').find('.is-invalid').removeClass('is-invalid');
                $('#addOrdemForm').find('[id$="-error"]').text('').hide();
        } else if (type === 'edit') {
        $('#editGarantiaForm').find('.is-invalid').removeClass('is-invalid');
        $('#editGarantiaForm').find('[id$="-error"]').text('').hide();
        }
    }

    // Exibe mensagens de erro retornadas pelo servidor. messages é um objeto { field: message }
    function showFieldErrors(messages, type) {
        const mapping = {
            'g1_nome': {
                add: '#garantia-nome',
                edit: '#edit-garantia-nome',
                errorAdd: '#add-garantia-nome-error',
                errorEdit: '#edit-garantia-nome-error'
            },
            'g1_descricao': {
                add: '#garantia-descricao',
                edit: '#edit-garantia-descricao',
                errorAdd: '#add-garantia-descricao-error',
                errorEdit: '#edit-garantia-descricao-error'
            },
            'g1_observacao': {
                add: '#garantia-observacao',
                edit: '#edit-garantia-observacao',
                errorAdd: '#add-garantia-observacao-error',
                errorEdit: '#edit-garantia-observacao-error'
            },
            'g1_data': {
                add: '#garantia-data',
                edit: '#edit-garantia-data',
                errorAdd: '#add-garantia-data-error',
                errorEdit: '#edit-garantia-data-error'
            },
            'g1_data_garantia': {
                add: '#garantia-data-garantia',
                edit: '#edit-garantia-data-garantia',
                errorAdd: '#add-garantia-data-garantia-error',
                errorEdit: '#edit-garantia-data-garantia-error'
            },
            'nome': {
                add: '#garantia-nome',
                edit: '#edit-garantia-nome',
                errorAdd: '#add-garantia-nome-error',
                errorEdit: '#edit-garantia-nome-error'
            },
            'descricao': {
                add: '#garantia-descricao',
                edit: '#edit-garantia-descricao',
                errorAdd: '#add-garantia-descricao-error',
                errorEdit: '#edit-garantia-descricao-error'
            },
            'observacao': {
                add: '#garantia-observacao',
                edit: '#edit-garantia-observacao',
                errorAdd: '#add-garantia-observacao-error',
                errorEdit: '#edit-garantia-observacao-error'
            },
            'data': {
                add: '#garantia-data',
                edit: '#edit-garantia-data',
                errorAdd: '#add-garantia-data-error',
                errorEdit: '#edit-garantia-data-error'
            },
            'data_garantia': {
                add: '#garantia-data-garantia',
                edit: '#edit-garantia-data-garantia',
                errorAdd: '#add-garantia-data-garantia-error',
                errorEdit: '#edit-garantia-data-garantia-error'
            },
            'cliente_id': {
                add: '#add-ordem-cliente-id',
                edit: '#edit-ordem-cliente-id',
                errorAdd: '#add-ordem-cliente-id-error',
                errorEdit: '#edit-ordem-cliente-id-error'
            },
            'equipamento': {
                add: '#add-ordem-equipamento',
                edit: '#edit-ordem-equipamento',
                errorAdd: '#add-ordem-equipamento-error',
                errorEdit: '#edit-ordem-equipamento-error'
            },
            'defeito_relatado': {
                add: '#add-ordem-defeito',
                edit: '#edit-ordem-defeito',
                errorAdd: '#add-ordem-defeito-error',
                errorEdit: '#edit-ordem-defeito-error'
            },
            'observacoes_entrada': {
                add: '#add-ordem-observacoes',
                edit: '#edit-ordem-observacoes',
                errorAdd: null,
                errorEdit: null
            },
            'valor_final': {
                add: '#add-ordem-valor-final',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'valor_servicos': {
                add: '#add-ordem-valor-servicos',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'valor_produtos': {
                add: '#add-ordem-valor-produtos',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'desconto': {
                add: '#add-ordem-desconto',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'tecnico_id': {
                add: '#add-ordem-tecnico',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'prioridade': {
                add: '#add-ordem-prioridade',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'estado_aparente': {
                add: '#add-ordem-estado',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
            'garantia_servico': {
                add: '#add-ordem-garantia',
                edit: null,
                errorAdd: null,
                errorEdit: null
            },
        };

        for (const field in messages) {
            if (!messages.hasOwnProperty(field)) continue;
            const message = messages[field];
            const map = mapping[field];
            if (map) {
                const selector = type === 'add' ? map.add : map.edit;
                const errorSelector = type === 'add' ? map.errorAdd : map.errorEdit;
                if (selector) $(selector).addClass('is-invalid');
                if (errorSelector) $(errorSelector).text(message).show();
            } else {
                // fallback: show generic alert
                showAlert('error', message);
            }
        }
    }

    // Excluir ordem
    async function deleteOrdemConfirm(id, numero) {
        Swal.fire({
            title: `Tem certeza que deseja excluir a ordem "${numero}"?`,
            text: 'Esta ação não pode ser desfeita. Todos os produtos e serviços relacionados serão removidos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }).then(async (resultSwal) => {
            if (resultSwal.isConfirmed) {
                try {
                    const response = await $.ajax({
                        url: `<?= site_url('/ordens/') ?>${id}`,
                        method: 'DELETE'
                    });

                    if (response && response.success) {
                        showAlert('success', 'Ordem excluída com sucesso!');
                        await loadOrdens();
                    } else {
                        showAlert('error', response.message || 'Erro ao excluir ordem');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    if (error.responseJSON && error.responseJSON.message) {
                        showAlert('error', error.responseJSON.message);
                    } else {
                        showAlert('error', 'Erro ao excluir ordem');
                    }
                }
            }
        });
    }

    // Função para mostrar alertas
    function showAlert(type, message) {
        // Remover alertas existentes
        $('.alert').remove();

        // Criar novo alerta
        const alertClass = type === 'error' ? 'danger' : type;
        const $alertDiv = $(`
            <div class="alert alert-${alertClass} alert-dismissible fade show">
                <strong>${type === 'error' ? 'Erro!' : 'Sucesso!'}</strong> ${message}
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
    }

    // Limpar formulários quando os modais são fechados
    $('#addOrdemModal').on('hidden.bs.modal', function() {
        const $form = $('#addOrdemForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    // reset Select2 selection
    try { $('#add-ordem-cliente-id').val('').trigger('change'); } catch (e) { }
    try { $('#add-ordem-tecnico').val('').trigger('change'); } catch (e) { }
    });

    $('#editgarantiaModal').on('hidden.bs.modal', function() {
        $('#editGarantiaForm').removeClass('was-validated');
    });

    // Funções para formatação de dinheiro
    function setupMoneyMasks() {
        // Configurar máscara de dinheiro brasileiro
        const moneyPattern = '000.000.000.000.000,00';
        if ($.fn && $.fn.mask) {
            $('#produto-preco, #produto-subtotal, #servico-preco, #servico-subtotal').mask(moneyPattern, { reverse: true });
            // Aplicar máscara aos campos da tabela também
            $('.produto-preco, .servico-preco').mask(moneyPattern, { reverse: true });
        }
    }

    function formatMoney(value) {
        // Formatar valor numérico para string monetária brasileira
        const numValue = parseFloat(value || 0);
        if (isNaN(numValue)) return '0,00';
        return numValue.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function parseMoney(value) {
        // Converter string monetária brasileira para float
        if (!value || value === '0,00') return 0;
        // Remove pontos e substitui vírgula por ponto
        const cleanValue = value.toString().replace(/\./g, '').replace(',', '.');
        const result = parseFloat(cleanValue) || 0;
        return isNaN(result) ? 0 : result;
    }

    // Funções auxiliares para formatação
    function formatDateTime(dateTime) {
        if (!dateTime) return '-';
        const date = new Date(dateTime);
        return date.toLocaleString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatDate(date) {
        if (!date) return '-';
        const d = new Date(date);
        return d.toLocaleDateString('pt-BR');
    }

    function getStatusColor(status) {
        const colors = {
            'Faturado': 'success',
            'Aguardando': 'warning',
            'Em Andamento': 'primary',
            'Aguardando Peças': 'warning',
            'Concluído': 'success',
            'Entregue': 'info',
            'Cancelado': 'danger'
        };
        return colors[status] || 'secondary';
    }

    function getPrioridadeColor(prioridade) {
        const colors = {
            'Baixa': 'secondary',
            'Média': 'warning',
            'Alta': 'danger',
            'Urgente': 'dark'
        };
        return colors[prioridade] || 'secondary';
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

    // Abrir/Imprimir cupom da ordem (comportamento equivalente ao de vendas)
    async function printCupomOrdem(id) {
        try {
            if (!id) {
                showAlert('error', 'ID da ordem inválido para impressão');
                return;
            }

            // Reutiliza a mesma URL de cupom do sistema (agora via OrdemController)
            const CUPOM_BASE = <?= json_encode(rtrim(site_url('ordens/downloadCupom'), '/') . '/') ?>;
            const url = CUPOM_BASE + id;
            window.open(url, '_blank');
        } catch (error) {
            console.error('Erro ao abrir cupom da ordem:', error);
            showAlert('error', 'Erro ao abrir cupom da ordem');
        }
    }

    // Expose to global scope for inline onclick handlers
    window.printCupomOrdem = printCupomOrdem;

    // Operações: abrir modal, gerenciar linhas e calcular totais
    async function openOperacoesModal(ordemId, ordemData = null) {
        if (!ordemId) {
            // se não houver id, tenta extrair de ordemData
            ordemId = ordemData && (ordemData.o1_id || ordemData.id) ? (ordemData.o1_id || ordemData.id) : '';
        }
        $('#operacoes-ordem-id').val(ordemId);

        // limpar tabelas
        $('#produtosTable tbody').empty();
        $('#servicosTable tbody').empty();
        // limpar formulários
        $('#produto-select').val('').trigger('change');
        $('#produto-quantidade').val('1');
        $('#produto-preco').val('0,00');
        $('#produto-subtotal').val('0,00');
        $('#servico-select').val('').trigger('change');
        $('#servico-quantidade').val('1');
        $('#servico-preco').val('0,00');
        $('#servico-subtotal').val('0,00');
        // zerar totais
        updateTotais();

        // Carregar produtos e serviços existentes da ordem
        if (ordemId) {
            try {
                const response = await $.ajax({
                    url: `<?= site_url('/ordens/') ?>${ordemId}`,
                    method: 'GET',
                    dataType: 'json'
                });

                if (response && response.produtos) {
                    // Carregar produtos existentes
                    response.produtos.forEach(produto => {
                        loadExistingProduto(produto);
                    });
                }

                if (response && response.servicos) {
                    // Carregar serviços existentes
                    response.servicos.forEach(servico => {
                        loadExistingServico(servico);
                    });
                }

                // Atualizar totais após carregar os dados
                updateTotais();

            } catch (error) {
                console.error('Erro ao carregar produtos e serviços:', error);
                showAlert('error', 'Erro ao carregar produtos e serviços da ordem');
            }
        }

        // Inicializar Select2 para produtos e serviços
        try {
            $('#produto-select').select2({
                placeholder: 'Selecione um produto...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#operacoesModal')
            });
        } catch (e) {
            // se Select2 não carregar, ignora
        }

        try {
            $('#servico-select').select2({
                placeholder: 'Selecione um serviço...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#operacoesModal')
            });
        } catch (e) {
            // se Select2 não carregar, ignora
        }

        // Configurar máscaras de dinheiro (formato brasileiro)
        setupMoneyMasks();

        // Aplicar máscara novamente após um pequeno delay para garantir
        setTimeout(function() {
            setupMoneyMasks();
            // Aplicar máscara aos campos da tabela também
            $('.produto-preco, .servico-preco').mask('000.000.000.000.000,00', { reverse: true });
        }, 100);

        // Bind events para selects
        $('#produto-select').on('change', function() {
            const preco = $(this).find('option:selected').data('preco') || 0;
            $('#produto-preco').val(formatMoney(preco));
            updateProdutoSubtotal();
        });

        $('#servico-select').on('change', function() {
            const preco = $(this).find('option:selected').data('preco') || 0;
            $('#servico-preco').val(formatMoney(preco));
            updateServicoSubtotal();
        });

        // Bind events para quantidade
        $('#produto-quantidade').on('input', updateProdutoSubtotal);
        $('#servico-quantidade').on('input', updateServicoSubtotal);

        const el = document.getElementById('operacoesModal');
        try { new bootstrap.Modal(el).show(); } catch (e) { bootstrap.Modal.getOrCreateInstance(el).show(); }
    }

    // Carrega produto existente na tabela
    function loadExistingProduto(produto) {
        const produtoId = produto.p3_produto_id || produto.produto_id;
        const produtoNome = produto.produto_nome || produto.nome || 'Produto não encontrado';
        const quantidade = produto.p3_quantidade || produto.quantidade || 1;
        const preco = produto.p3_valor_unitario || produto.valor_unitario || produto.preco || 0;
        const subtotal = produto.p3_valor_total || produto.valor_total || produto.subtotal || (quantidade * preco);

        const $tbody = $('#produtosTable tbody');
        const id = Date.now() + Math.floor(Math.random() * 1000);

        const row = $(`
            <tr data-row-id="${id}" data-produto-id="${produtoId}">
                <td>
                    <input type="hidden" name="produtos[][produto_id]" value="${produtoId}">
                    <input type="hidden" name="produtos[][nome]" value="${produtoNome}">
                    ${produtoNome}
                </td>
                <td>
                    <input type="number" name="produtos[][quantidade]" class="form-control form-control-sm produto-quantidade" value="${quantidade}" min="1">
                </td>
                <td>
                    <input type="text" name="produtos[][preco]" class="form-control form-control-sm produto-preco" value="${formatMoney(preco)}" readonly>
                </td>
                <td class="text-end produto-subtotal">${formatMoney(subtotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger remove-produto-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $tbody.append(row);

        // Aplicar máscara aos novos campos
        setTimeout(function() {
            row.find('.produto-preco').mask('000.000.000.000.000,00', { reverse: true });
        }, 50);

        // Bind events
        row.find('.produto-quantidade').on('input', function() {
            recalcProdutoRow($(this).closest('tr'));
            updateTotais();
        });
        row.find('.remove-produto-btn').on('click', function() {
            $(this).closest('tr').remove();
            updateTotais();
        });
    }

    // Carrega serviço existente na tabela
    function loadExistingServico(servico) {
        const servicoId = servico.s2_servico_id || servico.servico_id;
        const servicoNome = servico.servico_nome || servico.nome || 'Serviço não encontrado';
        const quantidade = servico.s2_quantidade || servico.quantidade || 1;
        const preco = servico.s2_valor_unitario || servico.valor_unitario || servico.preco || 0;
        const subtotal = servico.s2_valor_total || servico.valor_total || servico.subtotal || (quantidade * preco);

        const $tbody = $('#servicosTable tbody');
        const id = Date.now() + Math.floor(Math.random() * 1000);

        const row = $(`
            <tr data-row-id="${id}" data-servico-id="${servicoId}">
                <td>
                    <input type="hidden" name="servicos[][servico_id]" value="${servicoId}">
                    <input type="hidden" name="servicos[][nome]" value="${servicoNome}">
                    ${servicoNome}
                </td>
                <td>
                    <input type="number" name="servicos[][quantidade]" class="form-control form-control-sm servico-quantidade" value="${quantidade}" min="1">
                </td>
                <td>
                    <input type="text" name="servicos[][preco]" class="form-control form-control-sm servico-preco" value="${formatMoney(preco)}" readonly>
                </td>
                <td class="text-end servico-subtotal">${formatMoney(subtotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger remove-servico-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $tbody.append(row);

        // Aplicar máscara aos novos campos
        setTimeout(function() {
            row.find('.servico-preco').mask('000.000.000.000.000,00', { reverse: true });
        }, 50);

        // Bind events
        row.find('.servico-quantidade').on('input', function() {
            recalcServicoRow($(this).closest('tr'));
            updateTotais();
        });
        row.find('.remove-servico-btn').on('click', function() {
            $(this).closest('tr').remove();
            updateTotais();
        });
    }

    // Funções para calcular subtotais automaticamente
    function updateProdutoSubtotal() {
        const quantidade = parseFloat($('#produto-quantidade').val()) || 1;
        const preco = parseMoney($('#produto-preco').val()) || 0;
        const subtotal = quantidade * preco;
        $('#produto-subtotal').val(formatMoney(subtotal));
    }

    function updateServicoSubtotal() {
        const quantidade = parseFloat($('#servico-quantidade').val()) || 1;
        const preco = parseMoney($('#servico-preco').val()) || 0;
        const subtotal = quantidade * preco;
        $('#servico-subtotal').val(formatMoney(subtotal));
    }

    // Adiciona produto à tabela
    function addProduto() {
        const produtoId = $('#produto-select').val();
        const produtoNome = $('#produto-select option:selected').text().split(' - ')[0];
        const quantidade = parseFloat($('#produto-quantidade').val()) || 1;
        const preco = parseMoney($('#produto-preco').val()) || 0;

        if (!produtoId || !produtoNome.trim()) {
            showAlert('error', 'Selecione um produto válido.');
            return;
        }

        const subtotal = quantidade * preco;
        const $tbody = $('#produtosTable tbody');
        const id = Date.now() + Math.floor(Math.random() * 1000);

        const row = $(`
            <tr data-row-id="${id}" data-produto-id="${produtoId}">
                <td>
                    <input type="hidden" name="produtos[][produto_id]" value="${produtoId}">
                    <input type="hidden" name="produtos[][nome]" value="${produtoNome}">
                    ${produtoNome}
                </td>
                <td>
                    <input type="number" name="produtos[][quantidade]" class="form-control form-control-sm produto-quantidade" value="${quantidade}" min="1">
                </td>
                <td>
                    <input type="text" name="produtos[][preco]" class="form-control form-control-sm produto-preco" value="${formatMoney(preco)}" readonly>
                </td>
                <td class="text-end produto-subtotal">${formatMoney(subtotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger remove-produto-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $tbody.append(row);

        // Aplicar máscara aos novos campos
        setTimeout(function() {
            row.find('.produto-preco').mask('000.000.000.000.000,00', { reverse: true });
        }, 50);

        // Bind events
        row.find('.produto-quantidade').on('input', function() {
            recalcProdutoRow($(this).closest('tr'));
            updateTotais();
        });
        row.find('.remove-produto-btn').on('click', function() {
            $(this).closest('tr').remove();
            updateTotais();
        });

        // Limpar formulário
        $('#produto-select').val('').trigger('change');
        $('#produto-quantidade').val('1');
        $('#produto-preco').val('0,00');
        $('#produto-subtotal').val('0,00');

        updateTotais();
    }

    // Adiciona serviço à tabela
    function addServico() {
        const servicoId = $('#servico-select').val();
        const servicoNome = $('#servico-select option:selected').text().split(' - ')[0];
        const quantidade = parseFloat($('#servico-quantidade').val()) || 1;
        const preco = parseMoney($('#servico-preco').val()) || 0;

        if (!servicoId || !servicoNome.trim()) {
            showAlert('error', 'Selecione um serviço válido.');
            return;
        }

        const subtotal = quantidade * preco;
        const $tbody = $('#servicosTable tbody');
        const id = Date.now() + Math.floor(Math.random() * 1000);

        const row = $(`
            <tr data-row-id="${id}" data-servico-id="${servicoId}">
                <td>
                    <input type="hidden" name="servicos[][servico_id]" value="${servicoId}">
                    <input type="hidden" name="servicos[][nome]" value="${servicoNome}">
                    ${servicoNome}
                </td>
                <td>
                    <input type="number" name="servicos[][quantidade]" class="form-control form-control-sm servico-quantidade" value="${quantidade}" min="1">
                </td>
                <td>
                    <input type="text" name="servicos[][preco]" class="form-control form-control-sm servico-preco" value="${formatMoney(preco)}" readonly>
                </td>
                <td class="text-end servico-subtotal">${formatMoney(subtotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger remove-servico-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $tbody.append(row);

        // Aplicar máscara aos novos campos
        setTimeout(function() {
            row.find('.servico-preco').mask('000.000.000.000.000,00', { reverse: true });
        }, 50);

        // Bind events
        row.find('.servico-quantidade').on('input', function() {
            recalcServicoRow($(this).closest('tr'));
            updateTotais();
        });
        row.find('.remove-servico-btn').on('click', function() {
            $(this).closest('tr').remove();
            updateTotais();
        });

        // Limpar formulário
        $('#servico-select').val('').trigger('change');
        $('#servico-quantidade').val('1');
        $('#servico-preco').val('0,00');
        $('#servico-subtotal').val('0,00');

        updateTotais();
    }

    function recalcProdutoRow($tr) {
        const q = parseFloat($tr.find('.produto-quantidade').val() || 1);
        const p = parseMoney($tr.find('.produto-preco').val() || '0,00');
        const subtotal = (isFinite(q) && isFinite(p)) ? (q * p) : 0;
        $tr.find('.produto-subtotal').text(formatMoney(subtotal));
    }

    function recalcServicoRow($tr) {
        const q = parseFloat($tr.find('.servico-quantidade').val() || 1);
        const p = parseMoney($tr.find('.servico-preco').val() || '0,00');
        const subtotal = (isFinite(q) && isFinite(p)) ? (q * p) : 0;
        $tr.find('.servico-subtotal').text(formatMoney(subtotal));
    }

    function updateTotais() {
        // Calcular total de produtos
        let totalProdutos = 0;
        $('#produtosTable tbody tr').each(function() {
            const subtotalText = $(this).find('.produto-subtotal').text() || '0,00';
            const subtotal = parseMoney(subtotalText);
            totalProdutos += subtotal;
        });
        $('#produtosTotal').text(formatMoney(totalProdutos));

        // Calcular total de serviços
        let totalServicos = 0;
        $('#servicosTable tbody tr').each(function() {
            const subtotalText = $(this).find('.servico-subtotal').text() || '0,00';
            const subtotal = parseMoney(subtotalText);
            totalServicos += subtotal;
        });
        $('#servicosTotal').text(formatMoney(totalServicos));

        // Total geral
        const totalGeral = totalProdutos + totalServicos;
        $('#operacoesTotal').text(formatMoney(totalGeral));
    }

    // Handlers para botões de adicionar
    $(document).on('click', '#addProdutoBtn', function(e) {
        e.preventDefault();
        addProduto();
    });

    $(document).on('click', '#addServicoBtn', function(e) {
        e.preventDefault();
        addServico();
    });

    // Salvar operações (front-end) -> agora com endpoint backend implementado
    $(document).on('click', '#saveOperacoesBtn', async function(e) {
        e.preventDefault();
        const ordemId = $('#operacoes-ordem-id').val();
        
        if (!ordemId) {
            showAlert('error', 'ID da ordem não encontrado');
            return;
        }
        
        // Coletar produtos
        const produtos = [];
        $('#produtosTable tbody tr').each(function() {
            const produtoId = $(this).data('produto-id');
            const nome = $(this).find('input[name="produtos[][nome]"]').val() || '';
            const quantidade = parseFloat($(this).find('input[name="produtos[][quantidade]"]').val() || 0) || 0;
            const preco = parseMoney($(this).find('input[name="produtos[][preco]"]').val() || '0,00') || 0;
            if (produtoId && quantidade > 0) {
                produtos.push({ 
                    produto_id: produtoId, 
                    nome, 
                    quantidade, 
                    preco, 
                    subtotal: (quantidade * preco) 
                });
            }
        });

        // Coletar serviços
        const servicos = [];
        $('#servicosTable tbody tr').each(function() {
            const servicoId = $(this).data('servico-id');
            const nome = $(this).find('input[name="servicos[][nome]"]').val() || '';
            const quantidade = parseFloat($(this).find('input[name="servicos[][quantidade]"]').val() || 0) || 0;
            const preco = parseMoney($(this).find('input[name="servicos[][preco]"]').val() || '0,00') || 0;
            if (servicoId && quantidade > 0) {
                servicos.push({ 
                    servico_id: servicoId, 
                    nome, 
                    quantidade, 
                    preco, 
                    subtotal: (quantidade * preco) 
                });
            }
        });

        // Validar se há pelo menos um produto ou serviço
        if (produtos.length === 0 && servicos.length === 0) {
            showAlert('warning', 'Adicione pelo menos um produto ou serviço');
            return;
        }

        // estrutura a enviar ao backend
        const payload = { 
            ordem_id: ordemId, 
            produtos: produtos, 
            servicos: servicos, 
            total_produtos: parseMoney($('#produtosTotal').text() || '0,00'),
            total_servicos: parseMoney($('#servicosTotal').text() || '0,00'),
            total_geral: parseMoney($('#operacoesTotal').text() || '0,00')
        };

        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${ordemId}/operacoes`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload)
            });

            if (response.success) {
                showAlert('success', `Operações salvas com sucesso! ${response.produtos_salvos} produtos e ${response.servicos_salvos} serviços salvos.`);
                const el = document.getElementById('operacoesModal');
                try { bootstrap.Modal.getOrCreateInstance(el).hide(); } catch (e) { /* ignore */ }
                await loadOrdens();
            } else {
                showAlert('error', response.message || 'Erro ao salvar operações');
            }
        } catch (error) {
            console.error('Erro ao salvar operações:', error);
            if (error.responseJSON && error.responseJSON.messages) {
                const messages = Object.values(error.responseJSON.messages).join(', ');
                showAlert('error', messages);
            } else {
                showAlert('error', 'Erro ao salvar operações');
            }
        }
    });
</script>

    <script>
    (function(){
        'use strict';

        function openFaturarModal(id) {
            try {
                console.log('openFaturarModal called for id:', id);
                if (!id) return;

                // Preparar campos com valores padrões enquanto carregamos os dados
                $('#faturar-ordem-id').val(id);
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

                // Buscar dados da ordem para preencher observações/data se existirem
                const BASE_URL = <?= json_encode(rtrim(site_url('ordens'), '/') . '/') ?>;
                $.ajax({
                    url: BASE_URL + id,
                    method: 'GET',
                    dataType: 'json',
                    timeout: 8000,
                    success: function(resp){
                        try {
                            if (resp && resp.ordem) {
                                const o = resp.ordem;
                                if (o.o1_observacoes_conclusao) $('#faturar-observacoes').val(o.o1_observacoes_conclusao);
                                if (o.o1_data_faturamento) $('#faturar-data').val(o.o1_data_faturamento.split(' ')[0] || o.o1_data_faturamento);
                            }
                        } catch(e){
                            console.warn('openFaturarModal: falha ao preencher dados da ordem', e);
                        } finally {
                            showModal();
                        }
                    },
                    error: function(xhr, status, err){
                        console.warn('openFaturarModal: não foi possível carregar ordem, exibindo modal com valores padrão', status, err);
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
                const ordemId = $('#faturar-ordem-id').val();
                const dataFaturamento = $('#faturar-data').val();
                const observacoes = $('#faturar-observacoes').val() || null;

                if (!ordemId) { showAlert('error', 'ID da ordem inválido'); return; }
                if (!dataFaturamento) { $('#faturar-data')[0].reportValidity(); return; }

                const payload = { data_faturamento: dataFaturamento, observacoes: observacoes };

                const resp = await $.ajax({
                    url: `<?= rtrim(site_url('ordens'), '/') . '/' ?>${ordemId}/faturar`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    timeout: 15000
                });

                if (resp && resp.success) {
                    try { const el = document.getElementById('faturarModal'); if (el) { bootstrap.Modal.getInstance(el)?.hide(); } } catch(e){}
                    showAlert('success', 'Ordem faturada com sucesso');
                    try { await loadOrdens(); } catch(e){}
                } else {
                    const msg = resp && resp.message ? resp.message : 'Erro ao faturar ordem';
                    showAlert('error', msg);
                }

            } catch (error) {
                console.error('submitFaturar error:', error);
                showAlert('error', 'Erro ao faturar ordem (verifique console)');
            }
        }

        // wire up button
        $(document).off('click.faturar', '#faturarSubmitBtn').on('click.faturar', '#faturarSubmitBtn', function(e){ e.preventDefault(); submitFaturar(); });

        // expose globally
        window.openFaturarModal = openFaturarModal;
        window.submitFaturar = submitFaturar;
        window.OrdensManager = window.OrdensManager || {};
        window.OrdensManager.openFaturarModal = openFaturarModal;
        window.OrdensManager.submitFaturar = submitFaturar;

    })();
    </script>

<?= $this->endSection() ?>