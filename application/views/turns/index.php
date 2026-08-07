<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Turns') ?></h1>
		<p class="text-muted mb-0"><?= t('Appointments for physical therapy sessions.') ?></p>
	</div>
	<div class="d-flex gap-2">
		<a href="<?= base_url('turns/bulk') ?>" class="btn btn-outline-dark btn-icon"><i class="bi bi-list-check" aria-hidden="true"></i> <?= t('Bulk Entry') ?></a>
		<a href="<?= base_url('turns/create') ?>" class="btn btn-dark btn-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('Add Turn') ?></a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-dt-server="<?= html_escape($datatable_url) ?>" data-order-col="0" data-order-dir="desc" data-no-sort-cols="9" data-no-export="true" data-col-widths='["5%","12%","9%","18%","13%","11%","13%","8%","11%","auto"]'>
				<thead>
					<tr>
						<th><?= t('turn_id') ?></th>
						<th class="col-date"><?= t('Date') ?></th>
						<th><?= t('turn_number') ?></th>
						<th><?= t('Patient') ?></th>
						<th><?= t('father_name') ?> / <?= t('Last Name') ?></th>
						<th><?= t('section') ?></th>
						<th><?= t('staff_member') ?></th>
						<th><?= t('fee') ?></th>
						<th><?= t('payment_type') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
