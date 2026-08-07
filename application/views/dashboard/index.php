<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Dashboard') ?></h1>
		<p class="text-muted mb-0"><?= t('Overview of the physical therapy clinic.') ?></p>
	</div>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
	<?php if (isset($safe_balance) && $safe_balance !== NULL) : ?>
		<div class="col">
			<div class="card h-100 dashboard-safe-card <?= (float) $safe_balance > 0 ? 'dashboard-safe-card--positive' : 'dashboard-safe-card--neutral' ?>">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start gap-3">
						<div>
							<div class="stat-label"><?= t('safe') ?></div>
							<div class="stat-value"><?= format_number($safe_balance, 2) ?></div>
						</div>
						<div class="dashboard-safe-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18a2 2 0 0 1 2 2v1.5H6.5A2.5 2.5 0 0 0 4 11v5.5A2.5 2.5 0 0 0 6.5 19H20V7"></path>
								<path d="M20 10H17a2 2 0 0 0 0 4h3"></path>
								<circle cx="17" cy="12" r=".25"></circle>
							</svg>
						</div>
					</div>
					<a href="<?= base_url('safe') ?>" class="dashboard-safe-card__link btn-icon mt-auto pt-2"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Patients') ?></div><div class="stat-value"><?= (int) $stats['patients'] ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Users') ?></div><div class="stat-value"><?= (int) $stats['users'] ?></div></div></div></div>
	<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('Today Turns') ?></div><div class="stat-value"><?= (int) $stats['today_turns'] ?></div></div></div></div>
	<?php if ($new_patients_this_month !== NULL) : ?>
		<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('new_patients_this_month') ?></div><div class="stat-value"><?= (int) $new_patients_this_month ?></div></div></div></div>
	<?php endif; ?>
	<?php if ($expenses_this_month !== NULL) : ?>
		<div class="col"><div class="card h-100"><div class="card-body"><div class="stat-label"><?= t('this_month_expenses') ?></div><div class="stat-value"><?= format_number($expenses_this_month, 2) ?></div></div></div></div>
	<?php endif; ?>
</div>

<?php $has_pending_approvals_card = isset($pending_requisitions_count) || isset($pending_sale_batches_count); ?>
<?php if ($has_pending_approvals_card || $open_debt_summary !== NULL || $staff_on_leave !== NULL || $unpaid_salary_count !== NULL) : ?>
	<h2 class="h5 mb-3"><?= t('needs_attention') ?></h2>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
		<?php if ($has_pending_approvals_card) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<?php if (isset($pending_requisitions_count)) : ?>
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="text-muted small"><?= t('pending_requisitions') ?></span>
									<span class="badge rounded-pill <?= $pending_requisitions_count > 0 ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' ?>"><?= (int) $pending_requisitions_count ?></span>
								</div>
							<?php endif; ?>
							<?php if (isset($pending_sale_batches_count)) : ?>
								<div class="d-flex justify-content-between align-items-center">
									<span class="text-muted small"><?= t('pending_sale_batches') ?></span>
									<span class="badge rounded-pill <?= $pending_sale_batches_count > 0 ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' ?>"><?= (int) $pending_sale_batches_count ?></span>
								</div>
							<?php endif; ?>
						</div>
						<div class="mt-auto pt-2 d-flex flex-wrap gap-3">
							<?php if (isset($pending_requisitions_count)) : ?>
								<a href="<?= base_url('store/requisitions') ?>" class="btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('requisitions') ?></a>
							<?php endif; ?>
							<?php if (isset($pending_sale_batches_count)) : ?>
								<a href="<?= base_url('store/sale_batches') ?>" class="btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('sale_batches') ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($open_debt_summary !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="stat-label"><?= t('total_open_debt') ?></div>
							<div class="stat-value"><?= format_number($open_debt_summary['total_amount'], 2) ?></div>
							<div class="text-muted small mt-1"><?= (int) $open_debt_summary['patient_count'] ?> <?= t('patients_owe_total') ?></div>
						</div>
						<a href="<?= base_url('reports/outstanding-balances') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($staff_on_leave !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="text-muted small"><?= t('staff_on_leave_today') ?></span>
								<span class="badge rounded-pill <?= $staff_on_leave ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary' ?>"><?= count($staff_on_leave) ?></span>
							</div>
							<?php if ($staff_on_leave) : ?>
								<div class="small text-muted"><?= html_escape(implode(', ', array_map(static function ($staff_member) {
									return trim($staff_member['first_name'] . ' ' . ($staff_member['last_name'] ?? ''));
								}, $staff_on_leave))) ?></div>
							<?php else : ?>
								<div class="small text-muted"><?= t('no_staff_on_leave_today') ?></div>
							<?php endif; ?>
						</div>
						<a href="<?= base_url('leaves') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($unpaid_salary_count !== NULL) : ?>
			<div class="col">
				<div class="card h-100">
					<div class="card-body">
						<div>
							<div class="stat-label"><?= t('unpaid_salaries_this_month') ?></div>
							<div class="stat-value"><?= (int) $unpaid_salary_count ?></div>
							<?php if ($unpaid_salary_count === 0) : ?>
								<div class="text-muted small mt-1"><?= t('no_unpaid_salaries') ?></div>
							<?php endif; ?>
						</div>
						<a href="<?= base_url('salaries') ?>" class="mt-auto pt-2 btn-icon small"><i class="bi bi-eye" aria-hidden="true"></i> <?= t('view_details') ?></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($turns_by_section !== NULL) : ?>
	<div class="row g-3">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h2 class="h5 mb-3"><?= t('turns_by_section_today') ?></h2>
					<?php if ($turns_by_section) : ?>
						<div class="table-responsive">
							<table class="table table-sm align-middle mb-0">
								<tbody>
								<?php foreach ($turns_by_section as $row) : ?>
									<tr>
										<td><?= !empty($row['section_name']) ? html_escape(t($row['section_name'])) : '&mdash;' ?></td>
										<td class="text-end"><span class="badge rounded-pill bg-dark-subtle text-dark"><?= (int) $row['turn_count'] ?></span></td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<p class="text-muted mb-0"><?= t('No turns for today.') ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
