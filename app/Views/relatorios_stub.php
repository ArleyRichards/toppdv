<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>

<div class="container" style="padding:40px;">
    <h3><?= esc($title ?? 'Relatório') ?></h3>
    <p>Este relatório está sendo preparado. Em breve forneceremos informações completas.</p>
</div>

<?= $this->endSection() ?>
