<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('staff_profile') ?></h1>
		<p class="text-muted mb-0"><?= html_escape($staff['first_name'] . ' ' . $staff['last_name']) ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('staff/edit/' . $staff['id']) ?>" class="btn btn-outline-secondary"><?= t('Edit') ?></a>
		<a href="<?= base_url('staff') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="row g-4">
	<div class="col-12">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('staff') ?></h2>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Full Name') ?></div>
							<div class="fw-semibold"><?= html_escape($staff['first_name'] . ' ' . $staff['last_name']) ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('staff_type') ?></div>
							<div class="fw-semibold"><?= html_escape(t($staff['staff_type_name'])) ?></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Gender') ?></div>
							<div class="fw-semibold"><?= $staff['gender'] === 'male' ? t('Male') : t('Female') ?></div>
						</div>
					</div>
					<?php if ($show_section) : ?>
						<div class="col-md-6">
							<div class="border rounded p-3 h-100">
								<div class="small text-muted mb-1"><?= t('section') ?></div>
								<div class="fw-semibold">
									<?php if ($staff['section'] === 'male') : ?>
										<?= t('male_section') ?>
									<?php elseif ($staff['section'] === 'female') : ?>
										<?= t('female_section') ?>
									<?php elseif ($staff['section'] === 'both') : ?>
										<?= t('both_sections') ?>
									<?php else : ?>
										<?= t('section_na') ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<div class="col-md-4">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('Status') ?></div>
							<div class="fw-semibold"><?= $staff['status'] === 'active' ? t('Active') : t('Inactive') ?></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('salary') ?></div>
							<div class="fw-semibold"><?= format_number($staff['salary'], 2) ?></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('monthly_leave_quota') ?></div>
							<div class="fw-semibold"><?= format_number($staff['monthly_leave_quota']) ?></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="border rounded p-3 h-100">
							<div class="small text-muted mb-1"><?= t('linked_user') ?></div>
							<div class="fw-semibold">
								<?php if (!empty($staff['user_id'])) : ?>
									<?= html_escape(trim($staff['linked_first_name'] . ' ' . $staff['linked_last_name'])) ?>
									<?php if (!empty($staff['linked_username'])) : ?>
										<span class="text-muted">(<?= html_escape($staff['linked_username']) ?>)</span>
									<?php endif; ?>
								<?php else : ?>
									<?= t('no_linked_user') ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-5">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('patients_last_month') ?></h2>
				<?php if (!empty($staff['user_id'])) : ?>
					<div class="stat-value mb-2"><?= format_number($patients_last_month) ?></div>
				<?php else : ?>
					<div class="stat-value mb-2"><?= format_number(0) ?></div>
					<div class="text-muted"><?= t('no_linked_user') ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-7">
			<div class="card h-100">
				<div class="card-body">
					<h2 class="h5 mb-3"><?= t('calculate_salary') ?></h2>
				<button type="button" class="btn btn-dark" id="openSalaryModalButton"><?= t('calculate_salary') ?></button>
				<div id="salaryCalculationError" class="alert alert-danger mt-3 d-none"></div>
				<div id="salaryCalculationResult" class="mt-3 d-none"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="salaryRangeModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title fs-5"><?= t('Select Salary Date Range') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label" for="salaryFromDate"><?= t('From') ?></label>
						<input type="date" id="salaryFromDate" class="form-control" value="<?= html_escape(date('Y-m-01')) ?>">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="salaryToDate"><?= t('To') ?></label>
						<input type="date" id="salaryToDate" class="form-control" value="<?= html_escape(date('Y-m-t')) ?>">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('Close') ?></button>
				<button type="button" class="btn btn-dark" id="calculateSalaryButton"><?= t('calculate_salary') ?></button>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	const openModalButton = document.getElementById('openSalaryModalButton');
	const button = document.getElementById('calculateSalaryButton');
	const modalElement = document.getElementById('salaryRangeModal');
	const fromDateInput = document.getElementById('salaryFromDate');
	const toDateInput = document.getElementById('salaryToDate');
	const result = document.getElementById('salaryCalculationResult');
	const errorBox = document.getElementById('salaryCalculationError');
	const locale = <?= json_encode($current_locale === 'farsi' ? 'fa-IR' : 'en-US') ?>;
	const labels = {
		from_date: <?= json_encode(t('From')) ?>,
		to_date: <?= json_encode(t('To')) ?>,
		base_salary: <?= json_encode(t('base_salary')) ?>,
		monthly_leave_quota: <?= json_encode(t('monthly_leave_quota')) ?>,
		approved_leaves: <?= json_encode(t('approved_leaves_in_range')) ?>,
		paid_leaves: <?= json_encode(t('paid_leaves')) ?>,
		excess_leaves: <?= json_encode(t('excess_leaves')) ?>,
		deduction: <?= json_encode(t('deduction')) ?>,
		final_salary: <?= json_encode(t('final_salary')) ?>
	};

	function formatNumber(value, decimals) {
		if (value === null || value === undefined || value === '') {
			return '—';
		}

		return new Intl.NumberFormat(locale, {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals
		}).format(Number(value));
	}

	function renderRows(data) {
		const rows = [
			[labels.from_date, data.from_date],
			[labels.to_date, data.to_date],
			[labels.base_salary, formatNumber(data.base_salary, 2)],
			[labels.monthly_leave_quota, formatNumber(data.monthly_leave_quota, 0)],
			[labels.approved_leaves, formatNumber(data.approved_leaves, 0)],
			[labels.paid_leaves, formatNumber(data.paid_leaves, 0)],
			[labels.excess_leaves, formatNumber(data.excess_leaves, 0)],
			[labels.deduction, formatNumber(data.deduction, 2)],
			[labels.final_salary, formatNumber(data.final_salary, 2)]
		];

		return rows.map(function (row) {
			return '<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3">'
				+ '<span class="text-muted">' + row[0] + '</span>'
				+ '<strong>' + row[1] + '</strong>'
				+ '</div>';
		}).join('');
	}

	function getSalaryModal() {
		if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
			return null;
		}

		return window.bootstrap.Modal.getOrCreateInstance(modalElement);
	}

	if (openModalButton) {
		openModalButton.addEventListener('click', function () {
			errorBox.classList.add('d-none');

			const salaryModal = getSalaryModal();
			if (salaryModal) {
				salaryModal.show();
			}
		});
	}

	button.addEventListener('click', function () {
		button.disabled = true;
		errorBox.classList.add('d-none');
		result.classList.add('d-none');

		const payload = new URLSearchParams();
		payload.set('from_date', fromDateInput.value);
		payload.set('to_date', toDateInput.value);

		fetch(<?= json_encode(base_url('staff/calculate_salary/' . $staff['id'])) ?>, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			},
			body: payload.toString()
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (data) {
				if (data.error) {
					throw new Error(data.error);
				}

				const salaryModal = getSalaryModal();
				if (salaryModal) {
					salaryModal.hide();
				}

				result.innerHTML = renderRows(data);
				result.classList.remove('d-none');
			})
			.catch(function (error) {
				errorBox.textContent = error && error.message ? error.message : <?= json_encode(t('Unable to calculate salary right now.')) ?>;
				errorBox.classList.remove('d-none');
			})
			.finally(function () {
				button.disabled = false;
			});
	});
})();
</script>
