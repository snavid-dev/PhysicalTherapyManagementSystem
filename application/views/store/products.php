<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('store_products') ?></h1>
		</div>
		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-auto">
			<a href="<?= site_url('store/categories') ?>" class="btn btn-outline-secondary"><?= t('manage_categories') ?></a>
			<a href="<?= site_url('store/create_product') ?>" class="btn btn-primary"><?= t('create_product') ?></a>
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
			<table class="table table-hover dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('Name') ?></th>
						<th><?= t('category') ?></th>
						<th><?= t('brand') ?></th>
						<th><?= t('unit') ?></th>
						<th><?= t('variants') ?></th>
						<th><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $product): ?>
						<tr>
							<td><?= html_escape($product['name']) ?></td>
							<td><?= html_escape($product['category_name']) ?></td>
							<td><?= html_escape($product['brand'] ?: '—') ?></td>
							<td><?= html_escape($product['unit']) ?></td>
							<td>
								<a href="<?= site_url('store/edit_product/' . $product['id']) ?>">
									<?= count($this->Store_model->get_variants_by_product($product['id'])) ?>
								</a>
							</td>
							<td>
								<?php if ($this->auth->has_permission('manage_store')): ?>
									<a href="<?= site_url('store/edit_product/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary"><?= t('Edit') ?></a>
									<a href="<?= site_url('store/create_variant/' . $product['id']) ?>" class="btn btn-sm btn-outline-success"><?= t('add_variant') ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
