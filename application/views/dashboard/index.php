<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Dashboard') ?></h1>
		<p class="text-muted mb-0"><?= t('Overview of the physical therapy clinic.') ?></p>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Patients') ?></div><div class="stat-value"><?= (int) $stats['patients'] ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Users') ?></div><div class="stat-value"><?= (int) $stats['users'] ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Today Turns') ?></div><div class="stat-value"><?= (int) $stats['today_turns'] ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('This Month Payments') ?></div><div class="stat-value">$<?= number_format((float) $stats['payments_this_month'], 2) ?></div></div></div></div>
</div>

<div class="row g-4">
	<div class="col-lg-7">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Today Turns') ?></h2>
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
	<div class="col-lg-5">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Recent Payments') ?></h2>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead><tr><th><?= t('Date') ?></th><th><?= t('Patient') ?></th><th><?= t('Amount') ?></th></tr></thead>
						<tbody>
						<?php if ($recent_payments) : foreach ($recent_payments as $payment) : ?>
							<tr>
								<td><?= html_escape($payment['payment_date']) ?></td>
								<td><?= html_escape($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
								<td>$<?= number_format((float) $payment['amount'], 2) ?></td>
							</tr>
						<?php endforeach; else : ?>
							<tr><td colspan="3" class="text-muted"><?= t('No payments yet.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
