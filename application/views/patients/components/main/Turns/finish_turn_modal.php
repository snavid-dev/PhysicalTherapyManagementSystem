<?php $ci = get_instance(); ?>
<div class="modal fade effect-scale" id="finishTurnModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('finish turn') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form id="finishTurnForm">
					<input type="hidden" name="turn_id" id="finish_turn_id">
					<div id="finish_turn_processes_container"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('finishTurnForm', '<?= base_url('admin/insert_turn_done_processes') ?>', 'turnsTable', 'finishTurnModal', false); reloadTurnsTable()">
					<?= $ci->lang('save') ?> <i class="fa fa-check-circle"></i>
				</button>
			</div>
		</div>
	</div>
</div>
