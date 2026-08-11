<?php
$filters = isset($filters) && is_array($filters) ? $filters : array();
$summary = isset($summary) && is_array($summary) ? $summary : array();
$turns = isset($turns) && is_array($turns) ? $turns : array();
$sections = isset($sections) && is_array($sections) ? $sections : array();
$debts_by_turn = isset($summary['debts_by_turn']) && is_array($summary['debts_by_turn']) ? $summary['debts_by_turn'] : array();
$income_by_section = isset($summary['income_by_section']) && is_array($summary['income_by_section']) ? $summary['income_by_section'] : array();
$selected_section_ids = array_map('intval', (array) ($filters['section_ids'] ?? array()));

$selected_section_names = array();
foreach ($sections as $section) {
	if (in_array((int) ($section['id'] ?? 0), $selected_section_ids, TRUE)) {
		$selected_section_names[] = !empty($section['name']) ? t($section['name']) : t('section_na');
	}
}

$selected_section_name = $selected_section_names ? implode(', ', $selected_section_names) : t('all_sections');

$selected_gender_label = t('all_genders');
if (($filters['gender'] ?? '') === 'male') {
	$selected_gender_label = t('Male');
} elseif (($filters['gender'] ?? '') === 'female') {
	$selected_gender_label = t('Female');
}

$date_range_label = $date_from === $date_to ? $date_from : ($date_from . ' - ' . $date_to);
$income_total = 0.00;
foreach ($income_by_section as $section_income) {
	$income_total += (float) ($section_income['total_received'] ?? 0);
}
$income_total += (float) ($summary['total_manual_wallet_topups'] ?? 0);
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('daily_register') . ' - ' . $date_range_label) ?></title>
	<?= print_report_styles() ?>
