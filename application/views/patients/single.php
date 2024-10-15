<?php $ci = get_instance(); ?>
<!-- Start::row-1 -->
<div class="row">
	<!--Patient Information Card -->
	<?php $ci->render('patients/components/patient_info/patient.php') ?>

	<?php $ci->render('patients/components/main/tabs.php'); ?>

	<?php $ci->render('patients/components/status/status.php') ?>
</div>
<!--End::row-1 -->

<script>
	var ageOld = <?= $profile['age']; ?>;

	function multiple_value(selectId = '#pains', inputId = '#model_value') {
		const select = $(selectId).val();
		let text = select.join()
		$(inputId).val(text);
	}

	// TODO: show prices indo
	// function service_price(serviceSelectId = '#services', hiddenInputId = '#services_input', priceInputId = '#price_tooth') {
	//   const select = $(serviceSelectId).val();
	//   let text = select.join();
	//   $(hiddenInputId).val(text);

	//   $.ajax({
	//     url: "<?= base_url('admin/check_service_price') ?>",
	//     type: 'POST',
	//     data: {
	//       service: select
	//     },
	//     success: function(response) {
	//       let result = JSON.parse(response);
	//       $(priceInputId).val(result[0]);

	//     }
	//   })
	// }

	// TODO: show prices restorative
	// function service_price_resto(serviceSelectId = '#services_restorative', hiddenInputId = '#services_input_restorative', priceInputId = '#price_tooth_restorative') {
	//   const select = $(serviceSelectId).val();
	//   let text = select.join();
	//   $(hiddenInputId).val(text);

	//   $.ajax({
	//     url: "<?= base_url('admin/check_service_price') ?>",
	//     type: 'POST',
	//     data: {
	//       service: select
	//     },
	//     success: function(response) {
	//       let result = JSON.parse(response);
	//       $(priceInputId).val(result[0]);
	//     }
	//   })
	// }


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



