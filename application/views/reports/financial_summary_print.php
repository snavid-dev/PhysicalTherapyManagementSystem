<?php
$sections = isset($sections) && is_array($sections) ? $sections : array();
$safe_summary = isset($safe_summary) && is_array($safe_summary) ? $safe_summary : array();

$total_income = 0.00;
$total_patients = 0;
foreach ($sections as $section) {
	$total_income += (float) ($section['total_income'] ?? 0);
	$total_patients += (int) ($section['patient_count'] ?? 0);
}

$range_label = $from === $to ? $from : ($from . ' - ' . $to);
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('financial_summary_report') . ' - ' . $range_label) ?></title>
	<?= print_report_styles() ?>
</head>
<body>
	<?= print_report_toolbar() ?>
	<?= print_report_letterhead(t('financial_summary_report'), $range_label) ?>

	<div class="stat-strip">
		<div class="stat">
			<p class="stat-label"><?= t('safe_balance_before') ?></p>
			<div class="stat-value"><?= format_amount($safe_summary['opening_balance'] ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_income') ?></p>
			<div class="stat-value accent"><?= format_amount($total_income) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_expenses') ?></p>
			<div class="stat-value danger"><?= format_amount($expenses_total ?? 0) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('safe_balance_after') ?></p>
			<div class="stat-value accent"><?= format_amount($safe_summary['closing_balance'] ?? 0) ?></div>
		</div>
	</div>

	<h2 class="section-title"><?= t('income_by_section') ?></h2>
	<table class="report-table">
		<thead>
			<tr>
				<th><?= t('section') ?></th>
				<th class="num"><?= t('patient_count') ?></th>
				<th class="num"><?= t('total_income') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($sections) : ?>
			<?php foreach ($sections as $section) : ?>
				<tr>
					<td><?= !empty($section['section_name']) ? html_escape(t($section['section_name'])) : t('section_na') ?></td>
					<td class="num"><?= format_number($section['patient_count'] ?? 0) ?></td>
					<td class="num"><?= format_amount($section['total_income'] ?? 0) ?></td>
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
				<td class="num"><?= format_number($total_patients) ?></td>
				<td class="num"><?= format_amount($total_income) ?></td>
			</tr>
		</tfoot>
	</table>

	<?= print_report_footer() ?>
	<?= print_report_autoprint_script() ?>
</body>
</html>
