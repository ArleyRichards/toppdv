<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Fornecedores</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todos os fornecedores cadastrados no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addProviderModal">
                    <i class="fa-solid fa-plus me-1"></i> Novo
                </button>
                <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-home me-1"></i> Menu
                </a>
            </div>
        </div>
    </div>

            <!-- Modal de edição de fornecedor -->
            <div class="modal fade" id="editProviderModal" tabindex="-1" aria-labelledby="editProviderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProviderModalLabel">
                                <i class="fa-solid fa-edit text-warning me-2"></i>Editar Fornecedor
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editProviderForm" class="needs-validation" novalidate>
                                <input type="hidden" name="id" id="edit-provider-id">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="edit-provider-razao" class="form-label">Razão Social <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit-provider-razao" name="razao_social" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-provider-fantasia" class="form-label">Nome Fantasia <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit-provider-fantasia" name="nome_fantasia" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit-provider-cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit-provider-cnpj" name="cnpj" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="edit-provider-cep" class="form-label">CEP</label>
                                        <input type="text" class="form-control" id="edit-provider-cep" name="cep" maxlength="9" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="edit-provider-cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="edit-provider-cidade" name="cidade">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit-provider-uf" class="form-label">UF</label>
                                        <select class="form-select" id="edit-provider-uf" name="uf">
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
                                    <div class="col-md-3">
                                        <label for="edit-provider-telefone" class="form-label">Telefone</label>
                                        <input type="text" class="form-control" id="edit-provider-telefone" name="telefone">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="edit-provider-celular" class="form-label">Celular</label>
                                        <input type="text" class="form-control" id="edit-provider-celular" name="celular">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-provider-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit-provider-email" name="email">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-provider-endereco" class="form-label">Endereço <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit-provider-endereco" name="endereco" required maxlength="255">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit-provider-bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit-provider-bairro" name="bairro" required maxlength="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit-provider-numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="edit-provider-numero" name="numero" maxlength="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-provider-complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="edit-provider-complemento" name="complemento" maxlength="255">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-provider-ponto-referencia" class="form-label">Ponto de Referência</label>
                                        <input type="text" class="form-control" id="edit-provider-ponto-referencia" name="ponto_referencia" maxlength="255">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-times me-1"></i>Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" id="updateProviderBtn">
                                <i class="fa-solid fa-save me-1"></i>Atualizar Fornecedor
                            </button>
                        </div>
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
                    <label for="filterRazao" class="form-label">Razão Social</label>
                    <input type="text" class="form-control" id="filterRazao" placeholder="Digite a razão social...">
                </div>
                <div class="col-lg-3 col-md-4">
                    <label for="filterFantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" class="form-control" id="filterFantasia" placeholder="Digite o nome fantasia...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterCnpj" class="form-label">CNPJ</label>
                    <input type="text" class="form-control" id="filterCnpj" placeholder="00.000.000/0000-00">
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
                <div class="col-lg-1 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de fornecedores -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Fornecedores Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Razão Social</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ</th>
                            <th>Cidade</th>
                            <th>UF</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="providersTableBody">
                        <!-- Preenchido via JS -->
                    </tbody>
                </table>
            </div>
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
            <nav aria-label="Navegação da página" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Preenchido via JS -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal de visualização de fornecedor -->
