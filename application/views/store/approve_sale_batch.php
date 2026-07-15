<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<h2><?= t('approve_sale_batch') ?></h2>
			<p><?= t('submitted_by') ?>: <strong><?= html_escape($batch['first_name'] . ' ' . $batch['last_name']) ?></strong> — <?= html_escape(to_shamsi($batch['created_at'])) ?></p>

			<?php if ($msg = $this->session->flashdata('error')): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?= html_escape($msg) ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			<?php endif; ?>

			<form method="post">
				<?php foreach ($customers as $customer): ?>
					<div class="card mb-3">
						<div class="card-header d-flex justify-content-between">
							<span>
								<?php if ($customer['patient_id']): ?>
									<?= html_escape($customer['first_name'] . ' ' . $customer['last_name']) ?>
								<?php else: ?>
									<?= html_escape($customer['customer_name']) ?> (<?= t('walk_in_customer') ?>)
								<?php endif; ?>
							</span>
							<span class="badge bg-secondary"><?= html_escape(t($customer['payment_method'])) ?></span>
						</div>
						<div class="card-body">
							<table class="table table-sm mb-0">
								<thead>
									<tr>
										<th><?= t('product_variant') ?></th>
										<th class="text-end"><?= t('qty') ?></th>
										<th class="text-end"><?= t('price') ?></th>
										<th class="text-end"><?= t('total') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $customer_total = 0; ?>
									<?php foreach ($customer['items'] as $item): ?>
										<?php $line = $item['qty'] * $item['unit_price']; $customer_total += $line; ?>
										<tr>
											<td><?= html_escape($item['product_name'] . ' - ' . $item['variant_label']) ?></td>
											<td class="text-end"><?= (int) $item['qty'] ?></td>
											<td class="text-end"><?= number_format($item['unit_price'], 2) ?></td>
											<td class="text-end"><?= number_format($line, 2) ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="d-flex justify-content-between align-items-center card-footer bg-transparent border-0 px-0">
					<div class="fs-5"><?= t('total_amount') ?>: <strong><?= number_format($batch['total_amount'], 2) ?> AFN</strong></div>
					<div class="d-flex gap-2">
						<button type="submit" name="action" value="approve" class="btn btn-success"><?= t('approve') ?></button>
						<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"><?= t('reject') ?></button>
						<a href="<?= site_url('store/sale_batches') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= t('reject_sale_batch') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form method="post">
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label"><?= t('reject_reason') ?></label>
						<textarea name="reject_reason" class="form-control" rows="3" required></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= t('cancel') ?></button>
					<button type="submit" name="action" value="reject" class="btn btn-danger"><?= t('reject_sale_batch') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
