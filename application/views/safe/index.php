<?php
$manage_safe = $this->auth->has_permission('manage_safe');
$balance_class = 'safe-balance-card';
$balance_value_class = 'text-body';

if ((float) $current_balance > 0) {
	$balance_class .= ' safe-balance-card--positive';
	$balance_value_class = 'text-success';
} elseif ((float) $current_balance < 0) {
	$balance_class .= ' safe-balance-card--negative';
	$balance_value_class = 'text-danger';
}

$source_labels = array(
	'turn_cash' => t('source_turn_cash'),
	'wallet_topup' => t('source_wallet_topup'),
	'patient_payment' => t('source_patient_payment'),
	'other_income' => t('source_other_income'),
	'expense' => t('source_expense'),
	'salary_payment' => t('source_salary'),
	'wallet_refund' => t('source_wallet_refund'),
	'adjustment' => t('source_adjustment'),
);

$type_badges = array(
	'in' => array('label' => 'IN', 'class' => 'text-bg-success'),
	'out' => array('label' => 'OUT', 'class' => 'text-bg-danger'),
	'adjustment' => array('label' => 'ADJ', 'class' => 'text-bg-secondary'),
);

$entry_links = static function ($entry) {
	$links = array();

	if ($entry['reference_table'] === 'turns' && !empty($entry['reference_id'])) {
		$links[] = array(
			'label' => t('Open Turn'),
			'url' => base_url('turns/' . $entry['reference_id'] . '/edit'),
		);

		if (!empty($entry['turn_patient_id'])) {
			$links[] = array(
				'label' => t('Open Patient'),
				'url' => base_url('patients/' . $entry['turn_patient_id']),
			);
		}
	}

	if ($entry['reference_table'] === 'payments' && !empty($entry['reference_id'])) {
		$links[] = array(
			'label' => t('Open Payment'),
			'url' => base_url('payments/' . $entry['reference_id']),
		);

		if (!empty($entry['payment_patient_id'])) {
			$links[] = array(
				'label' => t('Open Patient'),
				'url' => base_url('patients/' . $entry['payment_patient_id']),
			);
		}
	}

	if ($entry['reference_table'] === 'patient_wallet_transactions' && !empty($entry['wallet_patient_id'])) {
		$links[] = array(
			'label' => t('Open Patient'),
			'url' => base_url('patients/' . $entry['wallet_patient_id']),
		);
	}

	if ($entry['reference_table'] === 'expenses' && !empty($entry['reference_id'])) {
		$links[] = array(
			'label' => t('Open Expense'),
			'url' => base_url('expenses/edit/' . $entry['reference_id']),
		);
	}

	if ($entry['reference_table'] === 'staff_salary_payments' && !empty($entry['salary_staff_id'])) {
		$url = 'salaries/pay/' . $entry['salary_staff_id'];

		if (!empty($entry['salary_month'])) {
			$url .= '?month=' . rawurlencode(gregorian_month_to_shamsi($entry['salary_month']));
		}

		$links[] = array(
			'label' => t('Open Salary'),
			'url' => base_url($url),
		);
	}

	return $links;
};

$entry_owner = static function ($entry) {
	if (!empty($entry['payment_patient_name'])) {
		return array(
			'label' => trim((string) $entry['payment_patient_name']),
			'url' => !empty($entry['payment_patient_id']) ? base_url('patients/' . $entry['payment_patient_id']) : NULL,
		);
	}

	if (!empty($entry['wallet_patient_name'])) {
		return array(
			'label' => trim((string) $entry['wallet_patient_name']),
			'url' => !empty($entry['wallet_patient_id']) ? base_url('patients/' . $entry['wallet_patient_id']) : NULL,
		);
	}

	if (!empty($entry['turn_patient_name'])) {
		return array(
			'label' => trim((string) $entry['turn_patient_name']),
			'url' => !empty($entry['turn_patient_id']) ? base_url('patients/' . $entry['turn_patient_id']) : NULL,
		);
	}

	if (!empty($entry['salary_staff_name'])) {
		return array(
			'label' => trim((string) $entry['salary_staff_name']),
			'url' => NULL,
		);
	}

	return NULL;
};

