<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($patient['first_name'] . ' ' . $patient['last_name']) ?></h1>
		<p class="text-muted mb-0"><?= t('Patient Profile') ?></p>
	</div>
<?php $can_record_payment = $this->auth->has_permission('manage_turns'); ?>
	<div class="d-flex flex-wrap gap-2">
		<a href="<?= base_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-outline-secondary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
		<a href="<?= base_url('patients') ?>" class="btn btn-outline-dark btn-icon"><i class="bi bi-arrow-left icon-flip-rtl" aria-hidden="true"></i> <?= t('Back') ?></a>
	</div>
</div>

<?php $father_name = $patient['father_name'] ?? NULL; ?>
<?php $age = $patient['age'] ?? NULL; ?>
<?php $phone = $patient['phone'] ?? NULL; ?>
<?php $phone2 = $patient['phone2'] ?? NULL; ?>
<?php $address = $patient['address'] ?? NULL; ?>
<?php $medical_notes = $patient['medical_notes'] ?? NULL; ?>
<?php $referred_by = $patient['referred_by'] ?? NULL; ?>
<?php $referred_by_name = $patient['referred_by_name'] ?? NULL; ?>
<?php
$display_time = static function ($time_value) {
	$time_value = (string) $time_value;
	return ($time_value === '' || $time_value === '00:00:00') ? '&mdash;' : html_escape(substr($time_value, 0, 5));
};
$financial_summary = is_array($financial_summary ?? NULL) ? $financial_summary : array();
$financial_timeline = is_array($financial_timeline ?? NULL) ? $financial_timeline : array();
$wallet_breakdown = is_array($wallet_breakdown ?? NULL) ? $wallet_breakdown : array();
$discounts = is_array($discounts ?? NULL) ? $discounts : array();
$all_sections = is_array($all_sections ?? NULL) ? $all_sections : array();
$discounts_payload = json_encode($discounts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$wallet_transaction_meta = static function ($transaction) {
	$type = (string) ($transaction['type'] ?? '');
	$fund_type = (string) ($transaction['fund_type'] ?? 'cash_topup');
	$note = (string) ($transaction['note'] ?? '');

	// An internal reversal (e.g. a cancelled/edited turn undoing its own wallet
	// effect) is not money the patient paid in or was refunded — it's bookkeeping.
	// Wallet_model::reversal_note() always prefixes these with 'REVERSAL:'. Shown
	// with the same badge as a real cash top-up, staff have mistaken it for a
	// payment and double-subtracted it from the patient's debt total.
	if (strpos($note, 'REVERSAL:') === 0) {
		return array(
			'class' => 'bg-secondary-subtle text-secondary',
			'label' => t('wallet_correction'),
			'prefix' => $type === 'topup' ? '+' : '-',
		);
	}

	if ($type === 'auto_debt_settlement') {
		return array(
			'class' => 'bg-primary-subtle text-primary',
			'label' => t('auto_debt_settlement'),
			'prefix' => '-',
		);
	}

	if ($type === 'refund') {
		return array(
			'class' => 'bg-danger-subtle text-danger',
			'label' => t('refund'),
			'prefix' => '-',
		);
	}

	if ($type === 'topup' && $fund_type === 'historical_credit') {
		return array(
			'class' => 'bg-info-subtle text-info',
			'label' => t('historical_wallet_credit'),
			'prefix' => '+',
		);
	}

	if ($type === 'topup') {
		return array(
			'class' => 'bg-success-subtle text-success',
			'label' => t('cash_wallet_topup'),
			'prefix' => '+',
		);
	}

	if ($fund_type === 'historical_credit') {
		return array(
			'class' => 'bg-warning-subtle text-warning',
			'label' => t('historical_wallet_deduction'),
			'prefix' => '-',
		);
	}

	return array(
		'class' => 'bg-warning-subtle text-warning',
		'label' => t('cash_wallet_deduction'),
		'prefix' => '-',
	);
};
$turns = is_array($turns ?? NULL) ? $turns : array();
$turn_history = $turns;
usort($turn_history, static function ($left, $right) {
	$left_number = isset($left['turn_number']) && $left['turn_number'] !== NULL ? (int) $left['turn_number'] : PHP_INT_MAX;
	$right_number = isset($right['turn_number']) && $right['turn_number'] !== NULL ? (int) $right['turn_number'] : PHP_INT_MAX;

	if ($left_number !== $right_number) {
		return $left_number <=> $right_number;
	}

	$left_date = (string) ($left['turn_date'] ?? '');
	$right_date = (string) ($right['turn_date'] ?? '');
	if ($left_date !== $right_date) {
		return strcmp($left_date, $right_date);
	}

	return ((int) ($left['id'] ?? 0)) <=> ((int) ($right['id'] ?? 0));
});
$can_edit_turn = $this->auth->has_permission('manage_turns');
?>

<div class="row g-4">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Profile Details') ?></h2>
				<dl class="row mb-0">
					<dt class="col-5"><?= t('First Name') ?></dt><dd class="col-7"><?= html_escape($patient['first_name']) ?></dd>
					<dt class="col-5"><?= t('Last Name') ?></dt><dd class="col-7"><?= html_escape($patient['last_name']) ?></dd>
					<dt class="col-5"><?= t('father_name') ?></dt><dd class="col-7"><?= $father_name ? html_escape($father_name) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Gender') ?></dt><dd class="col-7"><?= !empty($patient['gender']) ? html_escape($patient['gender']) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('age') ?></dt><dd class="col-7"><?= $age !== NULL ? format_number($age) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Phone 1') ?></dt><dd class="col-7"><?= $phone ? html_escape($phone) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('phone2') ?></dt><dd class="col-7"><?= $phone2 ? html_escape($phone2) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Address') ?></dt><dd class="col-7"><?= $address ? nl2br(html_escape($address)) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Medical Notes') ?></dt><dd class="col-7"><?= $medical_notes ? nl2br(html_escape($medical_notes)) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('Referred By') ?></dt><dd class="col-7"><?= $referred_by ? html_escape($referred_by_name) : '&mdash;' ?></dd>
					<dt class="col-5"><?= t('diagnoses') ?></dt>
					<dd class="col-7">
						<?php if (!empty($patient_diagnoses)) : ?>
							<?php
							$diagnosis_names = array_map(static function ($diagnosis) use ($is_rtl) {
								return $is_rtl && !empty($diagnosis['name_fa']) ? $diagnosis['name_fa'] : $diagnosis['name'];
							}, $patient_diagnoses);
							?>
							<?= html_escape(implode(', ', $diagnosis_names)) ?>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card financial-report-card mb-4">
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
					<div>
						<h2 class="h5 mb-1"><?= t('money') ?></h2>
						<p class="text-muted mb-0"><?= t('money_hint') ?></p>
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-sm-6">
						<div class="financial-summary-card h-100">
							<span class="financial-summary-card__label"><?= t('wallet_balance') ?></span>
							<strong id="moneyWalletBalance" class="financial-summary-card__value fs-3"><?= format_amount($wallet_balance) ?></strong>
							<span class="text-muted small d-block mt-1"><?= t('wallet_balance_hint') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="financial-summary-card h-100<?= $total_open_debt > 0 ? ' financial-summary-card--danger' : '' ?>">
							<span class="financial-summary-card__label"><?= t('amount_owed') ?></span>
							<strong id="moneyAmountOwed" class="financial-summary-card__value fs-3"><?= format_amount($total_open_debt) ?></strong>
							<span class="text-muted small d-block mt-1"><?= t('amount_owed_hint') ?></span>
						</div>
					</div>
				</div>

				<?php if ($can_record_payment) : ?>
					<div class="d-flex flex-wrap gap-2 mb-4">
						<button type="button" class="btn btn-dark btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#walletTopupModal" data-tooltip="1" title="<?= html_escape(t('wallet_action_hint')) ?>">
							<i class="bi bi-wallet2" aria-hidden="true"></i> <?= t('wallet_topup_action') ?>
						</button>
						<button type="button" class="btn btn-outline-info btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#walletHistoricalCreditModal" data-tooltip="1" title="<?= html_escape(t('historical_wallet_credit_hint')) ?>">
							<i class="bi bi-clock-history" aria-hidden="true"></i> <?= t('wallet_legacy_credit_action') ?>
						</button>
						<button type="button" class="btn btn-outline-secondary btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#walletDeductModal" data-tooltip="1" title="<?= html_escape(t('wallet_deduction_hint')) ?>">
							<i class="bi bi-dash-circle" aria-hidden="true"></i> <?= t('wallet_deduct_action') ?>
						</button>
						<button type="button" class="btn btn-danger btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#recordPaymentModal" data-tooltip="1" title="<?= html_escape(t('debt_payment_hint')) ?>">
							<i class="bi bi-cash-coin" aria-hidden="true"></i> <?= t('record_payment') ?>
						</button>
						<button id="openRefundModalBtn" type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#refundModal" data-tooltip="1" title="<?= html_escape(t('refund_hint')) ?>" <?= ((float) $wallet_balance) <= 0 ? 'disabled' : '' ?>>
							<i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= t('refund') ?>
						</button>
					</div>
				<?php endif; ?>

				<ul class="nav nav-pills financial-report-tabs flex-wrap mb-3" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="money-breakdown-tab" data-bs-toggle="pill" data-bs-target="#money-breakdown-pane" type="button" role="tab" aria-controls="money-breakdown-pane" aria-selected="true"><?= t('balance_breakdown') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="money-wallet-tab" data-bs-toggle="pill" data-bs-target="#money-wallet-pane" type="button" role="tab" aria-controls="money-wallet-pane" aria-selected="false"><?= t('wallet_history_tab') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="money-debts-tab" data-bs-toggle="pill" data-bs-target="#money-debts-pane" type="button" role="tab" aria-controls="money-debts-pane" aria-selected="false"><?= t('debts_history') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="money-payments-tab" data-bs-toggle="pill" data-bs-target="#money-payments-pane" type="button" role="tab" aria-controls="money-payments-pane" aria-selected="false"><?= t('payment_refund_history') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="money-department-tab" data-bs-toggle="pill" data-bs-target="#money-department-pane" type="button" role="tab" aria-controls="money-department-pane" aria-selected="false"><?= t('paid_by_department') ?></button>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade show active" id="money-breakdown-pane" role="tabpanel" aria-labelledby="money-breakdown-tab" tabindex="0">
						<div class="table-responsive">
							<table class="table table-sm align-middle mb-0">
								<tbody>
									<tr>
										<td><?= t('real_wallet_balance') ?></td>
										<td id="moneyCashWalletValue" class="text-end"><?= format_amount($wallet_breakdown['cash_topup'] ?? 0) ?></td>
									</tr>
									<tr>
										<td><?= t('historical_wallet_balance') ?></td>
										<td id="moneyLegacyCreditValue" class="text-end"><?= format_amount($wallet_breakdown['historical_credit'] ?? 0) ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="money-wallet-pane" role="tabpanel" aria-labelledby="money-wallet-tab" tabindex="0">
						<div class="table-responsive">
							<table id="patientWalletTransactionsTable" class="table table-sm align-middle mb-0 dt-table" data-order-col="0" data-order-dir="desc" data-no-export="true" data-col-widths='["26%","18%","14%","42%"]'>
								<thead>
									<tr>
										<th class="col-date"><?= t('date_time') ?></th>
										<th><?= t('wallet_action') ?></th>
										<th><?= t('Amount') ?></th>
										<th class="col-text"><?= t('wallet_note') ?></th>
									</tr>
								</thead>
								<tbody id="walletTransactionsBody">
								<?php if ($wallet_transactions) : foreach ($wallet_transactions as $transaction) : ?>
									<?php $meta = $wallet_transaction_meta($transaction); ?>
									<tr>
										<td class="col-date"><?= html_escape(to_shamsi($transaction['created_at'], 'Y/m/d H:i')) ?></td>
										<td><span class="badge rounded-pill <?= $meta['class'] ?>"><?= html_escape($meta['label']) ?></span></td>
										<td><?= html_escape($meta['prefix']) . format_amount($transaction['amount']) ?></td>
										<td class="col-text"><?= !empty($transaction['note']) ? html_escape($transaction['note']) : (!empty($transaction['turn_id']) ? '#' . (int) $transaction['turn_id'] : '&mdash;') ?></td>
									</tr>
								<?php endforeach; endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="money-debts-pane" role="tabpanel" aria-labelledby="money-debts-tab" tabindex="0">
						<p class="text-muted small mb-3"><?= t('debt_payment_hint') ?></p>
						<div id="debtPaymentFeedback" class="alert d-none mb-3"></div>
						<div class="table-responsive">
							<table class="table table-sm align-middle mb-0">
								<thead>
									<tr>
										<th><?= t('Date') ?></th>
										<th><?= t('Turns') ?></th>
										<th><?= t('Amount') ?></th>
										<th><?= t('debt_type') ?></th>
										<th><?= t('Status') ?></th>
									</tr>
								</thead>
								<tbody id="openDebtTableBody">
								<?php if ($open_debts) : foreach ($open_debts as $debt) : ?>
									<?php
									$turn_label = '#' . (int) $debt['turn_id'];
									if (!empty($debt['section_name'])) {
										$turn_label .= ' - ' . t($debt['section_name']);
									}
									$debt_type_value = (string) ($debt['debt_type'] ?? 'auto_settleable');
									$debt_type_class = $debt_type_value === 'manual_only' ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info';
									$debt_type_label = $debt_type_value === 'manual_only' ? t('debt_type_manual_only') : t('debt_type_auto_settleable');
									$debt_status_value = (string) ($debt['status'] ?? 'open');
									$debt_status_class = $debt_status_value === 'cleared' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
									$debt_status_label = $debt_status_value === 'cleared' ? t('debt_status_cleared') : t('debt_status_open');
									?>
									<tr>
										<td><?= html_escape(to_shamsi($debt['debt_date'])) ?></td>
										<td>
											<?php if ($can_edit_turn) : ?>
												<a href="<?= base_url('turns/' . (int) $debt['turn_id'] . '/edit') ?>"><?= html_escape($turn_label) ?></a>
											<?php else : ?>
												<?= html_escape($turn_label) ?>
											<?php endif; ?>
										</td>
										<td><?= format_amount($debt['amount']) ?></td>
										<td><span class="badge rounded-pill <?= $debt_type_class ?>"><?= html_escape($debt_type_label) ?></span></td>
										<td><span class="badge rounded-pill <?= $debt_status_class ?>"><?= html_escape($debt_status_label) ?></span></td>
									</tr>
								<?php endforeach; else : ?>
									<tr><td colspan="5" class="text-muted"><?= t('no_open_debt') ?></td></tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="money-payments-pane" role="tabpanel" aria-labelledby="money-payments-tab" tabindex="0">
						<div class="table-responsive">
							<table class="table table-sm align-middle mb-0">
								<thead>
									<tr>
										<th><?= t('date_time') ?></th>
										<th><?= t('wallet_action') ?></th>
										<th><?= t('Amount') ?></th>
										<th><?= t('section') ?></th>
										<th><?= t('staff_member') ?></th>
										<th><?= t('wallet_note') ?></th>
										<th class="text-end"><?= t('Actions') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php if ($standalone_payments) : foreach ($standalone_payments as $entry) : ?>
									<?php
									$kind_badge = array(
										'debt_payment' => array('bg-success-subtle text-success', t('pay_debt')),
										'refund' => array('bg-danger-subtle text-danger', t('refund')),
										'no_turn_payment' => array('bg-info-subtle text-info', t('payment_without_turn')),
									)[$entry['kind']] ?? array('bg-secondary-subtle text-secondary', $entry['kind']);
									$can_manage_entry = $entry['kind'] !== 'no_turn_payment';
									$edit_url = $entry['edit_kind'] === 'payment'
										? base_url('patients/' . $patient['id'] . '/payments/' . $entry['id'] . '/edit')
										: base_url('patients/' . $patient['id'] . '/refunds/' . $entry['id'] . '/edit');
									$delete_url = $entry['edit_kind'] === 'payment'
										? base_url('patients/' . $patient['id'] . '/payments/' . $entry['id'] . '/delete')
										: base_url('patients/' . $patient['id'] . '/refunds/' . $entry['id'] . '/delete');
									?>
									<tr>
										<td class="col-date"><?= html_escape($entry['occurred_at']) ?></td>
										<td><span class="badge rounded-pill <?= $kind_badge[0] ?>"><?= html_escape($kind_badge[1]) ?></span></td>
										<td><?= format_amount($entry['amount']) ?></td>
										<td><?= html_escape($entry['section_name'] ?: '&mdash;') ?></td>
										<td><?= html_escape($entry['staff_name'] ?: '&mdash;') ?></td>
										<td><?= !empty($entry['note']) ? html_escape($entry['note']) : '&mdash;' ?></td>
										<td class="text-end">
											<?php if ($can_manage_entry) : ?>
												<button type="button" class="btn btn-sm btn-outline-secondary btn-icon standalone-payment-edit-btn"
													data-edit-url="<?= html_escape($edit_url) ?>"
													data-note="<?= html_escape((string) $entry['note']) ?>"
													data-section-id="<?= (int) $entry['section_id'] ?>"
													data-staff-id="<?= (int) $entry['staff_id'] ?>"
												><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></button>
												<form action="<?= html_escape($delete_url) ?>" method="post" class="d-inline" onsubmit="return confirm('<?= html_escape(t('confirm_delete')) ?>');">
													<button type="submit" class="btn btn-sm btn-outline-danger btn-icon"><i class="bi bi-trash" aria-hidden="true"></i> <?= t('Delete') ?></button>
												</form>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; else : ?>
									<tr><td colspan="7" class="text-muted"><?= t('no_financial_entries') ?></td></tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="money-department-pane" role="tabpanel" aria-labelledby="money-department-tab" tabindex="0">
						<?php $paid_by_section = $financial_summary['paid_by_section'] ?? array(); ?>
						<?php if ($paid_by_section) : ?>
							<div class="table-responsive">
								<table class="table table-sm align-middle mb-0">
									<thead>
										<tr>
											<th><?= t('section') ?></th>
											<th><?= t('Paid') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($paid_by_section as $row) : ?>
										<tr>
											<td><?= !empty($row['section_name']) ? html_escape(t($row['section_name'])) : '&mdash;' ?></td>
											<td><?= format_amount($row['paid']) ?></td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php else : ?>
							<p class="text-muted mb-0"><?= t('no_financial_entries') ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="standalonePaymentEditModal" tabindex="-1" aria-labelledby="standalonePaymentEditModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h2 class="modal-title h5 mb-0" id="standalonePaymentEditModalLabel"><?= t('Edit') ?></h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
					</div>
					<form id="standalonePaymentEditForm" method="post">
						<div class="modal-body">
							<div class="mb-3">
								<label class="form-label"><?= t('section') ?></label>
								<select name="section_id" id="standalonePaymentEditSection" class="form-select s2-select" required>
									<option value=""><?= t('Select') ?></option>
									<?php foreach ($all_sections as $section) : ?>
										<option value="<?= (int) $section['id'] ?>"><?= html_escape(t($section['name'])) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= t('staff_member') ?></label>
								<select name="staff_id" id="standalonePaymentEditStaff" class="form-select s2-select" required>
									<option value=""><?= t('Select') ?></option>
									<?php foreach ($all_staff as $staff_member) : ?>
										<option value="<?= (int) $staff_member['id'] ?>"><?= html_escape(trim($staff_member['first_name'] . ' ' . ($staff_member['last_name'] ?? ''))) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="mb-0">
								<label class="form-label"><?= t('wallet_note') ?></label>
								<input type="text" name="note" id="standalonePaymentEditNote" class="form-control" maxlength="255">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
							<button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-check-lg" aria-hidden="true"></i> <?= t('Save') ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script>
		(function () {
			const modalEl = document.getElementById('standalonePaymentEditModal');
			if (!modalEl) {
				return;
			}
			const form = document.getElementById('standalonePaymentEditForm');
			const sectionSelect = document.getElementById('standalonePaymentEditSection');
			const staffSelect = document.getElementById('standalonePaymentEditStaff');
			const noteInput = document.getElementById('standalonePaymentEditNote');
			const modal = new bootstrap.Modal(modalEl);

			document.querySelectorAll('.standalone-payment-edit-btn').forEach(function (button) {
				button.addEventListener('click', function () {
					form.action = button.dataset.editUrl;
					sectionSelect.value = button.dataset.sectionId && button.dataset.sectionId !== '0' ? button.dataset.sectionId : '';
					staffSelect.value = button.dataset.staffId && button.dataset.staffId !== '0' ? button.dataset.staffId : '';
					noteInput.value = button.dataset.note || '';
					modal.show();
				});
			});
		})();
		</script>

		<div class="card mb-4">
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
					<div>
						<h2 class="h5 mb-1"><?= t('discounts') ?></h2>
					</div>
					<button type="button" class="btn btn-dark btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#discountModal"><i class="bi bi-plus-lg" aria-hidden="true"></i> <?= t('add_discount') ?></button>
				</div>
				<div id="discountFeedback" class="alert d-none mb-3"></div>
				<div id="patientDiscountsContent"></div>
			</div>
		</div>
		<div class="card mb-4">
			<div class="card-body">
				<h2 class="h5 mb-3"><?= t('Turn History') ?></h2>
				<div class="table-responsive">
					<table id="patientTurnHistoryTable" class="table dt-table" data-order-col="0" data-order-dir="asc" data-no-export="true" data-col-widths='["9%","13%","16%","18%","11%","14%","9%","10%"]'>
						<thead><tr><th><?= t('turn_number') ?></th><th class="col-date"><?= t('Date') ?></th><th><?= t('section') ?></th><th><?= t('staff_member') ?></th><th><?= t('payment_type') ?></th><th><?= t('wallet_details') ?></th><th><?= t('fee') ?></th><th class="no-export text-end"><?= t('Actions') ?></th></tr></thead>
						<tbody>
						<?php if ($turn_history) : foreach ($turn_history as $turn) : ?>
							<?php $staff_name = !empty($turn['staff_full_name']) ? $turn['staff_full_name'] : ($turn['doctor_full_name'] ?? ''); ?>
							<?php $topup_amount = (float) ($turn['topup_amount'] ?? 0); ?>
							<?php $wallet_used = (float) ($turn['wallet_deducted'] ?? 0); ?>
							<tr>
								<td><?= !empty($turn['turn_number']) ? format_number($turn['turn_number']) : '&mdash;' ?></td>
								<td class="col-date"><?= html_escape(to_shamsi($turn['turn_date'])) ?></td>
								<td><?= !empty($turn['section_name']) ? html_escape(t($turn['section_name'])) : '&mdash;' ?></td>
								<td><?= $staff_name !== '' ? html_escape($staff_name) : '&mdash;' ?></td>
								<td><?= html_escape(t($turn['payment_type'] ?? 'cash')) ?></td>
								<td>
									<?php if ($topup_amount > 0 || $wallet_used > 0) : ?>
										<?php if ($topup_amount > 0) : ?>
											<div><?= t('top_up_amount') ?>: <?= format_amount($topup_amount) ?></div>
										<?php endif; ?>
										<?php if ($wallet_used > 0) : ?>
											<div><?= t('wallet_used') ?>: <?= format_amount($wallet_used) ?></div>
										<?php endif; ?>
									<?php else : ?>
										&mdash;
									<?php endif; ?>
								</td>
								<td><?= format_amount($turn['fee'] ?? 0) ?></td>
								<td class="no-export text-end">
									<?php if ($can_edit_turn) : ?>
										<a href="<?= base_url('turns/' . $turn['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> <?= t('Edit') ?></a>
									<?php else : ?>
										&mdash;
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="recordPaymentModalLabel"><?= t('record_payment') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="recordPaymentForm" action="<?= base_url('patients/' . $patient['id'] . '/debt-payment') ?>" method="post">
				<div class="modal-body">
					<div id="recordPaymentModalFeedback" class="alert d-none mb-3"></div>
					<p class="text-muted small mb-3"><?= t('record_payment_hint') ?></p>
					<div class="mb-3">
						<label class="form-label"><?= t('debt_payment_amount') ?></label>
						<input type="number" name="amount" id="recordPaymentAmount" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
						<small class="text-muted"><?= t('total_open_debt') ?>: <span id="recordPaymentOpenDebt"><?= format_amount($total_open_debt) ?></span></small>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('payment_date') ?></label>
						<input type="text" name="payment_date" class="form-control shamsi-date" value="<?= html_escape(shamsi_today()) ?>" placeholder="<?= t('month_format_hint') ?>">
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('section') ?></label>
						<select name="section_id" class="form-select s2-select" required>
							<option value=""><?= t('Select') ?></option>
							<?php foreach ($all_sections as $section) : ?>
								<option value="<?= (int) $section['id'] ?>"><?= html_escape(t($section['name'])) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('staff_member') ?></label>
						<select name="staff_id" class="form-select s2-select" required>
							<option value=""><?= t('Select') ?></option>
							<?php foreach ($all_staff as $staff_member) : ?>
								<option value="<?= (int) $staff_member['id'] ?>"><?= html_escape(trim($staff_member['first_name'] . ' ' . ($staff_member['last_name'] ?? ''))) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('wallet_note') ?></label>
						<input type="text" name="note" class="form-control" maxlength="255" placeholder="<?= t('debt_note_placeholder') ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-success btn-icon"><i class="bi bi-cash-coin" aria-hidden="true"></i> <?= t('record_payment') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="refundModalLabel"><?= t('refund') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="refundForm" action="<?= base_url('patients/' . $patient['id'] . '/refund') ?>" method="post">
				<div class="modal-body">
					<div id="refundModalFeedback" class="alert d-none mb-3"></div>
					<p class="text-muted small mb-3"><?= t('refund_hint') ?></p>
					<div class="mb-3">
						<label class="form-label"><?= t('refund_amount') ?></label>
						<input type="number" name="amount" id="refundAmount" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
						<small class="text-muted"><?= t('wallet_balance') ?>: <span id="refundMaxAmount"><?= format_amount($wallet_balance) ?></span></small>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('refund_date') ?></label>
						<input type="text" name="refund_date" class="form-control shamsi-date" value="<?= html_escape(shamsi_today()) ?>" placeholder="<?= t('month_format_hint') ?>">
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('section') ?></label>
						<select name="section_id" class="form-select s2-select" required>
							<option value=""><?= t('Select') ?></option>
							<?php foreach ($all_sections as $section) : ?>
								<option value="<?= (int) $section['id'] ?>"><?= html_escape(t($section['name'])) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('staff_member') ?></label>
						<select name="staff_id" class="form-select s2-select" required>
							<option value=""><?= t('Select') ?></option>
							<?php foreach ($all_staff as $staff_member) : ?>
								<option value="<?= (int) $staff_member['id'] ?>"><?= html_escape(trim($staff_member['first_name'] . ' ' . ($staff_member['last_name'] ?? ''))) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('wallet_note') ?></label>
						<input type="text" name="note" class="form-control" maxlength="255" placeholder="<?= t('refund_note_placeholder') ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-warning btn-icon"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= t('refund') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="walletTopupModal" tabindex="-1" aria-labelledby="walletTopupModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="walletTopupModalLabel"><?= t('wallet_topup_action') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="walletTopupForm" action="<?= base_url('patients/' . $patient['id'] . '/wallet-topup') ?>" method="post">
				<div class="modal-body">
					<div id="walletTopupFeedback" class="alert d-none mb-3"></div>
					<p class="text-muted small mb-3"><?= t('wallet_action_hint') ?></p>
					<div class="mb-3">
						<label class="form-label"><?= t('real_wallet_deposit_amount') ?></label>
						<input type="number" name="amount" id="walletTopupAmount" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
					</div>
					<div class="wallet-quick-actions mb-3">
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletTopupAmount" data-amount="100">+<?= format_amount(100) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletTopupAmount" data-amount="250">+<?= format_amount(250) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletTopupAmount" data-amount="500">+<?= format_amount(500) ?></button>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('wallet_note') ?></label>
						<input type="text" name="note" id="walletTopupNote" class="form-control" placeholder="<?= t('wallet_note_placeholder') ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-dark btn-icon"><i class="bi bi-wallet2" aria-hidden="true"></i> <?= t('wallet_topup_action') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="walletHistoricalCreditModal" tabindex="-1" aria-labelledby="walletHistoricalCreditModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="walletHistoricalCreditModalLabel"><?= t('wallet_legacy_credit_action') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="walletHistoricalCreditForm" action="<?= base_url('patients/' . $patient['id'] . '/wallet-historical-credit') ?>" method="post">
				<div class="modal-body">
					<div id="walletHistoricalCreditFeedback" class="alert d-none mb-3"></div>
					<p class="text-muted small mb-3"><?= t('historical_wallet_credit_hint') ?></p>
					<div class="mb-3">
						<label class="form-label"><?= t('historical_wallet_credit_amount') ?></label>
						<input type="number" name="amount" id="walletHistoricalCreditAmount" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
					</div>
					<div class="wallet-quick-actions mb-3">
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletHistoricalCreditAmount" data-amount="100">+<?= format_amount(100) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletHistoricalCreditAmount" data-amount="250">+<?= format_amount(250) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletHistoricalCreditAmount" data-amount="500">+<?= format_amount(500) ?></button>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('wallet_note') ?></label>
						<input type="text" name="note" id="walletHistoricalCreditNote" class="form-control" placeholder="<?= t('historical_wallet_note_placeholder') ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-outline-info btn-icon"><i class="bi bi-clock-history" aria-hidden="true"></i> <?= t('wallet_legacy_credit_action') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="walletDeductModal" tabindex="-1" aria-labelledby="walletDeductModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="walletDeductModalLabel"><?= t('wallet_deduct_action') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="walletDeductForm" action="<?= base_url('patients/' . $patient['id'] . '/wallet-deduct') ?>" method="post">
				<div class="modal-body">
					<div id="walletDeductFeedback" class="alert d-none mb-3"></div>
					<p class="text-muted small mb-3"><?= t('wallet_deduction_hint') ?></p>
					<div class="mb-3">
						<label class="form-label"><?= t('Amount') ?></label>
						<input type="number" name="amount" id="walletDeductAmount" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
					</div>
					<div class="wallet-quick-actions mb-3">
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletDeductAmount" data-amount="100">-<?= format_amount(100) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletDeductAmount" data-amount="250">-<?= format_amount(250) ?></button>
						<button type="button" class="btn btn-sm btn-outline-secondary wallet-quick-amount" data-target="walletDeductAmount" data-amount="500">-<?= format_amount(500) ?></button>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('wallet_note') ?></label>
						<input type="text" name="note" id="walletDeductNote" class="form-control" placeholder="<?= t('wallet_note_placeholder') ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-outline-dark btn-icon"><i class="bi bi-dash-circle" aria-hidden="true"></i> <?= t('wallet_deduct_action') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="discountModalLabel"><?= t('add_discount') ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
			</div>
			<form id="discountForm" action="<?= base_url('patients/add-discount/' . $patient['id']) ?>" method="post">
				<div class="modal-body">
					<div id="discountModalFeedback" class="alert d-none mb-3"></div>
					<div class="mb-3">
						<label class="form-label"><?= t('section') ?></label>
						<select name="section_id" class="form-select s2-select" required>
							<option value=""><?= t('Select') ?></option>
							<?php foreach ($all_sections as $section) : ?>
								<option value="<?= (int) $section['id'] ?>"><?= html_escape(t($section['name'])) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('discount_percent') ?></label>
						<input type="number" name="discount_percent" id="discountPercentInput" class="form-control" min="0.01" max="100" step="0.01">
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('discount_amount') ?></label>
						<input type="number" name="discount_amount" id="discountAmountInput" class="form-control" min="0.01" step="0.01">
						<small class="text-muted"><?= t('discount_value_hint') ?></small>
					</div>
					<div class="mb-0">
						<label class="form-label"><?= t('Notes') ?></label>
						<input type="text" name="note" class="form-control" maxlength="255">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-icon" data-bs-dismiss="modal"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= t('Close') ?></button>
					<button type="submit" class="btn btn-dark btn-icon"><i class="bi bi-check-lg" aria-hidden="true"></i> <?= t('save_discount') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
