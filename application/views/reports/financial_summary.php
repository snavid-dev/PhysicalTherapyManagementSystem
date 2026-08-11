<?php
$sections = isset($sections) && is_array($sections) ? $sections : array();
$safe_summary = isset($safe_summary) && is_array($safe_summary) ? $safe_summary : array();

$total_income = 0.00;
$total_patients = 0;
foreach ($sections as $section) {
	$total_income += (float) ($section['total_income'] ?? 0);
	$total_patients += (int) ($section['patient_count'] ?? 0);
}

$print_url = base_url('reports/financial-summary/print?' . http_build_query(array('from' => $from, 'to' => $to)));
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('financial_summary_report') ?></h1>
		<p class="text-muted mb-0"><?= t('financial_summary_report_hint') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-secondary btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/financial-summary', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
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

<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('safe_balance_before') ?></div><div class="stat-value"><?= format_amount($safe_summary['opening_balance'] ?? 0) ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('total_income') ?></div><div class="stat-value"><?= format_amount($total_income) ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('total_expenses') ?></div><div class="stat-value"><?= format_amount($expenses_total ?? 0) ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('safe_balance_after') ?></div><div class="stat-value"><?= format_amount($safe_summary['closing_balance'] ?? 0) ?></div></div></div></div>
</div>

<div class="card">
	<div class="card-body">
		<h2 class="h5 mb-3"><?= t('income_by_section') ?></h2>
		<div class="table-responsive">
			<table class="table table-sm align-middle mb-0">
				<thead>
					<tr>
						<th><?= t('section') ?></th>
						<th class="text-end"><?= t('patient_count') ?></th>
						<th class="text-end"><?= t('total_income') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($sections) : ?>
					<?php foreach ($sections as $section) : ?>
						<tr>
							<td><?= !empty($section['section_name']) ? html_escape(t($section['section_name'])) : t('section_na') ?></td>
							<td class="text-end"><?= format_number($section['patient_count'] ?? 0) ?></td>
							<td class="text-end"><?= format_amount($section['total_income'] ?? 0) ?></td>
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
						<td class="fw-semibold"><?= t('Total:') ?></td>
						<td class="text-end fw-semibold"><?= format_number($total_patients) ?></td>
						<td class="text-end fw-semibold"><?= format_amount($total_income) ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
