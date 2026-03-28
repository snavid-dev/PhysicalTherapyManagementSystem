<?php
$filters = isset($filters) && is_array($filters) ? $filters : array();
$summary = isset($summary) && is_array($summary) ? $summary : array();
$turns = isset($turns) && is_array($turns) ? $turns : array();
$sections = isset($sections) && is_array($sections) ? $sections : array();
$debts_by_turn = isset($summary['debts_by_turn']) && is_array($summary['debts_by_turn']) ? $summary['debts_by_turn'] : array();
$income_by_section = isset($summary['income_by_section']) && is_array($summary['income_by_section']) ? $summary['income_by_section'] : array();

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

$date_range_label = $date_from === $date_to ? $date_from : ($date_from . ' - ' . $date_to);
$income_total = 0.00;
foreach ($income_by_section as $section_income) {
	$income_total += (float) ($section_income['total_cash'] ?? 0);
}
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('daily_register') . ' - ' . $date_range_label) ?></title>
	<style>
		body {
			font-family: 'Wazir', 'Tahoma', sans-serif;
			font-size: 11px;
			direction: <?= is_rtl_locale() ? 'rtl' : 'ltr' ?>;
			margin: 10mm;
			color: #111827;
		}
		.clinic-header {
			text-align: center;
			margin-bottom: 8px;
			border-bottom: 2px solid #000;
			padding-bottom: 6px;
		}
		.clinic-header h2 { margin: 0; font-size: 14px; }
		.clinic-header p { margin: 2px 0; font-size: 10px; }
		.filter-summary {
			font-size: 10px;
			margin-bottom: 6px;
			color: #555;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			font-size: 10px;
		}
		th, td {
			border: 1px solid #333;
			padding: 3px 5px;
			text-align: right;
			vertical-align: top;
		}
		th {
			background: #f0f0f0;
			font-weight: bold;
		}
		tfoot td {
			font-weight: bold;
			background: #f9f9f9;
		}
		.tfoot-label {
			display: block;
			font-size: 9px;
			color: #666;
			margin-bottom: 2px;
		}
		.summary-section {
			margin-top: 10px;
			display: flex;
			gap: 20px;
			flex-wrap: wrap;
		}
		.summary-box {
			border: 1px solid #ccc;
			padding: 6px 10px;
			font-size: 10px;
			min-width: 120px;
		}
		.summary-box strong {
			display: block;
			font-size: 11px;
		}
		.debt-danger {
			color: #b91c1c;
			font-weight: bold;
		}
		.debt-warning {
			color: #c2410c;
			font-weight: bold;
		}
		@media print {
			.no-print { display: none; }
		}
	</style>
