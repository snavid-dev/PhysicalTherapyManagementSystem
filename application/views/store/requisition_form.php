<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<h2><?= t('create_requisition') ?></h2>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('request_to_location') ?></label>
					<select name="to_location_id" class="form-select" required>
						<option value="">— <?= t('select_location') ?> —</option>
						<?php foreach ($locations as $loc): ?>
							<?php if ($loc['type'] !== 'warehouse'): ?>
								<option value="<?= $loc['id'] ?>"><?= html_escape($loc['name']) ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="card mb-3">
					<div class="card-header">
						<h5 class="mb-0"><?= t('line_items') ?></h5>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table mb-0" id="requisition-items">
								<thead>
									<tr>
										<th><?= t('product_variant') ?></th>
										<th style="width:120px"><?= t('quantity') ?></th>
										<th style="width:120px"><?= t('warehouse_available') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="items-tbody"></tbody>
							</table>
						</div>
						<button type="button" class="btn btn-sm btn-outline-success mt-2 btn-icon" id="add-row"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_line') ?></button>
					</div>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i> <?= t('submit_requisition') ?></button>
					<a href="<?= site_url('store/requisitions') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>

<template id="row-template">
	<tr class="item-row">
		<td>
			<select name="variant_id[]" class="form-select s2-select variant-select" data-placeholder="<?= html_escape(t('select_variant')) ?>" required>
				<option value=""></option>
				<?php foreach ($products as $product): ?>
					<?php foreach ($product['variants'] as $variant): ?>
						<option value="<?= (int) $variant['id'] ?>" data-available="<?= (int) $variant['warehouse_available'] ?>">
							<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>
						</option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
		</td>
		<td><input type="number" name="qty[]" class="form-control qty-input" min="1" value="1" required></td>
		<td class="available-qty text-muted">—</td>
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

		const select = row.querySelector('.variant-select');
		if (window.initSelect2) initSelect2(row);

		jQuery(select).on('select2:select select2:clear', function () {
			const opt = select.options[select.selectedIndex];
			row.querySelector('.available-qty').textContent = opt && opt.value ? (opt.dataset.available || '0') : '—';
		});

		row.querySelector('.remove-row').addEventListener('click', function () {
			row.remove();
		});
	}

	document.getElementById('add-row').addEventListener('click', addRow);
	addRow();
});
</script>
