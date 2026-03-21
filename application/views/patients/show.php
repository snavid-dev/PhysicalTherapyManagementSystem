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

<?php $father_name = $patient['father_name'] ?? NULL; ?>
<?php $age = $patient['age'] ?? NULL; ?>
<?php $phone = $patient['phone'] ?? NULL; ?>
<?php $phone2 = $patient['phone2'] ?? NULL; ?>
<?php $address = $patient['address'] ?? NULL; ?>
<?php $medical_notes = $patient['medical_notes'] ?? NULL; ?>
<?php $referred_by = $patient['referred_by'] ?? NULL; ?>
<?php $referred_by_name = $patient['referred_by_name'] ?? NULL; ?>

<div class="row g-4">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Profile Details') ?></h2>
				<dl class="row mb-0">
					<dt class="col-5"><?= t('First Name') ?></dt><dd class="col-7"><?= html_escape($patient['first_name']) ?></dd>
					<dt class="col-5"><?= t('Last Name') ?></dt><dd class="col-7"><?= html_escape($patient['last_name']) ?></dd>
					<dt class="col-5"><?= t('father_name') ?></dt><dd class="col-7"><?= $father_name ? html_escape($father_name) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Gender') ?></dt><dd class="col-7"><?= !empty($patient['gender']) ? html_escape($patient['gender']) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('age') ?></dt><dd class="col-7"><?= $age !== NULL ? format_number($age) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Phone 1') ?></dt><dd class="col-7"><?= $phone ? html_escape($phone) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('phone2') ?></dt><dd class="col-7"><?= $phone2 ? html_escape($phone2) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Address') ?></dt><dd class="col-7"><?= $address ? nl2br(html_escape($address)) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Medical Notes') ?></dt><dd class="col-7"><?= $medical_notes ? nl2br(html_escape($medical_notes)) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Referred By') ?></dt><dd class="col-7"><?= $referred_by ? html_escape($referred_by_name) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('diagnoses') ?></dt>
					<dd class="col-7">
						<?php if (!empty($patient_diagnoses)) : ?>
							<?php
							$diagnosis_names = array_map(static function ($diagnosis) use ($is_rtl) {
								return $is_rtl && !empty($diagnosis['name_fa']) ? $diagnosis['name_fa'] : $diagnosis['name'];
							}, $patient_diagnoses);
							?>
							<?= html_escape(implode(', ', $diagnosis_names)) ?>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
					</dd>
				</dl>
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