</head>
<body>
	<div class="no-print" style="padding:10px">
		<button onclick="window.print()"><?= html_escape(t('dt_print')) ?></button>
		<button onclick="window.close()"><?= html_escape(t('Close')) ?></button>
	</div>

	<div class="clinic-header">
		<h2><?= html_escape(t('clinic_name_print')) ?></h2>
		<p><?= html_escape(t('daily_register')) ?></p>
		<p><?= html_escape(t('register_date_range')) ?>: <?= html_escape($date_range_label) ?></p>
	</div>

	<div class="filter-summary">
		<?= html_escape(t('section')) ?>: <?= html_escape($selected_section_name) ?> |
		<?= html_escape(t('Gender')) ?>: <?= html_escape($selected_gender_label) ?>
	</div>

	<table>
		<thead>
			<tr>
				<th>#</th>
				<th><?= t('Date') ?></th>
				<th><?= t('Patient') ?></th>
				<th><?= t('reference_doctor') ?></th>
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
				$open_debt = isset($debts_by_turn[$turn_id]) ? (float) $debts_by_turn[$turn_id] : NULL;
				$calculated_prepaid_debt = max(0, $fee - $wallet_used);
				$debt_value = $open_debt;
				$debt_class = 'debt-danger';

				if ($debt_value === NULL) {
					if ($payment_type === 'deferred' && $fee > 0) {
						$debt_value = $fee;
						$debt_class = 'debt-danger';
					} elseif ($payment_type === 'prepaid' && $calculated_prepaid_debt > 0) {
						$debt_value = $calculated_prepaid_debt;
						$debt_class = 'debt-warning';
					}
				} elseif ($payment_type === 'prepaid') {
					$debt_class = 'debt-warning';
				}
				?>
				<tr>
					<td><?= !empty($turn['turn_number']) ? format_number($turn['turn_number']) : '-' ?></td>
					<td><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
					<td><?= html_escape($turn['patient_name']) ?></td>
					<td><?= !empty($turn['reference_doctor_name']) ? html_escape($turn['reference_doctor_name']) : '-' ?></td>
					<td><?= html_escape(t(ucfirst(strtolower((string) ($turn['gender'] ?? ''))))) ?></td>
					<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '-' ?></td>
					<td><?= !empty($turn['staff_name']) ? html_escape($turn['staff_name']) : '-' ?></td>
					<td><?= format_amount($fee) ?></td>
					<td><?= (float) ($turn['discount_amount'] ?? 0) > 0 ? format_amount($turn['discount_amount']) : '-' ?></td>
					<td><?= html_escape(t($payment_type)) ?></td>
					<td><?= format_amount($turn['cash_collected'] ?? 0) ?></td>
					<td><?= $wallet_used > 0 ? format_amount($wallet_used) : '-' ?></td>
					<td>
						<?php if ($debt_value !== NULL && (float) $debt_value > 0) : ?>
							<span class="<?= $debt_class ?>"><?= format_amount($debt_value) ?></span>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
					<td><?= trim((string) ($turn['notes'] ?? '')) !== '' ? html_escape($turn['notes']) : '-' ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="14"><?= t('No turns in this range.') ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"><?= t('Total:') ?></td>
				<td><span class="tfoot-label"><?= t('fee') ?></span><?= format_amount($summary['total_fees'] ?? 0) ?></td>
				<td><span class="tfoot-label"><?= t('discount') ?></span><?= format_amount($summary['total_discounts'] ?? 0) ?></td>
				<td></td>
				<td><span class="tfoot-label"><?= t('cash_paid') ?></span><?= format_amount($summary['total_cash'] ?? 0) ?></td>
				<td><span class="tfoot-label"><?= t('wallet_used') ?></span><?= format_amount($summary['total_wallet_used'] ?? 0) ?></td>
				<td><span class="tfoot-label"><?= t('debt_amount') ?></span><?= format_amount($summary['total_debts'] ?? 0) ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>

	<div class="summary-section">
		<div class="summary-box">
			<strong><?= html_escape(t('total_turns_count')) ?></strong>
			<?= format_number($summary['total_turns'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_fees')) ?></strong>
			<?= format_amount($summary['total_fees'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_cash_collected')) ?></strong>
			<?= format_amount($summary['total_cash'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_wallet_topups')) ?></strong>
			<?= format_amount($summary['total_wallet_topups'] ?? 0) ?>
			<div><?= html_escape(t('Turns')) ?>: <?= format_amount($summary['total_turn_topups'] ?? 0) ?></div>
			<div><?= html_escape(t('Patients')) ?>: <?= format_amount($summary['total_manual_wallet_topups'] ?? 0) ?></div>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_direct_payments')) ?></strong>
			<?= format_amount($summary['total_direct_payments'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_patient_income')) ?></strong>
			<?= format_amount($summary['total_patient_income'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_debts_created')) ?></strong>
			<?= format_amount($summary['total_debts'] ?? 0) ?>
		</div>
	</div>

	<table style="margin-top:8px; width:auto">
		<thead>
			<tr>
				<th><?= t('section') ?></th>
				<th><?= t('cash_paid') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($income_by_section) : ?>
			<?php foreach ($income_by_section as $section_income) : ?>
				<tr>
					<td><?= !empty($section_income['section_name']) ? html_escape(t($section_income['section_name'])) : t('section_na') ?></td>
					<td><?= format_amount($section_income['total_cash'] ?? 0) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="2"><?= t('No data available.') ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td><?= t('Total:') ?></td>
				<td><?= format_amount($income_total) ?></td>
			</tr>
		</tfoot>
	</table>

	<script>
		window.onload = function() {
			setTimeout(function() { window.print(); }, 500);
		};
	</script>
</body>
</html>
