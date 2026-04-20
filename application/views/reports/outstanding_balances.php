<?php
$filters = isset($filters) && is_array($filters) ? $filters : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();
$can_open_patient = $this->auth->has_permission('manage_patients');
$selected_status = $filters['status'] ?? 'all';
$search = $filters['search'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Outstanding Balances') ?></h1>
		<p class="text-muted mb-0"><?= t('Outstanding balance follow-up list.') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/outstanding-balances', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
			<div class="col-md-5">
				<label class="form-label"><?= t('Search') ?></label>
				<input type="text" name="search" class="form-control" value="<?= html_escape($search) ?>" placeholder="<?= html_escape(t('Search patient name or phone')) ?>">
			</div>
			<div class="col-md-4">
				<label class="form-label"><?= t('Filter') ?></label>
				<select name="status" class="form-select">
					<option value="all"<?= $selected_status === 'all' ? ' selected' : '' ?>><?= t('all_outstanding') ?></option>
					<option value="negative_wallet"<?= $selected_status === 'negative_wallet' ? ' selected' : '' ?>><?= t('negative_wallet_only') ?></option>
					<option value="debt"<?= $selected_status === 'debt' ? ' selected' : '' ?>><?= t('debt_only') ?></option>
					<option value="both"<?= $selected_status === 'both' ? ' selected' : '' ?>><?= t('both_negative_and_debt') ?></option>
				</select>
			</div>
			<div class="col-md-3">
				<button type="submit" class="btn btn-dark w-100"><?= t('Apply') ?></button>
			</div>
		<?= form_close() ?>
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
						<th><?= t('Total Due') ?></th>
						<th><?= t('Last Visit') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($rows) : ?>
					<?php foreach ($rows as $row) : ?>
						<?php
						$wallet_balance = (float) ($row['wallet_balance'] ?? 0);
						$open_debt = (float) ($row['open_debt'] ?? 0);
						$total_due = round(max(0, $open_debt) + max(0, 0 - $wallet_balance), 2);
						$name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
						$phone = trim((string) ($row['phone'] ?? '')) !== '' ? $row['phone'] : ($row['phone2'] ?? '');
						?>
						<tr>
							<td><?= html_escape($name !== '' ? $name : ('#' . (int) $row['id'])) ?></td>
							<td><?= $phone ? html_escape($phone) : '&mdash;' ?></td>
							<td class="<?= $wallet_balance < 0 ? 'text-danger fw-semibold' : '' ?>"><?= format_amount($wallet_balance) ?></td>
							<td class="<?= $open_debt > 0 ? 'text-danger fw-semibold' : '' ?>"><?= format_amount($open_debt) ?></td>
							<td class="<?= $total_due > 0 ? 'text-danger fw-semibold' : '' ?>"><?= format_amount($total_due) ?></td>
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
						<td colspan="7" class="text-center text-muted"><?= t('No matching patients found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
