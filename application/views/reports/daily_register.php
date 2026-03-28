<?php
$filters = isset($filters) && is_array($filters) ? $filters : array();
$summary = isset($summary) && is_array($summary) ? $summary : array();
$turns = isset($turns) && is_array($turns) ? $turns : array();
$sections = isset($sections) && is_array($sections) ? $sections : array();
$debts_by_turn = isset($summary['debts_by_turn']) && is_array($summary['debts_by_turn']) ? $summary['debts_by_turn'] : array();
$income_by_section = isset($summary['income_by_section']) && is_array($summary['income_by_section']) ? $summary['income_by_section'] : array();

$payment_badges = array(
	'prepaid' => 'bg-primary-subtle text-primary',
	'cash' => 'bg-success-subtle text-success',
	'deferred' => 'bg-danger-subtle text-danger',
	'free' => 'bg-secondary-subtle text-secondary',
);

$truncate_note = static function ($value, $limit) {
	$value = trim((string) $value);
	if ($value === '') {
		return '';
	}

	if (function_exists('mb_strlen') && function_exists('mb_substr')) {
		return mb_strlen($value) > $limit ? (mb_substr($value, 0, $limit - 1) . '...') : $value;
	}

	return strlen($value) > $limit ? (substr($value, 0, $limit - 1) . '...') : $value;
};

$selected_section_name = t('all_sections');
foreach ($sections as $section) {
	if ((int) ($section['id'] ?? 0) === (int) ($filters['section_id'] ?? 0)) {
		$selected_section_name = !empty($section['name']) ? t($section['name']) : t('section_na');
		break;
	}
}

$selected_gender_label = t('all_genders');
if (($filters['gender'] ?? '') === 'male') {
	$selected_gender_label = t('Male');
} elseif (($filters['gender'] ?? '') === 'female') {
	$selected_gender_label = t('Female');
}

$print_params = array(
	'date_from' => $filters['date_from'] ?? $date_from,
	'date_to' => $filters['date_to'] ?? $date_to,
);
if (!empty($filters['section_id'])) {
	$print_params['section_id'] = (int) $filters['section_id'];
}
if (!empty($filters['gender'])) {
	$print_params['gender'] = $filters['gender'];
}
$print_url = base_url('reports/daily-register/print?' . http_build_query($print_params));

