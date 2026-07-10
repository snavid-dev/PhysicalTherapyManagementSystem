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
								<option value="<?= $loc['id'] ?>"><?= h($loc['name']) ?></option>
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
										<th><?= t('quantity') ?></th>
										<th><?= t('warehouse_available') ?></th>
										<th><?= t('action') ?></th>
									</tr>
								</thead>
								<tbody id="items-tbody">
									<tr class="item-row" data-row="0">
										<td>
											<select name="variant_id[]" class="form-select select2 variant-select" required>
												<option value="">— <?= t('select_variant') ?> —</option>
											</select>
										</td>
										<td>
											<input type="number" name="qty[]" class="form-control qty-input" min="1" value="1" required>
										</td>
										<td class="available-qty">0</td>
										<td>
											<button type="button" class="btn btn-sm btn-outline-danger remove-row" style="display: none;"><?= t('remove') ?></button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<button type="button" class="btn btn-sm btn-outline-success mt-2" id="add-row"><?= t('add_line') ?></button>
					</div>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary"><?= t('submit_requisition') ?></button>
					<a href="<?= site_url('store/requisitions') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const products = <?= json_encode($this->Store_model->get_all_products()) ?>;

	function buildVariantOptions() {
		let options = '<option value="">— <?= t("select_variant") ?> —</option>';
		products.forEach(p => {
			const variants = <?= json_encode($this->Store_model->get_variants_by_product(0)) ?>;
			// Need to fetch variants dynamically - simplified approach
		});
		return options;
	}

	jQuery('.select2').select2();

	document.getElementById('add-row').addEventListener('click', function() {
		const tbody = document.getElementById('items-tbody');
		const newRow = tbody.rows[0].cloneNode(true);
		const rowNum = tbody.rows.length;
		newRow.dataset.row = rowNum;
		newRow.querySelector('.remove-row').style.display = 'inline-block';
		newRow.querySelector('select').value = '';
		newRow.querySelector('input').value = '1';
		tbody.appendChild(newRow);
		jQuery(newRow.querySelector('.select2')).select2();
	});

	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('remove-row')) {
			e.target.closest('tr').remove();
		}
	});
});
</script>
