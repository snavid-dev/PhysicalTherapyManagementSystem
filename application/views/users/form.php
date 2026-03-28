<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Manage login access and privileges.') ?></p>
	</div>
	<a href="<?= base_url('users') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('First Name') ?></label>
					<input type="text" name="first_name" class="form-control" value="<?= set_value('first_name', $user['first_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('first_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Last Name') ?></label>
					<input type="text" name="last_name" class="form-control" value="<?= set_value('last_name', $user['last_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('last_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Username') ?></label>
					<input type="text" name="username" class="form-control" value="<?= set_value('username', $user['username'] ?? '') ?>">
					<small class="text-danger"><?= form_error('username') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Password') ?> <?= isset($user) && $user ? '<span class="text-muted">(' . t('leave empty to keep current') . ')</span>' : '' ?></label>
					<input type="password" name="password" class="form-control">
					<small class="text-danger"><?= form_error('password') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Email') ?></label>
					<input type="email" name="email" class="form-control" value="<?= set_value('email', $user['email'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Phone') ?></label>
					<input type="text" name="phone" class="form-control" value="<?= set_value('phone', $user['phone'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Role') ?></label>
					<select name="role_id" class="form-select s2-select">
						<option value=""><?= t('Select') ?></option>
						<?php $selected_role = (int) set_value('role_id', $user['role_id'] ?? 0); ?>
						<?php foreach ($roles as $role) : ?>
							<option value="<?= $role['id'] ?>" <?= $selected_role === (int) $role['id'] ? 'selected' : '' ?>><?= html_escape($role['name']) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('role_id') ?></small>
				</div>
				<div class="col-md-6 d-flex align-items-end">
					<div class="form-check">
						<?php $is_active = (int) set_value('is_active', $user['is_active'] ?? 1); ?>
						<input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" <?= $is_active ? 'checked' : '' ?>>
						<label class="form-check-label" for="isActive"><?= t('Active account') ?></label>
					</div>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save User') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
