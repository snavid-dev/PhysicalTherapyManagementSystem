<?php
$ci = get_instance();
?>
<script>
	var ageOld = <?= $profile['age']; ?>;

	function multiple_value(selectId = '#pains', inputId = '#model_value') {
		const select = $(selectId).val();
		let text = select.join()
		$(inputId).val(text);
	}




	// TODO: the fucking services start
	// Update service_price_resto to accept a callback function
	function service_price_resto(serviceSelectId = '#services_restorative', hiddenInputId = '#services_input_restorative', priceInputId = '#price_tooth_restorative', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Update service_price to accept a callback function
	function service_price(serviceSelectId = '#services', hiddenInputId = '#services_input', priceInputId = '#price_tooth', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}


	function service_price_pro(serviceSelectId = '#services_pro', hiddenInputId = '#services_input_pro', priceInputId = '#price_tooth_pro', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Function to calculate the sum of values from service_price_resto and service_price
	function calculate_sum() {
		let priceResto = 0;
		let priceService = 0;
		let priceProsthodontics = 0;

		let is_endo = ($('#checkbox_endo').is(':checked')) ? true : false;
		let is_resto = ($('#checkbox_resto').is(':checked')) ? true : false;
		let is_prosthodontics = ($('#checkbox_prosthodontics').is(':checked')) ? true : false;

		// Callback function for service_price_resto
		function handlePriceResto(price) {
			if (is_resto) {
				priceResto = price;
				handleResults();
			}
		}

		function handlePriceProsthodontics(price) {
			if (is_prosthodontics) {
				priceProsthodontics = price;
				handleResults();
			}
		}


		// Callback function for service_price
		function handlePriceService(price) {
			if (is_endo) {
				priceService = price;
				handleResults();
			}
		}

		// Function to handle the results and update the inputs accordingly
		function handleResults() {
			// Calculate the sum
			const sum = priceResto + priceService + priceProsthodontics;

			// Determine which inputs to update based on the values of priceResto and priceService
			if (priceResto === 0 && priceService !== 0) {
				// If priceResto is zero, update only the "priceTag_resto" input
				$('#priceTag_endo').val(sum);
				$('#priceTag_resto').val(sum);
				$('#priceTag_pro').val(sum);
			} else if (priceService === 0 && priceResto !== 0) {
				// If priceService is zero, update only the "priceTag_endo" input
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);

			} else {
				// If both priceResto and priceService have non-zero values, update both inputs
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);
			}
		}

		// Add event listeners to handle changes in price_tooth_restorative and price_tooth inputs
		document.getElementById('price_tooth_restorative').addEventListener('change', () => {
			priceResto = parseFloat(document.getElementById('price_tooth_restorative').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth_pro').addEventListener('change', () => {
			priceProsthodontics = parseFloat(document.getElementById('price_tooth_pro').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth').addEventListener('change', () => {
			priceService = parseFloat(document.getElementById('price_tooth').value) || 0;
			handleResults();
		});

		// Call the functions with the appropriate callback functions
		service_price_resto('#services_restorative', '#services_input_restorative', '#price_tooth_restorative', handlePriceResto);
		service_price('#services', '#services_input', '#price_tooth', handlePriceService);
		service_price_pro('#services_pro', '#services_input_pro', '#price_tooth_pro', handlePriceProsthodontics);
	}

	// TODO: the fucking services end


	let pains = document.getElementById('pains').innerHTML;
	let doctor_id = document.getElementById('doctor_id').innerHTML;
	let gender = document.getElementById('gender').innerHTML;

	function edit_profile(id = <?= $profile['id'] ?>) {
		$.ajax({
			url: "<?= base_url('admin/edit_patient') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug').val(result['content']['slug']);
					$('#name').val(result['content']['name']);
					$('#lname').val(result['content']['lname']);
					$('#age').val(result['content']['age']);
					$('#phone1').val(result['content']['phone1']);
					$('#phone2').val(result['content']['phone2']);
					$('#other_pains').val(result['content']['other_pains']);
					$('#address').val(result['content']['address']);
					$('#remarks').val(result['content']['remarks']);
					$('#model_value').val(result['content']['pains']);
					let pains_selected = result['content']['pains_select'];
					let pains_new = pains;
					pains_selected.map((item) => {
						pains_new = pains_new.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					});
					$("#pains").html(pains_new);

					let gender_new = gender;
					gender_new = gender_new.replace(`<option value="${result['content']['gender']}">`, `<option value="${result['content']['gender']}" selected>`);
					$("#gender").html(gender_new);


					let doctor = doctor_id;
					doctor = doctor.replace(`<option value="${result['content']['doctor_id']}">`, `<option value="${result['content']['doctor_id']}" selected>`);
					$("#doctor_id").html(doctor);


					// select_with_search('edit_profile');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		jalaliDatepicker.startWatch();
	});
</script>

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
</script>


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
</script>

<script>
	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}
</script>

<script>
	function print_payment(turnId) {
		update_balance();
		window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
	}
</script>

<script>
	function print_lab(labId) {
		window.open(`<?= base_url() ?>admin/print_lab/${labId}`, '_blank');
	}
</script>

<script>
	function print_prescription(prescriptionId) {
		window.open(`<?= base_url() ?>admin/print_prescription/${prescriptionId}`, '_blank');
	}
</script>

<script>
	function payment_modal_clicked() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/list_turns_pending') ?>",
			type: 'POST',
			data: {
				slug: patient_id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					let turns = result['content']['turns'];
					let template = ``;
					if (turns.length > 0) {
						turns.map((item) => {
							template += `<option value="${item['id']}">${item['date']} - ${item['hour']}</option>`;
						})
					} else {
						template += `<option></option>`;
					}
					$('#select_turns').html(template);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>


<script>
	function update_balance() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/balance_json') ?>",
			type: 'POST',
			data: {
				record: patient_id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					let balanceTemplate =
						`
          <div class="d-flex align-items-center justify-content-between w-100">
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0" id="sum_fees">${result['balance']['sum_dr']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('fees') ?></p>
            </div>
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0" id="sum_paid">${result['balance']['sum_cr']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('paid') ?></p>
            </div>
            <div class="py-3 w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0 ${((result['balance']['sum'] > 0) ? `text-danger` : '')}" id="balance">${result['balance']['sum']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('balance') ?></p>
            </div>
          </div>
          `;
					$('#percentage').html(result['balance']['percentage_text']);
					document.querySelector('.progress-bar.bg-primary.ronded-1').style.width = ((result['balance']['sum_dr'] != 0) ? (result['balance']['sum_cr'] * 100) / result['balance']['sum_dr'] : 100) + '%';

					document.querySelector(".card-body.p-0.main-profile-info").innerHTML = balanceTemplate;
				}
			}
		})
	}
