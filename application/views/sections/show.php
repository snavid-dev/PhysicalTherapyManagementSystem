<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Section Staff Chart') ?></h1>
		<p class="text-muted mb-0"><?= html_escape(t($section['name'])) ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('sections/' . $section['id'] . '/edit') ?>" class="btn btn-outline-secondary"><?= t('Edit') ?></a>
		<a href="<?= base_url('sections') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="row g-4">
	<div class="col-12 col-lg-5">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Section Summary') ?></h2>
				<div class="border rounded p-3 mb-3">
					<div class="small text-muted mb-1"><?= t('Name') ?></div>
					<div class="fw-semibold"><?= html_escape(t($section['name'])) ?></div>
				</div>
				<div class="border rounded p-3">
					<div class="small text-muted mb-1"><?= t('Default Fee') ?></div>
					<div class="fw-semibold"><?= format_amount($section['default_fee']) ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-7">
		<div class="card h-100">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Staff Distribution') ?></h2>
				<?php
					$max_total = 0;
					foreach ($staff_type_counts as $row) {
						$max_total = max($max_total, (int) $row['total']);
					}
				?>
				<?php if ($staff_type_counts) : ?>
					<?php foreach ($staff_type_counts as $row) : ?>
						<?php $width = $max_total > 0 ? max(8, round(((int) $row['total'] / $max_total) * 100)) : 0; ?>
						<div class="mb-3">
							<div class="d-flex justify-content-between align-items-center mb-1 gap-3">
								<span><?= html_escape(t($row['staff_type_name'])) ?></span>
								<strong><?= format_number($row['total']) ?></strong>
							</div>
							<div class="progress section-chart-bar">
								<div class="progress-bar bg-dark" role="progressbar" style="width: <?= $width ?>%"></div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="text-muted mb-0"><?= t('No staff assigned to this section.') ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Section Staff') ?></h2>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th><?= t('Full Name') ?></th>
								<th><?= t('staff_type') ?></th>
								<th><?= t('Gender') ?></th>
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
								<td><?= $staff['status'] === 'active' ? t('Active') : t('Inactive') ?></td>
								<td class="text-end">
									<a href="<?= base_url('staff/profile/' . $staff['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Profile') ?></a>
								</td>
							</tr>
						<?php endforeach; else : ?>
							<tr><td colspan="5" class="text-muted"><?= t('No staff assigned to this section.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
