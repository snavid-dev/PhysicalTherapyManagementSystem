<?php
$filters = isset($filters) && is_array($filters) ? $filters : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();
$can_open_patient = $this->auth->has_permission('manage_patients');
$search = $filters['search'] ?? '';
$from = $filters['from'] ?? '';
$to = $filters['to'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Patient Financial Summary') ?></h1>
		<p class="text-muted mb-0"><?= t('Patient balances and visit totals.') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/patient-financial-summary', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
			<div class="col-md-4">
				<label class="form-label"><?= t('Search') ?></label>
				<input type="text" name="search" class="form-control" value="<?= html_escape($search) ?>" placeholder="<?= html_escape(t('Search patient name or phone')) ?>">
			</div>
			<div class="col-md-3">
				<label class="form-label"><?= t('From') ?></label>
				<input type="text" name="from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($from) ?>">
			</div>
			<div class="col-md-3">
				<label class="form-label"><?= t('To') ?></label>
				<input type="text" name="to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($to) ?>">
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-dark w-100"><?= t('Apply') ?></button>
			</div>
		<?= form_close() ?>
		<p class="text-muted small mt-3 mb-0"><?= t('Use date filters only for turn totals and last visit.') ?></p>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table dt-table" data-order-col="4" data-order-dir="desc" data-no-export="true">
				<thead>
					<tr>
						<th><?= t('Patient') ?></th>
						<th><?= t('Phone') ?></th>
						<th><?= t('Current Balance') ?></th>
						<th><?= t('total_open_debt') ?></th>
						<th><?= t('Visit Count') ?></th>
						<th><?= t('Total Turn Fees') ?></th>
						<th><?= t('Last Visit') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($rows) : ?>
					<?php foreach ($rows as $row) : ?>
						<?php
						$name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
						$phone = trim((string) ($row['phone'] ?? '')) !== '' ? $row['phone'] : ($row['phone2'] ?? '');
						$wallet_balance = (float) ($row['wallet_balance'] ?? 0);
						$open_debt = (float) ($row['open_debt'] ?? 0);
						?>
						<tr>
							<td><?= html_escape($name !== '' ? $name : ('#' . (int) $row['id'])) ?></td>
							<td><?= $phone ? html_escape($phone) : '&mdash;' ?></td>
							<td class="<?= $wallet_balance < 0 ? 'text-danger fw-semibold' : '' ?>"><?= format_amount($wallet_balance) ?></td>
							<td class="<?= $open_debt > 0 ? 'text-danger fw-semibold' : '' ?>"><?= format_amount($open_debt) ?></td>
							<td><?= format_number($row['total_turns'] ?? 0) ?></td>
							<td><?= format_amount($row['total_turn_fees'] ?? 0) ?></td>
							<td><?= !empty($row['last_turn_date']) ? html_escape(to_shamsi($row['last_turn_date'])) : '&mdash;' ?></td>
							<td class="no-export text-end">
								<?php if ($can_open_patient) : ?>
									<a href="<?= base_url('patients/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-secondary"><?= t('Open Profile') ?></a>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="8" class="text-center text-muted"><?= t('No matching patients found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
