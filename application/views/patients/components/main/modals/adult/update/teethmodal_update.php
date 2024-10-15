<div class="modal fade effect-scale" tabindex="-1" id="teethmodal_update" role="dialog">


	<!-- TODO: (Update) Here is the sidebar start -->
	<!-- TODO: (Update) Here is the sidebar end -->


	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header" style="justify-content: space-between !important">
				<!-- (Update) adulth institle -->
				<h5 class="modal-title">
					<?= $ci->lang('Edit Tooth') ?>
				</h5>

				<form id="demo_form_update" style="width: 75%; display: inline-flex; align-items: center;">

					<div style="width: 500px;display: flex;gap: 10px;">
						<label class="form-label" style="display: flex;">
							<?= $ci->lang('diagnose') ?> <span class="text-red">*</span>
						</label>
						<select class="form-control select2-show-search form-select" name="diagnoses"
								style="width: 300px !important;"
								onchange="multiple_value('#select_diagnose_update', '#diagnose_adult_update')"
								id="select_diagnose_update" data-placeholder="<?= $ci->lang('select') ?>" multiple>
							<option label="<?= $ci->lang('select') ?>" value=""></option>

							<?php foreach ($diagnoses as $diagnose) : ?>
								<option value="<?= $diagnose['id'] ?>">
									<?= $diagnose['name'] ?>
								</option>

							<?php endforeach; ?>

						</select>
					</div>
					<!-- this form was added to debut a shit -->
				</form>


				<!-- (Update) adulth insbtn -->
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
						style="margin: unset; padding: unset;">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<!-- (Update) insTab start head -->
			<div class="border-block-end-dashed  bg-white rounded-2 p-2">
				<div>
					<ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="insertTabs"
						role="tablist">
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link active" id="Restorative_update" data-bs-toggle="tab"
									data-bs-target="#Restorative_update-pane" type="button" role="tab"
									aria-controls="Restorative_update-pane" aria-selected="true"><i
									class="las la-tooth me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('restorative') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="endo_update" data-bs-toggle="tab"
									data-bs-target="#endo_update-pane" type="button" role="tab"
									aria-controls="endo_update-pane" aria-selected="false"><i
									class="las la-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Endodantic') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="pros_update-tab" data-bs-toggle="tab"
									data-bs-target="#pros_update-pane" type="button" role="tab"
									aria-controls="pros_update-pane" aria-selected="false"><i
									class="las la-vial me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Prosthodontics') ?>
							</button> <!--Translate-->
						</li>
					</ul>
				</div>
			</div>


			<!-- insTabContent start update -->
			<div class="tab-content" id="myITabContent">

				<!--TODO:(Update) insFixing  -->

				<!--insFixing update  -->


				<!-- TODO: (Update) insEndo -->

				<!-- insEndo -->


				<!-- TODO: (Update) insPro -->

				<!-- insPro update -->

			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>

				<button class="btn btn-primary"
						onclick="submitWithoutDatatableMulti(['insertTooth', 'demo_form', 'insertFixing', 'checkboxes'], '<?= base_url() ?>admin/insert_tooth', '','teethmodal', list_teeth)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>

			</div>
		</div>
	</div>
</div>