<script>
	function imageChange(toothNameId, toothLocationId, imagePlace, tittleId, imgAddress) {
		var toothName = $(toothNameId).val();
		var toothLocation = $(toothLocationId).val();
		// console.log(`id: ${typeof toothName} and location ${typeof toothLocation}`);

		if (typeof toothName === 'string' && !isNaN(toothName)) {
			// up q1 --------------------------------------------------------------------------------------------------
			if (toothName == "1" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png');
				$(imgAddress).val('/v2/up/PNG/8upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/7upup.png');
				$(imgAddress).val('/v2/up/PNG/7upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/6upup.png');
				$(imgAddress).val('/v2/up/PNG/6upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/5upup.png');
				$(imgAddress).val('/v2/up/PNG/5upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/4upup.png');
				$(imgAddress).val('/v2/up/PNG/4upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/3upup.png');
				$(imgAddress).val('/v2/up/PNG/3upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/2upup.png');
				$(imgAddress).val('/v2/up/PNG/2upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png');
				$(imgAddress).val('/v2/up/PNG/1upup.png');
				$(tittleId).text(toothName);
			}
			// up q2--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/9upup.png');
				$(imgAddress).val('/v2/up/PNG/9upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/10upup.png');
				$(imgAddress).val('/v2/up/PNG/10upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/11upup.png');
				$(imgAddress).val('/v2/up/PNG/11upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/12upup.png');
				$(imgAddress).val('/v2/up/PNG/12upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/13upup.png');
				$(imgAddress).val('/v2/up/PNG/13upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/14upup.png');
				$(imgAddress).val('/v2/up/PNG/14upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/15upup.png');
				$(imgAddress).val('/v2/up/PNG/15upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/16upup.png');
				$(imgAddress).val('/v2/up/PNG/16upup.png');
				$(tittleId).text(toothName);
			}
			// down q1--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/24do.png');
				$(imgAddress).val('/v2/down/PNG/24do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/23do.png');
				$(imgAddress).val('/v2/down/PNG/23do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/22do.png');
				$(imgAddress).val('/v2/down/PNG/22do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/21do.png');
				$(imgAddress).val('/v2/down/PNG/21do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/20do.png');
				$(imgAddress).val('/v2/down/PNG/20do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/19do.png');
				$(imgAddress).val('/v2/down/PNG/19do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/18do.png');
				$(imgAddress).val('/v2/down/PNG/18do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/17do.png');
				$(imgAddress).val('/v2/down/PNG/17do.png');
				$(tittleId).text(toothName);
			}
			// down q1--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/25do.png');
				$(imgAddress).val('/v2/down/PNG/25do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/26do.png');
				$(imgAddress).val('/v2/down/PNG/26do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/27do.png');
				$(imgAddress).val('/v2/down/PNG/27do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/28do.png');
				$(imgAddress).val('/v2/down/PNG/28do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/29do.png');
				$(imgAddress).val('/v2/down/PNG/29do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/30do.png');
				$(imgAddress).val('/v2/down/PNG/30do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/31do.png');
				$(imgAddress).val('/v2/down/PNG/31do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/32do.png');
				$(imgAddress).val('/v2/down/PNG/32do.png');
				$(tittleId).text(toothName);
			}
		} else if (typeof toothName === 'string' && /^[a-tA-T]+$/.test(toothName)) {
			// up q1 -baby --------------------------------------------------------------------------------------------------
			if (toothName == "A" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/f.png');
				$(imgAddress).val('/v2/baby/f.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/g.png');
				$(imgAddress).val('/v2/baby/g.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/h.png');
				$(imgAddress).val('/v2/baby/h.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/i.png');
				$(imgAddress).val('/v2/baby/i.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/j.png');
				$(imgAddress).val('/v2/baby/j.png');
				$(tittleId).text(toothName);
			}
			// up q2 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/e.png');
				$(imgAddress).val('/v2/baby/e.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/d.png');
				$(imgAddress).val('/v2/baby/d.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/c.png');
				$(imgAddress).val('/v2/baby/c.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/b.png');
				$(imgAddress).val('/v2/baby/b.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/a.png');
				$(imgAddress).val('/v2/baby/a.png');
				$(tittleId).text(toothName);
			}
			// down q1 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/o.png');
				$(imgAddress).val('/v2/baby/o.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/n.png');
				$(imgAddress).val('/v2/baby/n.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/m.png');
				$(imgAddress).val('/v2/baby/m.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/l.png');
				$(imgAddress).val('/v2/baby/l.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/k.png');
				$(imgAddress).val('/v2/baby/k.png');
				$(tittleId).text(toothName);
			}
			// down q2 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/p.png');
				$(imgAddress).val('/v2/baby/p.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/q.png');
				$(imgAddress).val('/v2/baby/q.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/r.png');
				$(imgAddress).val('/v2/baby/r.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/s.png');
				$(imgAddress).val('/v2/baby/s.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/t.png');
				$(imgAddress).val('/v2/baby/t.png');
				$(tittleId).text(toothName);
			}
		} else {

		}
	}
</script>


<script>
	function request(selectId = "#selectTeeth") {

		$.ajax({
			url: "<?= base_url('admin/patient_teeth') ?>",
			type: 'POST',
			data: {
				id: <?= $profile["id"] ?>,
			},
			success: function (response) {
				let result = JSON.parse(response);
				let options = "";
				if (result.type == "success") {
					result.teeth.map((item) => {
						options += `<option value="${item.id}">${item.name} (${item.location})</option>`
					})
				}
				$(selectId).html(options);


			}
		});


	}

	function imageReplacer() {

	}
</script>


