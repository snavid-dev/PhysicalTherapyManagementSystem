<?php
$display_time = static function ($time_value) {
	$time_value = (string) $time_value;
	return ($time_value === '' || $time_value === '00:00:00') ? '&mdash;' : html_escape(substr($time_value, 0, 5));
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Turns') ?></h1>
		<p class="text-muted mb-0"><?= t('Appointments for physical therapy sessions.') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('turns/bulk') ?>" class="btn btn-outline-dark"><?= t('Bulk Entry') ?></a>
		<a href="<?= base_url('turns/create') ?>" class="btn btn-dark"><?= t('Add Turn') ?></a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-order-col="0" data-order-dir="desc" data-no-sort-cols="9" data-col-widths='["5%","12%","10%","17%","11%","13%","8%","11%","8%","10%"]'>
				<thead>
					<tr>
						<th><?= t('turn_id') ?></th>
						<th class="col-date"><?= t('Date') ?></th>
						<th><?= t('Time') ?></th>
						<th><?= t('Patient') ?></th>
						<th><?= t('section') ?></th>
						<th><?= t('staff_member') ?></th>
						<th><?= t('fee') ?></th>
						<th><?= t('payment_type') ?></th>
						<th><?= t('Status') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($turns) : foreach ($turns as $turn) : ?>
					<?php $staff_name = !empty($turn['staff_full_name']) ? $turn['staff_full_name'] : ($turn['doctor_full_name'] ?? ''); ?>
					<tr>
						<td><?= '#' . (int) $turn['id'] ?></td>
						<td class="col-date"><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
						<td><?= $display_time($turn['turn_time']) ?></td>
						<td><?= html_escape($turn['patient_first_name'] . ' ' . $turn['patient_last_name']) ?></td>
						<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '&mdash;' ?></td>
						<td><?= $staff_name !== '' ? html_escape($staff_name) : '&mdash;' ?></td>
						<td><?= format_amount($turn['fee'] ?? 0) ?></td>
						<td><?= html_escape(t($turn['payment_type'] ?? 'cash')) ?></td>
						<td><?= t(ucfirst($turn['status'])) ?></td>
						<td class="no-export text-end">
							<a href="<?= base_url('turns/' . $turn['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('turns/' . $turn['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this turn?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
