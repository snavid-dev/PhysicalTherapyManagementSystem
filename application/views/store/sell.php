<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$is_edit = !empty($is_edit);
$is_refund_edit = !empty($is_refund_edit);
$sale = $sale ?? NULL;
$items = $items ?? array();
?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= $is_refund_edit ? t('edit_refund') : ($is_edit ? t('edit_sale') : t('sell_product')) ?></h1>
		</div>
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
	<?php if ($is_refund_edit): ?>
		<div class="alert alert-info"><?= t('refund_edit_locked_notice') ?></div>
	<?php endif; ?>

	<form method="post" id="sale-form" action="<?= site_url($is_edit ? 'store/update_sale/' . $sale['id'] : 'store/sell') ?>">
		<div class="row">
			<div class="col-lg-5 order-lg-2 mb-4 mb-lg-0">
				<div class="card">
					<div class="card-header">
						<input type="search" id="product-search" class="form-control form-control-sm" placeholder="<?= html_escape(t('search_product')) ?>">
					</div>
					<div class="card-body d-grid gap-2" id="product-grid" style="max-height: 640px; overflow-y: auto;">
						<?php foreach ($products as $product): ?>
							<?php foreach ($product['variants'] as $variant): ?>
								<button type="button"
									class="btn btn-outline-primary text-start product-btn"
									data-variant-id="<?= (int) $variant['id'] ?>"
									data-search="<?= html_escape(mb_strtolower($product['name'] . ' ' . $variant['variant_label'])) ?>"
									data-name="<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>"
									data-price="<?= (float) $variant['sell_price'] ?>">
									<div class="fw-semibold"><?= html_escape($product['name']) ?></div>
									<div class="small text-muted d-flex justify-content-between">
										<span><?= html_escape($variant['variant_label']) ?></span>
										<span><?= number_format($variant['sell_price'], 2) ?> AFN</span>
									</div>
								</button>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<?php if (empty($products)): ?>
							<div class="text-muted text-center py-4"><?= t('no_products_found') ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-lg-7 order-lg-1">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0"><?= t('cart') ?></h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label"><?= t('patient') ?></label>
							<select name="patient_id" id="patient-select" class="form-select s2-select" data-placeholder="<?= html_escape(t('search_patient')) ?>" <?= $is_refund_edit ? 'disabled' : '' ?>>
								<option value=""><?= t('walk_in_customer') ?></option>
								<?php foreach ($patients as $patient): ?>
									<?php
										$last_or_father = $patient['last_name'] ?: ($patient['father_name'] ?? '');
										$patient_name = trim($patient['first_name'] . ' ' . $last_or_father);
									?>
									<option value="<?= (int) $patient['id'] ?>" <?= ($is_edit && (int) $sale['patient_id'] === (int) $patient['id']) ? 'selected' : '' ?>><?= html_escape($patient_name) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="row mb-3" id="external-customer-fields">
							<div class="col-6">
								<label class="form-label small"><?= t('customer_name') ?></label>
								<input type="text" name="customer_name" id="customer-name-input" class="form-control" value="<?= $is_edit ? html_escape((string) $sale['customer_name']) : '' ?>" <?= $is_refund_edit ? 'disabled' : '' ?>>
							</div>
							<div class="col-6">
								<label class="form-label small"><?= t('customer_phone') ?></label>
								<input type="text" name="customer_phone" class="form-control" value="<?= $is_edit ? html_escape((string) $sale['customer_phone']) : '' ?>" <?= $is_refund_edit ? 'disabled' : '' ?>>
							</div>
						</div>

						<div class="table-responsive mb-3">
							<table class="table mb-0 align-middle" id="cart-table">
								<thead>
									<tr>
										<th><?= t('product_variant') ?></th>
										<th style="width:90px"><?= t('qty') ?></th>
										<th style="width:110px"><?= t('price') ?></th>
										<th style="width:110px"><?= t('total') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="cart-items">
									<tr class="empty-row">
										<td colspan="5" class="text-center text-muted py-4"><?= t('no_items_in_cart') ?></td>
									</tr>
								</tbody>
							</table>
						</div>

						<details class="mb-3">
							<summary class="text-muted small"><?= t('add_item_manually') ?></summary>
							<div class="d-flex gap-2 mt-2">
								<select id="manual-variant-select" class="form-select s2-select" data-placeholder="<?= html_escape(t('select_variant')) ?>">
									<option value=""></option>
									<?php foreach ($products as $product): ?>
										<?php foreach ($product['variants'] as $variant): ?>
											<option value="<?= (int) $variant['id'] ?>" data-price="<?= (float) $variant['sell_price'] ?>" data-name="<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>">
												<?= html_escape($product['name'] . ' — ' . $variant['variant_label']) ?>
											</option>
										<?php endforeach; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</details>

						<div class="row mb-3">
							<div class="col-6 col-md-3">
								<label class="form-label small"><?= t('subtotal') ?></label>
								<input type="text" class="form-control" id="subtotal" readonly>
							</div>
							<div class="col-6 col-md-3">
								<label class="form-label small"><?= t('discount') ?></label>
								<input type="number" name="discount" class="form-control" step="0.01" value="<?= $is_edit ? html_escape($sale['discount']) : '0' ?>" id="discount-input">
							</div>
							<div class="col-6 col-md-3">
								<label class="form-label small"><?= t('tax') ?></label>
								<input type="number" name="tax" class="form-control" step="0.01" value="<?= $is_edit ? html_escape($sale['tax']) : '0' ?>" id="tax-input">
							</div>
							<div class="col-6 col-md-3">
								<label class="form-label small fw-semibold"><?= t('total') ?></label>
								<input type="text" class="form-control fw-bold" id="total" readonly>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label"><?= t('payment_method') ?></label>
							<select name="payment_method" id="payment-method-select" class="form-select" required <?= $is_refund_edit ? 'disabled' : '' ?>>
								<option value="cash" <?= (!$is_edit || $sale['payment_method'] === 'cash') ? 'selected' : '' ?>><?= t('cash') ?></option>
								<option value="wallet" id="pm-option-wallet" <?= ($is_edit && $sale['payment_method'] === 'wallet') ? 'selected' : '' ?>><?= t('wallet') ?></option>
								<option value="debt" <?= ($is_edit && $sale['payment_method'] === 'debt') ? 'selected' : '' ?>><?= t('debt') ?></option>
								<?php if ($is_refund_edit && in_array($sale['payment_method'], array('card', 'prepayment'), TRUE)): ?>
									<option value="<?= html_escape($sale['payment_method']) ?>" selected><?= t($sale['payment_method']) ?></option>
								<?php endif; ?>
							</select>
							<div class="form-text" id="wallet-requires-patient-hint" style="display:none;"><?= t('patient_required_for_wallet') ?></div>
						</div>

						<button type="submit" class="btn btn-primary btn-lg w-100 btn-icon" id="complete-sale-btn" disabled><i class="bi bi-cart" aria-hidden="true"></i> <?= $is_edit ? t('save') : t('complete_sale') ?></button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const cartItems = document.getElementById('cart-items');
	const completeBtn = document.getElementById('complete-sale-btn');
	const emptyRowHtml = '<tr class="empty-row"><td colspan="5" class="text-center text-muted py-4"><?= t("no_items_in_cart") ?></td></tr>';

	const patientSelect = document.getElementById('patient-select');
	const externalFields = document.getElementById('external-customer-fields');
	const customerNameInput = document.getElementById('customer-name-input');
	const paymentMethodSelect = document.getElementById('payment-method-select');
	const walletOption = document.getElementById('pm-option-wallet');
	const walletHint = document.getElementById('wallet-requires-patient-hint');

	function syncPatientDependentFields() {
		const hasPatient = !!patientSelect.value;

		externalFields.style.display = hasPatient ? 'none' : '';
		customerNameInput.required = !hasPatient;
		if (hasPatient) customerNameInput.value = '';

		walletOption.disabled = !hasPatient;
		walletHint.style.display = hasPatient ? 'none' : '';
		if (!hasPatient && paymentMethodSelect.value === 'wallet') {
			paymentMethodSelect.value = 'cash';
		}
	}

	syncPatientDependentFields();
	patientSelect.addEventListener('change', syncPatientDependentFields);
	if (window.jQuery) {
		jQuery(patientSelect).on('select2:select select2:clear', syncPatientDependentFields);
	}

	function calculateTotals() {
		let subtotal = 0;
		cartItems.querySelectorAll('.item-row').forEach(function (row) {
			const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
			const price = parseFloat(row.querySelector('.price-input').value) || 0;
			const line = qty * price;
			row.querySelector('.line-total').textContent = line.toFixed(2);
			subtotal += line;
		});

		document.getElementById('subtotal').value = subtotal.toFixed(2);
		const discount = parseFloat(document.getElementById('discount-input').value) || 0;
		const tax = parseFloat(document.getElementById('tax-input').value) || 0;
		document.getElementById('total').value = (subtotal - discount + tax).toFixed(2);

		completeBtn.disabled = cartItems.querySelectorAll('.item-row').length === 0;
	}

	function addToCart(variantId, name, price, qty) {
		qty = qty || 1;

		const existing = cartItems.querySelector('.item-row[data-variant-id="' + variantId + '"]');
		if (existing) {
			const qtyInput = existing.querySelector('.qty-input');
			qtyInput.value = parseInt(qtyInput.value, 10) + qty;
			calculateTotals();
			return;
		}

		if (cartItems.querySelector('.empty-row')) {
			cartItems.querySelector('.empty-row').remove();
		}

		const row = document.createElement('tr');
		row.className = 'item-row';
		row.dataset.variantId = variantId;
		row.innerHTML =
			'<td>' +
				'<input type="hidden" name="variant_id[]" value="' + variantId + '">' +
				name +
			'</td>' +
			'<td><input type="number" name="qty[]" class="form-control form-control-sm qty-input" min="1" value="' + qty + '" required></td>' +
			'<td><input type="number" name="price[]" class="form-control form-control-sm price-input" step="0.01" value="' + price.toFixed(2) + '" required></td>' +
			'<td class="line-total">0.00</td>' +
			'<td><button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" aria-label="<?= html_escape(t('remove')) ?>"><i class="bi bi-trash" aria-hidden="true"></i></button></td>';

		cartItems.appendChild(row);
		row.querySelector('.qty-input').addEventListener('change', calculateTotals);
		row.querySelector('.price-input').addEventListener('change', calculateTotals);
		row.querySelector('.remove-item-btn').addEventListener('click', function () {
			row.remove();
			if (!cartItems.querySelector('.item-row')) {
				cartItems.innerHTML = emptyRowHtml;
			}
			calculateTotals();
		});

		calculateTotals();
	}

	document.querySelectorAll('.product-btn').forEach(function (btn) {
		btn.addEventListener('click', function () {
			addToCart(btn.dataset.variantId, btn.dataset.name, parseFloat(btn.dataset.price));
		});
	});

	<?php if ($is_edit): ?>
	const initialCart = <?= json_encode(array_map(function ($item) {
		return array(
			'variant_id' => (int) $item['variant_id'],
			'name' => $item['product_name'] . ' — ' . $item['variant_label'],
			'price' => (float) $item['unit_price'],
			'qty' => (int) $item['qty'],
		);
	}, $items)) ?>;
	initialCart.forEach(function (item) {
		addToCart(item.variant_id, item.name, item.price, item.qty);
	});
	<?php endif; ?>

	const manualSelect = document.getElementById('manual-variant-select');
	if (window.jQuery) {
		jQuery(manualSelect).on('select2:select', function () {
			const opt = manualSelect.options[manualSelect.selectedIndex];
			if (opt.value) {
				addToCart(opt.value, opt.dataset.name, parseFloat(opt.dataset.price));
				jQuery(manualSelect).val('').trigger('change');
			}
		});
	}

	document.getElementById('product-search').addEventListener('input', function () {
		const term = this.value.trim().toLowerCase();
		document.querySelectorAll('.product-btn').forEach(function (btn) {
			btn.style.display = btn.dataset.search.includes(term) ? '' : 'none';
		});
	});

	document.getElementById('discount-input').addEventListener('change', calculateTotals);
	document.getElementById('tax-input').addEventListener('change', calculateTotals);
});
</script>
