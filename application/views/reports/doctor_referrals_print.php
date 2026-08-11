<?php
$doctors = isset($doctors) && is_array($doctors) ? $doctors : array();

$total_referred = 0;
foreach ($doctors as $doctor) {
	$total_referred += (int) ($doctor['referred_count'] ?? 0);
}

$date_range_label = $from === $to ? $from : ($from . ' - ' . $to);
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('doctor_referral_report') . ' - ' . $date_range_label) ?></title>
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
		<p><?= html_escape(t('doctor_referral_report')) ?></p>
		<p><?= html_escape(t('register_date_range')) ?>: <?= html_escape($date_range_label) ?></p>
	</div>

	<table>
		<thead>
			<tr>
				<th><?= t('reference_doctor') ?></th>
				<th><?= t('specialty') ?></th>
				<th><?= t('patients_referred') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($doctors) : ?>
			<?php foreach ($doctors as $doctor) : ?>
				<tr>
					<td><?= html_escape(trim($doctor['first_name'] . ' ' . ($doctor['last_name'] ?? ''))) ?></td>
					<td><?= !empty($doctor['specialty']) ? html_escape($doctor['specialty']) : '-' ?></td>
					<td><?= format_number($doctor['referred_count'] ?? 0) ?></td>
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
				<td colspan="2"><?= t('Total:') ?></td>
				<td><?= format_number($total_referred) ?></td>
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
