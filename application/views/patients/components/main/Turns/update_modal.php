<?php $ci = get_instance(); ?>

<div class="modal fade effect-scale" id="update_turn" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('update turn') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form id="updateTurn">
					<input type="hidden" name="patient_id" id="patient_id_turn" value="<?= $profile['id'] ?>">
					<input type="hidden" name="slug" id="slug_turn">
					<input type="hidden" name="dateOld" id="dateTurnOld">
					<input type="hidden" name="doctorOld" id="doctorTurnOld">
					<input type="hidden" name="fromTimeOld" id="fromTimeTurnOld">
					<input type="hidden" name="toTimeOld" id="toTimeTurnOld">


					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group jdp" id="main-div">
								<label class="form-label"><?= $ci->lang('date') ?> <span class="text-red">*</span></label>
								<input data-jdp type="text" id="date_turn" name="date" class="form-control"
									   placeholder="<?= $ci->lang('date') ?>"
									   onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
									   autocomplete="off">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('reference doctor') ?> <span class="text-red">*</span></label>
								<select name="doctor_id" class="form-control select2-show-search form-select"
										onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
										id="doctor_id_turn" data-placeholder="<?= $ci->lang('select') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
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
								<label class="form-label"><?= $ci->lang('hour') ?> <span class="text-red">*</span></label>
								<input type="time" name="from_time" class="form-control" id="from_time_turn" list="from_times">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('hour') ?> <span class="text-red">*</span></label>
								<input type="time" name="to_time" class="form-control" id="to_time_turn" list="from_times">
							</div>
						</div>

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
				</form>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="turnsTableModalupdate"></div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?></button>
				<button class="btn btn-primary"
						onclick="updateWithoutDatatable('updateTurn', '<?= base_url() ?>admin/update_turn', 'turnsTable', 'update_turn'); reloadTurnsTable();">
					<?= $ci->lang('save') ?>
				</button>
			</div>
		</div>
	</div>
</div>







<div class="modal fade effect-scale" id="finishTurn" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('update turn') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="updateTurn">
						<div class="row">

							<input type="hidden" name="patient_id" id="patient_id_turn" value="<?= $profile['id'] ?>">
							<input type="hidden" name="slug" id="slug_turn">

							<div class="col-sm-12 col-md-4">
								<div class="form-group jdp" id="main-div">
									<input type="hidden" name="dateOld" id="dateTurnOld">
									<label class="form-label"><?= $ci->lang('date') ?> <span
											class="text-red">*</span></label>
									<input data-jdp="date0" type="text" id="date_turn" name="date" class="form-control"
										   placeholder="<?= $ci->lang('date') ?>"
										   onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), doctor = $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
										   autocomplete="off">
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span
											class="text-red">*</span></label>
									<input type="hidden" name="doctorOld" id="doctorTurnOld">
									<select name="doctor_id" class="form-control select2-show-search form-select"
											onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), doctor = $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
											id="doctor_id_turn" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option
												value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('hour') ?> <span
											class="text-red">*</span></label>
									<input type="hidden" name="hourOld" id="hourTurnOld">
									<select name="hour" class="form-control select2-show-search form-select"
											id="hour_turn" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($ci->dentist->hours() as $hour) :
											?>
											<option value="<?= $hour['key'] ?>"><?= $hour['value'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="turnsTableModalupdate">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?></button>
				<button class="btn btn-primary"
						onclick="updateWithoutDatatable('updateTurn', '<?= base_url() ?>admin/update_turn', 'turnsTable', 'update_turn')"><?= $ci->lang('save') ?></button>
			</div>
		</div>
	</div>
</div>
