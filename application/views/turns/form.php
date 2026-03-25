<?php
$selected_patient_id = (int) set_value('patient_id', $turn['patient_id'] ?? 0);
$selected_section_id = (int) set_value('section_id', $turn['section_id'] ?? 0);
$selected_staff_id = (int) set_value('staff_id', $turn['staff_id'] ?? 0);
$selected_turn_number = set_value('turn_number', $turn['turn_number'] ?? '');
$selected_fee = set_value('fee', isset($turn['fee']) ? number_format((float) $turn['fee'], 2, '.', '') : number_format((float) $default_section_fee, 2, '.', ''));
$selected_payment_type = set_value('payment_type', $turn['payment_type'] ?? 'cash');
$selected_topup = set_value('topup_amount', isset($turn['topup_amount']) ? number_format((float) $turn['topup_amount'], 2, '.', '') : '0.00');
$selected_wallet_deducted = number_format((float) ($turn['wallet_deducted'] ?? 0), 2, '.', '');
$selected_cash_collected = number_format((float) ($turn['cash_collected'] ?? 0), 2, '.', '');
$selected_date = set_value('turn_date', $turn['turn_date'] ?? date('Y-m-d'));
$selected_time = set_value('turn_time', (!empty($turn['turn_time']) && $turn['turn_time'] !== '00:00:00') ? substr($turn['turn_time'], 0, 5) : '');
$selected_status = set_value('status', $turn['status'] ?? 'accepted');
$selected_notes = set_value('notes', $turn['notes'] ?? '');
$patient_lookup = array();
$patient_display_name = static function ($patient) {
	$first_name = trim((string) ($patient['first_name'] ?? ''));
	$last_name = trim((string) ($patient['last_name'] ?? ''));
	$father_name = trim((string) ($patient['father_name'] ?? ''));

	if ($last_name !== '') {
		return trim($first_name . ' ' . $last_name);
	}

	return $father_name !== '' ? trim($first_name . ' ' . $father_name) : $first_name;
};

foreach ($patients as $patient_item) {
	$patient_lookup[(int) $patient_item['id']] = $patient_display_name($patient_item);
}

