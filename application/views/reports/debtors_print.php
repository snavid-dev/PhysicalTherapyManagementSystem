<?php
$debtors = isset($debtors) && is_array($debtors) ? $debtors : array();
$total_debt = 0.0;
$total_wallet_negative = 0.0;
foreach ($debtors as $debtor) {
	$total_debt += (float) ($debtor['open_debt'] ?? 0);
	$wallet_balance = (float) ($debtor['wallet_balance'] ?? 0);
	if ($wallet_balance < 0) {
		$total_wallet_negative += $wallet_balance;
	}
}
$from = isset($from) ? trim((string) $from) : '';
$to = isset($to) ? trim((string) $to) : '';
$range_label = ($from !== '' || $to !== '') ? trim($from . ' - ' . $to, ' -') : '';
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('debtors_list') . ' - ' . shamsi_today()) ?></title>
	<?= print_report_styles() ?>
</head>
<body>
	<?= print_report_toolbar() ?>
	<?= print_report_letterhead(t('debtors_list'), $range_label, array(
		array('label' => t('Last Visit'), 'value' => $range_label !== '' ? $range_label : t('all_sections')),
	)) ?>

	<div class="stat-strip">
		<div class="stat">
			<p class="stat-label"><?= t('debtors_list') ?></p>
			<div class="stat-value"><?= format_number(count($debtors)) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('total_open_debt') ?></p>
			<div class="stat-value danger"><?= format_amount($total_debt) ?></div>
		</div>
		<div class="stat">
			<p class="stat-label"><?= t('wallet_balance') ?></p>
			<div class="stat-value warn"><?= format_amount($total_wallet_negative) ?></div>
		</div>
	</div>

	<table class="report-table">
		<thead>
			<tr>
				<th>#</th>
				<th><?= t('First Name') ?></th>
				<th><?= t('Last Name') ?></th>
				<th><?= t('father_name') ?></th>
				<th><?= t('Phone') ?></th>
				<th><?= t('Last Visit') ?></th>
				<th class="num"><?= t('total_open_debt') ?></th>
				<th class="num"><?= t('wallet_balance') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($debtors) : foreach ($debtors as $i => $row) : ?>
			<?php
			$wallet_balance = (float) ($row['wallet_balance'] ?? 0);
			$open_debt = (float) ($row['open_debt'] ?? 0);
			$last_turn = !empty($row['last_turn_date']) ? to_shamsi($row['last_turn_date']) : '&mdash;';
			?>
			<tr>
				<td><?= format_number($i + 1) ?></td>
				<td><?= html_escape($row['first_name'] ?? '') ?></td>
				<td><?= html_escape($row['last_name'] ?? '') ?></td>
				<td><?= !empty($row['father_name']) ? html_escape($row['father_name']) : '&mdash;' ?></td>
				<td><?= !empty($row['phone']) ? html_escape($row['phone']) : '&mdash;' ?></td>
				<td><?= html_escape($last_turn) ?></td>
				<td class="num <?= $open_debt > 0 ? 'text-danger' : '' ?>"><?= format_amount($open_debt) ?></td>
				<td class="num <?= $wallet_balance < 0 ? 'text-warn' : '' ?>"><?= format_amount($wallet_balance) ?></td>
			</tr>
		<?php endforeach; else : ?>
			<tr><td colspan="8"><?= t('No data available.') ?></td></tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6"><?= t('total_open_debt') ?></td>
				<td class="num text-danger"><?= format_amount($total_debt) ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>

	<?= print_report_footer() ?>
</body>
</html>
