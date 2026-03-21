<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('reference_doctors') ?></h1>
		<p class="text-muted mb-0"><?= t('Reference doctor records and referral tracking.') ?></p>
	</div>
	<a href="<?= base_url('reference_doctors/create') ?>" class="btn btn-dark"><?= t('add_reference_doctor') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead>
					<tr>
						<th><?= t('Full Name') ?></th>
						<th><?= t('specialty') ?></th>
						<th><?= t('Phone') ?></th>
						<th><?= t('clinic_name') ?></th>
						<th><?= t('total_referred') ?></th>
						<th><?= t('Status') ?></th>
						<th class="text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($reference_doctors) : foreach ($reference_doctors as $reference_doctor) : ?>
					<tr>
						<td><?= html_escape($reference_doctor['first_name'] . ' ' . $reference_doctor['last_name']) ?></td>
						<td><?= html_escape($reference_doctor['specialty']) ?></td>
						<td><?= html_escape($reference_doctor['phone']) ?></td>
						<td><?= html_escape($reference_doctor['clinic_name']) ?></td>
						<td><?= format_number($reference_doctor['total_referred']) ?></td>
						<td><?= $reference_doctor['status'] === 'active' ? t('Active') : t('Inactive') ?></td>
						<td class="text-end">
							<div class="d-flex gap-2 justify-content-end flex-wrap">
								<a href="<?= base_url('reference_doctors/edit/' . $reference_doctor['id']) ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
								<a href="<?= base_url('reference_doctors/profile/' . $reference_doctor['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a>
								<?php if ($reference_doctor['status'] === 'active') : ?>
									<a href="<?= base_url('reference_doctors/delete/' . $reference_doctor['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Deactivate this reference doctor?') ?>')"><?= t('Deactivate') ?></a>
								<?php else : ?>
									<a href="<?= base_url('reference_doctors/activate/' . $reference_doctor['id']) ?>" class="btn btn-sm btn-outline-success"><?= t('Activate') ?></a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr>
						<td colspan="7" class="text-muted"><?= t('No reference doctors found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
