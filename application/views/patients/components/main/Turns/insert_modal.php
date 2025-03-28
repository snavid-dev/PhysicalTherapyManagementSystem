<?php $ci = get_instance(); ?>
<!-- turns Modal -->
<?php if ($ci->auth->has_permission('Create Turn')): ?>
	<div class="modal fade effect-scale" id="extralargemodal" role="dialog">
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

							<div class="col-sm-12 col-md-6">
								<div class="form-group jdp" id="main-div">
									<label class="form-label">
										<?= $ci->lang('date') ?> <span class="text-red">*</span>
									</label>
									<input data-jdp type="text" id="test-date-id-date" name="date"
										   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
										   class="form-control" placeholder="<?= $ci->lang('date') ?>"
										   onchange="check_turns()">
									<div></div>
									<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('reference doctor') ?> <span class="text-red">*</span>
									</label>
									<select name="doctor_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>" id="doctorName"
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
									<input type="time" name="from_time" list="from_times" class="form-control"
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
									<input name="to_time" class="form-control" type="time" list="from_times"
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
					<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
							class="fa fa-close"></i></button>
					<button class="btn btn-warning"
							onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable','extralargemodal', print_turn, 'print'); setTimeout(reloadTurnsTable(), 500)"><?= $ci->lang('save and print') ?>
						<i class="fa fa-print"></i></button>
					<button class="btn btn-primary"
							onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable', 'extralargemodal', false); setTimeout(reloadTurnsTable(), 500)"><?= $ci->lang('save') ?>
						<i class="fa fa-plus"></i></button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- turns Modal End -->
