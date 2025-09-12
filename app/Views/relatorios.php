<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-chart-line text-primary me-2"></i> Relatórios</h2>
            <p class="text-muted" style="font-size: 14px;">Visualize e gere relatórios do sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm">Voltar</a>
            </div>
        </div>
    </div>

    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Relatórios</h5>
        </div>
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="relatoriosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-vendas-tab" data-bs-toggle="tab" data-bs-target="#tab-vendas" type="button" role="tab">1 - Vendas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-ordens-tab" data-bs-toggle="tab" data-bs-target="#tab-ordens" type="button" role="tab">2 - Ordens de Serviço</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-comissoes-tab" data-bs-toggle="tab" data-bs-target="#tab-comissoes" type="button" role="tab">3 - Comissões por Vendedor</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-livro-tab" data-bs-toggle="tab" data-bs-target="#tab-livro" type="button" role="tab">4 - Livro de Caixa</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <!-- VENDAS -->
                <div class="tab-pane fade show active" id="tab-vendas" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Relatório de Vendas</h6></div>
                        <div class="card-body">
                            <form id="relatorioVendasForm">
                                <div id="relatorioAlert" class="alert alert-warning d-none" role="alert"></div>

                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="data_inicial" class="form-label">Data Inicial</label>
                                        <input type="date" id="data_inicial" name="data_inicial" class="form-control" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="data_final" class="form-label">Data Final</label>
                                        <input type="date" id="data_final" name="data_final" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status_select" class="form-label">Status</label>
                                        <select id="status_select" name="status" class="form-select">
                                            <option value="">Todos</option>
                                            <?php if (!empty($statuses) && is_array($statuses)): ?>
                                                <?php foreach ($statuses as $s): ?>
                                                    <option value="<?= esc($s) ?>"><?= esc($s) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Filtre por status (opcional)</div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" id="gerarRelatorioVendas" class="btn btn-primary mt-2" disabled>
                                            <span id="gerarSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label for="clientes_select" class="form-label">Clientes <small class="text-muted" id="clientesCount">(0 selecionados)</small></label>
                                        <select multiple="multiple" size="12" name="clientes[]" id="clientes_select" class="form-control dual-list">
                                            <?php if (!empty($clientes) && is_array($clientes)): ?>
                                                <?php foreach ($clientes as $c): ?>
                                                    <option value="<?= esc($c->id) ?>"><?= esc($c->nome) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecione os clientes (necessário).</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="vendedores_select" class="form-label">Vendedores <small class="text-muted" id="vendedoresCount">(0 selecionados)</small></label>
                                        <select multiple="multiple" size="12" name="vendedores[]" id="vendedores_select" class="form-control dual-list">
                                            <?php if (!empty($vendedores) && is_array($vendedores)): ?>
                                                <?php foreach ($vendedores as $v): ?>
                                                    <option value="<?= esc($v->id) ?>"><?= esc($v->nome) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecione os vendedores (opcional).</div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- ORDENS -->
                <div class="tab-pane fade" id="tab-ordens" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Relatório de Ordens de Serviço</h6></div>
                        <div class="card-body">
                            <form id="relatorioOrdensForm">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="ord_data_inicial" class="form-label">Data Inicial</label>
                                        <input type="date" id="ord_data_inicial" name="data_inicial" class="form-control" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="ord_data_final" class="form-label">Data Final</label>
                                        <input type="date" id="ord_data_final" name="data_final" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ord_status_select" class="form-label">Status</label>
                                        <select id="ord_status_select" name="status" class="form-select">
                                            <option value="">Todos</option>
                                            <?php if (!empty($statuses) && is_array($statuses)): ?>
                                                <?php foreach ($statuses as $s): ?>
                                                    <option value="<?= esc($s) ?>"><?= esc($s) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" id="gerarRelatorioOrdens" class="btn btn-primary mt-2">Gerar PDF</button>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label for="ord_clientes_select" class="form-label">Clientes <small class="text-muted" id="ordClientesCount">(0 selecionados)</small></label>
                                        <select multiple="multiple" size="12" name="clientes[]" id="ord_clientes_select" class="form-control dual-list">
                                            <?php if (!empty($clientes) && is_array($clientes)): ?>
                                                <?php foreach ($clientes as $c): ?>
                                                    <option value="<?= esc($c->id) ?>"><?= esc($c->nome) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecione os clientes (opcional).</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="ord_tecnicos_select" class="form-label">Técnicos <small class="text-muted" id="ordTecnicosCount">(0 selecionados)</small></label>
                                        <select multiple="multiple" size="12" name="tecnicos[]" id="ord_tecnicos_select" class="form-control dual-list">
                                            <?php if (!empty($vendedores) && is_array($vendedores)): ?>
                                                <?php foreach ($vendedores as $v): ?>
                                                    <option value="<?= esc($v->id) ?>"><?= esc($v->nome) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecione os técnicos (opcional).</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- COMISSOES -->
                <div class="tab-pane fade" id="tab-comissoes" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Relatório de Comissões por Vendedor</h6></div>
                        <div class="card-body">
                            <form id="relatorioComissoesForm">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="com_data_inicial" class="form-label">Data Inicial</label>
                                        <input type="date" id="com_data_inicial" name="data_inicial" class="form-control" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="com_data_final" class="form-label">Data Final</label>
                                        <input type="date" id="com_data_final" name="data_final" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" id="gerarRelatorioComissoes" class="btn btn-primary mt-2">Gerar PDF</button>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-12">
                                        <label for="com_vendedores_select" class="form-label">Vendedores <small class="text-muted" id="comVendedoresCount">(0 selecionados)</small></label>
                                        <select multiple="multiple" size="12" name="vendedores[]" id="com_vendedores_select" class="form-control dual-list">
                                            <?php if (!empty($vendedores) && is_array($vendedores)): ?>
                                                <?php foreach ($vendedores as $v): ?>
                                                    <option value="<?= esc($v->id) ?>"><?= esc($v->nome) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Selecione os vendedores (opcional).</div>
                                    </div>
                                </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" id="gerarRelatorioComissoes" class="btn btn-primary mt-2">Gerar PDF</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- LIVRO DE CAIXA -->
                <div class="tab-pane fade" id="tab-livro" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Livro de Caixa</h6></div>
                        <div class="card-body">
                            <form id="relatorioLivroCaixaForm">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="liv_data_inicial" class="form-label">Data Inicial</label>
                                        <input type="date" id="liv_data_inicial" name="data_inicial" class="form-control" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="liv_data_final" class="form-label">Data Final</label>
                                        <input type="date" id="liv_data_final" name="data_final" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" id="gerarRelatorioLivro" class="btn btn-primary mt-2">Gerar PDF</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-duallistbox@4.0.2/dist/bootstrap-duallistbox.min.css">