</head>
<body>
	<?= print_report_toolbar() ?>
	<?= print_report_letterhead(t('daily_register'), $date_range_label, array(
		array('label' => t('section'), 'value' => $selected_section_name),
		array('label' => t('Gender'), 'value' => $selected_gender_label),
	)) ?>

	<div class="stat-strip">
		<div class="stat">
			<p class="stat-label"><?= t('total_turns_count') ?></p>
			<div class="stat-value"><?= format_number($summary['total_turns'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_fees') ?></p>
			<div class="stat-value"><?= format_amount($summary['total_fees'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_cash_collected') ?></p>
			<div class="stat-value accent"><?= format_amount($summary['total_cash'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_wallet_topups') ?></p>
			<div class="stat-value accent"><?= format_amount($summary['total_wallet_topups'] ?? 0) ?></div>
			<p class="stat-hint"><?= t('Turns') ?>: <?= format_amount($summary['total_turn_topups'] ?? 0) ?> &middot; <?= t('Patients') ?>: <?= format_amount($summary['total_manual_wallet_topups'] ?? 0) ?></p>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_debt_payments') ?></p>
			<div class="stat-value accent"><?= format_amount($summary['total_debt_payments'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_refunds') ?></p>
			<div class="stat-value danger">-<?= format_amount($summary['total_refunds'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_patient_income') ?></p>
			<div class="stat-value accent"><?= format_amount($summary['total_patient_income'] ?? 0) ?></div>
			<p class="stat-hint"><?= t('net_income_formula_hint') ?></p>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_debts_created') ?></p>
			<div class="stat-value <?= (float) ($summary['total_debts'] ?? 0) > 0 ? 'danger' : '' ?>"><?= format_amount($summary['total_debts'] ?? 0) ?></div>
		</div>
	</div>

	<table class="report-table">
		<thead>
			<tr>
				<th>#</th>
				<th><?= t('Date') ?></th>
				<th><?= t('Patient') ?></th>
				<th><?= t('reference_doctor') ?></th>
				<th><?= t('Gender') ?></th>
				<th><?= t('section') ?></th>
				<th><?= t('Staff') ?></th>
				<th class="num"><?= t('fee') ?></th>
				<th class="num"><?= t('discount') ?></th>
				<th><?= t('payment_type') ?></th>
				<th class="num"><?= t('cash_paid') ?></th>
				<th class="num"><?= t('top_up_amount') ?></th>
				<th class="num"><?= t('wallet_used') ?></th>
				<th class="num"><?= t('received_amount') ?></th>
				<th class="num"><?= t('debt_amount') ?></th>
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
				$topup_amount = (float) ($turn['topup_amount'] ?? 0);
				$wallet_used = (float) ($turn['wallet_deducted'] ?? 0);
				$row_received_total = (float) ($turn['cash_collected'] ?? 0) + $topup_amount;
				$open_debt = isset($debts_by_turn[$turn_id]) ? (float) $debts_by_turn[$turn_id] : NULL;
				$calculated_prepaid_debt = max(0, $fee - $wallet_used);
				$debt_value = $open_debt;
				$debt_class = 'text-danger';

				if ($debt_value === NULL) {
					if ($payment_type === 'deferred' && $fee > 0) {
						$debt_value = $fee;
						$debt_class = 'text-danger';
					} elseif ($payment_type === 'prepaid' && $calculated_prepaid_debt > 0) {
						$debt_value = $calculated_prepaid_debt;
						$debt_class = 'text-warn';
					}
				} elseif ($payment_type === 'prepaid') {
					$debt_class = 'text-warn';
				}
				?>
				<tr>
					<td><?= !empty($turn['turn_number']) ? format_number($turn['turn_number']) : '&mdash;' ?></td>
					<td><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
					<td><?= html_escape($turn['patient_name']) ?></td>
					<td><?= !empty($turn['reference_doctor_name']) ? html_escape($turn['reference_doctor_name']) : '&mdash;' ?></td>
					<td><?= html_escape(t(ucfirst(strtolower((string) ($turn['gender'] ?? ''))))) ?></td>
					<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '&mdash;' ?></td>
					<td><?= !empty($turn['staff_name']) ? html_escape($turn['staff_name']) : '&mdash;' ?></td>
					<td class="num"><?= format_amount($fee) ?></td>
					<td class="num"><?= (float) ($turn['discount_amount'] ?? 0) > 0 ? format_amount($turn['discount_amount']) : '&mdash;' ?></td>
					<td><?= html_escape(t($payment_type)) ?></td>
					<td class="num"><?= format_amount($turn['cash_collected'] ?? 0) ?></td>
					<td class="num">
						<?php if ($topup_amount > 0) : ?>
							<?= format_amount($topup_amount) ?>
						<?php elseif ($wallet_used > 0) : ?>
							<?= html_escape(t('No')) ?>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
					</td>
					<td class="num"><?= $wallet_used > 0 ? format_amount($wallet_used) : '&mdash;' ?></td>
					<td class="num"><?= format_amount($row_received_total) ?></td>
					<td class="num">
						<?php if ($debt_value !== NULL && (float) $debt_value > 0) : ?>
							<span class="<?= $debt_class ?>"><?= format_amount($debt_value) ?></span>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
					</td>
					<td><?= trim((string) ($turn['notes'] ?? '')) !== '' ? html_escape($turn['notes']) : '&mdash;' ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="16"><?= t('No turns in this range.') ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"><?= t('Total:') ?></td>
				<td class="num"><span class="foot-label"><?= t('fee') ?></span><?= format_amount($summary['total_fees'] ?? 0) ?></td>
				<td class="num"><span class="foot-label"><?= t('discount') ?></span><?= format_amount($summary['total_discounts'] ?? 0) ?></td>
				<td></td>
				<td class="num"><span class="foot-label"><?= t('cash_paid') ?></span><?= format_amount($summary['total_cash'] ?? 0) ?></td>
				<td class="num"><span class="foot-label"><?= t('top_up_amount') ?></span><?= format_amount($summary['total_turn_topups'] ?? 0) ?></td>
				<td class="num"><span class="foot-label"><?= t('wallet_used') ?></span><?= format_amount($summary['total_wallet_used'] ?? 0) ?></td>
				<td class="num"><span class="foot-label"><?= t('received_amount') ?></span><?= format_amount(((float) ($summary['total_cash'] ?? 0) + (float) ($summary['total_turn_topups'] ?? 0))) ?></td>
				<td class="num"><span class="foot-label"><?= t('debt_amount') ?></span><?= format_amount($summary['total_debts'] ?? 0) ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>

	<h2 class="section-title"><?= t('income_by_section') ?></h2>
	<table class="report-table" style="max-width: 360px;">
		<thead>
			<tr>
				<th><?= t('section') ?></th>
				<th class="num"><?= t('total_patient_income') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($income_by_section) : ?>
			<?php foreach ($income_by_section as $section_income) : ?>
				<tr>
					<td><?= !empty($section_income['section_name']) ? html_escape(t($section_income['section_name'])) : t('section_na') ?></td>
					<td class="num"><?= format_amount($section_income['total_received'] ?? 0) ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if ((float) ($summary['total_manual_wallet_topups'] ?? 0) > 0 && empty($selected_section_ids)) : ?>
				<tr>
					<td><?= t('Patients') ?> / <?= t('total_wallet_topups') ?></td>
					<td class="num"><?= format_amount($summary['total_manual_wallet_topups'] ?? 0) ?></td>
				</tr>
			<?php endif; ?>
		<?php else : ?>
			<tr>
				<td colspan="2"><?= t('No data available.') ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td><?= t('Total:') ?></td>
				<td class="num"><?= format_amount($income_total) ?></td>
			</tr>
		</tfoot>
	</table>

	<?= print_report_footer() ?>
	<?= print_report_autoprint_script() ?>
</body>
</html>
