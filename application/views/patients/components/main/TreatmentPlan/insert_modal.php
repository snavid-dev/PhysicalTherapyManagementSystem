<?php $ci = get_instance(); ?>
<div class="modal fade effect-scale" id="multiTurnModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert multiple turns') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form id="multiInsertTurn">
					<div id="turnsContainer">
						<!-- First Turn Row (index 0) -->
						<div class="turn-row border p-3 mb-3" data-index="0">
							<div class="row">
								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('date') ?> <span class="text-red">*</span></label>
									<input data-jdp type="text"
										   name="turns[0][date]"
										   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
										   class="form-control">
									<input type="hidden" name="turns[0][patient_id]" value="<?= $profile['id'] ?>">
								</div>

								<div class="col-md-3">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span class="text-red">*</span></label>
									<select name="turns[0][doctor_id]" class="form-control">
										<option value=""><?= $ci->lang('select') ?></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="col-md-2">
									<label class="form-label"><?= $ci->lang('treatment plan') ?> <span class="text-red">*</span></label>
									<select name="turns[0][plan]" class="form-control">
										<option value=""><?= $ci->lang('select') ?></option>
										<?php foreach ($treatment_plans as $treatment_plan) : ?>
											<option value="<?= htmlentities($treatment_plan['recommendation_name'], ENT_QUOTES, 'UTF-8') ?>">
												<?= $treatment_plan['recommendation_name'] ?>
											</option>
										<?php endforeach; ?>
									</select>
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
						</div>
						<!-- /First Turn Row -->
					</div>

					<!-- Add More Turns Button -->
					<button type="button" id="addTurn" class="btn btn-info">
						<?= $ci->lang('add another turn') ?> <i class="fa fa-plus"></i>
					</button>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $ci->lang('cancel') ?></button>
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

		// PHP values encoded into JS
		var defaultDate = <?= json_encode($ci->mylibrary->getCurrentShamsiDate()['date']) ?>;
		var patientId = <?= json_encode($profile['id']) ?>;

		// Build a clean (plain-select) template from the existing first row.
		function buildCleanTemplate() {
			var firstRow = container.querySelector('.turn-row');
			if (!firstRow) return '';

			// clone so we don't mutate the visible row
			var clone = firstRow.cloneNode(true);

			// Rebuild selects inside the clone as plain selects using their option list
			clone.querySelectorAll('select').forEach(function (sel) {
				var name = sel.getAttribute('name') || '';
				var optionsHtml = '';
				Array.prototype.forEach.call(sel.options, function (opt) {
					// escape double quotes in values
					var val = (opt.value === undefined) ? '' : String(opt.value).replace(/"/g, '&quot;');
					optionsHtml += '<option value="' + val + '">' + opt.text + '</option>';
				});
				var newSel = document.createElement('select');
				newSel.className = 'form-control';
				if (name) newSel.setAttribute('name', name);
				newSel.innerHTML = optionsHtml;
				sel.parentNode.replaceChild(newSel, sel);
			});

			// Reset other inputs in clone
			clone.querySelectorAll('input, textarea').forEach(function (inp) {
				var name = inp.getAttribute('name') || '';
				if (name.indexOf('patient_id') !== -1) {
					inp.value = patientId; // keep patient_id
				} else if (inp.hasAttribute('data-jdp')) {
					inp.value = defaultDate; // reset date to default
				} else {
					// clear everything else
					if (inp.type !== 'hidden') inp.value = '';
				}
			});

			return clone.outerHTML;
		}

		var templateHTML = buildCleanTemplate();
		if (!templateHTML) return;

		// Replace visible first row with the clean template to ensure the live first row is plain as well
		container.innerHTML = templateHTML;

		var turnIndex = 1;
		var addBtn = document.getElementById('addTurn');

		// Utility to destroy Select2 inside modal (if jQuery + Select2 present)
		function destroySelect2OnModal() {
			if (!$) return;
			try {
				var $modal = $('#multiTurnModal');
				$modal.find('select').each(function () {
					var $s = $(this);
					if ($s.data('select2')) {
						try { $s.select2('destroy'); } catch (e) { /* ignore */ }
					}
					$s.show();
					// remove select2-hidden-accessible class if present
					$s.removeClass('select2-hidden-accessible');
				});
				// remove any leftover Select2 containers
				$modal.find('.select2-container').remove();
			} catch (e) {
				// silent
				console && console.warn && console.warn('destroySelect2OnModal error', e);
			}
		}

		// Add handler
		addBtn.addEventListener('click', function () {
			// produce a new row from the template and update its index
			var newHTML = templateHTML.replace(/turns\[\d+\]/g, 'turns[' + turnIndex + ']');
			container.insertAdjacentHTML('beforeend', newHTML);

			// immediately ensure no Select2 decoration on new row
			destroySelect2OnModal();

			turnIndex++;
		});

		// Remove row (delegated) — keep at least one row
		document.addEventListener('click', function (e) {
			if (!e.target) return;
			if (e.target.classList && e.target.classList.contains('remove-turn')) {
				var rows = container.querySelectorAll('.turn-row');
				if (rows.length > 1) {
					var row = e.target.closest('.turn-row');
					if (row) row.parentNode.removeChild(row);
				}
			}
		});

		// If Select2 is being applied globally later, destroy any Select2 when the modal is about to show
		if ($) {
			$('#multiTurnModal').on('show.bs.modal shown.bs.modal', function () {
				destroySelect2OnModal();
			});
		} else {
			// fallback: try to destroy after modal shown (Bootstrap events should exist)
			var modalEl = document.getElementById('multiTurnModal');
			if (modalEl) {
				modalEl.addEventListener('shown.bs.modal', destroySelect2OnModal);
			}
		}

		// Extra defensive: destroy Select2 once on load for this modal
		destroySelect2OnModal();
	});
</script>
