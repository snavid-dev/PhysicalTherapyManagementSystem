<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h2><?= isset($product) ? t('edit_product') : t('create_product') ?></h2>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('category') ?></label>
					<select name="category_id" class="form-select" required>
						<option value="">— <?= t('select_category') ?> —</option>
						<?php foreach ($categories as $cat): ?>
							<option value="<?= $cat['id'] ?>" <?= isset($product) && $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
								<?= html_escape($cat['name']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('product_name') ?></label>
					<input type="text" name="product_name" class="form-control" value="<?= isset($product) ? html_escape($product['name']) : '' ?>" required>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('brand') ?></label>
					<input type="text" name="brand" class="form-control" value="<?= isset($product) ? html_escape($product['brand'] ?: '') : '' ?>">
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('unit') ?></label>
					<input type="text" name="unit" class="form-control" value="<?= isset($product) ? html_escape($product['unit']) : 'piece' ?>">
				</div>

				<?php if (!isset($product)): ?>
					<fieldset class="border p-3 mb-3">
						<legend class="float-none w-auto px-2"><?= t('first_variant') ?></legend>

						<div class="mb-3">
							<label class="form-label"><?= t('variant_label') ?></label>
							<input type="text" name="variant_label" class="form-control" placeholder="<?= html_escape(t('variant_label_placeholder')) ?>">
						</div>

						<div class="row">
							<div class="col-md-4">
								<label class="form-label"><?= t('cost_price') ?></label>
								<input type="number" name="cost_price" class="form-control" step="0.01" value="0" required>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('sell_price') ?></label>
								<input type="number" name="sell_price" class="form-control" step="0.01" value="0" required>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('reorder_level') ?></label>
								<input type="number" name="reorder_level" class="form-control" value="0">
							</div>
						</div>
					</fieldset>
				<?php endif; ?>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-check-lg" aria-hidden="true"></i> <?= t('save') ?></button>
					<a href="<?= site_url('store/products') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
