<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Record a payment against a patient profile.') ?></p>
	</div>
	<a href="<?= base_url('payments') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('Patient') ?></label>
					<select name="patient_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php $selected_patient = (int) set_value('patient_id', $payment['patient_id'] ?? 0); ?>
						<?php foreach ($patients as $patient) : ?>
							<option value="<?= $patient['id'] ?>" <?= $selected_patient === (int) $patient['id'] ? 'selected' : '' ?>><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></option>
						<?php endforeach; ?>
					</select>
					<small class="text-danger"><?= form_error('patient_id') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Payment Date') ?></label>
					<input type="date" name="payment_date" class="form-control" value="<?= set_value('payment_date', $payment['payment_date'] ?? date('Y-m-d')) ?>">
					<small class="text-danger"><?= form_error('payment_date') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Amount') ?></label>
					<input type="number" step="0.01" name="amount" class="form-control" value="<?= set_value('amount', $payment['amount'] ?? '') ?>">
					<small class="text-danger"><?= form_error('amount') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Payment Method') ?></label>
					<?php $method = set_value('payment_method', $payment['payment_method'] ?? 'cash'); ?>
					<select name="payment_method" class="form-select">
						<option value="cash" <?= $method === 'cash' ? 'selected' : '' ?>><?= t('Cash') ?></option>
						<option value="card" <?= $method === 'card' ? 'selected' : '' ?>><?= t('Card') ?></option>
						<option value="transfer" <?= $method === 'transfer' ? 'selected' : '' ?>><?= t('Transfer') ?></option>
					</select>
					<small class="text-danger"><?= form_error('payment_method') ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= t('Reference Number') ?></label>
					<input type="text" name="reference_number" class="form-control" value="<?= set_value('reference_number', $payment['reference_number'] ?? '') ?>">
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Notes') ?></label>
					<textarea name="notes" class="form-control" rows="4"><?= set_value('notes', $payment['notes'] ?? '') ?></textarea>
				</div>
			</div>
			<div class="mt-4">
				<button type="submit" class="btn btn-dark"><?= t('Save Payment') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>