<!-- Modal start laboratoryIEditModal_Script -------------------------------------------------------------------------------------------------->
<script>
	function edit_lab(id) {
		$.ajax({
			url: "<?= base_url('admin/single_lab') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				request('#selectTeeth_edit');
				let results = JSON.parse(response);
				let data = results.content;
				// console.log(data);

				$('#selectLab_edit').val(data.lab_id).trigger('change');


				let tooth = data.teeth;
				var teethInnerHTML = document.getElementById("selectTeeth_edit").innerHTML;
				tooth.map((item) => {
					console.log(item);
					teethInnerHTML = teethInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
				})
				document.getElementById("selectTeeth_edit").innerHTML = teethInnerHTML;
				$('#selectTeethHiddenInput_edit').val(data.teeth_hidden);


				let toothtypes = data.types;
				var toothtypesInnerHTML = document.getElementById("selectToothType_edit").innerHTML;
				toothtypes.map((item) => {
					console.log(`this is the items: ${item}`);
					toothtypesInnerHTML = toothtypesInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
				})
				document.getElementById("selectToothType_edit").innerHTML = toothtypesInnerHTML;
				$('#selectToothTypeHiddenInput_edit').val(data.types_hidden);


				$('#deliveryDate_edit').val(data.delivery_date).trigger('change');
				$('#deliveryTime_edit').val(data.delivery_time).trigger('change');
				$('#selectToothColor_edit').val(data.tooth_color).trigger('change');
				$('#payment_edit').val(data.pay_amount).trigger('change');
				$('#details_edit').val(data.remarks).trigger('change');
				$('#labIdSlug').val(id);
			}
		})


		$(`#laboratoryEditModal`).modal('toggle');
	}
</script>
<!-- Modal End laboratoryEdittModal_Script -------------------------------------------------------------------------------------------------->


