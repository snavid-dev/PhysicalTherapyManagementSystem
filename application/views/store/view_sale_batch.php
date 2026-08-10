<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h2>
				<?= t('sale_batches') ?> #<?= (int) $batch['id'] ?>
				<span class="badge bg-<?= $batch['status'] === 'pending' ? 'warning' : ($batch['status'] === 'approved' ? 'success' : 'secondary') ?>">
					<?= html_escape(t('sale_batch_status_' . $batch['status'])) ?>
				</span>
			</h2>
			<p class="text-muted mb-0">
				<?= t('submitted_by') ?>: <?= html_escape($batch['first_name'] . ' ' . $batch['last_name']) ?> — <?= html_escape(to_shamsi($batch['created_at'])) ?>
				<?php if ($batch['approver_first']): ?>
					· <?= t('approved_by') ?>: <?= html_escape($batch['approver_first'] . ' ' . $batch['approver_last']) ?>
				<?php endif; ?>
			</p>
			<?php if ($batch['status'] === 'rejected' && $batch['reject_reason']): ?>
				<p class="text-muted mb-0"><?= t('reject_reason') ?>: <?= html_escape($batch['reject_reason']) ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php foreach ($customers as $customer): ?>
		<div class="card mb-3">
			<div class="card-header d-flex justify-content-between">
				<span>
					<?php if ($customer['patient_id']): ?>
						<?= html_escape($customer['first_name'] . ' ' . $customer['last_name']) ?>
					<?php else: ?>
						<?= html_escape($customer['customer_name']) ?> (<?= t('walk_in_customer') ?>)
					<?php endif; ?>
				</span>
				<span class="badge bg-secondary"><?= html_escape(t($customer['payment_method'])) ?></span>
			</div>
			<div class="card-body">
				<table class="table table-sm mb-0">
					<thead>
						<tr>
							<th><?= t('product_variant') ?></th>
							<th class="text-end"><?= t('qty') ?></th>
							<th class="text-end"><?= t('price') ?></th>
							<th class="text-end"><?= t('total') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($customer['items'] as $item): ?>
							<?php $line = $item['qty'] * $item['unit_price']; ?>
							<tr>
								<td><?= html_escape($item['product_name'] . ' - ' . $item['variant_label']) ?></td>
								<td class="text-end"><?= (int) $item['qty'] ?></td>
								<td class="text-end"><?= number_format($item['unit_price'], 2) ?></td>
								<td class="text-end"><?= number_format($line, 2) ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endforeach; ?>

	<div class="d-flex justify-content-between align-items-center">
		<div class="fs-5"><?= t('total_amount') ?>: <strong><?= number_format($batch['total_amount'], 2) ?> AFN</strong></div>
		<div class="d-flex gap-2">
			<?php if ($batch['status'] === 'pending' && $this->auth->has_permission('approve_store_sale_batch')): ?>
				<a href="<?= site_url('store/approve_sale_batch/' . $batch['id']) ?>" class="btn btn-outline-warning btn-icon"><i class="bi bi-check-circle" aria-hidden="true"></i> <?= t('approve') ?></a>
			<?php endif; ?>
			<a href="<?= site_url('store/sale_batches') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
		</div>
	</div>
</div>
