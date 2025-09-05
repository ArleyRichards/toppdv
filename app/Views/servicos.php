<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Serviços</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todos os serviços cadastrados no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addServicoModal">
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
                <div class="col-lg-2 col-md-6">
                    <label for="filterCodigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="filterCodigo" placeholder="Ex: SVC001">
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="filterName" class="form-label">Nome do Serviço</label>
                    <input type="text" class="form-control" id="filterName" placeholder="Digite o nome do serviço...">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="filterValorMin" class="form-label">Valor (min)</label>
                    <input type="text" class="form-control" id="filterValorMin" placeholder="0,00">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="filterValorMax" class="form-label">Valor (max)</label>
                    <input type="text" class="form-control" id="filterValorMax" placeholder="0,00">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="filterCategoria" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="filterCategoria" placeholder="Categoria...">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de garantias -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Serviços Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nome do Serviço</th>
                            <th>Valor</th>
                            <th>Categoria</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="servicosTableBody">
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
<div class="modal fade" id="servicoModal" tabindex="-1" aria-labelledby="servicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="servicoModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes do Serviço
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="servicoModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editCategoryBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de serviço -->
<div class="modal fade" id="addServicoModal" tabindex="-1" aria-labelledby="addServicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicoModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Novo Serviço
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addServicoForm" class="needs-validation" novalidate>
                    <?php if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } ?>
                    <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="servico-codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="servico-codigo" name="codigo" placeholder="Ex: SVC001">
                        </div>
                        <div class="col-md-6">
                            <label for="servico-nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="servico-nome" name="nome" required>
                            <div class="invalid-feedback">Informe o nome do serviço.</div>
                        </div>
                        <div class="col-12">
                            <label for="servico-descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="servico-descricao" name="descricao" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="servico-valor" class="form-label">Valor</label>
                            <input type="text" class="form-control" id="servico-valor" name="valor" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label for="servico-tempo_medio" class="form-label">Tempo Médio (min)</label>
                            <input type="number" class="form-control" id="servico-tempo_medio" name="tempo_medio" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="servico-categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="servico-categoria" name="categoria">
                        </div>
                        <div class="col-md-4">
                            <label for="servico-garantia" class="form-label">Garantia (dias)</label>
                            <input type="number" class="form-control" id="servico-garantia" name="garantia" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="servico-status" class="form-label">Status</label>
                            <select id="servico-status" name="status" class="form-select">
                                <option value="Ativo" selected>Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveServicoBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Serviço
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Serviço -->
<div class="modal fade" id="editServicoModal" tabindex="-1" aria-labelledby="editServicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServicoModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Serviço
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editServicoForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit-servico-id" name="id">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit-servico-codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="edit-servico-codigo" name="codigo" placeholder="Ex: SVC001">
                            <div class="invalid-feedback" id="edit-servico-codigo-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit-servico-nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-servico-nome" name="nome" required>
                            <div class="invalid-feedback" id="edit-servico-nome-error"></div>
                        </div>
                        <div class="col-12">
                            <label for="edit-servico-descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="edit-servico-descricao" name="descricao" rows="3"></textarea>
                            <div class="invalid-feedback" id="edit-servico-descricao-error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-servico-valor" class="form-label">Valor</label>
                            <input type="text" class="form-control" id="edit-servico-valor" name="valor" placeholder="0.00">
                            <div class="invalid-feedback" id="edit-servico-valor-error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-servico-tempo_medio" class="form-label">Tempo Médio (min)</label>
                            <input type="number" class="form-control" id="edit-servico-tempo_medio" name="tempo_medio" min="0">
                            <div class="invalid-feedback" id="edit-servico-tempo_medio-error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-servico-categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="edit-servico-categoria" name="categoria">
                            <div class="invalid-feedback" id="edit-servico-categoria-error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-servico-garantia" class="form-label">Garantia (dias)</label>
                            <input type="number" class="form-control" id="edit-servico-garantia" name="garantia" min="0">
                            <div class="invalid-feedback" id="edit-servico-garantia-error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-servico-status" class="form-label">Status</label>
                            <select id="edit-servico-status" name="status" class="form-select">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                            <div class="invalid-feedback" id="edit-servico-status-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="updateServicoBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Serviço
                </button>
            </div>
        </div>
    </div>