<style>
.dual-list { min-height: 180px; }
.bootstrap-duallistbox-container .box1, .bootstrap-duallistbox-container .box2 { max-height: 320px; overflow: auto; }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-duallistbox@4.0.2/dist/jquery.bootstrap-duallistbox.min.js"></script>

<script>
(function(){
    'use strict';

    // Initialize dual-list boxes for selects that exist
    try {
        const $select = $('#clientes_select');
        if ($select.length && $.fn.bootstrapDualListbox) {
            $select.bootstrapDualListbox({
                nonSelectedListLabel: 'Todos os clientes',
                selectedListLabel: 'Clientes selecionados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                selectorMinimalHeight: 200
            });
        }

        const $vselect = $('#vendedores_select');
        if ($vselect.length && $.fn.bootstrapDualListbox) {
            $vselect.bootstrapDualListbox({
                nonSelectedListLabel: 'Todos os vendedores',
                selectedListLabel: 'Vendedores selecionados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                selectorMinimalHeight: 200
            });
        }
        const $ordC = $('#ord_clientes_select');
        if ($ordC.length && $.fn.bootstrapDualListbox) {
            $ordC.bootstrapDualListbox({
                nonSelectedListLabel: 'Todos os clientes',
                selectedListLabel: 'Clientes selecionados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                selectorMinimalHeight: 200
            });
        }

        const $ordT = $('#ord_tecnicos_select');
        if ($ordT.length && $.fn.bootstrapDualListbox) {
            $ordT.bootstrapDualListbox({
                nonSelectedListLabel: 'Todos os técnicos',
                selectedListLabel: 'Técnicos selecionados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                selectorMinimalHeight: 200
            });
        }

        const $comV = $('#com_vendedores_select');
        if ($comV.length && $.fn.bootstrapDualListbox) {
            $comV.bootstrapDualListbox({
                nonSelectedListLabel: 'Todos os vendedores',
                selectedListLabel: 'Vendedores selecionados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                selectorMinimalHeight: 200
            });
        }
    } catch (e) {
        console.error('Erro ao inicializar dual listbox:', e);
    }

    // VENDAS: state handling and PDF generation
    function updateVendasState(){
        const dataInicial = $('#data_inicial').val();
        const dataFinal = $('#data_final').val();
        const clientes = $('#clientes_select').val() || [];
        const vendedores = $('#vendedores_select').val() || [];
        const btn = $('#gerarRelatorioVendas');

        $('#clientesCount').text(`(${clientes.length} selecionados)`);
        $('#vendedoresCount').text(`(${vendedores.length} selecionados)`);

        let valid = true;
        if (dataInicial && dataFinal) {
            valid = (new Date(dataInicial) <= new Date(dataFinal));
        }

        if (valid) btn.removeAttr('disabled'); else btn.attr('disabled', 'disabled');
    }

    $('#data_inicial, #data_final').on('change', updateVendasState);
    $('#clientes_select').on('change', updateVendasState);
    $('#vendedores_select').on('change', updateVendasState);
    updateVendasState();

    $('#gerarRelatorioVendas').on('click', function(){
        try {
            const $btn = $(this);
            const $spinner = $('#gerarSpinner');

            $spinner.removeClass('d-none');
            $btn.attr('disabled', 'disabled');

            const dataInicial = $('#data_inicial').val();
            const dataFinal = $('#data_final').val();
            const clientes = $('#clientes_select').val() || [];
            const vendedores = $('#vendedores_select').val() || [];
            const status = $('#status_select').val() || '';

            const $inlineAlert = $('#relatorioAlert');
            if (!clientes.length) {
                $spinner.addClass('d-none');
                $inlineAlert.removeClass('d-none').text('Selecione pelo menos um cliente para gerar o relatório.');
                updateVendasState();
                return;
            } else {
                $inlineAlert.addClass('d-none').text('');
            }

            const params = new URLSearchParams();
            if (dataInicial) params.append('data_inicial', dataInicial);
            if (dataFinal) params.append('data_final', dataFinal);
            clientes.forEach(id => params.append('clientes[]', id));
            vendedores.forEach(id => params.append('vendedores[]', id));
            if (status) params.append('status', status);

            const url = `<?= site_url('relatorios/vendas/pdf') ?>` + '?' + params.toString();

            fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => {
                    if (!response.ok) return response.json().then(j => { throw j; });
                    const win = window.open(url, '_blank');
                    setTimeout(function(){
                        $spinner.addClass('d-none');
                        updateVendasState();
                        if (win) win.focus();
                    }, 800);
                }).catch(err => {
                    $spinner.addClass('d-none');
                    $('#relatorioAlert').removeClass('d-none').text(err.error || 'Erro ao gerar relatório');
                    updateVendasState();
                });

        } catch (err) {
            console.error('Erro ao solicitar relatório:', err);
            $('#relatorioAlert').removeClass('d-none').text('Erro ao solicitar relatório (ver console)');
        }
    });

    // ORDENS: simple handler (placeholder) — wire to your real endpoint later
    $('#gerarRelatorioOrdens').on('click', function(){
        const params = new URLSearchParams();
        if ($('#ord_data_inicial').val()) params.append('data_inicial', $('#ord_data_inicial').val());
        if ($('#ord_data_final').val()) params.append('data_final', $('#ord_data_final').val());
        if ($('#ord_status_select').val()) params.append('status', $('#ord_status_select').val());
        // clientes[]
        const ordClientes = $('#ord_clientes_select').val() || [];
        ordClientes.forEach(id => params.append('clientes[]', id));
        // tecnicos[]
        const ordTecnicos = $('#ord_tecnicos_select').val() || [];
        ordTecnicos.forEach(id => params.append('tecnicos[]', id));

        const url = `<?= site_url('relatorios/ordens/pdf') ?>` + '?' + params.toString();
        window.open(url, '_blank');
    });

    // COMISSÕES: simple handler
    $('#gerarRelatorioComissoes').on('click', function(){
        const params = new URLSearchParams();
        if ($('#com_data_inicial').val()) params.append('data_inicial', $('#com_data_inicial').val());
        if ($('#com_data_final').val()) params.append('data_final', $('#com_data_final').val());
        const comVendedores = $('#com_vendedores_select').val() || [];
        comVendedores.forEach(id => params.append('vendedores[]', id));
        const url = `<?= site_url('relatorios/comissoes/pdf') ?>` + '?' + params.toString();
        window.open(url, '_blank');
    });

    // LIVRO DE CAIXA: simple handler
    $('#gerarRelatorioLivro').on('click', function(){
        const params = new URLSearchParams({
            data_inicial: $('#liv_data_inicial').val() || '',
            data_final: $('#liv_data_final').val() || ''
        });
        const url = `<?= site_url('relatorios/livrocaixa/pdf') ?>` + '?' + params.toString();
        window.open(url, '_blank');
    });

})();
</script>

<?= $this->endSection() ?>