</script>

<script>
	function list_teeth() {
		$.ajax({
			url: "<?= base_url('admin/list_teeth_json') ?>",
			type: 'POST',
			data: {
				record: <?= $profile['id'] ?>
			},
			success: function (response) {
				let result = JSON.parse(response);
				let teeth = result.content.teeth;
				if (result['type'] == 'success') {
					if (teeth.length != 0) {
						let tableTemplate =
							`
              <div class="table-responsive">
                <table class="table text-nowrap" id="teethTable">
                  <thead class="tableHead">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col"><?= $ci->lang('tooth name') ?></th>
                      <th scope="col"><?= $ci->lang('tooth location') ?></th>
                      <th scope="col"><?= $ci->lang('diagnose') ?></th>
                      <th scope="col"><?= $ci->lang('services') ?></th>
                      <th scope="col"><?= $ci->lang('pay amount') ?></th>
                      <th scope="col"><?= $ci->lang('actions') ?></th>
                    </tr>
                  </thead>
                  <tbody>`;

						teeth.map((tooth) => {
							tableTemplate +=
								`
                    <tr id="${tooth['id']}" class="tableRow">
                        <td scope="row">${tooth['number']}</td>
                        <td>${tooth['name']}</td>
                        <td>${tooth['location']}</td>
                        <td>${tooth['diagnose']}</td>
                        <td>${tooth['services']}</td>
                        <td>${tooth['price']}</td>
                        <td>
                          <div class="g-2">
                            <a href="javascript:updateTeeth('${tooth['id']}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-pen-to-square fs-14"></span></a>
                            <a href="javascript:delete_via_alert('${tooth['id']}', '<?= base_url() ?>admin/delete_tooth', 'teethTable', update_balance)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-trash-can fs-14"></span></a>
                          </div>
                        </td>
                      </tr>
                    `;
						})

						tableTemplate +=
							`
                  </tbody>
                </table>
              </div>
            `;
						$('#teeth_list').html(tableTemplate);
						update_balance();
					} else {
						var tableTemplate = ``;
						$('#teeth_list').html(tableTemplate);
					}
					// $('#teeth_list').html(tableTemplate);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		});
	}
</script>
