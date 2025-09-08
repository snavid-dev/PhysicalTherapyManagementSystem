<?php $ci = get_instance() ?>
<div class="modal fade effect-scale" id="filesModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert files') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertFile">
						<div class="row">

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('select file') ?> <span
											class="text-red">*</span></label>
									<input type="file" name="to_upload" id="" class="form-control">
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('title') ?> <span class="text-red">*</span></label>
									<input type="text" name="title" class="form-control"
										   placeholder="<?= $ci->lang('title') ?>" id="" autocomplete="off">
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('category') ?> <span
											class="text-red">*</span></label>
									<select class="form-control select2-show-search form-select" name="categories_id"
											style="width: 300px !important;"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>

										<?php foreach ($categories_files as $categories_file) : ?>

											<option value="<?= $categories_file['id'] ?>">
												<?= $categories_file['name'] ?>
											</option>

										<?php endforeach; ?>

									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="form-group">
									<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
									<label class="form-label"><?= $ci->lang('desc') ?></label>
									<textarea name="desc" id="" rows="4" class="form-control"
											  placeholder="<?= $ci->lang('desc') ?>"></textarea>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('insertFile', '<?= base_url() ?>admin/insert_files', 'filesTable', 'filesModal')"><?= $ci->lang('save') ?>
					<i class="fa fa-upload"></i></button>
			</div>
		</div>
	</div>
</div>
