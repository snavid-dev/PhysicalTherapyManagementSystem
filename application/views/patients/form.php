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
			<?php $selected_gender = set_value('gender', $patient['gender'] ?? ''); ?>
			<?php $selected_reference_doctor = set_value('referred_by', $patient['referred_by'] ?? ''); ?>
			<div class="row g-4">
				<div class="col-12">
					<div class="patient-form-section">
						<div class="patient-form-section-head">
							<h2 class="h5 mb-1"><?= t('Patient Profile') ?></h2>
							<p class="text-muted mb-0"><?= t('Keep the patient record simple and reusable.') ?></p>
						</div>
						<div class="row g-3">
							<div class="col-lg-4 col-md-6">
								<label class="form-label"><?= t('First Name') ?></label>
								<input type="text" name="first_name" class="form-control" value="<?= set_value('first_name', $patient['first_name'] ?? '') ?>">
								<small class="text-danger"><?= form_error('first_name') ?></small>
							</div>
							<div class="col-lg-4 col-md-6">
								<label class="form-label"><?= t('Last Name') ?></label>
								<input type="text" name="last_name" class="form-control" value="<?= set_value('last_name', $patient['last_name'] ?? '') ?>">
								<small class="text-danger"><?= form_error('last_name') ?></small>
							</div>
							<div class="col-lg-4 col-md-6">
								<label class="form-label"><?= t('father_name') ?></label>
								<input type="text" name="father_name" class="form-control" value="<?= set_value('father_name', $patient['father_name'] ?? '') ?>">
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="form-label"><?= t('Gender') ?></label>
								<select name="gender" class="form-select">
									<option value=""><?= t('Select') ?></option>
									<option value="Male" <?= $selected_gender === 'Male' ? 'selected' : '' ?>><?= t('Male') ?></option>
									<option value="Female" <?= $selected_gender === 'Female' ? 'selected' : '' ?>><?= t('Female') ?></option>
								</select>
								<small class="text-danger"><?= form_error('gender') ?></small>
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="form-label"><?= t('age') ?></label>
								<input type="number" name="age" class="form-control" min="0" max="120" value="<?= set_value('age', $patient['age'] ?? '') ?>">
								<small class="text-danger"><?= form_error('age') ?></small>
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="form-label"><?= t('Referred By') ?></label>
								<select name="referred_by" class="form-select">
									<option value=""><?= t('-- None --') ?></option>
									<?php foreach ($reference_doctors as $reference_doctor) : ?>
										<option value="<?= $reference_doctor['id'] ?>" <?= (string) $selected_reference_doctor === (string) $reference_doctor['id'] ? 'selected' : '' ?>>
											<?= html_escape($reference_doctor['full_name']) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-5">
					<div class="patient-form-section h-100">
						<div class="patient-form-section-head">
							<h2 class="h5 mb-1"><?= t('Phone') ?></h2>
							<p class="text-muted mb-0"><?= t('Address') ?></p>
						</div>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label"><?= t('Phone 1') ?></label>
								<input type="text" name="phone" class="form-control" value="<?= set_value('phone', $patient['phone'] ?? '') ?>">
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= t('phone2') ?></label>
								<input type="text" name="phone2" class="form-control" value="<?= set_value('phone2', $patient['phone2'] ?? '') ?>">
							</div>
							<div class="col-12">
								<label class="form-label"><?= t('Address') ?></label>
								<textarea name="address" class="form-control" rows="5"><?= set_value('address', $patient['address'] ?? '') ?></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-7">
					<div class="patient-form-section h-100">
						<div class="patient-form-section-head">
							<h2 class="h5 mb-1"><?= t('Medical Notes') ?></h2>
							<p class="text-muted mb-0"><?= t('diagnoses') ?></p>
						</div>
						<div class="row g-3">
							<div class="col-12">
								<label class="form-label"><?= t('Medical Notes') ?></label>
								<textarea name="medical_notes" class="form-control patient-notes-field" rows="5"><?= set_value('medical_notes', $patient['medical_notes'] ?? '') ?></textarea>
							</div>
							<div class="col-12">
								<label class="form-label"><?= t('diagnoses') ?></label>
								<select name="diagnosis_ids[]" class="form-select diagnosis-multiselect" multiple size="8">
									<?php foreach ($diagnoses as $diagnosis) : ?>
										<?php $label = $is_rtl && !empty($diagnosis['name_fa']) ? $diagnosis['name_fa'] : $diagnosis['name']; ?>
										<option value="<?= $diagnosis['id'] ?>" <?= in_array((int) $diagnosis['id'], $selected_diagnosis_ids, TRUE) ? 'selected' : '' ?>>
											<?= html_escape($label) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mt-4 d-flex justify-content-end">
				<button type="submit" class="btn btn-dark"><?= t('Save Patient') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
