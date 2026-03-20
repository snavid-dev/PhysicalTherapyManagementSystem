<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('manage_sections') ?></p>
	</div>
	<a href="<?= base_url('sections') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('Name') ?></label>
					<input type="text" name="name" class="form-control" value="<?= set_value('name', $section['name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Default Fee') ?></label>
					<input type="number" name="default_fee" class="form-control" min="0" step="0.01" value="<?= set_value('default_fee', $section['default_fee'] ?? '0.00') ?>">
					<small class="text-danger"><?= form_error('default_fee') ?></small>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Section') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
