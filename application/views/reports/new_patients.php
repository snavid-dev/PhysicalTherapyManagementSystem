<?php
$patients = isset($patients) && is_array($patients) ? $patients : array();
$can_open_patient = $this->auth->has_permission('manage_patients');
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
	<div>
		<h1 class="h3 mb-1"><?= t('new_patients_report') ?></h1>
		<p class="text-muted mb-0"><?= t('new_patients_report_hint') ?></p>
	</div>
	<a href="<?= base_url('reports') ?>" class="btn btn-outline-dark btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<?= form_open('reports/new-patients', array('method' => 'get', 'class' => 'row g-3 align-items-end')) ?>
			<div class="col-md-4">
				<label class="form-label"><?= t('Date From') ?></label>
				<input type="text" name="from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($from) ?>">
			</div>
			<div class="col-md-4">
				<label class="form-label"><?= t('Date To') ?></label>
				<input type="text" name="to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($to) ?>">
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-dark w-100 btn-icon"><i class="bi bi-funnel" aria-hidden="true"></i> <?= t('Apply') ?></button>
			</div>
		<?= form_close() ?>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="text-muted small mb-1"><?= t('new_patients_count') ?></div>
				<div class="h4 mb-0"><?= format_number(count($patients)) ?></div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table dt-table" data-order-col="5" data-order-dir="desc" data-no-export="true">
				<thead>
					<tr>
						<th><?= t('First Name') ?></th>
						<th><?= t('Last Name') ?></th>
						<th><?= t('father_name') ?></th>
						<th><?= t('Gender') ?></th>
						<th><?= t('Phone') ?></th>
						<th class="col-date"><?= t('date_added') ?></th>
						<th><?= t('Referred By') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($patients) : foreach ($patients as $row) : ?>
					<tr>
						<td><?= html_escape($row['first_name'] ?? '') ?></td>
						<td><?= html_escape($row['last_name'] ?? '') ?></td>
						<td><?= !empty($row['father_name']) ? html_escape($row['father_name']) : '&mdash;' ?></td>
						<td><?= !empty($row['gender']) ? html_escape($row['gender']) : '&mdash;' ?></td>
						<td><?= !empty($row['phone']) ? html_escape($row['phone']) : '&mdash;' ?></td>
						<td class="col-date"><?= html_escape(to_shamsi(substr((string) ($row['created_at'] ?? ''), 0, 10))) ?></td>
						<td><?= !empty($row['referred_by_name']) ? html_escape($row['referred_by_name']) : '&mdash;' ?></td>
						<td class="no-export text-end">
							<?php if ($can_open_patient) : ?>
								<a href="<?= base_url('patients/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-dark btn-icon"><i class="bi bi-person-vcard" aria-hidden="true"></i> <?= t('Open') ?></a>
							<?php else : ?>
								&mdash;
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="8" class="text-muted"><?= t('No data available.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
