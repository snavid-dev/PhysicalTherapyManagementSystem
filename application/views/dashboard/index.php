<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Dashboard') ?></h1>
		<p class="text-muted mb-0"><?= t('Overview of the physical therapy clinic.') ?></p>
	</div>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
	<?php if (isset($safe_balance) && $safe_balance !== NULL) : ?>
		<div class="col">
			<div class="card h-100 dashboard-safe-card <?= (float) $safe_balance > 0 ? 'dashboard-safe-card--positive' : 'dashboard-safe-card--neutral' ?>">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start gap-3">
						<div>
							<div class="stat-label"><?= t('safe') ?></div>
							<div class="stat-value"><?= format_number($safe_balance, 2) ?></div>
						</div>
						<div class="dashboard-safe-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18a2 2 0 0 1 2 2v1.5H6.5A2.5 2.5 0 0 0 4 11v5.5A2.5 2.5 0 0 0 6.5 19H20V7"></path>
								<path d="M20 10H17a2 2 0 0 0 0 4h3"></path>
								<circle cx="17" cy="12" r=".25"></circle>
							</svg>
						</div>
					</div>
					<a href="<?= base_url('safe') ?>" class="dashboard-safe-card__link btn-icon mt-auto pt-2"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Patients') ?></div><div class="stat-value"><?= (int) $stats['patients'] ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Users') ?></div><div class="stat-value"><?= (int) $stats['users'] ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Today Turns') ?></div><div class="stat-value"><?= (int) $stats['today_turns'] ?></div></div></div></div>
	<?php if ($new_patients_this_month !== NULL) : ?>
		<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('new_patients_this_month') ?></div><div class="stat-value"><?= (int) $new_patients_this_month ?></div></div></div></div>
	<?php endif; ?>
	<?php if ($expenses_this_month !== NULL) : ?>
		<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('this_month_expenses') ?></div><div class="stat-value"><?= format_number($expenses_this_month, 2) ?></div></div></div></div>
	<?php endif; ?>
</div>

