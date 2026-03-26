<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('staff_profile') ?></h1>
		<p class="text-muted mb-0"><?= html_escape($staff['first_name'] . ' ' . $staff['last_name']) ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('salaries/pay/' . $staff['id'] . '?month=' . rawurlencode($current_month_shamsi)) ?>" class="btn btn-dark"><?= t('go_to_salary_payment') ?></a>
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
									<?php if (!empty($staff['sections'])) : ?>
										<?= html_escape(implode(', ', array_map(function ($section) { return t($section['name']); }, $staff['sections']))) ?>
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
				<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
					<div>
						<h2 class="h5 mb-1"><?= t('calculate_salary') ?></h2>
						<p class="text-muted mb-0"><?= html_escape($current_month_shamsi) ?></p>
					</div>
					<div class="d-flex gap-2 flex-wrap">
						<input type="text" id="salaryMonth" class="form-control shamsi-month" placeholder="1403/01" value="<?= html_escape($current_month_shamsi) ?>" style="max-width: 180px;">
						<button type="button" class="btn btn-dark" id="calculateSalaryButton"><?= t('calculate_salary') ?></button>
					</div>
				</div>
				<div id="salaryCalculationError" class="alert alert-danger d-none"></div>
				<div id="salaryCalculationResult">
					<?php $salary_rows = array(
						array(t('month'), $current_month_shamsi),
						array(t('base_salary'), format_number($salary_calculation['base_salary'], 2)),
						array(t('monthly_leave_quota'), format_number($salary_calculation['leave_quota'] ?? $salary_calculation['monthly_leave_quota'], 0)),
						array(t('approved_leaves_in_range'), format_number($salary_calculation['approved_leaves'], 0)),
						array(t('paid_leaves'), format_number($salary_calculation['paid_leaves'], 0)),
						array(t('excess_leaves'), format_number($salary_calculation['excess_leaves'], 0)),
						array(t('deduction'), format_number($salary_calculation['deduction'], 2)),
						array(t('final_salary'), format_number($salary_calculation['final_salary'], 2)),
					); ?>
					<?php foreach ($salary_rows as $salary_row) : ?>
						<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3">
							<span class="text-muted"><?= html_escape($salary_row[0]) ?></span>
							<strong><?= html_escape($salary_row[1]) ?></strong>
						</div>
					<?php endforeach; ?>
					<?php if (($salary_calculation['salary_type'] ?? 'fixed') === 'hourly') : ?>
						<div class="alert alert-warning mb-0"><?= t('hourly_manual_note') ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	const button = document.getElementById('calculateSalaryButton');
	const monthInput = document.getElementById('salaryMonth');
	const result = document.getElementById('salaryCalculationResult');
	const errorBox = document.getElementById('salaryCalculationError');
	const locale = <?= json_encode($current_locale === 'farsi' ? 'fa-IR' : 'en-US') ?>;
	const labels = {
		month: <?= json_encode(t('month')) ?>,
		base_salary: <?= json_encode(t('base_salary')) ?>,
		monthly_leave_quota: <?= json_encode(t('monthly_leave_quota')) ?>,
		approved_leaves: <?= json_encode(t('approved_leaves_in_range')) ?>,
		paid_leaves: <?= json_encode(t('paid_leaves')) ?>,
		excess_leaves: <?= json_encode(t('excess_leaves')) ?>,
		deduction: <?= json_encode(t('deduction')) ?>,
		final_salary: <?= json_encode(t('final_salary')) ?>,
		hourly_manual_note: <?= json_encode(t('hourly_manual_note')) ?>
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
			[labels.month, data.month],
			[labels.base_salary, formatNumber(data.base_salary, 2)],
			[labels.monthly_leave_quota, formatNumber(data.leave_quota || data.monthly_leave_quota, 0)],
			[labels.approved_leaves, formatNumber(data.approved_leaves, 0)],
			[labels.paid_leaves, formatNumber(data.paid_leaves, 0)],
			[labels.excess_leaves, formatNumber(data.excess_leaves, 0)],
			[labels.deduction, formatNumber(data.deduction, 2)],
			[labels.final_salary, formatNumber(data.final_salary, 2)]
		];

		let html = rows.map(function (row) {
			return '<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3">'
				+ '<span class="text-muted">' + row[0] + '</span>'
				+ '<strong>' + row[1] + '</strong>'
				+ '</div>';
		}).join('');

		if (data.salary_type === 'hourly') {
			html += '<div class="alert alert-warning mb-0">' + labels.hourly_manual_note + '</div>';
		}

		return html;
	}

	button.addEventListener('click', function () {
		button.disabled = true;
		errorBox.classList.add('d-none');

		const payload = new URLSearchParams();
		payload.set('month', monthInput.value);

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

				result.innerHTML = renderRows(data);
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
