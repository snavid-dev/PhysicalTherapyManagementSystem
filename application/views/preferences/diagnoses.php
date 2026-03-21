<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('diagnoses') ?></h1>
		<p class="text-muted mb-0"><?= t('manage_diagnoses') ?></p>
	</div>
	<a href="<?= base_url('dashboard') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<h2 class="h5 mb-3"><?= t('add_diagnosis') ?></h2>
		<?php if (!$schema_ready) : ?>
			<div class="alert alert-warning mb-0"><?= t('diagnosis_schema_missing') ?></div>
		<?php else : ?>
		<?= form_open('preferences/diagnoses/store') ?>
			<div class="row g-3 align-items-end">
				<div class="col-md-5">
					<label class="form-label"><?= t('diagnosis_name') ?></label>
					<input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" required>
				</div>
				<div class="col-md-5">
					<label class="form-label"><?= t('diagnosis_name_fa') ?></label>
					<input type="text" name="name_fa" class="form-control" value="<?= set_value('name_fa') ?>">
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-dark"><?= t('add_diagnosis') ?></button>
				</div>
			</div>
		<?= form_close() ?>
		<?php endif; ?>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead>
					<tr>
						<th><?= t('Name (EN)') ?></th>
						<th><?= t('Name (FA)') ?></th>
						<th class="text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if (!$schema_ready) : ?>
					<tr>
						<td colspan="3" class="text-muted"><?= t('diagnosis_schema_missing') ?></td>
					</tr>
				<?php elseif ($diagnoses) : ?>
					<?php foreach ($diagnoses as $diagnosis) : ?>
						<tr>
							<td><?= html_escape($diagnosis['name']) ?></td>
							<td><?= $diagnosis['name_fa'] ? html_escape($diagnosis['name_fa']) : '&mdash;' ?></td>
							<td class="text-end preferences-diagnosis-actions">
								<div class="d-flex gap-2 justify-content-end flex-wrap">
									<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#diagnosis-edit-<?= $diagnosis['id'] ?>" aria-expanded="false">
										<?= t('Edit') ?>
									</button>
									<?= form_open('preferences/diagnoses/delete/' . $diagnosis['id'], array('class' => 'd-inline')) ?>
										<button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this diagnosis?') ?>')">
											<?= t('Delete') ?>
										</button>
									<?= form_close() ?>
								</div>
							</td>
						</tr>
						<tr class="collapse" id="diagnosis-edit-<?= $diagnosis['id'] ?>">
							<td colspan="3">
								<?= form_open('preferences/diagnoses/update/' . $diagnosis['id']) ?>
									<div class="row g-3 align-items-end">
										<div class="col-md-5">
											<label class="form-label"><?= t('diagnosis_name') ?></label>
											<input type="text" name="name" class="form-control" value="<?= html_escape($diagnosis['name']) ?>" required>
										</div>
										<div class="col-md-5">
											<label class="form-label"><?= t('diagnosis_name_fa') ?></label>
											<input type="text" name="name_fa" class="form-control" value="<?= html_escape($diagnosis['name_fa']) ?>">
										</div>
										<div class="col-md-2">
											<button type="submit" class="btn btn-dark"><?= t('Update') ?></button>
										</div>
									</div>
								<?= form_close() ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="3" class="text-muted"><?= t('no_diagnoses') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
