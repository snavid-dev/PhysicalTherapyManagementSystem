<?php
$debtors = isset($debtors) && is_array($debtors) ? $debtors : array();
$total_debt = 0.0;
foreach ($debtors as $debtor) {
	$total_debt += (float) ($debtor['open_debt'] ?? 0);
}
$today_label = to_shamsi(date('Y-m-d'));
?>
<!DOCTYPE html>
<html dir="<?= is_rtl_locale() ? 'rtl' : 'ltr' ?>" lang="<?= app_locale() === 'farsi' ? 'fa' : 'en' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= html_escape(t('debtors_list') . ' - ' . $today_label) ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
	<style>
		body {
			font-family: 'Wazir', 'Tahoma', sans-serif;
			font-size: 12px;
			direction: <?= is_rtl_locale() ? 'rtl' : 'ltr' ?>;
			margin: 12mm;
			color: #111827;
		}
		.clinic-header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom: 8px; }
		.clinic-header h2 { margin: 0; font-size: 16px; }
		.clinic-header p { margin: 2px 0; font-size: 11px; }
		table { width: 100%; border-collapse: collapse; font-size: 11px; }
		th, td { border: 1px solid #333; padding: 6px 8px; text-align: <?= is_rtl_locale() ? 'right' : 'left' ?>; vertical-align: top; }
		th { background: #f0f0f0; font-weight: bold; }
		tfoot td { font-weight: bold; background: #f9f9f9; }
		.text-danger { color: #b91c1c; }
		.text-warning { color: #a16207; }
		.print-actions { margin-bottom: 10px; }
		.print-actions button { padding: 6px 14px; cursor: pointer; }
		@media print { .print-actions { display: none; } }
		.btn-icon {
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
		}
	</style>
</head>
<body>
	<div class="print-actions">
		<button type="button" class="btn-icon" onclick="window.print()"><i class="bi bi-printer" aria-hidden="true"></i> <?= t('print_register') ?></button>
		<button type="button" class="btn-icon" onclick="window.close()"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
	</div>

	<div class="clinic-header">
		<h2><?= html_escape(t('clinic_name_print')) ?></h2>
		<p><?= html_escape(t('debtors_list')) ?> &mdash; <?= html_escape($today_label) ?></p>
	</div>

	<table>
		<thead>
			<tr>
				<th>#</th>
				<th><?= t('First Name') ?></th>
				<th><?= t('Last Name') ?></th>
				<th><?= t('father_name') ?></th>
				<th><?= t('Phone') ?></th>
				<th><?= t('Last Visit') ?></th>
				<th><?= t('total_open_debt') ?></th>
				<th><?= t('wallet_balance') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($debtors) : foreach ($debtors as $i => $row) : ?>
			<?php
			$wallet_balance = (float) ($row['wallet_balance'] ?? 0);
			$open_debt = (float) ($row['open_debt'] ?? 0);
			$last_turn = !empty($row['last_turn_date']) ? to_shamsi($row['last_turn_date']) : '—';
			?>
			<tr>
				<td><?= format_number($i + 1) ?></td>
				<td><?= html_escape($row['first_name'] ?? '') ?></td>
				<td><?= html_escape($row['last_name'] ?? '') ?></td>
				<td><?= !empty($row['father_name']) ? html_escape($row['father_name']) : '—' ?></td>
				<td><?= !empty($row['phone']) ? html_escape($row['phone']) : '—' ?></td>
				<td><?= html_escape($last_turn) ?></td>
				<td class="<?= $open_debt > 0 ? 'text-danger' : '' ?>"><?= format_amount($open_debt) ?></td>
				<td class="<?= $wallet_balance < 0 ? 'text-warning' : '' ?>"><?= format_amount($wallet_balance) ?></td>
			</tr>
		<?php endforeach; else : ?>
			<tr><td colspan="8"><?= t('No data available.') ?></td></tr>
		<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6" style="text-align: <?= is_rtl_locale() ? 'left' : 'right' ?>"><?= t('total_open_debt') ?></td>
				<td class="text-danger"><?= format_amount($total_debt) ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</body>
</html>
