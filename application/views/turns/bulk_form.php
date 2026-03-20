<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Bulk Turn Entry') ?></h1>
		<p class="text-muted mb-0"><?= t('Add many turns for one day from a single screen.') ?></p>
	</div>
	<a href="<?= base_url('turns') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<?= form_open('turns/bulk-store', array('id' => 'bulkTurnsForm')) ?>
<div class="card mb-4">
	<div class="card-body">
		<h2 class="h5 mb-3"><?= t('Common Settings') ?></h2>
		<div class="row g-3 align-items-end">
			<div class="col-md-3">
				<label class="form-label"><?= t('Date') ?></label>
				<input type="date" name="turn_date" id="bulkTurnDate" class="form-control" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-md-3">
				<label class="form-label"><?= t('Therapist') ?></label>
				<select name="doctor_id" id="bulkDoctorId" class="form-select">
					<option value=""><?= t('Select') ?></option>
					<?php foreach ($therapists as $therapist) : ?>
						<option value="<?= $therapist['id'] ?>"><?= html_escape($therapist['first_name'] . ' ' . $therapist['last_name']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<label class="form-label"><?= t('Default Status') ?></label>
				<select name="default_status" id="bulkDefaultStatus" class="form-select">
					<option value="scheduled"><?= t('Scheduled') ?></option>
					<option value="completed"><?= t('Completed') ?></option>
					<option value="cancelled"><?= t('Cancelled') ?></option>
				</select>
			</div>
			<div class="col-md-2">
				<label class="form-label"><?= t('Start Time') ?></label>
				<input type="time" id="bulkStartTime" class="form-control" value="08:00">
			</div>
			<div class="col-md-2">
				<label class="form-label"><?= t('Interval Minutes') ?></label>
				<input type="number" id="bulkInterval" class="form-control" value="10" min="1">
			</div>
		</div>
		<div class="d-flex gap-2 mt-3">
			<button class="btn btn-outline-secondary" type="button" id="autofillTimes"><?= t('Autofill Times') ?></button>
			<button class="btn btn-outline-secondary" type="button" id="addRows"><?= t('Add 10 Rows') ?></button>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2 class="h5 mb-0"><?= t('Rows') ?></h2>
			<button type="submit" class="btn btn-dark"><?= t('Save All Turns') ?></button>
		</div>
		<div id="bulkRows" class="bulk-turn-grid"></div>
	</div>
</div>
<?= form_close() ?>

<template id="bulkTurnRowTemplate">
	<div class="row g-3 row-item align-items-end">
		<div class="col-lg-4">
			<label class="form-label"><?= t('Patient') ?></label>
			<select name="patient_id[]" class="form-select">
				<option value=""><?= t('Select') ?></option>
				<?php foreach ($patients as $patient) : ?>
					<option value="<?= $patient['id'] ?>"><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-lg-2">
			<label class="form-label"><?= t('Time') ?></label>
			<input type="time" name="turn_time[]" class="form-control turn-time-field">
		</div>
		<div class="col-lg-2">
			<label class="form-label"><?= t('Status') ?></label>
			<select name="status[]" class="form-select row-status">
				<option value="scheduled"><?= t('Scheduled') ?></option>
				<option value="completed"><?= t('Completed') ?></option>
				<option value="cancelled"><?= t('Cancelled') ?></option>
			</select>
		</div>
		<div class="col-lg-3">
			<label class="form-label"><?= t('Notes') ?></label>
			<input type="text" name="notes[]" class="form-control">
		</div>
		<div class="col-lg-1">
			<button type="button" class="btn btn-outline-danger w-100 remove-row"><?= t('Remove') ?></button>
		</div>
	</div>
</template>

<script>
	(function () {
		const rowsContainer = document.getElementById('bulkRows');
		const template = document.getElementById('bulkTurnRowTemplate');
		const addRowsBtn = document.getElementById('addRows');
		const autofillBtn = document.getElementById('autofillTimes');
		const defaultStatus = document.getElementById('bulkDefaultStatus');

		function appendRows(count) {
			for (let i = 0; i < count; i += 1) {
				const node = template.content.cloneNode(true);
				const row = node.querySelector('.row-item');
				const statusSelect = node.querySelector('.row-status');
				statusSelect.value = defaultStatus.value;
				row.querySelector('.remove-row').addEventListener('click', function () {
					row.remove();
				});
				rowsContainer.appendChild(node);
			}
		}

		function autofillTimes() {
			const start = document.getElementById('bulkStartTime').value;
			const interval = parseInt(document.getElementById('bulkInterval').value || '10', 10);
			if (!start) return;
			const fields = rowsContainer.querySelectorAll('.turn-time-field');
			const [hours, minutes] = start.split(':').map(Number);
			let totalMinutes = hours * 60 + minutes;
			fields.forEach(function (field) {
				const hh = String(Math.floor(totalMinutes / 60)).padStart(2, '0');
				const mm = String(totalMinutes % 60).padStart(2, '0');
				field.value = `${hh}:${mm}`;
				totalMinutes += interval;
			});
		}

		addRowsBtn.addEventListener('click', function () {
			appendRows(10);
		});

		autofillBtn.addEventListener('click', autofillTimes);
		defaultStatus.addEventListener('change', function () {
			rowsContainer.querySelectorAll('.row-status').forEach(function (select) {
				if (!select.dataset.changed) {
					select.value = defaultStatus.value;
				}
			});
		});

		rowsContainer.addEventListener('change', function (event) {
			if (event.target.classList.contains('row-status')) {
				event.target.dataset.changed = '1';
			}
		});

		appendRows(20);
	})();
</script>
