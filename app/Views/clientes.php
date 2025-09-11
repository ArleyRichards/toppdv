<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Clientes</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todos os clientes cadastrados no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="fa-solid fa-plus me-1"></i> Novo
                </button>
                <a href="<?= site_url('cadastro-cliente') ?>" class="btn btn-outline-secondary btn-sm">
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
                    <label for="filterName" class="form-label">Nome do Cliente</label>
                    <input type="text" class="form-control" id="filterName" placeholder="Digite o nome do cliente...">
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
                    <label for="filterCidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="filterCidade" placeholder="Cidade...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterUf" class="form-label">UF</label>
                    <select class="form-select" id="filterUf">
                        <option value="">Todos os estados</option>
                        <option value="AC">AC</option>
                        <option value="AL">AL</option>
                        <option value="AP">AP</option>
                        <option value="AM">AM</option>
                        <option value="BA">BA</option>
                        <option value="CE">CE</option>
                        <option value="DF">DF</option>
                        <option value="ES">ES</option>
                        <option value="GO">GO</option>
                        <option value="MA">MA</option>
                        <option value="MT">MT</option>
                        <option value="MS">MS</option>
                        <option value="MG">MG</option>
                        <option value="PA">PA</option>
                        <option value="PB">PB</option>
                        <option value="PR">PR</option>
                        <option value="PE">PE</option>
                        <option value="PI">PI</option>
                        <option value="RJ">RJ</option>
                        <option value="RN">RN</option>
                        <option value="RS">RS</option>
                        <option value="RO">RO</option>
                        <option value="RR">RR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterSituacao" class="form-label">Situação</label>
                    <select class="form-select" id="filterSituacao">
                        <option value="">Todas</option>
                        <?php foreach ($situacoes as $situacao): ?>
                            <option value="<?= $situacao ?>"><?= $situacao ?></option>
                        <?php endforeach; ?>
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

    <!-- Tabela de clientes -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Clientes Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Cidade</th>
                            <th>UF</th>
                            <th>Situação</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="clientsTableBody">
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
<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes do Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="clientModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editClientBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de cliente -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Novo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Dados Pessoais -->
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados Pessoais</h6>
                            <hr>
                        </div>

                        <!-- Nome -->
                        <div class="col-md-6">
                            <label for="client-name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-name" name="c2_nome" required>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-3">
                            <label for="client-cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-cpf" name="c2_cpf" required maxlength="14" placeholder="000.000.000-00">
                        </div>

                        <!-- RG -->
                        <div class="col-md-3">
                            <label for="client-rg" class="form-label">RG</label>
                            <input type="text" class="form-control" id="client-rg" name="c2_rg" maxlength="20">
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="col-md-4">
                            <label for="client-birth-date" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="client-birth-date" name="c2_data_nascimento" required>
                        </div>

                        <!-- Idade (calculada automaticamente) -->
                        <div class="col-md-2">
                            <label for="client-age" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="client-age" name="c2_idade" readonly>
                        </div>

                        <!-- Telefone -->
                        <div class="col-md-3">
                            <label for="client-phone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="client-phone" name="c2_telefone" maxlength="15" placeholder="(00) 0000-0000">
                        </div>

                        <!-- Celular -->
                        <div class="col-md-3">
                            <label for="client-mobile" class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-mobile" name="c2_celular" required maxlength="15" placeholder="(00) 00000-0000">
                        </div>

                        <!-- E-mail -->
                        <div class="col-md-6">
                            <label for="client-email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="client-email" name="c2_email">
                        </div>

                        <!-- Endereço -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary"><i class="fa-solid fa-map-marker-alt me-2"></i>Endereço</h6>
                            <hr>
                        </div>

                        <!-- CEP -->
                        <div class="col-md-2">
                            <label for="client-cep" class="form-label">CEP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-cep" name="c2_cep" required maxlength="9" placeholder="00000-000">
                        </div>

                        <!-- Endereço -->
                        <div class="col-md-5">
                            <label for="client-address" class="form-label">Endereço <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-address" name="c2_endereco" required>
                        </div>

                        <!-- Número -->
                        <div class="col-md-2">
                            <label for="client-number" class="form-label">Número</label>
                            <input type="text" class="form-control" id="client-number" name="c2_numero" maxlength="10">
                        </div>

                        <!-- Complemento -->
                        <div class="col-md-3">
                            <label for="client-complement" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="client-complement" name="c2_complemento">
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-4">
                            <label for="client-neighborhood" class="form-label">Bairro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-neighborhood" name="c2_bairro" required>
                        </div>

                        <!-- Cidade -->
                        <div class="col-md-4">
                            <label for="client-city" class="form-label">Cidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="client-city" name="c2_cidade" required>
                        </div>

                        <!-- UF -->
                        <div class="col-md-2">
                            <label for="client-uf" class="form-label">UF <span class="text-danger">*</span></label>
                            <select class="form-select" id="client-uf" name="c2_uf" required>
                                <option value="">Selecione</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                        </div>

                        <!-- Ponto de Referência -->
                        <div class="col-md-6">
                            <label for="client-reference" class="form-label">Ponto de Referência</label>
                            <input type="text" class="form-control" id="client-reference" name="c2_ponto_referencia">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveClientBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Cliente -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="c2_id" id="edit-client-id">

                    <div class="row g-3">
                        <!-- Dados Pessoais -->
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados Pessoais</h6>
                            <hr>
                        </div>

                        <!-- Nome -->
                        <div class="col-md-6">
                            <label for="edit-client-name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-name" name="c2_nome" required>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-3">
                            <label for="edit-client-cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-cpf" name="c2_cpf" required maxlength="14" placeholder="000.000.000-00">
                        </div>

                        <!-- RG -->
                        <div class="col-md-3">
                            <label for="edit-client-rg" class="form-label">RG</label>
                            <input type="text" class="form-control" id="edit-client-rg" name="c2_rg" maxlength="20">
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="col-md-4">
                            <label for="edit-client-birth-date" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit-client-birth-date" name="c2_data_nascimento" required>
                        </div>

                        <!-- Idade (calculada automaticamente) -->
                        <div class="col-md-2">
                            <label for="edit-client-age" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="edit-client-age" name="c2_idade" readonly>
                        </div>

                        <!-- Telefone -->
                        <div class="col-md-3">
                            <label for="edit-client-phone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="edit-client-phone" name="c2_telefone" maxlength="15" placeholder="(00) 0000-0000">
                        </div>

                        <!-- Celular -->
                        <div class="col-md-3">
                            <label for="edit-client-mobile" class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-mobile" name="c2_celular" required maxlength="15" placeholder="(00) 00000-0000">
                        </div>

                        <!-- E-mail -->
                        <div class="col-md-6">
                            <label for="edit-client-email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="edit-client-email" name="c2_email">
                        </div>

                        <!-- Endereço -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary"><i class="fa-solid fa-map-marker-alt me-2"></i>Endereço</h6>
                            <hr>
                        </div>

                        <!-- CEP -->
                        <div class="col-md-2">
                            <label for="edit-client-cep" class="form-label">CEP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-cep" name="c2_cep" required maxlength="9" placeholder="00000-000">
                        </div>

                        <!-- Endereço -->
                        <div class="col-md-5">
                            <label for="edit-client-address" class="form-label">Endereço <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-address" name="c2_endereco" required>
                        </div>

                        <!-- Número -->
                        <div class="col-md-2">
                            <label for="edit-client-number" class="form-label">Número</label>
                            <input type="text" class="form-control" id="edit-client-number" name="c2_numero" maxlength="10">
                        </div>

                        <!-- Complemento -->
                        <div class="col-md-3">
                            <label for="edit-client-complement" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="edit-client-complement" name="c2_complemento">
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-4">
                            <label for="edit-client-neighborhood" class="form-label">Bairro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-neighborhood" name="c2_bairro" required>
                        </div>

                        <!-- Cidade -->
                        <div class="col-md-4">
                            <label for="edit-client-city" class="form-label">Cidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-client-city" name="c2_cidade" required>
                        </div>

                        <!-- UF -->
                        <div class="col-md-2">
                            <label for="edit-client-uf" class="form-label">UF <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-client-uf" name="c2_uf" required>
                                <option value="">Selecione</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                        </div>

                        <!-- Ponto de Referência -->
                        <div class="col-md-6">
                            <label for="edit-client-reference" class="form-label">Ponto de Referência</label>
                            <input type="text" class="form-control" id="edit-client-reference" name="c2_ponto_referencia">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="updateClientBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Cliente
                </button>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script>
    // Variáveis globais
    let clientsData = [];
    let filteredClients = [];
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
        loadClients();
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

    // Configurar event listeners com jQuery
    /**
     * Carrega os dados dos clientes via AJAX GET.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    function setupEventListeners() {
        // Filtros
        $('#filterName, #filterCpf, #filterEmail, #filterCidade').on('input', applyFilters);
        $('#filterUf, #filterSituacao').on('change', applyFilters);
        $('#clearFilters').on('click', clearFilters);

        // Paginação
        $('#itemsPerPage').on('change', function() {

    /**
     * Aplica os filtros nos dados dos clientes.
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
     * Renderiza a tabela de clientes paginada.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        $('#saveClientBtn').on('click', saveClient);
        $('#updateClientBtn').on('click', updateClient);
        $('#editClientBtn').on('click', function() {

    /**
     * Cria uma linha da tabela para um cliente.
     * @param {Object} client - Dados do cliente
     * @returns {string} HTML da linha
     * @author Arley Richards <arleyrichards@gmail.com>
     */
            const clientId = $(this).data('client-id');
            if (clientId) {
                openEditModal(clientId);

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
        $('#client-birth-date, #edit-client-birth-date').on('change', function() {
            const isEdit = $(this).attr('id').includes('edit');

    /**
     * Retorna o badge HTML para a situação do cliente.
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
        $('#client-cep, #edit-client-cep').on('blur', function() {
            const isEdit = $(this).attr('id').includes('edit');

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

    /**
     * Visualiza os detalhes de um cliente.
     * @param {number} id
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        // CPF
        applyMask('client-cpf', masks.cpf);
        applyMask('edit-client-cpf', masks.cpf);

    /**
     * Exibe os detalhes do cliente no modal.
     * @param {Object} client
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        applyMask('filterCpf', masks.cpf);

        // Telefones

    /**
     * Abre o modal de edição de cliente.
     * @param {number} id
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        applyMask('client-phone', masks.phone);
        applyMask('edit-client-phone', masks.phone);
        applyMask('client-mobile', masks.mobile);

    /**
     * Preenche o formulário de edição com os dados do cliente.
     * @param {Object} client
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        applyMask('edit-client-mobile', masks.mobile);

        // CEP

    /**
     * Salva um novo cliente via AJAX POST.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        applyMask('client-cep', masks.cep);
        applyMask('edit-client-cep', masks.cep);
    }

    /**
     * Atualiza os dados de um cliente via AJAX PUT.
     * @author Arley Richards <arleyrichards@gmail.com>
     */

    // Aplicar máscara a um campo
    function applyMask(elementId, mask) {

    /**
     * Exclui um cliente via AJAX DELETE.
     * @param {number} id
     * @param {string} name
     * @author Arley Richards <arleyrichards@gmail.com>
     */
        const $element = $('#' + elementId);
        if ($element.length) {
            $element.on('input', function(e) {

    /**
     * Exibe um alerta na tela.
     * @param {string} type
     * @param {string} message
     * @author Arley Richards <arleyrichards@gmail.com>
     */
                $(this).val(maskValue($(this).val(), mask));
            });
        }
    }

    // Função para aplicar máscara
    function maskValue(value, mask) {
        value = value.replace(/\D/g, '');
        let masked = '';
        let valueIndex = 0;

        for (let i = 0; i < mask.length && valueIndex < value.length; i++) {
            if (mask[i] === '0') {
                masked += value[valueIndex];
                valueIndex++;
            } else {
                masked += mask[i];
            }
        }

        return masked;
    }

    // Calcular idade
    function calculateAge(isEdit = false) {
        const prefix = isEdit ? 'edit-' : '';
        const birthDate = $('#' + prefix + 'client-birth-date').val();
        if (birthDate) {
            const age = getAge(birthDate);
            $('#' + prefix + 'client-age').val(age);
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

    // Buscar CEP
    async function searchCep(isEdit = false) {
        const prefix = isEdit ? 'edit-' : '';
        const cep = $('#' + prefix + 'client-cep').val().replace(/\D/g, '');
        if (cep.length === 8) {
            await fillAddressFromCep(cep, isEdit);
        }
    }

    async function fillAddressFromCep(cep, isEdit) {
        try {
            // const response = await $.get('/clientes/cep', { cep: cep });
            const response = await $.get(`https://viacep.com.br/ws/${cep}/json/`);
            
            if (response) {
                const prefix = isEdit ? 'edit-' : '';
                $('#' + prefix + 'client-address').val(response.logradouro || '');
                $('#' + prefix + 'client-neighborhood').val(response.bairro || '');
                $('#' + prefix + 'client-city').val(response.localidade || '');
                $('#' + prefix + 'client-uf').val(response.uf || '');
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        }
    }

    // Carregar dados dos clientes
    async function loadClients() {
        try {
            const response = await $.get('<?= site_url('/clientes/list') ?>');

            if (Array.isArray(response)) {
                clientsData = response.map(client => ({
                    id: client.c2_id,
                    nome: client.c2_nome,
                    cpf: client.c2_cpf,
                    idade: client.c2_idade,
                    celular: client.c2_celular,
                    email: client.c2_email,
                    cidade: client.c2_cidade,
                    uf: client.c2_uf,
                    situacao: client.c2_situacao,
                    rg: client.c2_rg,
                    data_nascimento: client.c2_data_nascimento,
                    telefone: client.c2_telefone,
                    cep: client.c2_cep,
                    endereco: client.c2_endereco,
                    numero: client.c2_numero,
                    complemento: client.c2_complemento,
                    bairro: client.c2_bairro,
                    ponto_referencia: client.c2_ponto_referencia,
                    data_criacao: client.c2_created_at
                }));

                filteredClients = [...clientsData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar clientes');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados dos clientes');
        }
    }

    // Aplicar filtros
    function applyFilters() {
        const filters = {
            name: $('#filterName').val().toLowerCase(),
            cpf: $('#filterCpf').val(),
            email: $('#filterEmail').val().toLowerCase(),
            cidade: $('#filterCidade').val().toLowerCase(),
            uf: $('#filterUf').val(),
            situacao: $('#filterSituacao').val()
        };

        filteredClients = clientsData.filter(client => {
            return (!filters.name || client.nome.toLowerCase().includes(filters.name)) &&
                (!filters.cpf || client.cpf.includes(filters.cpf)) &&
                (!filters.email || (client.email && client.email.toLowerCase().includes(filters.email))) &&
                (!filters.cidade || client.cidade.toLowerCase().includes(filters.cidade)) &&
                (!filters.uf || client.uf === filters.uf) &&
                (!filters.situacao || client.situacao === filters.situacao);
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterName, #filterCpf, #filterEmail, #filterCidade').val('');
        $('#filterUf, #filterSituacao').val('');

        filteredClients = [...clientsData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredClients.slice(startIndex, endIndex);

        const $tbody = $('#clientsTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhum cliente encontrado
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(client => {
                const row = createTableRow(client);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    function createTableRow(client) {
        return `
            <tr>
                <td>${client.nome}</td>
                <td>${formatarCpf(client.cpf)}</td>
                <td>${client.email || '-'}</td>
                <td>${client.cidade}</td>
                <td>${client.uf}</td>
                <td>${getBadgeSituacao(client.situacao)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewClient(${client.id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editClient(${client.id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteClient(${client.id}, '${client.nome.replace(/'/g, "\\'")}')" title="Excluir">
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
        const start = Math.min((currentPage - 1) * itemsPerPage + 1, filteredClients.length);
        const end = Math.min(currentPage * itemsPerPage, filteredClients.length);
        const total = filteredClients.length;

        $('#paginationInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);
    }

    // Renderizar paginação
    function renderPagination() {
        const totalPages = Math.ceil(filteredClients.length / itemsPerPage);
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
        const totalPages = Math.ceil(filteredClients.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar cliente
    async function viewClient(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/clientes/') ?>+${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                showClientDetails(response);
            } else {
                showAlert('error', 'Erro ao carregar dados do cliente');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do cliente');
        }
    }

    // Mostrar detalhes do cliente
    function showClientDetails(client) {
        const dataNascimento = client.c2_data_nascimento ?
            new Date(client.c2_data_nascimento).toLocaleDateString('pt-BR') : '-';
        const dataCadastro = client.c2_created_at ?
            new Date(client.c2_created_at).toLocaleDateString('pt-BR') : '-';

        $('#clientModalBody').html(`
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-primary"><i class="fa-solid fa-user me-2"></i>Dados Pessoais</h6>
                    <hr>
                </div>
                <div class="col-md-6">
                    <strong>Nome:</strong><br>
                    ${client.c2_nome}
                </div>
                <div class="col-md-3">
                    <strong>CPF:</strong><br>
                    ${formatarCpf(client.c2_cpf)}
                </div>
                <div class="col-md-3">
                    <strong>RG:</strong><br>
                    ${client.c2_rg || '-'}
                </div>
                <div class="col-md-4">
                    <strong>Data de Nascimento:</strong><br>
                    ${dataNascimento}
                </div>
                <div class="col-md-2">
                    <strong>Idade:</strong><br>
                    ${client.c2_idade} anos
                </div>
                <div class="col-md-3">
                    <strong>Telefone:</strong><br>
                    ${formatarTelefone(client.c2_telefone) || '-'}
                </div>
                <div class="col-md-3">
                    <strong>Celular:</strong><br>
                    ${formatarTelefone(client.c2_celular)}
                </div>
                <div class="col-md-6">
                    <strong>E-mail:</strong><br>
                    ${client.c2_email || '-'}
                </div>
                
                <div class="col-12 mt-4">
                    <h6 class="text-primary"><i class="fa-solid fa-map-marker-alt me-2"></i>Endereço</h6>
                    <hr>
                </div>
                <div class="col-md-2">
                    <strong>CEP:</strong><br>
                    ${client.c2_cep}
                </div>
                <div class="col-md-6">
                    <strong>Endereço:</strong><br>
                    ${client.c2_endereco}${client.c2_numero ? ', ' + client.c2_numero : ''}
                </div>
                <div class="col-md-4">
                    <strong>Complemento:</strong><br>
                    ${client.c2_complemento || '-'}
                </div>
                <div class="col-md-4">
                    <strong>Bairro:</strong><br>
                    ${client.c2_bairro}
                </div>
                <div class="col-md-4">
                    <strong>Cidade:</strong><br>
                    ${client.c2_cidade}
                </div>
                <div class="col-md-2">
                    <strong>UF:</strong><br>
                    ${client.c2_uf}
                </div>
                <div class="col-md-6">
                    <strong>Ponto de Referência:</strong><br>
                    ${client.c2_ponto_referencia || '-'}
                </div>
                
                <div class="col-12 mt-4">
                    <h6 class="text-primary"><i class="fa-solid fa-info-circle me-2"></i>Informações do Sistema</h6>
                    <hr>
                </div>
                <div class="col-md-6">
                    <strong>Data de Cadastro:</strong><br>
                    ${dataCadastro}
                </div>
            </div>
        `);

        // Configurar botão de editar
        $('#editClientBtn').data('client-id', client.c2_id);

        // Mostrar modal
        new bootstrap.Modal('#clientModal').show();
    }

    // Editar cliente
    function editClient(id) {
        openEditModal(id);
    }

    // Abrir modal de edição
    async function openEditModal(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/clientes/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                fillEditForm(response);
                new bootstrap.Modal('#editClientModal').show();
            } else {
                showAlert('error', 'Erro ao carregar dados do cliente');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do cliente');
        }
    }

    // Preencher formulário de edição
    function fillEditForm(client) {
        $('#edit-client-id').val(client.c2_id);
        $('#edit-client-name').val(client.c2_nome);
        $('#edit-client-cpf').val(formatarCpf(client.c2_cpf));
        $('#edit-client-rg').val(client.c2_rg || '');
        $('#edit-client-birth-date').val(client.c2_data_nascimento);
        $('#edit-client-age').val(client.c2_idade);
        $('#edit-client-phone').val(formatarTelefone(client.c2_telefone) || '');
        $('#edit-client-mobile').val(formatarTelefone(client.c2_celular));
        $('#edit-client-email').val(client.c2_email || '');
        $('#edit-client-cep').val(client.c2_cep);
        $('#edit-client-address').val(client.c2_endereco);
        $('#edit-client-number').val(client.c2_numero || '');
        $('#edit-client-complement').val(client.c2_complemento || '');
        $('#edit-client-neighborhood').val(client.c2_bairro);
        $('#edit-client-city').val(client.c2_cidade);
        $('#edit-client-uf').val(client.c2_uf);
        $('#edit-client-reference').val(client.c2_ponto_referencia || '');
    }

    // Salvar novo cliente
    async function saveClient() {
        const $form = $('#addClientForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        // Remover máscaras antes de enviar
        data.c2_cpf = data.c2_cpf.replace(/\D/g, '');
        data.c2_telefone = data.c2_telefone ? data.c2_telefone.replace(/\D/g, '') : '';
        data.c2_celular = data.c2_celular.replace(/\D/g, '');
        data.c2_cep = data.c2_cep.replace(/\D/g, '');

        try {
            const response = await $.ajax({
                url: '<?= site_url('/clientes') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Cliente cadastrado com sucesso!');
                $('#addClientModal').modal('hide');
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadClients();
            } else {
                showAlert('error', response.message || 'Erro ao cadastrar cliente');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao cadastrar cliente');
        }
    }

    // Atualizar cliente
    async function updateClient() {
        const $form = $('#editClientForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const formData = $form.serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        const clientId = data.c2_id;

        // Remover máscaras antes de enviar
        data.c2_cpf = data.c2_cpf.replace(/\D/g, '');
        data.c2_telefone = data.c2_telefone ? data.c2_telefone.replace(/\D/g, '') : '';
        data.c2_celular = data.c2_celular.replace(/\D/g, '');
        data.c2_cep = data.c2_cep.replace(/\D/g, '');

        try {
            const response = await $.ajax({
                url: `/clientes/${clientId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response) {
                showAlert('success', 'Cliente atualizado com sucesso!');
                $('#editClientModal').modal('hide');
                $form.removeClass('was-validated');
                await loadClients();
            } else {
                showAlert('error', response.message || 'Erro ao atualizar cliente');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao atualizar cliente');
        }
    }

    // Excluir cliente
    async function deleteClient(id, name) {
        Swal.fire({
            title: `Tem certeza que deseja excluir o cliente "${name}"?`,
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
                        url: `<?= site_url('/clientes/') ?>${id}`,
                        method: 'DELETE'
                    });

                    if (response) {
                        showAlert('success', 'Cliente excluído com sucesso!');
                        await loadClients();
                    } else {
                        showAlert('error', response.message || 'Erro ao excluir cliente');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    showAlert('error', 'Erro ao excluir cliente');
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
    $('#addClientModal').on('hidden.bs.modal', function() {
        const $form = $('#addClientForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    $('#editClientModal').on('hidden.bs.modal', function() {
        $('#editClientForm').removeClass('was-validated');
    });
</script>

<?= $this->endSection() ?>