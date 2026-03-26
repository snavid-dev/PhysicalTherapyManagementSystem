<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Payments') ?></h1>
		<p class="text-muted mb-0"><?= t('Track simple patient payments.') ?></p>
	</div>
	<a href="<?= base_url('payments/create') ?>" class="btn btn-dark"><?= t('Add Payment') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<strong><?= t('Total Received:') ?></strong> $<?= number_format((float) $total_received, 2) ?>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead><tr><th><?= t('Date') ?></th><th><?= t('Patient') ?></th><th><?= t('Method') ?></th><th><?= t('Amount') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($payments) : foreach ($payments as $payment) : ?>
					<tr>
						<td><?= html_escape(to_shamsi($payment['payment_date'])) ?></td>
						<td><?= html_escape($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
						<td><?= html_escape($payment['payment_method']) ?></td>
						<td>$<?= number_format((float) $payment['amount'], 2) ?></td>
						<td class="text-end">
							<a href="<?= base_url('payments/' . $payment['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('payments/' . $payment['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this payment?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="5" class="text-muted"><?= t('No payments found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
