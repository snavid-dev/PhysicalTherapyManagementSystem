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
								<input data-jdp type="text" class="form-control" id="datePicker"
									   onchange="tableFilter()" placeholder="<?= $ci->lang('date') ?>"
									   autocomplete="off">
							</li>

							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('To Date') ?>:
								</h6>
							</li>

							<li style="width: 150px;">
								<input data-jdp type="text" class="form-control" id="datePicker"
									   onchange="tableFilter()" placeholder="<?= $ci->lang('date') ?>"
									   autocomplete="off">
							</li>

							<li style="position: relative; top:9px">
								<h6>
									<?= $ci->lang('payment status') ?>:
								</h6>
							</li>

							<li style="width: 180px;">
								<select
									class="form-control form-select"
									data-placeholder="<?= $ci->lang('select') ?>"
									onchange="tableFilter()">
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

							<li style="width: 180px;">
								<select name="doctor_id"
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>"
										id="doctor"
										onchange="tableFilter()" multiple>
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="0" selected>
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
								foreach ($teeths as $tooth) {
									$info = $ci->tooth_by_id($tooth);
									$teethName .= $info['name'];
									$teethName .= $info['location'];
									$teethName .= ',';
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
								<td><?= $ci->dentist->find_time($lab['hour']) ?></td>
								<td><?= $lab['dr'] ?></td>
								<td><?= $lab['remarks'] ?></td>
								<td>
									<div class="g-2">
										<a href="<?= base_url('admin/single_patient/') . $lab['patient_id'] ?>" class="btn btn-icon btn-outline-info rounded-pill btn-wave waves-effect waves-light">
											<span
												class="fa fa-user-circle-o"></span>
										</a>
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
										<?php if ($lab['status'] != 'm'): ?>
											<a href="javascript:payLab('<?= $lab['id'] ?>')"
											   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light <?= ($lab['status'] != 'a') ? 'locked' : '' ?>"><span
													class="fa fa-money"></span></a>
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