<?php $has_pending_approvals_card = isset($pending_requisitions_count) || isset($pending_sale_batches_count); ?>
<?php if ($has_pending_approvals_card || $open_debt_summary !== NULL || $staff_on_leave !== NULL || $unpaid_salary_count !== NULL) : ?>
	<h2 class="h5 mb-3"><?= t('needs_attention') ?></h2>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
		<?php if ($has_pending_approvals_card) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<?php if (isset($pending_requisitions_count)) : ?>
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="text-muted small"><?= t('pending_requisitions') ?></span>
									<span class="badge rounded-pill <?= $pending_requisitions_count > 0 ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' ?>"><?= (int) $pending_requisitions_count ?></span>
								</div>
							<?php endif; ?>
							<?php if (isset($pending_sale_batches_count)) : ?>
								<div class="d-flex justify-content-between align-items-center">
									<span class="text-muted small"><?= t('pending_sale_batches') ?></span>
									<span class="badge rounded-pill <?= $pending_sale_batches_count > 0 ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' ?>"><?= (int) $pending_sale_batches_count ?></span>
								</div>
							<?php endif; ?>
						</div>
						<div class="mt-auto pt-2 d-flex flex-wrap gap-3">
							<?php if (isset($pending_requisitions_count)) : ?>
								<a href="<?= base_url('store/requisitions') ?>" class="btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('requisitions') ?></a>
							<?php endif; ?>
							<?php if (isset($pending_sale_batches_count)) : ?>
								<a href="<?= base_url('store/sale_batches') ?>" class="btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('sale_batches') ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($open_debt_summary !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="stat-label"><?= t('total_open_debt') ?></div>
							<div class="stat-value"><?= format_number($open_debt_summary['total_amount'], 2) ?></div>
							<div class="text-muted small mt-1"><?= (int) $open_debt_summary['patient_count'] ?> <?= t('patients_owe_total') ?></div>
						</div>
						<a href="<?= base_url('reports/outstanding-balances') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($staff_on_leave !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="text-muted small"><?= t('staff_on_leave_today') ?></span>
								<span class="badge rounded-pill <?= $staff_on_leave ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary' ?>"><?= count($staff_on_leave) ?></span>
							</div>
							<?php if ($staff_on_leave) : ?>
								<div class="small text-muted"><?= html_escape(implode(', ', array_map(static function ($staff_member) {
									return trim($staff_member['first_name'] . ' ' . ($staff_member['last_name'] ?? ''));
								}, $staff_on_leave))) ?></div>
							<?php else : ?>
								<div class="small text-muted"><?= t('no_staff_on_leave_today') ?></div>
							<?php endif; ?>
						</div>
						<a href="<?= base_url('leaves') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($unpaid_salary_count !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="stat-label"><?= t('unpaid_salaries_this_month') ?></div>
							<div class="stat-value"><?= (int) $unpaid_salary_count ?></div>
							<?php if ($unpaid_salary_count === 0) : ?>
								<div class="text-muted small mt-1"><?= t('no_unpaid_salaries') ?></div>
							<?php endif; ?>
						</div>
						<a href="<?= base_url('salaries') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($this->auth->has_permission('view_reports')) : ?>
	<h2 class="h5 mb-3"><?= t('Reports') ?></h2>
	<div class="row row-cols-1 row-cols-sm-2 g-3 mb-4">
		<div class="col">
			<div class="card h-100">
				<div class="card-body">
					<h3 class="h6 mb-1"><?= t('financial_summary_report') ?></h3>
					<p class="text-muted small mb-3"><?= t('financial_summary_report_hint') ?></p>
					<button type="button" class="btn btn-dark btn-icon dashboard-report-card" data-report-type="financial_summary" data-report-url="<?= base_url('reports/financial-summary') ?>" data-data-url="<?= base_url('reports/financial-summary/data') ?>"><i class="bi bi-graph-up" aria-hidden="true"></i> <?= t('view_report') ?></button>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card h-100">
				<div class="card-body">
					<h3 class="h6 mb-1"><?= t('doctor_referral_report') ?></h3>
					<p class="text-muted small mb-3"><?= t('doctor_referral_report_hint') ?></p>
					<button type="button" class="btn btn-dark btn-icon dashboard-report-card" data-report-type="doctor_referrals" data-report-url="<?= base_url('reports/doctor-referrals') ?>" data-data-url="<?= base_url('reports/doctor-referrals/data') ?>"><i class="bi bi-person-heart" aria-hidden="true"></i> <?= t('view_report') ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="dashboardReportDateModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title fs-5" id="dashboardReportModalTitle"><?= t('choose_date_range') ?></h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
				</div>
				<div class="modal-body">
					<div id="dashboardReportError" class="alert alert-danger d-none"></div>
					<div class="row g-3 align-items-end">
						<div class="col-md-5">
							<label class="form-label" for="dashboardReportFrom"><?= t('From') ?></label>
							<input type="text" id="dashboardReportFrom" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape(to_shamsi(date('Y-m-01'))) ?>">
						</div>
						<div class="col-md-5">
							<label class="form-label" for="dashboardReportTo"><?= t('To') ?></label>
							<input type="text" id="dashboardReportTo" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape(shamsi_today()) ?>">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-dark w-100 btn-icon" id="dashboardReportSearchButton">
								<i class="bi bi-search" aria-hidden="true"></i>
								<span class="spinner-border spinner-border-sm ms-1 d-none" id="dashboardReportSpinner" role="status" aria-hidden="true"></span>
							</button>
						</div>
					</div>

					<div id="dashboardReportResults" class="mt-4 d-none"></div>
				</div>
				<div class="modal-footer">
					<a href="#" target="_blank" rel="noopener" class="btn btn-outline-dark btn-icon d-none" id="dashboardReportPrintLink"><i class="bi bi-printer" aria-hidden="true"></i> <?= t('dt_print') ?></a>
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
				</div>
			</div>
		</div>
	</div>

	<script>
	(function () {
		const modalElement = document.getElementById('dashboardReportDateModal');

		if (!modalElement) {
			return;
		}

		function getModal() {
			if (!window.bootstrap || !window.bootstrap.Modal) {
				return null;
			}

			return window.bootstrap.Modal.getOrCreateInstance(modalElement);
		}

		const titleEl = document.getElementById('dashboardReportModalTitle');
		const fromInput = document.getElementById('dashboardReportFrom');
		const toInput = document.getElementById('dashboardReportTo');
		const searchButton = document.getElementById('dashboardReportSearchButton');
		const spinner = document.getElementById('dashboardReportSpinner');
		const errorBox = document.getElementById('dashboardReportError');
		const resultsBox = document.getElementById('dashboardReportResults');
		const printLink = document.getElementById('dashboardReportPrintLink');
		const locale = <?= json_encode($current_locale === 'farsi' ? 'fa-IR' : 'en-US') ?>;
		let reportType = '';
		let dataUrl = '';
		let reportUrl = '';

		const labels = {
			financial_summary: <?= json_encode(t('financial_summary_report')) ?>,
			doctor_referrals: <?= json_encode(t('doctor_referral_report')) ?>,
			safe_balance_before: <?= json_encode(t('safe_balance_before')) ?>,
			safe_balance_after: <?= json_encode(t('safe_balance_after')) ?>,
			total_income: <?= json_encode(t('total_income')) ?>,
			total_expenses: <?= json_encode(t('total_expenses')) ?>,
			section: <?= json_encode(t('section')) ?>,
			patient_count: <?= json_encode(t('patient_count')) ?>,
			reference_doctor: <?= json_encode(t('reference_doctor')) ?>,
			specialty: <?= json_encode(t('specialty')) ?>,
			patients_referred: <?= json_encode(t('patients_referred')) ?>,
			no_data: <?= json_encode(t('No data available.')) ?>,
			search_error: <?= json_encode(t('Please choose a valid date range.')) ?>,
		};

		function formatAmount(value) {
			return new Intl.NumberFormat(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value || 0));
		}

		function formatCount(value) {
			return new Intl.NumberFormat(locale).format(Number(value || 0));
		}

		function escapeHtml(value) {
			return String(value === null || value === undefined ? '' : value)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		function renderFinancialSummary(data) {
			let html = '<div class="row row-cols-2 row-cols-md-4 g-3 mb-3">';
			[
				[labels.safe_balance_before, data.safe_balance_before],
				[labels.total_income, data.sections.reduce(function (sum, s) { return sum + Number(s.total_income || 0); }, 0)],
				[labels.total_expenses, data.expenses_total],
				[labels.safe_balance_after, data.safe_balance_after],
			].forEach(function (pair) {
				html += '<div class="col"><div class="card h-100"><div class="card-body p-2"><div class="stat-label small">' + escapeHtml(pair[0]) + '</div><div class="stat-value fs-6">' + formatAmount(pair[1]) + '</div></div></div></div>';
			});
			html += '</div>';

			html += '<div class="table-responsive"><table class="table table-sm align-middle mb-0"><thead><tr><th>' + escapeHtml(labels.section) + '</th><th class="text-end">' + escapeHtml(labels.patient_count) + '</th><th class="text-end">' + escapeHtml(labels.total_income) + '</th></tr></thead><tbody>';

			if (!data.sections.length) {
				html += '<tr><td colspan="3" class="text-muted">' + escapeHtml(labels.no_data) + '</td></tr>';
			} else {
				data.sections.forEach(function (section) {
					html += '<tr><td>' + escapeHtml(section.section_name) + '</td><td class="text-end">' + formatCount(section.patient_count) + '</td><td class="text-end">' + formatAmount(section.total_income) + '</td></tr>';
				});
			}

			html += '</tbody></table></div>';

			return html;
		}

		function renderDoctorReferrals(data) {
			let html = '<div class="table-responsive"><table class="table table-sm align-middle mb-0"><thead><tr><th>' + escapeHtml(labels.reference_doctor) + '</th><th>' + escapeHtml(labels.specialty) + '</th><th class="text-end">' + escapeHtml(labels.patients_referred) + '</th></tr></thead><tbody>';

			if (!data.doctors.length) {
				html += '<tr><td colspan="3" class="text-muted">' + escapeHtml(labels.no_data) + '</td></tr>';
			} else {
				data.doctors.forEach(function (doctor) {
					html += '<tr><td>' + escapeHtml(doctor.name) + '</td><td>' + escapeHtml(doctor.specialty || '—') + '</td><td class="text-end"><span class="badge rounded-pill bg-dark-subtle text-dark-emphasis">' + formatCount(doctor.referred_count) + '</span></td></tr>';
				});
			}

			html += '</tbody></table></div>';

			return html;
		}

		function setLoading(isLoading) {
			searchButton.disabled = isLoading;
			spinner.classList.toggle('d-none', !isLoading);
		}

		document.querySelectorAll('.dashboard-report-card').forEach(function (button) {
			button.addEventListener('click', function () {
				reportType = button.getAttribute('data-report-type');
				dataUrl = button.getAttribute('data-data-url');
				reportUrl = button.getAttribute('data-report-url');
				titleEl.textContent = labels[reportType] || labels.financial_summary;
				errorBox.classList.add('d-none');
				resultsBox.classList.add('d-none');
				printLink.classList.add('d-none');

				const modal = getModal();
				if (modal) {
					modal.show();
				}
			});
		});

		searchButton.addEventListener('click', function () {
			errorBox.classList.add('d-none');
			resultsBox.classList.add('d-none');
			printLink.classList.add('d-none');
			setLoading(true);

			const payload = new URLSearchParams();
			payload.set('date_from', fromInput.value);
			payload.set('date_to', toInput.value);

			fetch(dataUrl, {
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
					resultsBox.innerHTML = reportType === 'doctor_referrals' ? renderDoctorReferrals(data) : renderFinancialSummary(data);
					resultsBox.classList.remove('d-none');

					const printParams = new URLSearchParams();
					printParams.set('from', data.date_from);
					printParams.set('to', data.date_to);
					printLink.href = reportUrl + '/print?' + printParams.toString();
					printLink.classList.remove('d-none');
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
<?php endif; ?>

<?php if ($turns_by_section !== NULL) : ?>
	<div class="row g-3">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h2 class="h5 mb-3"><?= t('turns_by_section_today') ?></h2>
					<?= form_open('dashboard', array('method' => 'get', 'class' => 'row g-3 align-items-end mb-3')) ?>
						<div class="col-md-4">
							<label class="form-label"><?= t('From') ?></label>
							<input type="text" name="turns_from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($turns_by_section_from) ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= t('To') ?></label>
							<input type="text" name="turns_to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($turns_by_section_to) ?>">
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-dark btn-icon"><i class="bi bi-funnel" aria-hidden="true"></i> <?= t('Apply') ?></button>
						</div>
					<?= form_close() ?>
					<?php if ($turns_by_section) : ?>
						<div class="table-responsive">
							<table class="table table-sm align-middle mb-0">
								<tbody>
								<?php foreach ($turns_by_section as $row) : ?>
									<tr>
										<td><?= !empty($row['section_name']) ? html_escape(t($row['section_name'])) : '&mdash;' ?></td>
										<td class="text-end"><span class="badge rounded-pill bg-dark-subtle text-dark-emphasis"><?= (int) $row['turn_count'] ?></span></td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<p class="text-muted mb-0"><?= t('No data available.') ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
