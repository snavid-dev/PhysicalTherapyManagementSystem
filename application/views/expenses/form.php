<?php
$expense = isset($expense) ? $expense : NULL;
$read_only = !empty($read_only);
$selected_category_id = (int) set_value('category_id', $expense['category_id'] ?? 0);
$selected_staff_id = (int) set_value('staff_id', $expense['staff_id'] ?? 0);
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Record non-salary clinic expenses.') ?></p>
	</div>
	<a href="<?= base_url('expenses') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<?php if ($read_only) : ?>
	<div class="alert alert-warning"><?= t('salary_linked_expense_read_only') ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<?= form_open($action) ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label"><?= t('expense_category') ?></label>
					<select name="category_id" id="expenseCategory" class="form-select" <?= $read_only ? 'disabled' : '' ?>>
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($categories as $category) : ?>
							<?php $label = $current_locale === 'farsi' && !empty($category['name_fa']) ? $category['name_fa'] : $category['name']; ?>
							<option value="<?= $category['id'] ?>" data-is-salary="<?= $category['name'] === 'Staff Salary Payment' ? '1' : '0' ?>" <?= $selected_category_id === (int) $category['id'] ? 'selected' : '' ?>>
								<?= html_escape($label) ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php if ($read_only) : ?><input type="hidden" name="category_id" value="<?= $selected_category_id ?>"><?php endif; ?>
					<small class="text-danger"><?= form_error('category_id') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('Amount') ?></label>
					<input type="number" step="0.01" name="amount" class="form-control" value="<?= set_value('amount', $expense['amount'] ?? '') ?>" <?= $read_only ? 'readonly' : '' ?>>
					<small class="text-danger"><?= form_error('amount') ?></small>
				</div>
				<div class="col-md-3">
					<label class="form-label"><?= t('expense_date') ?></label>
					<input type="text" name="expense_date" class="form-control shamsi-date" placeholder="1403/01/01" value="<?= set_value('expense_date', isset($expense['expense_date']) ? to_shamsi($expense['expense_date']) : shamsi_today()) ?>" <?= $read_only ? 'readonly' : '' ?>>
					<small class="text-danger"><?= form_error('expense_date') ?></small>
				</div>
				<div class="col-md-6 d-none" id="staffWrap">
					<label class="form-label"><?= t('staff') ?></label>
					<select name="staff_id" class="form-select" <?= $read_only ? 'disabled' : '' ?>>
						<option value=""><?= t('Select') ?></option>
						<?php foreach ($staff_members as $staff_member) : ?>
							<option value="<?= $staff_member['id'] ?>" <?= $selected_staff_id === (int) $staff_member['id'] ? 'selected' : '' ?>>
								<?= html_escape(trim($staff_member['first_name'] . ' ' . $staff_member['last_name'])) ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php if ($read_only) : ?><input type="hidden" name="staff_id" value="<?= $selected_staff_id ?>"><?php endif; ?>
				</div>
				<div class="col-12">
					<div id="salaryCategoryAlert" class="alert alert-warning d-none mb-0"><?= t('salary_payment_blocked') ?></div>
				</div>
				<div class="col-12">
					<label class="form-label"><?= t('Description') ?></label>
					<textarea name="description" class="form-control" rows="4" <?= $read_only ? 'readonly' : '' ?>><?= set_value('description', $expense['description'] ?? '') ?></textarea>
				</div>
			</div>
			<?php if (!$read_only) : ?>
				<div class="mt-4">
					<button type="submit" class="btn btn-dark"><?= t('Save Expense') ?></button>
				</div>
			<?php endif; ?>
		<?= form_close() ?>
	</div>
</div>

<script>
(function () {
	const categorySelect = document.getElementById('expenseCategory');
	const staffWrap = document.getElementById('staffWrap');
	const alertBox = document.getElementById('salaryCategoryAlert');

	function updateExpenseFormState() {
		if (!categorySelect) {
			return;
		}

		const selectedOption = categorySelect.options[categorySelect.selectedIndex];
		const isSalary = selectedOption && selectedOption.getAttribute('data-is-salary') === '1';

		staffWrap.classList.toggle('d-none', !isSalary);
		alertBox.classList.toggle('d-none', !isSalary);
	}

	if (categorySelect) {
		categorySelect.addEventListener('change', updateExpenseFormState);
		updateExpenseFormState();
	}
})();
</script>
