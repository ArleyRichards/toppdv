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
                <div class="col-lg-6 col-md-6">
                    <label for="filterSearch" class="form-label">Pesquisar</label>
                    <input type="text" class="form-control" id="filterSearch" placeholder="Número da ordem, cliente ou equipamento...">
                </div>
                <div class="col-lg-2 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
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
                            <th>Cliente ID</th>
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
                            <input type="text" class="form-control" id="add-ordem-tecnico" name="tecnico_id">
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
                                <option value="Normal" selected>Normal</option>
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

<!-- Modal de Edição de Garantia -->
<div class="modal fade" id="editgarantiaModal" tabindex="-1" aria-labelledby="editgarantiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editgarantiaModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Garantia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                    <form id="editGarantiaForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit-garantia-id" name="id">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="edit-garantia-nome" class="form-label">Nome da Garantia</label>
                        <input type="text" class="form-control" id="edit-garantia-nome" name="nome" required>
                        <div class="invalid-feedback" id="edit-garantia-nome-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-garantia-descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="edit-garantia-descricao" name="descricao" rows="2"></textarea>
                        <div class="invalid-feedback" id="edit-garantia-descricao-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-garantia-observacao" class="form-label">Observação</label>
                        <textarea class="form-control" id="edit-garantia-observacao" name="observacao" rows="2"></textarea>
                        <div class="invalid-feedback" id="edit-garantia-observacao-error"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-garantia-data" class="form-label">Data</label>
                            <input type="date" class="form-control" id="edit-garantia-data" name="data">
                            <div class="invalid-feedback" id="edit-garantia-data-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-garantia-data-garantia" class="form-label">Data da Garantia</label>
                            <input type="datetime-local" class="form-control" id="edit-garantia-data-garantia" name="data_garantia">
                            <div class="invalid-feedback" id="edit-garantia-data-garantia-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="updateGarantiaBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Garantia
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
                                    <small class="text-muted">Adicione produtos e serviços à ordem. Você pode adicionar várias linhas de cada tipo.</small>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm" id="operacoesTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descrição</th>
                                                <th style="width:120px">Quantidade</th>
                                                <th style="width:140px">Preço Unit.</th>
                                                <th style="width:140px">Subtotal</th>
                                                <th style="width:80px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- linhas serão adicionadas dinamicamente -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addProdutoBtn"><i class="fa-solid fa-plus me-1"></i>Produto</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="addServicoBtn"><i class="fa-solid fa-plus me-1"></i>Serviço</button>
                                    </div>
                                    <div class="text-end">
                                        <div><small class="text-muted">Total</small></div>
                                        <h4 id="operacoesTotal">0.00</h4>
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

        // remove classe de erro quando usuário muda seleção
        $(document).on('change', '#add-ordem-cliente-id', function() {
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
        $('#filterSearch').on('input', applyFilters);
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
                    equipamento: o.o1_equipamento,
                    marca: o.o1_marca,
                    modelo: o.o1_modelo,
                    status: o.o1_status,
                    prioridade: o.o1_prioridade,
                    valor_final: o.o1_valor_final,
                    data_entrada: o.o1_data_entrada,
                    defeito: o.o1_defeito_relatado
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
        const q = ($('#filterSearch').val() || '').toLowerCase();

        filteredOrdens = ordensData.filter(o => {
            if (!q) return true;
            return String(o.numero || '').toLowerCase().includes(q)
                || String(o.cliente_id || '').toLowerCase().includes(q)
                || String(o.equipamento || '').toLowerCase().includes(q);
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterSearch').val('');
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
        const cliente = ordem.cliente_id || '-';
        const equipamento = ordem.equipamento || '-';
        const marca = ordem.marca || '-';
        const modelo = ordem.modelo || '-';
        const status = ordem.status || '-';
        const prioridade = ordem.prioridade || '-';
        const valor = ordem.valor_final ? Number(ordem.valor_final).toFixed(2) : '0.00';
        const dataEntrada = ordem.data_entrada || '-';

        return `
            <tr>
                <td>${numero}</td>
                <td>${cliente}</td>
                <td>${equipamento}</td>
                <td>${marca}</td>
                <td>${modelo}</td>
                <td>${status}</td>
                <td>${prioridade}</td>
                <td class="text-end">${valor}</td>
                <td>${dataEntrada}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewOrdem(${id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editOrdem(${id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteOrdem(${id}, '${String(numero).replace(/'/g, "\\'")}')" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>
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
    function showOrdemDetails(ordem) {
        $('#garantiaModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary">Dados da Ordem</h6>
                    <hr>
                </div>
                <div class="col-12"><strong>Número:</strong><br>${ordem.o1_numero_ordem || '-'}</div>
                <div class="col-12"><strong>Cliente ID:</strong><br>${ordem.o1_cliente_id || '-'}</div>
                <div class="col-12"><strong>Equipamento:</strong><br>${ordem.o1_equipamento || '-'}</div>
                <div class="col-12"><strong>Defeito:</strong><br>${ordem.o1_defeito_relatado || '-'}</div>
                <div class="col-12"><strong>Valor Final:</strong><br>${ordem.o1_valor_final ?? '0.00'}</div>
            </div>
        `);

        $('#editCategoryBtn').data('category-id', ordem.o1_id);

        const viewEl = document.getElementById('garantiaModal');
        try { new bootstrap.Modal(viewEl).show(); } catch (e) { bootstrap.Modal.getOrCreateInstance(viewEl).show(); }
    }

    // Alias used by table buttons
    function editOrdem(id) { openEditModal(id); }

    // Alias used by table buttons
    function deleteOrdem(id, numero) { deleteGarantia(id, numero); }

    // Abrir modal de edição (reusa modais existentes mas carrega dados da ordem)
    async function openEditModal(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                // preencher campos do modal de edição de garantia com dados relevantes da ordem
                $('#edit-garantia-id').val(response.o1_id);
                $('#edit-garantia-nome').val(response.o1_numero_ordem);
                $('#edit-garantia-descricao').val(response.o1_defeito_relatado || '');
                $('#edit-garantia-observacao').val(response.o1_observacoes_entrada || '');
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

    // Atualizar garantia (reaproveitado para a edição rápida de ordens via modal)
    async function updateGarantia() {
        const $form = $('#editGarantiaForm');
        // Temporarily disable HTML5 datetime-local validation for the data_garantia field
        const $dtInput = $('#edit-garantia-data-garantia');
        const originalType = $dtInput.prop('type') || 'datetime-local';
        try {
            $dtInput.prop('type', 'text');
        } catch (e) {
            /* ignore */
        }

        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            // restore type before returning
            try { $dtInput.prop('type', originalType); } catch (e) { /* ignore */ }
            return;
        }

        // restore input type for UX
        try { $dtInput.prop('type', originalType); } catch (e) { /* ignore */ }
        // limpar mensagens anteriores
        clearFieldErrors('edit');

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        const garantiaId = data.id || data.g1_id;

    // Map garantia form fields to ordem fields expected by the server
    // controller accepts multiple keys; provide common mappings here
    if (data.nome) data.numero_ordem = data.nome;
    if (data.descricao) data.defeito_relatado = data.descricao;
    if (data.observacao) data.observacoes_entrada = data.observacao;

        // normalize datetime-local to server-friendly format (YYYY-MM-DD HH:MM:SS)
        if (data.data_garantia) {
            // e.g. "2025-09-03T13:45" -> "2025-09-03 13:45:00"
            try {
                let v = String(data.data_garantia).trim();
                if (v.includes('T')) {
                    v = v.replace('T', ' ');
                }
                // if no seconds provided, append :00
                if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/.test(v)) {
                    v = v + ':00';
                }
                data.data_garantia = v;
            } catch (e) {
                // keep original if something goes wrong
            }
        }

        try {
            const response = await $.ajax({
                url: `<?= site_url('/ordens/') ?>${garantiaId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Garantia atualizada com sucesso!');
                // hide edit modal via Bootstrap API
                const editEl = document.getElementById('editgarantiaModal');
                try {
                    bootstrap.Modal.getOrCreateInstance(editEl).hide();
                } catch (e) {
                    /* ignore */ }
                $form.removeClass('was-validated');
                await loadOrdens();
            } else {
                showAlert('error', response.message || 'Erro ao atualizar garantia');
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
                showAlert('error', 'Erro ao atualizar garantia');
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
                edit: null,
                errorAdd: '#add-ordem-cliente-id-error',
                errorEdit: null
            },
            'equipamento': {
                add: '#add-ordem-equipamento',
                edit: null,
                errorAdd: '#add-ordem-equipamento-error',
                errorEdit: null
            },
            'defeito_relatado': {
                add: '#add-ordem-defeito',
                edit: '#edit-garantia-descricao',
                errorAdd: '#add-ordem-defeito-error',
                errorEdit: '#edit-garantia-descricao-error'
            },
            'observacoes_entrada': {
                add: '#add-ordem-observacoes',
                edit: '#edit-garantia-observacao',
                errorAdd: null,
                errorEdit: '#edit-garantia-observacao-error'
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

    // Excluir categoria
    async function deleteGarantia(id, name) {
        Swal.fire({
            title: `Tem certeza que deseja excluir a categoria "${name}"?`,
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

                    if (response) {
                        showAlert('success', 'Garantia excluída com sucesso!');
                        await loadOrdens();
                    } else {
                        showAlert('error', response.message || 'Erro ao excluir garantia');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    showAlert('error', 'Erro ao excluir garantia');
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
    });

    $('#editgarantiaModal').on('hidden.bs.modal', function() {
        $('#editGarantiaForm').removeClass('was-validated');
    });

    // Operações: abrir modal, gerenciar linhas e calcular totais
    function openOperacoesModal(ordemId, ordemData = null) {
        if (!ordemId) {
            // se não houver id, tenta extrair de ordemData
            ordemId = ordemData && (ordemData.o1_id || ordemData.id) ? (ordemData.o1_id || ordemData.id) : '';
        }
        $('#operacoes-ordem-id').val(ordemId);
        // limpar tabela
        $('#operacoesTable tbody').empty();
        // adicionar uma linha de produto e uma de serviço por padrão
        addOperacaoRow('produto');
        addOperacaoRow('servico');
        // zerar total
        updateOperacoesTotal();

        const el = document.getElementById('operacoesModal');
        try { new bootstrap.Modal(el).show(); } catch (e) { bootstrap.Modal.getOrCreateInstance(el).show(); }
    }

    // Adiciona uma linha na tabela de operações. tipo: 'produto' ou 'servico'
    function addOperacaoRow(tipo) {
        const $tbody = $('#operacoesTable tbody');
        const id = Date.now() + Math.floor(Math.random() * 1000);
        const tipoLabel = tipo === 'servico' ? 'Serviço' : 'Produto';
        const row = $(
            `<tr data-row-id="${id}" data-tipo="${tipo}">
                <td>
                    <input type="hidden" name="items[][tipo]" value="${tipo}">
                    ${tipoLabel}
                </td>
                <td><input type="text" name="items[][descricao]" class="form-control form-control-sm" placeholder="Descrição"></td>
                <td><input type="number" min="0" step="1" name="items[][quantidade]" class="form-control form-control-sm operacao-quantidade" value="1"></td>
                <td><input type="number" min="0" step="0.01" name="items[][preco_unitario]" class="form-control form-control-sm operacao-preco" value="0.00"></td>
                <td class="text-end operacao-subtotal">0.00</td>
                <td class="text-end"><button type="button" class="btn btn-sm btn-danger removeOperacaoBtn"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`
        );
        $tbody.append(row);

        // bind events
        row.find('.operacao-quantidade, .operacao-preco').on('input', function() { recalcRow($(this).closest('tr')); updateOperacoesTotal(); });
        row.find('.removeOperacaoBtn').on('click', function() { $(this).closest('tr').remove(); updateOperacoesTotal(); });
    }

    function recalcRow($tr) {
        const q = parseFloat($tr.find('.operacao-quantidade').val() || 0);
        const p = parseFloat($tr.find('.operacao-preco').val() || 0);
        const subtotal = (isFinite(q) && isFinite(p)) ? (q * p) : 0;
        $tr.find('.operacao-subtotal').text(subtotal.toFixed(2));
    }

    function updateOperacoesTotal() {
        let total = 0;
        $('#operacoesTable tbody tr').each(function() {
            const s = parseFloat($(this).find('.operacao-subtotal').text() || 0) || 0;
            total += s;
        });
        $('#operacoesTotal').text(Number(total).toFixed(2));
    }

    // Handlers para botões de adicionar
    $(document).on('click', '#addProdutoBtn', function(e) { e.preventDefault(); addOperacaoRow('produto'); updateOperacoesTotal(); });
    $(document).on('click', '#addServicoBtn', function(e) { e.preventDefault(); addOperacaoRow('servico'); updateOperacoesTotal(); });

    // Salvar operações (front-end) -> precisa de endpoint backend para persistir
    $(document).on('click', '#saveOperacoesBtn', async function(e) {
        e.preventDefault();
        const ordemId = $('#operacoes-ordem-id').val();
        const items = [];
        $('#operacoesTable tbody tr').each(function() {
            const tipo = $(this).data('tipo') || $(this).find('input[name="items[][tipo]"]').val();
            const descricao = $(this).find('input[name="items[][descricao]"]').val() || '';
            const quantidade = parseFloat($(this).find('input[name="items[][quantidade]"]').val() || 0) || 0;
            const preco = parseFloat($(this).find('input[name="items[][preco_unitario]"]').val() || 0) || 0;
            if (descricao === '' && quantidade === 0 && preco === 0) return; // pular linhas vazias
            items.push({ tipo, descricao, quantidade, preco, subtotal: (quantidade * preco) });
        });

        // estrutura a enviar ao backend
        const payload = { ordem_id: ordemId, items: items, total: Number($('#operacoesTotal').text() || 0) };

        try {
            // TODO: implementar endpoint POST /ordens/{id}/operacoes
            // Por enquanto, apenas mostra mensagem e fecha modal
            showAlert('success', 'Operações preparadas (persistência backend não implementada).');
            const el = document.getElementById('operacoesModal');
            try { bootstrap.Modal.getOrCreateInstance(el).hide(); } catch (e) { /* ignore */ }
            await loadOrdens();
        } catch (error) {
            console.error('Erro ao salvar operações:', error);
            showAlert('error', 'Erro ao salvar operações');
        }
    });
</script>

<?= $this->endSection() ?>