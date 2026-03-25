<?php
$shared_input = isset($shared_input) && is_array($shared_input) ? $shared_input : array();
$shared_input = array_merge(array(
	'section_id' => 0,
	'date' => date('Y-m-d'),
	'status' => 'accepted',
), $shared_input);

$submitted_turns = isset($submitted_turns) && is_array($submitted_turns) ? array_values($submitted_turns) : array();
$shared_errors = isset($shared_errors) && is_array($shared_errors) ? $shared_errors : array();
$row_errors = isset($row_errors) && is_array($row_errors) ? $row_errors : array();
$patient_display_name = static function ($patient) {
	$first_name = trim((string) ($patient['first_name'] ?? ''));
	$last_name = trim((string) ($patient['last_name'] ?? ''));
	$father_name = trim((string) ($patient['father_name'] ?? ''));

	if ($last_name !== '') {
		return trim($first_name . ' ' . $last_name);
	}

	return $father_name !== '' ? trim($first_name . ' ' . $father_name) : $first_name;
};

$patients_payload = array_map(static function ($patient) use ($patient_display_name) {
	return array(
		'id' => (int) $patient['id'],
		'name' => $patient_display_name($patient),
	);
}, $patients);

$initial_rows = array_map(static function ($row) {
	return array(
		'patient_id' => (string) ($row['patient_id'] ?? ''),
		'staff_id' => (string) ($row['staff_id'] ?? ''),
		'turn_number' => (string) ($row['turn_number'] ?? ''),
		'fee' => (string) ($row['fee'] ?? ''),
		'payment_type' => (string) ($row['payment_type'] ?? 'cash'),
		'topup_amount' => (string) ($row['topup_amount'] ?? '0'),
		'notes' => (string) ($row['notes'] ?? ''),
	);
}, $submitted_turns);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('bulk_turns') ?></h1>
		<p class="text-muted mb-0"><?= t('Add many turns for one day from a single screen.') ?></p>
	</div>
	<a href="<?= base_url('turns') ?>" class="btn btn-outline-dark"><?= t('Back') ?></a>
</div>

<?= form_open('turns/bulk/store', array('id' => 'bulkTurnsForm')) ?>
<input type="hidden" name="status" value="accepted">

<div class="card mb-4">
	<div class="card-body">
		<?php if ($shared_errors) : ?>
			<div class="alert alert-danger">
				<ul class="mb-0 ps-3">
					<?php foreach ($shared_errors as $error_message) : ?>
						<li><?= html_escape($error_message) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="row g-3 align-items-end">
			<div class="col-lg-4">
				<label class="form-label"><?= t('section') ?></label>
				<select name="section_id" id="bulkSectionSelect" class="form-select" required>
					<option value=""><?= t('Select') ?></option>
					<?php foreach ($sections as $section) : ?>
						<option value="<?= $section['id'] ?>" <?= (int) $shared_input['section_id'] === (int) $section['id'] ? 'selected' : '' ?>>
							<?= html_escape(t($section['name'])) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-lg-3">
				<label class="form-label"><?= t('Date') ?></label>
				<input type="date" name="date" id="bulkDateInput" class="form-control" value="<?= html_escape($shared_input['date']) ?>" required>
			</div>
			<div class="col-lg-3">
				<div class="bulk-turn-counter text-muted">
					<strong id="bulkRowCount">0</strong>
					<span><?= t('patients_added') ?></span>
				</div>
				<div class="small text-muted"><?= t('Status') ?>: <?= t('Accepted') ?></div>
			</div>
			<div class="col-lg-2">
				<label class="form-label"><?= t('row_count') ?></label>
				<input type="number" id="bulkRowAddCount" class="form-control" min="1" value="1">
			</div>
			<div class="col-lg-3">
				<div class="d-grid gap-2">
					<button type="button" class="btn btn-dark" id="addBulkRowButton"><?= t('add_row') ?></button>
					<button type="button" class="btn btn-outline-dark" id="addBulkRowsButton"><?= t('add_rows') ?></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
	<div class="text-muted small"><?= t('payment_summary') ?>: <?= t('Each row is saved with accepted status and the shared date above.') ?></div>
	<button type="submit" class="btn btn-dark"><?= t('Save All Turns') ?></button>
</div>

<div id="bulkRows" class="bulk-turn-rows"></div>
<?= form_close() ?>

