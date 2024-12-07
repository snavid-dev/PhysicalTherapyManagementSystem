<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0">
					<?= $ci->lang('patient list') ?>
				</h3>
			</div>

			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">
							<li style="width: 200px">

								<!-- Filter Select -->
								<select id="filterTable" name="type" class="form-control form-select"
										data-placeholder="<?= $ci->lang('select') ?>"
										onchange="tableFilter('#filterTable')">

									<option value="p"><?= $ci->lang('pending') ?></option>
									<option value="a"><?= $ci->lang('accepted') ?></option>
									<option value="b"><?= $ci->lang('blocked') ?></option>

								</select>
								<!-- Filter Select -->


							</li>


							<li>
								<button class="btn btn-primary" data-bs-toggle="modal"
										data-bs-target="#searchModal">
									<?= $ci->lang('search') ?> <i class="fa-solid fa-search"></i>
								</button>
							</li>
							<li>
								<?php if ($ci->auth->has_permission('Create Patient')): ?>
								<button class="btn btn-primary" data-bs-toggle="modal"
										data-bs-target="#extralargemodal">
									<?= $ci->lang('add new') ?> <i class="fa-solid fa-plus"></i>
								</button>
								<?php endif; ?>
							</li>
						</ul>


						<!-- Modal Button -->
						<!-- Modal -->
						<?php if ($ci->auth->has_permission('Create Patient')): ?>
						<div class="modal fade effect-scale" id="extralargemodal" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">
											<?= $ci->lang('insert patient') ?>
										</h5>
										<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="insertAccount">
											<div class="row">
												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('name') ?> <span class="text-red">*</span>
														</label>
														<input type="text" name="name" class="form-control"
															   placeholder="<?= $ci->lang('name') ?>">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('lname') ?> <span class="text-red">*</span>
														</label>
														<input type="text" name="lname" class="form-control"
															   placeholder="<?= $ci->lang('lname') ?>">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('age') ?> <span class="text-red">*</span>
														</label>
														<input type="number" name="age" class="form-control"
															   placeholder="<?= $ci->lang('age') ?>">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('phone') ?> <span class="text-red">*</span>
														</label>
														<input type="text" name="phone1" class="form-control"
															   placeholder="<?= $ci->lang('phone') ?>">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('phone2') ?>
														</label>
														<input type="text" name="phone2" class="form-control"
															   placeholder="<?= $ci->lang('phone2') ?>">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('gender') ?> <span class="text-red">*</span>
														</label>
														<select name="gender" class="form-control form-select">
															<option label="<?= $ci->lang('select') ?>"></option>
															<option value="m">
																<?= $ci->lang('male') ?>
															</option>
															<option value="f">
																<?= $ci->lang('female') ?>
															</option>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('medical history') ?>
														</label>
														<select name="pains_select"
																class="form-control select2-show-search form-select"
																id="model" data-placeholder="<?= $ci->lang('select') ?>"
																multiple onchange="multiple_value()">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($ci->dentist->diseases() as $pain) : ?>
																<option value="<?= $pain ?>">
																	<?= $pain ?>
																</option>
															<?php endforeach; ?>
														</select>
														<input type="hidden" name="pains" id="model_value">
													</div>
												</div>

												<div class="col-sm-12 col-md-3">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('reference doctor') ?>
														</label>
														<select name="doctor_id"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($doctors as $doctor) : ?>
																<option value="<?= $doctor['id'] ?>">
																	<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
																</option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('other diseases') ?>
														</label>
														<textarea type="text" name="other_pains" class="form-control"
																  rows="4"
																  placeholder="<?= $ci->lang('other diseases') ?>"></textarea>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('address') ?>
														</label>
														<textarea type="text" name="address" class="form-control"
																  rows="4"
																  placeholder="<?= $ci->lang('address') ?>"></textarea>
													</div>
												</div>


												<div class="col-sm-12 col-md-4">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('desc') ?>
														</label>
														<textarea type="text" name="remarks" class="form-control"
																  rows="4"
																  placeholder="<?= $ci->lang('desc') ?>"></textarea>
													</div>
												</div>


											</div>
										</form>

									</div>
									<div class="modal-footer">
										<button class="btn btn-secondary" data-bs-dismiss="modal">
											<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
										</button>
										<button class="btn btn-primary"
												onclick="xhr_insert_patient('insertAccount', '<?= base_url() ?>admin/insert_patient','<?= base_url() ?>admin/single_patient/')">
											<?= $ci->lang('save') ?> <i class="fa-solid fa-plus"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<!-- Modal End -->
					</div>
				</div>
				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0">
								<?= $ci->lang('serial id') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('fullname') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('phone1') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('reference doctor') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('medical history') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('other diseases') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('desc') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('actions') ?>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($patients as $patient) : ?>
							<tr id="<?= $patient['id'] ?>">
								<td>
									<?= $i ?>
								</td>
								<td>
									<?= $patient['serial_id'] ?>
								</td>
								<td class="">
									<?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], '', $patient['gender']) ?>
								</td>
								<td class="">
									<?= $patient['phone1'] ?>
								</td>
								<td class="">
									<?= $patient['doctor_name'] ?>
								</td>
								<td class="">
									<?= $patient['pains'] ?>
								</td>
								<td class="">
									<?= $patient['other_pains'] ?>
								</td>
								<td class="">
									<?= $patient['remarks'] ?>
								</td>
								<td>
									<div class="g-2">
										<a href="<?= base_url("admin/single_patient/" . $patient['id'] . "") ?>"
										   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip"
										   data-bs-original-title="<?= $ci->lang('edit') ?>"><span
												class="fa fa-user-circle-o fs-14"></span></a>
										<a href="javascript:print_patient('<?= $patient['id'] ?>')"
										   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip"
										   data-bs-original-title="<?= $ci->lang('print') ?>"><span
												class="fa fa-print fs-14"></span></a>
										<a href="javascript:accept_via_alert('<?= $patient['id'] ?>', '<?= base_url() ?>admin/accept_patient')"
										   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('accept') ?>"><span
												class="fa-regular fa-circle-check fs-14"></span></a>
										<a href="javascript:accept_via_alert('<?= $patient['id'] ?>', '<?= base_url() ?>admin/block_patient')"
										   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip"
										   data-bs-original-title="<?= $ci->lang('block') ?>"><span
												class="fa-solid fa-minus fs-14"></span></a>
										<a href="javascript:delete_via_alert('<?= $patient['id'] ?>', '<?= base_url() ?>admin/delete_patient')"
										   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span
												class="fa-solid fa-trash-can fs-14"></span></a>
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