$selected_patient_name = isset($patient_lookup[$selected_patient_id]) ? $patient_lookup[$selected_patient_id] : '';
$financial_payload = array(
	'wallet_balance' => (float) $wallet_balance,
	'total_open_debt' => (float) $total_open_debt,
	'open_debts' => array_map(static function ($debt) {
		return array(
			'id' => (int) $debt['id'],
			'amount' => (float) $debt['amount'],
			'created_at' => substr((string) $debt['created_at'], 0, 10),
		);
	}, $open_debts),
);
$staff_payload = array_map(static function ($staff_member) {
	return array(
		'id' => (int) $staff_member['id'],
		'full_name' => $staff_member['full_name'],
	);
}, $staff_members);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= html_escape($title) ?></h1>
		<p class="text-muted mb-0"><?= t('Create or update a patient turn.') ?></p>
	</div>
	<a href="<?= base_url('turns') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<?= form_open($action, array('id' => 'turnForm')) ?>
			<div class="row g-4">
				<div class="col-12">
					<div class="turn-form-block">
						<div class="row g-3 align-items-end">
							<div class="col-lg-6">
								<label class="form-label"><?= t('Patient') ?></label>
								<?php if ($is_edit) : ?>
									<input type="text" class="form-control" value="<?= html_escape($selected_patient_name) ?>" readonly>
									<input type="hidden" name="patient_id" id="patientSelect" value="<?= $selected_patient_id ?>">
								<?php else : ?>
									<select name="patient_id" id="patientSelect" class="form-select">
										<option value=""><?= t('Select') ?></option>
										<?php foreach ($patients as $patient) : ?>
											<option value="<?= $patient['id'] ?>" <?= $selected_patient_id === (int) $patient['id'] ? 'selected' : '' ?>><?= html_escape($patient_display_name($patient)) ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
								<small class="text-danger"><?= form_error('patient_id') ?></small>
							</div>
							<div class="col-lg-6">
								<label class="form-label"><?= t('section') ?></label>
								<select name="section_id" id="sectionSelect" class="form-select">
									<option value=""><?= t('Select') ?></option>
									<?php foreach ($sections as $section) : ?>
										<option value="<?= $section['id'] ?>" <?= $selected_section_id === (int) $section['id'] ? 'selected' : '' ?>><?= html_escape(t($section['name'])) ?></option>
									<?php endforeach; ?>
								</select>
								<small class="text-danger"><?= form_error('section_id') ?></small>
							</div>
						</div>
						<div class="turn-financial-strip mt-3">
							<div class="turn-financial-item">
								<span class="text-muted d-block mb-1"><?= t('wallet_balance') ?></span>
								<span id="walletBalanceBadge" class="badge rounded-pill bg-secondary-subtle text-secondary"><?= format_amount($wallet_balance) ?></span>
							</div>
							<div class="turn-financial-item">
								<span class="text-muted d-block mb-1"><?= t('total_open_debt') ?></span>
								<span id="openDebtBadge" class="badge rounded-pill bg-danger-subtle text-danger"<?= $total_open_debt > 0 ? '' : ' style="display:none;"' ?>><?= format_amount($total_open_debt) ?></span>
							</div>
							<div class="turn-financial-item turn-financial-toggle" id="openDebtToggleWrap"<?= empty($open_debts) ? ' style="display:none;"' : '' ?>>
								<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#turnOpenDebtCollapse" aria-expanded="false" aria-controls="turnOpenDebtCollapse">
									<?= t('open_debts') ?>
								</button>
							</div>
						</div>
						<div class="collapse mt-3<?= empty($open_debts) ? '' : ' show' ?>" id="turnOpenDebtCollapse">
							<div class="table-responsive">
								<table class="table table-sm align-middle mb-0">
									<thead>
										<tr>
											<th>#</th>
											<th><?= t('Date') ?></th>
											<th><?= t('Amount') ?></th>
										</tr>
									</thead>
									<tbody id="openDebtTableBody">
									<?php if ($open_debts) : foreach ($open_debts as $debt) : ?>
										<tr>
											<td>#<?= (int) $debt['id'] ?></td>
											<td><?= html_escape(substr((string) $debt['created_at'], 0, 10)) ?></td>
											<td><?= format_amount($debt['amount']) ?></td>
										</tr>
									<?php endforeach; else : ?>
										<tr><td colspan="3" class="text-muted"><?= t('no_open_debt') ?></td></tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12" id="turnDetailsPanel">
					<div class="turn-form-block">
						<div class="row g-3">
							<div class="col-lg-4">
								<label class="form-label"><?= t('staff_member') ?></label>
								<select name="staff_id" id="staffSelect" class="form-select">
									<option value=""><?= t('Select') ?></option>
									<?php foreach ($staff_members as $staff_member) : ?>
										<option value="<?= $staff_member['id'] ?>" <?= $selected_staff_id === (int) $staff_member['id'] ? 'selected' : '' ?>><?= html_escape($staff_member['full_name']) ?></option>
									<?php endforeach; ?>
								</select>
								<small class="text-danger"><?= form_error('staff_id') ?></small>
							</div>
							<div class="col-lg-4">
								<label class="form-label"><?= t('turn_number') ?></label>
								<input type="number" name="turn_number" id="turnNumberInput" class="form-control" min="1" step="1" value="<?= html_escape($selected_turn_number) ?>">
								<small class="text-muted d-block mt-2"><?= t('session_number_hint') ?></small>
								<small class="text-danger"><?= form_error('turn_number') ?></small>
							</div>
							<div class="col-lg-4">
								<label class="form-label"><?= t('fee') ?></label>
								<input type="number" name="fee" id="feeInput" class="form-control" min="0" step="0.01" value="<?= html_escape($selected_fee) ?>"<?= $is_edit ? ' readonly' : '' ?>>
								<small class="text-danger"><?= form_error('fee') ?></small>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('Date') ?></label>
								<input type="date" name="turn_date" class="form-control" value="<?= html_escape($selected_date) ?>">
								<small class="text-danger"><?= form_error('turn_date') ?></small>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('Time') ?></label>
								<input type="time" name="turn_time" class="form-control" value="<?= html_escape($selected_time) ?>">
								<small class="text-danger"><?= form_error('turn_time') ?></small>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= t('Status') ?></label>
								<select name="status" class="form-select">
									<option value="accepted" <?= $selected_status === 'accepted' ? 'selected' : '' ?>><?= t('Accepted') ?></option>
									<option value="scheduled" <?= $selected_status === 'scheduled' ? 'selected' : '' ?>><?= t('Scheduled') ?></option>
									<option value="completed" <?= $selected_status === 'completed' ? 'selected' : '' ?>><?= t('Completed') ?></option>
									<option value="cancelled" <?= $selected_status === 'cancelled' ? 'selected' : '' ?>><?= t('Cancelled') ?></option>
								</select>
								<small class="text-danger"><?= form_error('status') ?></small>
							</div>
							<div class="col-12">
								<label class="form-label"><?= t('Notes') ?></label>
								<textarea name="notes" class="form-control" rows="4"><?= html_escape($selected_notes) ?></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12" id="paymentPanel">
					<div class="turn-form-block">
						<?php if ($is_edit) : ?>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label"><?= t('payment_type') ?></label>
									<input type="text" class="form-control" value="<?= html_escape(t($selected_payment_type)) ?>" readonly>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= t('top_up_amount') ?></label>
									<input type="text" class="form-control" value="<?= html_escape($selected_topup) ?>" readonly>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= t('wallet_deducted') ?></label>
									<input type="text" class="form-control" value="<?= html_escape($selected_wallet_deducted) ?>" readonly>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= t('cash_collected') ?></label>
									<input type="text" class="form-control" value="<?= html_escape($selected_cash_collected) ?>" readonly>
								</div>
								<div class="col-12">
									<div class="alert alert-secondary mb-0"><?= t('payment_locked_after_creation') ?></div>
								</div>
							</div>
						<?php else : ?>
							<div class="row g-3">
								<div class="col-12" id="topupRow">
									<div class="turn-topup-box">
										<div>
											<label class="form-label mb-1"><?= t('top_up_wallet') ?></label>
											<p class="text-muted mb-0"><?= t('wallet_balance') ?>: <strong id="topupWalletBalanceText"><?= format_amount($wallet_balance) ?></strong></p>
										</div>
										<div class="turn-topup-input">
											<input type="number" name="topup_amount" id="topupInput" class="form-control" min="0" step="0.01" value="<?= html_escape($selected_topup) ?>">
											<small class="text-muted d-block mt-2"><?= t('topup_applies_before_payment') ?></small>
											<small class="text-danger"><?= form_error('topup_amount') ?></small>
										</div>
									</div>
								</div>
								<div class="col-12">
									<label class="form-label d-block mb-3"><?= t('payment_type') ?></label>
									<div class="row g-3">
										<div class="col-md-6 col-xl-3">
											<label class="turn-payment-option">
												<input type="radio" class="form-check-input" name="payment_type" value="prepaid" <?= $selected_payment_type === 'prepaid' ? 'checked' : '' ?>>
												<span class="turn-payment-option__title"><?= t('prepaid') ?></span>
												<span class="turn-payment-option__text"><?= t('wallet_balance') ?></span>
											</label>
										</div>
										<div class="col-md-6 col-xl-3">
											<label class="turn-payment-option">
												<input type="radio" class="form-check-input" name="payment_type" value="cash" <?= $selected_payment_type === 'cash' ? 'checked' : '' ?>>
												<span class="turn-payment-option__title"><?= t('cash') ?></span>
												<span class="turn-payment-option__text"><?= t('cash_collected') ?></span>
											</label>
										</div>
										<div class="col-md-6 col-xl-3">
											<label class="turn-payment-option">
												<input type="radio" class="form-check-input" name="payment_type" value="deferred" <?= $selected_payment_type === 'deferred' ? 'checked' : '' ?>>
												<span class="turn-payment-option__title"><?= t('deferred') ?></span>
												<span class="turn-payment-option__text"><?= t('amount_becoming_debt') ?></span>
											</label>
										</div>
										<div class="col-md-6 col-xl-3">
											<label class="turn-payment-option">
												<input type="radio" class="form-check-input" name="payment_type" value="free" <?= $selected_payment_type === 'free' ? 'checked' : '' ?>>
												<span class="turn-payment-option__title"><?= t('free') ?></span>
												<span class="turn-payment-option__text"><?= t('fee') ?></span>
											</label>
										</div>
									</div>
									<small class="text-danger d-block mt-2"><?= form_error('payment_type') ?></small>
								</div>
								<div class="col-md-6" id="cashFieldWrap">
									<label class="form-label"><?= t('cash_amount') ?></label>
									<input type="text" id="cashAmountPreview" class="form-control" value="<?= html_escape($selected_fee) ?>" readonly>
								</div>
								<div class="col-12">
									<div id="paymentWarnings" class="alert alert-warning d-none mb-0"></div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-12">
					<div class="turn-summary-block">
						<h2 class="h5 mb-3"><?= t('payment_summary') ?></h2>
						<div class="turn-summary-grid">
							<div class="turn-summary-row"><span><?= t('fee') ?></span><strong id="summaryFee"><?= format_amount($selected_fee) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('top_up_amount') ?></span><strong id="summaryTopup"><?= format_amount($selected_topup) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('wallet_deducted') ?></span><strong id="summaryWalletDeducted"><?= format_amount($selected_wallet_deducted) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('cash_collected') ?></span><strong id="summaryCashCollected"><?= format_amount($selected_cash_collected) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('amount_becoming_debt') ?></span><strong id="summaryDebtAmount"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('new_wallet_balance') ?></span><strong id="summaryNewBalance"><?= format_amount($wallet_balance) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('remaining_after_deduction') ?></span><strong id="summaryRemainingAfterDeduction"><?= format_amount(0) ?></strong></div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<button type="submit" class="btn btn-dark" id="submitButton"<?= !$is_edit && (!$selected_patient_id || !$selected_section_id) ? ' disabled' : '' ?>><?= t('Save Turn') ?></button>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>

