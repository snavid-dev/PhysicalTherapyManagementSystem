<?php
$patient_name = trim((string) ($payment['first_name'] ?? '') . ' ' . (string) ($payment['last_name'] ?? ''));

if ($patient_name === '') {
	$patient_name = trim((string) ($payment['first_name'] ?? '') . ' ' . (string) ($payment['father_name'] ?? ''));
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('Payment Details') ?></h1>
		<p class="text-muted mb-0"><?= t('Review a single patient payment record.') ?></p>
	</div>
	<div class="d-flex gap-2 flex-wrap">
		<a href="<?= base_url('payments/' . $payment['id'] . '/edit') ?>" class="btn btn-outline-secondary"><?= t('Edit') ?></a>
		<a href="<?= base_url('payments') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="row g-4">
			<div class="col-md-6">
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Patient') ?></span><span class="safe-summary-value"><a href="<?= base_url('patients/' . $payment['patient_id']) ?>"><?= html_escape($patient_name) ?></a></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Payment Date') ?></span><span class="safe-summary-value"><?= html_escape(to_shamsi($payment['payment_date'])) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Amount') ?></span><span class="safe-summary-value"><?= format_number($payment['amount'], 2) ?></span></div>
			</div>
			<div class="col-md-6">
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Payment Method') ?></span><span class="safe-summary-value"><?= html_escape($payment['payment_method']) ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Reference Number') ?></span><span class="safe-summary-value"><?= $payment['reference_number'] ? html_escape($payment['reference_number']) : '&mdash;' ?></span></div>
				<div class="safe-summary-row"><span class="safe-summary-label"><?= t('Notes') ?></span><span class="safe-summary-value"><?= $payment['notes'] ? nl2br(html_escape($payment['notes'])) : '&mdash;' ?></span></div>
			</div>
		</div>
	</div>
</div>
