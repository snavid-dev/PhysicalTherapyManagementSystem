<?php $category_label = static function ($category) use ($current_locale) {
	return $current_locale === 'farsi' && !empty($category['name_fa']) ? $category['name_fa'] : $category['name'];
}; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= t('expenses') ?></h1>
		<p class="text-muted mb-0"><?= t('Track clinic expenses and salary payments.') ?></p>
	</div>
	<a href="<?= base_url('expenses/create') ?>" class="btn btn-dark"><?= t('add_expense') ?></a>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form method="get" action="<?= base_url('expenses') ?>">
			<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label"><?= t('expense_category') ?></label>
					<select name="category_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($categories as $category) : ?>
							<option value="<?= $category['id'] ?>" <?= (int) $filters['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
								<?= html_escape($category_label($category)) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label"><?= t('date_from') ?></label>
					<input type="text" name="date_from" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($filters['date_from']) ?>">
				</div>
				<div class="col-md-2">
					<label class="form-label"><?= t('date_to') ?></label>
					<input type="text" name="date_to" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= html_escape($filters['date_to']) ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('staff') ?></label>
					<select name="staff_id" class="form-select">
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($staff_members as $staff_member) : ?>
							<option value="<?= $staff_member['id'] ?>" <?= (int) $filters['staff_id'] === (int) $staff_member['id'] ? 'selected' : '' ?>>
								<?= html_escape(trim($staff_member['first_name'] . ' ' . $staff_member['last_name'])) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2 d-flex gap-2 align-items-end">
					<button type="submit" class="btn btn-dark w-100"><?= t('Apply') ?></button>
					<a href="<?= base_url('expenses') ?>" class="btn btn-outline-secondary w-100"><?= t('Reset') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle dt-table" data-order-col="0" data-order-dir="desc" data-no-sort-cols="5">
				<thead>
					<tr>
						<th><?= t('Date') ?></th>
						<th><?= t('expense_category') ?></th>
						<th><?= t('staff') ?></th>
						<th><?= t('Amount') ?></th>
						<th><?= t('Description') ?></th>
						<th class="no-export text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($expenses) : ?>
					<?php foreach ($expenses as $expense) : ?>
						<?php $is_salary_linked = !empty($expense['salary_payment_id']); ?>
						<tr>
							<td><?= html_escape(to_shamsi($expense['expense_date'])) ?></td>
							<td><?= html_escape($category_label(array('name' => $expense['category_name'], 'name_fa' => $expense['category_name_fa']))) ?></td>
							<td>
								<?php if (!empty($expense['staff_first_name']) || !empty($expense['staff_last_name'])) : ?>
									<?= html_escape(trim($expense['staff_first_name'] . ' ' . $expense['staff_last_name'])) ?>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
							<td><?= format_number($expense['amount'], 2) ?></td>
							<td><?= $expense['description'] ? html_escape($expense['description']) : '&mdash;' ?></td>
							<td class="no-export text-end">
								<div class="d-flex gap-2 justify-content-end flex-wrap">
									<a href="<?= base_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
									<?php if ($is_salary_linked) : ?>
										<button type="button" class="btn btn-sm btn-outline-danger" disabled><?= t('Delete') ?></button>
									<?php else : ?>
										<a href="<?= base_url('expenses/delete/' . $expense['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this expense?') ?>')"><?= t('Delete') ?></a>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
				<?php if ($expenses) : ?>
					<tfoot>
						<tr>
							<td colspan="3" class="fw-semibold"><?= t('Total:') ?></td>
							<td class="fw-semibold"><?= format_number($total_amount, 2) ?></td>
							<td colspan="2"></td>
						</tr>
					</tfoot>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
