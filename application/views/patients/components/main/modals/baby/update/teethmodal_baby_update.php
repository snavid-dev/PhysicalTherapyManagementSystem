<?php
$ci = get_instance();
?>
<div class="modal fade effect-scale" tabindex="-1" id="teethmodal_baby_update" role="dialog">


	<!-- TODO: Here is the sidebar start -->
	<?php $ci->render("patients/components/main/modals/baby/update/sidebar/sidebar.php");  ?>
	<!-- TODO: Here is the sidebar end -->


	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header" style="justify-content: space-between !important">
				<!-- adulth institle -->
				<h5 class="modal-title">
<!--					--><?php //= $ci->lang('Insert Tooth') ?>
					update tooth baby
				</h5>

				<form id="demo_form_baby_update" style="width: 75%; display: inline-flex; align-items: center;">

					<div style="width: 500px;display: flex;gap: 10px;">
						<label class="form-label" style="display: flex;">
							<?= $ci->lang('diagnose') ?> <span class="text-red">*</span>
						</label>
						<select class="form-control select2-show-search form-select" name="diagnoses"
							style="width: 300px !important;"
							onchange="multiple_value('#select_diagnose', '#diagnose_adult')" id="select_diagnose_baby_update"
							data-placeholder="<?= $ci->lang('select') ?>" multiple>
							<option label="<?= $ci->lang('select') ?>" value=""></option>

							<?php foreach ($diagnoses as $diagnose) : ?>
								<option value="<?= $diagnose['id'] ?>">
									<?= $diagnose['name'] ?>
								</option>

							<?php endforeach; ?>

						</select>
					</div>
				</form>


				<!-- adulth insbtn -->
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
					style="margin: unset; padding: unset;">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<!-- insTab start head -->
			<div class="border-block-end-dashed  bg-white rounded-2 p-2">
				<div>
					<ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="updateTabs_baby"
						role="tablist">
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link active" id="Restorative_baby_update" data-bs-toggle="tab"
								data-bs-target="#Restorative-pane_baby_update" type="button" role="tab"
								aria-controls="Restorative-pane_baby_update" aria-selected="true"><i
									class="fa fa-tooth me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('restorative') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="endo_baby_update" data-bs-toggle="tab" data-bs-target="#endo-pane_baby_update"
								type="button" role="tab" aria-controls="endo-pane_baby_update" aria-selected="false"><i
									class="fa fa-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Endodantic') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="pros-tab_baby_update" data-bs-toggle="tab" data-bs-target="#pros-pane_baby_update"
								type="button" role="tab" aria-controls="pros-pane_baby_update" aria-selected="false"><i
									class="fa fa-vial me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Prosthodontics') ?>
							</button> <!--Translate-->
						</li>
					</ul>
				</div>
			</div>


			<!-- insTabContent start -->
			<div class="tab-content" id="myITabContent_baby_update">

				<!--TODO:insFixing  -->
				<!--insFixing  -->
				<?php $ci->render("patients/components/main/modals/baby/update/restorative/restorative.php");  ?>


				<!-- TODO:insEndo -->
				<?php $ci->render("patients/components/main/modals/baby/update/endo/endo.php");  ?>

				<!-- insEndo -->


				<!-- TODO:insPro -->
				<?php $ci->render("patients/components/main/modals/baby/update/pro/pro.php");  ?>

				<!-- insPro -->

			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>

				<button class="btn btn-primary"
					onclick="submitWithoutDatatableMulti(['insertTooth_baby', 'demo_form_baby', 'insertFixing_baby', 'insertPro_baby', 'checkboxes_baby'], '<?= base_url() ?>admin/insert_tooth', '','teethmodal', list_teeth)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>

			</div>
		</div>
	</div>
</div>
