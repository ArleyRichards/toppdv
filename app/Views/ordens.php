<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Garantias</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todas as garantias cadastradas no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addgarantiaModal">
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
                <div class="col-lg-4 col-md-6">
                    <label for="filterName" class="form-label">Nome da Garantia</label>
                    <input type="text" class="form-control" id="filterName" placeholder="Digite o nome da garantia...">
                </div>
                <div class="col-lg-2 col-md-2 d-flex align-items-end">
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
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Garantias Cadastradas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome da Garantia</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="garantiasTableBody">
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

<!-- Modal de cadastro de garantia -->
<div class="modal fade" id="addgarantiaModal" tabindex="-1" aria-labelledby="addgarantiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addgarantiaModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Nova Garantia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addGarantiaForm" class="needs-validation" novalidate>
                    <?php if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } ?>
                    <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="action" value="add_garantia">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="garantia-nome" class="form-label">Nome da Garantia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="garantia-nome" name="nome" required>
                            <div class="invalid-feedback">Informe o nome da garantia.</div>
                        </div>
                        <div class="col-md-3">
                            <label for="garantia-data" class="form-label">Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="garantia-data" name="data" required value="<?= date('Y-m-d'); ?>">
                            <div class="invalid-feedback">Informe a data.</div>
                        </div>
                        <div class="col-md-3">
                            <label for="garantia-data-garantia" class="form-label">Data da Garantia</label>
                            <input type="datetime-local" class="form-control" id="garantia-data-garantia" name="data_garantia" value="<?= date('Y-m-d\TH:i'); ?>">
                            <div class="invalid-feedback" id="add-garantia-data-garantia-error"></div>
                        </div>
                        <div class="col-md-12">
                            <label for="garantia-observacao" class="form-label">Observação</label>
                            <textarea class="form-control" id="garantia-observacao" name="observacao" rows="2"></textarea>
                            <div class="invalid-feedback" id="add-garantia-observacao-error"></div>
                        </div>
                        <div class="col-md-12">
                            <label for="garantia-descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="garantia-descricao" name="descricao" rows="3" required></textarea>
                            <div class="invalid-feedback">A Descrição da garantia deve ter no mínimo 10 caracteres.</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveGarantiaBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Garantia
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
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script>
    // Variáveis globais
    let garantiasData = [];
    let filteredGarantias = [];
    let currentPage = 1; //PÁGINA ATUAL
    let itemsPerPage = 10; //ITENS POR PÁGINA  

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        loadGarantias();
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
    // Filtros: nome
    $('#filterName').on('input', applyFilters);
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
                const viewModalEl = document.getElementById('garantiaModal');
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
    $(document).on('click', '#saveGarantiaBtn', function(e) {
        e.preventDefault();
        saveGarantia();
    });

    $(document).on('click', '#updateGarantiaBtn', function(e) {
        e.preventDefault();
        updateGarantia();
    });

    // Carregar dados das categorias
    async function loadGarantias() {
        try {
            const response = await $.get('<?= site_url('/garantias/list') ?>');

            if (Array.isArray(response)) {
                garantiasData = response.map(g => ({
                    id: g.g1_id,
                    nome: g.g1_nome,
                    data: g.g1_data,
                    descricao: g.g1_descricao,
                    observacao: g.g1_observacao,
                    data_garantia: g.g1_data_garantia,
                }));

                filteredGarantias = [...garantiasData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar garantias');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados das garantias');
        }
    }

    // Aplicar filtros: nome e intervalo de comissão
    function applyFilters() {
        const nameFilter = ($('#filterName').val() || '').toLowerCase();

        filteredGarantias = garantiasData.filter(g => {
            return !nameFilter || (g.nome && g.nome.toLowerCase().includes(nameFilter));
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
    $('#filterName').val('');

        filteredGarantias = [...garantiasData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredGarantias.slice(startIndex, endIndex);

        const $tbody = $('#garantiasTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="2" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhuma garantia encontrada
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(garantia => {
                const row = createTableRow(garantia);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    function createTableRow(garantia) {
        const id = garantia.id || '';
        const nome = garantia.nome || '-';

        return `
            <tr>
                <td>${nome}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewGarantia(${id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editGarantia(${id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteGarantia(${id}, '${String(nome).replace(/'/g, "\\'")}')" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
        const total = filteredGarantias.length;
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
        const totalPages = Math.ceil(filteredGarantias.length / itemsPerPage);
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
        const totalPages = Math.ceil(filteredGarantias.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar categoria
    async function viewGarantia(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/garantias/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                showGarantiaDetails(response);
            } else {
                showAlert('error', 'Erro ao carregar dados da garantia');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da garantia');
        }
    }

    // Mostrar detalhes da categoria
    function showGarantiaDetails(garantia) {
        $('#garantiaModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary">Dados da Garantia</h6>
                    <hr>
                </div>
                <div class="col-12">
                    <strong>Nome:</strong><br>
                    ${garantia.g1_nome || '-'}
                </div>
                <div class="col-12">
                    <strong>Data:</strong><br>
                    ${garantia.g1_data || '-'}
                </div>
                <div class="col-12">
                    <strong>Observação:</strong><br>
                    ${garantia.g1_observacao || '-'}
                </div>
                <div class="col-12">
                    <strong>Descrição:</strong><br>
                    ${garantia.g1_descricao || '-'}
                </div>
            </div>
        `);

        $('#editCategoryBtn').data('category-id', garantia.g1_id);

        // Show view modal using element + Bootstrap API (robust across setups)
        const viewEl = document.getElementById('garantiaModal');
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
                url: `<?= site_url('/garantias/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                fillEditForm(response);
                // Use Bootstrap API to get/create modal instance and show it
                const editEl = document.getElementById('editgarantiaModal');
                try {
                    // prefer explicit constructor for compatibility
                    new bootstrap.Modal(editEl).show();
                } catch (e) {
                    const editModalInstance = bootstrap.Modal.getOrCreateInstance(editEl);
                    editModalInstance.show();
                }
            } else {
                showAlert('error', 'Erro ao carregar dados da garantia');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da garantia');
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

    // Salvar nova categoria
    async function saveGarantia() {
        const $form = $('#addGarantiaForm');
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
                url: '<?= site_url('/garantias') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Garantia cadastrada com sucesso!');
                // hide add modal via Bootstrap API
                const addEl = document.getElementById('addgarantiaModal');
                try {
                    bootstrap.Modal.getOrCreateInstance(addEl).hide();
                } catch (e) {
                    /* ignore */ }
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadGarantias();
            } else {
                showAlert('error', response.message || 'Erro ao cadastrar garantia');
            }
        } catch (error) {
            console.error('Erro:', error);
            // tenta ler mensagens de validação
            if (error && error.responseJSON && error.responseJSON.messages) {
                showFieldErrors(error.responseJSON.messages, 'add');
            } else {
                showAlert('error', 'Erro ao cadastrar garantia');
            }
        }
    }

    // Atualizar garantia
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
                url: `<?= site_url('/garantias/') ?>${garantiaId}`,
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
                await loadGarantias();
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
            $('#addGarantiaForm').find('.is-invalid').removeClass('is-invalid');
            $('#addGarantiaForm').find('[id$="-error"]').text('').hide();
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
                        url: `<?= site_url('/garantias/') ?>${id}`,
                        method: 'DELETE'
                    });

                    if (response) {
                        showAlert('success', 'Garantia excluída com sucesso!');
                        await loadGarantias();
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
    $('#addgarantiaModal').on('hidden.bs.modal', function() {
        const $form = $('#addGarantiaForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    $('#editgarantiaModal').on('hidden.bs.modal', function() {
        $('#editGarantiaForm').removeClass('was-validated');
    });
</script>

<?= $this->endSection() ?>