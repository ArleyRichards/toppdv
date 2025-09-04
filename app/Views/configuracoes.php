<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top:10px; padding:15px;">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="fa-solid fa-cog text-primary me-2"></i> Configurações do Sistema</h2>
            <p class="text-muted" style="font-size:14px;">Ajuste as configurações gerais do sistema abaixo.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-home me-1"></i> Menu</a>
        </div>
    </div>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>

    <?php $errors = session('errors') ?: (isset($validation) ? $validation : null); ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ((array)$errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= site_url('configuracoes/salvar') ?>" class="row g-3 needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="col-md-6">
                    <label for="c3_nome_app" class="form-label">Nome do App</label>
                    <input type="text" id="c3_nome_app" name="c3_nome_app" class="form-control" required value="<?= old('c3_nome_app') ?? ($configuracoes->c3_nome_app ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_versao_app" class="form-label">Versão</label>
                    <input type="text" id="c3_versao_app" name="c3_versao_app" class="form-control" value="<?= old('c3_versao_app') ?? ($configuracoes->c3_versao_app ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="c3_nome_empresa" class="form-label">Nome da Empresa</label>
                    <input type="text" id="c3_nome_empresa" name="c3_nome_empresa" class="form-control" required value="<?= old('c3_nome_empresa') ?? ($configuracoes->c3_nome_empresa ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="c3_cnpj_empresa" class="form-label">CNPJ da Empresa</label>
                    <input type="text" id="c3_cnpj_empresa" name="c3_cnpj_empresa" class="form-control" maxlength="18" value="<?= old('c3_cnpj_empresa') ?? ($configuracoes->c3_cnpj_empresa ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="c3_email_contato" class="form-label">E-mail de Contato</label>
                    <input type="email" id="c3_email_contato" name="c3_email_contato" class="form-control" required value="<?= old('c3_email_contato') ?? ($configuracoes->c3_email_contato ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="c3_telefone_empresa" class="form-label">Telefone da Empresa</label>
                    <input type="text" id="c3_telefone_empresa" name="c3_telefone_empresa" class="form-control" maxlength="15" value="<?= old('c3_telefone_empresa') ?? ($configuracoes->c3_telefone_empresa ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label for="c3_site_empresa" class="form-label">Site da Empresa</label>
                    <input type="url" id="c3_site_empresa" name="c3_site_empresa" class="form-control" value="<?= old('c3_site_empresa') ?? ($configuracoes->c3_site_empresa ?? '') ?>">
                </div>

                <div class="col-12">
                    <label for="c3_endereco_empresa" class="form-label">Endereço da Empresa</label>
                    <textarea id="c3_endereco_empresa" name="c3_endereco_empresa" class="form-control" rows="3"><?= old('c3_endereco_empresa') ?? ($configuracoes->c3_endereco_empresa ?? '') ?></textarea>
                </div>

                <div class="col-md-3">
                    <label for="c3_logo_path" class="form-label">Logo Path</label>
                    <input type="text" id="c3_logo_path" name="c3_logo_path" class="form-control" value="<?= old('c3_logo_path') ?? ($configuracoes->c3_logo_path ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="c3_favicon_path" class="form-label">Favicon Path</label>
                    <input type="text" id="c3_favicon_path" name="c3_favicon_path" class="form-control" value="<?= old('c3_favicon_path') ?? ($configuracoes->c3_favicon_path ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="c3_timezone" class="form-label">Timezone</label>
                    <input type="text" id="c3_timezone" name="c3_timezone" class="form-control" required value="<?= old('c3_timezone') ?? ($configuracoes->c3_timezone ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_idioma" class="form-label">Idioma</label>
                    <input type="text" id="c3_idioma" name="c3_idioma" class="form-control" value="<?= old('c3_idioma') ?? ($configuracoes->c3_idioma ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_moeda" class="form-label">Moeda</label>
                    <input type="text" id="c3_moeda" name="c3_moeda" class="form-control" value="<?= old('c3_moeda') ?? ($configuracoes->c3_moeda ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_simbolo_moeda" class="form-label">Símbolo</label>
                    <input type="text" id="c3_simbolo_moeda" name="c3_simbolo_moeda" class="form-control" value="<?= old('c3_simbolo_moeda') ?? ($configuracoes->c3_simbolo_moeda ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_casas_decimais" class="form-label">Casas Decimais</label>
                    <select id="c3_casas_decimais" name="c3_casas_decimais" class="form-select">
                        <?php for ($i=0;$i<=3;$i++): ?>
                            <option value="<?= $i ?>" <?= ((old('c3_casas_decimais') ?? ($configuracoes->c3_casas_decimais ?? '')) == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="c3_separador_decimal" class="form-label">Separador Decimal</label>
                    <input type="text" id="c3_separador_decimal" name="c3_separador_decimal" class="form-control" maxlength="1" value="<?= old('c3_separador_decimal') ?? ($configuracoes->c3_separador_decimal ?? ',') ?>">
                </div>

                <div class="col-md-2">
                    <label for="c3_separador_milhar" class="form-label">Separador Milhar</label>
                    <input type="text" id="c3_separador_milhar" name="c3_separador_milhar" class="form-control" maxlength="1" value="<?= old('c3_separador_milhar') ?? ($configuracoes->c3_separador_milhar ?? '.') ?>">
                </div>

                <div class="col-md-3">
                    <label for="c3_tema" class="form-label">Tema</label>
                    <select id="c3_tema" name="c3_tema" class="form-select">
                        <option value="light" <?= ((old('c3_tema') ?? ($configuracoes->c3_tema ?? ''))=='light')?'selected':'' ?>>Light</option>
                        <option value="dark" <?= ((old('c3_tema') ?? ($configuracoes->c3_tema ?? ''))=='dark')?'selected':'' ?>>Dark</option>
                        <option value="auto" <?= ((old('c3_tema') ?? ($configuracoes->c3_tema ?? ''))=='auto')?'selected':'' ?>>Auto</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="c3_limite_backup" class="form-label">Limite de Backups (dias)</label>
                    <input type="number" id="c3_limite_backup" name="c3_limite_backup" class="form-control" min="1" value="<?= old('c3_limite_backup') ?? ($configuracoes->c3_limite_backup ?? 30) ?>">
                </div>

                <div class="col-md-6">
                    <label for="c3_email_notificacoes" class="form-label">E-mail Notificações</label>
                    <input type="email" id="c3_email_notificacoes" name="c3_email_notificacoes" class="form-control" value="<?= old('c3_email_notificacoes') ?? ($configuracoes->c3_email_notificacoes ?? '') ?>">
                </div>

                <div class="col-12"><hr></div>

                <div class="col-md-4">
                    <label for="c3_smtp_host" class="form-label">SMTP Host</label>
                    <input type="text" id="c3_smtp_host" name="c3_smtp_host" class="form-control" value="<?= old('c3_smtp_host') ?? ($configuracoes->c3_smtp_host ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="c3_smtp_port" class="form-label">SMTP Port</label>
                    <input type="number" id="c3_smtp_port" name="c3_smtp_port" class="form-control" value="<?= old('c3_smtp_port') ?? ($configuracoes->c3_smtp_port ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="c3_smtp_usuario" class="form-label">SMTP Usuário</label>
                    <input type="text" id="c3_smtp_usuario" name="c3_smtp_usuario" class="form-control" value="<?= old('c3_smtp_usuario') ?? ($configuracoes->c3_smtp_usuario ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="c3_smtp_senha" class="form-label">SMTP Senha</label>
                    <input type="password" id="c3_smtp_senha" name="c3_smtp_senha" class="form-control" value="<?= old('c3_smtp_senha') ?? ($configuracoes->c3_smtp_senha ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="c3_smtp_criptografia" class="form-label">Criptografia SMTP</label>
                    <select id="c3_smtp_criptografia" name="c3_smtp_criptografia" class="form-select">
                        <option value="ssl" <?= ((old('c3_smtp_criptografia') ?? ($configuracoes->c3_smtp_criptografia ?? ''))=='ssl')?'selected':'' ?>>SSL</option>
                        <option value="tls" <?= ((old('c3_smtp_criptografia') ?? ($configuracoes->c3_smtp_criptografia ?? ''))=='tls')?'selected':'' ?>>TLS</option>
                        <option value="none" <?= ((old('c3_smtp_criptografia') ?? ($configuracoes->c3_smtp_criptografia ?? ''))=='none')?'selected':'' ?>>Nenhuma</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="c3_status_loja" class="form-label">Status da Loja</label>
                    <select id="c3_status_loja" name="c3_status_loja" class="form-select">
                        <option value="aberta" <?= ((old('c3_status_loja') ?? ($configuracoes->c3_status_loja ?? ''))=='aberta')?'selected':'' ?>>Aberta</option>
                        <option value="fechada" <?= ((old('c3_status_loja') ?? ($configuracoes->c3_status_loja ?? ''))=='fechada')?'selected':'' ?>>Fechada</option>
                        <option value="manutencao" <?= ((old('c3_status_loja') ?? ($configuracoes->c3_status_loja ?? ''))=='manutencao')?'selected':'' ?>>Manutenção</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="c3_mensagem_manutencao" class="form-label">Mensagem de Manutenção</label>
                    <textarea id="c3_mensagem_manutencao" name="c3_mensagem_manutencao" class="form-control" rows="4"><?= old('c3_mensagem_manutencao') ?? ($configuracoes->c3_mensagem_manutencao ?? '') ?></textarea>
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Salvar Configurações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>
<script>
    // pequenas máscaras para CNPJ e telefone
    $(document).ready(function(){
        function maskValue(value, mask){
            value = String(value).replace(/\D/g,'');
            let masked = '';
            let idx = 0;
            for (let i=0;i<mask.length && idx<value.length;i++){
                if (mask[i] === '0') { masked += value[idx++]; } else { masked += mask[i]; }
            }
            return masked;
        }

        $('#c3_cnpj_empresa').on('input', function(){
            $(this).val(maskValue($(this).val(), '00.000.000/0000-00'));
        });

        $('#c3_telefone_empresa').on('input', function(){
            const v = String($(this).val()).replace(/\D/g,'');
            const mask = v.length>10 ? '(00) 00000-0000' : '(00) 0000-0000';
            $(this).val(maskValue(v, mask));
        });
    });
</script>

<?= $this->endSection() ?>
