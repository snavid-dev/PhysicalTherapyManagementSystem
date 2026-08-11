<?php
$doctors = isset($doctors) && is_array($doctors) ? $doctors : array();

$total_referred = 0;
foreach ($doctors as $doctor) {
	$total_referred += (int) ($doctor['referred_count'] ?? 0);
}

$print_url = base_url('reports/doctor-referrals/print?' . http_build_query(array('from' => $from, 'to' => $to)));
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('doctor_referral_report') ?></h1>
		<p class="text-muted mb-0"><?= t('doctor_referral_report_hint') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-secondary btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/doctor-referrals', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
			<div class="col-md-4">
				<label class="form-label"><?= t('From') ?></label>
				<input type="text" name="from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($from) ?>">
			</div>
			<div class="col-md-4">
				<label class="form-label"><?= t('To') ?></label>
				<input type="text" name="to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($to) ?>">
			</div>
			<div class="col-md-4">
				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-dark flex-grow-1 btn-icon"><i class="bi bi-funnel" aria-hidden="true"></i> <?= t('Apply') ?></button>
					<a href="<?= $print_url ?>" class="btn btn-outline-dark flex-grow-1 btn-icon" target="_blank" rel="noopener"><i class="bi bi-printer" aria-hidden="true"></i> <?= t('dt_print') ?></a>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm align-middle mb-0">
				<thead>
					<tr>
						<th><?= t('reference_doctor') ?></th>
						<th><?= t('specialty') ?></th>
						<th class="text-end"><?= t('patients_referred') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($doctors) : ?>
					<?php foreach ($doctors as $doctor) : ?>
						<tr>
							<td><a href="<?= base_url('reference_doctors/profile/' . (int) $doctor['id']) ?>" class="text-decoration-none"><?= html_escape(trim($doctor['first_name'] . ' ' . ($doctor['last_name'] ?? ''))) ?></a></td>
							<td><?= !empty($doctor['specialty']) ? html_escape($doctor['specialty']) : '&mdash;' ?></td>
							<td class="text-end"><span class="badge rounded-pill bg-dark-subtle text-dark-emphasis"><?= format_number($doctor['referred_count'] ?? 0) ?></span></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="3" class="text-center text-muted"><?= t('No data available.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2" class="fw-semibold"><?= t('Total:') ?></td>
						<td class="text-end fw-semibold"><?= format_number($total_referred) ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
