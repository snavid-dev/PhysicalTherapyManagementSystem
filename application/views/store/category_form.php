<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h2><?= isset($category) ? t('edit_category') : t('create_category') ?></h2>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('Name') ?></label>
					<input type="text" name="name" class="form-control" value="<?= isset($category) ? html_escape($category['name']) : '' ?>" required>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary"><?= t('save') ?></button>
					<a href="<?= site_url('store/categories') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
