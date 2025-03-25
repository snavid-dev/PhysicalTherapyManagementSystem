<?php

$ci = get_instance();

?>
<div class="col-xxl-2">
		<div class="card custom-card">
			<div class="p-4 border-bottom border-block-end-dashed">
				<p class="fs-15 mb-2 fw-semibold"><?= $ci->lang('payment status') ?> :</p>
				<?php
				$percentage = ($sum_dr != 0) ? ($sum_cr * 100) / $sum_dr : 100;
				?>
				<p class="fw-semibold mb-2"
				   id="percentage"><?= $ci->language->languages('payment percent status', null, round($percentage)) ?></p>
				<div class="progress progress-sm progress-animate ">
					<div class="progress-bar bg-primary  ronded-1" role="progressbar" aria-valuenow="60"
						 aria-valuemin="0" aria-valuemax="100" style="width: <?= $percentage ?>%"></div>
				</div>
			</div>
		</div>
		<!-- dxdiag -->
		<div class="card custom-card" style="padding: 1rem !important;">
			<div class="form-group">
				<label class="form-label">
					<?= $ci->lang('Toggle View') ?>
				</label>
				<!-- this is an important select tag remember it -->
				<select id="selectToggleView" name="name" class="form-control select2-show-search form-select"
						data-placeholder="<?= $ci->lang('select') ?>" onchange="toogleView()">
					<option label="<?= $ci->lang('select') ?>"></option>
					<option value="simple"><?= $ci->lang('Simple') ?></option>
					<option value="Xray" selected><?= $ci->lang('X-Ray') ?></option>
				</select>
			</div>

		</div>
		<!-- dxdiag -->

		<!-- TODO: the new Action -->
		<div class="card custom-card" style="padding: 1rem !important;">
			<div class="form-group">
				<label class="form-label">
					<?= $ci->lang('actions') ?>
				</label>
				<select id="selectaction" name="actions" class="form-control select2-show-search form-select"
						data-placeholder="<?= $ci->lang('select') ?>" onchange="actions()">
					<option label="<?= $ci->lang('select') ?>"></option>
					<option value="1"><?= $ci->lang('turn') ?></option>
					<option value="2"><?= $ci->lang('cr') ?></option>
					<option value="3"><?= $ci->lang('laboratory') ?></option>
					<option value="4"><?= $ci->lang('prescription') ?></option>
					<option value="5"><?= $ci->lang('archive') ?></option>
					<option value="6"><?= $ci->lang('recommended processes') ?></option>
				</select>
			</div>

		</div>
		<!-- TODO: the new Action end -->

	</div>

<?php
$ci->render('patients/components/status/recommended_process_modal.php');
$ci->render('patients/components/status/status_js.php')
?>