<!-- Modal -->
<div class="modal fade effect-scale hide" id="searchModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('search') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="insertAccount">
					<div class="row">

						<div id="reader" style="width:500px; height: 500px; display: none"></div>


						<div class="col-sm-12 col-md-1">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('scan') ?>
								</label>
								<button class="btn btn-primary" type="button" onclick="startScanning(list_patients)"><i
										class="fa fa-qrcode"></i></button>
							</div>
						</div>

						<div class="col-sm-12 col-md-5">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('serial id') ?>
								</label>
								<input type="text" class="form-control" id="serial_id" onchange="list_patients()">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label" for="fullname">
									<?= $ci->lang('fullname') ?>
								</label>
								<input type="text" class="form-control" id="fullname" onkeyup="list_patients()"
									   autocomplete="off" name="fullname">
							</div>
						</div>

					</div>
				</form>

				<div class="row" style="margin-top: 2%">
					<div class="col-md-12">
						<div class="table-responsive" id="listpatients">
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
					<i class="fa fa-close"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->


<script>
	function multiple_value() {
		const select = $('#model').val();
		let text = select.join()
		$('#model_value').val(text);
	}
</script>

<script>
	function print_patient(turnId) {
		window.open(`<?= base_url() ?>admin/print_patient/${turnId}`, '_blank');
	}
</script>


<script>
	function tableFilter(selectId) {
		var selectIdValue = $(selectId).val();
		$.ajax({
			url: "<?= base_url('admin/list_patient_json') ?>",
			type: 'POST',
			data: {
				status: selectIdValue,
			},
			success: function (response) {
				let result = JSON.parse(response);
				var table = $('#file-datatable').DataTable();
				table.rows().remove();
				result.content.map((item) => {
					let changeBtnsStatus = ``;

					if (selectIdValue == 'p') {
						changeBtnsStatus +=
							`<a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/accept_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-check-circle fs-14"></span></a>
              <a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/block_patient')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-minus-circle fs-14"></span></a>`;
					} else if (selectIdValue == 'a') {
						changeBtnsStatus +=
							`<a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/pending_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-clock fs-14"></span></a>
              <a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/block_patient')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-minus-circle fs-14"></span></a>`;
					} else {
						changeBtnsStatus +=
							`<a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/pending_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-clock fs-14"></span></a>
            <a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/accept_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-check-circle fs-14"></span></a>`;

					}
					let row = table.row.add([
						item.number,
						item.serial_id,
						item.fullname,
						item.phone,
						item.doctor_name,
						item.history,
						item.other_pains,
						item.remarks,
						` <div class="g-2">
                <a href="<?= base_url("admin/single_patient/") ?>${item.id}" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('edit') ?>"><span class="fa fa-user-circle-o fs-14"></span></a>
                <a href="javascript:print_patient('${item.id}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('print') ?>"><span class="fa fa-print fs-14"></span></a>
                ${changeBtnsStatus}
                <a href="javascript:delete_via_alert('${item.id}', '<?= base_url() ?>admin/delete_patient')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span class="fe fe-trash-2 fs-14"></span></a>
              </div>`
					]).node();
					row.id = item.id;
				});
				table.draw(false);
			}
		});
	}
</script>


<script>
	function list_patients() {
		const serial_id = document.getElementById("serial_id");
		const serial = serial_id.value;

		const fullname = document.getElementById("fullname");
		const full_name = fullname.value;

		$.ajax({
			url: "<?= base_url('admin/list_patients') ?>",
			type: 'POST',
			data: {
				serial_id: serial,
				fullname: full_name
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					if (result['content']['patients'].length < 1) {
						var querytable = ``;
					} else {
						var querytable = `
          <table class="table text-nowrap table-striped">
            <thead>
              <tr>
                <th scope="col">
                  <?= $ci->lang('serial id') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('fullname') ?>
                </th>
                 <th scope="col">
                  <?= $ci->lang('phone1') ?>
                </th>
                 <th scope="col">
                  <?= $ci->lang('medical history') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('reference doctor') ?>
                </th>
              </tr>
            </thead>
            <tbody>
        `;

						result["content"]["patients"].map((item) => {
							querytable += `
            <tr>
              <td dir="ltr" style="text-align:right;">${item.serial_id}</td>
              <td><a href="<?= base_url('admin/single_patient/') ?>${item.id}" target="_blank">${item.fullname}</a></td>
              <td>${item.phone1}</td>
              <td>${item.pains}</td>
              <td>${item.doctor_name}</td>
            </tr>
          `;
						});

						querytable += `
            </tbody>
          </table>
        `;

					}

					document.getElementById("listpatients").innerHTML = querytable;
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
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
		});
	}
</script>
