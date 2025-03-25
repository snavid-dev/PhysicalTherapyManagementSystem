<?php $ci = get_instance(); ?>

<div class="modal fade effect-scale" tabindex="-1" id="recommended_processes" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('recommended processes') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<!--select section-->
					<div class="col-sm-12 col-md-4">
						<select class="form-control select2-show-search form-select" name="teeth[]" id="process_teeth" onchange="get_teeth_process()" data-placeholder="<?= $ci->lang('select') ?>" multiple>
							<!-- Teeth options populated dynamically -->
						</select>
					</div>
					<div class="customHr"></div>
				</div>

				<!--info-->
				<form id="processForm">
					<div id="teeth_processes_container"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>
				<button class="btn btn-primary" onclick="submitWithoutDatatable('tableInsert', '<?= base_url() ?>admin/insert_lab', 'labsTable', 'laboratoryInsertModal', list_labs)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>