<div class="modal fade" id="providerModal" tabindex="-1" aria-labelledby="providerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="providerModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes do Fornecedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="providerModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editProviderBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Fornecedor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de fornecedor -->
<div class="modal fade" id="addProviderModal" tabindex="-1" aria-labelledby="addProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProviderModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Novo Fornecedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addProviderForm" class="needs-validation" novalidate>
                    <?php if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="add-provider-razao" class="form-label">Razão Social <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-razao" name="razao_social" required autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="add-provider-fantasia" class="form-label">Nome Fantasia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-fantasia" name="nome_fantasia" required autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="add-provider-cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-cnpj" name="cnpj" required maxlength="18" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label for="add-provider-cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="add-provider-cep" name="cep" maxlength="9" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label for="add-provider-cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-cidade" name="cidade" required maxlength="100" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label for="add-provider-uf" class="form-label">UF <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-provider-uf" name="uf" required>
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
                        <div class="col-md-6">
                            <label for="add-provider-endereco" class="form-label">Endereço <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-endereco" name="endereco" required maxlength="255" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="add-provider-bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-bairro" name="bairro" required maxlength="100" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label for="add-provider-numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="add-provider-numero" name="numero" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="add-provider-complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="add-provider-complemento" name="complemento" maxlength="255" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="add-provider-ponto-referencia" class="form-label">Ponto de Referência</label>
                            <input type="text" class="form-control" id="add-provider-ponto-referencia" name="ponto_referencia" maxlength="255" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="add-provider-telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="add-provider-telefone" name="telefone" maxlength="15" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="add-provider-celular" class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-provider-celular" name="celular" required maxlength="15" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="add-provider-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="add-provider-email" name="email" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveProviderBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Fornecedor
                </button>
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
        loadFornecedores();
        setupEventListeners();
        setupMasks();
    });

    // Configurações de máscaras (similar ao implementado em clientes.php)
    const masks = {
        cnpj: '00.000.000/0000-00',
    cep: '00000-000',
        phone: '(00) 0000-0000',
        mobile: '(00) 00000-0000'
    };

    // Configura máscaras nos campos do modal e filtros
    function setupMasks() {
        applyMask('add-provider-cnpj', masks.cnpj);
    applyMask('add-provider-cep', masks.cep);
        applyMask('filterCnpj', masks.cnpj);
        applyMask('add-provider-telefone', masks.phone);
        applyMask('add-provider-celular', masks.mobile);
    }

    // Bind CEP blur events to fetch address via ViaCEP
    $(document).on('blur', '#add-provider-cep', function() {
        const cep = ($(this).val() || '').replace(/\D/g, '');
        if (cep.length === 8) fetchCepAndFill(cep, 'add');
    });

    $(document).on('blur', '#edit-provider-cep', function() {
        const cep = ($(this).val() || '').replace(/\D/g, '');
        if (cep.length === 8) fetchCepAndFill(cep, 'edit');
    });

    async function fetchCepAndFill(cep, mode) {
        try {
            const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            if (!res.ok) throw new Error('Erro na consulta CEP');
            const data = await res.json();
            if (data.erro) {
                showAlert('error', 'CEP não encontrado.');
                return;
            }
            // preencher campos
            if (mode === 'add') {
                $('#add-provider-endereco').val(data.logradouro || '');
                $('#add-provider-bairro').val(data.bairro || '');
                $('#add-provider-cidade').val(data.localidade || '');
                $('#add-provider-uf').val(data.uf || '');
            } else {
                $('#edit-provider-endereco').val(data.logradouro || '');
                $('#edit-provider-bairro').val(data.bairro || '');
                $('#edit-provider-cidade').val(data.localidade || '');
                $('#edit-provider-uf').val(data.uf || '');
            }
        } catch (e) {
            console.error('Erro viaCEP:', e);
            showAlert('error', 'Não foi possível consultar o CEP.');
        }
    }

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
     * Carrega os dados dos fornecedores via AJAX GET.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    function setupEventListeners() {
        // Filtros: razão, fantasia, cnpj, cidade, uf
        $('#filterRazao, #filterFantasia, #filterCnpj, #filterCidade, #filterUf').on('input change', applyFilters);
        $('#clearFilters').on('click', clearFilters);

        // Paginação
        $('#itemsPerPage').on('change', function() {

            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });

        // Buttons handled via delegated calls to specific functions
        // Bind edit button (quando usado dentro do modal de visualização)
        $('#editProviderBtn').on('click', function() {
            const id = $(this).data('provider-id');
            if (id) {
                // Fecha o modal de visualização caso esteja aberto para evitar problemas com backdrop
                const viewModalEl = document.getElementById('providerModal');
                const viewInstance = bootstrap.Modal.getInstance(viewModalEl) || null;
                if (viewInstance) {
                    try {
                        viewInstance.hide();
                    } catch (e) {
                        /* ignore */
                    }
                }
                openEditModal(id);
            }
        });
    }

    // (no duplicate handler) - handled inside setupEventListeners

    // Bind action buttons for fornecedores
    $(document).on('click', '#saveProviderBtn', function(e) {
        e.preventDefault();
        saveProvider();
    });

    // Salvar novo fornecedor
    async function saveProvider() {
        const $form = $('#addProviderForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const data = {
            razao_social: $('#add-provider-razao').val(),
            nome_fantasia: $('#add-provider-fantasia').val(),
            cnpj: ($('#add-provider-cnpj').val() || '').replace(/\D/g, ''),
            cep: ($('#add-provider-cep').val() || '').replace(/\D/g, ''),
            cidade: $('#add-provider-cidade').val(),
            uf: $('#add-provider-uf').val(),
            endereco: $('#add-provider-endereco').val(),
            bairro: $('#add-provider-bairro').val(),
            numero: $('#add-provider-numero').val(),
            complemento: $('#add-provider-complemento').val(),
            ponto_referencia: $('#add-provider-ponto-referencia').val(),
            telefone: ($('#add-provider-telefone').val() || '').replace(/\D/g, ''),
            celular: ($('#add-provider-celular').val() || '').replace(/\D/g, ''),
            email: $('#add-provider-email').val()
        };

        // include CSRF token if present
        const csrf = $('input[name="csrf_token"]').val() || $('input[name="__token"]').val() || $('input[name="csrf_test_name"]').val();
        if (csrf) data.csrf_token = csrf;

        try {
            const resp = await $.ajax({
                url: `<?= site_url('/fornecedores') ?>`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (resp) {
                showAlert('success', 'Fornecedor cadastrado com sucesso!');
                // hide add modal
                const addEl = document.getElementById('addProviderModal');
                bootstrap.Modal.getOrCreateInstance(addEl).hide();
                $form[0].reset();
                $form.removeClass('was-validated');
                await loadFornecedores();
            } else {
                showAlert('error', resp.message || 'Erro ao cadastrar fornecedor');
            }
        } catch (err) {
            console.error('Erro ao salvar fornecedor:', err);
            // try to read validation errors
            if (err && err.responseJSON && err.responseJSON.errors) {
                const errs = err.responseJSON.errors;
                const first = Object.keys(errs)[0];
                const msg = errs[first][0] || 'Erro de validação.';
                showAlert('error', msg);
                return;
            }
            const msg = (err && err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Erro ao cadastrar fornecedor.';
            showAlert('error', msg);
        }
    }

    // Carregar dados dos fornecedores
    async function loadFornecedores() {
        try {
            const response = await $.get('<?= site_url('/fornecedores/list') ?>');

            if (Array.isArray(response)) {
                // map to fornecedores data shape
                garantiasData = response.map(f => ({
                    id: f.f1_id,
                    razao_social: f.f1_razao_social,
                    nome_fantasia: f.f1_nome_fantasia,
                    cnpj: f.f1_cnpj,
                    cidade: f.f1_cidade,
                    uf: f.f1_uf,
                    telefone: f.f1_telefone,
                    celular: f.f1_celular,
                    email: f.f1_email
                }));

                filteredGarantias = [...garantiasData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar fornecedores');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados dos fornecedores');
        }
    }

    // Aplicar filtros: nome e intervalo de comissão
    function applyFilters() {
        const razao = ($('#filterRazao').val() || '').toLowerCase();
        const fantasia = ($('#filterFantasia').val() || '').toLowerCase();
        const cnpj = ($('#filterCnpj').val() || '').toLowerCase().replace(/[^0-9]/g, '');
        const cidade = ($('#filterCidade').val() || '').toLowerCase();
        const uf = ($('#filterUf').val() || '').toUpperCase();

        filteredGarantias = garantiasData.filter(g => {
            const matchRazao = !razao || (g.razao_social && g.razao_social.toLowerCase().includes(razao));
            const matchFantasia = !fantasia || (g.nome_fantasia && g.nome_fantasia.toLowerCase().includes(fantasia));
            const matchCnpj = !cnpj || (g.cnpj && g.cnpj.replace(/[^0-9]/g, '').includes(cnpj));
            const matchCidade = !cidade || (g.cidade && g.cidade.toLowerCase().includes(cidade));
            const matchUf = !uf || (g.uf && g.uf.toUpperCase() === uf);
            return matchRazao && matchFantasia && matchCnpj && matchCidade && matchUf;
        });

        currentPage = 1;
        renderTable();
    }

    // Aplicar máscara a um campo (simples, copia behavior de clientes.php)
    function applyMask(elementId, mask) {
        const $element = $('#' + elementId);
        if ($element.length) {
            $element.on('input', function(e) {
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

    // Limpar filtros
    function clearFilters() {
        $('#filterRazao, #filterFantasia, #filterCnpj, #filterCidade').val('');
        $('#filterUf').val('');

        filteredGarantias = [...garantiasData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredGarantias.slice(startIndex, endIndex);

        const $tbody = $('#providersTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhum fornecedor encontrado
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(garantia => {
                const row = createProviderRow(garantia);
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Criar linha da tabela
    function createProviderRow(f) {
        const id = f.id || '';
        const razao = f.razao_social || '-';
        const fantasia = f.nome_fantasia || '-';
        const cnpj = f.cnpj || '-';
        const cidade = f.cidade || '-';
        const uf = f.uf || '-';
        return `
            <tr>
                <td>${razao}</td>
                <td>${fantasia}</td>
                <td>${cnpj}</td>
                <td>${cidade}</td>
                <td>${uf}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-action" onclick="viewFornecedor(${id})" title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-action" onclick="editFornecedor(${id})" title="Editar">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-action" onclick="deleteFornecedor(${id}, '${String(razao).replace(/'/g, "\\'")}')" title="Excluir">
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
    async function viewFornecedor(id) {
        try {
            const response = await $.ajax({
                url: `<?= site_url('/fornecedores/') ?>${id}`,
                method: 'GET',
                dataType: 'json'
            });
            if (response) {
                // show in a simple modal (providerModal body)
                $('#providerModalLabel').text('Detalhes do Fornecedor');
                $('#providerModalBody').html(`
                    <div class="row g-3">
                        <div class="col-12"><h6 class="text-primary">Dados do Fornecedor</h6><hr></div>
                        <div class="col-12"><strong>Razão Social:</strong><br>${response.f1_razao_social || '-'}</div>
                        <div class="col-12"><strong>Nome Fantasia:</strong><br>${response.f1_nome_fantasia || '-'}</div>
                        <div class="col-12"><strong>CNPJ:</strong><br>${response.f1_cnpj || '-'}</div>
                        <div class="col-12"><strong>Cidade / UF:</strong><br>${response.f1_cidade || '-'} / ${response.f1_uf || '-'}</div>
                        <div class="col-12"><strong>Telefone:</strong><br>${response.f1_telefone || '-'}</div>
                        <div class="col-12"><strong>Celular:</strong><br>${response.f1_celular || '-'}</div>
                        <div class="col-12"><strong>Email:</strong><br>${response.f1_email || '-'}</div>
                    </div>
                `);

                // set edit button data attribute
                $('#editProviderBtn').data('provider-id', response.f1_id);

                const viewEl = document.getElementById('providerModal');
                try {
                    new bootstrap.Modal(viewEl).show();
                } catch (e) {
                    bootstrap.Modal.getOrCreateInstance(viewEl).show();
                }
            } else {
                showAlert('error', 'Erro ao carregar dados do fornecedor');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do fornecedor');
        }
    }

    function editFornecedor(id) {
    // Open fornecedor-specific edit modal
    openEditProviderModal(id);
    }

    async function deleteFornecedor(id, name) {
        Swal.fire({
            title: `Tem certeza que deseja excluir o fornecedor "${name}"?`,
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
                        url: `<?= site_url('/fornecedores/') ?>${id}`,
                        method: 'DELETE'
                    });

                    if (response) {
                        showAlert('success', 'Fornecedor excluído com sucesso!');
                        await loadFornecedores();
                    } else {
                        showAlert('error', response.message || 'Erro ao excluir fornecedor');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    showAlert('error', 'Erro ao excluir fornecedor');
                }
            }
        });
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
                    /* ignore */
                }
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
    $('#addProviderModal').on('hidden.bs.modal', function() {
        const $form = $('#addProviderForm');
        $form[0].reset();
        $form.removeClass('was-validated');
    });

    // when provider view modal is closed remove provider-id from edit button
    $('#providerModal').on('hidden.bs.modal', function() {
        $('#editProviderBtn').removeData('provider-id');
    });

    // ---------- Fornecedor edit helpers ----------
    // Open edit modal and load fornecedor data
    function openEditProviderModal(id) {
        if (!id) return;
        $.ajax({
            url: `<?= site_url('/fornecedores/') ?>${id}`,
            method: 'GET',
            dataType: 'json'
        }).done(function(response) {
            if (!response) {
                showAlert('error', 'Fornecedor não encontrado.');
                return;
            }
            fillEditProviderForm(response);
            // show edit modal
            const editEl = document.getElementById('editProviderModal');
            bootstrap.Modal.getOrCreateInstance(editEl).show();
        }).fail(function(xhr) {
            const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erro ao carregar fornecedor.';
            showAlert('error', msg);
        });
    }

    function fillEditProviderForm(provider) {
        $('#edit-provider-id').val(provider.f1_id || provider.id || '');
        $('#edit-provider-razao').val(provider.f1_razao_social || '');
        $('#edit-provider-fantasia').val(provider.f1_nome_fantasia || '');
        $('#edit-provider-cnpj').val(maskCnpj(provider.f1_cnpj || ''));
    $('#edit-provider-cep').val(maskCep(provider.f1_cep || ''));
        $('#edit-provider-cidade').val(provider.f1_cidade || '');
        $('#edit-provider-uf').val(provider.f1_uf || '');
        $('#edit-provider-telefone').val(maskPhone(provider.f1_telefone || ''));
        $('#edit-provider-celular').val(maskPhone(provider.f1_celular || ''));
    $('#edit-provider-email').val(provider.f1_email || '');
    $('#edit-provider-endereco').val(provider.f1_endereco || '');
    $('#edit-provider-bairro').val(provider.f1_bairro || '');
    $('#edit-provider-numero').val(provider.f1_numero || '');
    $('#edit-provider-complemento').val(provider.f1_complemento || '');
    $('#edit-provider-ponto-referencia').val(provider.f1_ponto_referencia || '');
    }

    // Masks for edit modal
    function setupEditMasks() {
        applyMask('edit-provider-cnpj', masks.cnpj);
    applyMask('edit-provider-cep', masks.cep);
        applyMask('edit-provider-telefone', masks.phone);
        applyMask('edit-provider-celular', masks.mobile);
    }

    // helper to format cnpj/phone values for display
    function maskCnpj(val) {
        if (!val) return '';
        return maskValue(String(val).replace(/\D/g, ''), masks.cnpj);
    }

    function maskPhone(val) {
        if (!val) return '';
        const digits = String(val).replace(/\D/g, '');
        // choose mobile mask when 11 digits
        const m = digits.length > 10 ? masks.mobile : masks.phone;
        return maskValue(digits, m);
    }

    function maskCep(val) {
        if (!val) return '';
        return maskValue(String(val).replace(/\D/g, ''), masks.cep);
    }

    // ensure edit masks are set when modal shown
    $(document).on('shown.bs.modal', '#editProviderModal', function () {
        setupEditMasks();
    });

    // Update provider via PUT
    function updateProvider() {
        const id = $('#edit-provider-id').val();
        if (!id) return showAlert('error', 'ID do fornecedor ausente.');

        const payload = {
            razao_social: $('#edit-provider-razao').val(),
            nome_fantasia: $('#edit-provider-fantasia').val(),
            cnpj: ($('#edit-provider-cnpj').val() || '').replace(/\D/g, ''),
            cep: ($('#edit-provider-cep').val() || '').replace(/\D/g, ''),
            cidade: $('#edit-provider-cidade').val(),
            uf: $('#edit-provider-uf').val(),
            telefone: ($('#edit-provider-telefone').val() || '').replace(/\D/g, ''),
            celular: ($('#edit-provider-celular').val() || '').replace(/\D/g, ''),
            email: $('#edit-provider-email').val(),
            endereco: $('#edit-provider-endereco').val(),
            bairro: $('#edit-provider-bairro').val(),
            numero: $('#edit-provider-numero').val(),
            complemento: $('#edit-provider-complemento').val(),
            ponto_referencia: $('#edit-provider-ponto-referencia').val()
        };

        // include CSRF token if present on page
        const csrf = $('input[name="csrf_token"]').val() || $('input[name="__token"]').val() || $('input[name="csrf_test_name"]').val();
        if (csrf) payload.csrf_token = csrf;

        $.ajax({
            url: `<?= site_url('/fornecedores/') ?>${id}`,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(payload)
        }).done(function(resp) {
            showAlert('success', 'Fornecedor atualizado com sucesso!');
            // hide modal
            const editEl = document.getElementById('editProviderModal');
            bootstrap.Modal.getOrCreateInstance(editEl).hide();
            // refresh list
            loadFornecedores();
        }).fail(function(xhr) {
            // try to show validation errors
            if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                const errs = xhr.responseJSON.errors;
                const firstField = Object.keys(errs)[0];
                const msg = errs[firstField][0] || 'Erro de validação.';
                showAlert('error', msg);
                return;
            }
            const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erro ao atualizar fornecedor.';
            showAlert('error', msg);
        });
    }

    // bind update button
    $(document).on('click', '#updateProviderBtn', function(e) {
        e.preventDefault();
        updateProvider();
    });
</script>

<?= $this->endSection() ?>