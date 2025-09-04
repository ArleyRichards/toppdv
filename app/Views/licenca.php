<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>

<div class="container" style="margin-top: 15px;">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Informações da Licença</h5>
					<div class="table-responsive">
						<table class="table table-hover mt-2">
							<thead>
								<tr>
									<th scope="col">Usuário da Licença</th>
									<th scope="col">Data de Ativação do Sistema</th>
									<th scope="col">Data da Última Renovação</th>
									<th scope="col">Data da Próxima Renovação</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$licenses = $result ?? [];
								$user = $user_logged ?? null;
								if (empty($licenses)) : ?>
									<tr>
										<td colspan="4" class="text-center text-muted">Nenhuma licença encontrada</td>
									</tr>
								<?php else:
									foreach ($licenses as $license) : ?>
										<tr>
											<td><?= esc($user->u1_usuario_acesso ?? $user->usuario_acesso ?? '-') ?></td>
											<td><?= isset($license->l2_data_ativacao_sistema) && $license->l2_data_ativacao_sistema ? date('d/m/Y H:i:s', strtotime($license->l2_data_ativacao_sistema)) : '-' ?></td>
											<td><?= isset($license->l2_data_ultima_renovacao) && $license->l2_data_ultima_renovacao ? date('d/m/Y H:i:s', strtotime($license->l2_data_ultima_renovacao)) : '-' ?></td>
											<td><?= isset($license->l2_data_proxima_renovacao) && $license->l2_data_proxima_renovacao ? date('d/m/Y H:i:s', strtotime($license->l2_data_proxima_renovacao)) : '-' ?></td>
										</tr>
								<?php
									endforeach;
								endif;
								?>
							</tbody>
						</table>
					</div>

					<div class="mt-3">
						<h6>Para renovar sua licença, clique no botão abaixo para gerar a chave PIX.</h6>
						<div class="mt-2">
							<button type="button" class="btn btn-lg btn-primary" id="generatePixBtn">Gerar chave PIX</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>
<script>
	function keyPix() {
		if (window.Swal) {
			Swal.fire({
				title: 'Chave Pix',
				text: 'Chave Pix: SOMENTE TESTE',
				icon: 'info',
				showCancelButton: false,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Fechar',
			});
			return;
		}
		alert('Chave Pix: SOMENTE TESTE');
	}

	document.addEventListener('DOMContentLoaded', function () {
		const btn = document.getElementById('generatePixBtn');
		if (btn) btn.addEventListener('click', keyPix);
	});
</script>
<?= $this->endSection() ?>
