<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Manage referral sources and linked patients.') ?></p>
	</div>
	<a href="<?= base_url('reference_doctors') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('First Name') ?></label>
					<input type="text" name="first_name" class="form-control" value="<?= set_value('first_name', $reference_doctor['first_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('first_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Last Name') ?></label>
					<input type="text" name="last_name" class="form-control" value="<?= set_value('last_name', $reference_doctor['last_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('last_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('specialty') ?></label>
					<input type="text" name="specialty" class="form-control" value="<?= set_value('specialty', $reference_doctor['specialty'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Phone') ?></label>
					<input type="text" name="phone" class="form-control" value="<?= set_value('phone', $reference_doctor['phone'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('clinic_name') ?></label>
					<input type="text" name="clinic_name" class="form-control" value="<?= set_value('clinic_name', $reference_doctor['clinic_name'] ?? '') ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Status') ?></label>
					<?php $selected_status = set_value('status', $reference_doctor['status'] ?? 'active'); ?>
					<select name="status" class="form-select">
						<option value="active" <?= $selected_status === 'active' ? 'selected' : '' ?>><?= t('Active') ?></option>
						<option value="inactive" <?= $selected_status === 'inactive' ? 'selected' : '' ?>><?= t('Inactive') ?></option>
					</select>
					<small class="text-danger"><?= form_error('status') ?></small>
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Address') ?></label>
					<textarea name="address" class="form-control" rows="3"><?= set_value('address', $reference_doctor['address'] ?? '') ?></textarea>
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Notes') ?></label>
					<textarea name="notes" class="form-control" rows="4"><?= set_value('notes', $reference_doctor['notes'] ?? '') ?></textarea>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Reference Doctor') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
