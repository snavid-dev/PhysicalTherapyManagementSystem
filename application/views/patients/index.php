<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Patients') ?></h1>
		<p class="text-muted mb-0"><?= t('Basic patient records and profile access.') ?></p>
	</div>
	<a href="<?= base_url('patients/create') ?>" class="btn btn-dark"><?= t('Add Patient') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead><tr><th><?= t('Patient') ?></th><th><?= t('Phone') ?></th><th><?= t('Email') ?></th><th><?= t('Gender') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($patients) : foreach ($patients as $patient) : ?>
					<tr>
						<td><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
						<td><?= html_escape($patient['phone']) ?></td>
						<td><?= html_escape($patient['email']) ?></td>
						<td><?= html_escape($patient['gender']) ?></td>
						<td class="text-end">
							<a href="<?= base_url('patients/' . $patient['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a>
							<a href="<?= base_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('patients/' . $patient['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this patient?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="5" class="text-muted"><?= t('No patients found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