<script>
(function () {
	const form = document.getElementById('turnForm');
	if (!form) {
		return;
	}

	const isEdit = <?= $is_edit ? 'true' : 'false' ?>;
	const patientSelect = document.getElementById('patientSelect');
	const sectionSelect = document.getElementById('sectionSelect');
	const staffSelect = document.getElementById('staffSelect');
	const turnNumberInput = document.getElementById('turnNumberInput');
	const feeInput = document.getElementById('feeInput');
	const topupRow = document.getElementById('topupRow');
	const topupInput = document.getElementById('topupInput');
	const paymentPanel = document.getElementById('paymentPanel');
	const detailsPanel = document.getElementById('turnDetailsPanel');
	const submitButton = document.getElementById('submitButton');
	const cashFieldWrap = document.getElementById('cashFieldWrap');
	const cashAmountPreview = document.getElementById('cashAmountPreview');
	const warningsBox = document.getElementById('paymentWarnings');
	const walletBalanceBadge = document.getElementById('walletBalanceBadge');
	const topupWalletBalanceText = document.getElementById('topupWalletBalanceText');
	const openDebtBadge = document.getElementById('openDebtBadge');
	const openDebtToggleWrap = document.getElementById('openDebtToggleWrap');
	const openDebtTableBody = document.getElementById('openDebtTableBody');
	const summaryFee = document.getElementById('summaryFee');
	const summaryTopup = document.getElementById('summaryTopup');
	const summaryWalletDeducted = document.getElementById('summaryWalletDeducted');
	const summaryCashCollected = document.getElementById('summaryCashCollected');
	const summaryDebtAmount = document.getElementById('summaryDebtAmount');
	const summaryNewBalance = document.getElementById('summaryNewBalance');
	const summaryRemainingAfterDeduction = document.getElementById('summaryRemainingAfterDeduction');
	const selectedStaffId = <?= (int) $selected_staff_id ?>;
	const state = {
		walletBalance: <?= json_encode((float) $wallet_balance) ?>,
		totalOpenDebt: <?= json_encode((float) $total_open_debt) ?>,
		openDebts: <?= json_encode($financial_payload['open_debts'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		staff: <?= json_encode($staff_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		sessionManuallyEdited: false,
	};
	const messages = {
		noSessionNumber: <?= json_encode('—') ?>,
		noOpenDebt: <?= json_encode(t('no_open_debt')) ?>,
		noStaff: <?= json_encode(t('no_staff_for_section')) ?>,
		insufficientBalance: <?= json_encode(t('insufficient_balance')) ?>,
		cashClearsDebts: <?= json_encode(t('cash_clears_oldest_debts')) ?>,
		deferredDebt: <?= json_encode(t('full_fee_recorded_as_debt')) ?>,
		noPaymentRequired: <?= json_encode(t('no_payment_required')) ?>,
		amountBecomingDebt: <?= json_encode(t('amount_becoming_debt')) ?>,
		openDebts: <?= json_encode(t('open_debts')) ?>,
	};

	function paymentType() {
		const checked = form.querySelector('input[name="payment_type"]:checked');
		return checked ? checked.value : 'cash';
	}

	function refreshPaymentOptionState() {
		form.querySelectorAll('.turn-payment-option').forEach(function (option) {
			const input = option.querySelector('input[name="payment_type"]');
			option.classList.toggle('turn-payment-option--active', Boolean(input && input.checked));
		});
	}

	function toNumber(value) {
		const parsed = parseFloat(value);
		return Number.isFinite(parsed) ? parsed : 0;
	}

	function formatAmount(value) {
		return new Intl.NumberFormat(<?= json_encode($is_rtl ? 'fa-AF' : 'en-US') ?>, {
			minimumFractionDigits: value % 1 === 0 ? 0 : 2,
			maximumFractionDigits: 2
		}).format(value);
	}

	function clearSessionNumber() {
		if (!turnNumberInput) {
			return;
		}

		turnNumberInput.value = '';
	}

	function renderOpenDebts() {
		if (!openDebtTableBody) {
			return;
		}

		if (!state.openDebts.length) {
			openDebtTableBody.innerHTML = `<tr><td colspan="3" class="text-muted">${messages.noOpenDebt}</td></tr>`;
			if (openDebtToggleWrap) {
				openDebtToggleWrap.style.display = 'none';
			}
			return;
		}

		if (openDebtToggleWrap) {
			openDebtToggleWrap.style.display = '';
		}

		openDebtTableBody.innerHTML = state.openDebts.map(function (debt) {
			return `<tr><td>#${debt.id}</td><td>${debt.created_at}</td><td>${formatAmount(toNumber(debt.amount))}</td></tr>`;
		}).join('');
	}

	function updateFinancialBadges() {
		if (walletBalanceBadge) {
			walletBalanceBadge.textContent = formatAmount(state.walletBalance);
			walletBalanceBadge.className = state.walletBalance > 0
				? 'badge rounded-pill bg-success-subtle text-success'
				: 'badge rounded-pill bg-secondary-subtle text-secondary';
		}

		if (topupWalletBalanceText) {
			topupWalletBalanceText.textContent = formatAmount(state.walletBalance);
		}

		if (openDebtBadge) {
			if (state.totalOpenDebt > 0) {
				openDebtBadge.style.display = '';
				openDebtBadge.textContent = formatAmount(state.totalOpenDebt);
				openDebtBadge.className = 'badge rounded-pill bg-danger-subtle text-danger';
			} else {
				openDebtBadge.style.display = 'none';
			}
		}

		renderOpenDebts();
	}

	function populateStaff(selectedId) {
		if (!staffSelect) {
			return;
		}

		const options = ['<option value="">' + <?= json_encode(t('Select')) ?> + '</option>'];

		state.staff.forEach(function (staffMember) {
			const selected = Number(selectedId) === Number(staffMember.id) ? ' selected' : '';
			options.push('<option value="' + staffMember.id + '"' + selected + '>' + staffMember.full_name + '</option>');
		});

		if (!state.staff.length) {
			options.push('<option value="" disabled>' + messages.noStaff + '</option>');
		}

		staffSelect.innerHTML = options.join('');
	}

	function togglePanels() {
		const ready = isEdit || (patientSelect.value !== '' && sectionSelect.value !== '');
		detailsPanel.style.display = ready ? '' : 'none';
		paymentPanel.style.display = ready ? '' : 'none';
		submitButton.disabled = !ready;
	}

	function refreshSummary() {
		const fee = toNumber(feeInput ? feeInput.value : 0);
		const type = paymentType();
		let topup = isEdit ? toNumber(<?= json_encode((float) ($turn['topup_amount'] ?? 0)) ?>) : toNumber(topupInput ? topupInput.value : 0);
		let walletDeducted = isEdit ? toNumber(<?= json_encode((float) ($turn['wallet_deducted'] ?? 0)) ?>) : 0;
		let cashCollected = isEdit ? toNumber(<?= json_encode((float) ($turn['cash_collected'] ?? 0)) ?>) : 0;
		let debtAmount = 0;
		let newBalance = state.walletBalance;
		let remainingAfterDeduction = 0;
		const warnings = [];

		if (!isEdit) {
			if (topupRow) {
				topupRow.style.display = type === 'prepaid' ? '' : 'none';
			}

			if (cashFieldWrap) {
				cashFieldWrap.style.display = type === 'cash' ? '' : 'none';
			}

			if (cashAmountPreview) {
				cashAmountPreview.value = fee.toFixed(2);
			}

			if (type !== 'prepaid' && topupInput) {
				topup = 0;
				topupInput.value = '0.00';
			}

			const availableWallet = state.walletBalance + topup;

			switch (type) {
				case 'prepaid':
					walletDeducted = Math.min(availableWallet, fee);
					remainingAfterDeduction = Math.max(0, fee - walletDeducted);
					debtAmount = remainingAfterDeduction;
					newBalance = Math.max(0, availableWallet - walletDeducted);
					if (remainingAfterDeduction > 0) {
						warnings.push(messages.insufficientBalance + ' - ' + messages.amountBecomingDebt + ': ' + formatAmount(remainingAfterDeduction));
					}
					break;

				case 'cash':
					cashCollected = fee;
					newBalance = state.walletBalance;
					if (state.totalOpenDebt > 0) {
						warnings.push(messages.cashClearsDebts);
					}
					break;

				case 'deferred':
					debtAmount = fee;
					newBalance = state.walletBalance;
					if (fee > 0) {
						warnings.push(messages.deferredDebt);
					}
					break;

				case 'free':
					newBalance = state.walletBalance;
					warnings.push(messages.noPaymentRequired);
					break;
			}

			if (warnings.length) {
				warningsBox.classList.remove('d-none');
				warningsBox.innerHTML = warnings.join('<br>');
			} else {
				warningsBox.classList.add('d-none');
				warningsBox.textContent = '';
			}
		}

		summaryFee.textContent = formatAmount(fee);
		summaryTopup.textContent = formatAmount(topup);
		summaryWalletDeducted.textContent = formatAmount(walletDeducted);
		summaryCashCollected.textContent = formatAmount(cashCollected);
		summaryDebtAmount.textContent = formatAmount(debtAmount);
		summaryNewBalance.textContent = formatAmount(newBalance);
		summaryRemainingAfterDeduction.textContent = formatAmount(remainingAfterDeduction);
	}

	function fetchJson(url, payload, onSuccess) {
		fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: new URLSearchParams(payload)
		})
		.then(function (response) {
			if (!response.ok) {
				throw new Error('Request failed');
			}
			return response.json();
		})
		.then(onSuccess)
		.catch(function () {
			return null;
		});
	}

	function requestSessionNumber() {
		if (isEdit || !patientSelect || !sectionSelect || !turnNumberInput) {
			return;
		}

		const patientId = patientSelect.value;
		const sectionId = sectionSelect.value;

		if (!patientId || !sectionId) {
			clearSessionNumber();
			state.sessionManuallyEdited = false;
			return;
		}

		fetchJson(<?= json_encode(base_url('turns/get_session_number')) ?>, { patient_id: patientId, section_id: sectionId }, function (data) {
			if (!data || data.success !== true || state.sessionManuallyEdited) {
				return;
			}

			turnNumberInput.value = data.session_number ? String(data.session_number) : '';
		});
	}

	if (!isEdit && patientSelect) {
		patientSelect.addEventListener('change', function () {
			const patientId = this.value;
			if (!patientId) {
				state.walletBalance = 0;
				state.totalOpenDebt = 0;
				state.openDebts = [];
				clearSessionNumber();
				state.sessionManuallyEdited = false;
				updateFinancialBadges();
				refreshSummary();
				togglePanels();
				return;
			}

			clearSessionNumber();
			state.sessionManuallyEdited = false;

			fetchJson(<?= json_encode(base_url('turns/get_patient_financial')) ?>, { patient_id: patientId }, function (data) {
				state.walletBalance = toNumber(data.wallet_balance);
				state.totalOpenDebt = toNumber(data.total_open_debt);
				state.openDebts = Array.isArray(data.open_debts) ? data.open_debts : [];
				updateFinancialBadges();
				refreshSummary();
				togglePanels();
			});

			requestSessionNumber();
		});
	}

	if (sectionSelect) {
		sectionSelect.addEventListener('change', function () {
			const sectionId = this.value;
			if (!sectionId) {
				state.staff = [];
				populateStaff();
				if (!isEdit) {
					clearSessionNumber();
					state.sessionManuallyEdited = false;
				}
				togglePanels();
				refreshSummary();
				return;
			}

			fetchJson(<?= json_encode(base_url('turns/get_section_data')) ?>, { section_id: sectionId }, function (data) {
				state.staff = Array.isArray(data.staff) ? data.staff : [];
				populateStaff(isEdit ? 0 : Number(staffSelect.value || 0));

				if (!isEdit && feeInput) {
					feeInput.value = toNumber(data.fee).toFixed(2);
				}

				togglePanels();
				refreshSummary();
			});

			if (!isEdit) {
				clearSessionNumber();
				state.sessionManuallyEdited = false;
				requestSessionNumber();
			}
		});
	}

	if (!isEdit) {
		form.querySelectorAll('input[name="payment_type"]').forEach(function (input) {
			input.addEventListener('change', function () {
				refreshPaymentOptionState();
				refreshSummary();
			});
		});

		if (feeInput) {
			feeInput.addEventListener('input', refreshSummary);
		}

		if (topupInput) {
			topupInput.addEventListener('input', refreshSummary);
		}

		if (turnNumberInput) {
			turnNumberInput.addEventListener('input', function () {
				state.sessionManuallyEdited = true;
			});
		}
	}

	updateFinancialBadges();
	populateStaff(selectedStaffId);
	togglePanels();
	refreshPaymentOptionState();
	refreshSummary();
})();
</script>
