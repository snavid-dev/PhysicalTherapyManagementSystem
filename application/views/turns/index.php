<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Turns') ?></h1>
		<p class="text-muted mb-0"><?= t('Appointments for physical therapy sessions.') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('turns/bulk-create') ?>" class="btn btn-outline-dark"><?= t('Bulk Entry') ?></a>
		<a href="<?= base_url('turns/create') ?>" class="btn btn-dark"><?= t('Add Turn') ?></a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead><tr><th><?= t('Date') ?></th><th><?= t('Time') ?></th><th><?= t('Patient') ?></th><th><?= t('Therapist') ?></th><th><?= t('Status') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($turns) : foreach ($turns as $turn) : ?>
					<tr>
						<td><?= html_escape($turn['turn_date']) ?></td>
						<td><?= html_escape($turn['turn_time']) ?></td>
						<td><?= html_escape($turn['patient_first_name'] . ' ' . $turn['patient_last_name']) ?></td>
						<td><?= html_escape($turn['therapist_first_name'] . ' ' . $turn['therapist_last_name']) ?></td>
						<td><?= t(ucfirst($turn['status'])) ?></td>
						<td class="text-end">
							<a href="<?= base_url('turns/' . $turn['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('turns/' . $turn['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this turn?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="6" class="text-muted"><?= t('No turns found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
