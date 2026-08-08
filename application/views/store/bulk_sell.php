<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('bulk_sell') ?></h1>
			<p class="text-muted small"><?= t('bulk_sell_hint') ?></p>
		</div>
	</div>

	<?php if ($msg = $this->session->flashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= html_escape($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>

	<form method="post" id="bulk-sell-form">
		<div id="bulk-customers"></div>

		<div class="d-flex justify-content-between align-items-center my-4">
			<button type="button" class="btn btn-outline-primary btn-icon" id="add-customer-btn"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_customer') ?></button>
			<div class="text-end">
				<div class="text-muted small"><?= t('total_amount') ?></div>
				<div class="fs-4 fw-bold" id="grand-total">0.00 AFN</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary btn-lg w-100 btn-icon" id="submit-batch-btn" disabled><i class="bi bi-check-lg" aria-hidden="true"></i> <?= t('submit_for_approval') ?></button>
	</form>
</div>

<template id="customerRowTemplate">
	<div class="card mb-3 customer-row" data-index="__INDEX__">
		<div class="card-header d-flex justify-content-between align-items-center">
			<span><?= t('customer') ?> #<span class="row-number">__ROW_NUMBER__</span></span>
			<button type="button" class="btn btn-sm btn-outline-danger remove-customer-btn btn-icon"><i class="bi bi-trash" aria-hidden="true"></i> <?= t('remove') ?></button>
		</div>
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-md-4">
					<label class="form-label small"><?= t('patient') ?></label>
					<select name="customers[__INDEX__][patient_id]" class="form-select s2-select customer-patient-select" data-placeholder="<?= html_escape(t('search_patient')) ?>">
						<option value=""><?= t('walk_in_customer') ?></option>
						<?php foreach ($patients as $patient): ?>
							<?php
								$last_or_father = $patient['last_name'] ?: ($patient['father_name'] ?? '');
								$patient_name = trim($patient['first_name'] . ' ' . $last_or_father);
							?>
							<option value="<?= (int) $patient['id'] ?>"><?= html_escape($patient_name) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3 customer-external-field">
					<label class="form-label small"><?= t('customer_name') ?></label>
					<input type="text" name="customers[__INDEX__][customer_name]" class="form-control customer-name-input">
				</div>
				<div class="col-md-3 customer-external-field">
					<label class="form-label small"><?= t('customer_phone') ?></label>
					<input type="text" name="customers[__INDEX__][customer_phone]" class="form-control">
				</div>
				<div class="col-md-2">
					<label class="form-label small"><?= t('payment_method') ?></label>
					<select name="customers[__INDEX__][payment_method]" class="form-select customer-payment-select">
						<option value="cash"><?= t('cash') ?></option>
						<option value="wallet" class="customer-wallet-option"><?= t('wallet') ?></option>
						<option value="debt"><?= t('debt') ?></option>
					</select>
				</div>
			</div>

			<select class="form-select s2-select customer-variant-select mb-2" data-placeholder="<?= html_escape(t('select_variant')) ?>">
				<option value=""></option>
				<?php foreach ($products as $product): ?>
					<?php foreach ($product['variants'] as $variant): ?>
						<option value="<?= (int) $variant['id'] ?>" data-price="<?= (float) $variant['sell_price'] ?>" data-name="<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>">
							<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>
						</option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>

			<div class="table-responsive">
				<table class="table table-sm mb-2">
					<thead>
						<tr>
							<th><?= t('product_variant') ?></th>
							<th style="width:90px"><?= t('qty') ?></th>
							<th style="width:110px"><?= t('price') ?></th>
							<th style="width:110px"><?= t('total') ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody class="customer-items">
						<tr class="empty-row"><td colspan="5" class="text-center text-muted py-2"><?= t('no_items_in_cart') ?></td></tr>
					</tbody>
				</table>
			</div>

			<div class="text-end small text-muted"><?= t('subtotal') ?>: <span class="customer-subtotal">0.00</span> AFN</div>
		</div>
	</div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const template = document.getElementById('customerRowTemplate');
	const container = document.getElementById('bulk-customers');
	const addBtn = document.getElementById('add-customer-btn');
	const submitBtn = document.getElementById('submit-batch-btn');
	const grandTotalEl = document.getElementById('grand-total');
	const emptyRowHtml = '<tr class="empty-row"><td colspan="5" class="text-center text-muted py-2"><?= t("no_items_in_cart") ?></td></tr>';
	let rowCount = 0;

	function recalcRow(row) {
		let subtotal = 0;
		row.querySelectorAll('.item-row').forEach(function (itemRow) {
			const qty = parseFloat(itemRow.querySelector('.qty-input').value) || 0;
			const price = parseFloat(itemRow.querySelector('.price-input').value) || 0;
			const line = qty * price;
			itemRow.querySelector('.line-total').textContent = line.toFixed(2);
			subtotal += line;
		});
		row.querySelector('.customer-subtotal').textContent = subtotal.toFixed(2);
		recalcGrandTotal();
	}

	function recalcGrandTotal() {
		let grand = 0;
		container.querySelectorAll('.customer-row').forEach(function (row) {
			grand += parseFloat(row.querySelector('.customer-subtotal').textContent) || 0;
		});
		grandTotalEl.textContent = grand.toFixed(2) + ' AFN';
		submitBtn.disabled = grand <= 0 || container.querySelectorAll('.customer-row').length === 0;
	}

	function addItemToRow(row, index, variantId, name, price) {
		const itemsBody = row.querySelector('.customer-items');
		const existing = itemsBody.querySelector('.item-row[data-variant-id="' + variantId + '"]');
		if (existing) {
			const qtyInput = existing.querySelector('.qty-input');
			qtyInput.value = parseInt(qtyInput.value, 10) + 1;
			recalcRow(row);
			return;
		}

		if (itemsBody.querySelector('.empty-row')) itemsBody.querySelector('.empty-row').remove();

		const tr = document.createElement('tr');
		tr.className = 'item-row';
		tr.dataset.variantId = variantId;
		tr.innerHTML =
			'<td><input type="hidden" name="customers[' + index + '][variant_id][]" value="' + variantId + '">' + name + '</td>' +
			'<td><input type="number" name="customers[' + index + '][qty][]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>' +
			'<td><input type="number" name="customers[' + index + '][price][]" class="form-control form-control-sm price-input" step="0.01" value="' + price.toFixed(2) + '" required></td>' +
			'<td class="line-total">0.00</td>' +
			'<td><button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" aria-label="<?= html_escape(t('remove')) ?>"><i class="bi bi-trash" aria-hidden="true"></i></button></td>';

		itemsBody.appendChild(tr);
		tr.querySelector('.qty-input').addEventListener('change', function () { recalcRow(row); });
		tr.querySelector('.price-input').addEventListener('change', function () { recalcRow(row); });
		tr.querySelector('.remove-item-btn').addEventListener('click', function () {
			tr.remove();
			if (!itemsBody.querySelector('.item-row')) itemsBody.innerHTML = emptyRowHtml;
			recalcRow(row);
		});

		recalcRow(row);
	}

	function syncCustomerType(row) {
		const hasPatient = !!row.querySelector('.customer-patient-select').value;
		row.querySelectorAll('.customer-external-field').forEach(function (el) { el.style.display = hasPatient ? 'none' : ''; });
		row.querySelector('.customer-name-input').required = !hasPatient;
		const walletOption = row.querySelector('.customer-wallet-option');
		walletOption.disabled = !hasPatient;
		const paymentSelect = row.querySelector('.customer-payment-select');
		if (!hasPatient && paymentSelect.value === 'wallet') paymentSelect.value = 'cash';
	}

	function addCustomerRow() {
		const index = rowCount++;
		const html = template.innerHTML.replace(/__INDEX__/g, String(index)).replace(/__ROW_NUMBER__/g, String(index + 1));
		const wrapper = document.createElement('div');
		wrapper.innerHTML = html.trim();
		const row = wrapper.firstElementChild;
		container.appendChild(row);

		const patientSelect = row.querySelector('.customer-patient-select');
		const variantSelect = row.querySelector('.customer-variant-select');

		patientSelect.addEventListener('change', function () { syncCustomerType(row); });
		row.querySelector('.remove-customer-btn').addEventListener('click', function () {
			if (window.jQuery) row.querySelectorAll('.s2-select').forEach(function (el) { jQuery(el).select2('destroy'); });
			row.remove();
			recalcGrandTotal();
		});

		if (window.initSelect2) window.initSelect2(row);
		if (window.jQuery) {
			jQuery(patientSelect).on('select2:select select2:clear', function () { syncCustomerType(row); });
			jQuery(variantSelect).on('select2:select', function () {
				const opt = variantSelect.options[variantSelect.selectedIndex];
				if (opt.value) {
					addItemToRow(row, index, opt.value, opt.dataset.name, parseFloat(opt.dataset.price));
					jQuery(variantSelect).val('').trigger('change');
				}
			});
		}

		syncCustomerType(row);
		recalcGrandTotal();
	}

	addBtn.addEventListener('click', addCustomerRow);
	addCustomerRow();
});
</script>