<template id="bulkTurnRowTemplate">
	<div class="card bulk-turn-row mb-4" data-index="__INDEX__" data-fee-edited="0">
		<div class="card-header d-flex justify-content-between align-items-center gap-3">
			<div class="d-flex align-items-center gap-3 flex-wrap">
				<button type="button" class="btn btn-sm btn-outline-secondary bulk-toggle-row" aria-expanded="true">
					<span class="bulk-toggle-label"><?= t('Collapse') ?></span>
				</button>
				<div>
					<div class="fw-semibold bulk-row-title"><?= t('row_number') ?> __ROW_NUMBER__</div>
					<div class="text-muted small bulk-row-summary-title"><?= t('Patient') ?>: <?= t('Select') ?> | <?= t('fee') ?>: 0 | <?= t('payment_type') ?>: <?= t('cash') ?></div>
				</div>
			</div>
			<button type="button" class="btn btn-sm btn-outline-danger bulk-remove-row" aria-label="<?= t('remove_row') ?>">&times;</button>
		</div>
		<div class="card-body bulk-row-body">
			<div class="bulk-row-errors d-none alert alert-danger py-2 px-3 mb-3"></div>

			<div class="row g-4">
				<div class="col-xl-6">
					<div class="row g-3">
						<div class="col-12">
							<label class="form-label"><?= t('Patient') ?></label>
							<select class="form-select bulk-patient-select"></select>
						</div>
						<div class="col-md-6">
							<label class="form-label"><?= t('turn_number') ?></label>
							<input type="number" min="1" step="1" class="form-control bulk-session-input">
							<small class="text-muted d-block mt-2"><?= t('session_number_hint') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label"><?= t('staff_member') ?></label>
							<select class="form-select bulk-staff-select"></select>
						</div>
						<div class="col-12">
							<div class="turn-financial-strip">
								<div class="turn-financial-item">
									<span class="text-muted d-block mb-1"><?= t('wallet_balance') ?></span>
									<span class="badge rounded-pill bg-secondary-subtle text-secondary bulk-wallet-badge"><?= format_amount(0) ?></span>
								</div>
								<div class="turn-financial-item">
									<span class="text-muted d-block mb-1"><?= t('total_open_debt') ?></span>
									<span class="badge rounded-pill bg-secondary-subtle text-secondary bulk-debt-badge"><?= format_amount(0) ?></span>
								</div>
								<div class="turn-financial-item bulk-debt-toggle-wrap d-none">
									<button type="button" class="btn btn-sm btn-outline-secondary bulk-debt-toggle"><?= t('open_debts') ?></button>
								</div>
							</div>
						</div>
						<div class="col-12 bulk-debt-summary d-none">
							<div class="table-responsive">
								<table class="table table-sm align-middle mb-0">
									<thead>
										<tr>
											<th>#</th>
											<th><?= t('Date') ?></th>
											<th><?= t('Amount') ?></th>
										</tr>
									</thead>
									<tbody class="bulk-debt-table-body">
										<tr>
											<td colspan="3" class="text-muted"><?= t('no_open_debt') ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-6">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label"><?= t('fee') ?></label>
							<input type="number" min="0" step="0.01" class="form-control bulk-fee-input">
						</div>
						<div class="col-md-6 bulk-cash-wrap d-none">
							<label class="form-label"><?= t('cash_amount') ?></label>
							<input type="text" class="form-control bulk-cash-preview" value="0.00" readonly>
						</div>
						<div class="col-12">
							<label class="form-label d-block mb-3"><?= t('payment_type') ?></label>
							<div class="row g-3 bulk-payment-options">
								<div class="col-sm-6 col-xxl-3">
									<label class="turn-payment-option bulk-payment-option">
										<input type="radio" class="form-check-input bulk-payment-radio" value="prepaid">
										<span class="turn-payment-option__title"><?= t('prepaid') ?></span>
										<span class="turn-payment-option__text"><?= t('wallet_balance') ?></span>
									</label>
								</div>
								<div class="col-sm-6 col-xxl-3">
									<label class="turn-payment-option bulk-payment-option">
										<input type="radio" class="form-check-input bulk-payment-radio" value="cash">
										<span class="turn-payment-option__title"><?= t('cash') ?></span>
										<span class="turn-payment-option__text"><?= t('cash_collected') ?></span>
									</label>
								</div>
								<div class="col-sm-6 col-xxl-3">
									<label class="turn-payment-option bulk-payment-option">
										<input type="radio" class="form-check-input bulk-payment-radio" value="deferred">
										<span class="turn-payment-option__title"><?= t('deferred') ?></span>
										<span class="turn-payment-option__text"><?= t('amount_becoming_debt') ?></span>
									</label>
								</div>
								<div class="col-sm-6 col-xxl-3">
									<label class="turn-payment-option bulk-payment-option">
										<input type="radio" class="form-check-input bulk-payment-radio" value="free">
										<span class="turn-payment-option__title"><?= t('free') ?></span>
										<span class="turn-payment-option__text"><?= t('fee') ?></span>
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-6 bulk-topup-wrap d-none">
							<label class="form-label"><?= t('top_up_amount') ?></label>
							<input type="number" min="0" step="0.01" class="form-control bulk-topup-input" value="0.00">
						</div>
						<div class="col-12">
							<div class="alert alert-warning d-none bulk-payment-warning mb-0"></div>
						</div>
						<div class="col-12">
							<label class="form-label"><?= t('Notes') ?></label>
							<textarea rows="3" class="form-control bulk-notes-input"></textarea>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="turn-summary-block">
						<h2 class="h5 mb-3"><?= t('payment_summary') ?></h2>
						<div class="turn-summary-grid">
							<div class="turn-summary-row"><span><?= t('fee') ?></span><strong class="bulk-summary-fee"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('top_up_amount') ?></span><strong class="bulk-summary-topup"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('wallet_deducted') ?></span><strong class="bulk-summary-wallet"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('cash_collected') ?></span><strong class="bulk-summary-cash"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('amount_becoming_debt') ?></span><strong class="bulk-summary-debt"><?= format_amount(0) ?></strong></div>
							<div class="turn-summary-row"><span><?= t('new_wallet_balance') ?></span><strong class="bulk-summary-balance"><?= format_amount(0) ?></strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
