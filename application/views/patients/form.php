<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Keep the patient record simple and reusable.') ?></p>
	</div>
	<a href="<?= base_url('patients') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('First Name') ?></label>
					<input type="text" name="first_name" class="form-control" value="<?= set_value('first_name', $patient['first_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('first_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Last Name') ?></label>
					<input type="text" name="last_name" class="form-control" value="<?= set_value('last_name', $patient['last_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('last_name') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Gender') ?></label>
					<select name="gender" class="form-select">
						<?php $gender = set_value('gender', $patient['gender'] ?? ''); ?>
						<option value=""><?= t('Select') ?></option>
						<option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>><?= t('Male') ?></option>
						<option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>><?= t('Female') ?></option>
						<option value="Other" <?= $gender === 'Other' ? 'selected' : '' ?>><?= t('Other') ?></option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Date of Birth') ?></label>
					<input type="date" name="date_of_birth" class="form-control" value="<?= set_value('date_of_birth', $patient['date_of_birth'] ?? '') ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Phone') ?></label>
					<input type="text" name="phone" class="form-control" value="<?= set_value('phone', $patient['phone'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Email') ?></label>
					<input type="email" name="email" class="form-control" value="<?= set_value('email', $patient['email'] ?? '') ?>">
					<small class="text-danger"><?= form_error('email') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Address') ?></label>
					<input type="text" name="address" class="form-control" value="<?= set_value('address', $patient['address'] ?? '') ?>">
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Medical Notes') ?></label>
					<textarea name="medical_notes" class="form-control" rows="5"><?= set_value('medical_notes', $patient['medical_notes'] ?? '') ?></textarea>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Patient') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
