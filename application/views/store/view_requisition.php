<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h2>
				<?= t('requisitions') ?> #<?= (int) $requisition['id'] ?>
				<span class="badge bg-<?= $requisition['status'] === 'pending' ? 'warning' : ($requisition['status'] === 'approved' || $requisition['status'] === 'in_transit' ? 'info' : ($requisition['status'] === 'received' ? 'success' : 'secondary')) ?>">
					<?= html_escape($requisition['status']) ?>
				</span>
			</h2>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<p><strong><?= t('from') ?>:</strong> <?= html_escape($requisition['from_location']) ?> → <?= html_escape($requisition['to_location']) ?></p>
			<p><strong><?= t('requested_by') ?>:</strong> <?= html_escape($requisition['first_name'] . ' ' . $requisition['last_name']) ?></p>
			<?php if ($requisition['approver_first']): ?>
				<p><strong><?= t('approved_by') ?>:</strong> <?= html_escape($requisition['approver_first'] . ' ' . $requisition['approver_last']) ?></p>
			<?php endif; ?>
		</div>
		<div class="col-md-6">
			<p><strong><?= t('Date') ?>:</strong> <?= html_escape(to_shamsi($requisition['created_at'])) ?></p>
			<?php if ($requisition['status'] === 'rejected' && $requisition['reject_reason']): ?>
				<p><strong><?= t('reject_reason') ?>:</strong> <?= html_escape($requisition['reject_reason']) ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="table-responsive mb-3">
		<table class="table">
			<thead class="table-light">
				<tr>
					<th><?= t('product') ?></th>
					<th><?= t('variant') ?></th>
					<th><?= t('qty_requested') ?></th>
					<th><?= t('qty_approved') ?></th>
					<th><?= t('qty_received') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($items as $item): ?>
					<tr>
						<td><?= html_escape($item['product_name']) ?></td>
						<td><?= html_escape($item['variant_label']) ?></td>
						<td><?= (int) $item['qty_requested'] ?></td>
						<td><?= $item['qty_approved'] !== NULL ? (int) $item['qty_approved'] : '—' ?></td>
						<td><?= $item['qty_received'] !== NULL ? (int) $item['qty_received'] : '—' ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="d-flex gap-2">
		<?php if ($requisition['status'] === 'pending' && $this->auth->has_permission('approve_store_requisition')): ?>
			<a href="<?= site_url('store/approve_requisition/' . $requisition['id']) ?>" class="btn btn-outline-warning btn-icon"><i class="bi bi-check-circle" aria-hidden="true"></i> <?= t('approve') ?></a>
		<?php elseif ($requisition['status'] === 'in_transit' && $this->auth->has_permission('manage_store')): ?>
			<a href="<?= site_url('store/receive_requisition/' . $requisition['id']) ?>" class="btn btn-outline-success btn-icon"><i class="bi bi-box-seam" aria-hidden="true"></i> <?= t('receive') ?></a>
		<?php endif; ?>
		<a href="<?= site_url('store/requisitions') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
	</div>
</div>
