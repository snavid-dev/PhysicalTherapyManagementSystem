<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h2><?= t('stock_receipt') ?> #<?= (int) $receipt['id'] ?></h2>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<p><strong><?= t('supplier') ?>:</strong> <?= html_escape($receipt['supplier_name'] ?: t('no_supplier')) ?></p>
			<p><strong><?= t('received_by') ?>:</strong> <?= html_escape($receipt['first_name'] . ' ' . $receipt['last_name']) ?></p>
		</div>
		<div class="col-md-6">
			<p><strong><?= t('date') ?>:</strong> <?= html_escape(to_shamsi($receipt['created_at'])) ?></p>
			<p><strong><?= t('receipt_number') ?>:</strong> #<?= (int) $receipt['id'] ?></p>
		</div>
	</div>

	<div class="table-responsive mb-3">
		<table class="table">
			<thead class="table-light">
				<tr>
					<th><?= t('product') ?></th>
					<th><?= t('variant') ?></th>
					<th><?= t('qty') ?></th>
					<th><?= t('unit_cost') ?></th>
					<th><?= t('total') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; foreach ($items as $item): $line = (int) $item['qty'] * round((float) $item['unit_cost'], 2); $total += $line; ?>
					<tr>
						<td><?= html_escape($item['product_name']) ?></td>
						<td><?= html_escape($item['variant_label']) ?></td>
						<td><?= (int) $item['qty'] ?></td>
						<td><?= number_format($item['unit_cost'], 2) ?> AFN</td>
						<td><?= number_format($line, 2) ?> AFN</td>
					</tr>
				<?php endforeach; ?>
				<tr class="table-active">
					<td colspan="4" class="text-end"><strong><?= t('total') ?>:</strong></td>
					<td><strong><?= number_format($total, 2) ?> AFN</strong></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="d-flex gap-2">
		<a href="<?= site_url('store/stock_receipts') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
	</div>
</div>
