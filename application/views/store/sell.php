<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('sell_product') ?></h1>
		</div>
	</div>

	<?php if ($msg = $this->session->flashdata('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= h($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>
	<?php if ($msg = $this->session->flashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= h($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0"><?= t('cart') ?></h5>
				</div>
				<div class="card-body">
					<form method="post" id="sale-form">
						<div class="mb-3">
							<label class="form-label"><?= t('patient') ?></label>
							<select name="patient_id" class="form-select select2">
								<option value="">— <?= t('walk_in_customer') ?> —</option>
							</select>
						</div>

						<div class="table-responsive mb-3">
							<table class="table mb-0" id="cart-table">
								<thead>
									<tr>
										<th><?= t('product_variant') ?></th>
										<th><?= t('qty') ?></th>
										<th><?= t('price') ?></th>
										<th><?= t('total') ?></th>
										<th><?= t('action') ?></th>
									</tr>
								</thead>
								<tbody id="cart-items">
									<tr class="empty-row">
										<td colspan="5" class="text-center text-muted"><?= t('no_items_in_cart') ?></td>
									</tr>
								</tbody>
							</table>
						</div>

						<button type="button" class="btn btn-sm btn-outline-success mb-3" id="add-item-btn"><?= t('add_item') ?></button>

						<div class="row mb-3">
							<div class="col-md-6">
								<label><?= t('subtotal') ?></label>
								<div class="input-group">
									<input type="text" class="form-control" id="subtotal" readonly>
									<span class="input-group-text">AFN</span>
								</div>
							</div>
							<div class="col-md-6">
								<label><?= t('discount') ?></label>
								<div class="input-group">
									<input type="number" name="discount" class="form-control" step="0.01" value="0" id="discount-input">
									<span class="input-group-text">AFN</span>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label><?= t('tax') ?></label>
								<div class="input-group">
									<input type="number" name="tax" class="form-control" step="0.01" value="0" id="tax-input">
									<span class="input-group-text">AFN</span>
								</div>
							</div>
							<div class="col-md-6">
								<label><?= t('total') ?></label>
								<div class="input-group">
									<input type="text" class="form-control" id="total" readonly style="font-weight: bold;">
									<span class="input-group-text">AFN</span>
								</div>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label"><?= t('payment_method') ?></label>
							<select name="payment_method" class="form-select" required>
								<option value="cash"><?= t('cash') ?></option>
								<option value="card"><?= t('card') ?></option>
								<option value="wallet"><?= t('wallet') ?></option>
								<option value="prepayment"><?= t('prepayment') ?></option>
							</select>
						</div>

						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary"><?= t('complete_sale') ?></button>
							<button type="reset" class="btn btn-secondary"><?= t('clear_cart') ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0"><?= t('quick_products') ?></h5>
				</div>
				<div class="card-body" style="max-height: 600px; overflow-y: auto;">
					<?php
						$products = $this->Store_model->get_all_products();
						foreach ($products as $product):
							$variants = $this->Store_model->get_variants_by_product($product['id']);
							foreach ($variants as $variant):
					?>
						<button type="button" class="btn btn-sm btn-outline-primary w-100 mb-2 add-variant-btn" data-variant-id="<?= $variant['id'] ?>" data-name="<?= h($product['name'] . ' - ' . $variant['variant_label']) ?>" data-price="<?= (float) $variant['sell_price'] ?>">
							<?= h($product['name']) ?><br><small><?= h($variant['variant_label']) ?> - <?= (float) $variant['sell_price'] ?> AFN</small>
						</button>
					<?php endforeach; endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	let itemCounter = 0;

	function calculateTotals() {
		let subtotal = 0;
		document.querySelectorAll('.item-row').forEach(row => {
			const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
			const price = parseFloat(row.querySelector('.price-input').value) || 0;
			const line = qty * price;
			row.querySelector('.line-total').textContent = line.toFixed(2);
			subtotal += line;
		});

		document.getElementById('subtotal').value = subtotal.toFixed(2);

		const discount = parseFloat(document.getElementById('discount-input').value) || 0;
		const tax = parseFloat(document.getElementById('tax-input').value) || 0;
		const total = subtotal - discount + tax;

		document.getElementById('total').value = total.toFixed(2);
	}

	document.getElementById('add-item-btn').addEventListener('click', function() {
		const tbody = document.getElementById('cart-items');
		if (document.querySelector('.empty-row')) {
			document.querySelector('.empty-row').remove();
		}

		const newRow = document.createElement('tr');
		newRow.className = 'item-row';
		newRow.innerHTML = `
			<td>
				<select name="variant_id[]" class="form-select form-select-sm select2" required>
					<option value="">— <?= t("select_variant") ?> —</option>
				</select>
			</td>
			<td><input type="number" name="qty[]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>
			<td><input type="number" name="price[]" class="form-control form-control-sm price-input" step="0.01" value="0" required></td>
			<td><span class="line-total">0.00</span></td>
			<td><button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"><?= t("remove") ?></button></td>
		`;

		tbody.appendChild(newRow);
		jQuery(newRow.querySelector('.select2')).select2();

		newRow.querySelector('.qty-input').addEventListener('change', calculateTotals);
		newRow.querySelector('.price-input').addEventListener('change', calculateTotals);
		newRow.querySelector('.remove-item-btn').addEventListener('click', function() {
			newRow.remove();
			if (document.querySelectorAll('.item-row').length === 0) {
				const emptyRow = document.createElement('tr');
				emptyRow.className = 'empty-row';
				emptyRow.innerHTML = `<td colspan="5" class="text-center text-muted"><?= t("no_items_in_cart") ?></td>`;
				tbody.appendChild(emptyRow);
			}
			calculateTotals();
		});
	});

	document.querySelectorAll('.add-variant-btn').forEach(btn => {
		btn.addEventListener('click', function() {
			const variantId = this.dataset.variantId;
			const name = this.dataset.name;
			const price = parseFloat(this.dataset.price);

			const tbody = document.getElementById('cart-items');
			if (document.querySelector('.empty-row')) {
				document.querySelector('.empty-row').remove();
			}

			let existingRow = null;
			document.querySelectorAll('.item-row').forEach(row => {
				const select = row.querySelector('select[name="variant_id[]"]');
				if (select.value == variantId) {
					existingRow = row;
				}
			});

			if (existingRow) {
				const qtyInput = existingRow.querySelector('.qty-input');
				qtyInput.value = parseInt(qtyInput.value) + 1;
				qtyInput.dispatchEvent(new Event('change'));
			} else {
				document.getElementById('add-item-btn').click();
				const lastRow = document.querySelectorAll('.item-row')[document.querySelectorAll('.item-row').length - 1];
				lastRow.querySelector('select').value = variantId;
				lastRow.querySelector('.price-input').value = price.toFixed(2);
				jQuery(lastRow.querySelector('select')).change();
			}

			calculateTotals();
		});
	});

	document.getElementById('discount-input').addEventListener('change', calculateTotals);
	document.getElementById('tax-input').addEventListener('change', calculateTotals);

	jQuery('.select2').select2();
});
</script>
