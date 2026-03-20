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
			<table class="table align-middle">
				<thead><tr><th><?= t('Therapist') ?></th><th><?= t('From') ?></th><th><?= t('To') ?></th><th><?= t('Status') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($leaves) : foreach ($leaves as $leave) : ?>
					<tr>
						<td><?= html_escape($leave['first_name'] . ' ' . $leave['last_name']) ?></td>
						<td><?= html_escape($leave['start_date']) ?></td>
						<td><?= html_escape($leave['end_date']) ?></td>
						<td><?= t(ucfirst($leave['status'])) ?></td>
						<td class="text-end">
							<a href="<?= base_url('leaves/' . $leave['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('leaves/' . $leave['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this leave?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="5" class="text-muted"><?= t('No leaves found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
