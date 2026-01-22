<?php $ci = get_instance(); ?>
<div class="modal fade effect-scale" id="multiTurnModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert multiple turns') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>

			<div class="modal-body">
				<form id="multiInsertTurn">
					<div id="turnsContainer">
						<!-- First Turn Row (Template) -->
						<div class="turn-row border p-3 mb-3" data-index="0">
							<div class="row">

								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('treatment plan') ?> <span class="text-red">*</span></label>
									<select name="turns[0][plan]" class="form-control turn-plan-select">
										<option value=""><?= $ci->lang('select') ?></option>
										<?php foreach ($treatment_plans as $treatment_plan) : ?>
											<option value="<?= htmlentities($treatment_plan['recommendation_name'], ENT_QUOTES, 'UTF-8') ?>">
												<?= $treatment_plan['recommendation_name'] ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="col-md-3">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span class="text-red">*</span></label>
									<select name="turns[0][doctor_id]" class="form-control turn-date-doctor">
										<option value=""><?= $ci->lang('select') ?></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('date') ?> <span class="text-red">*</span></label>
									<input data-jdp type="text"
										   name="turns[0][date]"
										   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
										   class="form-control turn-date-doctor">
									<input type="hidden" name="turns[0][patient_id]" value="<?= $profile['id'] ?>">
								</div>

								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('hour') ?></label>
									<input type="time" name="turns[0][from_time]" class="form-control">
								</div>

								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('hour') ?></label>
									<input type="time" name="turns[0][to_time]" class="form-control">
								</div>

								<div class="col-md-1 d-flex align-items-end">
									<button type="button" class="btn btn-danger remove-turn rounded">X</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mt-3 turn-availability-container">
								</div>
							</div>
						</div>
					</div>

					<button type="button" id="addTurn" class="btn btn-info">
						<?= $ci->lang('add another turn') ?> <i class="fa fa-plus"></i>
					</button>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?></button>
				<button type="button" class="btn btn-primary"
						onclick="submitWithoutDatatable('multiInsertTurn', '<?= base_url() ?>admin/insert_turnsMultiple', 'turnsTable', 'multiTurnModal', false); reloadTurnsTable();">
					<?= $ci->lang('save all') ?> <i class="fa fa-save"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		var $ = window.jQuery || null;
		var container = document.getElementById('turnsContainer');
		if (!container) return;

		var defaultDate = <?= json_encode($ci->mylibrary->getCurrentShamsiDate()['date']) ?>;
		var patientId = <?= json_encode($profile['id']) ?>;

		/**
		 * --- FIX #1: THE SELECT2 PROBLEM ---
		 * This function finds and destroys all Select2 instances within the modal.
		 * It ensures the first row and all new rows have standard, predictable dropdowns.
		 */
		function destroyAllSelect2InModal() {
			if (!$) return;
			try {
				$('#multiTurnModal').find('select.select2-hidden-accessible').each(function() {
					$(this).select2('destroy');
				});
			} catch (e) {
				console.warn("Could not destroy Select2.", e);
			}
		}

		// Run the fix when the modal is about to be shown. This is the most reliable way.
		if ($) {
			$('#multiTurnModal').on('show.bs.modal', function() {
				destroyAllSelect2InModal();
			});
		}
		// Also run it once on load, just in case.
		destroyAllSelect2InModal();


		function reinitializePlugins(parentElement) {
			// Datepicker initialization for NEW rows would go here.
		}

		function buildCleanTemplate() {
			var firstRow = container.querySelector('.turn-row');
			if (!firstRow) return '';
			var clone = firstRow.cloneNode(true);
			clone.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
			clone.querySelectorAll('input:not([type=hidden])').forEach(inp => inp.value = '');
			clone.querySelector('input[name*="[date]"]').value = defaultDate;
			var availabilityContainer = clone.querySelector('.turn-availability-container');
			if (availabilityContainer) availabilityContainer.innerHTML = '';
			return clone.outerHTML;
		}

		var templateHTML = buildCleanTemplate();
		var turnIndex = 1;
		var addBtn = document.getElementById('addTurn');

		addBtn.addEventListener('click', function () {
			var newHTML = templateHTML.replace(/turns\[\d+\]/g, 'turns[' + turnIndex + ']');
			container.insertAdjacentHTML('beforeend', newHTML);
			var newRow = container.lastElementChild;
			if(newRow) reinitializePlugins(newRow);
			turnIndex++;
		});

		document.addEventListener('click', function (e) {
			if (e.target && e.target.classList.contains('remove-turn')) {
				if (container.querySelectorAll('.turn-row').length > 1) {
					e.target.closest('.turn-row').remove();
				}
			}
		});

		function check_turns(currentRow) {
			const dateInput = currentRow.querySelector('input[name*="[date]"]');
			const doctorSelect = currentRow.querySelector('select[name*="[doctor_id]"]');
			const availabilityContainer = currentRow.querySelector('.turn-availability-container');

			const date = dateInput ? dateInput.value : '';
			const doctor = doctorSelect ? doctorSelect.value : '';

			if (!date || !doctor) {
				if(availabilityContainer) availabilityContainer.innerHTML = '';
				return;
			}

			$.ajax({
				url: "<?= base_url('admin/check_turns') ?>",
				type: 'POST',
				data: { date: date, doctor: doctor },
				dataType: 'json',
				success: function (result) {
					if (result.type === 'success' && result.content && result.content.time_slots) {
						var timeSlots = result.content.time_slots;
						var tableTemplate = `<table class="table text-nowrap table-striped"><thead><tr><th>Available Time</th><th>Status</th></tr></thead><tbody>`;
						timeSlots.forEach(slot => {
							tableTemplate += `<tr><td>${slot.range}</td><td>${slot.status}</td></tr>`;
						});
						tableTemplate += `</tbody></table>`;
						if(availabilityContainer) availabilityContainer.innerHTML = tableTemplate;
					} else {
						if(availabilityContainer) availabilityContainer.innerHTML = `<p class="text-muted">No available time slots for this doctor on this date.</p>`;
					}
				},
				error: function() {
					if(availabilityContainer) availabilityContainer.innerHTML = `<p class="text-danger">Error fetching availability.</p>`;
				}
			});
		}

		function setDoctorForPlan(currentRow, planName) {
			const doctorSelect = currentRow.querySelector('select[name*="[doctor_id]"]');
			if (!doctorSelect) return;

			$.ajax({
				url: "<?= base_url('admin/get_doctor_for_plan') ?>",
				type: 'POST',
				data: { plan_name: planName, patient_id: patientId },
				dataType: "json",
				success: function (result) {
					if (result.status === 'success' && result.doctor_id) {
						doctorSelect.value = result.doctor_id;

						// --- FIX #2: THE AVAILABILITY CHECK ---
						// We explicitly and directly call check_turns() right here to GUARANTEE it runs.
						// This is more reliable than dispatching an event.
						check_turns(currentRow);
					}
				},
				error: function() {
					toastr["error"]('A server error occurred while fetching the doctor.', 'Error');
				}
			});
		}

		container.addEventListener('change', function (e) {
			if (!e.target) return;
			const currentRow = e.target.closest('.turn-row');
			if (!currentRow) return;

			// This handles MANUAL changes to the doctor or date
			if (e.target.classList.contains('turn-date-doctor')) {
				check_turns(currentRow);
			}

			// This handles the treatment plan selection
			if (e.target.classList.contains('turn-plan-select')) {
				const planName = e.target.value;
				if (planName) {
					setDoctorForPlan(currentRow, planName);
				}
			}
		});
	});
</script>
