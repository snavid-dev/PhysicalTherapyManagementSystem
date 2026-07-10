<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('product_categories') ?></h1>
		</div>
		<div class="col-auto">
			<a href="<?= site_url('store/create_category') ?>" class="btn btn-primary"><?= t('create_category') ?></a>
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

	<?php if (empty($categories)): ?>
		<div class="alert alert-info"><?= t('no_categories_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th><?= t('Name') ?></th>
						<th><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($categories as $cat): ?>
						<tr>
							<td><?= html_escape($cat['name']) ?></td>
							<td>
								<a href="<?= site_url('store/edit_category/' . $cat['id']) ?>" class="btn btn-sm btn-outline-primary"><?= t('Edit') ?></a>
								<a href="<?= site_url('store/delete_category/' . $cat['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('confirm_delete') ?>');"><?= t('Delete') ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
