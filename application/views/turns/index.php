<?php $turns = isset($turns) && is_array($turns) ? $turns : array(); ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
	<div>
		<h1 class="h3 mb-1"><?= t('Turns') ?></h1>
		<p class="text-muted mb-0"><?= t('Appointments for physical therapy sessions.') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('turns/bulk') ?>" class="btn btn-outline-dark btn-icon"><i class="bi bi-list-check" aria-hidden="true"></i> <?= t('Bulk Entry') ?></a>
		<a href="<?= base_url('turns/create') ?>" class="btn btn-dark btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('Add Turn') ?></a>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form method="get" action="<?= base_url('turns') ?>">
			<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label"><?= t('Date From') ?></label>
					<input type="text" name="from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($from) ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Date To') ?></label>
					<input type="text" name="to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($to) ?>">
				</div>
				<div class="col-md-3 d-flex gap-2 align-items-end">
					<button type="submit" class="btn btn-dark w-100 btn-icon"><i class="bi bi-funnel" aria-hidden="true"></i> <?= t('Apply') ?></button>
					<a href="<?= base_url('turns') ?>" class="btn btn-outline-secondary w-100 btn-icon"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= t('Reset') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-order-col="0" data-order-dir="desc" data-no-sort-cols="9" data-no-export="true" data-col-widths='["5%","12%","9%","18%","13%","11%","13%","8%","11%","auto"]'>
				<thead>
					<tr>
						<th><?= t('turn_id') ?></th>
						<th class="col-date"><?= t('Date') ?></th>
						<th><?= t('turn_number') ?></th>
						<th><?= t('Patient') ?></th>
						<th><?= t('father_name') ?> / <?= t('Last Name') ?></th>
						<th><?= t('section') ?></th>
						<th><?= t('staff_member') ?></th>
						<th><?= t('fee') ?></th>
						<th><?= t('payment_type') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($turns) : ?>
					<?php foreach ($turns as $turn) : $id = (int) $turn['id']; ?>
						<tr>
							<td>#<?= $id ?></td>
							<td class="col-date"><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
							<td><?= !empty($turn['turn_number']) ? format_number($turn['turn_number']) : '&mdash;' ?></td>
							<td><?= html_escape($turn['patient_name']) ?></td>
							<td><?= $turn['family_name'] !== NULL ? html_escape($turn['family_name']) : '&mdash;' ?></td>
							<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '&mdash;' ?></td>
							<td><?= $turn['staff_name'] !== '' ? html_escape($turn['staff_name']) : '&mdash;' ?></td>
							<td><?= format_amount($turn['fee'] ?? 0) ?></td>
							<td><?= html_escape(t($turn['payment_type'] ?? 'cash')) ?></td>
							<td class="no-export text-end">
								<div class="d-flex gap-2 justify-content-end flex-wrap">
									<a href="<?= base_url('turns/' . $id . '/edit') ?>" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
									<a href="<?= base_url('turns/' . $id . '/delete') ?>" class="btn btn-sm btn-outline-danger btn-icon" onclick="return confirm('<?= t('Delete this turn?') ?>')"><i class="bi bi-trash" aria-hidden="true"></i> <?= t('Delete') ?></a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="10" class="text-muted"><?= t('No data available.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
