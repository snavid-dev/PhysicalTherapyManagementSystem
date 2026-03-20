<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Create or update a patient turn.') ?></p>
	</div>
	<a href="<?= base_url('turns') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('Patient') ?></label>
					<select name="patient_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php $selected_patient = (int) set_value('patient_id', $turn['patient_id'] ?? 0); ?>
						<?php foreach ($patients as $patient) : ?>
							<option value="<?= $patient['id'] ?>" <?= $selected_patient === (int) $patient['id'] ? 'selected' : '' ?>><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('patient_id') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Therapist') ?></label>
					<select name="doctor_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php $selected_doctor = (int) set_value('doctor_id', $turn['doctor_id'] ?? 0); ?>
						<?php foreach ($therapists as $therapist) : ?>
							<option value="<?= $therapist['id'] ?>" <?= $selected_doctor === (int) $therapist['id'] ? 'selected' : '' ?>><?= html_escape($therapist['first_name'] . ' ' . $therapist['last_name']) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('doctor_id') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Date') ?></label>
					<input type="date" name="turn_date" class="form-control" value="<?= set_value('turn_date', $turn['turn_date'] ?? '') ?>">
					<small class="text-danger"><?= form_error('turn_date') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Time') ?></label>
					<input type="time" name="turn_time" class="form-control" value="<?= set_value('turn_time', $turn['turn_time'] ?? '') ?>">
					<small class="text-danger"><?= form_error('turn_time') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Status') ?></label>
					<?php $status = set_value('status', $turn['status'] ?? 'scheduled'); ?>
					<select name="status" class="form-select">
						<option value="scheduled" <?= $status === 'scheduled' ? 'selected' : '' ?>><?= t('Scheduled') ?></option>
						<option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>><?= t('Completed') ?></option>
						<option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>><?= t('Cancelled') ?></option>
					</select>
					<small class="text-danger"><?= form_error('status') ?></small>
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Notes') ?></label>
					<textarea name="notes" class="form-control" rows="4"><?= set_value('notes', $turn['notes'] ?? '') ?></textarea>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Turn') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
