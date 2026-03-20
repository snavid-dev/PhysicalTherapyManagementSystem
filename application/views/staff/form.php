<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('manage_staff') ?></p>
	</div>
	<a href="<?= base_url('staff') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action, array('id' => 'staffForm')) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('First Name') ?></label>
					<input type="text" name="first_name" class="form-control" value="<?= set_value('first_name', $staff['first_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('first_name') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Last Name') ?></label>
					<input type="text" name="last_name" class="form-control" value="<?= set_value('last_name', $staff['last_name'] ?? '') ?>">
					<small class="text-danger"><?= form_error('last_name') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Gender') ?></label>
					<?php $selected_gender = set_value('gender', $staff['gender'] ?? ''); ?>
					<select name="gender" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<option value="male" <?= $selected_gender === 'male' ? 'selected' : '' ?>><?= t('Male') ?></option>
						<option value="female" <?= $selected_gender === 'female' ? 'selected' : '' ?>><?= t('Female') ?></option>
					</select>
					<small class="text-danger"><?= form_error('gender') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('staff_type') ?></label>
					<?php $selected_type = (int) set_value('staff_type_id', $staff['staff_type_id'] ?? 0); ?>
					<select name="staff_type_id" id="staffTypeSelect" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($staff_types as $staff_type) : ?>
							<option value="<?= $staff_type['id'] ?>" data-type-name="<?= html_escape($staff_type['name']) ?>" <?= $selected_type === (int) $staff_type['id'] ? 'selected' : '' ?>><?= html_escape(t($staff_type['name'])) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('staff_type_id') ?></small>
				</div>
				<div class="col-md-4" id="sectionField">
					<label class="form-label"><?= t('section') ?></label>
					<?php $selected_sections = set_value('section_ids[]', $selected_section_ids ?? array()); ?>
					<select name="section_ids[]" id="sectionSelect" class="form-select" multiple size="5">
						<?php foreach ($sections as $section) : ?>
							<option value="<?= $section['id'] ?>" <?= in_array((string) $section['id'], array_map('strval', (array) $selected_sections), TRUE) ? 'selected' : '' ?>>
								<?= html_escape(t($section['name'])) ?> (<?= format_amount($section['default_fee']) ?>)
							</option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('section_ids[]') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('monthly_leave_quota') ?></label>
					<input type="number" name="monthly_leave_quota" class="form-control" min="0" value="<?= set_value('monthly_leave_quota', $staff['monthly_leave_quota'] ?? 4) ?>">
					<small class="text-danger"><?= form_error('monthly_leave_quota') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('salary') ?></label>
					<input type="number" name="salary" class="form-control" min="0" step="0.01" value="<?= set_value('salary', $staff['salary'] ?? '0.00') ?>">
					<small class="text-danger"><?= form_error('salary') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('Status') ?></label>
					<?php $selected_status = set_value('status', $staff['status'] ?? 'active'); ?>
					<select name="status" class="form-select">
						<option value="active" <?= $selected_status === 'active' ? 'selected' : '' ?>><?= t('Active') ?></option>
						<option value="inactive" <?= $selected_status === 'inactive' ? 'selected' : '' ?>><?= t('Inactive') ?></option>
					</select>
					<small class="text-danger"><?= form_error('status') ?></small>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('linked_user') ?></label>
					<?php $selected_user = (int) set_value('user_id', $staff['user_id'] ?? 0); ?>
					<select name="user_id" class="form-select">
						<option value=""><?= t('Auto Create Inactive User') ?></option>
						<?php foreach ($users as $user) : ?>
							<option value="<?= $user['id'] ?>" <?= $selected_user === (int) $user['id'] ? 'selected' : '' ?>><?= html_escape($user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['username'] . ')') ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('user_id') ?></small>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Staff') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>

<script>
(function () {
	const typeSelect = document.getElementById('staffTypeSelect');
	const sectionField = document.getElementById('sectionField');
	const sectionSelect = document.getElementById('sectionSelect');

	if (!typeSelect || !sectionField || !sectionSelect) {
		return;
	}

	function toggleSectionField() {
		const selectedOption = typeSelect.options[typeSelect.selectedIndex];
		const typeName = selectedOption && selectedOption.dataset.typeName
			? selectedOption.dataset.typeName.toLowerCase()
			: '';
		const shouldShow = ['doctor', 'physiotherapist', 'cleaner', 'intern', 'helper'].includes(typeName);

		sectionField.classList.toggle('d-none', !shouldShow);

		if (!shouldShow) {
			Array.from(sectionSelect.options).forEach(function (option) {
				option.selected = false;
			});
		}
	}

	typeSelect.addEventListener('change', toggleSectionField);
	toggleSectionField();
})();
</script>
