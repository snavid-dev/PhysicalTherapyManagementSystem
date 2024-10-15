<div class="modal fade effect-scale" tabindex="-1" id="extralargemodalxx"
										 role="dialog">
										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<!-- insertHeader _ insTitle _ start -->
													<h5 class="modal-title">
														<?= $ci->lang('Insert Child\'s Teeth') ?>
													</h5>
													<!-- insertHeader _ insTitle _ end -->


													<!-- insertHeader _ insbutton _ start -->

													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>

													<!-- insertHeader _ insbutton _ start -->

												</div>
												<div class="modal-body">

													<div class="row">
														<div class="col-md-10">
															<form id="insertToothbaby">
																<div class="row">


																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('tooth name') ?> <span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="toothnamebaby" name="name"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<option value="A">A</option>
																				<option value="B">B</option>
																				<option value="C">C</option>
																				<option value="D">D</option>
																				<option value="E">E</option>
																			</select>
																			<input type="hidden" name="imgAddress"
																				   id="child_teeth_location" value="">
																			<input type="hidden" name="patient_id"
																				   value="<?= $profile['id'] ?>">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('tooth location') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="toothlocationbaby"
																					name="location"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<option value="1">
																					<?= $ci->lang('up right') ?>
																				</option>
																				<option value="2">
																					<?= $ci->lang('up left') ?>
																				</option>
																				<option value="3">
																					<?= $ci->lang('down right') ?>
																				</option>
																				<option value="4">
																					<?= $ci->lang('down left') ?>
																				</option>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group jdp" id="main-divs">
																			<label class="form-label">
																				<?= $ci->lang('number of canal') ?>
																			</label>

																			<select id="toothbcanal" name="root_number"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="showRow('#toothbcanal', '#firstRowb', '#secoundRowb', '#thirdRowb', '#fourthRowb', '#fifthRowb')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

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
																<!-- maybe you shold wrap these rows onto a div just div -->
																<div class="row" id="firstRowb" style="display: none;">
																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Location -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal location') ?>
																			</label>

																			<select name="r_name1"
																					class="form-control select2-show-search form-select"
																					id="canalbLocation1"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
																					<option value="<?= $key ?>">
																						<?= $value ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Length -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal length') ?>
																			</label>
																			<input id="canalblength1" type="number"
																				   name="r_width1" class="form-control"
																				   placeholder="<?= $ci->lang('canal length') ?>">
																		</div>
																	</div>

																</div>

																<div class="row" id="secoundRowb"
																	 style="display: none;">


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Location -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal location') ?>

																			</label>


																			<select name="r_name2"
																					class="form-control select2-show-search form-select"
																					id="canalbLocation2"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
																					<option value="<?= $key ?>">
																						<?= $value ?>
																					</option>
																				<?php endforeach; ?>

																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Length -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal length') ?>
																			</label>


																			<input id="canalblength2" type="number"
																				   name="r_width2" class="form-control"
																				   placeholder="<?= $ci->lang('canal length') ?>">

																		</div>
																	</div>

																</div>

																<div class="row" id="thirdRowb" style="display: none;">

																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Location -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal location') ?>
																			</label>


																			<select name="r_name3"
																					class="form-control select2-show-search form-select"
																					id="canalbLocation3"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
																					<option value="<?= $key ?>">
																						<?= $value ?>
																					</option>
																				<?php endforeach; ?>

																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Length -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal length') ?>
																			</label>


																			<input id="canalblength3" name="r_width3"
																				   type="number" class="form-control"
																				   placeholder="<?= $ci->lang('canal length') ?>">

																		</div>
																	</div>

																</div>

																<div class="row" id="fourthRowb" style="display: none;">

																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Location -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal location') ?>

																			</label>


																			<select name="r_name4"
																					class="form-control select2-show-search form-select"
																					id="canalbLocation4"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
																					<option value="<?= $key ?>">
																						<?= $value ?>
																					</option>
																				<?php endforeach; ?>

																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Length -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal length') ?>

																			</label>


																			<input id="canalblength4" name="r_width4"
																				   type="number" class="form-control"
																				   placeholder="<?= $ci->lang('canal length') ?>">

																		</div>
																	</div>

																</div>

																<div class="row" id="fifthRowb" style="display: none;">

																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Location -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal location') ?>

																			</label>


																			<select name="r_name5"
																					class="form-control select2-show-search form-select"
																					id="canalbLocation5"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
																					<option value="<?= $key ?>">
																						<?= $value ?>
																					</option>
																				<?php endforeach; ?>

																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-6">
																		<!-- Canal Length -->
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('canal length') ?>

																			</label>


																			<input id="canalblength5" name="r_width5"
																				   type="number" class="form-control"
																				   placeholder="<?= $ci->lang('canal length') ?>">

																		</div>
																	</div>

																</div>


																<div class="row">

																	<div class="col-sm-12 col-md-6">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('diagnose') ?> <span
																					class="text-red">*</span>
																			</label>
																			<textarea class="form-control"
																					  name="diagnose"
																					  placeholder="<?= $ci->lang('diagnose') ?>"
																					  id='bdiagnose'></textarea>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-6">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('description') ?>
																			</label>
																			<textarea class="form-control"
																					  name="details"
																					  placeholder="<?= $ci->lang('description') ?>"
																					  id="bdescription"></textarea>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-6">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('services') ?> <span
																					class="text-red">*</span>
																			</label>


																			<select
																				class="form-control select2-show-search form-select"
																				id="bservices"
																				onchange="service_price('#bservices', '#services_inputbInsert', '#price_toothb')"
																				data-placeholder="<?= $ci->lang('select') ?>"
																				multiple>
																				<option
																					label="<?= $ci->lang('select') ?>"></option>

																				<?php foreach ($services as $service) : ?>

																					<option
																						value="<?= $service['id'] ?>">
																						<?= $service['name'] ?>
																					</option>

																				<?php endforeach; ?>

																			</select>
																			<input type="hidden" name="services"
																				   id="services_inputbInsert">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-6">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('pay amount') ?> <span
																					class="text-red">*</span>
																			</label>
																			<input type="number" name="price"
																				   id="price_toothb"
																				   class="form-control"
																				   placeholder="<?= $ci->lang('pay amount') ?>">
																		</div>
																	</div>

																</div>

															</form>
														</div>

														<div class="col-md-2">

															<div class="modal-image-area">
																<h2 id="modalTitleb">teeth</h2>
																<img
																	src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png"
																	class="modalimg" id="modalImageb">
															</div>

														</div>


													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="submitWithoutDatatable('insertToothbaby', '<?= base_url() ?>admin/insert_tooth', '','extralargemodalxx', list_teeth)">
														<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>