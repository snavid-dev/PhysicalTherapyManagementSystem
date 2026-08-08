<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('staff') ?></h1>
		<p class="text-muted mb-0"><?= t('manage_staff') ?></p>
	</div>
	<a href="<?= base_url('staff/create') ?>" class="btn btn-dark btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_staff') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead>
					<tr>
						<th><?= t('Full Name') ?></th>
						<th><?= t('staff_type') ?></th>
						<th><?= t('Gender') ?></th>
						<th><?= t('section') ?></th>
						<th><?= t('Status') ?></th>
						<th class="text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($staff_members) : foreach ($staff_members as $staff) : ?>
					<tr>
						<td><?= html_escape($staff['first_name'] . ' ' . $staff['last_name']) ?></td>
						<td><?= html_escape(t($staff['staff_type_name'])) ?></td>
						<td><?= $staff['gender'] === 'male' ? t('Male') : t('Female') ?></td>
						<td>
							<?php if (!empty($staff['sections'])) : ?>
								<?= html_escape(implode(', ', array_map(function ($section) { return t($section['name']); }, $staff['sections']))) ?>
							<?php else : ?>
								<?= t('section_na') ?>
							<?php endif; ?>
						</td>
						<td><?= $staff['status'] === 'active' ? t('Active') : t('Inactive') ?></td>
						<td class="text-end">
							<div class="d-flex gap-2 justify-content-end">
								<a href="<?= base_url('staff/edit/' . $staff['id']) ?>" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
								<a href="<?= base_url('staff/profile/' . $staff['id']) ?>" class="btn btn-sm btn-outline-dark btn-icon"><i class="bi bi-person-vcard" aria-hidden="true"></i> <?= t('Profile') ?></a>
								<?php if ($staff['status'] === 'active') : ?>
									<a href="<?= base_url('staff/delete/' . $staff['id']) ?>" class="btn btn-sm btn-outline-danger btn-icon" onclick="return confirm('<?= t('Deactivate this staff member?') ?>')"><i class="bi bi-x-circle" aria-hidden="true"></i> <?= t('Deactivate') ?></a>
								<?php else : ?>
									<a href="<?= base_url('staff/activate/' . $staff['id']) ?>" class="btn btn-sm btn-outline-success btn-icon"><i class="bi bi-arrow-clockwise" aria-hidden="true"></i> <?= t('Activate') ?></a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr>
						<td colspan="6" class="text-muted"><?= t('No staff found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
