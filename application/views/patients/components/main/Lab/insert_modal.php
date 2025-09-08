<?php
$ci = get_instance();
?>
<div class="modal fade effect-scale" tabindex="-1" id="laboratoryInsertModal" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					<?= $ci->lang('insert laboratory expenses') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">

					<div class="col-md-12">

						<form id="tableInsert">
							<div class="row">

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('laboratory') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="selectLab" name="customers_id"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($lab_accounts as $lab_account) : ?>
												<option
													value="<?= $lab_account['id'] ?>"><?= $ci->mylibrary->account_name($lab_account['name'], $lab_account['lname']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('teeth') ?> <span class="text-red">*</span>
										</label>

										<select id="selectTeeth" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectTeeth', '#selectTeethHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>
										</select>
										<input type="hidden" id="selectTeethHiddenInput" name="teeth">
										<input type="hidden" id="" name="patient_id" value="<?= $profile['id'] ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group" id="main-divs">
										<label class="form-label">
											<?= $ci->lang('tooth type') ?>
										</label>

										<select id="selectToothType"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectToothType', '#selectToothTypeHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->teeth_type() as $key => $value) : ?>
												<option value="<?= $key ?>"><?= $value ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="selectToothTypeHiddenInput" name="type">

									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('tooth color') ?><span class="text-red">*</span>
										</label>

										<select id="ToothColor" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#ToothColor', '#selectToothColorTypeHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->teeth_color() as $color) : ?>

												<option value="<?= $color ?>"><?= $color ?></option>

											<?php endforeach; ?>

										</select>

										<input type="hidden" name="color" id="selectToothColorTypeHiddenInput">

									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="dr" id="payment" class="form-control"
											   placeholder="<?= $ci->lang('pay amount') ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Number of units') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="numberofUnits" id="numberofunite"
											   class="form-control" placeholder="<?= $ci->lang('Number of units') ?>">
									</div>
								</div>


							</div>






						</form>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('tableInsert', '<?= base_url() ?>admin/insert_lab', 'labsTable', 'laboratoryInsertModal', list_labs)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
