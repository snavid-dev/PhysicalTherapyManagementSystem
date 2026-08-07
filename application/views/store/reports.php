<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<h1 class="mb-4"><?= t('store_reports') ?></h1>

	<?php if ($msg = $this->session->flashdata('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= html_escape($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>
	<?php if ($msg = $this->session->flashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= html_escape($msg) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>

	<form method="get" class="card mb-4">
		<div class="card-body row g-3 align-items-end">
			<div class="col-6 col-md-2">
				<label class="form-label small"><?= t('from') ?></label>
				<input type="text" name="date_from" class="form-control shamsi-date" value="<?= html_escape($filters['date_from']) ?>">
			</div>
			<div class="col-6 col-md-2">
				<label class="form-label small"><?= t('to') ?></label>
				<input type="text" name="date_to" class="form-control shamsi-date" value="<?= html_escape($filters['date_to']) ?>">
			</div>
			<div class="col-6 col-md-3">
				<label class="form-label small"><?= t('product') ?></label>
				<select name="product_id" class="form-select s2-select" data-placeholder="<?= html_escape(t('all')) ?>">
					<option value=""><?= t('all') ?></option>
					<?php foreach ($products as $product): ?>
						<option value="<?= (int) $product['id'] ?>" <?= (string) $filters['product_id'] === (string) $product['id'] ? 'selected' : '' ?>><?= html_escape($product['name']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-6 col-md-2">
				<label class="form-label small"><?= t('payment_method') ?></label>
				<select name="payment_method" class="form-select">
					<option value=""><?= t('all') ?></option>
					<?php foreach (array('cash', 'wallet', 'debt', 'card', 'prepayment') as $pm): ?>
						<option value="<?= $pm ?>" <?= $filters['payment_method'] === $pm ? 'selected' : '' ?>><?= t($pm) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-6 col-md-2">
				<label class="form-label small"><?= t('customer_type') ?></label>
				<select name="customer_type" class="form-select">
					<option value=""><?= t('all') ?></option>
					<option value="patient" <?= $filters['customer_type'] === 'patient' ? 'selected' : '' ?>><?= t('patient') ?></option>
					<option value="external" <?= $filters['customer_type'] === 'external' ? 'selected' : '' ?>><?= t('walk_in_customer') ?></option>
				</select>
			</div>
			<div class="col-6 col-md-1">
				<button type="submit" class="btn btn-primary w-100"><?= t('filter') ?></button>
			</div>
		</div>
	</form>

	<div class="row g-3 mb-4">
		<div class="col-6 col-md-3">
			<div class="card h-100"><div class="card-body">
				<div class="text-muted small"><?= t('revenue') ?></div>
				<div class="fs-4 fw-bold"><?= number_format($summary['revenue'], 2) ?></div>
			</div></div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100"><div class="card-body">
				<div class="text-muted small"><?= t('cost') ?></div>
				<div class="fs-4 fw-bold"><?= number_format($summary['cost'], 2) ?></div>
			</div></div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100"><div class="card-body">
				<div class="text-muted small"><?= t('profit') ?></div>
				<div class="fs-4 fw-bold <?= $summary['profit'] < 0 ? 'text-danger' : 'text-success' ?>"><?= number_format($summary['profit'], 2) ?></div>
			</div></div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card h-100"><div class="card-body">
				<div class="text-muted small"><?= t('total_sales_count') ?></div>
				<div class="fs-4 fw-bold"><?= (int) $summary['sales_count'] ?></div>
			</div></div>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-header"><?= t('sales_per_user') ?></div>
		<div class="card-body p-0">
			<?php if (empty($by_user)): ?>
				<div class="text-muted text-center py-3"><?= t('no_data_for_range') ?></div>
			<?php else: ?>
				<table class="table mb-0">
					<thead class="table-light"><tr><th><?= t('users') ?></th><th class="text-end"><?= t('total_amount') ?></th></tr></thead>
					<tbody>
						<?php foreach ($by_user as $row): ?>
							<tr><td><?= html_escape($row['name']) ?></td><td class="text-end"><?= number_format($row['total'], 2) ?></td></tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>

	<div class="card">
		<div class="card-header"><?= t('sales_list') ?></div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover dt-table mb-0">
					<thead class="table-light">
						<tr>
							<th><?= t('id') ?></th>
							<th><?= t('Date') ?></th>
							<th><?= t('customer') ?></th>
							<th><?= t('payment_method') ?></th>
							<th class="text-end"><?= t('total') ?></th>
							<th><?= t('sold_by') ?></th>
							<th class="no-export"><?= t('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($sales as $sale): ?>
							<tr>
								<td><?= (int) $sale['id'] ?></td>
								<td><?= html_escape(to_shamsi($sale['created_at'])) ?></td>
								<td>
									<?php if ($sale['patient_id']): ?>
										<?= html_escape(trim($sale['patient_first_name'] . ' ' . $sale['patient_last_name'])) ?>
									<?php else: ?>
										<?= html_escape($sale['customer_name'] ?: t('walk_in_customer')) ?>
									<?php endif; ?>
								</td>
								<td>
									<?= html_escape(t($sale['payment_method'])) ?>
									<?php if ($sale['payment_method'] === 'debt'): ?>
										<span class="badge bg-<?= $sale['debt_status'] === 'open' ? 'warning' : 'success' ?>"><?= html_escape(t('debt_status_' . $sale['debt_status'])) ?></span>
									<?php endif; ?>
								</td>
								<td class="text-end"><?= number_format($sale['total'], 2) ?></td>
								<td><?= html_escape($sale['first_name'] . ' ' . $sale['last_name']) ?></td>
								<td>
									<a href="<?= site_url('store/receipt/' . $sale['id']) ?>" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-receipt" aria-hidden="true"></i> <?= t('view') ?></a>
									<?php if ($sale['payment_method'] === 'debt' && $sale['debt_status'] === 'open'): ?>
										<form method="post" action="<?= site_url('store/clear_sale_debt/' . $sale['id']) ?>" class="d-inline">
											<button type="submit" class="btn btn-sm btn-outline-success btn-icon"><i class="bi bi-check-circle" aria-hidden="true"></i> <?= t('mark_debt_cleared') ?></button>
										</form>
									<?php endif; ?>
									<?php if ($sale['status'] === 'completed'): ?>
										<form method="post" action="<?= site_url('store/refund_sale/' . $sale['id']) ?>" class="d-inline" onsubmit="return confirm('<?= t('confirm_refund_sale') ?>');">
											<button type="submit" class="btn btn-sm btn-outline-danger btn-icon"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= t('refund') ?></button>
										</form>
									<?php else: ?>
										<span class="badge bg-secondary"><?= html_escape(t('sale_status_' . $sale['status'])) ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
