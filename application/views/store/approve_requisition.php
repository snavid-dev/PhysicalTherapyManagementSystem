<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<h2><?= t('approve_requisition') ?></h2>
			<p><?= t('from') ?>: <strong><?= html_escape($requisition['from_location']) ?></strong> → <strong><?= html_escape($requisition['to_location']) ?></strong></p>
			<p><?= t('requested_by') ?>: <?= html_escape($requisition['first_name'] . ' ' . $requisition['last_name']) ?></p>

			<form method="post" class="card">
				<div class="card-header">
					<h5 class="mb-0"><?= t('adjust_quantities') ?></h5>
				</div>
				<div class="card-body">
					<?php if ($msg = $this->session->flashdata('error')): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?= html_escape($msg) ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
					<?php endif; ?>

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><?= t('product') ?></th>
									<th><?= t('variant') ?></th>
									<th><?= t('qty_requested') ?></th>
									<th><?= t('warehouse_available') ?></th>
									<th><?= t('qty_approve') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($items as $item): ?>
									<?php
										$available = $this->Inventory_model->get_stock_level($item['variant_id'], $requisition['from_location_id']);
										$avail_qty = $available ? $available['qty_on_hand'] : 0;
									?>
									<tr>
										<td><?= html_escape($item['product_name']) ?></td>
										<td><?= html_escape($item['variant_label']) ?></td>
										<td><?= (int) $item['qty_requested'] ?></td>
										<td><?= (int) $avail_qty ?></td>
										<td>
											<input type="number" name="qty_approved_<?= (int) $item['id'] ?>" class="form-control" min="0" max="<?= $avail_qty ?>" value="<?= (int) $item['qty_requested'] ?>" required>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex gap-2">
						<button type="submit" name="action" value="approve" class="btn btn-success btn-icon"><i class="bi bi-check-circle" aria-hidden="true"></i> <?= t('approve_requisition') ?></button>
						<button type="button" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle" aria-hidden="true"></i> <?= t('reject') ?></button>
						<a href="<?= site_url('store/requisitions') ?>" class="btn btn-secondary btn-icon"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('cancel') ?></a>
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
				<h5 class="modal-title"><?= t('reject_requisition') ?></h5>
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
					<button type="button" class="btn btn-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('cancel') ?></button>
					<button type="submit" name="action" value="reject" class="btn btn-danger btn-icon"><i class="bi bi-x-circle" aria-hidden="true"></i> <?= t('reject_requisition') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
