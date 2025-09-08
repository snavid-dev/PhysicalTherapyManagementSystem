<?php
$ci = get_instance();
?>
<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="pros-pane_baby" role="tabpanel"
	 aria-labelledby="Pros-pane" tabindex="0">

	<div class="modal-body">
		<div class="row">
			<!-- Pro modal content -->
			<div class="col-md-10">
				<div class="row">
					<div class="col-sm-12">
						<form action="" id="insertPro_baby">
							<div class="row">
								<div class="col-sm-12 col-md-12">
									<label class="form-label">
										<?= ucwords('type of Prosthodontics') ?>
									</label>
									<select id="type_pro_baby" name="type_pro"
											onchange="check_pro_type('type_pro_baby')"
											class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option value="abutment" selected><?= ucwords('abutment') ?></option>
										<option value="pontic"><?= ucwords('pontic') ?></option>
									</select>
								</div>

								<div class="col-sm-12 col-md-6 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('type of restoration') ?></label>
										<select id="type_restoration_baby" name="type_restoration"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($TypeOfRestorationList as $typeOfRestorationList) : ?>
												<option
													value="<?= $typeOfRestorationList['id'] ?>"><?= ucwords($typeOfRestorationList['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('Filling material (Core)') ?></label>
										<select id="filling_material_baby" name="filling_material"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($CoreMaterialList as $CoreMaterial) : ?>
												<option
													value="<?= $CoreMaterial['id'] ?>"><?= ucwords($CoreMaterial['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('post') ?></label>
										<select id="post_baby" name="post"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="showSelect('post_baby','fiber_post_div_baby', 'metal_screw_post_div_baby', 'type_crown_material_div_baby')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($PostList as $Post) : ?>
												<option
													value="<?= $Post['id'] ?>"><?= ucwords($Post['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6 nonDisplay abutment" id="fiber_post_div_baby">
									<div class="form-group">
										<label class="form-label"><?= ucwords('type of prefabricated post') ?></label>
										<select id="fiber_post_baby" name="PrefabricatedPost"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($PrefabricatedPostList as $PrefabricatedPost) : ?>
												<option
													value="<?= $PrefabricatedPost['id'] ?>"><?= ucwords($PrefabricatedPost['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6 nonDisplay abutment" id="metal_screw_post_div_baby">
									<div class="form-group">
										<label class="form-label"><?= ucwords('type of custom post') ?></label>
										<select id="metal_screw_post_baby" name="customPost"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($CustomPostList as $CustomPost) : ?>
												<option
													value="<?= $CustomPost['id'] ?>"><?= ucwords($CustomPost['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-6 abutment" id="type_crown_material_div_baby">
									<div class="form-group">
										<label class="form-label"><?= ucwords('type of crown material') ?></label>
										<select id="crown_material_baby" name="crown_material"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($MaterialOfCrownList as $MaterialOfCrown) : ?>
												<option
													value="<?= $MaterialOfCrown['id'] ?>"><?= ucwords($MaterialOfCrown['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-4 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('color') ?></label>
										<select id="pro_color_baby" name="pro_color"
												onchange="multiple_value('#pro_color_baby', '#pro_colors_baby')"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple>
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ColorList as $Color) : ?>
												<option
													value="<?= $Color['id'] ?>"><?= ucwords($Color['name']) ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" name="color" id="pro_colors_baby">
									</div>
								</div>

								<div class="col-sm-12 col-md-4 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('Impression Technique') ?></label>
										<select id="impression_technique_baby" name="impression_technique"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="ImpressionTechniq('impression_technique_baby', 'impression_material_div_baby')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ImpressionTechniqueList as $ImpressionTechnique) : ?>
												<option
													value="<?= $ImpressionTechnique['id'] ?>"><?= ucwords($ImpressionTechnique['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-4 nonDisplay abutment" id="impression_material_div_baby">
									<div class="form-group">
										<label class="form-label"><?= ucwords('Impression material') ?></label>
										<select id="impression_material_baby" name="impression_material"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ImpressionMaterialsList as $ImpressionMaterials) : ?>
												<option
													value="<?= $ImpressionMaterials['id'] ?>"><?= ucwords($ImpressionMaterials['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-4 abutment">
									<div class="form-group">
										<label class="form-label"><?= ucwords('Cement Material') ?></label>
										<select id="content_material_baby" name="CementMaterial"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($CementMaterialList as $CementMaterial) : ?>
												<option
													value="<?= $CementMaterial['id'] ?>"><?= ucwords($CementMaterial['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-12 pontic nonDisplay">
									<div class="form-group">
										<label class="form-label"><?= ucwords('Pontic Design') ?></label>
										<select id="pontic_design_baby" name="pontic_design"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($PonticDesignList as $PonticDesign) : ?>
												<option
													value="<?= $PonticDesign['id'] ?>"><?= ucwords($PonticDesign['name']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label class="form-label"><?= $ci->lang('treatment') ?> <span
													class="text-red">*</span></label>
											<input type="hidden" name="diagnose" id="diagnose_adult_baby">
											<select class="form-control select2-show-search form-select"
													id="services_pro_baby"
													onchange="service_price_pro('#services_pro_baby', '#services_input_pro_baby', '#price_tooth_pro_baby'), calculate_sum_baby()"
													data-placeholder="<?= $ci->lang('select') ?>" multiple>
												<?php foreach ($Prosthodontics_services as $service) : ?>
													<option
														value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
												<?php endforeach; ?>
											</select>
											<input type="hidden" name="pro_services" id="services_input_pro_baby">
										</div>
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label class="form-label"><?= $ci->lang('pay amount') ?> <span
													class="text-red">*</span></label>
											<input type="number" name="price_pro" id="price_tooth_pro_baby"
												   class="form-control"
												   placeholder="<?= $ci->lang('pay amount') ?>">
										</div>
									</div>

									<div class="col-sm-12 col-md-12">
										<div class="form-group">
											<label class="form-label"><?= $ci->lang('description') ?></label>
											<textarea class="form-control" name="details_pro"
													  placeholder="<?= $ci->lang('description') ?>"></textarea>
										</div>
									</div>
								</div>

							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Pro modal picture -->
			<div class="col-md-2">
				<div class="modal-image-area">
					<h2 class="modal-Title"></h2>
					<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
						 class="modalimg" id="modalImage2_baby">
					<div>
						<label class="form-label"><?= $ci->lang('pay amount') ?></label>
						<input type="text" id="priceTag_pro_baby" class="form-control" name="total_price">
					</div>
				</div>
			</div>
		</div>
	</div>


</div>
