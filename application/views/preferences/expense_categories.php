<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('expense_categories') ?></h1>
		<p class="text-muted mb-0"><?= t('manage_expenses') ?></p>
	</div>
	<a href="<?= base_url('dashboard') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<h2 class="h5 mb-3"><?= t('add_expense_category') ?></h2>
		<?= form_open('preferences/expense-categories/store') ?>
			<div class="row g-3 align-items-end">
				<div class="col-md-5">
					<label class="form-label"><?= t('Name (EN)') ?></label>
					<input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" required>
				</div>
				<div class="col-md-5">
					<label class="form-label"><?= t('Name (FA)') ?></label>
					<input type="text" name="name_fa" class="form-control" value="<?= set_value('name_fa') ?>">
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-dark"><?= t('add_expense_category') ?></button>
				</div>
			</div>
		<?= form_close() ?>
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
				<?php if ($categories) : ?>
					<?php foreach ($categories as $category) : ?>
						<tr>
							<td><?= html_escape($category['name']) ?></td>
							<td><?= $category['name_fa'] ? html_escape($category['name_fa']) : '&mdash;' ?></td>
							<td class="text-end">
								<div class="d-flex gap-2 justify-content-end flex-wrap">
									<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#expense-category-edit-<?= $category['id'] ?>" aria-expanded="false">
										<?= t('Edit') ?>
									</button>
									<?= form_open('preferences/expense-categories/delete/' . $category['id'], array('class' => 'd-inline')) ?>
										<button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this expense category?') ?>')">
											<?= t('Delete') ?>
										</button>
									<?= form_close() ?>
								</div>
							</td>
						</tr>
						<tr class="collapse" id="expense-category-edit-<?= $category['id'] ?>">
							<td colspan="3">
								<?= form_open('preferences/expense-categories/update/' . $category['id']) ?>
									<div class="row g-3 align-items-end">
										<div class="col-md-5">
											<label class="form-label"><?= t('Name (EN)') ?></label>
											<input type="text" name="name" class="form-control" value="<?= html_escape($category['name']) ?>" required>
										</div>
										<div class="col-md-5">
											<label class="form-label"><?= t('Name (FA)') ?></label>
											<input type="text" name="name_fa" class="form-control" value="<?= html_escape($category['name_fa']) ?>">
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
						<td colspan="3" class="text-muted"><?= t('No expense categories found.') ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