(function () {
	const walletTopupForm = document.getElementById('walletTopupForm');
	if (!walletTopupForm) {
		return;
	}

	const transactionsBody = document.getElementById('walletTransactionsBody');
	const openDebtTableBody = document.getElementById('openDebtTableBody');
	const quickButtons = document.querySelectorAll('.wallet-quick-amount');
	const financialSummaryFields = {
		wallet_balance: document.getElementById('moneyWalletBalance'),
		total_open_debt: document.getElementById('moneyAmountOwed'),
		cash_wallet_balance: document.getElementById('moneyCashWalletValue'),
		historical_wallet_balance: document.getElementById('moneyLegacyCreditValue')
	};
	const canEditTurn = <?= json_encode((bool) $can_edit_turn) ?>;
	const turnEditBaseUrl = <?= json_encode(base_url('turns/')) ?>;
	const labels = {
		noOpenDebt: <?= json_encode(t('no_open_debt')) ?>,
		cashTopup: <?= json_encode(t('cash_wallet_topup')) ?>,
		walletCorrection: <?= json_encode(t('wallet_correction')) ?>,
		historicalCredit: <?= json_encode(t('historical_wallet_credit')) ?>,
		cashDeduction: <?= json_encode(t('cash_wallet_deduction')) ?>,
		historicalDeduction: <?= json_encode(t('historical_wallet_deduction')) ?>,
		autoDebtSettlement: <?= json_encode(t('auto_debt_settlement')) ?>,
		refund: <?= json_encode(t('refund')) ?>,
		debtTypeAutoSettleable: <?= json_encode(t('debt_type_auto_settleable')) ?>,
		debtTypeManualOnly: <?= json_encode(t('debt_type_manual_only')) ?>,
		debtStatusOpen: <?= json_encode(t('debt_status_open')) ?>,
		debtStatusCleared: <?= json_encode(t('debt_status_cleared')) ?>,
	};

	function formatAmount(value) {
		return new Intl.NumberFormat(<?= json_encode($is_rtl ? 'fa-AF' : 'en-US') ?>, {
			minimumFractionDigits: value % 1 === 0 ? 0 : 2,
			maximumFractionDigits: 2
		}).format(value);
	}

	function escapeHtml(value) {
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function activateTab(tabButtonId) {
		const button = document.getElementById(tabButtonId);
		if (button && window.bootstrap && window.bootstrap.Tab) {
			window.bootstrap.Tab.getOrCreateInstance(button).show();
		}
	}

	function updateFinancialSummary(summary) {
		if (!summary || typeof summary !== 'object') {
			return;
		}

		Object.keys(financialSummaryFields).forEach(function (key) {
			const field = financialSummaryFields[key];
			if (!field) {
				return;
			}
			field.textContent = formatAmount(parseFloat(summary[key] || 0));
		});

		const debtCard = document.getElementById('moneyAmountOwed');
		const debtCardWrapper = debtCard ? debtCard.closest('.financial-summary-card') : null;
		if (debtCardWrapper) {
			debtCardWrapper.classList.toggle('financial-summary-card--danger', parseFloat(summary.total_open_debt || 0) > 0);
		}
	}

	function renderTransactions(transactions) {
		if (!transactionsBody) {
			return;
		}

		if (!transactions.length) {
			transactionsBody.innerHTML = '';
			if (window.CANINDataTables) {
				window.CANINDataTables.refreshTable('#patientWalletTransactionsTable');
			}
			return;
		}

		transactionsBody.innerHTML = transactions.map(function (transaction) {
			const type = transaction.type;
			const isTopup = type === 'topup';
			const fundType = transaction.fund_type || 'cash_topup';
			const rawNote = transaction.note || '';
			let badgeClass = 'bg-warning-subtle text-warning';
			let label = fundType === 'historical_credit' ? labels.historicalDeduction : labels.cashDeduction;
			let prefix = isTopup ? '+' : '-';

			if (rawNote.indexOf('REVERSAL:') === 0) {
				// Internal bookkeeping (e.g. a cancelled/edited turn undoing its own
				// wallet effect) — not a payment or refund. Kept distinct from a real
				// cash top-up so staff don't mistake it for money in and double-count it.
				badgeClass = 'bg-secondary-subtle text-secondary';
				label = labels.walletCorrection;
			} else if (type === 'auto_debt_settlement') {
				badgeClass = 'bg-primary-subtle text-primary';
				label = labels.autoDebtSettlement;
				prefix = '-';
			} else if (type === 'refund') {
				badgeClass = 'bg-danger-subtle text-danger';
				label = labels.refund;
				prefix = '-';
			} else if (isTopup && fundType === 'historical_credit') {
				badgeClass = 'bg-info-subtle text-info';
				label = labels.historicalCredit;
			} else if (isTopup) {
				badgeClass = 'bg-success-subtle text-success';
				label = labels.cashTopup;
			}

			const note = transaction.note ? escapeHtml(transaction.note) : (transaction.turn_id ? ('#' + transaction.turn_id) : '&mdash;');
			return '<tr>'
				+ '<td class="col-date">' + escapeHtml(transaction.created_at || '') + '</td>'
				+ '<td><span class="badge rounded-pill ' + badgeClass + '">' + escapeHtml(label) + '</span></td>'
				+ '<td>' + prefix + formatAmount(parseFloat(transaction.amount || 0)) + '</td>'
				+ '<td class="col-text">' + note + '</td>'
				+ '</tr>';
		}).join('');

		if (window.CANINDataTables) {
			window.CANINDataTables.refreshTable('#patientWalletTransactionsTable');
		}
	}

	function renderOpenDebts(debts) {
		if (!openDebtTableBody) {
			return;
		}

		if (!debts.length) {
			openDebtTableBody.innerHTML = '<tr><td colspan="5" class="text-muted">' + labels.noOpenDebt + '</td></tr>';
			return;
		}

		openDebtTableBody.innerHTML = debts.map(function (debt) {
			const parts = ['#' + debt.turn_id];
			if (debt.section_name) {
				parts.push(debt.section_name);
			}
			const turnLabel = escapeHtml(parts.join(' - '));
			const turnCell = canEditTurn
				? '<a href="' + turnEditBaseUrl + debt.turn_id + '/edit">' + turnLabel + '</a>'
				: turnLabel;
			const debtType = String(debt.debt_type || 'auto_settleable');
			const debtTypeClass = debtType === 'manual_only' ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info';
			const debtTypeLabel = debtType === 'manual_only' ? labels.debtTypeManualOnly : labels.debtTypeAutoSettleable;
			const debtStatus = String(debt.status || 'open');
			const debtStatusClass = debtStatus === 'cleared' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
			const debtStatusLabel = debtStatus === 'cleared' ? labels.debtStatusCleared : labels.debtStatusOpen;
			return '<tr>'
				+ '<td>' + escapeHtml(debt.debt_date || '') + '</td>'
				+ '<td>' + turnCell + '</td>'
				+ '<td>' + formatAmount(parseFloat(debt.amount || 0)) + '</td>'
				+ '<td><span class="badge rounded-pill ' + debtTypeClass + '">' + escapeHtml(debtTypeLabel) + '</span></td>'
				+ '<td><span class="badge rounded-pill ' + debtStatusClass + '">' + escapeHtml(debtStatusLabel) + '</span></td>'
				+ '</tr>';
		}).join('');
	}

	function showFeedback(element, message, isError) {
		if (!element) {
			return;
		}

		element.className = 'alert mt-3 mb-0 ' + (isError ? 'alert-danger' : 'alert-success');
		element.classList.remove('d-none');
		element.textContent = message;
	}

	function handleJsonResponse(response, fallbackMessage) {
		return response.text().then(function (text) {
			let data = {};
			try {
				data = JSON.parse(text);
			} catch (error) {
				data = { success: false, message: text || fallbackMessage };
			}
			return { ok: response.ok, data: data };
		});
	}

	function submitProfileForm(form, feedbackElement, onSuccess, fallbackMessage) {
		if (!form) {
			return;
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			const formData = new URLSearchParams(new FormData(form));

			fetch(form.action, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'Accept': 'application/json'
				},
				body: formData
			})
			.then(function (response) {
				return handleJsonResponse(response, fallbackMessage);
			})
			.then(function (result) {
				showFeedback(feedbackElement, result.data.message || '', !result.ok || result.data.success === false);

				if (!result.ok || result.data.success === false) {
					return;
				}

				onSuccess(result.data);
			})
			.catch(function () {
				showFeedback(feedbackElement, fallbackMessage, true);
			});
		});
	}

	quickButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			const target = document.getElementById(this.dataset.target);
			if (!target) {
				return;
			}
			target.value = this.dataset.amount;
			target.focus();
		});
	});

	const walletTopupModalEl = document.getElementById('walletTopupModal');
	const walletHistoricalCreditModalEl = document.getElementById('walletHistoricalCreditModal');
	const walletDeductModalEl = document.getElementById('walletDeductModal');

	submitProfileForm(
		document.getElementById('walletTopupForm'),
		document.getElementById('walletTopupFeedback'),
		function (data) {
			renderTransactions(Array.isArray(data.wallet_transactions) ? data.wallet_transactions : []);
			updateFinancialSummary(data.financial_summary);
			document.getElementById('walletTopupAmount').value = '';
			document.getElementById('walletTopupNote').value = '';
			closeModal(walletTopupModalEl);
			activateTab('money-wallet-tab');
		},
		<?= json_encode(t('Unable to update wallet right now.')) ?>
	);

	submitProfileForm(
		document.getElementById('walletHistoricalCreditForm'),
		document.getElementById('walletHistoricalCreditFeedback'),
		function (data) {
			renderTransactions(Array.isArray(data.wallet_transactions) ? data.wallet_transactions : []);
			updateFinancialSummary(data.financial_summary);
			document.getElementById('walletHistoricalCreditAmount').value = '';
			document.getElementById('walletHistoricalCreditNote').value = '';
			closeModal(walletHistoricalCreditModalEl);
			activateTab('money-wallet-tab');
		},
		<?= json_encode(t('Unable to update wallet right now.')) ?>
	);

	submitProfileForm(
		document.getElementById('walletDeductForm'),
		document.getElementById('walletDeductFeedback'),
		function (data) {
			renderTransactions(Array.isArray(data.wallet_transactions) ? data.wallet_transactions : []);
			updateFinancialSummary(data.financial_summary);
			document.getElementById('walletDeductAmount').value = '';
			document.getElementById('walletDeductNote').value = '';
			closeModal(walletDeductModalEl);
			activateTab('money-wallet-tab');
		},
		<?= json_encode(t('Unable to update wallet right now.')) ?>
	);

	const recordPaymentModalEl = document.getElementById('recordPaymentModal');
	const refundModalEl = document.getElementById('refundModal');
	const refundOpenButton = document.getElementById('openRefundModalBtn');
	const recordPaymentOpenDebtEl = document.getElementById('recordPaymentOpenDebt');
	const refundMaxAmountEl = document.getElementById('refundMaxAmount');
	const recordPaymentAmount = document.getElementById('recordPaymentAmount');
	const refundAmount = document.getElementById('refundAmount');

	function refreshFinancialBadges(data) {
		renderOpenDebts(Array.isArray(data.open_debts) ? data.open_debts : []);
		renderTransactions(Array.isArray(data.wallet_transactions) ? data.wallet_transactions : []);
		updateFinancialSummary(data.financial_summary);

		if (recordPaymentOpenDebtEl) {
			recordPaymentOpenDebtEl.textContent = formatAmount(parseFloat(data.total_open_debt || 0));
		}

		if (refundMaxAmountEl) {
			refundMaxAmountEl.textContent = formatAmount(parseFloat(data.wallet_balance || 0));
		}

		if (refundAmount) {
			refundAmount.max = String(Math.max(0, parseFloat(data.wallet_balance || 0)));
		}

		if (refundOpenButton) {
			const walletBalance = parseFloat(data.wallet_balance || 0);
			if (walletBalance > 0) {
				refundOpenButton.disabled = false;
				refundOpenButton.removeAttribute('disabled');
			} else {
				refundOpenButton.disabled = true;
				refundOpenButton.setAttribute('disabled', 'disabled');
			}
		}
	}

	function closeModal(modalEl) {
		if (!modalEl || !window.bootstrap || !window.bootstrap.Modal) {
			return;
		}
		// getOrCreateInstance handles the case where Bootstrap never promoted the
		// data-toggle button (e.g., after a previous modal close left state behind).
		const instance = window.bootstrap.Modal.getOrCreateInstance(modalEl);
		instance.hide();
		// Defensive: scrub any leftover backdrop / modal-open class that can block
		// further clicks if Bootstrap's hide animation was interrupted.
		setTimeout(function () {
			document.querySelectorAll('.modal-backdrop').forEach(function (b) {
				if (!document.querySelector('.modal.show')) {
					b.remove();
				}
			});
			if (!document.querySelector('.modal.show')) {
				document.body.classList.remove('modal-open');
				document.body.style.removeProperty('padding-right');
				document.body.style.removeProperty('overflow');
			}
		}, 350);
	}

	submitProfileForm(
		document.getElementById('recordPaymentForm'),
		document.getElementById('recordPaymentModalFeedback'),
		function (data) {
			refreshFinancialBadges(data);
			document.getElementById('recordPaymentForm').reset();
			closeModal(recordPaymentModalEl);
			activateTab('money-debts-tab');
		},
		<?= json_encode(t('Unable to record debt payment right now.')) ?>
	);

	submitProfileForm(
		document.getElementById('refundForm'),
		document.getElementById('refundModalFeedback'),
		function (data) {
			refreshFinancialBadges(data);
			document.getElementById('refundForm').reset();
			closeModal(refundModalEl);
			activateTab('money-wallet-tab');
		},
		<?= json_encode(t('Unable to record refund right now.')) ?>
	);

	if (recordPaymentAmount) {
		recordPaymentAmount.addEventListener('focus', function () {
			recordPaymentAmount.select();
		});
	}

})();
</script>

