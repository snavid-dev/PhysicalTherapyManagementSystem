<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Reports') ?></h1>
		<p class="text-muted mb-0"><?= t('Simple reporting for activity and payments.') ?></p>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
			<div class="col-md-4">
				<label class="form-label"><?= t('From') ?></label>
				<input type="text" name="from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($from) ?>">
			</div>
			<div class="col-md-4">
				<label class="form-label"><?= t('To') ?></label>
				<input type="text" name="to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($to) ?>">
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-dark"><?= t('Apply') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Turns') ?></div><div class="stat-value"><?= (int) $summary['turns'] ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Payments') ?></div><div class="stat-value">$<?= number_format((float) $summary['payments'], 2) ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Doctor Leaves') ?></div><div class="stat-value"><?= (int) $summary['leaves'] ?></div></div></div></div>
	<div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('New Patients') ?></div><div class="stat-value"><?= (int) $summary['new_patients'] ?></div></div></div></div>
</div>

<div class="row g-4">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Turns') ?></h2>
				<ul class="list-group list-group-flush">
					<?php if ($turns) : foreach ($turns as $turn) : ?>
						<li class="list-group-item px-0"><?= html_escape(to_shamsi($turn['turn_date'])) ?> | <?= html_escape($turn['patient_first_name'] . ' ' . $turn['patient_last_name']) ?></li>
					<?php endforeach; else : ?>
						<li class="list-group-item px-0 text-muted"><?= t('No turns in this range.') ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Payments') ?></h2>
				<ul class="list-group list-group-flush">
					<?php if ($payments) : foreach ($payments as $payment) : ?>
						<li class="list-group-item px-0"><?= html_escape(to_shamsi($payment['payment_date'])) ?> | <?= html_escape($payment['first_name'] . ' ' . $payment['last_name']) ?> | $<?= number_format((float) $payment['amount'], 2) ?></li>
					<?php endforeach; else : ?>
						<li class="list-group-item px-0 text-muted"><?= t('No payments in this range.') ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Doctor Leaves') ?></h2>
				<ul class="list-group list-group-flush">
					<?php if ($leaves) : foreach ($leaves as $leave) : ?>
						<li class="list-group-item px-0"><?= html_escape(to_shamsi($leave['start_date'])) ?> to <?= html_escape(to_shamsi($leave['end_date'])) ?> | <?= html_escape($leave['first_name'] . ' ' . $leave['last_name']) ?></li>
					<?php endforeach; else : ?>
						<li class="list-group-item px-0 text-muted"><?= t('No leaves in this range.') ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
