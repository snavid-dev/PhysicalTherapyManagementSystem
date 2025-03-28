<?php $ci = get_instance(); ?>
<script>
	function edit_turn(id) {
		$.ajax({
			url: "<?= base_url('admin/single_turn') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_turn').val(result['content']['slug']);
					$('#date_turn').val(result['content']['date']);
					$('#dateTurnOld').val(result['content']['date']);
					$('#hourTurnOld').val(result['content']['hour']);
					$('#doctorTurnOld').val(result['content']['doctor_id']);
					let doctor = $('#doctor_id_turn').html();
					replacer(doctor, result['content']['doctor_id'], 'doctor_id_turn');

					let hour = $('#hour_turn').html();
					replacer(hour, result['content']['hour'], 'hour_turn');
					$('#update_turn').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
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
	// function finishTurn(id){
	// 	console.log('id ', id);
	// }
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


</script>
