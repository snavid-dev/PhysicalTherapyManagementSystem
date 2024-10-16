<?php $ci = get_instance(); ?>
<div class="modal fade effect-scale" id="paymentModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert payment') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertPayment">
						<div class="row">

							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('select turn') ?> <span
											class="text-red">*</span></label>
									<select name="slug" class="form-control select2-show-search form-select"
											id="select_turns" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('amount') ?> <span class="text-red">*</span></label>
									<input type="number" name="cr" class="form-control"
										   placeholder="<?= $ci->lang('amount') ?>" id="" autocomplete="off">
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-warning"
						onclick="updateWithoutDatatable('insertPayment', '<?= base_url() ?>admin/pay_turn', 'turnsTable', 'paymentModal', print_payment, 'print');"><?= $ci->lang('save and print') ?>
					<i class="fa fa-print"></i></button>
				<button class="btn btn-primary"
						onclick="updateWithoutDatatable('insertPayment', '<?= base_url() ?>admin/pay_turn', 'turnsTable', 'paymentModal', update_balance)"><?= $ci->lang('save') ?>
					<i class="fa fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>
