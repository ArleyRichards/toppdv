<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top:10px; padding:15px;">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="fa-solid fa-user text-primary me-2"></i> Meu Perfil</h2>
            <p class="text-muted" style="font-size:14px;">Atualize suas informações pessoais.</p>
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
            <form method="post" action="<?= site_url('perfil/salvar') ?>" class="row g-3 needs-validation" novalidate>
                <?= csrf_field() ?>
                <?php $usuario = $usuario ?? null; ?>

                <div class="col-md-6">
                    <label for="u1_nome" class="form-label">Nome Completo</label>
                    <input type="text" id="u1_nome" name="u1_nome" class="form-control" required value="<?= old('u1_nome') ?? ($usuario->u1_nome ?? session('user_nome') ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="u1_cpf" class="form-label">CPF</label>
                    <input type="text" id="u1_cpf" name="u1_cpf" class="form-control" maxlength="14" value="<?= old('u1_cpf') ?? ($usuario->u1_cpf ?? '') ?>" placeholder="000.000.000-00">
                </div>

                <div class="col-md-3">
                    <label for="u1_usuario_acesso" class="form-label">Usuário (login)</label>
                    <input type="text" id="u1_usuario_acesso" name="u1_usuario_acesso" class="form-control" required value="<?= old('u1_usuario_acesso') ?? ($usuario->u1_usuario_acesso ?? session('user_usuario') ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label for="u1_email" class="form-label">E-mail</label>
                    <input type="email" id="u1_email" name="u1_email" class="form-control" required value="<?= old('u1_email') ?? ($usuario->u1_email ?? session('user_email') ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="u1_senha_usuario" class="form-label">Senha (deixe em branco para manter)</label>
                    <input type="password" id="u1_senha_usuario" name="u1_senha_usuario" class="form-control" minlength="6" placeholder="Nova senha">
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Atualizar Perfil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>
<script>
    // utilizar jQuery Mask para CPF
    $(function(){
        if ($.fn && $.fn.mask) {
            $('#u1_cpf').mask('000.000.000-00');
        }
    });
</script>

<?= $this->endSection() ?>
