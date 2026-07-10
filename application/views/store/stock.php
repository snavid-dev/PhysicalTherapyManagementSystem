<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('stock_view') ?> — <?= html_escape($location['name']) ?></h1>
		</div>
		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-auto">
			<a href="<?= site_url('store/set_opening_stock') ?>" class="btn btn-primary"><?= t('set_opening_stock') ?></a>
		</div>
		<?php endif; ?>
	</div>

	<?php if (count($locations) > 1): ?>
		<div class="mb-3">
			<div class="btn-group" role="group">
				<?php foreach ($locations as $loc): ?>
					<a href="<?= site_url('store/stock/' . $loc['id']) ?>" class="btn btn-outline-secondary <?= $loc['id'] == $location['id'] ? 'active' : '' ?>">
						<?= html_escape($loc['name']) ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

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

	<?php
		$location_stock = array_filter($stock_levels, function($s) use ($location) {
			return $s['location_id'] == $location['id'];
		});
	?>

	<?php if (empty($location_stock)): ?>
		<div class="alert alert-info"><?= t('no_stock_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('product') ?></th>
						<th><?= t('variant') ?></th>
						<th><?= t('qty_on_hand') ?></th>
						<?php if ($location['type'] === 'front_desk'): ?>
							<th><?= t('warehouse_available') ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($location_stock as $stock): ?>
						<tr>
							<td><?= html_escape($stock['product_name'] ?? '—') ?></td>
							<td><?= html_escape($stock['variant_label']) ?></td>
							<td><?= (int) $stock['qty_on_hand'] ?></td>
							<?php if ($location['type'] === 'front_desk'): ?>
								<td>
									<?php
										$warehouse_stock = array_values(array_filter($stock_levels, function($s) use ($stock) {
											return $s['location_id'] == 2 && $s['variant_id'] == $stock['variant_id'];
										}));
										echo isset($warehouse_stock[0]) ? (int) $warehouse_stock[0]['qty_on_hand'] : 0;
									?>
								</td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
