<?php
$staff_name = trim($staff['first_name'] . ' ' . $staff['last_name']);
$is_settled = !empty($record['settled']);
$is_paid = $record['status'] === 'paid';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('Pay Salary') ?> - <?= html_escape($staff_name) ?> - <?= html_escape($month_display) ?></h1>
		<p class="text-muted mb-0"><?= t('salary_payment') ?></p>
	</div>
	<a href="<?= base_url('salaries?month=' . rawurlencode($month_display)) ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="row g-3 align-items-end">
			<div class="col-md-4">
				<label class="form-label" for="salaryPayMonth"><?= t('month') ?></label>
				<input type="text" id="salaryPayMonth" class="form-control shamsi-month" placeholder="1403/01" value="<?= html_escape($month_display) ?>">
			</div>
			<div class="col-md-4">
				<a href="<?= base_url('salaries?month=' . rawurlencode($month_display)) ?>" class="btn btn-outline-secondary"><?= t('View All Salaries') ?></a>
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
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('leave_days') ?></span><strong><?= format_number($calculation['approved_leaves'], 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('days_in_month') ?></span><strong><?= format_number($calculation['days_in_month'] ?? 0, 0) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('daily_rate') ?></span><strong><?= format_number($calculation['daily_rate'] ?? 0, 2) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 gap-3"><span class="text-muted"><?= t('deduction') ?></span><strong><?= format_number($calculation['deduction'], 2) ?></strong></div>
					<div class="d-flex justify-content-between align-items-center border rounded p-3 gap-3"><span class="text-muted"><?= t('final_salary') ?></span><strong><?= format_number($calculation['final_salary'], 2) ?></strong></div>
				</div>
				<p class="text-muted small mt-2 mb-0"><?= t('suggested_salary_note') ?></p>
				<?php if (!empty($calculation['leave_dates'])) : ?>
					<div class="alert alert-info mt-3 mb-0">
						<strong><?= t('leave_impact') ?>:</strong>
						<div class="mt-1 small">
							<?= html_escape(implode(', ', array_map('to_shamsi', $calculation['leave_dates']))) ?>
						</div>
					</div>
				<?php endif; ?>
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
					<h2 class="h5 mb-0"><?= t('salary_history_payments') ?></h2>
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
									<td><?= html_escape(to_shamsi($payment['payment_date'])) ?></td>
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
				<?php if ($is_settled) : ?>
					<div class="alert alert-success d-flex justify-content-between align-items-center flex-wrap gap-2">
						<span><?= t('salary_settled_badge') ?></span>
						<?= form_open('salaries/reopen/' . (int) $staff['id'], 'class="m-0"') ?>
							<input type="hidden" name="month" value="<?= html_escape($month_display) ?>">
							<button type="submit" class="btn btn-sm btn-outline-dark"><?= t('reopen_salary') ?></button>
						<?= form_close() ?>
					</div>
				<?php elseif ($is_paid) : ?>
					<div class="alert alert-success" id="salaryPaidMessage"><?= t('salary_fully_paid') ?></div>
				<?php endif; ?>

				<h2 class="h5 mb-2"><?= t('record_payment') ?></h2>
				<p class="text-muted small"><?= t('salary_flexible_hint') ?></p>
				<?= form_open('salaries/store-payment') ?>
					<input type="hidden" name="staff_id" value="<?= (int) $staff['id'] ?>">
					<input type="hidden" name="month" id="paymentMonthInput" value="<?= html_escape($month_display) ?>">
					<div class="row g-3">
						<div class="col-md-4">
							<label class="form-label"><?= t('Amount') ?></label>
							<input type="number" step="0.01" min="0.01" name="amount" id="paymentAmountInput" class="form-control" value="<?= $remaining_amount > 0 ? html_escape(number_format($remaining_amount, 2, '.', '')) : '' ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= t('Payment Date') ?></label>
							<input type="text" name="payment_date" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape(shamsi_today()) ?>">
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

				<?php if (!$is_settled) : ?>
					<hr>
					<?= form_open('salaries/settle/' . (int) $staff['id'], 'class="d-flex align-items-center flex-wrap gap-2 mb-0"') ?>
						<input type="hidden" name="month" value="<?= html_escape($month_display) ?>">
						<button type="submit" class="btn btn-outline-secondary btn-sm"><?= t('mark_salary_settled') ?></button>
						<span class="text-muted small"><?= t('mark_salary_settled_hint') ?></span>
					<?= form_close() ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	// Changing the month reloads the page so the server renders the correct
	// calculation, payment history, and settle/paid state for that month.
	const monthInput = document.getElementById('salaryPayMonth');
	if (!monthInput) {
		return;
	}

	const base = <?= json_encode(base_url('salaries/pay/' . (int) $staff['id'])) ?>;

	monthInput.addEventListener('change', function () {
		const value = monthInput.value.trim();
		if (value) {
			window.location = base + '?month=' + encodeURIComponent(value);
		}
	});
})();
</script>
