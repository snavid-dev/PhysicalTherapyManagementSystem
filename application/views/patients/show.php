<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></h1>
		<p class="text-muted mb-0"><?= t('Patient Profile') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-outline-secondary"><?= t('Edit') ?></a>
		<a href="<?= base_url('patients') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="row g-4">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Profile Details') ?></h2>
				<dl class="row mb-0">
					<dt class="col-5"><?= t('Phone') ?></dt><dd class="col-7"><?= html_escape($patient['phone']) ?></dd>
					<dt class="col-5"><?= t('Email') ?></dt><dd class="col-7"><?= html_escape($patient['email']) ?></dd>
					<dt class="col-5"><?= t('Gender') ?></dt><dd class="col-7"><?= html_escape($patient['gender']) ?></dd>
					<dt class="col-5"><?= t('Birth Date') ?></dt><dd class="col-7"><?= html_escape($patient['date_of_birth']) ?></dd>
					<dt class="col-5"><?= t('Address') ?></dt><dd class="col-7"><?= html_escape($patient['address']) ?></dd>
					<dt class="col-5"><?= t('Referred By') ?></dt><dd class="col-7"><?= $patient['referred_by'] ? html_escape($patient['referred_by_name']) : '&mdash;' ?></dd>
				</dl>
				<hr>
				<h3 class="h6"><?= t('Medical Notes') ?></h3>
				<p class="mb-0 text-muted"><?= nl2br(html_escape($patient['medical_notes'])) ?></p>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card mb-4">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Turn History') ?></h2>
				<div class="table-responsive">
					<table class="table">
						<thead><tr><th><?= t('Date') ?></th><th><?= t('Time') ?></th><th><?= t('Therapist') ?></th><th><?= t('Status') ?></th></tr></thead>
						<tbody>
						<?php if ($turns) : foreach ($turns as $turn) : ?>
							<tr>
								<td><?= html_escape($turn['turn_date']) ?></td>
								<td><?= html_escape($turn['turn_time']) ?></td>
								<td><?= html_escape($turn['first_name'] . ' ' . $turn['last_name']) ?></td>
								<td><?= t(ucfirst($turn['status'])) ?></td>
							</tr>
						<?php endforeach; else : ?>
							<tr><td colspan="4" class="text-muted"><?= t('No turns found.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Payment History') ?></h2>
				<div class="table-responsive">
					<table class="table">
						<thead><tr><th><?= t('Date') ?></th><th><?= t('Method') ?></th><th><?= t('Amount') ?></th><th><?= t('Reference Number') ?></th></tr></thead>
						<tbody>
						<?php if ($payments) : foreach ($payments as $payment) : ?>
							<tr>
								<td><?= html_escape($payment['payment_date']) ?></td>
								<td><?= html_escape($payment['payment_method']) ?></td>
								<td>$<?= number_format((float) $payment['amount'], 2) ?></td>
								<td><?= html_escape($payment['reference_number']) ?></td>
							</tr>
						<?php endforeach; else : ?>
							<tr><td colspan="4" class="text-muted"><?= t('No payments found.') ?></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