$income_total = 0.00;
foreach ($income_by_section as $section_income) {
	$income_total += (float) ($section_income['total_cash'] ?? 0);
}
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('daily_register') ?></h1>
		<p class="text-muted mb-0"><?= t('register_title') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-secondary"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/daily-register', array('method' => 'get', 'class' => 'row g-3 align-items-end daily-register-filter-bar')) ?>
			<div class="col-xl-2 col-md-4">
				<label class="form-label"><?= t('Date From') ?></label>
				<input type="text" name="date_from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($date_from) ?>">
			</div>
			<div class="col-xl-2 col-md-4">
				<label class="form-label"><?= t('Date To') ?></label>
				<input type="text" name="date_to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($date_to) ?>">
			</div>
			<div class="col-xl-3 col-md-4">
				<label class="form-label"><?= t('section') ?></label>
				<select name="section_id" class="form-select" onchange="this.form.submit()">
					<option value=""><?= t('all_sections') ?></option>
					<?php foreach ($sections as $section) : ?>
						<option value="<?= (int) $section['id'] ?>"<?= (int) ($filters['section_id'] ?? 0) === (int) $section['id'] ? ' selected' : '' ?>>
							<?= html_escape(t($section['name'])) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-xl-2 col-md-4">
				<label class="form-label"><?= t('Gender') ?></label>
				<select name="gender" class="form-select" onchange="this.form.submit()">
					<option value=""><?= t('all_genders') ?></option>
					<option value="male"<?= ($filters['gender'] ?? '') === 'male' ? ' selected' : '' ?>><?= t('Male') ?></option>
					<option value="female"<?= ($filters['gender'] ?? '') === 'female' ? ' selected' : '' ?>><?= t('Female') ?></option>
				</select>
			</div>
			<div class="col-xl-3 col-md-8">
				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-dark flex-grow-1"><?= t('Search') ?></button>
					<a href="<?= $print_url ?>" class="btn btn-outline-dark flex-grow-1" target="_blank" rel="noopener"><?= t('print_register') ?></a>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--info">
			<div class="card-body">
				<div class="stat-label"><?= t('total_turns_count') ?></div>
				<div class="stat-value"><?= format_number($summary['total_turns'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--muted">
			<div class="card-body">
				<div class="stat-label"><?= t('total_fees') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_fees'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--success">
			<div class="card-body">
				<div class="stat-label"><?= t('total_cash_collected') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_cash'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--success">
			<div class="card-body">
				<div class="stat-label"><?= t('total_wallet_topups') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_wallet_topups'] ?? 0) ?></div>
				<div class="small text-muted mt-2">
					<?= t('Turns') ?>: <?= format_amount($summary['total_turn_topups'] ?? 0) ?> |
					<?= t('Patients') ?>: <?= format_amount($summary['total_manual_wallet_topups'] ?? 0) ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--success">
			<div class="card-body">
				<div class="stat-label"><?= t('total_direct_payments') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_direct_payments'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--success">
			<div class="card-body">
				<div class="stat-label"><?= t('total_patient_income') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_patient_income'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card <?= (float) ($summary['total_debts'] ?? 0) > 0 ? 'daily-register-summary-card--danger' : 'daily-register-summary-card--muted' ?>">
			<div class="card-body">
				<div class="stat-label"><?= t('total_debts_created') ?></div>
				<div class="stat-value<?= (float) ($summary['total_debts'] ?? 0) > 0 ? '' : ' text-muted' ?>"><?= format_amount($summary['total_debts'] ?? 0) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl col-md-4 col-sm-6">
		<div class="card h-100 daily-register-summary-card daily-register-summary-card--warning">
			<div class="card-body">
				<div class="stat-label"><?= t('total_discounts') ?></div>
				<div class="stat-value"><?= format_amount($summary['total_discounts'] ?? 0) ?></div>
			</div>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
			<div>
				<h2 class="h5 mb-1"><?= t('income_by_section') ?></h2>
				<p class="text-muted mb-0"><?= t('register_date_range') ?>: <?= html_escape($date_from) ?> - <?= html_escape($date_to) ?></p>
			</div>
			<div class="text-muted small"><?= t('section') ?>: <?= html_escape($selected_section_name) ?> | <?= t('Gender') ?>: <?= html_escape($selected_gender_label) ?></div>
		</div>
		<div class="table-responsive">
			<table class="table table-sm align-middle mb-0">
				<thead>
					<tr>
						<th><?= t('section') ?></th>
						<th class="text-end"><?= t('cash_paid') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($income_by_section) : ?>
					<?php foreach ($income_by_section as $section_income) : ?>
						<tr>
							<td><?= !empty($section_income['section_name']) ? html_escape(t($section_income['section_name'])) : t('section_na') ?></td>
							<td class="text-end"><?= format_amount($section_income['total_cash'] ?? 0) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="2" class="text-center text-muted"><?= t('No data available.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="fw-semibold"><?= t('Total:') ?></td>
						<td class="text-end fw-semibold"><?= format_amount($income_total) ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle mb-0 dt-table" data-order-col="1" data-order-dir="asc" data-no-export="false" data-no-sort-cols="">
				<thead>
					<tr>
						<th>#</th>
						<th class="col-date"><?= t('Date') ?></th>
						<th><?= t('Patient') ?></th>
						<th><?= t('Gender') ?></th>
						<th><?= t('section') ?></th>
						<th><?= t('Staff') ?></th>
						<th><?= t('fee') ?></th>
						<th><?= t('discount') ?></th>
						<th><?= t('payment_type') ?></th>
						<th><?= t('cash_paid') ?></th>
						<th><?= t('wallet_used') ?></th>
						<th><?= t('debt_amount') ?></th>
						<th><?= t('Notes') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($turns) : ?>
					<?php foreach ($turns as $turn) : ?>
						<?php
						$turn_id = (int) ($turn['id'] ?? 0);
						$payment_type = (string) ($turn['payment_type'] ?? 'cash');
						$fee = (float) ($turn['fee'] ?? 0);
						$wallet_used = (float) ($turn['wallet_deducted'] ?? 0);
						$calculated_prepaid_debt = max(0, $fee - $wallet_used);
						$open_debt = isset($debts_by_turn[$turn_id]) ? (float) $debts_by_turn[$turn_id] : NULL;
						$debt_value = $open_debt;
						$debt_class = 'text-danger';

						if ($debt_value === NULL) {
							if ($payment_type === 'deferred' && $fee > 0) {
								$debt_value = $fee;
								$debt_class = 'text-danger';
							} elseif ($payment_type === 'prepaid' && $calculated_prepaid_debt > 0) {
								$debt_value = $calculated_prepaid_debt;
								$debt_class = 'text-warning';
							}
						} elseif ($payment_type === 'prepaid') {
							$debt_class = 'text-warning';
						}

						$note_value = trim((string) ($turn['notes'] ?? ''));
						$badge_class = $payment_badges[$payment_type] ?? $payment_badges['cash'];
						?>
						<tr>
							<td><?= !empty($turn['turn_number']) ? format_number($turn['turn_number']) : '&mdash;' ?></td>
							<td class="col-date"><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
							<td><?= html_escape($turn['patient_name']) ?></td>
							<td><?= html_escape(t(ucfirst(strtolower((string) ($turn['gender'] ?? ''))))) ?></td>
							<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '&mdash;' ?></td>
							<td><?= !empty($turn['staff_name']) ? html_escape($turn['staff_name']) : '&mdash;' ?></td>
							<td><?= format_amount($fee) ?></td>
							<td><?= (float) ($turn['discount_amount'] ?? 0) > 0 ? format_amount($turn['discount_amount']) : '&mdash;' ?></td>
							<td><span class="badge rounded-pill <?= $badge_class ?>"><?= html_escape(t($payment_type)) ?></span></td>
							<td><?= format_amount($turn['cash_collected'] ?? 0) ?></td>
							<td><?= $wallet_used > 0 ? format_amount($wallet_used) : '&mdash;' ?></td>
							<td>
								<?php if ($debt_value !== NULL && (float) $debt_value > 0) : ?>
									<span class="fw-semibold <?= $debt_class ?>"><?= format_amount($debt_value) ?></span>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
							<td>
								<?php if ($note_value !== '') : ?>
									<span class="d-inline-block daily-register-notes" title="<?= html_escape($note_value) ?>"><?= html_escape($truncate_note($note_value, 40)) ?></span>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="13" class="text-center text-muted"><?= t('No turns in this range.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" class="fw-semibold"><?= t('Total:') ?></td>
						<td class="fw-semibold">
							<span class="d-block small text-muted"><?= t('fee') ?></span>
							<?= format_amount($summary['total_fees'] ?? 0) ?>
						</td>
						<td class="fw-semibold">
							<span class="d-block small text-muted"><?= t('discount') ?></span>
							<?= format_amount($summary['total_discounts'] ?? 0) ?>
						</td>
						<td></td>
						<td class="fw-semibold">
							<span class="d-block small text-muted"><?= t('cash_paid') ?></span>
							<?= format_amount($summary['total_cash'] ?? 0) ?>
						</td>
						<td class="fw-semibold">
							<span class="d-block small text-muted"><?= t('wallet_used') ?></span>
							<?= format_amount($summary['total_wallet_used'] ?? 0) ?>
						</td>
						<td class="fw-semibold">
							<span class="d-block small text-muted"><?= t('debt_amount') ?></span>
							<?= format_amount($summary['total_debts'] ?? 0) ?>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
