<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('sale_batches') ?></h1>
		</div>
		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-auto">
			<a href="<?= site_url('store/bulk_sell') ?>" class="btn btn-primary btn-icon"><i class="bi bi-cart" aria-hidden="true"></i> <?= t('bulk_sell') ?></a>
		</div>
		<?php endif; ?>
	</div>

	<?php if ($msg = $this->session->flashdata('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= html_escape($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>
	<?php if ($msg = $this->session->flashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= html_escape($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>

	<?php if (empty($batches)): ?>
		<div class="alert alert-info"><?= t('no_sale_batches_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('id') ?></th>
						<th><?= t('submitted_by') ?></th>
						<th><?= t('total_amount') ?></th>
						<th><?= t('Status') ?></th>
						<th><?= t('Date') ?></th>
						<th class="no-export"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($batches as $batch): ?>
						<tr>
							<td><?= (int) $batch['id'] ?></td>
							<td><?= html_escape($batch['first_name'] . ' ' . $batch['last_name']) ?></td>
							<td><?= number_format($batch['total_amount'], 2) ?> AFN</td>
							<td>
								<span class="badge bg-<?= $batch['status'] === 'pending' ? 'warning' : ($batch['status'] === 'approved' ? 'success' : 'secondary') ?>">
									<?= html_escape(t('sale_batch_status_' . $batch['status'])) ?>
								</span>
							</td>
							<td><?= html_escape(to_shamsi($batch['created_at'])) ?></td>
							<td>
								<?php if ($batch['status'] === 'pending' && $this->auth->has_permission('approve_store_sale_batch')): ?>
									<a href="<?= site_url('store/approve_sale_batch/' . $batch['id']) ?>" class="btn btn-sm btn-outline-warning btn-icon"><i class="bi bi-check-circle" aria-hidden="true"></i> <?= t('approve') ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
