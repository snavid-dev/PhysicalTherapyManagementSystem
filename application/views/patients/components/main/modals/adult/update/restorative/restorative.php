<?php
$ci = get_instance();
?>
<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3" id="Restorative_update-pane"
					 role="tabpanel" aria-labelledby="Restorative_update-pane" tabindex="0">
					<div class="modal-body">
						<div class="row">

							<!-- fixing modal contents -->
							<div class="col-md-10">

								<div class="row">

									<!-- fixing modal contents -->
									<div class="col-md-12">

										<form id="updateFixing">

											<div class="row">
												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('Caries Depth') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateCariesDepth" name="CariesDepth"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($CariesDepthList as $CariesDepth) : ?>
																<option
																	value="<?= $CariesDepth['id'] ?>"><?= $ci->lang($CariesDepth['name']) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>


												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('base or liner material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateMaterial" name="Material"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($BaseOrLinerMaterialList as $BaseOrLinerMaterial) : ?>
																<option
																	value="<?= $BaseOrLinerMaterial['id'] ?>"><?= $BaseOrLinerMaterial['name'] ?></option>
															<?php endforeach; ?>

														</select>
													</div>
												</div>


												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<input type="hidden" name="patient_id"
															   value="<?= $profile['id'] ?>">
														<label class="form-label">
															<?= $ci->lang('restorative material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateRestorativeMaterial"
																name="RestorativeMaterial"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="showBonding('#updateRestorativeMaterial','composite_update', 'bonding_update', 'amalgam_update')">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($RestorativeMaterialList as $Restorativeaterial) : ?>
																<option
																	value="<?= $Restorativeaterial['id'] ?>"><?= $Restorativeaterial['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-6" id="composite_update" style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('composite brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateCompositeBrand" name="CompositeBrand"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($CompositeBrandList as $CompositeBrand) : ?>
																<option
																	value="<?= $CompositeBrand['id'] ?>"><?= $CompositeBrand['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-6" id="bonding_update" style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('bonding brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateBondingBrand" name="bondingBrand"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($BondingBrandList as $BondingBrand) : ?>
																<option
																	value="<?= $BondingBrand['id'] ?>"><?= $BondingBrand['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>


												<div class="col-sm-12 col-md-12" id="amalgam_update" style="display:none;">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('amalgam brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="updateAmalgamBrand" name="AmalgamBrand"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($AmalgamBrandList as $AmalgamBrand) : ?>
																<option
																	value="<?= $AmalgamBrand['id'] ?>"><?= $AmalgamBrand['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>


												<div class="col-sm-12 col-md-6">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('treatment') ?> <span
																class="text-red">*</span>
														</label>
														<select class="form-control select2-show-search form-select"
																id="services_restorative_update"
																onchange="service_price_resto('#services_restorative_update', '#services_input_restorative_update', '#price_tooth_restorative_update', insert_resto_price_update) "
																data-placeholder="<?= $ci->lang('select') ?>" multiple>
															<?php foreach ($restorative_services as $service) : ?>

																<option
																	value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

															<?php endforeach; ?>

														</select>
														<input type="hidden" name="imgAddress"
															   id="adulth_teeth_location" value="">
														<input type="hidden" name="restorative_services"
															   id="services_input_restorative_update">
													</div>
												</div>

												<div class="col-sm-12 col-md-6">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('pay amount') ?> <span
																class="text-red">*</span>
														</label>
														<input type="number" name="price_restorative"
															   id="price_tooth_restorative_update" class="form-control"
															   placeholder="<?= $ci->lang('pay amount') ?>">
													</div>
												</div>


												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('description') ?>
														</label>
														<textarea class="form-control" id="restorative_details_update" name="restorativeDescription"
																  placeholder="<?= $ci->lang('desc') ?>"
																  rows="7"></textarea>
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
										 class="modalimg" id="modalImage_update_resto">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_resto_update" class="form-control" name="total_price">
									</div>

								</div>

							</div>

							</form>

						</div>

					</div>
				</div>