(function () {
	const form = document.getElementById('bulkTurnsForm');
	if (!form) {
		return;
	}

	const rowsContainer = document.getElementById('bulkRows');
	const template = document.getElementById('bulkTurnRowTemplate');
	const sectionSelect = document.getElementById('bulkSectionSelect');
	const addRowButton = document.getElementById('addBulkRowButton');
	const addRowsButton = document.getElementById('addBulkRowsButton');
	const addCountInput = document.getElementById('bulkRowAddCount');
	const rowCount = document.getElementById('bulkRowCount');
	const patients = <?= json_encode($patients_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
	const initialRows = <?= json_encode($initial_rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
	const initialRowErrors = <?= json_encode($row_errors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
	const state = {
		sectionFee: 0,
		staff: []
	};
	const messages = {
		select: <?= json_encode(t('Select')) ?>,
		noSessionNumber: <?= json_encode('—') ?>,
		rowNumber: <?= json_encode(t('row_number')) ?>,
		patientsAdded: <?= json_encode(t('patients_added')) ?>,
		noOpenDebt: <?= json_encode(t('no_open_debt')) ?>,
		noStaff: <?= json_encode(t('no_staff_for_section')) ?>,
		patient: <?= json_encode(t('Patient')) ?>,
		fee: <?= json_encode(t('fee')) ?>,
		paymentType: <?= json_encode(t('payment_type')) ?>,
		insufficientBalance: <?= json_encode(t('insufficient_balance')) ?>,
		cashClearsDebts: <?= json_encode(t('cash_clears_oldest_debts')) ?>,
		deferredDebt: <?= json_encode(t('full_fee_recorded_as_debt')) ?>,
		noPaymentRequired: <?= json_encode(t('no_payment_required')) ?>,
		amountBecomingDebt: <?= json_encode(t('amount_becoming_debt')) ?>,
		openDebts: <?= json_encode(t('open_debts')) ?>,
		collapse: <?= json_encode(t('Collapse')) ?>,
		expand: <?= json_encode(t('Expand')) ?>,
	};

	function escapeHtml(value) {
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
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

	function fetchJson(url, payload) {
		return fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: new URLSearchParams(payload)
		}).then(function (response) {
			if (!response.ok) {
				throw new Error('Request failed');
			}
			return response.json();
		});
	}

	function getRows() {
		return Array.from(rowsContainer.querySelectorAll('.bulk-turn-row'));
	}

	function updateRowCount() {
		rowCount.textContent = String(getRows().length);
	}

	function patientSelectOptions(selectedValue, excludedIds) {
		const options = ['<option value="">' + escapeHtml(messages.select) + '</option>'];

		patients.forEach(function (patient) {
			const id = String(patient.id);
			if (excludedIds.has(id) && id !== String(selectedValue || '')) {
				return;
			}

			const selected = String(selectedValue || '') === id ? ' selected' : '';
			options.push('<option value="' + id + '"' + selected + '>' + escapeHtml(patient.name) + '</option>');
		});

		return options.join('');
	}

	function updatePatientOptions() {
		const rows = getRows();
		const selectedIds = rows
			.map(function (row) {
				return row.querySelector('.bulk-patient-select').value;
			})
			.filter(Boolean);

		rows.forEach(function (row) {
			const select = row.querySelector('.bulk-patient-select');
			const currentValue = select.value;
			const excludedIds = new Set(selectedIds.filter(function (id) {
				return id !== currentValue;
			}));
			select.innerHTML = patientSelectOptions(currentValue, excludedIds);
			select.value = currentValue;
		});
	}

	function updateRowNames() {
		getRows().forEach(function (row, index) {
			row.dataset.index = String(index);
			row.querySelector('.bulk-row-title').textContent = messages.rowNumber + ' ' + (index + 1);
			row.querySelector('.bulk-patient-select').name = 'turns[' + index + '][patient_id]';
			row.querySelector('.bulk-session-input').name = 'turns[' + index + '][turn_number]';
			row.querySelector('.bulk-staff-select').name = 'turns[' + index + '][staff_id]';
			row.querySelector('.bulk-fee-input').name = 'turns[' + index + '][fee]';
			row.querySelector('.bulk-topup-input').name = 'turns[' + index + '][topup_amount]';
			row.querySelector('.bulk-notes-input').name = 'turns[' + index + '][notes]';
			row.querySelectorAll('.bulk-payment-radio').forEach(function (radio) {
				radio.name = 'turns[' + index + '][payment_type]';
			});
		});
		updateRowCount();
		updatePatientOptions();
	}

	function selectedPatientName(patientId) {
		const match = patients.find(function (patient) {
			return String(patient.id) === String(patientId || '');
		});

		return match ? match.name : messages.select;
	}

	function paymentTypeLabel(paymentType) {
		switch (paymentType) {
			case 'prepaid':
				return <?= json_encode(t('prepaid')) ?>;
			case 'cash':
				return <?= json_encode(t('cash')) ?>;
			case 'deferred':
				return <?= json_encode(t('deferred')) ?>;
			case 'free':
				return <?= json_encode(t('free')) ?>;
			default:
				return <?= json_encode(t('cash')) ?>;
		}
	}

	function updateRowHeader(row) {
		const patientId = row.querySelector('.bulk-patient-select').value;
		const fee = toNumber(row.querySelector('.bulk-fee-input').value);
		const paymentType = selectedPaymentType(row);
		const label = row.querySelector('.bulk-row-summary-title');
		label.textContent = messages.patient + ': ' + selectedPatientName(patientId)
			+ ' | ' + messages.fee + ': ' + formatAmount(fee)
			+ ' | ' + messages.paymentType + ': ' + paymentTypeLabel(paymentType);
	}

	function setRowCollapsed(row, collapsed) {
		const body = row.querySelector('.bulk-row-body');
		const toggle = row.querySelector('.bulk-toggle-row');
		const label = row.querySelector('.bulk-toggle-label');

		row.classList.toggle('bulk-turn-row--collapsed', collapsed);
		body.classList.toggle('d-none', collapsed);
		toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
		label.textContent = collapsed ? messages.expand : messages.collapse;
	}

	function renderRowErrors(row, errors) {
		const box = row.querySelector('.bulk-row-errors');
		if (!errors || !errors.length) {
			box.classList.add('d-none');
			box.innerHTML = '';
			return;
		}

		box.classList.remove('d-none');
		box.innerHTML = '<ul class="mb-0 ps-3">' + errors.map(function (message) {
			return '<li>' + escapeHtml(message) + '</li>';
		}).join('') + '</ul>';
	}

	function updateSessionNumber(row, value) {
		const normalized = value === null || value === undefined || value === '' ? '' : String(value);
		row.querySelector('.bulk-session-input').value = normalized;
	}

	function renderDebtRows(row) {
		const stateData = row._state;
		const tbody = row.querySelector('.bulk-debt-table-body');
		const toggleWrap = row.querySelector('.bulk-debt-toggle-wrap');
		const debtSummary = row.querySelector('.bulk-debt-summary');

		if (!stateData.openDebts.length) {
			toggleWrap.classList.add('d-none');
			debtSummary.classList.add('d-none');
			tbody.innerHTML = '<tr><td colspan="3" class="text-muted">' + escapeHtml(messages.noOpenDebt) + '</td></tr>';
			return;
		}

		toggleWrap.classList.remove('d-none');
		tbody.innerHTML = stateData.openDebts.map(function (debt) {
			return '<tr><td>#' + escapeHtml(debt.id) + '</td><td>' + escapeHtml(debt.created_at) + '</td><td>' + escapeHtml(formatAmount(toNumber(debt.amount))) + '</td></tr>';
		}).join('');
	}

	function updateFinancialUI(row) {
		const stateData = row._state;
		const walletBadge = row.querySelector('.bulk-wallet-badge');
		const debtBadge = row.querySelector('.bulk-debt-badge');

		walletBadge.textContent = formatAmount(stateData.walletBalance);
		walletBadge.className = stateData.walletBalance > 0
			? 'badge rounded-pill bg-success-subtle text-success bulk-wallet-badge'
			: 'badge rounded-pill bg-secondary-subtle text-secondary bulk-wallet-badge';

		debtBadge.textContent = formatAmount(stateData.totalOpenDebt);
		debtBadge.className = stateData.totalOpenDebt > 0
			? 'badge rounded-pill bg-danger-subtle text-danger bulk-debt-badge'
			: 'badge rounded-pill bg-secondary-subtle text-secondary bulk-debt-badge';

		renderDebtRows(row);
	}

	function populateStaffOptions(row, selectedId) {
		const select = row.querySelector('.bulk-staff-select');
		const options = ['<option value="">' + escapeHtml(messages.select) + '</option>'];

		state.staff.forEach(function (staffMember) {
			const selected = String(selectedId || '') === String(staffMember.id) ? ' selected' : '';
			options.push('<option value="' + staffMember.id + '"' + selected + '>' + escapeHtml(staffMember.full_name) + '</option>');
		});

		if (!state.staff.length) {
			options.push('<option value="" disabled>' + escapeHtml(messages.noStaff) + '</option>');
		}

		select.innerHTML = options.join('');
		select.value = selectedId || '';
	}

	function selectedPaymentType(row) {
		const checked = row.querySelector('.bulk-payment-radio:checked');
		return checked ? checked.value : 'cash';
	}

	function refreshPaymentOptionState(row) {
		row.querySelectorAll('.bulk-payment-option').forEach(function (option) {
			const input = option.querySelector('.bulk-payment-radio');
			option.classList.toggle('turn-payment-option--active', Boolean(input && input.checked));
		});
	}

	function refreshRowSummary(row) {
		const stateData = row._state;
		const feeInput = row.querySelector('.bulk-fee-input');
		const topupInput = row.querySelector('.bulk-topup-input');
		const cashWrap = row.querySelector('.bulk-cash-wrap');
		const cashPreview = row.querySelector('.bulk-cash-preview');
		const topupWrap = row.querySelector('.bulk-topup-wrap');
		const warningsBox = row.querySelector('.bulk-payment-warning');
		const paymentType = selectedPaymentType(row);
		const fee = toNumber(feeInput.value);
		let topup = toNumber(topupInput.value);
		let walletDeducted = 0;
		let cashCollected = 0;
		let debtAmount = 0;
		let newBalance = stateData.walletBalance;
		const warnings = [];

		topupWrap.classList.toggle('d-none', paymentType !== 'prepaid');
		cashWrap.classList.toggle('d-none', paymentType !== 'cash');
		cashPreview.value = fee.toFixed(2);

		if (paymentType !== 'prepaid') {
			topup = 0;
			topupInput.value = '0.00';
		}

		if (paymentType === 'prepaid') {
			const availableWallet = stateData.walletBalance + topup;
			walletDeducted = Math.min(availableWallet, fee);
			debtAmount = Math.max(0, fee - walletDeducted);
			newBalance = Math.max(0, availableWallet - walletDeducted);

			if (debtAmount > 0) {
				warnings.push(messages.insufficientBalance + ' - ' + messages.amountBecomingDebt + ': ' + formatAmount(debtAmount));
			}
		}

		if (paymentType === 'cash') {
			cashCollected = fee;
			if (stateData.totalOpenDebt > 0) {
				warnings.push(messages.cashClearsDebts);
			}
		}

		if (paymentType === 'deferred') {
			debtAmount = fee;
			if (fee > 0) {
				warnings.push(messages.deferredDebt);
			}
		}

		if (paymentType === 'free') {
			warnings.push(messages.noPaymentRequired);
		}

		if (warnings.length) {
			warningsBox.classList.remove('d-none');
			warningsBox.innerHTML = warnings.map(escapeHtml).join('<br>');
		} else {
			warningsBox.classList.add('d-none');
			warningsBox.textContent = '';
		}

		row.querySelector('.bulk-summary-fee').textContent = formatAmount(fee);
		row.querySelector('.bulk-summary-topup').textContent = formatAmount(topup);
		row.querySelector('.bulk-summary-wallet').textContent = formatAmount(walletDeducted);
		row.querySelector('.bulk-summary-cash').textContent = formatAmount(cashCollected);
		row.querySelector('.bulk-summary-debt').textContent = formatAmount(debtAmount);
		row.querySelector('.bulk-summary-balance').textContent = formatAmount(newBalance);
	}

	function applyPatientFinancial(row, data) {
		row._state.walletBalance = toNumber(data.wallet_balance);
		row._state.totalOpenDebt = toNumber(data.total_open_debt);
		row._state.openDebts = Array.isArray(data.open_debts) ? data.open_debts : [];
		updateFinancialUI(row);
		refreshRowSummary(row);
	}

	function resetPatientState(row) {
		row._state.walletBalance = 0;
		row._state.totalOpenDebt = 0;
		row._state.openDebts = [];
		updateSessionNumber(row, '');
		updateFinancialUI(row);
		refreshRowSummary(row);
	}

	function loadPatientData(row, patientId) {
		if (!patientId) {
			resetPatientState(row);
			return;
		}

		updateSessionNumber(row, '');
		row._state.sessionManuallyEdited = false;

		fetchJson(<?= json_encode(base_url('turns/get_patient_financial')) ?>, { patient_id: patientId })
			.then(function (data) {
				applyPatientFinancial(row, data);
			})
			.catch(function () {
				return null;
			});

		requestRowSessionNumber(row);
	}

	function requestRowSessionNumber(row) {
		const patientId = row.querySelector('.bulk-patient-select').value;
		const sectionId = sectionSelect.value;

		if (!patientId || !sectionId) {
			updateSessionNumber(row, '');
			row._state.sessionManuallyEdited = false;
			return;
		}

		fetchJson(<?= json_encode(base_url('turns/get_session_number')) ?>, { patient_id: patientId, section_id: sectionId })
			.then(function (data) {
				if (!data || data.success !== true || row._state.sessionManuallyEdited) {
					return;
				}

				updateSessionNumber(row, data.session_number || '');
			})
			.catch(function () {
				return null;
			});
	}

	function attachRowEvents(row) {
		row.querySelector('.bulk-remove-row').addEventListener('click', function () {
			row.remove();
			updateRowNames();
		});

		row.querySelector('.bulk-toggle-row').addEventListener('click', function () {
			setRowCollapsed(row, !row.classList.contains('bulk-turn-row--collapsed'));
		});

		row.querySelector('.bulk-patient-select').addEventListener('change', function () {
			updatePatientOptions();
			updateRowHeader(row);
			loadPatientData(row, this.value);
		});

		row.querySelector('.bulk-session-input').addEventListener('input', function () {
			row._state.sessionManuallyEdited = true;
		});

		row.querySelector('.bulk-fee-input').addEventListener('input', function () {
			row.dataset.feeEdited = '1';
			updateRowHeader(row);
			refreshRowSummary(row);
		});

		row.querySelector('.bulk-topup-input').addEventListener('input', function () {
			refreshRowSummary(row);
		});

		row.querySelector('.bulk-debt-toggle').addEventListener('click', function () {
			row.querySelector('.bulk-debt-summary').classList.toggle('d-none');
		});

		row.querySelectorAll('.bulk-payment-radio').forEach(function (radio) {
			radio.addEventListener('change', function () {
				updateRowHeader(row);
				refreshPaymentOptionState(row);
				refreshRowSummary(row);
			});
		});
	}

	function addRow(data, errors) {
		const index = getRows().length;
		const html = template.innerHTML
			.replace(/__INDEX__/g, String(index))
			.replace(/__ROW_NUMBER__/g, String(index + 1));
		const wrapper = document.createElement('div');
		wrapper.innerHTML = html.trim();
		const row = wrapper.firstElementChild;
		const rowData = data || {};

		row._state = {
			walletBalance: 0,
			totalOpenDebt: 0,
			openDebts: [],
			sessionManuallyEdited: false
		};

		rowsContainer.appendChild(row);
		attachRowEvents(row);
		populateStaffOptions(row, rowData.staff_id || '');

		const patientSelect = row.querySelector('.bulk-patient-select');
		const feeInput = row.querySelector('.bulk-fee-input');
		const topupInput = row.querySelector('.bulk-topup-input');
		const notesInput = row.querySelector('.bulk-notes-input');
		const paymentRadios = row.querySelectorAll('.bulk-payment-radio');

		patientSelect.innerHTML = patientSelectOptions('', new Set());
		patientSelect.value = rowData.patient_id || '';
		feeInput.value = rowData.fee !== undefined && rowData.fee !== '' ? rowData.fee : state.sectionFee.toFixed(2);
		topupInput.value = rowData.topup_amount !== undefined && rowData.topup_amount !== '' ? toNumber(rowData.topup_amount).toFixed(2) : '0.00';
		notesInput.value = rowData.notes || '';
		row.dataset.feeEdited = rowData.fee !== undefined && rowData.fee !== '' ? '1' : '0';

		paymentRadios.forEach(function (radio) {
			radio.checked = radio.value === (rowData.payment_type || 'cash');
		});

		updateSessionNumber(row, rowData.turn_number || '');
		renderRowErrors(row, errors || []);
		updateFinancialUI(row);
		updateRowHeader(row);
		refreshPaymentOptionState(row);
		refreshRowSummary(row);
		setRowCollapsed(row, false);
		updateRowNames();

		if (rowData.patient_id) {
			loadPatientData(row, rowData.patient_id);
		}
	}

	function applySectionData(data) {
		state.sectionFee = toNumber(data.fee);
		state.staff = Array.isArray(data.staff) ? data.staff : [];

		getRows().forEach(function (row) {
			const currentStaffId = row.querySelector('.bulk-staff-select').value;
			populateStaffOptions(row, currentStaffId);

			if (row.dataset.feeEdited !== '1') {
				row.querySelector('.bulk-fee-input').value = state.sectionFee.toFixed(2);
			}

			updateRowHeader(row);
			refreshRowSummary(row);
			row._state.sessionManuallyEdited = false;
			requestRowSessionNumber(row);
		});
	}

	function loadSectionData(sectionId) {
		if (!sectionId) {
			state.sectionFee = 0;
			state.staff = [];
			getRows().forEach(function (row) {
				populateStaffOptions(row, '');
				if (row.dataset.feeEdited !== '1') {
					row.querySelector('.bulk-fee-input').value = '0.00';
				}
				row._state.sessionManuallyEdited = false;
				updateSessionNumber(row, '');
				updateRowHeader(row);
				refreshRowSummary(row);
			});
			return;
		}

		fetchJson(<?= json_encode(base_url('turns/get_section_data')) ?>, { section_id: sectionId })
			.then(applySectionData)
			.catch(function () {
				return null;
			});
	}

	addRowButton.addEventListener('click', function () {
		addRow();
	});

	addRowsButton.addEventListener('click', function () {
		const count = Math.max(1, parseInt(addCountInput.value || '1', 10) || 1);
		for (let index = 0; index < count; index += 1) {
			addRow();
		}
	});

	sectionSelect.addEventListener('change', function () {
		loadSectionData(this.value);
	});

	if (initialRows.length) {
		initialRows.forEach(function (rowData, index) {
			addRow(rowData, initialRowErrors[index] || []);
		});
	} else if (<?= !empty($shared_errors) ? 'true' : 'false' ?>) {
		updateRowNames();
	}

	if (sectionSelect.value) {
		loadSectionData(sectionSelect.value);
	}

	updateRowCount();
})();
</script>
