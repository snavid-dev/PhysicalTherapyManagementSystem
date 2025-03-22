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
						onclick="submitWithoutDatatable('tryForm', '<?= base_url() ?>admin/tryLab', 'labsTable', 'triesModal', list_labs)">
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
						onclick="submitWithoutDatatable('init_lab_form', '<?= base_url() ?>admin/init_lab', 'labsTable', 'init_lab', print_lab ,'print'); setTimeout(() => {
							list_labs();
							}, 2000);">
					ذخیره و چاپ <i class="fa fa-print"></i>
				</button>

				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Show End -->