<script>
(function () {
	const container = document.getElementById('patientDiscountsContent');
	if (!container) {
		return;
	}

	const form = document.getElementById('discountForm');
	const feedback = document.getElementById('discountFeedback');
	const modalFeedback = document.getElementById('discountModalFeedback');
	const modalElement = document.getElementById('discountModal');
	const discountPercentInput = document.getElementById('discountPercentInput');
	const discountAmountInput = document.getElementById('discountAmountInput');
	const deleteUrlBase = <?= json_encode(base_url('patients/delete-discount/' . $patient['id'] . '/')) ?>;
	let discounts = <?= $discounts_payload ?: '[]' ?>;

	const labels = {
		noDiscounts: <?= json_encode(t('no_discounts')) ?>,
		section: <?= json_encode(t('section')) ?>,
		discountPercent: <?= json_encode(t('discount_percent')) ?>,
		discountAmount: <?= json_encode(t('discount_amount')) ?>,
		note: <?= json_encode(t('Notes')) ?>,
		dateAdded: <?= json_encode(t('date_added')) ?>,
		active: <?= json_encode(t('active_discount')) ?>,
		superseded: <?= json_encode(t('superseded_discount')) ?>,
		actions: <?= json_encode(t('Actions')) ?>,
		delete: <?= json_encode(t('Delete')) ?>,
		deleteConfirm: <?= json_encode(t('delete_discount_confirm')) ?>,
		discountInvalid: <?= json_encode(t('discount_invalid')) ?>,
		fallbackError: <?= json_encode(t('unable_to_save_discount')) ?>,
		fallbackDeleteError: <?= json_encode(t('unable_to_delete_discount')) ?>,
	};

	function escapeHtml(value) {
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function formatNumber(value) {
		return new Intl.NumberFormat(<?= json_encode($is_rtl ? 'fa-AF' : 'en-US') ?>, {
			minimumFractionDigits: value % 1 === 0 ? 0 : 2,
			maximumFractionDigits: 2
		}).format(value);
	}

	function showFeedback(element, message, isError) {
		if (!element) {
			return;
		}

		element.className = 'alert mb-3 ' + (isError ? 'alert-danger' : 'alert-success');
		element.classList.remove('d-none');
		element.textContent = message;
	}

	function clearFeedback(element) {
		if (!element) {
			return;
		}

		element.className = 'alert d-none mb-3';
		element.textContent = '';
	}

	function hasDiscountValue() {
		const discountPercent = parseFloat((discountPercentInput && discountPercentInput.value) || 0);
		const discountAmount = parseFloat((discountAmountInput && discountAmountInput.value) || 0);
		return discountPercent > 0 || discountAmount > 0;
	}

	function handleJsonResponse(response, fallbackMessage) {
		return response.text().then(function (text) {
			let data = {};
			try {
				data = JSON.parse(text);
			} catch (error) {
				data = { success: false, message: text || fallbackMessage };
			}
			return { ok: response.ok, data: data };
		});
	}

	function renderDiscounts() {
		if (!Array.isArray(discounts) || !discounts.length) {
			container.innerHTML = '<div class="alert alert-light border mb-0">' + escapeHtml(labels.noDiscounts) + '</div>';
			return;
		}

		container.innerHTML = '<div class="table-responsive"><table class="table align-middle mb-0">'
			+ '<thead><tr>'
			+ '<th>' + escapeHtml(labels.section) + '</th>'
			+ '<th>' + escapeHtml(labels.discountPercent) + '</th>'
			+ '<th>' + escapeHtml(labels.discountAmount) + '</th>'
			+ '<th>' + escapeHtml(labels.note) + '</th>'
			+ '<th>' + escapeHtml(labels.dateAdded) + '</th>'
			+ '<th>' + escapeHtml(labels.active) + '</th>'
			+ '<th>' + escapeHtml(labels.actions) + '</th>'
			+ '</tr></thead><tbody>'
			+ discounts.map(function (discount) {
				const statusBadge = discount.is_active
					? '<span class="badge rounded-pill bg-success-subtle text-success">' + escapeHtml(labels.active) + '</span>'
					: '<span class="badge rounded-pill bg-secondary-subtle text-secondary">' + escapeHtml(labels.superseded) + '</span>';

				return '<tr>'
					+ '<td>' + escapeHtml(discount.section_label || discount.section_name || '') + '</td>'
					+ '<td>' + escapeHtml(formatNumber(parseFloat(discount.discount_percent || 0))) + '%</td>'
					+ '<td>' + escapeHtml(formatNumber(parseFloat(discount.discount_amount || 0))) + '</td>'
					+ '<td>' + (discount.note ? escapeHtml(discount.note) : '&mdash;') + '</td>'
					+ '<td>' + escapeHtml(window.formatShamsiDate ? window.formatShamsiDate(discount.created_at || '', 'YYYY/MM/DD HH:mm') : (discount.created_at || '')) + '</td>'
					+ '<td>' + statusBadge + '</td>'
					+ '<td><button type="button" class="btn btn-sm btn-outline-danger btn-icon" data-discount-delete="1" data-discount-id="' + escapeHtml(discount.id) + '" data-url="' + escapeHtml(deleteUrlBase + discount.id) + '"><i class="bi bi-trash" aria-hidden="true"></i> ' + escapeHtml(labels.delete) + '</button></td>'
					+ '</tr>';
			}).join('')
			+ '</tbody></table></div>';
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		clearFeedback(modalFeedback);

		if (!hasDiscountValue()) {
			showFeedback(modalFeedback, labels.discountInvalid, true);
			return;
		}

		fetch(form.action, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'Accept': 'application/json'
			},
			body: new URLSearchParams(new FormData(form))
		})
		.then(function (response) {
			return handleJsonResponse(response, labels.fallbackError);
		})
		.then(function (result) {
			if (!result.ok || result.data.success === false) {
				showFeedback(modalFeedback, result.data.message || labels.fallbackError, true);
				return;
			}

			discounts = Array.isArray(result.data.discounts) ? result.data.discounts : [];
			renderDiscounts();
			form.reset();
			clearFeedback(modalFeedback);
			showFeedback(feedback, result.data.message || '', false);

			if (modalElement && window.bootstrap && window.bootstrap.Modal) {
				window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
			}
		})
		.catch(function () {
			showFeedback(modalFeedback, labels.fallbackError, true);
		});
	});

	container.addEventListener('click', function (event) {
		const button = event.target.closest('[data-discount-delete]');
		if (!button) {
			return;
		}

		if (!window.confirm(labels.deleteConfirm)) {
			return;
		}

		fetch(button.dataset.url, {
			method: 'POST',
			headers: {
				'Accept': 'application/json'
			}
		})
		.then(function (response) {
			return handleJsonResponse(response, labels.fallbackDeleteError);
		})
		.then(function (result) {
			if (!result.ok || result.data.success === false) {
				showFeedback(feedback, result.data.message || labels.fallbackDeleteError, true);
				return;
			}

			discounts = Array.isArray(result.data.discounts) ? result.data.discounts : [];
			renderDiscounts();
			showFeedback(feedback, result.data.message || '', false);
		})
		.catch(function () {
			showFeedback(feedback, labels.fallbackDeleteError, true);
		});
	});

	if (modalElement) {
		modalElement.addEventListener('hidden.bs.modal', function () {
			clearFeedback(modalFeedback);
		});
	}

	if (discountPercentInput && discountAmountInput) {
		discountPercentInput.addEventListener('input', function () {
			if (parseFloat(this.value || 0) > 0) {
				discountAmountInput.value = '';
			}
		});

		discountAmountInput.addEventListener('input', function () {
			if (parseFloat(this.value || 0) > 0) {
				discountPercentInput.value = '';
			}
		});
	}

	renderDiscounts();
})();
</script>
