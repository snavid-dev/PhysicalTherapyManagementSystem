<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<h2><?= t('receive_stock') ?></h2>

	<form method="post" class="card">
		<div class="card-body">
			<div class="mb-3">
				<label class="form-label"><?= t('supplier') ?></label>
				<select name="supplier_id" class="form-select">
					<option value="">— <?= t('no_supplier') ?> —</option>
					<?php foreach ($suppliers as $s): ?>
						<option value="<?= $s['id'] ?>"><?= html_escape($s['name']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="table-responsive mb-3">
				<table class="table" id="receipt-items">
					<thead>
						<tr>
							<th><?= t('variant') ?></th>
							<th style="width:110px"><?= t('qty') ?></th>
							<th style="width:130px"><?= t('unit_cost') ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody id="items-tbody"></tbody>
				</table>
			</div>

			<button type="button" class="btn btn-sm btn-outline-success mb-3 btn-icon" id="add-row"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_line') ?></button>

			<div class="mb-3">
				<label class="form-label"><?= t('note') ?></label>
				<textarea name="note" class="form-control" rows="2"></textarea>
			</div>

			<div class="d-flex gap-2">
				<button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-box-seam" aria-hidden="true"></i> <?= t('receive_stock') ?></button>
				<a href="<?= site_url('store/stock_receipts') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('cancel') ?></a>
			</div>
		</div>
	</form>
</div>

<template id="row-template">
	<tr class="item-row">
		<td>
			<select name="variant_id[]" class="form-select s2-select" data-placeholder="<?= html_escape(t('select_variant')) ?>" required>
				<option value=""></option>
				<?php foreach ($products as $product): ?>
					<?php foreach ($product['variants'] as $variant): ?>
						<option value="<?= (int) $variant['id'] ?>" data-cost="<?= (float) $variant['cost_price'] ?>">
							<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>
						</option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
		</td>
		<td><input type="number" name="qty[]" class="form-control" min="1" value="1" required></td>
		<td><input type="number" name="unit_cost[]" class="form-control cost-input" step="0.01" value="0" required></td>
		<td><button type="button" class="btn btn-sm btn-outline-danger remove-row btn-icon"><i class="bi bi-trash" aria-hidden="true"></i> <?= t('remove') ?></button></td>
	</tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const tbody = document.getElementById('items-tbody');
	const template = document.getElementById('row-template');

	function addRow() {
		const row = template.content.firstElementChild.cloneNode(true);
		tbody.appendChild(row);

		const select = row.querySelector('select');
		if (window.initSelect2) initSelect2(row);

		jQuery(select).on('select2:select', function () {
			const opt = select.options[select.selectedIndex];
			if (opt.dataset.cost) {
				row.querySelector('.cost-input').value = parseFloat(opt.dataset.cost).toFixed(2);
			}
		});

		row.querySelector('.remove-row').addEventListener('click', function () {
			row.remove();
		});
	}

	document.getElementById('add-row').addEventListener('click', addRow);
	addRow();
});
</script>
