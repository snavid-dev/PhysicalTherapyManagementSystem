<?php
$ci = get_instance();
?>
<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="endo-pane_baby" role="tabpanel"
	 aria-labelledby="Endo-pane" tabindex="0">

	<div class="modal-body">
		<div class="row">
			<div class="col-md-10">

				<form id="insertTooth_baby">
					<div class="row">

						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<input type="hidden" name="id" value="<?= $profile['id'] ?>">
								<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
								<label class="form-label">
									<?= $ci->lang('tooth name') ?> <span class="text-red">*</span>
								</label>
								<select id="selectName_baby" name="name"
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
									<option value="E">E</option>
								</select>
							</div>
						</div>

						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('tooth location') ?> <span class="text-red">*</span>
								</label>

								<select id="locationSelector_baby" name="location"
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
									<option value="1"><?= $ci->lang('up right') ?></option>
									<option value="2"><?= $ci->lang('up left') ?></option>
									<option value="3"><?= $ci->lang('down right') ?></option>
									<option value="4"><?= $ci->lang('down left') ?></option>
								</select>
							</div>
						</div>

						<div class="col-sm-12 col-md-4">
							<div class="form-group jdp" id="main-divs_baby">
								<label class="form-label">
									<?= $ci->lang('number of canal') ?>
								</label>

								<select id="canalselector_baby" name="root_number"
										class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>"
										onchange="showRow('#canalselector_baby', '#firstRow_baby', '#secoundRow_baby', '#thirdRow_baby', '#fourthRow_baby', '#fifthRow_baby')">
									<option label="<?= $ci->lang('select') ?>"></option>

									<option value="0">none</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>

							</div>
						</div>
					</div>

					<div>
						<!-- Repeated canal rows -->
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<div class="row"
								 id="<?= ['first', 'secound', 'third', 'fourth', 'fifth'][$i - 1] ?>Row_baby"
								 style="display: none;">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>
										</label>
										<select name="r_name<?= $i ?>"
												class="form-control select2-show-search form-select"
												id="canalLocation<?= $i ?>_baby"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>"><?= $value ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>
										</label>
										<input type="number" name="r_width<?= $i ?>" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">
									</div>
								</div>
							</div>
						<?php endfor; ?>
					</div>

					<div class="row">
						<div style="border-bottom: 1px solid gray;margin: 50px 0 30px 0;opacity: 0.5;"></div>
						<!-- type of obturation -->
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('type of obturation') ?>
								</label>
								<select name="typeObturation"
										class="form-control select2-show-search form-select"
										id="instypeObturation_baby"
										data-placeholder="<?= $ci->lang('type of obturation') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
									<?php foreach ($typeOfAbturationList as $typeOfAbturation) : ?>
										<option
											value="<?= $typeOfAbturation['id'] ?>"><?= $typeOfAbturation['name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- type of sealer -->
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('type of sealer') ?>
								</label>
								<select name="TypeSealer"
										class="form-control select2-show-search form-select"
										id="insTypeSealer_baby"
										data-placeholder="<?= $ci->lang('type of sealer') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
									<?php foreach ($typeOfSealerList as $typeOfSealer) : ?>
										<option value="<?= $typeOfSealer['id'] ?>"><?= $typeOfSealer['name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- type of irrigation -->
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('type of irrigation') ?>
								</label>
								<select name="TypeIrrigation"
										class="form-control select2-show-search form-select"
										id="insTypeIrrigation_baby"
										data-placeholder="<?= $ci->lang('type of irrigation') ?>">
									<option label="<?= $ci->lang('select') ?>"></option>
									<?php foreach ($typeOfIrregationList as $typeOfIrregation) : ?>
										<option
											value="<?= $typeOfIrregation['id'] ?>"><?= $typeOfIrregation['name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- treatment -->
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('treatment') ?> <span class="text-red">*</span>
								</label>
								<input type="hidden" name="diagnose" id="diagnose_adultOld_baby">
								<select class="form-control select2-show-search form-select"
										id="services_baby" onchange="service_price('#services_baby', '#services_input_baby', '#price_tooth_baby'), calculate_sum_baby()"
										data-placeholder="<?= $ci->lang('select') ?>" multiple>
									<?php foreach ($endo_services as $service) : ?>
										<option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
									<?php endforeach; ?>
								</select>
								<input type="hidden" name="imgAddress" id="adulth_teeth_location_baby" value="">
								<input type="hidden" name="endo_services" id="services_input_baby">
							</div>
						</div>

						<!-- pay amount -->
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
								</label>
								<input type="number" name="price" id="price_tooth_baby" class="form-control"
									   placeholder="<?= $ci->lang('pay amount') ?>">
							</div>
						</div>

						<!-- description -->
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('description') ?>
								</label>
								<textarea class="form-control" name="details"
										  placeholder="<?= $ci->lang('description') ?>"></textarea>
							</div>
						</div>

					</div>

			</div>
			<div class="col-md-2">
				<div class="modal-image-area">
					<h2 class="modal-Title"></h2>
					<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png"
						 class="modalimg" id="modalImage_baby">
					<div>
						<label class="form-label">
							<?= $ci->lang('pay amount') ?>
						</label>
						<input type="text" id="priceTag_endo_baby" class="form-control" name="total_price">
					</div>
				</div>
			</div>

			</form>
		</div>
	</div>

	<!-- TODO: tooltip div Endo -->
	<div class="item-hints">
		<div class="hint" data-position="4">
			<!-- is-hint -->
			<span class="hint-radius"></span>
			<span class="hint-dot"></span>
			<div class="hint-content do--split-children">
				<p>
					Brushing too hard can wear down enamel.
					Use a soft-bristled brush with gentle strokes.
					Floss daily to clean between teeth and gums.
					Visit your dentist regularly for checkups.
				</p>
			</div>
		</div>
	</div>
	<!-- TODO: tooltip div Endo -->
</div>
