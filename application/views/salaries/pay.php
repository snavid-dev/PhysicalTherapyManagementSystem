<?php
$staff_name = trim($staff['first_name'] . ' ' . $staff['last_name']);
$is_paid = $record['status'] === 'paid';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('Pay Salary') ?> - <?= html_escape($staff_name) ?> - <?= html_escape($month) ?></h1>
		<p class="text-muted mb-0"><?= t('salary_payment') ?></p>
	</div>
	<a href="<?= base_url('salaries?month=' . rawurlencode($month)) ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="row g-3 align-items-end">
			<div class="col-md-4">
				<label class="form-label" for="salaryPayMonth"><?= t('month') ?></label>
				<input type="month" id="salaryPayMonth" class="form-control" value="<?= html_escape($month) ?>">
			</div>
			<div class="col-md-4">
				<a href="<?= base_url('salaries?month=' . rawurlencode($month)) ?>" class="btn btn-outline-secondary"><?= t('View All Salaries') ?></a>
			</div>
		</div>
	</div>
</div>

<div class="row g-4">
	<div class="col-12 col-lg-5">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Salary Calculation') ?></h2>
				<div id="salaryCalculationCard">
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('base_salary') ?></span><strong><?= format_number($calculation['base_salary'], 2) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('monthly_leave_quota') ?></span><strong><?= format_number($calculation['leave_quota'], 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('approved_leaves_in_range') ?></span><strong><?= format_number($calculation['approved_leaves'], 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('paid_leaves') ?></span><strong><?= format_number($calculation['paid_leaves'], 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('excess_leaves') ?></span><strong><?= format_number($calculation['excess_leaves'], 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('deduction') ?></span><strong><?= format_number($calculation['deduction'], 2) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 gap-3"><span class="text-muted"><?= t('final_salary') ?></span><strong><?= format_number($calculation['final_salary'], 2) ?></strong></div>
				</div>
				<?php if ($calculation['salary_type'] === 'hourly') : ?>
					<div class="alert alert-warning mt-3 mb-0" id="hourlyManualNote"><?= t('hourly_manual_note') ?></div>
				<?php else : ?>
					<div class="alert alert-warning mt-3 mb-0 d-none" id="hourlyManualNote"><?= t('hourly_manual_note') ?></div>
				<?php endif; ?>
				<div id="salaryAjaxError" class="alert alert-danger mt-3 d-none"></div>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-7">
		<div class="card mb-4">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
					<h2 class="h5 mb-0"><?= t('payment_history') ?></h2>
					<div class="text-muted"><?= t('total_paid') ?>: <strong id="totalPaidValue"><?= format_number($record['total_paid'], 2) ?></strong></div>
				</div>
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead>
							<tr>
								<th><?= t('Date') ?></th>
								<th><?= t('Amount') ?></th>
								<th><?= t('Notes') ?></th>
								<th><?= t('recorded_by') ?></th>
							</tr>
						</thead>
						<tbody id="paymentHistoryBody">
						<?php if ($payments) : ?>
							<?php foreach ($payments as $payment) : ?>
								<tr>
									<td><?= html_escape($payment['payment_date']) ?></td>
									<td><?= format_number($payment['amount'], 2) ?></td>
									<td><?= $payment['note'] ? html_escape($payment['note']) : '&mdash;' ?></td>
									<td><?= !empty($payment['created_by_first_name']) || !empty($payment['created_by_last_name']) ? html_escape(trim($payment['created_by_first_name'] . ' ' . $payment['created_by_last_name'])) : '&mdash;' ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr><td colspan="4" class="text-muted"><?= t('No salary payments recorded yet.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="mt-3">
					<span class="fw-semibold"><?= t('remaining') ?>:</span>
					<strong id="remainingValue" class="<?= $remaining_amount > 0 ? 'text-danger' : 'text-success' ?>"><?= format_number($remaining_amount, 2) ?></strong>
				</div>
			</div>
		</div>

		<div class="card" id="paymentFormCard">
			<div class="card-body">
				<?php if ($is_paid) : ?>
					<div class="alert alert-success mb-0" id="salaryPaidMessage"><?= t('salary_fully_paid') ?></div>
				<?php else : ?>
					<div class="alert alert-success d-none" id="salaryPaidMessage"><?= t('salary_fully_paid') ?></div>
				<?php endif; ?>

				<div id="salaryPaymentFormWrap" class="<?= $is_paid ? 'd-none' : '' ?>">
					<h2 class="h5 mb-3"><?= t('record_payment') ?></h2>
					<?= form_open('salaries/store-payment') ?>
						<input type="hidden" name="staff_id" value="<?= (int) $staff['id'] ?>">
						<input type="hidden" name="month" id="paymentMonthInput" value="<?= html_escape($month) ?>">
						<div class="row g-3">
							<div class="col-md-4">
								<label class="form-label"><?= t('Amount') ?></label>
								<input type="number" step="0.01" min="0.01" name="amount" id="paymentAmountInput" class="form-control" value="<?= html_escape(number_format($remaining_amount, 2, '.', '')) ?>">
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('Payment Date') ?></label>
								<input type="date" name="payment_date" class="form-control" value="<?= html_escape(date('Y-m-d')) ?>">
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('Notes') ?></label>
								<input type="text" name="note" class="form-control" value="">
							</div>
						</div>
						<div class="mt-4">
							<button type="submit" class="btn btn-dark"><?= t('record_payment') ?></button>
						</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	const monthInput = document.getElementById('salaryPayMonth');
	const salaryCard = document.getElementById('salaryCalculationCard');
	const hourlyNote = document.getElementById('hourlyManualNote');
	const errorBox = document.getElementById('salaryAjaxError');
	const paymentHistoryBody = document.getElementById('paymentHistoryBody');
	const totalPaidValue = document.getElementById('totalPaidValue');
	const remainingValue = document.getElementById('remainingValue');
	const paymentAmountInput = document.getElementById('paymentAmountInput');
	const paymentMonthInput = document.getElementById('paymentMonthInput');
	const paidMessage = document.getElementById('salaryPaidMessage');
	const formWrap = document.getElementById('salaryPaymentFormWrap');
	const locale = <?= json_encode($current_locale === 'farsi' ? 'fa-IR' : 'en-US') ?>;
	const labels = {
		base_salary: <?= json_encode(t('base_salary')) ?>,
		monthly_leave_quota: <?= json_encode(t('monthly_leave_quota')) ?>,
		approved_leaves: <?= json_encode(t('approved_leaves_in_range')) ?>,
		paid_leaves: <?= json_encode(t('paid_leaves')) ?>,
		excess_leaves: <?= json_encode(t('excess_leaves')) ?>,
		deduction: <?= json_encode(t('deduction')) ?>,
		final_salary: <?= json_encode(t('final_salary')) ?>,
		no_payments: <?= json_encode(t('No salary payments recorded yet.')) ?>,
		unknown_user: '--'
	};

	function formatNumber(value, decimals) {
		return new Intl.NumberFormat(locale, {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals
		}).format(Number(value || 0));
	}

	function escapeHtml(value) {
		return String(value === null || value === undefined ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function renderCalculation(calculation) {
		const rows = [
			[labels.base_salary, formatNumber(calculation.base_salary, 2)],
			[labels.monthly_leave_quota, formatNumber(calculation.leave_quota || calculation.monthly_leave_quota, 0)],
			[labels.approved_leaves, formatNumber(calculation.approved_leaves, 0)],
			[labels.paid_leaves, formatNumber(calculation.paid_leaves, 0)],
			[labels.excess_leaves, formatNumber(calculation.excess_leaves, 0)],
			[labels.deduction, formatNumber(calculation.deduction, 2)],
			[labels.final_salary, formatNumber(calculation.final_salary, 2)]
		];

		salaryCard.innerHTML = rows.map(function (row) {
			return '<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted">' + row[0] + '</span><strong>' + row[1] + '</strong></div>';
		}).join('');
		hourlyNote.classList.toggle('d-none', calculation.salary_type !== 'hourly');
	}

	function renderPayments(payments) {
		if (!payments.length) {
			paymentHistoryBody.innerHTML = '<tr><td colspan="4" class="text-muted">' + labels.no_payments + '</td></tr>';
			return;
		}

		paymentHistoryBody.innerHTML = payments.map(function (payment) {
			const recordedBy = payment.created_by_first_name || payment.created_by_last_name
				? (payment.created_by_first_name + ' ' + payment.created_by_last_name).trim()
				: labels.unknown_user;
			const note = payment.note ? payment.note : labels.unknown_user;

			return '<tr>'
				+ '<td>' + escapeHtml(payment.payment_date) + '</td>'
				+ '<td>' + formatNumber(payment.amount, 2) + '</td>'
				+ '<td>' + escapeHtml(note) + '</td>'
				+ '<td>' + escapeHtml(recordedBy) + '</td>'
				+ '</tr>';
		}).join('');
	}

	function updateStatus(record, remainingAmount) {
		totalPaidValue.textContent = formatNumber(record.total_paid, 2);
		remainingValue.textContent = formatNumber(remainingAmount, 2);
		remainingValue.classList.toggle('text-danger', Number(remainingAmount) > 0);
		remainingValue.classList.toggle('text-success', Number(remainingAmount) <= 0);
		paymentMonthInput.value = monthInput.value;

		if (paymentAmountInput) {
			paymentAmountInput.value = Number(remainingAmount).toFixed(2);
		}

		const isPaid = record.status === 'paid';
		paidMessage.classList.toggle('d-none', !isPaid);
		formWrap.classList.toggle('d-none', isPaid);
	}

	function refreshMonth() {
		const payload = new URLSearchParams();
		payload.set('staff_id', <?= (int) $staff['id'] ?>);
		payload.set('month', monthInput.value);
		errorBox.classList.add('d-none');

		fetch(<?= json_encode(base_url('salaries/get-calculation')) ?>, {
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

				renderCalculation(data.calculation);
				renderPayments(data.payments || []);
				updateStatus(data.record, data.remaining_amount);
				window.history.replaceState({}, '', <?= json_encode(base_url('salaries/pay/' . $staff['id'])) ?> + '?month=' + encodeURIComponent(monthInput.value));
			})
			.catch(function (error) {
				errorBox.textContent = error && error.message ? error.message : <?= json_encode(t('Unable to load salary calculation right now.')) ?>;
				errorBox.classList.remove('d-none');
			});
	}

	if (monthInput) {
		monthInput.addEventListener('change', refreshMonth);
	}
})();
</script>
