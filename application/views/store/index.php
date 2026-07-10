<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<h1 class="mb-4"><?= t('store_management') ?></h1>

	<?php if ($this->auth->has_permission('manage_store')): ?>
	<div class="row mb-4">
		<div class="col-md-6 mx-auto">
			<a href="<?= site_url('store/sell') ?>" class="btn btn-primary btn-lg w-100 py-3">
				<?= t('sell_product') ?>
			</a>
		</div>
	</div>
	<?php endif; ?>

	<div class="row g-3">
		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="card-title"><?= t('stock') ?></h5>
					<p class="card-text text-muted small"><?= t('view_manage_stock_levels') ?></p>
					<a href="<?= site_url('store/stock') ?>" class="btn btn-outline-primary"><?= t('go_to_stock') ?></a>
				</div>
			</div>
		</div>

		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="card-title"><?= t('store_products') ?></h5>
					<p class="card-text text-muted small"><?= t('manage_products_and_variants') ?></p>
					<a href="<?= site_url('store/products') ?>" class="btn btn-outline-primary"><?= t('go_to_products') ?></a>
					<a href="<?= site_url('store/categories') ?>" class="btn btn-outline-secondary btn-sm ms-1"><?= t('manage_categories') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="card-title">
						<?= t('requisitions') ?>
						<?php if (!empty($pending_requisitions_count)): ?>
							<span class="badge bg-warning text-dark"><?= (int) $pending_requisitions_count ?></span>
						<?php endif; ?>
					</h5>
					<p class="card-text text-muted small"><?= t('request_to_location') ?></p>
					<a href="<?= site_url('store/requisitions') ?>" class="btn btn-outline-primary"><?= t('requisitions') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="card-title"><?= t('stock_receipts') ?></h5>
					<p class="card-text text-muted small"><?= t('receive_stock') ?></p>
					<a href="<?= site_url('store/stock_receipts') ?>" class="btn btn-outline-primary"><?= t('stock_receipts') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="card-title"><?= t('suppliers') ?></h5>
					<p class="card-text text-muted small"><?= t('suppliers') ?></p>
					<a href="<?= site_url('store/suppliers') ?>" class="btn btn-outline-primary"><?= t('suppliers') ?></a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
