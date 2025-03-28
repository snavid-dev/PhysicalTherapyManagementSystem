<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0">
					<?= $ci->lang('turns list') ?>
				</h3>
			</div>
			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">

							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('date') ?>:
								</h6>
							</li>

							<li style="width: 150px;">
								<input data-jdp type="text" class="form-control" id="datePicker"
									   onchange="tableFilter()" placeholder="<?= $ci->lang('date') ?>"
									   autocomplete="off">
							</li>

							<li style="width: 180px;">
								<select name="doctor_id"
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>"
										id="doctor"
										onchange="tableFilter()">
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="0">
										<?= $ci->lang("all") ?>
									</option>
									<?php foreach ($doctors as $doctor) : ?>
										<option value="<?= $doctor['id'] ?>">
											<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</li>

							<li>
								<button class="btn btn-success" onclick="sendSms()">
									<?= $ci->lang('sms') ?> <i class="fa fa-sms" style="font-size: 15px;"></i>
								</button>
							</li>

							<li>
								<?php if ($ci->auth->has_permission('Create Turn')): ?>
									<button class="btn btn-primary" data-bs-toggle="modal"
											data-bs-target="#turnsModal">
										<?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
									</button>
								<?php endif; ?>
							</li>


						</ul>
						<!-- Modal Button -->


						<!-- turns Modal -->
						<?php if ($ci->auth->has_permission('Create Turn')): ?>
							<div class="modal fade effect-scale" id="turnsModal" role="dialog">
								<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">
												<?= $ci->lang('insert turn') ?>
											</h5>
											<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">
											<form id="insertTurn">
												<div class="row">

													<div class="col-sm-12 col-md-4">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('patient name') ?> <span class="text-red">*</span>
															</label>
															<select name="patient_id"
																	class="form-control select2-show-search form-select"
																	data-placeholder="<?= $ci->lang('select') ?>"
																	id="patientName"
																	onchange="check_turns()">
																<option label="<?= $ci->lang('select') ?>"></option>
																<?php foreach ($patients as $patient) : ?>
																	<option value="<?= $patient['id'] ?>">
																		<?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
																	</option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>


													<div class="col-sm-12 col-md-4">
														<div class="form-group jdp" id="main-div">
															<label class="form-label">
																<?= $ci->lang('date') ?> <span class="text-red">*</span>
															</label>
															<input data-jdp type="text" id="test-date-id-date"
																   name="date"
																   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
																   class="form-control"
																   placeholder="<?= $ci->lang('date') ?>"
																   onchange="check_turns()">
															<div></div>
														</div>
													</div>

													<div class="col-sm-12 col-md-4">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('reference doctor') ?> <span
																	class="text-red">*</span>
															</label>
															<select name="doctor_id"
																	class="form-control select2-show-search form-select"
																	data-placeholder="<?= $ci->lang('select') ?>"
																	id="doctorName"
																	onchange="check_turns()">
																<option label="<?= $ci->lang('select') ?>"></option>
																<option value="all">
																	<?= $ci->lang("select") ?>
																</option>
																<?php foreach ($doctors as $doctor) : ?>
																	<option value="<?= $doctor['id'] ?>">
																		<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
																	</option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('hour') ?> <span class="text-red">*</span>
															</label>
															<input type="time" name="from_time" list="from_times"
																   class="form-control"
																   id="from_time">
															<datalist id="from_times">
																<option value="08:00">
																<option value="08:30">
																<option value="09:00">
																<option value="09:30">
																<option value="10:00">
																<option value="10:30">
																<option value="11:00">
																<option value="11:30">
																<option value="12:00">
																<option value="12:30">
																<option value="13:00">
																<option value="13:30">
																<option value="14:00">
																<option value="14:30">
																<option value="15:00">
																<option value="15:30">
																<option value="16:00">
															</datalist>
														</div>
													</div>
													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('hour') ?> <span class="text-red">*</span>
															</label>
															<input name="to_time" class="form-control" type="time"
																   list="from_times"
																   id="to_time">
														</div>
													</div>
												</div>
											</form>
											<div class="row">
												<div class="col-md-12">
													<div class="table-responsive" id="queryTable">

													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button class="btn btn-secondary" data-bs-dismiss="modal">
												<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
											</button>
											<button class="btn btn-warning"
													onclick="xhrSubmit('insertTurn', '<?= base_url() ?>admin/insert_turn', 'table', 'turnsModal', print_turn, false, 'print');">
												<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i>
											</button>
											<button class="btn btn-primary"
													onclick="xhrSubmit('insertTurn', '<?= base_url() ?>admin/insert_turn', 'table', 'turnsModal');">
												<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<!-- turns Modal End -->


					</div>
				</div>
				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0">
								<?= $ci->lang('patient name') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('reference doctor') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('date') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('hour') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('actions') ?>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($receipts as $receipt) : ?>
							<tr id="<?= $receipt['id'] ?>">
								<td>
									<?= $i ?>
								</td>
								<td>
									<?= $ci->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], $receipt['serial_id'], $receipt['gender']) ?>
								</td>
								<td>
									<?= $receipt['doctor_name'] ?>
								</td>
								<td class="english">
									<?= $receipt['date'] ?>
								</td>
								<td>
									<bdo dir="ltr"><?= $receipt['from_time'] ?> - <?= $receipt['to_time'] ?></bdo>
								</td>
								<td>
									<div class="g-2">
										<a href="<?= base_url() ?>admin/single_patient/<?= $receipt['patient_id'] ?>"
										   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-user-circle-o fs-14"></span></a>
										<a href="javascript:print_turn('<?= $receipt['id'] ?>', '<?= base_url() ?>admin/delete_turn')"
										   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-print"></span></a>
									</div>
								</td>
							</tr>
							<?php $i++;
						endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Row -->

