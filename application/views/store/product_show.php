<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
		<div>
			<h1 class="h3 mb-1"><?= html_escape($product['name']) ?></h1>
			<p class="text-muted mb-0"><?= html_escape(implode(' · ', array_filter(array(
				$product['category_name'],
				$product['brand'],
				$product['unit'],
			)))) ?></p>
		</div>
		<div class="d-flex gap-2">
			<?php if ($this->auth->has_permission('manage_store')): ?>
				<a href="<?= site_url('store/edit_product/' . $product['id']) ?>" class="btn btn-outline-primary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
				<a href="<?= site_url('store/create_variant/' . $product['id']) ?>" class="btn btn-outline-success btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_variant') ?></a>
			<?php endif; ?>
			<a href="<?= site_url('store/products') ?>" class="btn btn-outline-dark btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
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

	<div class="card">
		<div class="card-body">
			<h2 class="h5 mb-3"><?= t('variants') ?></h2>

			<?php if (empty($variants)): ?>
				<p class="text-muted mb-0"><?= t('no_variants_found') ?></p>
			<?php else: ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th><?= t('variant_label') ?></th>
								<th><?= t('cost_price') ?></th>
								<th><?= t('sell_price') ?></th>
								<th><?= t('reorder_level') ?></th>
								<?php if ($this->auth->has_permission('manage_store')): ?>
									<th><?= t('Actions') ?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($variants as $variant): ?>
								<tr>
									<td><?= html_escape($variant['variant_label']) ?></td>
									<td><?= number_format($variant['cost_price'], 2) ?></td>
									<td><?= number_format($variant['sell_price'], 2) ?></td>
									<td><?= (int) $variant['reorder_level'] ?></td>
									<?php if ($this->auth->has_permission('manage_store')): ?>
										<td>
											<a href="<?= site_url('store/edit_variant/' . $variant['id']) ?>" class="btn btn-sm btn-outline-primary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
										</td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
