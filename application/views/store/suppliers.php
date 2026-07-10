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
		<div class="alert alert-success"><?= html_escape($msg) ?></div>
	<?php endif; ?>

	<?php if (empty($suppliers)): ?>
		<div class="alert alert-info"><?= t('no_suppliers_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th><?= t('Name') ?></th>
						<th><?= t('contact') ?></th>
						<th><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($suppliers as $s): ?>
						<tr>
							<td><?= html_escape($s['name']) ?></td>
							<td><?= html_escape($s['contact'] ?: '—') ?></td>
							<td>
								<a href="<?= site_url('store/edit_supplier/' . $s['id']) ?>" class="btn btn-sm btn-outline-primary"><?= t('Edit') ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
