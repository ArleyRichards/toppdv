<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Usuários</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todos os usuários do sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fa-solid fa-plus me-1"></i> Novo
                </button>
                <a href="<?= site_url('cadastro-usuario') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-external-link me-1"></i> Cadastro Antigo
                </a>
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
                <div class="col-lg-3 col-md-4">
                    <label for="filterName" class="form-label">Nome do Usuário</label>
                    <input type="text" class="form-control" id="filterName" placeholder="Digite o nome do usuário...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterCpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="filterCpf" placeholder="000.000.000-00">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterEmail" class="form-label">E-mail</label>
                    <input type="text" class="form-control" id="filterEmail" placeholder="E-mail...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterUsuario" class="form-label">Usuário (login)</label>
                    <input type="text" class="form-control" id="filterUsuario" placeholder="Login do usuário...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterPermissao" class="form-label">Permissão</label>
                    <select class="form-select" id="filterPermissao">
                        <option value="">Todas</option>
                        <option value="administrador">Administrador</option>
                        <option value="venda">Venda</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de usuários -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Usuários Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Usuário (login)</th>
                            <th>Permissão</th>
                            <th>Último Acesso</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
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
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes do Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="userModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editUserBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Usuário
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de usuário -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Novo Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Dados Pessoais -->
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados Pessoais</h6>
                            <hr>
                        </div>

                        <!-- Nome -->
                        <div class="col-md-6">
                            <label for="user-name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user-name" name="u1_nome" required>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-3">
                            <label for="user-cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user-cpf" name="u1_cpf" required maxlength="14" placeholder="000.000.000-00">
                        </div>

                        <!-- Usuário (login) -->
                        <div class="col-md-3">
                            <label for="user-username" class="form-label">Usuário (login) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user-username" name="u1_usuario_acesso" required maxlength="100" placeholder="login">
                        </div>

                        <!-- Campos utilizados pelo backend (u1_*) -->
                        <!-- E-mail -->
                        <div class="col-md-6">
                            <label for="user-email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="user-email" name="u1_email">
                        </div>

                        <!-- Senha -->
                        <div class="col-md-3">
                            <label for="user-password" class="form-label">Senha <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="user-password" name="u1_senha_usuario" required minlength="6" placeholder="Senha">
                        </div>

                        <!-- Permissão -->
                        <div class="col-md-3">
                            <label for="user-permissao" class="form-label">Permissão</label>
                            <select class="form-select" id="user-permissao" name="u1_tipo_permissao">                      
                                <option value="venda">Venda</option>                      
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveUserBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Usuário
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Usuário -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="u1_id" id="edit-user-id">

                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados Pessoais</h6>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label for="edit-user-name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-user-name" name="u1_nome" required>
                        </div>

                        <div class="col-md-3">
                            <label for="edit-user-cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-user-cpf" name="u1_cpf" required maxlength="14" placeholder="000.000.000-00">
                        </div>

                        <div class="col-md-3">
                            <label for="edit-user-username" class="form-label">Usuário (login) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-user-username" name="u1_usuario_acesso" required maxlength="100" placeholder="login">
                        </div>

                        <div class="col-md-6">
                            <label for="edit-user-email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="edit-user-email" name="u1_email">
                        </div>

                        <div class="col-md-3">
                            <label for="edit-user-password" class="form-label">Senha (deixe em branco para manter)</label>
                            <input type="password" class="form-control" id="edit-user-password" name="u1_senha_usuario" minlength="6" placeholder="Nova senha">
                        </div>

                        <div class="col-md-3">
                            <label for="edit-user-permissao" class="form-label">Permissão</label>
                            <select class="form-select" id="edit-user-permissao" name="u1_tipo_permissao">                      
                                <option value="venda">Venda</option>                      
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="updateUserBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Usuário
                </button>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script>
    // Variáveis globais
    let usersData = [];
    let filteredUsers = [];
    let currentPage = 1;//PÁGINA ATUAL
    let itemsPerPage = 10;//ITENS POR PÁGINA

    // Configurações de máscaras
    const masks = {
        cpf: '000.000.000-00',
        phone: '(00) 0000-0000',
        mobile: '(00) 00000-0000',
        cep: '00000-000'
    };

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        loadUsers();
        setupEventListeners();
        setupMasks();
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

    // Clear validation errors when the add modal is hidden
    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#user-cpf, #user-email, #user-name, #user-username, #user-password, #user-permissao').removeClass('is-invalid');
        $('#addUserForm .invalid-feedback.custom').remove();
        $('#addUserForm')[0].reset();
    });

    // Configurar event listeners com jQuery
    /**
     * Carrega os dados dos usuários via AJAX GET.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    function setupEventListeners() {
    // Filtros (ajustados para usuários)
    $('#filterName, #filterCpf, #filterEmail, #filterUsuario').on('input', applyFilters);
    $('#filterPermissao').on('change', applyFilters);
        $('#clearFilters').on('click', clearFilters);

        // Paginação
        $('#itemsPerPage').on('change', function() {

    /**
     * Aplica os filtros nos dados dos usuários.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();

    /**
     * Limpa todos os filtros aplicados.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        });

        // Botões de ação

    /**
     * Renderiza a tabela de usuários paginada.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    $('#saveUserBtn').on('click', saveUser);
    $('#updateUserBtn').on('click', updateUser);
    $('#editUserBtn').on('click', function() {

    /**
     * Cria uma linha da tabela para um usuário.
     * @param {Object} user - Dados do usuário
     * @returns {string} HTML da linha
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            const userId = $(this).data('user-id');
            if (userId) {
                openEditModalUser(userId);

    /**
     * Formata o CPF para exibição.
     * @param {string} cpf
     * @returns {string}
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            }
        });


    /**
     * Formata o telefone para exibição.
     * @param {string} telefone
     * @returns {string}
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        // Calcular idade automaticamente
        $('#edit-user-birth-date').on('change', function() {
            const isEdit = true;

    /**
     * Retorna o badge HTML para a situação do usuário.
     * @param {string} situacao
     * @returns {string}
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            calculateAge(isEdit);
        });


    /**
     * Atualiza as informações de paginação.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        // Busca CEP
        $('#edit-user-cep').on('blur', function() {
            const isEdit = true;

    /**
     * Renderiza os controles de paginação.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            searchCep(isEdit);
        });
    }

    /**
     * Altera a página atual da tabela.
     * @param {number} page
     * @returns {boolean}
     * @author Arley Richards <arleyrichards@gmail.com>
     */

    // Configurar máscaras nos campos
    function setupMasks() {
        // Use jQuery Mask plugin for CPF fields in the add/edit modals and the filter
        // Requires jquery.mask plugin to be loaded on the page
        if ($.fn && $.fn.mask) {
            $('#user-cpf').mask(masks.cpf);
            $('#edit-user-cpf').mask(masks.cpf);
            $('#filterCpf').mask(masks.cpf);
        }
    }

    /**
     * Atualiza os dados de um usuário via AJAX PUT.
     * @author Arley Richards <arleyrichards@gmail.com>
     */

    // Note: jQuery Mask plugin is used for CPF fields; custom mask helpers were removed.

    // Calcular idade (aplica somente ao formulário de edição)
    function calculateAge(isEdit = false) {
        if (!isEdit) return;
        const birthDate = $('#edit-user-birth-date').val();
        if (birthDate) {
            const age = getAge(birthDate);
            $('#edit-user-age').val(age);
        }
    }

    function getAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }

        return age;
    }

    // Buscar CEP (aplica somente ao formulário de edição)
    async function searchCep(isEdit = false) {
        if (!isEdit) return;
        const cep = $('#edit-user-cep').val().replace(/\D/g, '');
        if (cep.length === 8) {
            await fillAddressFromCep(cep, isEdit);
        }
    }

    async function fillAddressFromCep(cep, isEdit) {
        try {
            // ViaCEP lookup
            const response = await $.get(`https://viacep.com.br/ws/${cep}/json/`);
            
            if (response) {
                const prefix = isEdit ? 'edit-' : '';
                $('#' + prefix + 'user-address').val(response.logradouro || '');
                $('#' + prefix + 'user-neighborhood').val(response.bairro || '');
                $('#' + prefix + 'user-city').val(response.localidade || '');
                $('#' + prefix + 'user-uf').val(response.uf || '');
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        }
    }

    // Carregar usuários do backend (/usuarios/list)
    async function loadUsers() {
        try {
            const response = await $.get('<?= site_url('/usuarios/list') ?>');

            if (Array.isArray(response)) {
                // Mapear campos u1_* para o formato utilizado pela UI
                usersData = response.map(user => ({
                    id: user.u1_id,
                    nome: user.u1_nome || user.u1_usuario_acesso || '',
                    cpf: user.u1_cpf || '',
                    email: user.u1_email || '',
                    usuario: user.u1_usuario_acesso || '',
                    permissao: user.u1_tipo_permissao || '',
                    ultimo_acesso: user.u1_data_ultimo_acesso || user.u1_updated_at || user.u1_created_at || null,
                    data_criacao: user.u1_created_at || null
                }));

                filteredUsers = [...usersData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar usuários');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados dos usuários');
        }
    }

    // Compatibilidade: wrapper antigo
    function loadUsersLegacy() { return loadUsers(); }

    // Aplicar filtros
    function applyFilters() {
        const filters = {
            name: $('#filterName').val().toLowerCase(),
            cpf: $('#filterCpf').val(),
            email: $('#filterEmail').val().toLowerCase(),
            usuario: $('#filterUsuario').val().toLowerCase(),
            permissao: $('#filterPermissao').val()
        };

        filteredUsers = usersData.filter(user => {
            return (!filters.name || user.nome.toLowerCase().includes(filters.name)) &&
                (!filters.cpf || user.cpf.includes(filters.cpf)) &&
                (!filters.email || (user.email && user.email.toLowerCase().includes(filters.email))) &&
                (!filters.usuario || (user.usuario && user.usuario.toLowerCase().includes(filters.usuario))) &&
                (!filters.permissao || user.permissao === filters.permissao);
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterName, #filterCpf, #filterEmail, #filterUsuario').val('');
        $('#filterPermissao').val('');

    filteredUsers = [...usersData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredUsers.slice(startIndex, endIndex);

        const $tbody = $('#usersTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhum usuário encontrado
                    </td>
                </tr>
            `);
        } else {
        pageData.forEach(user => {
            const row = createTableRow(user);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela para usuários
    function createTableRow(user) {
        const ultimoAcesso = user.ultimo_acesso ? new Date(user.ultimo_acesso).toLocaleString('pt-BR') : '-';
        return `
            <tr>
                <td>${user.nome}</td>
                <td>${formatarCpf(user.cpf)}</td>
                <td>${user.email || '-'}</td>
                <td>${user.usuario || '-'}</td>
                <td>${user.permissao || '-'}</td>
                <td>${ultimoAcesso}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewUser(${user.id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editUser(${user.id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteUser(${user.id}, '${(user.nome || '').replace(/'/g, "\\'")}')" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Formatar CPF para exibição
    function formatarCpf(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.length === 11) {
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }
        return cpf;
    }

    // Formatar telefone para exibição
    function formatarTelefone(telefone) {
        telefone = telefone.replace(/\D/g, '');
        if (telefone.length === 11) {
            return telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (telefone.length === 10) {
            return telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
        return telefone;
    }

    // Retorna badge HTML para situação
    function getBadgeSituacao(situacao) {
        const classes = {
            'Ativo': 'bg-success',
            'Inativo': 'bg-secondary',
            'Pendente': 'bg-warning',
            'Bloqueado': 'bg-danger',
            'Adimplente': 'bg-success',
            'Inadimplente': 'bg-danger',
            'Atrasado': 'bg-warning',
            'Negociado': 'bg-info',
            'Ajuizado': 'bg-dark',
            'Quitado': 'bg-success'
        };

        const classe = classes[situacao] || 'bg-secondary';
        return `<span class="badge ${classe}">${situacao}</span>`;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
    const start = Math.min((currentPage - 1) * itemsPerPage + 1, filteredUsers.length);
    const end = Math.min(currentPage * itemsPerPage, filteredUsers.length);
    const total = filteredUsers.length;

        $('#paginationInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);
    }

    // Renderizar paginação
    function renderPagination() {
    const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
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
        const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar usuário
    async function viewUser(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/usuarios/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                showUserDetails(response);
            } else {
                showAlert('error', 'Erro ao carregar dados do usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do usuário');
        }
    }

    // Mostrar detalhes do usuário
    function showUserDetails(user) {
        const ultimoAcesso = user.u1_data_ultimo_acesso ? new Date(user.u1_data_ultimo_acesso).toLocaleString('pt-BR') : '-';
        const dataCadastro = user.u1_created_at ? new Date(user.u1_created_at).toLocaleDateString('pt-BR') : '-';

    $('#userModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados do Usuário</h6>
                    <hr>
                </div>
                <div class="col-md-6">
                    <strong>Nome:</strong><br>
                    ${user.u1_nome || '-'}
                </div>
                <div class="col-md-3">
                    <strong>CPF:</strong><br>
                    ${formatarCpf(user.u1_cpf || '')}
                </div>
                <div class="col-md-3">
                    <strong>Usuário (login):</strong><br>
                    ${user.u1_usuario_acesso || '-'}
                </div>
                <div class="col-md-6">
                    <strong>E-mail:</strong><br>
                    ${user.u1_email || '-'}
                </div>

                <div class="col-12 mt-4">
                    <h6 class="text-primary"><i class="fa-solid fa-info-circle me-2"></i>Informações do Sistema</h6>
                    <hr>
                </div>
                <div class="col-md-4">
                    <strong>Permissão:</strong><br>
                    ${user.u1_tipo_permissao || '-'}
                </div>
                <div class="col-md-4">
                    <strong>Último Acesso:</strong><br>
                    ${ultimoAcesso}
                </div>
                <div class="col-md-4">
                    <strong>Data de Cadastro:</strong><br>
                    ${dataCadastro}
                </div>
            </div>
        `);

    // Configurar botão de editar (usa chave user-id)
    $('#editUserBtn').data('user-id', user.u1_id);

    // Mostrar modal
    new bootstrap.Modal('#userModal').show();
    }

    // Editar usuário
    function editUser(id) {
        openEditModalUser(id);
    }

    // Abrir modal de edição
    async function openEditModalUser(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/usuarios/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                fillEditFormUser(response);
                new bootstrap.Modal('#editUserModal').show();
            } else {
                showAlert('error', 'Erro ao carregar dados do usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
                showAlert('error', 'Erro ao carregar dados do usuário');
        }
    }

    // Preencher formulário de edição
    function fillEditFormUser(user) {
    $('#edit-user-id').val(user.u1_id);
    $('#edit-user-name').val(user.u1_nome);
    $('#edit-user-cpf').val(formatarCpf(user.u1_cpf));
    $('#edit-user-username').val(user.u1_usuario_acesso || '');
    $('#edit-user-email').val(user.u1_email || '');
    $('#edit-user-password').val('');
    $('#edit-user-permissao').val(user.u1_tipo_permissao || 'venda');
    }

    // Salvar novo usuário
    async function saveUser() {
        const $form = $('#addUserForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        // Build payload only with u1_* fields expected by the backend
        const data = {
            u1_nome: $('#user-name').val(),
            u1_cpf: ($('#user-cpf').val() || '').replace(/\D/g, ''),
            u1_usuario_acesso: $('#user-username').val(),
            u1_email: $('#user-email').val(),
            u1_senha_usuario: $('#user-password').val(),
            u1_tipo_permissao: $('#user-permissao').val() || 'usuario'
        };

        try {
            const response = await $.ajax({
                url: '<?= site_url('/usuarios') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Usuário cadastrado com sucesso!');
                $('#addUserModal').modal('hide');
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadUsers();
            } else {
                showAlert('error', response.message || 'Erro ao cadastrar usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
            // Try to parse validation errors and show inline in the add modal when possible
            let fallbackMessage = 'Erro ao cadastrar usuário';
            if (error && error.responseJSON) {
                const err = error.responseJSON;
                // If model returned structured messages (field => message)
                if (err.messages && (err.messages.u1_cpf || err.messages.u1_email)) {
                    // clear previous states
                    $('#user-cpf, #user-email').removeClass('is-invalid');
                    $('#addUserForm .invalid-feedback.custom').remove();

                    if (err.messages.u1_cpf) {
                        $('#user-cpf').addClass('is-invalid');
                        $('#user-cpf').after(`<div class="invalid-feedback custom">${err.messages.u1_cpf}</div>`);
                    }
                    if (err.messages.u1_email) {
                        $('#user-email').addClass('is-invalid');
                        $('#user-email').after(`<div class="invalid-feedback custom">${err.messages.u1_email}</div>`);
                    }

                    const $firstInvalid = $('#addUserForm .is-invalid').first();
                    if ($firstInvalid.length) $firstInvalid.focus();
                    return;
                }

                // Other shapes: try common fields
                if (typeof err === 'string') fallbackMessage = err;
                else if (err.message) fallbackMessage = err.message;
                else if (err.error) fallbackMessage = err.error;
                else if (Object.keys(err).length) fallbackMessage = Object.values(err).flat().join('\n');
            }

            showAlert('error', fallbackMessage);
        }
    }

    // Atualizar usuário
    async function updateUser() {
        const $form = $('#editUserForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }
        // Build only the allowed fields
        const userId = $('#edit-user-id').val();
        const data = {
            u1_id: userId,
            u1_nome: $('#edit-user-name').val(),
            u1_cpf: ($('#edit-user-cpf').val() || '').replace(/\D/g, ''),
            u1_usuario_acesso: $('#edit-user-username').val(),
            u1_email: $('#edit-user-email').val(),
            u1_tipo_permissao: $('#edit-user-permissao').val() || 'venda'
        };

        // Include password only if provided
        const pwd = $('#edit-user-password').val();
        if (pwd && pwd.length >= 6) {
            data.u1_senha_usuario = pwd;
        }

        try {
            const response = await $.ajax({
                url: `<?= site_url('/usuarios/') ?>${userId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json'
            });

            if (response) {
                showAlert('success', 'Usuário atualizado com sucesso!');
                $('#editUserModal').modal('hide');
                $form.removeClass('was-validated');
                await loadUsers();
            } else {
                showAlert('error', response.message || 'Erro ao atualizar usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
            // Try to extract structured validation errors and show inline in the edit modal when possible
            let fallbackMessage = 'Erro ao atualizar usuário';
            if (error && error.responseJSON) {
                const err = error.responseJSON;

                if (err.messages && (err.messages.u1_cpf || err.messages.u1_email)) {
                    // clear previous states
                    $('#edit-user-cpf, #edit-user-email').removeClass('is-invalid');
                    $('#editUserForm .invalid-feedback.custom').remove();

                    if (err.messages.u1_cpf) {
                        $('#edit-user-cpf').addClass('is-invalid');
                        $('#edit-user-cpf').after(`<div class="invalid-feedback custom">${err.messages.u1_cpf}</div>`);
                    }
                    if (err.messages.u1_email) {
                        $('#edit-user-email').addClass('is-invalid');
                        $('#edit-user-email').after(`<div class="invalid-feedback custom">${err.messages.u1_email}</div>`);
                    }

                    const $firstInvalid = $('#editUserForm .is-invalid').first();
                    if ($firstInvalid.length) $firstInvalid.focus();
                    return;
                }

                if (err.message) fallbackMessage = err.message;
                else if (typeof err === 'string') fallbackMessage = err;
                else if (err.error) fallbackMessage = err.error;
            }

            showAlert('error', fallbackMessage);
        }
    }

    // Excluir usuário
    async function deleteUser(id, name) {
        Swal.fire({
            title: `Tem certeza que deseja excluir o usuário "${name}"?`,
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
                        url: `<?= site_url('/usuarios/') ?>${id}`,
                        method: 'DELETE',
                        dataType: 'json'
                    });

                    // Controller returns JSON with message on success or error
                    if (response && (response.message || response.data)) {
                        const msg = response.message || (response.data && response.data.message) || 'Usuário excluído com sucesso!';
                        showAlert('success', msg);
                        await loadUsers();
                    } else {
                        // Fallback: show generic message
                        showAlert('success', 'Usuário excluído com sucesso!');
                        await loadUsers();
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    // Try to extract message from error response
                    let message = 'Erro ao excluir usuário';
                    if (error && error.responseJSON) {
                        const err = error.responseJSON;
                        if (err.message) message = err.message;
                        else if (typeof err === 'string') message = err;
                    }
                    showAlert('error', message);
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
    $('#addUserModal').on('hidden.bs.modal', function() {
        const $form = $('#addUserForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    $('#editUserModal').on('hidden.bs.modal', function() {
        $('#editUserForm').removeClass('was-validated');
        // Clear validation UI
        $('#edit-user-cpf, #edit-user-email, #edit-user-name, #edit-user-username, #edit-user-password, #edit-user-permissao').removeClass('is-invalid');
        $('#editUserForm .invalid-feedback.custom').remove();
    });
</script>

<?= $this->endSection() ?>