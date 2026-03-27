<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Doctor Leaves') ?></h1>
		<p class="text-muted mb-0"><?= t('Track therapist or doctor leave periods.') ?></p>
	</div>
	<a href="<?= base_url('leaves/create') ?>" class="btn btn-dark"><?= t('Add Leave') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-order-col="1" data-order-dir="desc" data-no-sort-cols="4" data-col-widths='["30%","16%","16%","12%","26%"]'>
				<thead><tr><th><?= t('Therapist') ?></th><th class="col-date"><?= t('From') ?></th><th class="col-date"><?= t('To') ?></th><th><?= t('Status') ?></th><th class="no-export text-end"><?= t('Actions') ?></th></tr></thead>
				<tbody>
				<?php if ($leaves) : foreach ($leaves as $leave) : ?>
					<tr>
						<td><?= html_escape($leave['first_name'] . ' ' . $leave['last_name']) ?></td>
						<td class="col-date"><?= html_escape(to_shamsi($leave['start_date'])) ?></td>
						<td class="col-date"><?= html_escape(to_shamsi($leave['end_date'])) ?></td>
						<td><?= t(ucfirst($leave['status'])) ?></td>
						<td class="no-export text-end">
							<a href="<?= base_url('leaves/' . $leave['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('leaves/' . $leave['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this leave?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
