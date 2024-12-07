<?php $ci = get_instance(); ?>
<script>
	function check_turns(selectElement = document.getElementById('reference_doctor'), date = $('#test-date-id-date').val(), doctor = $('#reference_doctor').val(), tableId = '#turnsTableModal') {
		const time = document.getElementById("hour_insert");
		const timeOption = time.value;
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
				var turns = result['content']['turns'];


				if (result['type'] == 'success') {
					if (turns.length != 0) {
						var tableTemplate =
							`<table class="table text-nowrap table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col"><?= $ci->lang('patient name') ?></th>
                                            <th scope="col"><?= $ci->lang('reference doctor') ?></th>
                                            <th scope="col"><?= $ci->lang('hour') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

						turns.map((item) => {
							tableTemplate += `<tr>
                                            <th scope="row">${item['patient_name']}</th>
                                            <td>${item['doctor_name']}</td>
                                            <td>${item['hour']}</td>
                                        </tr>`;
						})


						tableTemplate += `</tbody>
                                </table>`;
					} else {
						var tableTemplate = ``;
					}
					$(tableId).html(tableTemplate);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}

				// if in the date & time turn was already taken this will alert for the user
				if (result['alert']) {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}

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
