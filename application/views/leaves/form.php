<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Keep leave entries simple and visible.') ?></p>
	</div>
	<a href="<?= base_url('leaves') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('Therapist') ?></label>
					<select name="doctor_id" class="form-select s2-select">
						<option value=""><?= t('Select') ?></option>
						<?php $selected_doctor = (int) set_value('doctor_id', $leave['doctor_id'] ?? 0); ?>
						<?php foreach ($therapists as $therapist) : ?>
							<option value="<?= $therapist['id'] ?>" <?= $selected_doctor === (int) $therapist['id'] ? 'selected' : '' ?>><?= html_escape($therapist['first_name'] . ' ' . $therapist['last_name']) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('doctor_id') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Start Date') ?></label>
					<input type="text" name="start_date" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= set_value('start_date', isset($leave['start_date']) ? to_shamsi($leave['start_date']) : '') ?>">
					<small class="text-danger"><?= form_error('start_date') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('End Date') ?></label>
					<input type="text" name="end_date" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= set_value('end_date', isset($leave['end_date']) ? to_shamsi($leave['end_date']) : '') ?>">
					<small class="text-danger"><?= form_error('end_date') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Status') ?></label>
					<?php $status = set_value('status', $leave['status'] ?? 'approved'); ?>
					<select name="status" class="form-select">
						<option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>><?= t('Approved') ?></option>
						<option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>><?= t('Pending') ?></option>
						<option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>><?= t('Rejected') ?></option>
					</select>
					<small class="text-danger"><?= form_error('status') ?></small>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= t('Reason') ?></label>
					<input type="text" name="reason" class="form-control" value="<?= set_value('reason', $leave['reason'] ?? '') ?>">
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Leave') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
