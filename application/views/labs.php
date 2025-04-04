<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0"><?= $ci->lang('laboratory') ?></h3>
			</div>
			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">


							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('From Date') ?>:
								</h6>
							</li>

							<li style="width: 150px;">
								<input data-jdp type="text" class="form-control" id="from_date"
									   onchange="listLabs()" placeholder="<?= $ci->lang('date') ?>"
									   autocomplete="off">
							</li>

							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('To Date') ?>:
								</h6>
							</li>

							<li style="width: 150px;">
								<input data-jdp type="text" class="form-control" id="to_date"
									   onchange="listLabs()" placeholder="<?= $ci->lang('date') ?>"
									   autocomplete="off" value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>">
							</li>


							<li style="position: relative; top: 9px;">
								<h6><?= $ci->lang('lab name') ?></h6>
							</li>

							<li style="width: 180px">
								<select name=""
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>"
										id="lab_name"
										onchange="listLabs()">
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="0"><?= $ci->lang('all') ?></option>
									<?php foreach ($accounts as $account) : ?>
										<option value="<?= $account['id'] ?>">
											<?= $account['name'] ?> <?= $account['lname'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</li>

							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('payment status') ?>:
								</h6>
							</li>

							<li style="width: 180px;">
								<select
									id="payment_status"
									class="form-control form-select"
									data-placeholder="<?= $ci->lang('select') ?>"
									onchange="listLabs()">
									<option value="0">
										<?= $ci->lang("all") ?>
									</option>

									<option value="paid">
										<?= $ci->lang("paid") ?>
									</option>
									<option value="unpaid" selected>
										<?= $ci->lang("unpaid") ?>
									</option>
									<?php foreach ($doctors as $doctor) : ?>
										<option value="<?= $doctor['id'] ?>">
											<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</li>


							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('case status') ?>:
								</h6>
							</li>

							<li style="width: 450px;">
								<select
									id="case_status"
									class="form-control select2-show-search form-select"
									data-placeholder="<?= $ci->lang('select') ?>"
									onchange="listLabs()" multiple>
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="0" selected>
										<?= $ci->lang("all") ?>
									</option>
									<option value="1">تحویل داده نشده</option>
									<option value="2">تست اول</option>
									<option value="3">تست دوم</option>
									<option value="4">تحویل گرفته شده</option>
									<option value="5">نصب شده</option>
								</select>
							</li>


							<li>
								<?php if ($ci->auth->has_permission('Create New Account')): ?>
									<button class="btn btn-primary" data-bs-toggle="modal"
											data-bs-target="#extralargemodal"><?= $ci->lang('add new') ?> <i
											class="fa fa-plus"></i></button>
								<?php endif; ?>
							</li>
						</ul>
						<!-- Modal Button -->
						<!-- expenses Modal -->
						<?php if ($ci->auth->has_permission('Create New Account')): ?>
							<div class="modal fade effect-scale" id="extralargemodal" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title"><?= $ci->lang('insert account') ?></h5>
											<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">
											<form id="insertAccount">
												<div class="row">
													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label"><?= $ci->lang('name') ?> <span
																	class="text-red">*</span></label>
															<input type="text" name="name" class="form-control"
																   placeholder="<?= $ci->lang('name') ?>">
														</div>
													</div>

													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label"><?= $ci->lang('lname') ?></label>
															<input type="text" name="lname" class="form-control"
																   placeholder="<?= $ci->lang('lname') ?>">
														</div>
													</div>

													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label"><?= $ci->lang('phone') ?></label>
															<input type="number" name="phone" class="form-control"
																   placeholder="<?= $ci->lang('phone') ?>">
														</div>
													</div>

													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label"><?= $ci->lang('account type') ?>
																<span class="text-red">*</span></label>
															<select name="type" class="form-control form-select">
																<option label="<?= $ci->lang('select') ?>"></option>
																<?php foreach ($ci->mylibrary->list_account_type() as $key => $value) : ?>
																	<option
																		value="<?= $key ?>"><?= $ci->lang($value) ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</form>

										</div>
										<div class="modal-footer">
											<button class="btn btn-secondary"
													data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
													class="fa fa-close"></i></button>
											<button class="btn btn-primary"
													onclick="xhrSubmit('insertAccount', '<?= base_url() ?>admin/insert_account')"><?= $ci->lang('save') ?>
												<i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<!-- expenses Modal End -->
					</div>
				</div>
				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0"><?= $ci->lang('laboratory') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('patient name') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('teeth') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('tooth type') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('delivery date') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('delivery time') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('pay amount') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('desc') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($labs as $lab) : ?>
							<tr id="<?= $lab['id'] ?>">
								<td scope="row"><?= $i ?></td>
								<td><?= $lab['lab_name'] ?></td>
								<td><?= $ci->mylibrary->get_patient_name($lab['name'], $lab['lname'], $lab['serial_id'], $lab['gender']) ?></td>
								<?php
								$teeths = explode(',', $lab['teeth']);
								$teethName = '';
								if (count($teeths) != 0) {

									foreach ($teeths as $tooth) {
										$info = $ci->tooth_by_id($tooth);
										if (is_array($info) && count($info) != 0) {

											$teethName .= $info['name'];
											$teethName .= $info['location'];
										}
										$teethName .= ',';
									}
								} else {
									$teethName = ',';
								}
								?>
								<td><?= substr($teethName, 0, -1) ?></td>
								<?php
								$types = explode(',', $lab['type']);
								$typesName = '';
								foreach ($types as $type) {
									$typesName .= $ci->lang($type);
									$typesName .= ',';
								}
								?>
								<td><?= substr($typesName, 0, -1) ?></td>
								<td><?= $lab['give_date'] ?></td>
								<td><?= $ci->mylibrary->from24to12($lab['hour']) ?></td>
								<td><?= $lab['dr'] ?></td>
								<td><?= $lab['remarks'] ?></td>
								<td>
									<div class="g-2">
										<?php if ($ci->auth->has_permission('Read Patient Profile')): ?>
											<a href="<?= base_url('admin/single_patient/') . $lab['patient_id'] ?>"
											   class="btn btn-icon btn-outline-info rounded-pill btn-wave waves-effect waves-light">
											<span
												class="fa fa-user-circle-o"></span>
											</a>
										<?php endif; ?>
										<?php if ($lab['init_date'] != ''): ?>
											<?php if ($lab['first_try_status'] == 'p'): ?>
												<a href="javascript:firstTry('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-check-circle"></span></a>
											<?php else: ?>
												<a href="javascript:showTry('<?= $lab['id'] ?>', 'first')"
												   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-eye"></span></a>
											<?php endif; ?>

											<?php if ($lab['second_try_status'] == 'p'): ?>
												<a href="javascript:secondTry('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-check-circle"></span></a>
											<?php else: ?>
												<a href="javascript:showTry('<?= $lab['id'] ?>', 'second')"
												   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-eye"></span></a>
											<?php endif; ?>


											<?php if ($lab['status'] == 'p'): ?>
												<a href="javascript:finish('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-check-circle"></span></a>
											<?php else: ?>
												<a href="javascript:showfinish('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-eye"></span></a>
											<?php endif; ?>

											<?php if ($lab['install_time'] == ''): ?>
												<a href="javascript:install('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light  <?= ($lab['status'] == 'p') ? 'locked' : '' ?>"><span
														class="fa fa-tooth"></span></a>
											<?php else: ?>
												<a href="javascript:showinstall('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
														class="fa fa-eye"></span></a>
											<?php endif; ?>

											<?php if ($lab['status'] != 'm'): ?>
												<a href="javascript:payLab('<?= $lab['id'] ?>')"
												   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light <?= ($lab['status'] != 'a' || is_null($lab['install_time'])) ? 'locked' : '' ?>"><span
														class="fa fa-money"></span></a>
											<?php endif; ?>

										<?php else: ?>
											<a href="javascript:init_lab('<?= $lab['id'] ?>')"
											   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
													class="fa fa-check-circle"></span></a>
										<?php endif; ?>
										<a href="javascript:print_lab('<?= $lab['id'] ?>')"
										   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
												class="fa-solid fa-print fs-14"></span></a>
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


<!-- Modal edit -->
<div class="modal fade effect-scale" id="editModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('edit account') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="update">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('name') ?> <span
										class="text-red">*</span></label>
								<input type="text" name="name" id="name" class="form-control"
									   placeholder="<?= $ci->lang('name') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('lname') ?></label>
								<input type="hidden" name="slug" id="slug">
								<input type="text" name="lname" id="lname" class="form-control"
									   placeholder="<?= $ci->lang('lname') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('phone') ?></label>
								<input type="number" name="phone" id="phone" class="form-control"
									   placeholder="<?= $ci->lang('phone') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('account type') ?> <span
										class="text-red">*</span></label>
								<select name="type" id="type" class="form-control form-select">
									<option label="<?= $ci->lang('select') ?>"></option>
									<?php foreach ($ci->mylibrary->list_account_type() as $key => $value) : ?>
										<option value="<?= $key ?>"><?= $ci->lang($value) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-primary"
						onclick="xhrUpdate('update', '<?= base_url() ?>admin/update_account')"><?= $ci->lang('update') ?>
					<i class="fa fa-edit"></i></button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->

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
	function print_lab(labId) {
		window.open(`<?= base_url() ?>admin/print_lab/${labId}`, '_blank');
	}
</script>

<script>
	function firstTry(id) {
		$('#type_try').val('first');
		$('#hiddenId').val(id);
		$("#triesModal").modal('toggle');
	}

	function secondTry(id) {
		$('#type_try').val('second');
		$('#hiddenId').val(id);
		$("#triesModal").modal('toggle');
	}

	function finish(id) {
		$.ajax({
			url: "<?= base_url('admin/finish_lab') ?>",
			type: 'POST',
			data: {
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					listLabs();
					toastr["success"](result['alert']['text'], result['alert']['title'])
				} else {
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

	function showTry(id, type) {
		$.ajax({
			url: "<?= base_url('admin/show_try') ?>",
			type: 'POST',
			data: {
				type: type,
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#details_lab').show();
					$('#details_lab').val(result['content']['message']);
					$('#datetime_lab').val(result['content']['datetime']);
					$("#showtriesModal").modal('toggle');
				} else {
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

	function showfinish(id) {
		$.ajax({
			url: "<?= base_url('admin/show_try') ?>",
			type: 'POST',
			data: {
				type: 'finish',
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#details_lab').hide();
					$('#datetime_lab').val(result['content']['datetime']);
					$("#showtriesModal").modal('toggle');
				} else {
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}


	function showinstall(id) {
		$.ajax({
			url: "<?= base_url('admin/show_try') ?>",
			type: 'POST',
			data: {
				type: 'install',
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#details_lab').hide();
					$('#datetime_lab').val(result['content']['datetime']);
					$("#showtriesModal").modal('toggle');
				} else {
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}


	function install(id) {
		$.ajax({
			url: "<?= base_url('admin/install_lab') ?>",
			type: 'POST',
			data: {
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					listLabs();
					toastr["success"](result['alert']['text'], result['alert']['title'])
				} else {
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

	function init_lab(id) {
		$('#init_lab_id').val(id);
		$("#init_lab").modal('toggle');
	}

</script>


<?php $ci = get_instance(); ?>

<!-- Modal Call -->
<div class="modal fade effect-scale" id="triesModal" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<!-- change language -->
					<?= $ci->lang('desc') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tryForm">
					<div class="row">

						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('desc') ?></label>
								<textarea type="text" name="remarks" id="callDetails1" class="form-control" rows="4"
										  placeholder="<?= $ci->lang('desc') ?>"></textarea>
							</div>
						</div>
						<input id="type_try" type="hidden" name="type" class="form-control">
						<input id="hiddenId" type="hidden" name="slug" class="form-control">

					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('tryForm', '<?= base_url() ?>admin/tryLab', 'labsTable', 'triesModal', listLabs)">
					<?= $ci->lang('Submit') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Call End -->

<!-- Modal Show -->
<div class="modal fade effect-scale" id="showtriesModal" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<!-- change language -->
					<?= $ci->lang('desc') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="">
					<div class="row">

						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('date') ?></label>
								<input type="text" id="datetime_lab" class="form-control" disabled>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('desc') ?></label>
								<textarea type="text" name="remarks" id="details_lab" class="form-control" rows="4"
										  placeholder="<?= $ci->lang('desc') ?>"></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Show End -->

<!-- Modal Show -->
<div class="modal fade effect-scale" id="init_lab" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<!-- change language -->
					<?= $ci->lang('desc') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="init_lab_form">
					<div class="row">

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('date') ?></label>
								<input type="hidden" name="slug" id="init_lab_id">
								<input data-jdp type="text" id="deliveryDate_edit" name="give_date"
									   class="form-control">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('hour') ?></label>
								<input type="time" name="hour" list="from_times" class="form-control">
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('desc') ?></label>
								<textarea type="text" name="remarks" id="details_lab" class="form-control" rows="4"
										  placeholder="<?= $ci->lang('desc') ?>"></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>

				<button class="btn btn-warning"
						onclick="submitWithoutDatatable('init_lab_form', '<?= base_url() ?>admin/init_lab', 'labsTable', 'init_lab', print_lab ,'print'); listLabs();">
					ذخیره و چاپ <i class="fa fa-print"></i>
				</button>

				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Show End -->


<script>
	//function tableFilter(selectId) {
	//	var selectIdValue = $(selectId).val();
	//	$.ajax({
	//		url: "<?php //= base_url('admin/list_patient_json') ?>//",
	//		type: 'POST',
	//		data: {
	//			status: selectIdValue,
	//		},
	//		success: function (response) {
	//			let result = JSON.parse(response);
	//			var table = $('#file-datatable').DataTable();
	//			table.rows().remove();
	//			result.content.map((item) => {
	//				let changeBtnsStatus = ``;
	//
	//				if (selectIdValue == 'p') {
	//					changeBtnsStatus +=
	//						`<a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/accept_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-check-circle"></span></a>
	//          <a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/block_patient')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-minus-circle"></span></a>`;
	//				} else if (selectIdValue == 'a') {
	//					changeBtnsStatus +=
	//						`<a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/pending_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-clock"></span></a>
	//          <a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/block_patient')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-minus-circle"></span></a>`;
	//				} else {
	//					changeBtnsStatus +=
	//						`<a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/pending_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-clock-o fs-14"></span></a>
	//        <a href="javascript:accept_via_alert('${item.id}', '<?php //= base_url() ?>//admin/accept_patient')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-check-circle"></span></a>`;
	//
	//				}
	//				let buttons = '';
	//				if (selectIdValue != 't') {
	//					buttons = ` <div class="g-2">
	//            <a href="<?php //= base_url("admin/single_patient/") ?>//${item.id}" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?php //= $ci->lang('edit') ?>//"><span class="fa fa-user-circle-o fs-14"></span></a>
	//            <a href="javascript:print_patient('${item.id}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?php //= $ci->lang('print') ?>//"><span class="fa fa-print fs-14"></span></a>
	//            ${changeBtnsStatus}
	//            <a href="javascript:delete_via_alert('${item.id}', '<?php //= base_url() ?>//admin/delete_patient')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?php //= $ci->lang('delete') ?>//"><span class="fa fa-trash"></span></a>
	//          </div>`;
	//				} else {
	//					buttons = ` <div class="g-2">
	//                  <a href="javascript:temp_to_permenant('${item.id}', '<?php //= base_url() ?>//admin/temp_to_permenant', 'tempTable')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-check fs-14"></span></a>
	//                </div>`;
	//				}
	//
	//				let row = table.row.add([
	//					item.number,
	//					item.serial_id,
	//					item.fullname,
	//					item.phone,
	//					item.doctor_name,
	//					item.history,
	//					item.other_pains,
	//					item.remarks,
	//					buttons
	//				]).node();
	//				row.id = item.id;
	//			});
	//			table.draw(false);
	//		}
	//	});
	//}
</script>


<script>
	function listLabs() {
		let filters = {
			from_date: $('#from_date').val() || '',
			to_date: $('#to_date').val() || '',
			lab_name: $('#lab_name').val() || '',
			payment_status: $('#payment_status').val() || '',
			case_status: $('#case_status').val() ? $('#case_status').val().join(',') : ''
		};

		$.ajax({
				url: "<?= base_url('admin/list_labs') ?>",
			type: 'POST',
			data: filters,
			success: function (response) {
				let result = JSON.parse(response);
				let labs = result.content.labs;
				let table = $('#file-datatable').DataTable();

				table.clear();

				if (result.type === 'success' && labs.length) {
					labs.forEach(lab => {
						let rowData = [
							lab.number || '',
							lab.lab_name || '',
							lab.patient_name || '',
							lab.teeth || '',
							lab.type || '',
							lab.delivery_date || '',
							lab.delivery_time || '',
							lab.pay_amount || '',
							lab.remarks || '',
							generateLabButtons(lab)
						];

						let row = table.row.add(rowData).node();
						row.id = lab.id;
					});

					table.draw(false);
				} else {
					table.clear().draw();
				}
			}
		});
	}

	function generateLabButtons(lab) {
		let buttons = '';

		if (lab.init_date) {
			buttons += createStatusButton('firstTry', lab, 'first_try_status', 'fa-check-circle', 'fa-eye');
			buttons += createStatusButton('secondTry', lab, 'second_try_status', 'fa-check-circle', 'fa-eye');
			buttons += createStatusButton('finish', lab, 'status', 'fa-check-circle', 'fa-eye');

			if (lab.install_time) {
				buttons += createButton('showTry', lab.id, 'fa-eye', 'primary', '', `'install'`);
			} else {
				let locked = !lab.receive_datetime ? 'locked' : '';
				buttons += createButton('install', lab.id, 'fa-tooth', 'success', locked);
			}

			if (lab.status !== 'm') {
				let locked = (lab.status !== 'a' || !lab.install_time || !lab.receive_datetime) ? 'locked' : '';
				buttons += createButton('payLab', lab.id, 'fa-money', 'success', locked);
			}
		} else {
			buttons += createButton('init_lab', lab.id, 'fa-check-circle', 'success');
		}

		let profile = '';

		if (lab.profile_access){
			profile = `<a href="<?= base_url('admin/single_patient/') ?>${lab.patient_id}" class="btn btn-icon btn-outline-info rounded-pill btn-wave waves-effect waves-light">
				<span class="fa fa-user-circle-o"></span>
			</a> `;
		}

		return `
		<div class="g-2">
			${profile}
			${buttons}
			${createButton('print_lab', lab.id, 'fa-print', 'warning')}
		</div>`;
	}


	function createStatusButton(action, lab, key, successIcon, defaultIcon) {
		return lab[key] === 'p'
			? createButton(action, lab.id, successIcon, 'success')
			: createButton('showTry', lab.id, defaultIcon, 'primary', '', `'${action}'`);
	}

	function createButton(action, id, icon, type, extraClass = '', extraParams = '') {
		return `<a href="javascript:${action}('${id}'${extraParams ? ', ' + extraParams : ''})"
                class="btn btn-icon btn-outline-${type} rounded-pill btn-wave waves-effect waves-light ${extraClass}">
                <span class="fa ${icon}"></span>
            </a> `;
	}


	function payLab(id) {
		swal({
			title: '<?= $ci->lang('pay lab alert title') ?>',
			text: "<?= $ci->lang('pay lab alert text') ?>",
			icon: "info",
			buttons: ['<?= $ci->lang('cancel') ?>', '<?= $ci->lang('yes') ?>'],
			dangerMode: true,
		}).then((accepts) => {
			if (accepts) {
				$.ajax({
					url: "<?= base_url('admin/pay_lab') ?>",
					type: 'POST',
					data: {record: id},
					success: function (response) {
						let result = JSON.parse(response);

						if (result.type === 'success') {
							listLabs(); // refresh table
							toastr["success"](result.alert.text, result.alert.title);

							// Open print page in new tab
							if (result.balance_id) {
								window.open('<?= base_url() ?>admin/print_expense/' + result.balance_id, '_blank');
							}
						} else {
							toastr["error"](
								result.messages?.[0] || result.alert?.text || '<?= $ci->lang('problem') ?>',
								result.alert?.title || '<?= $ci->lang('error') ?>'
							);
						}
					}
				});
			}
		});
	}


</script>
