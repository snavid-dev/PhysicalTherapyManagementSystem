<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('ref_doctor_profile') ?></h1>
		<p class="text-muted mb-0"><?= html_escape($reference_doctor['first_name'] . ' ' . $reference_doctor['last_name']) ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('reference_doctors/edit/' . $reference_doctor['id']) ?>" class="btn btn-outline-secondary"><?= t('Edit') ?></a>
		<a href="<?= base_url('reference_doctors') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="row g-4">
	<div class="col-12 col-xl-8">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('reference_doctor') ?></h2>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Full Name') ?></div>
							<div class="fw-semibold"><?= html_escape($reference_doctor['first_name'] . ' ' . $reference_doctor['last_name']) ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('specialty') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['specialty'] ? html_escape($reference_doctor['specialty']) : '&mdash;' ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Phone') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['phone'] ? html_escape($reference_doctor['phone']) : '&mdash;' ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('clinic_name') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['clinic_name'] ? html_escape($reference_doctor['clinic_name']) : '&mdash;' ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Address') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['address'] ? nl2br(html_escape($reference_doctor['address'])) : '&mdash;' ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Notes') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['notes'] ? nl2br(html_escape($reference_doctor['notes'])) : '&mdash;' ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Status') ?></div>
							<div class="fw-semibold"><?= $reference_doctor['status'] === 'active' ? t('Active') : t('Inactive') ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-xl-4">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('total_referred') ?></h2>
				<div class="stat-value mb-2"><?= format_number($reference_doctor['total_referred']) ?></div>
				<span class="badge text-bg-dark"><?= t('all_referred_patients') ?></span>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('count_by_date_range') ?></h2>
				<button type="button" class="btn btn-dark" id="openPatientCountModal"><?= t('count_by_date_range') ?></button>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('all_referred_patients') ?></h2>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th><?= t('Full Name') ?></th>
								<th><?= t('Gender') ?></th>
								<th><?= t('Phone') ?></th>
								<th><?= t('date_registered') ?></th>
								<th class="text-end"><?= t('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($all_patients) : foreach ($all_patients as $patient) : ?>
							<tr>
								<td><?= html_escape($patient['full_name']) ?></td>
								<td><?= html_escape($patient['gender']) ?></td>
								<td><?= html_escape($patient['phone']) ?></td>
								<td><?= html_escape($patient['created_at']) ?></td>
								<td class="text-end">
									<a href="<?= base_url('patients/' . $patient['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a>
								</td>
							</tr>
						<?php endforeach; else : ?>
							<tr>
								<td colspan="5" class="text-muted"><?= t('no_referred_patients') ?></td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="patientCountModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title fs-5"><?= t('count_by_date_range') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<div class="modal-body">
				<div id="patientCountError" class="alert alert-danger d-none"></div>
				<div class="row g-3 align-items-end">
					<div class="col-md-4">
						<label class="form-label" for="dateFromInput"><?= t('date_from') ?></label>
						<input type="date" id="dateFromInput" class="form-control" value="<?= html_escape(date('Y-m-01')) ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="dateToInput"><?= t('date_to') ?></label>
						<input type="date" id="dateToInput" class="form-control" value="<?= html_escape(date('Y-m-t')) ?>">
					</div>
					<div class="col-md-4">
						<button type="button" class="btn btn-dark w-100" id="searchPatientCountButton">
							<span class="button-label"><?= t('Search') ?></span>
							<span class="spinner-border spinner-border-sm ms-2 d-none" id="patientCountSpinner" role="status" aria-hidden="true"></span>
						</button>
					</div>
				</div>

				<div id="patientCountResults" class="mt-4 d-none">
					<p class="fw-semibold mb-3" id="patientCountSummary"></p>
					<div class="table-responsive">
						<table class="table align-middle">
							<thead>
								<tr>
									<th><?= t('Full Name') ?></th>
									<th><?= t('Gender') ?></th>
									<th><?= t('Phone') ?></th>
									<th><?= t('date_registered') ?></th>
									<th class="text-end"><?= t('Actions') ?></th>
								</tr>
							</thead>
							<tbody id="patientCountTableBody"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('Close') ?></button>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	const modalElement = document.getElementById('patientCountModal');
	const openButton = document.getElementById('openPatientCountModal');
	const searchButton = document.getElementById('searchPatientCountButton');
	const spinner = document.getElementById('patientCountSpinner');
	const dateFromInput = document.getElementById('dateFromInput');
	const dateToInput = document.getElementById('dateToInput');
	const errorBox = document.getElementById('patientCountError');
	const resultsBox = document.getElementById('patientCountResults');
	const summaryBox = document.getElementById('patientCountSummary');
	const tableBody = document.getElementById('patientCountTableBody');
	const locale = <?= json_encode($current_locale === 'farsi' ? 'fa-IR' : 'en-US') ?>;
	const labels = {
		new_patients_referred: <?= json_encode(t('new_patients_referred')) ?>,
		no_patients_in_range: <?= json_encode(t('no_patients_in_range')) ?>,
		no_data_available: <?= json_encode(t('No data available.')) ?>,
		search_error: <?= json_encode(t('Please choose a valid date range.')) ?>,
		between: <?= json_encode(t('between')) ?>,
		and: <?= json_encode(t('and')) ?>,
	};

	function getModal() {
		if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
			return null;
		}

		return window.bootstrap.Modal.getOrCreateInstance(modalElement);
	}

	function escapeHtml(value) {
		return String(value === null || value === undefined ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function formatCount(value) {
		return new Intl.NumberFormat(locale).format(Number(value || 0));
	}

	function renderRows(patients) {
		if (!patients.length) {
			return '<tr><td colspan="5" class="text-muted">' + escapeHtml(labels.no_data_available) + '</td></tr>';
		}

		return patients.map(function (patient) {
			return '<tr>'
				+ '<td>' + escapeHtml(patient.full_name) + '</td>'
				+ '<td>' + escapeHtml(patient.gender) + '</td>'
				+ '<td>' + escapeHtml(patient.phone) + '</td>'
				+ '<td>' + escapeHtml(patient.created_at) + '</td>'
				+ '<td class="text-end"><a href=' + JSON.stringify(<?= json_encode(base_url('patients/')) ?> + patient.id) + ' class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a></td>'
				+ '</tr>';
		}).join('');
	}

	function setLoading(isLoading) {
		searchButton.disabled = isLoading;
		spinner.classList.toggle('d-none', !isLoading);
	}

	openButton.addEventListener('click', function () {
		errorBox.classList.add('d-none');
		const modal = getModal();
		if (modal) {
			modal.show();
		}
	});

	searchButton.addEventListener('click', function () {
		errorBox.classList.add('d-none');
		resultsBox.classList.add('d-none');
		setLoading(true);

		const payload = new URLSearchParams();
		payload.set('date_from', dateFromInput.value);
		payload.set('date_to', dateToInput.value);

		fetch(<?= json_encode(base_url('reference_doctors/patient_count/' . $reference_doctor['id'])) ?>, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			},
			body: payload.toString()
		})
			.then(function (response) {
				return response.json().then(function (data) {
					if (!response.ok || !data.success) {
						throw new Error(data.message || labels.search_error);
					}

					return data;
				});
			})
			.then(function (data) {
				if (Number(data.count) === 0) {
					summaryBox.textContent = labels.no_patients_in_range;
					tableBody.innerHTML = '';
				} else {
					summaryBox.textContent = formatCount(data.count) + ' ' + labels.new_patients_referred + ' ' + labels.between + ' ' + data.date_from + ' ' + labels.and + ' ' + data.date_to;
					tableBody.innerHTML = renderRows(data.patients || []);
				}

				if (Number(data.count) === 0) {
					tableBody.innerHTML = '<tr><td colspan="5" class="text-muted">' + escapeHtml(labels.no_patients_in_range) + '</td></tr>';
				}

				resultsBox.classList.remove('d-none');
			})
			.catch(function (error) {
				errorBox.textContent = error && error.message ? error.message : labels.search_error;
				errorBox.classList.remove('d-none');
			})
			.finally(function () {
				setLoading(false);
			});
	});
})();
</script>
