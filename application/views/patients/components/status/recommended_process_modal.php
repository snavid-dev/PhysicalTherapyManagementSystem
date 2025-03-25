<?php
$ci = get_instance();
?>

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
						<select class="form-control select2-show-search form-select"
								id="services_restorative"
								onchange="service_price_resto(), calculate_sum()"
								data-placeholder="<?= $ci->lang('select') ?>" multiple>
							<option value="1">1</option>
							<option value="1">1</option>

						</select>
					</div>

					<!--custom hr-->
					<div class="customHr"></div>
				</div>

				<!--info-->
				<form id="processForm">

					<div class="row nthHrLine">

						<div class="col-sm-12 col-md-12 greyline">
							<!--Processes-->
							<div class="customMargin">
								<div class="processHeader">
									<h2 style="margin-bottom: 30px">Tooth 18</h2>
									<input type="hidden" name="toothName">
								</div>


								<div class="row">

									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox" data-target="textarea1" onclick="otherProcess(this)">
											<span>other</span>
										</label>

									</div>

									<div class="col-sm-12 col-md-12" id="textarea1" style="display: none">
										<label>Other Process</label>
										<textarea class="form-control"></textarea>
									</div>


								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-12 greyline">
							<!--Processes-->
							<div class="customMargin">
								<div class="processHeader">
									<h2 style="margin-bottom: 30px">Tooth 18</h2>
									<input type="hidden" name="toothName">
								</div>


								<div class="row">

									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox" data-target="textarea2" onclick="otherProcess(this)">
											<span>other</span>
										</label>

									</div>

									<div class="col-sm-12 col-md-12" id="textarea2" style="display: none">
										<label>Other Process</label>
										<textarea class="form-control"></textarea>
									</div>


								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-12 greyline">
							<!--Processes-->
							<div class="customMargin">
								<div class="processHeader">
									<h2 style="margin-bottom: 30px">Tooth 18</h2>
									<input type="hidden" name="toothName">
								</div>


								<div class="row">

									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox">
											<span>echo1</span>
										</label>

									</div>
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox ">
										<label class="cl-checkbox">
											<input type="checkbox" data-target="textarea3" onclick="otherProcess(this)">
											<span>other</span>
										</label>

									</div>

									<div class="col-sm-12 col-md-12" id="textarea3" style="display: none">
										<label>Other Process</label>
										<textarea class="form-control"></textarea>
									</div>


								</div>
							</div>
						</div>

					</div>

				</form>

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
