<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-md my-5" id="receipt-container" style="max-width: 400px;">
	<div class="card">
		<div class="card-body">
			<div class="text-center mb-4 border-bottom pb-3">
				<h4><?= t('physical_therapy_clinic') ?></h4>
				<small class="text-muted"><?= t('store_receipt') ?></small>
			</div>

			<div class="mb-3">
				<p class="mb-1"><small><strong><?= t('receipt_number') ?>:</strong> #<?= (int) $sale['id'] ?></small></p>
				<p class="mb-1"><small><strong><?= t('date') ?>:</strong> <?= html_escape(to_shamsi($sale['created_at'])) ?></small></p>
				<p class="mb-0"><small><strong><?= t('time') ?>:</strong> <?= html_escape(substr($sale['created_at'], 11, 5)) ?></small></p>
			</div>

			<div class="border-bottom py-3">
				<table class="table table-sm mb-0">
					<thead>
						<tr style="font-size: 0.8rem;">
							<th><?= t('item') ?></th>
							<th class="text-end"><?= t('qty') ?></th>
							<th class="text-end"><?= t('price') ?></th>
							<th class="text-end"><?= t('total') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $item): ?>
							<tr style="font-size: 0.75rem;">
								<td><?= html_escape($item['product_name'] . ' - ' . $item['variant_label']) ?></td>
								<td class="text-end"><?= (int) $item['qty'] ?></td>
								<td class="text-end"><?= number_format($item['unit_price'], 2) ?></td>
								<td class="text-end"><?= number_format($item['line_total'], 2) ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="my-2">
				<div class="d-flex justify-content-between" style="font-size: 0.85rem;">
					<span><?= t('subtotal') ?>:</span>
					<span><?= number_format($sale['subtotal'], 2) ?> AFN</span>
				</div>
				<?php if ((float) $sale['discount'] > 0): ?>
					<div class="d-flex justify-content-between" style="font-size: 0.85rem;">
						<span><?= t('discount') ?>:</span>
						<span>-<?= number_format($sale['discount'], 2) ?> AFN</span>
					</div>
				<?php endif; ?>
				<?php if ((float) $sale['tax'] > 0): ?>
					<div class="d-flex justify-content-between" style="font-size: 0.85rem;">
						<span><?= t('tax') ?>:</span>
						<span>+<?= number_format($sale['tax'], 2) ?> AFN</span>
					</div>
				<?php endif; ?>
			</div>

			<div class="border-top border-bottom py-3 text-center">
				<h5 class="mb-0"><?= t('total') ?>: <strong><?= number_format($sale['total'], 2) ?> AFN</strong></h5>
			</div>

			<div class="my-3">
				<p class="mb-1"><small><strong><?= t('payment_method') ?>:</strong></small></p>
				<p class="mb-3" style="font-size: 0.85rem;">
					<?php
						$methods = array('cash' => t('cash'), 'card' => t('card'), 'wallet' => t('wallet'), 'prepayment' => t('prepayment'));
						echo html_escape($methods[$sale['payment_method']] ?? $sale['payment_method']);
					?>
				</p>

				<?php if ($sale['patient_id']): ?>
					<p class="mb-1"><small><strong><?= t('patient') ?>:</strong></small></p>
					<p style="font-size: 0.85rem;">
						<?php
							$patient = $this->db->get_where('patients', array('id' => $sale['patient_id']))->row_array();
							echo html_escape($patient ? ($patient['first_name'] . ' ' . ($patient['last_name'] ?? '')) : '—');
						?>
					</p>
				<?php endif; ?>
			</div>

			<div class="border-top pt-3 text-center">
				<p style="font-size: 0.75rem;" class="mb-2"><?= t('thank_you_message') ?></p>
				<small class="text-muted"><?= t('clinic_contact') ?></small>
			</div>

			<div class="mt-4 text-center">
				<button class="btn btn-sm btn-primary" onclick="window.print();"><?= t('print_receipt') ?></button>
				<a href="<?= site_url('store/sell') ?>" class="btn btn-sm btn-secondary"><?= t('new_sale') ?></a>
			</div>
		</div>
	</div>
</div>

<style>
	@media print {
		body { background: white; }
		.btn { display: none; }
		.card { border: none; box-shadow: none; }
	}
</style>
