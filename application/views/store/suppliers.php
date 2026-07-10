<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('suppliers') ?></h1>
		</div>
		<div class="col-auto">
			<a href="<?= site_url('store/create_supplier') ?>" class="btn btn-primary"><?= t('create_supplier') ?></a>
		</div>
	</div>

	<?php if ($msg = $this->session->flashdata('success')): ?>
		<div class="alert alert-success"><?= h($msg) ?></div>
	<?php endif; ?>

	<?php if (empty($suppliers)): ?>
		<div class="alert alert-info"><?= t('no_suppliers_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th><?= t('name') ?></th>
						<th><?= t('contact') ?></th>
						<th><?= t('actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($suppliers as $s): ?>
						<tr>
							<td><?= h($s['name']) ?></td>
							<td><?= h($s['contact'] ?: '—') ?></td>
							<td>
								<a href="<?= site_url('store/edit_supplier/' . $s['id']) ?>" class="btn btn-sm btn-outline-primary"><?= t('edit') ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
