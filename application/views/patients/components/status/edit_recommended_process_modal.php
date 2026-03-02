<?php $ci = get_instance(); ?>

<div class="modal fade effect-scale" tabindex="-1" id="recommended_processes" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('recommended processes') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<!-- Patient ID -->

				<!-- Teeth Selection -->
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
						<input type="text" class="form-control" name="name" form="processForm">
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

				<!-- Dynamic processes container -->
				<form id="processForm">
					<div id="teeth_processes_container"></div>
					<input type="hidden" name="patient_id" id="patient_id" value="">

				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>

				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('processForm', '<?= base_url() ?>admin/insert_recommended_processes', 'labsTable', 'recommended_processes', list_treatment_plan)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>

		</div>
	</div>
</div>
