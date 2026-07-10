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
						<option value="<?= $s['id'] ?>"><?= h($s['name']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="table-responsive mb-3">
				<table class="table" id="receipt-items">
					<thead>
						<tr>
							<th><?= t('variant') ?></th>
							<th><?= t('qty') ?></th>
							<th><?= t('unit_cost') ?></th>
							<th><?= t('action') ?></th>
						</tr>
					</thead>
					<tbody>
						<tr class="item-row">
							<td><select name="variant_id[]" class="form-select select2" required></select></td>
							<td><input type="number" name="qty[]" class="form-control" min="1" value="1" required></td>
							<td><input type="number" name="unit_cost[]" class="form-control" step="0.01" value="0" required></td>
							<td><button type="button" class="btn btn-sm btn-outline-danger remove-row" style="display:none;"><?= t('remove') ?></button></td>
						</tr>
					</tbody>
				</table>
			</div>

			<button type="button" class="btn btn-sm btn-outline-success mb-3" id="add-row"><?= t('add_line') ?></button>

			<div class="mb-3">
				<label class="form-label"><?= t('note') ?></label>
				<textarea name="note" class="form-control" rows="2"></textarea>
			</div>

			<div class="d-flex gap-2">
				<button type="submit" class="btn btn-primary"><?= t('receive_stock') ?></button>
				<a href="<?= site_url('store/stock_receipts') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
			</div>
		</div>
	</form>
</div>

<script>
document.getElementById('add-row').addEventListener('click', function() {
	const tbody = document.querySelector('#receipt-items tbody');
	const newRow = tbody.rows[0].cloneNode(true);
	newRow.querySelector('select').value = '';
	newRow.querySelector('input[type="number"]:nth-of-type(1)').value = '1';
	newRow.querySelector('input[type="number"]:nth-of-type(2)').value = '0';
	newRow.querySelector('.remove-row').style.display = 'inline-block';
	tbody.appendChild(newRow);
	jQuery(newRow.querySelector('.select2')).select2();
});

document.addEventListener('click', function(e) {
	if (e.target.classList.contains('remove-row')) {
		e.target.closest('tr').remove();
	}
});

jQuery('.select2').select2();
</script>
