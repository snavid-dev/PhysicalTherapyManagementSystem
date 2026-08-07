<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('stock_receipts') ?></h1>
		</div>
		<div class="col-auto">
			<a href="<?= site_url('store/receive_stock') ?>" class="btn btn-primary btn-icon"><i class="bi bi-box-seam" aria-hidden="true"></i> <?= t('receive_stock') ?></a>
		</div>
	</div>

	<?php if (empty($receipts)): ?>
		<div class="alert alert-info"><?= t('no_receipts_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('id') ?></th>
						<th><?= t('supplier') ?></th>
						<th><?= t('received_by') ?></th>
						<th><?= t('date') ?></th>
						<th><?= t('action') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($receipts as $r): ?>
						<tr>
							<td>#<?= (int) $r['id'] ?></td>
							<td><?= html_escape($r['supplier_name'] ?: t('no_supplier')) ?></td>
							<td><?= html_escape($r['first_name'] . ' ' . $r['last_name']) ?></td>
							<td><?= html_escape(to_shamsi($r['created_at'])) ?></td>
							<td>
								<a href="<?= site_url('store/view_stock_receipt/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary btn-icon"><i class="bi bi-receipt" aria-hidden="true"></i> <?= t('view') ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
