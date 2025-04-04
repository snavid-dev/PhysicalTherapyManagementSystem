<?php $ci = get_instance(); ?>
<script>
	function edit_turn(id) {
		$.ajax({
			url: "<?= base_url('admin/single_turn') ?>",
			type: 'POST',
			data: {slug: id},
			success: function (response) {
				const result = JSON.parse(response);
				if (result.type === 'success') {
					$('#slug_turn').val(result.content.slug);
					$('#date_turn').val(result.content.date);
					$('#dateTurnOld').val(result.content.date);
					$('#doctorTurnOld').val(result.content.doctor_id);
					$('#from_time_turn').val(result.content.from_time);
					$('#fromTimeTurnOld').val(result.content.from_time);
					$('#to_time_turn').val(result.content.to_time);
					$('#toTimeTurnOld').val(result.content.to_time);

					let doctor = $('#doctor_id_turn').html();
					replacer(doctor, result.content.doctor_id, 'doctor_id_turn');

					check_turns(
						document.getElementById('doctor_id_turn'),
						$('#date_turn').val(),
						$('#doctor_id_turn').val(),
						'#turnsTableModalupdate'
					);

					$('#update_turn').modal('show');
				} else if (result.type === 'error') {
					$.growl.error1({
						title: result.alert.title,
						message: result.alert.text
					});
				}
			}
		});
	}

	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}
</script>