<!-- Modal pay turn -->
<div class="modal fade effect-scale" id="paymentModal_turns" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('insert payment') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertPayment">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('amount') ?> <span class="text-red">*</span>
									</label>
									<input type="hidden" class="form-control" id="turnsPaymentInfo" name="slug">
									<input type="number" name="cr" class="form-control"
										   placeholder="<?= $ci->lang('amount') ?>" id="" autocomplete="off">
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>
				<button class="btn btn-warning"
						onclick="update_and_delete('insertPayment', '<?= base_url() ?>admin/pay_turn', 'paymentModal_turns', print_payment, 'print');">
					<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->

<script>
	function turnPayment(id) {

		$(`#turnsPaymentInfo`).val(id);


		$(`#paymentModal_turns`).modal('toggle');
	}
</script>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		// jalaliDatepicker.startWatch();
		jalaliDatepicker.startWatch();
	});
</script>


<script>
	function edit_account(id) {

		$.ajax({
			url: "<?= base_url('admin/single_account') ?>",
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
					$('#type').val(result['content']['type']);
					$('#editModal').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		})
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
	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}
</script>


<script>
	function tableFilter(date = '#datePicker', doctor = '#doctor') {
		newDate = $(date).val();
		newDoctor = $(doctor).val();
		$.ajax({
			url: "<?= base_url('admin/list_turns_json') ?>",
			type: 'POST',
			data: {
				date: newDate,
				doctor: newDoctor,
			},
			success: function (response) {
				let result = JSON.parse(response);
				var table = $('#file-datatable').DataTable();
				table.rows().remove();
				if (result.content.length > 0) {
					result.content.map((item) => {
						item.time = `<bdo dir="ltr">${item.time}</bdo>`;
						let row = table.row.add([
							item.number,
							item.patient_name,
							item.doctor_name,
							item.date,
							item.time,
							` <div class="g-2">
              <a href="<?= base_url() ?>admin/single_patient/${item.patient_id}"
                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-user-circle-o fs-14"></span></a>
                      <a href="javascript:print_turn('${item.id}', '<?= base_url() ?>admin/delete_turn')"
                        class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-print"></span></a>
                      </div>`
						]).node();
						row.id = item.id;
					});
				}

				table.draw(false);
			}
		});
	}
</script>

<script>
	function print_payment(turnId) {
		window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
	}

	function sendSms() {
		let dates = $('#datePicker').val();
		console.log(dates);
		if (dates == '') {
			return;
		} else {
			$.ajax({
				url: "<?= base_url('admin/sms_turns_list') ?>",
				type: 'POST',
				data: {
					date: dates,
				},
				success: function (response) {
					var field = JSON.parse(response);
					if (field['alert']['type'] == 'success') {
						$.growl.notice1({
							title: field['alert']['title'],
							message: field['alert']['text']
						});
					} else {
						$.growl.error1({
							title: field['alert']['title'],
							message: field['alert']['text']
						});
					}
				}
			})
		}
	}
</script>


