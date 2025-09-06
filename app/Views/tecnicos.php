<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top:10px; padding:15px;">

    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-tools text-primary me-2"></i> Técnicos</h2>
            <p class="text-muted" style="font-size:14px;">Gerencie os técnicos do sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addTecnicoModal">
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
                <div class="col-lg-3 col-md-6">
                    <label for="filterNome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="filterNome" placeholder="Pesquisar por nome...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterCpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="filterCpf" placeholder="Pesquisar por CPF...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterTelefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="filterTelefone" placeholder="Pesquisar por telefone...">
                </div>
                <div class="col-lg-3 col-md-3">
                    <label for="filterEmail" class="form-label">Email</label>
                    <input type="text" class="form-control" id="filterEmail" placeholder="Pesquisar por email...">
                </div>
                <div class="col-lg-2 col-md-3 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </div>
            </div>
            <!-- date filters removed as requested -->

        </div>
    </div>

    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0">Lista de Técnicos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tecnicosTableBody">
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de cadastro de técnico -->
    <div class="modal fade" id="addTecnicoModal" tabindex="-1" aria-labelledby="addTecnicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTecnicoModalLabel">Cadastrar Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="addTecnicoForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="t1_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" id="t1_nome" name="t1_nome" class="form-control" required>
                            <div class="invalid-feedback" id="t1_nome_error">Informe o nome do técnico.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="t1_cpf" class="form-label">CPF</label>
                                <input type="text" id="t1_cpf" name="t1_cpf" class="form-control">
                                <div class="invalid-feedback" id="t1_cpf_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="t1_telefone" class="form-label">Telefone</label>
                                <input type="text" id="t1_telefone" name="t1_telefone" class="form-control">
                                <div class="invalid-feedback" id="t1_telefone_error"></div>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="t1_email" class="form-label">Email</label>
                            <input type="email" id="t1_email" name="t1_email" class="form-control">
                            <div class="invalid-feedback" id="t1_email_error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="t1_observacao" class="form-label">Observação</label>
                            <textarea id="t1_observacao" name="t1_observacao" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback" id="t1_observacao_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="saveTecnicoBtn">
                        <i class="fa-solid fa-save me-1"></i>Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de visualização de técnico -->
    <div class="modal fade" id="viewTecnicoModal" tabindex="-1" aria-labelledby="viewTecnicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTecnicoModalLabel">Detalhes do Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="viewTecnicoBody">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted">Carregando...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal wrapper (was missing) -->
    <div class="modal fade" id="editTecnicoModal" tabindex="-1">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editTecnicoForm" class="needs-validation" novalidate>
                        <input type="hidden" id="edit-t1_id" name="t1_id">
                        <div class="mb-3">
                            <label for="edit-t1_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" id="edit-t1_nome" name="t1_nome" class="form-control" required>
                            <div class="invalid-feedback" id="edit-t1_nome_error">Informe o nome.</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit-t1_cpf" class="form-label">CPF</label>
                                <input type="text" id="edit-t1_cpf" name="t1_cpf" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="edit-t1_telefone" class="form-label">Telefone</label>
                                <input type="text" id="edit-t1_telefone" name="t1_telefone" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="edit-t1_email" class="form-label">Email</label>
                            <input type="email" id="edit-t1_email" name="t1_email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-t1_observacao" class="form-label">Observação</label>
                            <textarea id="edit-t1_observacao" name="t1_observacao" class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="updateTecnicoBtn">
                        <i class="fa-solid fa-save me-1"></i>Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>

    <?= $this->section('pagescript') ?>

    <script>
        // Visualizar técnico (fetch e exibe no modal)
        async function viewTecnico(id) {
            if (!id) return;
            try {
                const resp = await $.ajax({
                    url: `<?= site_url('/tecnicos/') ?>${id}`,
                    method: 'GET',
                    dataType: 'json'
                });
                if (!resp) return showAlert('error', 'Técnico não encontrado');

                const created = resp.t1_created_at ? new Date(resp.t1_created_at).toLocaleString('pt-BR') : '-';
                const updated = resp.t1_updated_at ? new Date(resp.t1_updated_at).toLocaleString('pt-BR') : '-';

                const html = `
                <div class="row g-3">
                    <div class="col-md-6"><strong>Nome:</strong> ${resp.t1_nome || '-'}</div>
                    <div class="col-md-6"><strong>CPF:</strong> ${resp.t1_cpf || '-'}</div>
                    <div class="col-md-6"><strong>Telefone:</strong> ${resp.t1_telefone || '-'}</div>
                    <div class="col-md-6"><strong>Email:</strong> ${resp.t1_email || '-'}</div>
                    <div class="col-12"><strong>Observação:</strong><div class="mt-1">${resp.t1_observacao || '-'}</div></div>
                    <div class="col-md-6"><strong>Criado em:</strong> ${created}</div>
                    <div class="col-md-6"><strong>Atualizado em:</strong> ${updated}</div>
                </div>
            `;

                $('#viewTecnicoBody').html(html);
                try {
                    new bootstrap.Modal(document.getElementById('viewTecnicoModal')).show();
                } catch (e) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('viewTecnicoModal')).show();
                }
            } catch (e) {
                console.error(e);
                showAlert('error', 'Erro ao carregar técnico');
            }
        }
    </script>

    <script>
        // Filter state
        let allTecnicos = [];
        let filteredTecnicos = [];
        let filterDebounceTimer = null;

        $(document).ready(function() {
            loadTecnicos();

            // bind buttons
            $(document).on('click', '#saveTecnicoBtn', function(e) {
                e.preventDefault();
                saveTecnico();
            });
            $(document).on('click', '#updateTecnicoBtn', function(e) {
                e.preventDefault();
                updateTecnico();
            });

            // filters
            // bind all filter inputs with debounce
            function bindFilter(selector, delay) {
                $(selector).on('input change', function() {
                    clearTimeout(filterDebounceTimer);
                    filterDebounceTimer = setTimeout(applyFilters, delay || 300);
                });
            }
            bindFilter('#filterNome', 300);
            bindFilter('#filterCpf', 300);
            bindFilter('#filterTelefone', 300);
            bindFilter('#filterEmail', 300);
            // date filters removed

            $('#clearFilters').on('click', function(e) {
                e.preventDefault();
                clearFilters();
            });
            // initialize input masks
            setupMasks();
        });

        // Apply jQuery Mask for CPF and Telefone when plugin is available
        function setupMasks() {
            if (!window.jQuery || !$.fn || !$.fn.mask) return;

            // CPF mask
            $('#filterCpf, #t1_cpf, #edit-t1_cpf').each(function() {
                $(this).mask('000.000.000-00');
            });

            // Phone mask: choose 9-digit mobile mask when 11 digits
            var phoneBehavior = function(val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-0000';
            };
            var phoneOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(phoneBehavior(val), options);
                }
            };
            $('#filterTelefone, #t1_telefone, #edit-t1_telefone').each(function() {
                $(this).mask(phoneBehavior, phoneOptions);
            });
        }

        // Ensure masks are applied when modals are shown (handles dynamically inserted elements)
        $(document).on('shown.bs.modal', '#addTecnicoModal, #editTecnicoModal', function() {
            setupMasks();
        });

        async function loadTecnicos() {
            try {
                const response = await $.get('<?= site_url('/tecnicos/list') ?>');
                allTecnicos = Array.isArray(response) ? response : [];
                filteredTecnicos = allTecnicos.slice();
                renderTecnicos();
            } catch (e) {
                console.error(e);
                showAlert('error', 'Erro ao carregar técnicos');
            }
        }

        // Render table from filteredTecnicos
        function renderTecnicos() {
            const $tbody = $('#tecnicosTableBody');
            $tbody.empty();
            if (!Array.isArray(filteredTecnicos) || filteredTecnicos.length === 0) {
                $tbody.html('<tr><td colspan="5" class="text-center text-muted">Nenhum técnico encontrado</td></tr>');
                return;
            }
            filteredTecnicos.forEach(t => {
                const tr = `
                <tr>
                    <td>${t.t1_nome || '-'}</td>
                    <td>${t.t1_cpf || '-'}</td>
                    <td>${t.t1_telefone || '-'}</td>
                    <td>${t.t1_email || '-'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-primary btn-sm" onclick="viewTecnico(${t.t1_id})"><i class="fa-solid fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm" onclick="openEditTecnico(${t.t1_id})"><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTecnico(${t.t1_id}, '${String(t.t1_nome).replace(/'/g, "\\'")}')"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `;
                $tbody.append(tr);
            });
        }

        // Apply filters (client-side)
        function applyFilters() {
            const nomeQ = ($('#filterNome').val() || '').toString().toLowerCase().trim();
            const cpfQ = ($('#filterCpf').val() || '').toString().replace(/\D/g, '').trim();
            const telQ = ($('#filterTelefone').val() || '').toString().replace(/\D/g, '').trim();
            const emailQ = ($('#filterEmail').val() || '').toString().toLowerCase().trim();
            // date filtering removed

            filteredTecnicos = allTecnicos.filter(t => {
                if (nomeQ && !(t.t1_nome || '').toString().toLowerCase().includes(nomeQ)) return false;
                if (cpfQ) {
                    const cpfNorm = (t.t1_cpf || '').toString().replace(/\D/g, '');
                    if (!cpfNorm.includes(cpfQ)) return false;
                }
                if (telQ) {
                    const telNorm = (t.t1_telefone || '').toString().replace(/\D/g, '');
                    if (!telNorm.includes(telQ)) return false;
                }
                if (emailQ && !(t.t1_email || '').toString().toLowerCase().includes(emailQ)) return false;
                // created date filter removed
                return true;
            });
            renderTecnicos();
        }

        function clearFilters() {
            $('#filterNome').val('');
            $('#filterCpf').val('');
            $('#filterTelefone').val('');
            $('#filterEmail').val('');
            // date fields removed
            filteredTecnicos = allTecnicos.slice();
            renderTecnicos();
        }

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

        async function saveTecnico() {
            const $form = $('#addTecnicoForm');
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }
            clearFieldErrors('add');
            const data = {};
            $.each($form.serializeArray(), function(i, f) {
                data[f.name] = f.value;
            });
            try {
                const resp = await $.ajax({
                    url: '<?= site_url('/tecnicos') ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data)
                });
                if (resp) {
                    showAlert('success', 'Técnico criado');
                    try {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('addTecnicoModal')).hide();
                    } catch (e) {}
                    await loadTecnicos();
                }
            } catch (err) {
                console.error(err);
                const r = err && err.responseJSON ? err.responseJSON : null;
                if (r && r.messages) showFieldErrors(r.messages, 'add');
                else showAlert('error', 'Erro ao criar técnico');
            }
        }

        async function openEditTecnico(id) {
            try {
                const resp = await $.ajax({
                    url: `<?= site_url('/tecnicos/') ?>${id}`,
                    method: 'GET',
                    dataType: 'json'
                });
                if (resp) {
                    $('#edit-t1_id').val(resp.t1_id || resp.t1_id);
                    $('#edit-t1_nome').val(resp.t1_nome || '');
                    $('#edit-t1_cpf').val(resp.t1_cpf || '');
                    $('#edit-t1_telefone').val(resp.t1_telefone || '');
                    $('#edit-t1_email').val(resp.t1_email || '');
                    $('#edit-t1_observacao').val(resp.t1_observacao || '');
                    try {
                        new bootstrap.Modal(document.getElementById('editTecnicoModal')).show();
                    } catch (e) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('editTecnicoModal')).show();
                    }
                }
            } catch (e) {
                console.error(e);
                showAlert('error', 'Erro ao carregar técnico');
            }
        }

        async function updateTecnico() {
            const $form = $('#editTecnicoForm');
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }
            clearFieldErrors('edit');
            const data = {};
            $.each($form.serializeArray(), function(i, f) {
                data[f.name] = f.value;
            });
            const id = data.t1_id;
            try {
                const resp = await $.ajax({
                    url: `<?= site_url('/tecnicos/') ?>${id}`,
                    method: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(data)
                });
                if (resp) {
                    showAlert('success', 'Técnico atualizado');
                    try {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('editTecnicoModal')).hide();
                    } catch (e) {}
                    await loadTecnicos();
                }
            } catch (err) {
                console.error(err);
                const r = err && err.responseJSON ? err.responseJSON : null;
                if (r && r.messages) showFieldErrors(r.messages, 'edit');
                else showAlert('error', 'Erro ao atualizar técnico');
            }
        }

        async function deleteTecnico(id, name) {
            Swal.fire({
                title: `Excluir técnico "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    try {
                        const r = await $.ajax({
                            url: `<?= site_url('/tecnicos/') ?>${id}`,
                            method: 'DELETE'
                        });
                        showAlert('success', 'Técnico excluído');
                        await loadTecnicos();
                    } catch (e) {
                        console.error(e);
                        showAlert('error', 'Erro ao excluir técnico');
                    }
                }
            });
        }

        function clearFieldErrors(type) {
            if (type === 'add') {
                $('#addTecnicoForm').find('.is-invalid').removeClass('is-invalid');
                $('#addTecnicoForm').find('[id$="_error"]').text('').hide();
            } else {
                $('#editTecnicoForm').find('.is-invalid').removeClass('is-invalid');
                $('#editTecnicoForm').find('[id$="_error"]').text('').hide();
            }
        }

        function showFieldErrors(messages, type) {
            const mapping = {
                't1_nome': {
                    add: '#t1_nome',
                    edit: '#edit-t1_nome',
                    errorAdd: '#t1_nome_error',
                    errorEdit: '#edit-t1_nome_error'
                },
                't1_cpf': {
                    add: '#t1_cpf',
                    edit: '#edit-t1_cpf',
                    errorAdd: '#t1_cpf_error',
                    errorEdit: '#edit-t1_cpf_error'
                },
                't1_telefone': {
                    add: '#t1_telefone',
                    edit: '#edit-t1_telefone',
                    errorAdd: '#t1_telefone_error',
                    errorEdit: '#edit-t1_telefone_error'
                },
                't1_email': {
                    add: '#t1_email',
                    edit: '#edit-t1_email',
                    errorAdd: '#t1_email_error',
                    errorEdit: '#edit-t1_email_error'
                },
                't1_observacao': {
                    add: '#t1_observacao',
                    edit: '#edit-t1_observacao',
                    errorAdd: '#t1_observacao_error',
                    errorEdit: '#edit-t1_observacao_error'
                }
            };

            for (const field in messages) {
                if (!messages.hasOwnProperty(field)) continue;
                const msg = messages[field];
                const map = mapping[field];
                if (map) {
                    const selector = type === 'add' ? map.add : map.edit;
                    const errSel = type === 'add' ? map.errorAdd : map.errorEdit;
                    if (selector) $(selector).addClass('is-invalid');
                    if (errSel) $(errSel).text(msg).show();
                } else {
                    showAlert('error', msg);
                }
            }
        }

        // showAlert helper (same used in other views)
        function showAlert(type, message) {
            $('.alert').remove();
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
            setTimeout(() => {
                $alertDiv.alert('close');
            }, 5000);
        }
    </script>
    <?= $this->endSection() ?>