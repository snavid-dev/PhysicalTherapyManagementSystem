<?php
$doctors = isset($doctors) && is_array($doctors) ? $doctors : array();

$total_referred = 0;
foreach ($doctors as $doctor) {
	$total_referred += (int) ($doctor['referred_count'] ?? 0);
}

$range_label = $from === $to ? $from : ($from . ' - ' . $to);
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('doctor_referral_report') . ' - ' . $range_label) ?></title>
	<?= print_report_styles() ?>
</head>
<body>
	<?= print_report_toolbar() ?>
	<?= print_report_letterhead(t('doctor_referral_report'), $range_label) ?>

	<div class="stat-strip">
		<div class="stat">
			<p class="stat-label"><?= t('reference_doctor') ?></p>
			<div class="stat-value"><?= format_number(count($doctors)) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('patients_referred') ?></p>
			<div class="stat-value accent"><?= format_number($total_referred) ?></div>
		</div>
	</div>

	<table class="report-table">
		<thead>
			<tr>
				<th><?= t('reference_doctor') ?></th>
				<th><?= t('specialty') ?></th>
				<th class="num"><?= t('patients_referred') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($doctors) : ?>
			<?php foreach ($doctors as $doctor) : ?>
				<tr>
					<td><?= html_escape(trim($doctor['first_name'] . ' ' . ($doctor['last_name'] ?? ''))) ?></td>
					<td><?= !empty($doctor['specialty']) ? html_escape($doctor['specialty']) : '&mdash;' ?></td>
					<td class="num"><span class="badge-pill"><?= format_number($doctor['referred_count'] ?? 0) ?></span></td>
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
				<td class="num"><?= format_number($total_referred) ?></td>
			</tr>
		</tfoot>
	</table>

	<?= print_report_footer() ?>
	<?= print_report_autoprint_script() ?>
</body>
</html>
