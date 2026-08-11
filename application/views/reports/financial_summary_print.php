<?php
$sections = isset($sections) && is_array($sections) ? $sections : array();
$safe_summary = isset($safe_summary) && is_array($safe_summary) ? $safe_summary : array();

$total_income = 0.00;
$total_patients = 0;
foreach ($sections as $section) {
	$total_income += (float) ($section['total_income'] ?? 0);
	$total_patients += (int) ($section['patient_count'] ?? 0);
}

$date_range_label = $from === $to ? $from : ($from . ' - ' . $to);
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('financial_summary_report') . ' - ' . $date_range_label) ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
		.summary-section {
			margin: 10px 0;
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
		@media print {
			.no-print { display: none; }
		}
		.btn-icon {
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
		}
	</style>
</head>
<body>
	<div class="no-print" style="padding:10px">
		<button class="btn-icon" onclick="window.print()"><i class="bi bi-printer" aria-hidden="true"></i> <?= html_escape(t('dt_print')) ?></button>
		<button class="btn-icon" onclick="window.close()"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= html_escape(t('Close')) ?></button>
	</div>

	<div class="clinic-header">
		<h2><?= html_escape(t('clinic_name_print')) ?></h2>
		<p><?= html_escape(t('financial_summary_report')) ?></p>
		<p><?= html_escape(t('register_date_range')) ?>: <?= html_escape($date_range_label) ?></p>
	</div>

	<div class="summary-section">
		<div class="summary-box">
			<strong><?= html_escape(t('safe_balance_before')) ?></strong>
			<?= format_amount($safe_summary['opening_balance'] ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_income')) ?></strong>
			<?= format_amount($total_income) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('total_expenses')) ?></strong>
			<?= format_amount($expenses_total ?? 0) ?>
		</div>
		<div class="summary-box">
			<strong><?= html_escape(t('safe_balance_after')) ?></strong>
			<?= format_amount($safe_summary['closing_balance'] ?? 0) ?>
		</div>
	</div>

	<table>
		<thead>
			<tr>
				<th><?= t('section') ?></th>
				<th><?= t('patient_count') ?></th>
				<th><?= t('total_income') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($sections) : ?>
			<?php foreach ($sections as $section) : ?>
				<tr>
					<td><?= !empty($section['section_name']) ? html_escape(t($section['section_name'])) : t('section_na') ?></td>
					<td><?= format_number($section['patient_count'] ?? 0) ?></td>
					<td><?= format_amount($section['total_income'] ?? 0) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="3"><?= t('No data available.') ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td><?= t('Total:') ?></td>
				<td><?= format_number($total_patients) ?></td>
				<td><?= format_amount($total_income) ?></td>
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
