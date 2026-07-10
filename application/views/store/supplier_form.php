<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h2><?= isset($supplier) ? t('edit_supplier') : t('create_supplier') ?></h2>

			<form method="post">
				<div class="mb-3">
					<label class="form-label"><?= t('Name') ?></label>
					<input type="text" name="name" class="form-control" value="<?= isset($supplier) ? html_escape($supplier['name']) : '' ?>" required>
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('contact') ?></label>
					<input type="text" name="contact" class="form-control" value="<?= isset($supplier) ? html_escape($supplier['contact'] ?: '') : '' ?>">
				</div>

				<div class="mb-3">
					<label class="form-label"><?= t('note') ?></label>
					<textarea name="note" class="form-control" rows="3"><?= isset($supplier) ? html_escape($supplier['note'] ?: '') : '' ?></textarea>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary"><?= t('save') ?></button>
					<a href="<?= site_url('store/suppliers') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