</div>
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script>
    // Variáveis globais
    let servicosData = [];
    let filteredServicos = [];
    let currentPage = 1; //PÁGINA ATUAL
    let itemsPerPage = 10; //ITENS POR PÁGINA  

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        loadServicos();
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
    // Filtros: código, nome, valor, categoria
    $('#filterCodigo').on('input', applyFilters);
    $('#filterName').on('input', applyFilters);
    $('#filterValorMin').on('input', applyFilters);
    $('#filterValorMax').on('input', applyFilters);
    $('#filterCategoria').on('input', applyFilters);
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
                // Fecha o modal de visualização caso esteja aberto para evitar problemas com backdrop
                const viewModalEl = document.getElementById('servicoModal');
                const viewInstance = bootstrap.Modal.getInstance(viewModalEl) || null;
                if (viewInstance) {
                    try {
                        viewInstance.hide();
                    } catch (e) {
                        /* ignore */ }
                }
                openEditModal(id);
            }
        });
    }

    // (no duplicate handler) - handled inside setupEventListeners

    // Bind action buttons
    $(document).on('click', '#saveServicoBtn', function(e) {
        e.preventDefault();
        saveServico();
    });

    $(document).on('click', '#updateServicoBtn', function(e) {
        e.preventDefault();
        updateServico();
    });

    // Carregar dados das categorias
    async function loadServicos() {
        try {
            const response = await $.get('<?= site_url('/servico/list') ?>');

            if (Array.isArray(response)) {
                servicosData = response.map(s => ({
                    id: s.s1_id,
                    codigo: s.s1_codigo_servico ?? null,
                    nome: s.s1_nome_servico ?? null,
                    data: s.s1_created_at ?? null,
                    descricao: s.s1_descricao ?? null,
                    valor: typeof s.s1_valor !== 'undefined' ? Number(s.s1_valor) : 0.00,
                    tempo_medio: typeof s.s1_tempo_medio !== 'undefined' ? Number(s.s1_tempo_medio) : null,
                    categoria: s.s1_categoria ?? null,
                    garantia: typeof s.s1_garantia !== 'undefined' ? Number(s.s1_garantia) : 0,
                    status: s.s1_status ?? 'Ativo',
                    updated_at: s.s1_updated_at ?? null
                }));

                filteredServicos = [...servicosData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar serviços');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados dos serviços');
        }
    }

    // Aplicar filtros: nome e intervalo de comissão
    function applyFilters() {
        const codigoFilter = ($('#filterCodigo').val() || '').toLowerCase();
        const nameFilter = ($('#filterName').val() || '').toLowerCase();
        const categoriaFilter = ($('#filterCategoria').val() || '').toLowerCase();

        // parse numeric filter values (accepts 1.234,56 or 1234.56 or plain numbers)
        function parseFilterNumber(v) {
            if (!v) return null;
            let s = String(v).trim();
            if (s.indexOf(',') > -1) s = s.replace(/\./g, '').replace(',', '.');
            s = s.replace(/[^0-9.\-]/g, '');
            const n = Number(s);
            return isNaN(n) ? null : n;
        }

        const valorMin = parseFilterNumber($('#filterValorMin').val());
        const valorMax = parseFilterNumber($('#filterValorMax').val());

        filteredServicos = servicosData.filter(g => {
            // código
            if (codigoFilter) {
                const code = (g.codigo || g.s1_codigo_servico || '').toString().toLowerCase();
                if (!code.includes(codigoFilter)) return false;
            }
            // nome
            if (nameFilter) {
                const nome = (g.nome || g.s1_nome_servico || '').toString().toLowerCase();
                if (!nome.includes(nameFilter)) return false;
            }
            // categoria
            if (categoriaFilter) {
                const cat = (g.categoria || g.s1_categoria || '').toString().toLowerCase();
                if (!cat.includes(categoriaFilter)) return false;
            }
            // valor range
            const rawValor = (typeof g.valor !== 'undefined') ? g.valor : (g.s1_valor !== undefined ? g.s1_valor : null);
            let valorNum = null;
            if (rawValor !== null && typeof rawValor !== 'undefined' && rawValor !== '') {
                let s = String(rawValor);
                if (s.indexOf(',') > -1) s = s.replace(/\./g, '').replace(',', '.');
                s = s.replace(/[^0-9.\-]/g, '');
                const n = Number(s);
                valorNum = isNaN(n) ? null : n;
            }
            if (valorMin !== null && (valorNum === null || valorNum < valorMin)) return false;
            if (valorMax !== null && (valorNum === null || valorNum > valorMax)) return false;

            return true;
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        // Clear all filter inputs
        $('#filterCodigo').val('');
        $('#filterName').val('');
        $('#filterValorMin').val('');
        $('#filterValorMax').val('');
        $('#filterCategoria').val('');

        // Remove any validation / mask artifacts
        if ($.fn.mask) {
            // trigger mask update if used
            $('#filterValorMin').trigger('input');
            $('#filterValorMax').trigger('input');
        }

        // Reset filtered data and UI
        filteredServicos = [...servicosData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
    const pageData = filteredServicos.slice(startIndex, endIndex);

        const $tbody = $('#servicosTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhum serviço encontrado
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(servico => {
                const row = createTableRow(servico);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    // Formata número como valor com 2 casas e separador brasileiro (milhar . e decimal ,)
    function formatCurrency(value) {
        if (value === null || typeof value === 'undefined' || value === '') return '-';
        let n = Number(value);
        if (isNaN(n)) return '-';
        // toFixed with dot, then replace to Brazilian format
        return n.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function createTableRow(servico) {
        const id = servico.id || '';
        const codigo = servico.codigo || (servico.s1_codigo_servico || '-') || '-';
        const nome = servico.nome || (servico.s1_nome_servico || '-') || '-';
        const valor = (typeof servico.valor !== 'undefined') ? servico.valor : (servico.s1_valor !== undefined ? servico.s1_valor : null);
        const categoria = servico.categoria || (servico.s1_categoria || '-') || '-';

        const valorDisplay = formatCurrency(valor);

        return `
            <tr>
                <td>${codigo}</td>
                <td>${nome}</td>
                <td>${valorDisplay}</td>
                <td>${categoria}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewServico(${id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="openEditModal(${id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteServico(${id}, '${String(nome).replace(/'/g, "\\'")}')" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
    const total = filteredServicos.length;
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
    const totalPages = Math.ceil(filteredServicos.length / itemsPerPage);
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
    const totalPages = Math.ceil(filteredServicos.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar serviço
    async function viewServico(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/servico/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            // backend may return the resource directly or wrapped in { data: resource }
            const serv = response && response.data ? response.data : response;
            if (serv) {
                showServicoDetails(serv);
            } else {
                showAlert('error', 'Erro ao carregar dados do serviço');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do serviço');
        }
    }

    // Mostrar detalhes da categoria
    function showServicoDetails(servico) {
        // Helper to read either s1_ prefixed fields or plain keys
        function read(fieldPref, alt) {
            if (!servico) return null;
            if (typeof servico[fieldPref] !== 'undefined' && servico[fieldPref] !== null) return servico[fieldPref];
            if (typeof servico[alt] !== 'undefined' && servico[alt] !== null) return servico[alt];
            return null;
        }

        function parseNumber(val) {
            if (val === null || typeof val === 'undefined' || val === '') return null;
            let s = String(val).trim();
            // support brazilian format like 1.234,56
            if (s.indexOf(',') > -1) {
                s = s.replace(/\./g, '').replace(',', '.');
            }
            s = s.replace(/[^0-9.\-]/g, '');
            const n = Number(s);
            return isNaN(n) ? null : n;
        }

        const codigo = read('s1_codigo_servico', 'codigo') || '-';
        const nome = read('s1_nome_servico', 'nome') || '-';
        const descricao = read('s1_descricao', 'descricao') || '-';
        const valorNum = parseNumber(read('s1_valor', 'valor'));
        const valorDisplay = (valorNum !== null) ? valorNum.toFixed(2).replace('.', ',') : '-';
        const tempo = read('s1_tempo_medio', 'tempo_medio');
        const tempoDisplay = (tempo !== null && tempo !== '') ? tempo : '-';
        const categoria = read('s1_categoria', 'categoria') || '-';
        const garantia = read('s1_garantia', 'garantia');
        const garantiaDisplay = (garantia !== null && garantia !== '') ? garantia : '-';

        $('#servicoModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary">Dados do Serviço</h6>
                    <hr>
                </div>
                <div class="col-12">
                    <strong>Código:</strong><br>
                    ${codigo}
                </div>
                <div class="col-12">
                    <strong>Nome:</strong><br>
                    ${nome}
                </div>
                <div class="col-12">
                    <strong>Descrição:</strong><br>
                    ${descricao}
                </div>
                <div class="col-12">
                    <strong>Valor:</strong><br>
                    ${valorDisplay}
                </div>
                <div class="col-12">
                    <strong>Tempo Médio (min):</strong><br>
                    ${tempoDisplay}
                </div>
                <div class="col-12">
                    <strong>Categoria:</strong><br>
                    ${categoria}
                </div>
                <div class="col-12">
                    <strong>Garantia (dias):</strong><br>
                    ${garantiaDisplay}
                </div>
            </div>
        `);

        // Ensure the edit button gets the correct id regardless of backend key naming
        const editId = read('s1_id', 'id') || read('id', 's1_id') || '';
        $('#editCategoryBtn').data('category-id', editId);

        // Show view modal using element + Bootstrap API (robust across setups)
        const viewEl = document.getElementById('servicoModal');
        try {
            new bootstrap.Modal(viewEl).show();
        } catch (e) {
            const viewInstance = bootstrap.Modal.getOrCreateInstance(viewEl);
            viewInstance.show();
        }
    }

    // Editar categoria
    function editGarantia(id) {
        openEditModal(id);
    }

    // Abrir modal de edição de garantia
    async function openEditModal(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/servico/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            const serv = response && response.data ? response.data : response;
            if (serv) {
                fillEditForm(serv);
                // Use Bootstrap API to get/create modal instance and show it
                const editEl = document.getElementById('editServicoModal');
                try {
                    // prefer explicit constructor for compatibility
                    new bootstrap.Modal(editEl).show();
                } catch (e) {
                    const editModalInstance = bootstrap.Modal.getOrCreateInstance(editEl);
                    editModalInstance.show();
                }
            } else {
                showAlert('error', 'Erro ao carregar dados do serviço');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do serviço');
        }
    }

    // Preencher formulário de edição de garantia
    function fillEditForm(servico) {
        // Read helper: prefer s1_* then fall back to plain keys
        function read(fieldPref, alt) {
            if (!servico) return '';
            if (typeof servico[fieldPref] !== 'undefined' && servico[fieldPref] !== null) return servico[fieldPref];
            if (typeof servico[alt] !== 'undefined' && servico[alt] !== null) return servico[alt];
            return '';
        }

        function parseNumber(val) {
            if (val === null || typeof val === 'undefined' || val === '') return null;
            let s = String(val).trim();
            if (s.indexOf(',') > -1) {
                s = s.replace(/\./g, '').replace(',', '.');
            }
            s = s.replace(/[^0-9.\-]/g, '');
            const n = Number(s);
            return isNaN(n) ? null : n;
        }

        const editId = read('s1_id', 'id') || '';
        $('#edit-servico-id').val(editId);
        $('#edit-servico-nome').val(read('s1_nome_servico', 'nome') || '');
        $('#edit-servico-codigo').val(read('s1_codigo_servico', 'codigo') || '');
        $('#edit-servico-status').val(read('s1_status', 'status') || 'Ativo');
        // Preenche todos os campos disponíveis no formulário de edição
        $('#edit-servico-descricao').val(read('s1_descricao', 'descricao') || '');
        const valorRaw = read('s1_valor', 'valor');
        const valorNum = parseNumber(valorRaw);
        $('#edit-servico-valor').val(valorNum !== null ? valorNum.toFixed(2) : '');
        $('#edit-servico-tempo_medio').val(read('s1_tempo_medio', 'tempo_medio') || '');
        $('#edit-servico-categoria').val(read('s1_categoria', 'categoria') || '');
        $('#edit-servico-garantia').val(read('s1_garantia', 'garantia') || '');
        // Convert server datetime ("YYYY-MM-DD HH:MM:SS" or "YYYY-MM-DD HH:MM")
        // into a value accepted by <input type="datetime-local"> ("YYYY-MM-DDTHH:MM").
    let dg = '';
        if (dg) {
            // replace space with 'T' and strip seconds if present
            dg = String(dg).trim();
            dg = dg.replace(' ', 'T');
            const m = dg.match(/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2})/);
            dg = m ? m[1] : '';
        }
    // no-op: this view does not use datetime fields
    }

    // Inicializar máscaras quando o DOM estiver pronto
    $(function() {
        if ($.fn.mask) {
            $('#servico-valor').mask('#.##0,00', {reverse: true});
            $('#edit-servico-valor').mask('#.##0,00', {reverse: true});
            // Máscaras para filtros de valor
            $('#filterValorMin').mask('#.##0,00', {reverse: true});
            $('#filterValorMax').mask('#.##0,00', {reverse: true});
        }
    });

    // Salvar nova categoria
    async function saveServico() {
        const $form = $('#addServicoForm');
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
                url: '<?= site_url('/servico') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Serviço cadastrado com sucesso!');
                // hide add modal via Bootstrap API
                const addEl = document.getElementById('addServicoModal');
                try {
                    bootstrap.Modal.getOrCreateInstance(addEl).hide();
                } catch (e) {
                    /* ignore */ }
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadServicos();
            } else {
                showAlert('error', response.message || 'Erro ao cadastrar serviço');
            }
        } catch (error) {
            console.error('Erro:', error);
            // tenta ler mensagens de validação
            if (error && error.responseJSON && error.responseJSON.messages) {
                showFieldErrors(error.responseJSON.messages, 'add');
            } else {
                showAlert('error', 'Erro ao cadastrar serviço');
            }
        }
    }

    // Atualizar garantia
    async function updateServico() {
        const $form = $('#editServicoForm');
        // Temporarily disable HTML5 datetime-local validation for the data_garantia field
        // limpar mensagens anteriores
        clearFieldErrors('edit');

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        const servicoId = data.id;
        if (!servicoId) {
            showAlert('error', 'ID do serviço não informado.');
            return;
        }

        const $btn = $('#updateServicoBtn');
        $btn.prop('disabled', true);

        try {
            const response = await $.ajax({
                url: `<?= site_url('/servico/') ?>${servicoId}`,
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data)
            });

            showAlert('success', (response && response.message) ? response.message : 'Serviço atualizado com sucesso!');
            // hide edit modal via Bootstrap API
            const editEl = document.getElementById('editServicoModal');
            try {
                bootstrap.Modal.getOrCreateInstance(editEl).hide();
            } catch (e) {
                /* ignore */ }
            $form.removeClass('was-validated');
            await loadServicos();
        } catch (error) {
            console.error('Erro:', error);
            const respJson = (error && error.responseJSON) ? error.responseJSON : null;
            // If server returned validation messages
            if (respJson && respJson.messages) {
                showFieldErrors(respJson.messages, 'edit');
            } else if (respJson && respJson.message) {
                showAlert('error', respJson.message);
            } else if (error && error.responseText) {
                try {
                    const parsed = JSON.parse(error.responseText);
                    if (parsed && parsed.messages) {
                        showFieldErrors(parsed.messages, 'edit');
                    } else if (parsed && parsed.message) {
                        showAlert('error', parsed.message);
                    } else {
                        showAlert('error', 'Erro ao atualizar serviço');
                    }
                } catch (e) {
                    showAlert('error', 'Erro ao atualizar serviço');
                }
            } else {
                showAlert('error', 'Erro ao atualizar serviço');
            }
        } finally {
            $btn.prop('disabled', false);
        }
    }

    // Limpa mensagens de erro de campos em modais (tipo: 'add' ou 'edit')
    function clearFieldErrors(type) {
        if (type === 'add') {
            $('#addServicoForm').find('.is-invalid').removeClass('is-invalid');
            $('#addServicoForm').find('[id$="-error"]').text('').hide();
        } else if (type === 'edit') {
        $('#editServicoForm').find('.is-invalid').removeClass('is-invalid');
        $('#editServicoForm').find('[id$="-error"]').text('').hide();
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
            }
            ,
            // Serviço mappings
            's1_codigo_servico': {
                add: '#servico-codigo',
                edit: '#edit-servico-codigo',
                errorAdd: '#add-servico-codigo-error',
                errorEdit: '#edit-servico-codigo-error'
            },
            's1_nome_servico': {
                add: '#servico-nome',
                edit: '#edit-servico-nome',
                errorAdd: '#add-servico-nome-error',
                errorEdit: '#edit-servico-nome-error'
            },
            's1_descricao': {
                add: '#servico-descricao',
                edit: '#edit-servico-descricao',
                errorAdd: '#add-servico-descricao-error',
                errorEdit: '#edit-servico-descricao-error'
            },
            's1_valor': {
                add: '#servico-valor',
                edit: '#edit-servico-valor',
                errorAdd: '#add-servico-valor-error',
                errorEdit: '#edit-servico-valor-error'
            },
            's1_tempo_medio': {
                add: '#servico-tempo_medio',
                edit: '#edit-servico-tempo_medio',
                errorAdd: '#add-servico-tempo_medio-error',
                errorEdit: '#edit-servico-tempo_medio-error'
            },
            's1_categoria': {
                add: '#servico-categoria',
                edit: '#edit-servico-categoria',
                errorAdd: '#add-servico-categoria-error',
                errorEdit: '#edit-servico-categoria-error'
            },
            's1_garantia': {
                add: '#servico-garantia',
                edit: '#edit-servico-garantia',
                errorAdd: '#add-servico-garantia-error',
                errorEdit: '#edit-servico-garantia-error'
            },
            'status': {
                add: '#servico-status',
                edit: '#edit-servico-status',
                errorAdd: '#add-servico-status-error',
                errorEdit: '#edit-servico-status-error'
            },
            'codigo': {
                add: '#servico-codigo',
                edit: '#edit-servico-codigo'
            },
            'nome': {
                add: '#servico-nome',
                edit: '#edit-servico-nome'
            },
            'descricao': {
                add: '#servico-descricao',
                edit: '#edit-servico-descricao'
            },
            'valor': {
                add: '#servico-valor',
                edit: '#edit-servico-valor'
            }
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
    async function deleteServico(id, name) {
        Swal.fire({
            title: `Tem certeza que deseja excluir o serviço "${name}"?`,
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
                        url: `<?= site_url('/servico/') ?>${id}`,
                        method: 'DELETE',
                        dataType: 'json'
                    });

                    showAlert('success', (response && response.message) ? response.message : 'Serviço excluído com sucesso!');
                    await loadServicos();
                } catch (error) {
                    console.error('Erro:', error);
                    const respJson = (error && error.responseJSON) ? error.responseJSON : null;
                    if (respJson && respJson.message) {
                        showAlert('error', respJson.message);
                    } else {
                        showAlert('error', 'Erro ao excluir serviço');
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
    $('#addServicoModal').on('hidden.bs.modal', function() {
        const $form = $('#addServicoForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    $('#editServicoModal').on('hidden.bs.modal', function() {
        $('#editServicoForm').removeClass('was-validated');
    });
</script>

<?= $this->endSection() ?>