<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0">
					<?= $ci->lang('doctors leave requests') ?>
				</h3>
			</div>
			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">
							<li>
								<?php if ($ci->auth->has_permission('Create Expenses')): ?>
									<button class="btn btn-primary" data-bs-toggle="modal"
											data-bs-target="#extralargemodal">
										<?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
									</button>
								<?php endif; ?>
							</li>

						</ul>
						<!-- Modal Button -->
						<!-- ReceiptsModal -->
						<?php if ($ci->auth->has_permission('Create Expenses')): ?>
							<div class="modal fade effect-scale" id="extralargemodal" role="dialog">
								<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">
												<?= $ci->lang('insert receipt') ?>
											</h5>
											<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">
											<form id="insert_leave_request">
												<div class="row">


													<div class="col-sm-12 col-md-4">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('reference doctor') ?> <span
																	class="text-red">*</span>
															</label>
															<select name="doctor_id"
																	class="form-control select2-show-search form-select"
																	data-placeholder="<?= $ci->lang('select') ?>"
																	id="doctorName">
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


													<div class="col-sm-12 col-md-4">
														<div class="form-group jdp" id="main-div">
															<label class="form-label">
																<?= $ci->lang('start date') ?> <span
																	class="text-red">*</span>
															</label>
															<input data-jdp type="text" id="test-date-id-date"
																   name="leave_start_date"
																   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
																   class="form-control"
																   placeholder="<?= $ci->lang('date') ?>">
															<div></div>
														</div>
													</div>
													<div class="col-sm-12 col-md-4">
														<div class="form-group jdp" id="main-div">
															<label class="form-label">
																<?= $ci->lang('end date') ?> <span
																	class="text-red">*</span>
															</label>
															<input data-jdp type="text" id="test-date-id-date"
																   name="leave_end_date"
																   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
																   class="form-control"
																   placeholder="<?= $ci->lang('date') ?>">
															<div></div>
														</div>
													</div>


													<div class="col-sm-12 col-md-12 ">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('reason') ?> <span
																	class="text-red"></span>
															</label>
															<textarea class="form-control" name="reason"
																	  placeholder="<?= $ci->lang('description') ?>"></textarea>
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
													onclick="xhrSubmit('insert_leave_request', '<?= base_url() ?>admin/insert_leave')">
												<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<!-- ReceiptsModal End -->
					</div>
				</div>


				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0">
								<?= $ci->lang('doctor') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('start date') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('end date') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('reason') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('status') ?>
							</th>
							<th class="border-bottom-0">
								<?= $ci->lang('actions') ?>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($leaves as $leave) : ?>
							<tr id="<?= $leave['id'] ?>">
								<td>
									<?= $i ?>
								</td>
								<td>
									<?= $ci->mylibrary->user_name($leave['doctor_fname'], $leave['doctor_lname']) ?>
								</td>
								<td>
									<bdo dir="ltr"><?= $leave['leave_start_date'] ?></bdo>
								</td>
								<td class="english">
									<bdo dir="ltr"><?= $leave['leave_end_date'] ?></bdo>
								</td>
								<td>
									<?= $leave['reason'] ?>
								</td>
								<td>
									<?= $ci->mylibrary->check_status($leave['status']) ?>
								</td>
								<td>
									<div class="g-2">
										<a href="javascript:edit_leave('<?= $leave['id'] ?>')"
										   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-edit"></span></a>
										<a href="javascript:delete_via_alert('<?= $leave['id'] ?>', '<?= base_url() ?>admin/delete_leave')"
										   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-trash"></span></a>
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


<!--edit modal-->
<div class="modal fade effect-scale" id="editLeaveModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('doctors leave requests') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="update_leave_request">
					<input type="hidden" name="leave_id" id="leave_id">
					<div class="row">

						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('reference doctor') ?> <span class="text-red">*</span>
								</label>
								<select name="doctor_id" class="form-control select2-show-search form-select"
										id="editDoctorName">
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
							<div class="form-group jdp">
								<label class="form-label">
									<?= $ci->lang('start date') ?> <span class="text-red">*</span>
								</label>
								<input data-jdp type="text" name="leave_start_date" id="edit_leave_start_date"
									   class="form-control">
							</div>
						</div>

						<div class="col-sm-12 col-md-4">
							<div class="form-group jdp">
								<label class="form-label">
									<?= $ci->lang('end date') ?> <span class="text-red">*</span>
								</label>
								<input data-jdp type="text" name="leave_end_date" id="edit_leave_end_date"
									   class="form-control">
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('reason') ?>
								</label>
								<textarea class="form-control" name="reason" id="edit_reason"
										  placeholder="<?= $ci->lang('description') ?>"></textarea>
							</div>
						</div>

					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary" onclick="xhrUpdate('update_leave_request', '<?= base_url() ?>admin/update_leave', 'editLeaveModal')">
					<?= $ci->lang('update') ?>
				</button>
			</div>
		</div>
	</div>
</div>


<script>
	document.addEventListener("DOMContentLoaded", function () {
		// jalaliDatepicker.startWatch();
		jalaliDatepicker.startWatch();
	});
</script>

<script>
	function edit_leave(leave_id) {
		$.ajax({
			url: "<?= base_url('admin/get_leave') ?>",
			type: 'POST',
			data: {leave_id: leave_id},
			success: function (response) {
				var result = JSON.parse(response);
				if (result.type === 'success') {
					var leave = result.content;

					$('#leave_id').val(leave.id);
					$('#editDoctorName').val(leave.doctor_id).trigger('change');
					$('#edit_leave_start_date').val(leave.leave_start_date);
					$('#edit_leave_end_date').val(leave.leave_end_date);
					$('#edit_reason').val(leave.reason);

					$('#editLeaveModal').modal('show');
				} else {
					alert(result.alert.text);
				}
			}
		});
	}

</script>


