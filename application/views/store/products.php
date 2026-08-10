<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('store_products') ?></h1>
		</div>
		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-auto">
			<a href="<?= site_url('store/categories') ?>" class="btn btn-outline-secondary btn-icon"><i class="bi bi-tags" aria-hidden="true"></i> <?= t('manage_categories') ?></a>
			<a href="<?= site_url('store/create_product') ?>" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('create_product') ?></a>
		</div>
		<?php endif; ?>
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

	<?php if (empty($products)): ?>
		<div class="alert alert-info"><?= t('no_products_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover table-sm dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('Name') ?></th>
						<th><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $product): ?>
						<tr>
							<td>
								<a href="<?= site_url('store/view_product/' . $product['id']) ?>" class="text-decoration-none"><?= html_escape($product['name']) ?></a>
								<div class="small text-muted">
									<?= html_escape(implode(' · ', array_filter(array(
										$product['category_name'],
										$product['brand'],
										$product['unit'],
									)))) ?>
								</div>
							</td>
							<td>
								<?php if ($this->auth->has_permission('manage_store')): ?>
									<a href="<?= site_url('store/edit_product/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
									<a href="<?= site_url('store/create_variant/' . $product['id']) ?>" class="btn btn-sm btn-outline-success btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_variant') ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
