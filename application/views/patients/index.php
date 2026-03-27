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
			<table class="table align-middle dt-table" data-order-col="0" data-order-dir="asc" data-no-sort-cols="6" data-col-widths='["22%","13%","9%","8%","14%","10%","24%"]'>
				<thead>
					<tr>
						<th><?= t('Full Name') ?></th>
						<th><?= t('father_name') ?></th>
						<th><?= t('Gender') ?></th>
						<th><?= t('age') ?></th>
						<th><?= t('Phone 1') ?></th>
						<th><?= t('Status') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($patients) : foreach ($patients as $patient) : ?>
					<?php $father_name = $patient['father_name'] ?? NULL; ?>
					<?php $age = $patient['age'] ?? NULL; ?>
					<?php $phone = $patient['phone'] ?? NULL; ?>
					<?php $gender = $patient['gender'] ?? NULL; ?>
					<tr>
						<td><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
						<td><?= $father_name ? html_escape($father_name) : '&mdash;' ?></td>
						<td><?= $gender ? html_escape($gender) : '&mdash;' ?></td>
						<td><?= $age !== NULL ? format_number($age) : '&mdash;' ?></td>
						<td><?= $phone ? html_escape($phone) : '&mdash;' ?></td>
						<td><span class="badge text-bg-success"><?= t('Active') ?></span></td>
						<td class="no-export text-end">
							<div class="d-flex gap-2 justify-content-end flex-wrap">
								<a href="<?= base_url('patients/' . $patient['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a>
								<a href="<?= base_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
								<a href="<?= base_url('patients/' . $patient['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this patient?') ?>')"><?= t('Delete') ?></a>
							</div>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
