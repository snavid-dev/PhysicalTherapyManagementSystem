<?php $ci = get_instance(); ?>
<div class="modal fade effect-scale" id="extralargemodal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert turn') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<form id="insertTurn">
						<div class="row">

							<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">

							<div class="col-sm-12 col-md-4">
								<div class="form-group jdp" id="main-div">
									<label class="form-label"><?= $ci->lang('date') ?> <span
											class="text-red">*</span></label>
									<input data-jdp="date0" type="text" id="test-date-id-date" name="date"
										   class="form-control" onchange="check_turns()"
										   placeholder="<?= $ci->lang('date') ?>" autocomplete="off">
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span
											class="text-red">*</span></label>
									<select name="doctor_id" class="form-control select2-show-search form-select"
											onchange="check_turns()" id="reference_doctor"
											data-placeholder="<?= $ci->lang('select') ?>">
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
									<select name="hour" class="form-control select2-show-search form-select"
											id="hour_insert" onchange="check_turns()"
											data-placeholder="<?= $ci->lang('select') ?>">
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
						<div class="table-responsive" id="turnsTableModal">
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-warning"
						onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable','extralargemodal', print_turn, 'print')"><?= $ci->lang('save and print') ?>
					<i class="fa fa-print"></i></button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable')"><?= $ci->lang('save') ?>
					<i class="fa fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>
