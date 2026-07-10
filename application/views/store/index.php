<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<h1><?= t('store_management') ?></h1>

	<div class="row g-3">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?= t('products') ?></h5>
					<p class="card-text"><?= t('manage_products_and_variants') ?></p>
					<a href="<?= site_url('store/products') ?>" class="btn btn-primary"><?= t('go_to_products') ?></a>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?= t('stock') ?></h5>
					<p class="card-text"><?= t('view_manage_stock_levels') ?></p>
					<a href="<?= site_url('store/stock') ?>" class="btn btn-primary"><?= t('go_to_stock') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
