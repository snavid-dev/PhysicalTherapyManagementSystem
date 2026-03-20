<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Assign only the access each role needs.') ?></p>
	</div>
	<a href="<?= base_url('roles') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3 mb-4">
				<div class="col-md-6">
					<label class="form-label"><?= t('Role Name') ?></label>
					<input type="text" name="name" class="form-control" value="<?= set_value('name', $role['name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Role Slug') ?></label>
					<input type="text" name="slug" class="form-control" value="<?= set_value('slug', $role['slug'] ?? '') ?>">
					<small class="text-danger"><?= form_error('slug') ?></small>
				</div>
			</div>

			<div class="row g-3">
				<?php foreach ($permissions as $permission) : ?>
					<div class="col-md-4">
						<div class="form-check border rounded p-3 h-100">
							<input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>" id="permission<?= $permission['id'] ?>" <?= in_array((int) $permission['id'], $selected_permissions, TRUE) ? 'checked' : '' ?>>
							<label class="form-check-label" for="permission<?= $permission['id'] ?>">
								<strong><?= html_escape(ucwords(str_replace('_', ' ', $permission['module_key']))) ?></strong><br>
								<span class="text-muted"><?= html_escape($permission['name']) ?></span>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Role') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
