<?php $ci = get_instance(); ?>

<div class="modal fade effect-scale"
	 tabindex="-1"
	 id="recommended_processes"
	 role="dialog"
	 data-patient-id="<?= $profile['id'] ?>"
	 data-list-teeth-url="<?= base_url('admin/list_process_teeth') ?>"
	 data-get-processes-url="<?= base_url('admin/get_tooth_processes_by_teeth') ?>"
	 data-list-plan-url="<?= base_url('admin/list_treatment_plan') ?>"
	 data-get-plan-url="<?= base_url('admin/get_treatment_plan_for_edit') ?>"
	 data-insert-url="<?= base_url('admin/insert_recommended_processes') ?>"
	 data-update-url="<?= base_url('admin/update_recommended_process') ?>"
	 data-title-insert="<?= $ci->lang('recommended processes') ?>"
	 data-title-update="<?= $ci->lang('update treatment plan') ?>"
	 data-save-label="<?= $ci->lang('save') ?>"
	 data-update-label="<?= $ci->lang('update') ?>"
	 data-other-label="<?= $ci->lang('other') ?>"
	 data-other-process-label="<?= $ci->lang('other process') ?>">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="recommended_process_modal_title"><?= $ci->lang('recommended processes') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-md-3">
						<label><?= $ci->lang('doctor') ?></label>
						<select name="doctor_id" class="form-control select2-show-search form-select"
								data-placeholder="<?= $ci->lang('select') ?>" id="doctor_id" form="processForm">
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
					<div class="col-md-3">
						<label><?= $ci->lang('name') ?></label>
						<input type="text" class="form-control" name="name" id="treatment_plan_name" form="processForm">
					</div>
					<div class="col-md-6">
						<label><?= $ci->lang('teeth') ?></label>
						<select class="form-control select2-show-search form-select" name="teeth[]" id="process_teeth"
								onchange="get_teeth_process()" data-placeholder="<?= $ci->lang('select') ?>" multiple>
							<!-- Options added dynamically -->
						</select>
					</div>
				</div>

				<hr class="my-3">

				<form id="processForm">
					<div id="teeth_processes_container"></div>
					<input type="hidden" name="patient_id" id="patient_id" value="<?= $profile['id'] ?>">
					<input type="hidden" name="old_plan_name" id="old_plan_name" value="">
					<input type="hidden" name="mode" id="processFormMode" value="insert">
					<input type="hidden" name="treatment_id" id="treatment_id" value="">
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>

				<button class="btn btn-primary" id="treatment_plan_submit" onclick="submit_treatment_plan_form()">
					<span id="treatment_plan_submit_text"><?= $ci->lang('save') ?></span> <i class="fa fa-save"></i>
				</button>
			</div>

		</div>
	</div>
</div>