<!-- list labs function---start--- -->
<script>
	function list_labs() {
		$.ajax({
			url: "<?= base_url('admin/list_labs_json') ?>",
			type: 'POST',
			data: {
				record: <?= $profile['id'] ?>
			},
			success: function (response) {
				let result = JSON.parse(response);
				let labs = result.content.labs;
				if (result['type'] == 'success') {
					if (labs.length != 0) {
						let tableTemplate =
							`
              <div class="table-responsive">
                <table class="table text-nowrap" id="labsTable">
                  <thead class="tableHead">
                    <tr>
                    <th scope="col">#</th>
                          <th scope="col"><?= $ci->lang('laboratory') ?></th>
                          <th scope="col"><?= $ci->lang('teeth') ?></th>
                          <th scope="col"><?= $ci->lang('tooth type') ?></th>
                          <th scope="col"><?= $ci->lang('delivery date') ?></th>
                          <th scope="col"><?= $ci->lang('delivery time') ?></th>
                          <th scope="col"><?= $ci->lang('pay amount') ?></th>
                          <th scope="col"><?= $ci->lang('desc') ?></th>
                          <th scope="col"><?= $ci->lang('actions') ?></th>
                    </tr>
                  </thead>
                  <tbody>`;

						labs.map((lab) => {
							tableTemplate +=
								`
                    <tr id="${lab['id']}" class="tableRow">
                        <td scope="row">${lab['number']}</td>
                        <td>${lab['lab_name']}</td>
                        <td>${lab['teeth']}</td>
                        <td>${lab['type']}</td>
                        <td>${lab['delivery_date']}</td>
                        <td>${lab['delivery_time']}</td>
                        <td>${lab['pay_amount']}</td>
                        <td>${lab['remarks']}</td>
                        <td>
                          <div class="g-2">
                            <a href="javascript:edit_lab('${lab['id']}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-pen-to-square fs-14"></span></a>
                            <a href="javascript:print_lab('${lab['id']}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-print fs-14"></span></a>
                            <a href="javascript:delete_via_alert('${lab['id']}', '<?= base_url() ?>admin/delete_lab', 'labsTable')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-trash-can fs-14"></span></a>
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
						$('#posts-tab-pane').html(tableTemplate);
						update_balance();
					} else {
						var tableTemplate = ``;
						$('#posts-tab-pane').html(tableTemplate);
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
<!-- list labs function---end--- -->


<script>
	function getMedicienInfo(id, dozeId, unitId, usageId, dayId, timeId, amountId) {
		$.ajax({
			url: "<?= base_url('admin/single_medicine') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;
				$('#' + dozeId).val(medicienDatas.doze);
				$('#' + unitId).val(medicienDatas.unit).trigger('change');
				$('#' + usageId).val(medicienDatas.usageType).trigger('change');
				$('#' + dayId).val(medicienDatas.day);
				$('#' + timeId).val(medicienDatas.times);
				$('#' + amountId).val(medicienDatas.amount);
			}
		})

	}
</script>


<script>
	function viewPrescriptionsMedicines(id) {
		$.ajax({
			url: "<?= base_url('admin/single_prescription') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;

				// row1 -------------
				if (medicienDatas.medicine_1 != 0) {
					$("#prescription_row1").show();
					$('#view_medicine1').val(medicienDatas.medicine_1).trigger('change');
					$('#medicineDoze_Rx1_view').val(medicienDatas.doze_1);
					$('#medicineUnite_Rx1_view').val(medicienDatas.unit_1).trigger('change');
					$('#view_medicineUsage1').val(medicienDatas.usageType_1).trigger('change');
					$('#view_medicineDay1').val(medicienDatas.day_1);
					$('#view_medicineTime1').val(medicienDatas.time_1);
					$('#view_medicineAmount1').val(medicienDatas.amount_1);
				} else {
					$("#prescription_row1").hide();
				}
				// row1 -------------


				// row2 -------------
				if (medicienDatas.medicine_2 != 0) {
					$("#prescription_row2").show();
					$('#view_medicine2').val(medicienDatas.medicine_2).trigger('change');
					$('#medicineDoze_Rx2_view').val(medicienDatas.doze_2);
					$('#medicineUnite_Rx2_view').val(medicienDatas.unit_2).trigger('change');
					$('#view_medicineUsage2').val(medicienDatas.usageType_2).trigger('change');
					$('#view_medicineDay2').val(medicienDatas.day_2);
					$('#view_medicineTime2').val(medicienDatas.time_2);
					$('#view_medicineAmount2').val(medicienDatas.amount_2);
				} else {
					$("#prescription_row2").hide();
				}
				// row2 -------------


				// row3 -------------
				if (medicienDatas.medicine_3 != 0) {
					$("#prescription_row3").show();
					$('#view_medicine3').val(medicienDatas.medicine_3).trigger('change');
					$('#medicineDoze_Rx3_view').val(medicienDatas.doze_3);
					$('#medicineUnite_Rx3_view').val(medicienDatas.unit_3).trigger('change');
					$('#view_medicineUsage3').val(medicienDatas.usageType_3).trigger('change');
					$('#view_medicineDay3').val(medicienDatas.day_3);
					$('#view_medicineTime3').val(medicienDatas.time_3);
					$('#view_medicineAmount3').val(medicienDatas.amount_3);
				} else {
					$("#prescription_row3").hide();
				}
				// row3 -------------


				// row4 -------------
				if (medicienDatas.medicine_4 != 0) {
					$("#prescription_row4").show();
					$('#view_medicine4').val(medicienDatas.medicine_4).trigger('change');
					$('#medicineDoze_Rx4_view').val(medicienDatas.doze_4);
					$('#medicineUnite_Rx4_view').val(medicienDatas.unit_4).trigger('change');
					$('#view_medicineUsage4').val(medicienDatas.usageType_4).trigger('change');
					$('#view_medicineDay4').val(medicienDatas.day_4);
					$('#view_medicineTime4').val(medicienDatas.time_4);
					$('#view_medicineAmount4').val(medicienDatas.amount_4);
				} else {
					$("#prescription_row4").hide();
				}
				// row4 -------------


				// row5 -------------
				if (medicienDatas.medicine_5 != 0) {
					$("#prescription_row5").show();
					$('#view_medicine5').val(medicienDatas.medicine_5).trigger('change');
					$('#medicineDoze_Rx5_view').val(medicienDatas.doze_5);
					$('#medicineUnite_Rx5_view').val(medicienDatas.unit_5).trigger('change');
					$('#view_medicineUsage5').val(medicienDatas.usageType_5).trigger('change');
					$('#view_medicineDay5').val(medicienDatas.day_5);
					$('#view_medicineTime5').val(medicienDatas.time_5);
					$('#view_medicineAmount5').val(medicienDatas.amount_5);
				} else {
					$("#prescription_row5").hide();
				}
				// row5 -------------


				// row6 -------------
				if (medicienDatas.medicine_6 != 0) {
					$("#prescription_row6").show();
					$('#view_medicine6').val(medicienDatas.medicine_6).trigger('change');
					$('#medicineDoze_Rx6_view').val(medicienDatas.doze_6);
					$('#medicineUnite_Rx6_view').val(medicienDatas.unit_6).trigger('change');
					$('#view_medicineUsage6').val(medicienDatas.usageType_6).trigger('change');
					$('#view_medicineDay6').val(medicienDatas.day_6);
					$('#view_medicineTime6').val(medicienDatas.time_6);
					$('#view_medicineAmount6').val(medicienDatas.amount_6);
				} else {
					$("#prescription_row6").hide();
				}
				// row6 -------------


				// row7 -------------
				if (medicienDatas.medicine_7 != 0) {
					$("#prescription_row7").show();
					$('#view_medicine7').val(medicienDatas.medicine_7).trigger('change');
					$('#medicineDoze_Rx7_view').val(medicienDatas.doze_7);
					$('#medicineUnite_Rx7_view').val(medicienDatas.unit_7).trigger('change');
					$('#view_medicineUsage7').val(medicienDatas.usageType_7).trigger('change');
					$('#view_medicineDay7').val(medicienDatas.day_7);
					$('#view_medicineTime7').val(medicienDatas.time_7);
					$('#view_medicineAmount7').val(medicienDatas.amount_7);
				} else {
					$("#prescription_row7").hide();
				}
				// row7 -------------


				// row8 -------------
				if (medicienDatas.medicine_8 != 0) {
					$("#prescription_row8").show();
					$('#view_medicine8').val(medicienDatas.medicine_8).trigger('change');
					$('#medicineDoze_Rx8_view').val(medicienDatas.doze_8);
					$('#medicineUnite_Rx8_view').val(medicienDatas.unit_8).trigger('change');
					$('#view_medicineUsage8').val(medicienDatas.usageType_8).trigger('change');
					$('#view_medicineDay8').val(medicienDatas.day_8);
					$('#view_medicineTime8').val(medicienDatas.time_8);
					$('#view_medicineAmount8').val(medicienDatas.amount_8);
				} else {
					$("#prescription_row8").hide();
				}
				// row8 -------------


				// row9 -------------
				if (medicienDatas.medicine_9 != 0) {
					$("#prescription_row9").show();
					$('#view_medicine9').val(medicienDatas.medicine_9).trigger('change');
					$('#medicineDoze_Rx9_view').val(medicienDatas.doze_9);
					$('#medicineUnite_Rx9_view').val(medicienDatas.unit_9).trigger('change');
					$('#view_medicineUsage9').val(medicienDatas.usageType_9).trigger('change');
					$('#view_medicineDay9').val(medicienDatas.day_9);
					$('#view_medicineTime9').val(medicienDatas.time_9);
					$('#view_medicineAmount9').val(medicienDatas.amount_9);
				} else {
					$("#prescription_row9").hide();
				}
				// row9 -------------


				// row10 -------------
				if (medicienDatas.medicine_10 != 0) {
					$("#prescription_row10").show();
					$('#view_medicine10').val(medicienDatas.medicine_10).trigger('change');
					$('#medicineDoze_Rx10_view').val(medicienDatas.doze_10);
					$('#medicineUnite_Rx10_view').val(medicienDatas.unit_10).trigger('change');
					$('#view_medicineUsage10').val(medicienDatas.usageType_10).trigger('change');
					$('#view_medicineDay10').val(medicienDatas.day_10);
					$('#view_medicineTime10').val(medicienDatas.time_10);
					$('#view_medicineAmount10').val(medicienDatas.amount_10);
				} else {
					$("#prescription_row10").hide();
				}
				// row10 -------------
			}
		});

		$(`#viewPrescriptionsMedicines`).modal('toggle');
	}
</script>


<!--  TODO the checkboxes sctipt -->

<script>
	// Get the checkboxes, divs, and buttons
	const checkboxes = document.querySelectorAll('.checkbox');
	const div1 = document.getElementById('Restorative-pane');
	const div2 = document.getElementById('endo-pane');
	const div3 = document.getElementById('pros-pane');
	const button1 = document.getElementById('Restorative');
	const button2 = document.getElementById('endo');
	const button3 = document.getElementById('pros-tab');

	// Function to check if a checkbox is unchecked
	function isCheckboxUnchecked(checkbox) {
		return !checkbox.checked;
	}

	// Function to lock or unlock a specific div based on checkbox state
	function lockOrUnlockDiv(div, checkbox) {
		if (isCheckboxUnchecked(checkbox)) {
			div.classList.add('locked');
		} else {
			div.classList.remove('locked');
		}
	}

	// Function to lock or unlock a specific button based on div state
	function lockOrUnlockButton(button, div) {
		if (div.classList.contains('locked')) {
			button.disabled = true;
		} else {
			button.disabled = false;
		}
	}

	// Add event listener to each checkbox
	checkboxes.forEach((checkbox, index) => {
		checkbox.addEventListener('change', function () {
			if (index === 0) {
				lockOrUnlockDiv(div1, checkbox);
				lockOrUnlockButton(button1, div1);
			} else if (index === 1) {
				lockOrUnlockDiv(div2, checkbox);
				lockOrUnlockButton(button2, div2);
			} else if (index === 2) {
				lockOrUnlockDiv(div3, checkbox);
				lockOrUnlockButton(button3, div3);
			}
		});
	});

	// Initially lock or unlock the divs and buttons based on checkbox state
	lockOrUnlockDiv(div1, checkboxes[0]);
	lockOrUnlockDiv(div2, checkboxes[1]);
	lockOrUnlockDiv(div3, checkboxes[2]);
	lockOrUnlockButton(button1, div1);
	lockOrUnlockButton(button2, div2);
	lockOrUnlockButton(button3, div3);
</script>

<!-- TODO: bonding and composite  -->
<script>
	function showBonding(restorativeMaterial_id, compositeDiv_id, bondingDiv_id, amalgamDiv_id) {

		let restorativeMaterial = $(restorativeMaterial_id).val();
		var compositeDiv = document.getElementById(compositeDiv_id);
		var bondingDiv = document.getElementById(bondingDiv_id);
		var amalgamDiv = document.getElementById(amalgamDiv_id);

		if (restorativeMaterial == 1) {
			compositeDiv.style.display = 'block';
			bondingDiv.style.display = 'block';
			amalgamDiv.style.display = 'none';
		} else if (restorativeMaterial == 2) {
			compositeDiv.style.display = 'none';
			bondingDiv.style.display = 'none';
			amalgamDiv.style.display = 'block';
		} else {
			compositeDiv.style.display = 'none';
			bondingDiv.style.display = 'none';
			amalgamDiv.style.display = 'none';
		}
	}
</script>


<!-- TODO new edit -->
<!-- TODO: AJAX -->

<!-- edit function new ------------------------ start-->
<script>
	function updateTeeth(id) {
		$.ajax({
			url: "<?= base_url('admin/single_tooth') ?>",
			type: 'POST',
			data: {
				slug: id,
			},
			success: function (response) {
				let result = JSON.parse(response);
				let contents = result.content;
				console.log(contents);
				if (typeof contents.name === 'string' && !isNaN(contents.name)) {

					let alldiagnose = contents.diagnose;
					var diagnoses_select = document.getElementById("select_diagnose_update").innerHTML;
					alldiagnose.map((item) => {
						diagnoses_select = diagnoses_select.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					})
					document.getElementById("select_diagnose_update").innerHTML = diagnoses_select;


					$('#modalImage2_update_restro').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${contents.imgAddress}`);


					// added by navid
					$('#selectName_update').val(contents.name).trigger('change');
					$('#locationSelector_update').val(contents.location).trigger('change');


					if (contents.is_endo === "true") {
						$('#canalselector_update').val(contents.endo.root_number).trigger('change');


						$('#canalLocation1_update').val(contents.endo.r_name1).trigger('change');
						$('#c_length1_update').val(contents.endo.r_width1).trigger('change');

						$('#canalLocation2_update').val(contents.endo.r_name2).trigger('change');
						$('#c_length2_update').val(contents.endo.r_width2).trigger('change');

						$('#canalLocation3_update').val(contents.endo.r_name3).trigger('change');
						$('#c_length3_update').val(contents.endo.r_width3).trigger('change');

						$('#canalLocation4_update').val(contents.endo.r_name4).trigger('change');
						$('#c_length4_update').val(contents.endo.r_width4).trigger('change');

						$('#canalLocation5_update').val(contents.endo.r_name5).trigger('change');
						$('#c_length5_update').val(contents.endo.r_width5).trigger('change');

						let endoServices = contents.endo.services;
						var updateInnerHTML = document.getElementById("services_update").innerHTML;
						endoServices.map((item) => {
							updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_update").innerHTML = updateInnerHTML;


						$('#price_tooth_update').val(contents.endo.price).trigger('change');
						$('#details_update').val(contents.endo.details).trigger('change');
						$('#instypeObturation_update').val(contents.endo.typeObturation).trigger('change');
						$('#insTypeSealer_update').val(contents.endo.TypeSealer).trigger('change');
						$('#insTypeIrrigation_update').val(contents.endo.TypeIrrigation).trigger('change');

						$('#modalImage_update').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${contents.imgAddress}`);

					}

					if (contents.is_restorative === "true") {
						$('#insertCariesDepth_update').val(contents.restorative.CariesDepth).trigger('change');
						$('#insertMaterial_update').val(contents.restorative.Material).trigger('change');


						$('#insertRestorativeMaterial_update').val(contents.restorative.RestorativeMaterial).trigger('change');
						$('#insertCompositeBrand_update').val(contents.restorative.CompositeBrand).trigger('change');
						$('#insertBondingBrand_update').val(contents.restorative.bondingBrand).trigger('change');
						$('#insertAmalgamBrand_update').val(contents.restorative.AmalgamBrand).trigger('change');

						let restorativeServices = contents.restorative.services;
						var updateInnerHTML = document.getElementById("services_restorative_update").innerHTML;
						restorativeServices.map((item) => {
							updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_restorative_update").innerHTML = updateInnerHTML;

						$('#price_tooth_restorative_update').val(contents.restorative.price).trigger('change');
						$('#restorative_details_update').val(contents.restorative.details).trigger('change');


					}

					$(`#teethmodal_update`).modal('toggle');
				} else if (typeof contents.name === 'string' && /^[a-tA-T]+$/.test(contents.name)) {

				}
			}
		});


	}
</script>
<!-- edit function new ------------------------ end-->


<script>
	function check_pro_type() {
		let pro_type = $('#type_pro').val();

		if (pro_type == 'abutment') {
			$('.abutment').show();
			$('.pontic').hide();
		} else if (pro_type == 'pontic') {
			$('.abutment').hide();
			$('.pontic').show();
		}
	}
</script>