<script>
	function check_turns(selectElement = document.getElementById('doctorName'), date = $('#test-date-id-date').val(), doctor = $('#doctorName').val(), tableId = '#queryTable') {
		const time = document.getElementById("from_time");
		const timeOption = time ? time.value : '';  // Get selected time range

		$.ajax({
			url: "<?= base_url('admin/check_turns') ?>",
			type: 'POST',
			data: {
				date: date,
				doctor: doctor,
				patient_time: timeOption
			},
			success: function (response) {
				var result = JSON.parse(response);


				// Check for success type
				if (result['type'] === 'success') {
					document.querySelector('#from_time').removeAttribute('readonly');

					document.querySelector('#to_time').removeAttribute('readonly');

					if (typeof result['content']['time_slots'] != 'undefined') {
						var timeSlots = result['content']['time_slots'];  // assuming we get 'time_slots' as part of the response
					}
					if (timeSlots.length > 0 && typeof timeSlots != 'undefined') {
						if (timeSlots[0].range == "On Leave") {
							document.querySelector('#from_time').setAttribute('readonly', true);
							document.querySelector('#from_time').value = '';

							document.querySelector('#to_time').setAttribute('readonly', true);
							document.querySelector('#to_time').value = '';
						}
						var tableTemplate = `<table class="table text-nowrap table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col"><?= $ci->lang('Available Time Range') ?></th>
                                                    <th scope="col"><?= $ci->lang('Status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

						// Loop through each time slot and add it to the table
						timeSlots.forEach((slot) => {
							tableTemplate += `<tr>
                                              <td>${slot.range}</td>
                                              <td>${slot.status}</td>
                                          </tr>`;
						});

						tableTemplate += `</tbody>
                                    </table>`;
						$(tableId).html(tableTemplate);
					} else {
						var tableTemplate = `<p>No available time slots for this date and doctor.</p>`;
						$(tableId).html(tableTemplate);
					}
				} else if (result['type'] === 'error') {
					document.querySelector('#from_time').setAttribute('readonly', true);
					document.querySelector('#from_time').value = '';

					document.querySelector('#to_time').setAttribute('readonly', true);
					document.querySelector('#to_time').value = '';

					var tableTemplate = ``;

					$(tableId).html(tableTemplate);
					toastr["error"](result['alert']['text'], result['alert']['title'])
				}
			},
			error: function () {
				toastr["error"]('An error occurred while fetching the available time slots.', 'Error')
			}
		});
	}

</script>


<script>
	function finishTurn(turn_id) {
		const container = $('#finish_turn_processes_container');
		container.empty();
		$('#finish_turn_id').val(turn_id);

		$.ajax({
			url: "<?= base_url('admin/get_recommended_by_turn') ?>",
			type: 'POST',
			data: {turn_id},
			dataType: 'json',
			success: function (res) {
				if (res.type !== 'success' || !Array.isArray(res.content)) return;

				res.content.forEach((tooth, toothIndex) => {
					const toothId = tooth.tooth_id;
					const toothName = tooth.tooth_name;

					let html = `
				<div class="row nthHrLine">
					<div class="col-12 greyline">
						<div class="customMargin">
							<div class="processHeader">
								<h2 style="margin-bottom: 30px">${toothName}</h2>
								<input type="hidden" name="tooth_id[]" value="${toothId}">
							</div>`;

					tooth.departments.forEach((dept, deptIndex) => {
						const deptName = dept.name;
						const processes = dept.processes || [];
						const customText = dept.custom?.trim() ?? '';
						const otherId = `finish_other_textarea_${toothIndex}_${deptIndex}`;

						html += `<h5 class="text-primary">${deptName}</h5><div class="row">`;

						// Show processes as checkboxes (both DB and custom)
						processes.forEach(proc => {
							const value = proc.process_id ?? proc.label;
							const isCustom = !proc.process_id;
							html += `
							<div class="col-sm-12 col-md-2 customMargin_processCheckbox">
								<label class="cl-checkbox">
									<input type="checkbox" name="done_process[${toothId}][${deptName}][]" value="${value}" data-label="${proc.label}" data-department="${deptName}">
									<span>${proc.label}${isCustom ? ' (Custom)' : ''}</span>
								</label>
							</div>`;
						});

						// If there's a saved "custom" label (from recommended), show it as a checkbox too
						if (customText) {
							html += `
							<div class="col-sm-12 col-md-2 customMargin_processCheckbox">
								<label class="cl-checkbox">
									<input type="checkbox" name="done_process[${toothId}][${deptName}][]" value="${customText}" data-label="${customText}" data-department="${deptName}">
									<span>${customText} (Custom)</span>
								</label>
							</div>`;
						}

						// Always show new Other (unchecked, empty)
						html += `
						<div class="col-12 col-md-12 mt-3">
							<label class="cl-checkbox">
								<input type="checkbox" data-target="${otherId}" onclick="otherProcess(this)">
								<span><?= $ci->lang('other') ?></span>
							</label>
							<div class="mt-2" id="${otherId}" style="display: none">
								<label><?= $ci->lang('other process') ?></label>
								<textarea class="form-control" name="done_custom_process[${toothId}][${deptName}]"></textarea>
							</div>
						</div>`;

						html += '</div>'; // end dept row
					});

					html += '</div></div></div>'; // end tooth block
					container.append(html);
				});

				$('#finishTurnModal').modal('show');
			},
			error: function (xhr, status, error) {
				console.error("AJAX error:", error);
				container.empty();
			}
		});
	}

	function otherProcess(checkbox) {
		const targetId = checkbox.getAttribute("data-target");
		const textarea = document.getElementById(targetId);
		if (textarea) {
			textarea.style.display = checkbox.checked ? "block" : "none";
		}
	}

	function reloadTurnsTable() {
		setTimeout(() => {
			let patient_id = '<?= $profile['id'] ?>';
			const $tbody = $('#turnsTable tbody');
			$tbody.empty(); // Clear current table body

			$.ajax({
				url: "<?= base_url('admin/ajax_turns_by_patient') ?>",
				type: "POST",
				data: {patient_id},
				dataType: "json",
				success: function (res) {
					if (res.type === "success" && Array.isArray(res.data)) {
						let index = 1;
						res.data.forEach(turn => {
							const highlighted = turn.paid_user_name ? 'highlighted' : '';
							const paidUser = turn.paid_user_name || "<?= $ci->lang('not paid') ?>";

							const statusButton = turn.status === 'p'
								? `<a href="javascript:finishTurn('${turn.id}')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-stethoscope"></span></a>`
								: `<a href="javascript:viewTreatment('${turn.id}')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>`;

							const paymentButton = turn.payment_status === 'p'
								? `<a href="javascript:changeStatus('${turn.id}', '<?= base_url() ?>admin/accept_turn'); setTimeout(() => reloadTurnsTable(), 2000);" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-circle-check fs-14"></span></a>`
								: `<a href="javascript:changeStatus('${turn.id}', '<?= base_url() ?>admin/pending_turn'); setTimeout(() => reloadTurnsTable(), 2000);" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-times-circle fs-14"></span></a>`;

							const row = `
							<tr id="${turn.id}" class="tableRow ${highlighted}">
								<td>${index++}</td>
								<td>${turn.doctor_name}</td>
								<td>${turn.date}</td>
								<td><bdo dir="ltr">${turn.from_time} - ${turn.to_time}</bdo></td>
								<td>${turn.cr}</td>
								<td>${paidUser}</td>
								<td>
									<div class="g-2">
										<a href="javascript:edit_turn('${turn.id}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-pen-to-square fs-14"></span></a>
										<a href="javascript:print_turn('${turn.id}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-print fs-14"></span></a>
										${statusButton}
										${paymentButton}
										<a href="javascript:delete_via_alert('${turn.id}', '<?= base_url() ?>admin/delete_turn', 'turnsTable', update_balance)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-trash-can fs-14"></span></a>
									</div>
								</td>
							</tr>`;
							$tbody.append(row);
						});
					} else {
						$tbody.append(`<tr><td colspan="7" class="text-center">No turns found.</td></tr>`);
					}
				},
				error: function () {
					$tbody.append(`<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>`);
				}
			});
			update_process_completion();
		}, 1000); // 1	-second delay
	}


	// JS Function to display the viewTreatment modal with data
	function viewTreatment(turn_id) {
		const container = $('#view_treatment_processes_container');
		container.empty();
		$('#view_treatment_turn_id').val(turn_id);

		$.ajax({
			url: "<?= base_url('admin/get_treatment_summary') ?>",
			type: 'POST',
			data: {turn_id},
			dataType: 'json',
			success: function (res) {
				if (res.type !== 'success' || !Array.isArray(res.content)) return;

				res.content.forEach((tooth, toothIndex) => {
					const toothId = tooth.tooth_id;
					const toothName = tooth.tooth_name;

					let html = `
				<div class="row nthHrLine">
					<div class="col-12 greyline">
						<div class="customMargin">
							<div class="processHeader">
								<h2 style="margin-bottom: 30px">${toothName}</h2>
							</div>`;

					tooth.departments.forEach((dept, deptIndex) => {
						const deptName = dept.name;
						const recommended = Array.isArray(dept.recommended) ? dept.recommended : [];
						const done = Array.isArray(dept.done) ? dept.done : [];
						const doneCustomText = dept.done_custom_text || '';

						html += `<h5 class="text-primary">${deptName}</h5><div class="row">`;

						// Merge all recommended + done (if done not in recommended)
						const combined = [...recommended];

						done.forEach(doneProc => {
							if (!recommended.some(r => r.label === doneProc.label)) {
								combined.push({label: doneProc.label, type: doneProc.type || 'custom'});
							}
						});

						// Render checkboxes
						combined.forEach(proc => {
							const isDone = done.some(d => d.label === proc.label);
							html += `
						<div class="col-sm-12 col-md-3 customMargin_processCheckbox">
							<label class="cl-checkbox">
								<input type="checkbox" ${isDone ? 'checked' : ''} disabled>
								<span>${proc.label}</span>
							</label>
						</div>`;
						});

						// Show custom text if exists
						if (doneCustomText.trim()) {
							html += `
						<div class="col-12 mt-2">
							<label><strong><?= $ci->lang('other process') ?>:</strong></label>
							<p class="form-control-plaintext">${doneCustomText}</p>
						</div>`;
						}

						html += '</div>'; // close department
					});

					html += '</div></div></div>'; // close tooth
					container.append(html);
				});

				$('#viewTreatmentModal').modal('show');
			},
			error: function () {
				container.html('<div class="text-danger">Error loading treatment summary.</div>');
			}
		});
	}


</script>
