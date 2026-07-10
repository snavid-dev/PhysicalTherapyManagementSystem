<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<h2><?= t('receive_requisition') ?></h2>
			<p><?= t('from') ?>: <strong><?= h($requisition['from_location']) ?></strong> → <strong><?= h($requisition['to_location']) ?></strong></p>

			<form method="post" class="card">
				<div class="card-header">
					<h5 class="mb-0"><?= t('enter_received_quantities') ?></h5>
				</div>
				<div class="card-body">
					<?php if ($msg = $this->session->flashdata('error')): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?= h($msg) ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
					<?php endif; ?>

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><?= t('product') ?></th>
									<th><?= t('variant') ?></th>
									<th><?= t('qty_approved') ?></th>
									<th><?= t('qty_received') ?></th>
									<th><?= t('status') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($items as $item): ?>
									<tr>
										<td><?= h($item['product_name']) ?></td>
										<td><?= h($item['variant_label']) ?></td>
										<td><?= (int) $item['qty_approved'] ?></td>
										<td>
											<input type="number" name="qty_received_<?= (int) $item['id'] ?>" class="form-control" min="0" max="<?= (int) $item['qty_approved'] ?>" value="<?= (int) $item['qty_approved'] ?>" required>
										</td>
										<td>
											<?php if ((int) $item['qty_received'] !== (int) $item['qty_approved']): ?>
												<span class="badge bg-warning"><?= t('discrepancy') ?></span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-success"><?= t('confirm_receipt') ?></button>
						<a href="<?= site_url('store/requisitions') ?>" class="btn btn-secondary"><?= t('cancel') ?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
