<?php
$ci = get_instance();
?>
<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3" id="Restorative-pane_baby_update"
	 role="tabpanel" aria-labelledby="Restorative-pane" tabindex="0">
	<div class="modal-body">
		<div class="row">

			<!-- fixing modal contents -->
			<div class="col-md-10">

				<div class="row">

					<!-- fixing modal contents -->
					<div class="col-md-12">

						<form id="insertFixing_baby_update">

							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">

										<label class="form-label">
											<?= $ci->lang('Caries Depth') ?>
										</label>
										<select id="insertCariesDepth_baby_update" name="CariesDepth"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($CariesDepthList as $CariesDepth) : ?>
												<option value="<?= $CariesDepth['id'] ?>"><?= $ci->lang($CariesDepth['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('base or liner material') ?>
										</label>
										<select id="insertMaterial_baby_update" name="Material"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($BaseOrLinerMaterialList as $BaseOrLinerMaterial) : ?>
												<option value="<?= $BaseOrLinerMaterial['id'] ?>"><?= $BaseOrLinerMaterial['name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-12">
									<div class="form-group">
										<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
										<label class="form-label">
											<?= $ci->lang('restorative material') ?>
										</label>
										<select id="insertRestorativeMaterial_baby_update"
												name="RestorativeMaterial"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="showBonding('#insertRestorativeMaterial_baby_update','composite_baby_update', 'bonding_baby_update', 'amalgam_baby_update')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($RestorativeMaterialList as $Restorativeaterial) : ?>
												<option value="<?= $Restorativeaterial['id'] ?>"><?= $Restorativeaterial['name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6" id="composite_baby_update" style="display:none;">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('composite brand') ?>
										</label>
										<select id="insertCompositeBrand_baby_update" name="CompositeBrand"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($CompositeBrandList as $CompositeBrand) : ?>
												<option value="<?= $CompositeBrand['id'] ?>"><?= $CompositeBrand['name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6" id="bonding_baby_update" style="display:none;">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('bonding brand') ?>
										</label>
										<select id="insertBondingBrand_baby_update" name="bondingBrand"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($BondingBrandList as $BondingBrand) : ?>
												<option value="<?= $BondingBrand['id'] ?>"><?= $BondingBrand['name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-12" id="amalgam_baby_update" style="display:none;">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amalgam brand') ?>
										</label>
										<select id="insertAmalgamBrand_baby_update" name="AmalgamBrand"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($AmalgamBrandList as $AmalgamBrand) : ?>
												<option value="<?= $AmalgamBrand['id'] ?>"><?= $AmalgamBrand['name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('treatment') ?> <span class="text-red">*</span>
										</label>
										<select class="form-control select2-show-search form-select"
												id="services_restorative_baby_update"
												onchange="service_price_resto('#services_restorative_baby_update', '#services_input_restorative_baby_update', '#price_tooth_restorative_baby_update'), calculate_sum_baby()"
												data-placeholder="<?= $ci->lang('select') ?>" multiple>
											<?php foreach ($restorative_services as $service) : ?>
												<option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" name="imgAddress" id="adulth_teeth_location_baby_update" value="">
										<input type="hidden" name="restorative_services" id="services_input_restorative_baby_update">
									</div>
								</div>

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="price_restorative"
											   id="price_tooth_restorative_baby_update" class="form-control"
											   placeholder="<?= $ci->lang('pay amount') ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-12">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('description') ?>
										</label>
										<textarea class="form-control" name="restorativeDescription"
												  placeholder="<?= $ci->lang('desc') ?>" rows="7"></textarea>
									</div>
								</div>
							</div>

						</form>

					</div>

				</div>

			</div>

			<!-- fixing modal picture -->
			<div class="col-md-2">
				<div class="modal-image-area">
					<h2 class="modal-Title"></h2>
					<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
						 class="modalimg" id="modalImage3_baby_update">
					<div>
						<label class="form-label">
							<?= $ci->lang('pay amount') ?>
						</label>
						<input type="text" id="priceTag_resto_baby_update" class="form-control" name="total_price">
					</div>
				</div>
			</div>

		</div>
	</div>

</div>
<script>

</script>
