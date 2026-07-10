<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h2><?= isset($variant) ? t('edit_variant') : t('create_variant') ?></h2>
			<?php if (isset($product)): ?>
				<p class="text-muted"><?= t('product') ?>: <?= h($product['name']) ?></p>
			<?php endif; ?>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('variant_label') ?></label>
					<input type="text" name="variant_label" class="form-control" value="<?= isset($variant) ? h($variant['variant_label']) : '' ?>" placeholder="e.g., 0.25 × 40mm" required>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('cost_price') ?></label>
					<input type="number" name="cost_price" class="form-control" step="0.01" value="<?= isset($variant) ? $variant['cost_price'] : 0 ?>" required>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('sell_price') ?></label>
					<input type="number" name="sell_price" class="form-control" step="0.01" value="<?= isset($variant) ? $variant['sell_price'] : 0 ?>" required>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('reorder_level') ?></label>
					<input type="number" name="reorder_level" class="form-control" value="<?= isset($variant) ? $variant['reorder_level'] : 0 ?>">
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary"><?= t('save') ?></button>
					<a href="<?= site_url('store/products') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