$reference_label = static function ($entry) {
	if (empty($entry['reference_id']) || empty($entry['reference_table'])) {
		return '&mdash;';
	}

	return safe_reference_label($entry['reference_table'], $entry['reference_id']);
};
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('safe') ?></h1>
		<p class="text-muted mb-0"><?= t('Manual other income and cash movement ledger.') ?></p>
	</div>
	<div class="d-flex gap-2 flex-wrap">
		<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addIncomeModal"><?= t('add_income') ?></button>
		<?php if ($manage_safe) : ?>
			<button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#adjustBalanceModal"><?= t('adjust_balance') ?></button>
		<?php endif; ?>
	</div>
</div>

<div class="card <?= $balance_class ?> mb-4">
	<div class="card-body">
		<div class="row g-3 align-items-end">
			<div class="col-lg-8">
				<div class="stat-label"><?= t('safe_balance') ?></div>
				<div class="safe-balance-value <?= $balance_value_class ?>"><?= format_number($current_balance, 2) ?></div>
			</div>
			<div class="col-lg-4">
				<div class="safe-summary-label"><?= t('last_updated') ?></div>
				<div class="fw-semibold"><?= !empty($latest_transaction['created_at']) ? html_escape(to_shamsi($latest_transaction['created_at'], 'Y/m/d H:i')) : t('No data available.') ?></div>
			</div>
		</div>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-12 col-lg-4">
		<div class="card h-100 safe-summary-card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Today') ?></h2>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('total_in') ?></span><span class="safe-summary-value safe-summary-value--in"><?= format_number($today_summary['total_in'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('total_out') ?></span><span class="safe-summary-value safe-summary-value--out"><?= format_number($today_summary['total_out'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('net') ?></span><span class="safe-summary-value"><?= format_number($today_summary['net'], 2) ?></span></div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card h-100 safe-summary-card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('This Month') ?></h2>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('total_in') ?></span><span class="safe-summary-value safe-summary-value--in"><?= format_number($month_summary['total_in'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('total_out') ?></span><span class="safe-summary-value safe-summary-value--out"><?= format_number($month_summary['total_out'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('net') ?></span><span class="safe-summary-value"><?= format_number($month_summary['net'], 2) ?></span></div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card h-100 safe-summary-card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('safe_balance') ?></h2>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('opening_balance') ?></span><span class="safe-summary-value"><?= format_number($month_summary['opening_balance'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('closing_balance') ?></span><span class="safe-summary-value"><?= format_number($month_summary['closing_balance'], 2) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('balance_after') ?></span><span class="safe-summary-value"><?= format_number($current_balance, 2) ?></span></div>
			</div>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form method="get" action="<?= base_url('safe') ?>">
			<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label"><?= t('Type') ?></label>
					<select name="type" class="form-select">
						<option value=""><?= t('All') ?></option>
						<option value="in" <?= $filters['type'] === 'in' ? 'selected' : '' ?>>IN</option>
						<option value="out" <?= $filters['type'] === 'out' ? 'selected' : '' ?>>OUT</option>
						<option value="adjustment" <?= $filters['type'] === 'adjustment' ? 'selected' : '' ?>>ADJ</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Source') ?></label>
					<select name="source" class="form-select">
						<option value=""><?= t('All') ?></option>
						<?php foreach ($source_labels as $source_key => $source_label) : ?>
							<option value="<?= html_escape($source_key) ?>" <?= $filters['source'] === $source_key ? 'selected' : '' ?>><?= html_escape($source_label) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label"><?= t('date_from') ?></label>
					<input type="text" name="date_from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($filters['date_from']) ?>">
				</div>
				<div class="col-md-2">
					<label class="form-label"><?= t('date_to') ?></label>
					<input type="text" name="date_to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($filters['date_to']) ?>">
				</div>
				<div class="col-md-2 d-flex align-items-end gap-2">
					<button type="submit" class="btn btn-dark w-100"><?= t('Apply') ?></button>
					<a href="<?= base_url('safe') ?>" class="btn btn-outline-secondary w-100"><?= t('Reset') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
			<h2 class="h5 mb-0"><?= t('Ledger') ?></h2>
			<div class="text-muted"><?= t('safe_balance') ?>: <strong><?= format_number($current_balance, 2) ?></strong></div>
		</div>
		<div class="table-responsive">
			<table class="table align-middle mb-0">
				<thead>
					<tr>
						<th><?= t('Date') ?></th>
						<th><?= t('Type') ?></th>
						<th><?= t('Source') ?></th>
						<th><?= t('Amount') ?></th>
						<th><?= t('balance_after') ?></th>
						<th><?= t('Notes') ?></th>
						<th><?= t('Reference') ?></th>
						<th><?= t('recorded_by') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($ledger) : ?>
						<?php foreach ($ledger as $entry) : ?>
							<?php $badge = $type_badges[$entry['type']] ?? $type_badges['adjustment']; ?>
							<tr>
								<td><?= html_escape(to_shamsi($entry['created_at'], 'Y/m/d H:i')) ?></td>
								<td><span class="badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span></td>
							<td><?= html_escape($source_labels[$entry['source']] ?? $entry['source']) ?></td>
							<td class="<?= $entry['type'] === 'in' ? 'text-success' : ($entry['type'] === 'out' ? 'text-danger' : '') ?>"><?= format_number($entry['amount'], 2) ?></td>
							<td><?= format_number($entry['balance_after'], 2) ?></td>
							<td class="safe-table-note">
								<div><?= $entry['note'] ? html_escape($entry['note']) : '&mdash;' ?></div>
								<?php $owner = $entry_owner($entry); ?>
								<?php if ($owner && $owner['label'] !== '') : ?>
									<div class="small text-muted mt-2">
										<?= t('Owner') ?>:
										<?php if (!empty($owner['url'])) : ?>
											<a href="<?= html_escape($owner['url']) ?>"><?= html_escape($owner['label']) ?></a>
										<?php else : ?>
											<?= html_escape($owner['label']) ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php $links = $entry_links($entry); ?>
								<?php if ($links) : ?>
									<div class="safe-entry-links mt-2 d-flex gap-2 flex-wrap">
										<?php foreach ($links as $link) : ?>
											<a href="<?= html_escape($link['url']) ?>" class="btn btn-sm btn-outline-secondary"><?= html_escape($link['label']) ?></a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</td>
							<td><?= $reference_label($entry) ?></td>
							<td><?= !empty($entry['created_by_name']) ? html_escape($entry['created_by_name']) : '&mdash;' ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="8" class="text-muted"><?= t('No safe transactions found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0"><?= t('add_income') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<div class="modal-body">
				<?= form_open('safe/add-income') ?>
					<div class="mb-3">
						<label class="form-label"><?= t('Amount') ?></label>
						<input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('Description') ?></label>
						<input type="text" name="note" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('income_date') ?></label>
						<input type="text" name="income_date" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape(shamsi_today()) ?>" required>
					</div>
					<div class="d-flex gap-2 justify-content-end">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('Close') ?></button>
						<button type="submit" class="btn btn-dark"><?= t('add_income') ?></button>
					</div>
				<?= form_close() ?>
			</div>
		</div>
	</div>
</div>

<?php if ($manage_safe) : ?>
	<div class="modal fade" id="adjustBalanceModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title h5 mb-0"><?= t('adjust_balance') ?></h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
				</div>
				<div class="modal-body">
					<?= form_open('safe/adjust') ?>
						<div class="mb-3">
							<label class="form-label"><?= t('safe_balance') ?></label>
							<input type="text" class="form-control" value="<?= html_escape(format_number($current_balance, 2)) ?>" readonly>
						</div>
						<div class="mb-3">
							<label class="form-label"><?= t('new_balance') ?></label>
							<input type="number" step="0.01" min="0" name="new_balance" class="form-control" value="<?= html_escape(number_format((float) $current_balance, 2, '.', '')) ?>" required>
						</div>
						<div class="mb-3">
							<label class="form-label"><?= t('adjustment_reason') ?></label>
							<textarea name="reason" class="form-control" rows="4" required></textarea>
						</div>
						<div class="alert alert-warning"><?= t('adjustment_warning') ?></div>
						<div class="d-flex gap-2 justify-content-end">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('Close') ?></button>
							<button type="submit" class="btn btn-dark"><?= t('adjust_balance') ?></button>
						</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
