<?php
$debtors = isset($debtors) && is_array($debtors) ? $debtors : array();
$can_open_patient = $this->auth->has_permission('manage_patients');
$total_debt = 0.0;
$total_negative_wallet = 0.0;
foreach ($debtors as $debtor) {
	$total_debt += (float) ($debtor['open_debt'] ?? 0);
	$wallet = (float) ($debtor['wallet_balance'] ?? 0);
	if ($wallet < 0) {
		$total_negative_wallet += abs($wallet);
	}
}
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
	<div>
		<h1 class="h3 mb-1"><?= t('debtors_list') ?></h1>
		<p class="text-muted mb-0"><?= t('debtors_list_hint') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('reports/debtors/print') ?>" class="btn btn-outline-dark" target="_blank" rel="noopener"><?= t('print_register') ?></a>
		<a href="<?= base_url('reports') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="text-muted small mb-1"><?= t('debtors_count') ?></div>
				<div class="h4 mb-0"><?= format_number(count($debtors)) ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="text-muted small mb-1"><?= t('total_open_debt') ?></div>
				<div class="h4 mb-0 text-danger"><?= format_amount($total_debt) ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="text-muted small mb-1"><?= t('total_negative_wallet') ?></div>
				<div class="h4 mb-0 text-warning"><?= format_amount($total_negative_wallet) ?></div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table dt-table" data-order-col="4" data-order-dir="desc" data-no-export="true">
				<thead>
					<tr>
						<th><?= t('First Name') ?></th>
						<th><?= t('Last Name') ?></th>
						<th><?= t('father_name') ?></th>
						<th><?= t('Phone') ?></th>
						<th><?= t('total_open_debt') ?></th>
						<th><?= t('wallet_balance') ?></th>
						<th><?= t('Last Visit') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($debtors) : ?>
					<?php foreach ($debtors as $row) : ?>
						<?php
						$wallet_balance = (float) ($row['wallet_balance'] ?? 0);
						$open_debt = (float) ($row['open_debt'] ?? 0);
						$last_turn = !empty($row['last_turn_date']) ? to_shamsi($row['last_turn_date']) : '';
						?>
						<tr>
							<td><?= html_escape($row['first_name'] ?? '') ?></td>
							<td><?= html_escape($row['last_name'] ?? '') ?></td>
							<td><?= !empty($row['father_name']) ? html_escape($row['father_name']) : '&mdash;' ?></td>
							<td><?= !empty($row['phone']) ? html_escape($row['phone']) : '&mdash;' ?></td>
							<td class="<?= $open_debt > 0 ? 'text-danger fw-semibold' : 'text-muted' ?>"><?= format_amount($open_debt) ?></td>
							<td class="<?= $wallet_balance < 0 ? 'text-warning fw-semibold' : 'text-muted' ?>"><?= format_amount($wallet_balance) ?></td>
							<td><?= $last_turn !== '' ? html_escape($last_turn) : '&mdash;' ?></td>
							<td class="no-export text-end">
								<?php if ($can_open_patient) : ?>
									<a href="<?= base_url('patients/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Open') ?></a>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="8" class="text-muted"><?= t('No data available.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
