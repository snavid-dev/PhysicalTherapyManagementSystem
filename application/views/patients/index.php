<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Patients') ?></h1>
		<p class="text-muted mb-0"><?= t('Basic patient records and profile access.') ?></p>
	</div>
	<a href="<?= base_url('patients/create') ?>" class="btn btn-dark"><?= t('Add Patient') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-dt-server="<?= html_escape($datatable_url) ?>" data-order-col="0" data-order-dir="asc" data-no-sort-cols="6" data-no-export="true" data-col-widths='["22%","13%","9%","8%","14%","10%","24%"]'>
				<thead>
					<tr>
						<th><?= t('Full Name') ?></th>
						<th><?= t('father_name') ?></th>
						<th><?= t('Gender') ?></th>
						<th><?= t('age') ?></th>
						<th><?= t('Phone 1') ?></th>
						<th><?= t('Status') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
