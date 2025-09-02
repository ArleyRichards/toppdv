<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Categorias</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todas as categorias cadastradas no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
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
                        <label for="filterName" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" id="filterName" placeholder="Digite o nome da categoria...">
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label for="filterCommissionMin" class="form-label">Comissão mínima (%)</label>
                        <input type="number" class="form-control" id="filterCommissionMin" placeholder="0" min="0" step="0.01">
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label for="filterCommissionMax" class="form-label">Comissão máxima (%)</label>
                        <input type="number" class="form-control" id="filterCommissionMax" placeholder="100" min="0" step="0.01">
                    </div>
                    <div class="col-lg-2 col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                            <i class="fa-solid fa-eraser"></i>
                        </button>
                    </div>
                </div>
        </div>
    </div>

    <!-- Tabela de categorias -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Categorias Cadastradas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Comissão</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
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

<!-- Modal de visualização de categoria -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel"><i class="fa-solid fa-eye text-primary me-2"></i>Detalhes da Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="categoryModalBody">
                <!-- preenchido por JS -->
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

<!-- Modal de cadastro de categoria -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel"><i class="fa-solid fa-plus text-success me-2"></i>Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Nome da Categoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category-name" name="c1_categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="category-comissao" class="form-label">Comissão (%)</label>
                        <input type="number" class="form-control" id="category-comissao" name="c1_comissao" step="0.01" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="saveCategoryBtn">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de edição de categoria -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel"><i class="fa-solid fa-edit text-warning me-2"></i>Editar Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit-category-id" name="c1_id">
                    <div class="mb-3">
                        <label for="edit-category-name" class="form-label">Nome da Categoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-category-name" name="c1_categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-category-comissao" class="form-label">Comissão (%)</label>
                        <input type="number" class="form-control" id="edit-category-comissao" name="c1_comissao" step="0.01" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateCategoryBtn">Atualizar</button>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script>
    // Variáveis globais
    let categoriesData = [];
    let filteredCategories = [];
    let currentPage = 1; //PÁGINA ATUAL
    let itemsPerPage = 10; //ITENS POR PÁGINA  

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        loadCategories();
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
    // Filtros: nome e comissão
    $('#filterName, #filterCommissionMin, #filterCommissionMax').on('input', applyFilters);
    $('#clearFilters').on('click', clearFilters);

        // Paginação
        $('#itemsPerPage').on('change', function() {

            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });

        $('#saveCategoryBtn').on('click', saveClient);
        $('#updateCategoryBtn').on('click', updateClient);
        $('#editCategoryBtn').on('click', function() {
            const categoryId = $(this).data('category-id');
            if (categoryId) {
                openEditModal(categoryId);
            }
        });
    } 

    // Carregar dados das categorias
    async function loadCategories() {
        try {
            const response = await $.get('<?= site_url('/categorias/list') ?>');

            if (Array.isArray(response)) {
                categoriesData = response.map(category => ({
                    id: category.c1_id,
                    nome: category.c1_categoria,
                    comissao: category.c1_comissao,
                }));

                filteredCategories = [...categoriesData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar categorias');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados das categorias');
        }
    }

    // Aplicar filtros: nome e intervalo de comissão
    function applyFilters() {
        const nameFilter = ($('#filterName').val() || '').toLowerCase();
        const min = parseFloat($('#filterCommissionMin').val());
        const max = parseFloat($('#filterCommissionMax').val());

        filteredCategories = categoriesData.filter(category => {
            const nomeOk = !nameFilter || (category.nome && category.nome.toLowerCase().includes(nameFilter));

            let comissaoVal = null;
            if (category.comissao !== null && category.comissao !== undefined && category.comissao !== '') {
                comissaoVal = parseFloat(category.comissao);
            }

            const minOk = isNaN(min) || comissaoVal === null || comissaoVal >= min;
            const maxOk = isNaN(max) || comissaoVal === null || comissaoVal <= max;

            return nomeOk && minOk && maxOk;
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterName, #filterCommissionMin, #filterCommissionMax').val('');

        filteredCategories = [...categoriesData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredCategories.slice(startIndex, endIndex);

        const $tbody = $('#categoriesTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhuma categoria encontrada
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(category => {
                const row = createTableRow(category);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    function createTableRow(category) {
        // category object created in loadCategories: { id, nome, comissao }
        const id = category.id || '';
        const nome = category.nome || '-';
        const comissao = category.comissao || '-';

        return `
            <tr>
                <td>${nome}</td>
                <td>${comissao} %</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewClient(${id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editClient(${id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteClient(${id}, '${String(nome).replace(/'/g, "\\'")}')" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
        const total = filteredCategories.length;
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
        const totalPages = Math.ceil(filteredCategories.length / itemsPerPage);
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
        const totalPages = Math.ceil(filteredCategories.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar categoria
    async function viewClient(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/categorias/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                showCategoryDetails(response);
            } else {
                showAlert('error', 'Erro ao carregar dados da categoria');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da categoria');
        }
    }

    // Mostrar detalhes da categoria
    function showCategoryDetails(category) {
        $('#categoryModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary">Dados da Categoria</h6>
                    <hr>
                </div>
                <div class="col-12">
                    <strong>Nome:</strong><br>
                    ${category.c1_categoria}
                </div>
                <div class="col-12">
                    <strong>Comissão:</strong><br>
                    ${category.c1_comissao || '-'}
                </div>
            </div>
        `);

        $('#editCategoryBtn').data('category-id', category.c1_id);
        new bootstrap.Modal('#categoryModal').show();
    }

    // Editar categoria
    function editClient(id) {
        openEditModal(id);
    }

    // Abrir modal de edição de categoria
    async function openEditModal(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/categorias/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                fillEditForm(response);
                new bootstrap.Modal('#editCategoryModal').show();
            } else {
                showAlert('error', 'Erro ao carregar dados da categoria');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados da categoria');
        }
    }

    // Preencher formulário de edição de categoria
    function fillEditForm(category) {
        $('#edit-category-id').val(category.c1_id);
        $('#edit-category-name').val(category.c1_categoria);
        $('#edit-category-comissao').val(category.c1_comissao || '');
    }

    // Salvar nova categoria
    async function saveClient() {
        const $form = $('#addCategoryForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        try {
            const response = await $.ajax({
                url: '<?= site_url('/categorias') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Categoria cadastrada com sucesso!');
                $('#addCategoryModal').modal('hide');
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadCategories();
            } else {
                showAlert('error', response.message || 'Erro ao cadastrar categoria');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao cadastrar categoria');
        }
    }

    // Atualizar categoria
    async function updateClient() {
        const $form = $('#editCategoryForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        const categoryId = data.c1_id;

        try {
            const response = await $.ajax({
                url: `<?= site_url('/categorias/') ?>${categoryId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Categoria atualizada com sucesso!');
                $('#editCategoryModal').modal('hide');
                $form.removeClass('was-validated');
                await loadCategories();
            } else {
                showAlert('error', response.message || 'Erro ao atualizar categoria');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao atualizar categoria');
        }
    }

    // Excluir categoria
    async function deleteClient(id, name) {
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
                        url: `<?= site_url('/categorias/') ?>${id}`,
                        method: 'DELETE'
                    });

                    if (response) {
                        showAlert('success', 'Categoria excluída com sucesso!');
                        await loadCategories();
                    } else {
                        showAlert('error', response.message || 'Erro ao excluir categoria');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    showAlert('error', 'Erro ao excluir categoria');
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
    $('#addCategoryModal').on('hidden.bs.modal', function() {
        const $form = $('#addCategoryForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    $('#editCategoryModal').on('hidden.bs.modal', function() {
        $('#editCategoryForm').removeClass('was-validated');
    });
</script>

<?= $this->endSection() ?>