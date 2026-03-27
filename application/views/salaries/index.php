<?php
$overall_total = 0;
foreach ($records as $record) {
	$overall_total += (float) $record['final_salary'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('Staff Salaries') ?></h1>
		<p class="text-muted mb-0"><?= t('Review salary status by month and staff.') ?></p>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form method="get" action="<?= base_url('salaries') ?>">
			<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label"><?= t('month') ?></label>
					<input type="text" name="month" class="form-control shamsi-month" placeholder="1403/01" value="<?= html_escape($filters['month']) ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Status') ?></label>
					<select name="status" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<option value="unpaid" <?= $filters['status'] === 'unpaid' ? 'selected' : '' ?>><?= t('salary_unpaid') ?></option>
						<option value="partial" <?= $filters['status'] === 'partial' ? 'selected' : '' ?>><?= t('salary_partial') ?></option>
						<option value="paid" <?= $filters['status'] === 'paid' ? 'selected' : '' ?>><?= t('Paid') ?></option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= t('staff') ?></label>
					<select name="staff_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($staff_members as $staff_member) : ?>
							<option value="<?= $staff_member['id'] ?>" <?= (int) $filters['staff_id'] === (int) $staff_member['id'] ? 'selected' : '' ?>>
								<?= html_escape(trim($staff_member['first_name'] . ' ' . $staff_member['last_name'])) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2 d-flex gap-2 align-items-end">
					<button type="submit" class="btn btn-dark w-100"><?= t('Apply') ?></button>
					<a href="<?= base_url('salaries') ?>" class="btn btn-outline-secondary w-100"><?= t('Reset') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-order-col="0" data-order-dir="desc" data-no-sort-cols="9" data-col-widths='["10%","16%","10%","9%","9%","10%","10%","10%","8%","8%"]'>
				<thead>
					<tr>
						<th class="col-date"><?= t('month') ?></th>
						<th><?= t('staff') ?></th>
						<th><?= t('Type') ?></th>
						<th><?= t('base_salary') ?></th>
						<th><?= t('deduction') ?></th>
						<th><?= t('final_salary') ?></th>
						<th><?= t('total_paid') ?></th>
						<th><?= t('remaining') ?></th>
						<th><?= t('Status') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($records) : ?>
					<?php foreach ($records as $record) : ?>
						<?php $remaining = max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2)); ?>
						<tr>
							<td class="col-date"><?= html_escape(gregorian_month_to_shamsi($record['month'])) ?></td>
							<td><?= html_escape(trim($record['first_name'] . ' ' . $record['last_name'])) ?></td>
							<td><?= html_escape(t($record['salary_type'])) ?></td>
							<td><?= format_number($record['base_salary'], 2) ?></td>
							<td><?= format_number($record['calculated_deduction'], 2) ?></td>
							<td><?= format_number($record['final_salary'], 2) ?></td>
							<td><?= format_number($record['total_paid'], 2) ?></td>
							<td><?= format_number($remaining, 2) ?></td>
							<td>
								<?php if ($record['status'] === 'paid') : ?>
									<span class="badge text-bg-success"><?= t('Paid') ?></span>
								<?php elseif ($record['status'] === 'partial') : ?>
									<span class="badge text-bg-warning"><?= t('salary_partial') ?></span>
								<?php else : ?>
									<span class="badge text-bg-secondary"><?= t('salary_unpaid') ?></span>
								<?php endif; ?>
							</td>
							<td class="no-export text-end">
								<a href="<?= base_url('salaries/pay/' . $record['staff_id'] . '?month=' . rawurlencode(gregorian_month_to_shamsi($record['month']))) ?>" class="btn btn-sm btn-dark"><?= t('salary_payment') ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
				<?php if ($records) : ?>
					<tfoot>
						<tr>
							<td colspan="5" class="fw-semibold"><?= t('Total:') ?></td>
							<td class="fw-semibold"><?= format_number($overall_total, 2) ?></td>
							<td colspan="4"></td>
						</tr>
					</tfoot>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
