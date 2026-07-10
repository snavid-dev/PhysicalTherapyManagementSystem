<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-lg my-5">
	<div class="row mb-4">
		<div class="col">
			<h1><?= t('requisitions') ?></h1>
		</div>
		<?php if ($this->auth->has_permission('manage_store')): ?>
		<div class="col-auto">
			<a href="<?= site_url('store/create_requisition') ?>" class="btn btn-primary"><?= t('create_requisition') ?></a>
		</div>
		<?php endif; ?>
	</div>

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

	<?php if (empty($requisitions)): ?>
		<div class="alert alert-info"><?= t('no_requisitions_found') ?></div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table table-hover dt-table">
				<thead class="table-light">
					<tr>
						<th><?= t('id') ?></th>
						<th><?= t('from_location') ?></th>
						<th><?= t('to_location') ?></th>
						<th><?= t('requested_by') ?></th>
						<th><?= t('Status') ?></th>
						<th><?= t('Date') ?></th>
						<th><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($requisitions as $req): ?>
						<tr>
							<td><?= (int) $req['id'] ?></td>
							<td><?= html_escape($req['from_location']) ?></td>
							<td><?= html_escape($req['to_location']) ?></td>
							<td><?= html_escape($req['first_name'] . ' ' . $req['last_name']) ?></td>
							<td>
								<span class="badge bg-<?= $req['status'] === 'pending' ? 'warning' : ($req['status'] === 'approved' || $req['status'] === 'in_transit' ? 'info' : ($req['status'] === 'received' ? 'success' : 'secondary')) ?>">
									<?= html_escape($req['status']) ?>
								</span>
							</td>
							<td><?= html_escape(to_shamsi($req['created_at'])) ?></td>
							<td>
								<?php if ($req['status'] === 'pending' && $this->auth->has_permission('approve_store_requisition')): ?>
									<a href="<?= site_url('store/approve_requisition/' . $req['id']) ?>" class="btn btn-sm btn-outline-warning"><?= t('approve') ?></a>
								<?php elseif ($req['status'] === 'in_transit' && $this->auth->has_permission('manage_store')): ?>
									<a href="<?= site_url('store/receive_requisition/' . $req['id']) ?>" class="btn btn-sm btn-outline-success"><?= t('receive') ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
