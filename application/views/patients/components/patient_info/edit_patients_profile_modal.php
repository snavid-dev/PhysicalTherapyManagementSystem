<?php
$ci = get_instance();
?>
<div class="modal fade effect-scale" id="edit_patient" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('Edit Profile') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="insertAccount">
					<div class="row">
						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<input type="hidden" name="slug" id="slug">
								<label class="form-label"><?= $ci->lang('name') ?> <span
										class="text-red">*</span></label>
								<input type="text" name="name" id="name" class="form-control"
									   placeholder="<?= $ci->lang('name') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('lname') ?> <span
										class="text-red">*</span></label>
								<input type="text" name="lname" id="lname" class="form-control"
									   placeholder="<?= $ci->lang('lname') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('age') ?> <span
										class="text-red">*</span></label>
								<input type="number" name="age" id="age" class="form-control"
									   placeholder="<?= $ci->lang('age') ?>">
							</div>
						</div>
						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('gender') ?> <span
										class="text-red">*</span></label>
								<select name="gender" id="gender" class="form-control form-select">
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="m"><?= $ci->lang('male') ?></option>
									<option value="f"><?= $ci->lang('female') ?></option>
								</select>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('phone') ?> <span
										class="text-red">*</span></label>
								<input type="text" name="phone1" id="phone1" class="form-control"
									   placeholder="<?= $ci->lang('phone') ?>">
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('phone2') ?></label>
								<input type="text" name="phone2" id="phone2" class="form-control"
									   placeholder="<?= $ci->lang('phone2') ?>">
							</div>
						</div>


						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('medical history') ?></label>
								<select name="" class="form-control select2-show-search form-select"
										onchange="multiple_value()" id="pains"
										data-placeholder="<?= $ci->lang('select') ?>" multiple>
									<option label="<?= $ci->lang('select') ?>"></option>
									<?php foreach ($ci->dentist->diseases() as $pain) : ?>
										<option value="<?= $pain ?>"><?= $pain ?></option>
									<?php endforeach; ?>
								</select>
								<input type="hidden" name="pains" id="model_value">
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('reference doctor') ?></label>
								<select name="doctor_id"
										class="form-control select2-show-search form-select"
										id="doctor_id" data-placeholder="<?= $ci->lang('select') ?>">
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
								<label class="form-label"><?= $ci->lang('other diseases') ?></label>
								<textarea type="text" name="other_pains" id="other_pains"
										  class="form-control" rows="4"
										  placeholder="<?= $ci->lang('other diseases') ?>"></textarea>
							</div>
						</div>

						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('address') ?></label>
								<textarea type="text" name="address" id="address" class="form-control"
										  rows="4" placeholder="<?= $ci->lang('address') ?>"></textarea>
							</div>
						</div>


						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('desc') ?></label>
								<textarea type="text" name="remarks" id="remarks" class="form-control"
										  rows="4" placeholder="<?= $ci->lang('desc') ?>"></textarea>
							</div>
						</div>


					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-primary"
						onclick="Patient_profile_update('insertAccount', '<?= base_url() ?>admin/update_patient', 'edit_patient')"><?= $ci->lang('save') ?>
					<i class="fa fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>


<?php
$ci->render('patients/components/patient_info/edit_patients_profile_modal_js.php');
?>
