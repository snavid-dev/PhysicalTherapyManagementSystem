<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Dashboard') ?></h1>
		<p class="text-muted mb-0"><?= t('Overview of the physical therapy clinic.') ?></p>
	</div>
</div>

<div class="row g-3 mb-4">
	<?php if (isset($safe_balance) && $safe_balance !== NULL) : ?>
		<div class="col-md-6 col-xl-4">
			<div class="card h-100 dashboard-safe-card <?= (float) $safe_balance > 0 ? 'dashboard-safe-card--positive' : 'dashboard-safe-card--neutral' ?>">
				<div class="card-body d-flex flex-column justify-content-between gap-3">
					<div class="d-flex justify-content-between align-items-start gap-3">
						<div>
							<div class="stat-label"><?= t('safe') ?></div>
							<div class="stat-value"><?= format_number($safe_balance, 2) ?></div>
						</div>
						<div class="dashboard-safe-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18a2 2 0 0 1 2 2v1.5H6.5A2.5 2.5 0 0 0 4 11v5.5A2.5 2.5 0 0 0 6.5 19H20V7"></path>
								<path d="M20 10H17a2 2 0 0 0 0 4h3"></path>
								<circle cx="17" cy="12" r=".25"></circle>
							</svg>
						</div>
					</div>
					<a href="<?= base_url('safe') ?>" class="dashboard-safe-card__link"><?= t('view_details') ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Patients') ?></div><div class="stat-value"><?= (int) $stats['patients'] ?></div></div></div></div>
	<div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Users') ?></div><div class="stat-value"><?= (int) $stats['users'] ?></div></div></div></div>
	<div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Today Turns') ?></div><div class="stat-value"><?= (int) $stats['today_turns'] ?></div></div></div></div>
</div>

<div class="row g-4">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center gap-3 mb-3">
					<h2 class="h5 mb-0"><?= t('Today Turns') ?></h2>
					<span class="text-muted small"><?= html_escape($today_shamsi) ?></span>
				</div>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead><tr><th><?= t('Time') ?></th><th><?= t('Patient') ?></th><th><?= t('Therapist') ?></th><th><?= t('Status') ?></th></tr></thead>
						<tbody>
						<?php if ($today_turns) : foreach ($today_turns as $turn) : ?>
							<tr>
								<td><?= html_escape($turn['turn_time']) ?></td>
								<td><?= html_escape($turn['patient_first_name'] . ' ' . $turn['patient_last_name']) ?></td>
								<td><?= html_escape($turn['therapist_first_name'] . ' ' . $turn['therapist_last_name']) ?></td>
								<td><span class="badge text-bg-light"><?= t(ucfirst($turn['status'])) ?></span></td>
							</tr>
						<?php endforeach; else : ?>
							<tr><td colspan="4" class="text-muted"><?= t('No turns for today.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
