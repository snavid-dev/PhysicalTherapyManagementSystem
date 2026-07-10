<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h2><?= t('set_opening_stock') ?></h2>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('location') ?></label>
					<select name="location_id" class="form-select" required>
						<option value="">— <?= t('select_location') ?> —</option>
						<?php foreach ($locations as $loc): ?>
							<option value="<?= $loc['id'] ?>"><?= h($loc['name']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('product_variant') ?></label>
					<select name="variant_id" class="form-select select2" required>
						<option value="">— <?= t('select_variant') ?> —</option>
						<?php
							$products = $this->Store_model->get_all_products();
							foreach ($products as $p):
								$variants = $this->Store_model->get_variants_by_product($p['id']);
								foreach ($variants as $v):
						?>
							<option value="<?= $v['id'] ?>"><?= h($p['name'] . ' - ' . $v['variant_label']) ?></option>
						<?php endforeach; endforeach; ?>
					</select>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('quantity') ?></label>
					<input type="number" name="qty" class="form-control" min="0" value="0" required>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary"><?= t('save') ?></button>
					<a href="<?= site_url('store/stock') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	jQuery('.select2').select2();
});
</script>
