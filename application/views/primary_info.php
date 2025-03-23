<?php $ci = get_instance(); ?>
<div class="row">
	<div class="col-12 col-sm-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0"><?= $ci->lang('report patients') ?>:</h3>
			</div>
			<div class="card-body pt-4">
				<div class="grid-margin">
					<div class="">
						<div class="panel panel-primary">
							<div class="tab-menu-heading border-0 p-0">
								<div class="tabs-menu1">
									<!-- Tabs -->
									<ul class="nav panel-tabs product-sale" role="tablist">
										<li><a href="#services_tab" class="active" data-bs-toggle="tab"
											   aria-selected="true" role="tab"><?= $ci->lang('services') ?></a></li>
										<li><a href="#medicine_tab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('medicines') ?></a></li>
										<li><a href="#diagnose_tab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('diagnoses') ?></a></li>
										<li><a href="#restorativeTab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('restorative') ?></a></li>
										<li><a href="#EndoTab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('Endodantic') ?></a></li>
										<li><a href="#ProsthodonticsTab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('Prosthodontics') ?></a></li>
										<li><a href="#categoriesTab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('categories') ?></a></li>
										<li><a href="#rxTab" data-bs-toggle="tab" class="text-dark"
											   aria-selected="false" role="tab"
											   tabindex="-1"><?= $ci->lang('prescription') ?></a></li>
									</ul>
								</div>
							</div>
							<div class="panel-body tabs-menu-body border-0 pt-0">

								<div class="tab-content">

									<!-- TODO:tab5 services start-->
									<div class="tab-pane active show" id="services_tab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#insertServicesModal"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>


										<div class="table-responsive">
											<table id="services_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('department') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('price') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($services as $service) : ?>
													<tr id="<?= $service['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $service['name'] ?></td>
														<td><?= $ci->lang($service['department']) ?></td>
														<td class="english"><?= $service['price'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_service('<?= $service['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $service['id'] ?>', '<?= base_url() ?>admin/delete_service', 'services_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- tab5 services end -->


									<!-- Start services Modals -->

									<!-- Modal Insert -->
									<div class="modal fade effect-scale" id="insertServicesModal" tabindex="-1"
										 role="dialog">
										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><?= $ci->lang('insert service') ?></h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">

													<form id="insert">
														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label
																		class="form-label"><?= $ci->lang('department') ?>
																		<span class="text-red">*</span></label>
																	<select name="department"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($ci->dentist->departments_list() as $department) : ?>
																			<option value="<?= $department['name'] ?>">
																				<?= $ci->lang($department['name']) ?>
																			</option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>

															<div class="col-sm-12 col-md-2">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('price') ?>
																		<span class="text-red">*</span></label>
																	<input type="number" name="price"
																		   class="form-control"
																		   placeholder="<?= $ci->lang('price') ?>">
																</div>
															</div>

															<div class="col-sm-12 col-md-2">
																<button class="btn btn-danger" type="button"
																		style="margin-top: 36px" id="addRowButton">
																	<?= $ci->lang('add row') ?>
																</button>
															</div>

														</div>

														<div class="percentageRow">
															<table id="percentageTable" class="table">
																<thead>
																<tr>
																	<th><?= $ci->lang('number of row') ?></th>
																	<th><?= $ci->lang('name') ?></th>
																	<th><?= $ci->lang('percentage') ?></th>
																	<th><?= $ci->lang('actions') ?></th>
																</tr>
																</thead>
																<tbody>
																<!-- Rows will be dynamically added here -->
																</tbody>
															</table>
														</div>
													</form>

												</div>
												<div class="modal-footer">
													<button class="btn btn-secondary"
															data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
															class="fa fa-close"></i></button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert', '<?= base_url() ?>admin/insert_service', 'services_table', 'insertServicesModal')"><?= $ci->lang('save') ?>
														<i class="fa fa-plus"></i></button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal End -->


									<!-- Modal edit -->
									<div class="modal fade effect-scale" id="editServicesModal" tabindex="-1"
										 role="dialog">
										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><?= $ci->lang('edit service') ?></h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="update">
														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="hidden" name="slug" id="slug_services">
																	<input type="text" name="name" class="form-control"
																		   id="name"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>


															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label
																		class="form-label"><?= $ci->lang('department') ?>
																		<span class="text-red">*</span></label>
																	<select name="department" id="department"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($ci->dentist->departments_list() as $department) : ?>
																			<option value="<?= $department['name'] ?>">
																				<?= $ci->lang($department['name']) ?>
																			</option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>


															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('price') ?>
																		<span class="text-red">*</span></label>
																	<input type="hidden" name="nameOld" id="nameOld">
																	<input type="number" name="price"
																		   class="form-control" id="price"
																		   placeholder="<?= $ci->lang('price') ?>">
																</div>
															</div>
														</div>

														<!-- Add this inside the modal body after the form -->
														<div class="percentageRow">
															<button class="btn btn-danger" type="button"
																	style="margin-bottom: 15px;" id="editAddRowButton">
																<?= $ci->lang('add row') ?>
															</button>

															<table id="edit_percentageTable" class="table">
																<thead>
																<tr>
																	<th><?= $ci->lang('number of row') ?></th>
																	<th><?= $ci->lang('name') ?></th>
																	<th><?= $ci->lang('percentage') ?></th>
																	<th><?= $ci->lang('actions') ?></th>
																</tr>
																</thead>
																<tbody>
																<!-- Dynamic rows get inserted here -->
																</tbody>
															</table>
														</div>
													</form>

												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary"
															data-bs-dismiss="modal"><?= $ci->lang('cancel') ?><i
															class="fa fa-close"></i></button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('update', '<?= base_url() ?>admin/update_service', 'editServicesModal', false, 'services_table')"><?= $ci->lang('update') ?>
														<i class="fa fa-edit"></i></button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal End -->

									<!-- End services Modals -->

									<!-- TODO: tab6 medicine start  -->
									<div class="tab-pane" id="medicine_tab" role="tabpanel">

										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#Medicine">
											<?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
										</button>


										<div class="table-responsive">
											<table id="medicine_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Medicine Type') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Medicine Name') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Medicine Doze') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Medicine Unit') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Medicine Usage') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Day') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('Time') ?>
													</th>
													<th class="border-bottom-0">
														<?= $ci->lang('actions') ?>
													</th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($medicines as $medicine) : ?>
													<tr id="<?= $medicine['id'] ?>">
														<td>
															<?= $i ?>
														</td>
														<td>
															<?= $medicine['type'] ?>
														</td>
														<td>
															<?= $medicine['name'] ?>
														</td>
														<td>
															<?= $medicine['doze'] ?>
														</td>
														<td>
															<?= $medicine['unit'] ?>
														</td>
														<td>
															<?= $medicine['usageType'] ?>
														</td>
														<td>
															<?= $medicine['day'] ?>
														</td>
														<td>
															<?= $medicine['times'] ?>
														</td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_medicine('<?= $medicine['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
																   data-bs-toggle="tooltip"
																   data-bs-original-title="<?= $ci->lang('edit') ?>"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $medicine['id'] ?>', '<?= base_url() ?>admin/delete_medicine', 'medicine_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"
																   data-bs-toggle="tooltip"
																   data-bs-original-title="<?= $ci->lang('delete') ?>"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>

									</div>
									<!-- tab6 medicine end -->

									<!-- Start Medicine Modals -->

									<!-- Insert Modal ُstart --------------------------------------------------------------------------------------------- -->
									<div class="modal fade effect-scale" tabindex="-1" id="Medicine" role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert medicine') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="row">

														<div class="col-md-12">

															<form id="medicine_insert">
																<div class="row">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Type') ?> <span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="medicineType" name="type"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage() as $usage) : ?>
																					<option value="<?= $usage ?>">
																						<?= $usage ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="text" class="form-control"
																				   id="medicineName" name="name">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze"
																				   id="medicineDoze"
																				   class="form-control">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite" name="unit"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>


																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUsage" name="usageType"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																</div>


																<div class="row">

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineDay" value="1"
																				   name="day">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<!-- <span><ion-icon name="close-outline"></ion-icon></span> -->
																		<span class="the_X">X</span>
																	</div>

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineTime" value="3"
																				   name="times">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-3">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineAmount" name="amount">

																		</div>
																	</div>


																</div>
															</form>

														</div>
													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('medicine_insert', '<?= base_url() ?>admin/insert_medicine', 'medicine_table', 'Medicine')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Insert Modal end -------------------------------------------------------------------------------------------------->

									<!-- Edit Modal start --------------------------------------------------------------------------------------------- -->
									<div class="modal fade effect-scale" tabindex="-1" id="Medicine_edit" role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('edit medicine') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="row">

														<div class="col-md-12">

															<form id="medicine_edit_form">
																<div class="row">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Type') ?> <span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="medicineType_edit" name="type"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage() as $usage) : ?>
																					<option value="<?= $usage ?>">
																						<?= $usage ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>


																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?> <span
																					class="text-red">*</span>
																			</label>
																			<input type="hidden" name="slug"
																				   id="slug_medicine">
																			<input type="text" class="form-control"
																				   id="medicineName_edit" name="name">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze"
																				   id="medicineDoze_edit"
																				   class="form-control">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_edit" name="unit"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>


																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUsage_edit"
																					name="usageType"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																</div>


																<div class="row">

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineDay_edit" value="1"
																				   name="day">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<!-- <span><ion-icon name="close-outline"></ion-icon></span> -->
																		<span class="the_X">X</span>
																	</div>

																	<div class="col-sm-12 col-md-4">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineTime_edit" value="3"
																				   name="times">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-3">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" class="form-control"
																				   id="medicineAmount_edit"
																				   name="amount">

																		</div>
																	</div>


																</div>
															</form>

														</div>
													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('medicine_edit_form', '<?= base_url() ?>admin/update_medicine', 'Medicine_edit', false, 'medicine_table')">
														<?= $ci->lang('update') ?><i class="fa fa-edit"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Edit Modal end -------------------------------------------------------------------------------------------------->

									<!-- End Medicine Modals -->

									<!-- TODO: tab7 diagnose start -->
									<div class="tab-pane" id="diagnose_tab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#insertDiagnoseModal"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>


										<div class="table-responsive">
											<table id="diagnose_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($diagnoses as $diagnose) : ?>
													<tr id="<?= $diagnose['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $diagnose['name'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_diagnose('<?= $diagnose['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $diagnose['id'] ?>', '<?= base_url() ?>admin/delete_diagnose', 'diagnose_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- tab7 diagnose end -->


									<!-- Start diagnose Modals -->

									<!-- Modal Insert -->
									<div class="modal fade effect-scale" id="insertDiagnoseModal" tabindex="-1"
										 role="dialog">
										<div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><?= $ci->lang('insert diagnose') ?></h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="insert_diagnose">
														<div class="row">
															<div class="col-sm-12 col-md-12">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>
														</div>
													</form>

												</div>
												<div class="modal-footer">
													<button class="btn btn-secondary"
															data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
															class="fa fa-close"></i></button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert_diagnose', '<?= base_url() ?>admin/insert_diagnose', 'diagnose_table', 'insertDiagnoseModal')"><?= $ci->lang('save') ?>
														<i class="fa fa-plus"></i></button>
												</div>
											</div>
										</div>
									</div>

									<!-- Modal End -->


									<!-- Modal Update -->
									<div class="modal fade effect-scale" id="updateDiagnoseModal" tabindex="-1"
										 role="dialog">
										<div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><?= $ci->lang('update diagnose') ?></h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="update_diagnose">
														<div class="row">
															<div class="col-sm-12 col-md-12">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="hidden" name="nameOld"
																		   id="nameOld_diagnose">
																	<input type="hidden" name="slug" id="slug_diagnose">
																	<input type="text" name="name" class="form-control"
																		   id="name_diagnose"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>
														</div>
													</form>

												</div>
												<div class="modal-footer">
													<button class="btn btn-secondary"
															data-bs-dismiss="modal"><?= $ci->lang('cancel') ?><i
															class="fa fa-close"></i></button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('update_diagnose', '<?= base_url() ?>admin/update_diagnose', 'updateDiagnoseModal', false, 'diagnose_table')"><?= $ci->lang('update') ?>
														<i class="fa fa-edit"></i></button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal End -->

									<!-- End diagnose Modals -->

									<!-- TODO: tab8 restorative start -->
									<div class="tab-pane" id="restorativeTab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#restorativeData_insert"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>


										<div class="table-responsive">
											<table id="restorative_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('category') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($restorative_basic_informations as $restorative_basic_information) : ?>
													<tr id="<?= $restorative_basic_information['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $restorative_basic_information['category_name'] ?></td>
														<td><?= $restorative_basic_information['name'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_restorative('<?= $restorative_basic_information['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $restorative_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'restorative_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- tab8 restorative end -->


									<!-- TODO: tab8 restorative Modals start -->


									<!-- restorative insert Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="restorativeData_insert"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert restorative data') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="insert_restorative">
														<input type="hidden" name="slug" class="form-control">

														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose the data type') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="restorative_data" name="categories_id"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($categories_teeth as $category) : ?>
																			<option
																				value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
																		<?php endforeach; ?>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-8">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<input type="hidden" name="department" value="restorative">

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert_restorative', '<?= base_url() ?>admin/insert_teeth_info', 'restorative_table', 'restorativeData_insert')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- restorative insert Modals end -->


									<!-- restorative update Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="restorativeData_update"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('update restorative data') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="update_restorative">
														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose the data type') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="restorative_data_update"
																			name="categories_id"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>

																		<option value="1"
																				label="<?= $ci->lang('Caries Depth') ?>"> <?= $ci->lang('Caries Depth') ?> </option>
																		<option value="2"
																				label="<?= $ci->lang('base or liner material') ?>"> <?= $ci->lang('base or liner material') ?> </option>
																		<option value="3"
																				label="<?= $ci->lang('restorative material') ?>"> <?= $ci->lang('restorative material') ?> </option>
																		<option value="4"
																				label="<?= $ci->lang('composite brand') ?>"> <?= $ci->lang('composite brand') ?> </option>
																		<option value="5"
																				label="<?= $ci->lang('amalgam brand') ?>"> <?= $ci->lang('amalgam brand') ?> </option>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-8">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input id="restorative_update_name" type="text"
																		   name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																	<input id="restorative_update_nameOld" type="hidden"
																		   name="nameOld" class="form-control">
																</div>
															</div>

															<input type="hidden" name="slug" id="slug_restorative">

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('update_restorative', '<?= base_url() ?>admin/update_basic_information_teeth', 'restorativeData_update', false, 'restorative_table')">
														<?= $ci->lang('update') ?><i class="fa fa-edit"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- restorative update Modals end -->


									<!-- tab8 restorative Modals end -->


									<!-- TODO: tab9 Endo start -->
									<div class="tab-pane" id="EndoTab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#EndoData_insert"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>


										<div class="table-responsive">
											<table id="endo_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('category') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($Endo_basic_informations as $Endo_basic_information) : ?>
													<tr id="<?= $Endo_basic_information['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $Endo_basic_information['category_name'] ?></td>
														<td><?= $Endo_basic_information['name'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_Endo('<?= $Endo_basic_information['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $Endo_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'endo_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- tab9 Endo end -->


									<!-- TODO: tab9 Endo Modals start -->


									<!-- Endo insert Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="EndoData_insert"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert Endo data') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="insert_Endo">

														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose the data type') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="Endo_data" name="categories_id"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($categories_teeth as $category) : ?>
																			<option
																				value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
																		<?php endforeach; ?>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-8">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<input type="hidden" name="department" value="Endo">

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert_Endo', '<?= base_url() ?>admin/insert_teeth_info', 'endo_table', 'EndoData_insert')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>


									<!-- Endo update start -->
									<div class="modal fade effect-scale" tabindex="-1" id="EndoData_update"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('Update Endo data') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="update_Endo">

														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose the data type') ?> <span
																			cla ss="text-red">*</span>
																	</label>

																	<select id="endo_data_update" name="categories_id"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($categories_teeth as $category) : ?>
																			<option
																				value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
																		<?php endforeach; ?>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-8">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" id="endo_update_name"
																		   class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<input type="hidden" id="slug_endo" name="slug">
															<input type="hidden" name="nameOld"
																   id="endo_update_nameOld">

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('update_Endo', '<?= base_url() ?>admin/update_basic_information_teeth', 'EndoData_update', false, 'endo_table')">
														<?= $ci->lang('update') ?><i class="fa fa-edit"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Endo update end -->
									<!-- Endo insert Modals end -->


									<!-- Start Prosthodontics -->
									<div class="tab-pane" id="ProsthodonticsTab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#ProsthodonticsData_insert"><?= $ci->lang('add new') ?>
											<i class="fa fa-plus"></i></button>


										<div class="table-responsive">
											<table id="Prosthodontics_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('category') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($Prosthodontics_basic_informations as $Prosthodontics_basic_information) : ?>
													<tr id="<?= $Prosthodontics_basic_information['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $Prosthodontics_basic_information['category_name'] ?></td>
														<td><?= $Prosthodontics_basic_information['name'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_Prosthodontics('<?= $Prosthodontics_basic_information['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $Prosthodontics_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'Prosthodontics_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- End Prosthodontics -->


									<!-- Prosthodontics insert Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="ProsthodonticsData_insert"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert Prosthodontics data') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="insert_Prosthodontics">

														<div class="row">
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose the data type') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="Prosthodontics_data"
																			name="categories_id"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>
																		<?php foreach ($categories_teeth as $category) : ?>
																			<option
																				value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
																		<?php endforeach; ?>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-8">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<input type="hidden" name="department"
																   value="Prosthodontics">

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert_Prosthodontics', '<?= base_url() ?>admin/insert_teeth_info', 'Prosthodontics_table', 'ProsthodonticsData_insert')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>


									<!-- tab8 restorative Modals end -->


									<!-- TODO: tab9 categories start -->
									<div class="tab-pane" id="categoriesTab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#categories_insert"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>

										<div class="table-responsive">
											<table id="categories_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('type') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($categories as $category) : ?>
													<tr id="<?= $category['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $category['name'] ?></td>
														<td><?= $ci->lang($category['type']) ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:edit_categories('<?= $category['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $category['id'] ?>', '<?= base_url() ?>admin/delete_categories', 'categories_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- tab9 categories end -->


									<!-- TODO: tab9 categories Modals start -->


									<!-- categories insert Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="categories_insert"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert categories') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="insert_categories">
														<div class="row">

															<div class="col-sm-12 col-md-6">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose a category') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="categories_type_data" name="type"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>

																		<option value="files"
																				label="<?= $ci->lang('files') ?>"> <?= $ci->lang('files') ?> </option>
																		<option value="teeth"
																				label="<?= $ci->lang('teeth') ?>"> <?= $ci->lang('teeth') ?> </option>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-6">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="text" name="name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<!-- <input type="hidden" name="department" value="restorative"> -->

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('insert_categories', '<?= base_url() ?>admin/insert_categories', 'categories_table', 'categories_insert')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- categories insert Modals end -->


									<!-- categories update Modals start -->
									<div class="modal fade effect-scale" tabindex="-1" id="categories_update"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert categories') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="update_categories">
														<input type="hidden" name="slug" id="slug_category"
															   class="form-control">

														<div class="row">

															<div class="col-sm-12 col-md-6">
																<div class="form-group">
																	<label class="form-label">
																		<?= $ci->lang('choose a category') ?> <span
																			class="text-red">*</span>
																	</label>

																	<select id="categories_type_data_update" name="type"
																			class="form-control select2-show-search form-select"
																			data-placeholder="<?= $ci->lang('select') ?>">
																		<option
																			label="<?= $ci->lang('select') ?>"></option>

																		<option value="files"
																				label="<?= $ci->lang('files') ?>"> <?= $ci->lang('files') ?> </option>
																		<option value="teeth"
																				label="<?= $ci->lang('teeth') ?>"> <?= $ci->lang('teeth') ?> </option>
																	</select>

																</div>
															</div>

															<div class="col-sm-12 col-md-6">
																<div class="form-group">
																	<label class="form-label"><?= $ci->lang('name') ?>
																		<span class="text-red">*</span></label>
																	<input type="hidden" name="nameOld"
																		   id="old_category_name" class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																	<input type="text" name="name"
																		   id="update_category_name"
																		   class="form-control"
																		   placeholder="<?= $ci->lang('name') ?>">
																</div>
															</div>

															<!-- <input type="hidden" name="department" value="restorative"> -->

														</div>
													</form>
												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrUpdate('update_categories', '<?= base_url() ?>admin/update_categories', 'categories_update', false, 'categories_table')"><?= $ci->lang('update') ?>
														<i class="fa fa-edit"></i></button>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- categories update Modals end -->


									<!-- tab9 categories Modals end -->

									<!-- TODO: rx tab -->
									<!-- tab10 rx tab start -->

									<!--  rx contents start	-->
									<div class="tab-pane" id="rxTab" role="tabpanel">
										<button class="btn btn-primary" data-bs-toggle="modal"
												data-bs-target="#rxModal_primaryInfo"><?= $ci->lang('add new') ?> <i
												class="fa fa-plus"></i></button>

										<div class="table-responsive">
											<table id="prescription_table"
												   class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
													<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1;
												foreach ($prescriptions as $prescription) : ?>
													<tr id="<?= $prescription['id'] ?>">
														<td><?= $i ?></td>
														<td><?= $prescription['name'] ?></td>
														<td>
															<div class="g-2">
																<a href="javascript:editPrescription('<?= $prescription['id'] ?>')"
																   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-edit fs-14"></span></a>
																<a href="javascript:delete_via_alert('<?= $prescription['id'] ?>', '<?= base_url() ?>admin/delete_prescription_sample', 'prescription_table', null, true)"
																   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa fa-trash fs-14"></span></a>
															</div>
														</td>
													</tr>
													<?php $i++;
												endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--  rx contents end	-->

									<!--  rx insert Modal start	-->
									<div class="modal fade effect-scale" tabindex="-1" id="rxModal_primaryInfo"
										 role="dialog">

										<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

											<div class="modal-content">

												<div class="modal-header">

													<h5 class="modal-title">
														<?= $ci->lang('insert prescription') ?>
													</h5>
													<button class="btn-close" data-bs-dismiss="modal"
															aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">


													<form id="prescriptions_setMedicines_primaryInfo">
														<div class="row">

															<div class="row">

																<div class="col-sm-12 col-md-4">
																	<div class="form-group">
																		<label class="form-label">
																			<?= $ci->lang('name') ?> <span
																				class="text-red"></span>
																		</label>
																		<input type="text" class="form-control"
																			   autocomplete="off" name="name"
																			   placeholder="<?= $ci->lang('name') ?>"/>
																	</div>
																</div>

															</div>

															<div class="customHr2"></div>


															<div class="col-md-12">

																<!-- row 1 -->
																<div class="row">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine1_primaryInfo"
																					name="medicine_1"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx1_primaryInfo', 'medicineUnite_Rx1_primaryInfo', 'set_medicineUsage1_primaryInfo', 'set_medicineDay1_primaryInfo', 'set_medicineTime1_primaryInfo', 'set_medicineAmount1_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_1"
																				   id="medicineDoze_Rx1_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx1_primaryInfo"
																					name="unit_1"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage1_primaryInfo"
																					name="usageType_1"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_1"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay1_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_1"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime1_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_1"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount1_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusBtn1_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick=" plusBtn('setMedicien_row2_primaryInfo', 'plusBtn1_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<!-- <div  style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button" onclick="removeBtn('', 'plusbtn1')">
                        <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                      </button>
                    </div> -->
																		</div>


																	</div>

																</div>
																<!-- row 1 -->

																<!-- row 2 -->
																<div class="row" id="setMedicien_row2_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine2_primaryInfo"
																					name="medicine_2"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx2_primaryInfo', 'medicineUnite_Rx2_primaryInfo', 'set_medicineUsage2_primaryInfo', 'set_medicineDay2_primaryInfo', 'set_medicineTime2_primaryInfo', 'set_medicineAmount2_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_2"
																				   id="medicineDoze_Rx2_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx2_primaryInfo"
																					name="unit_2"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage2_primaryInfo"
																					name="usageType_2"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_2"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay2_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_2"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime2_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_2"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount2_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2"
																		 id="PRBtns2_primaryInfo">

																		<div class="plusRemovBtns">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn2_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row3_primaryInfo', 'plusbtn2_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row2_primaryInfo', 'plusBtn1_primaryInfo'), clearInput('set_medicine2_primaryInfo', 'medicineDoze_Rx2_primaryInfo', 'medicineUnite_Rx2_primaryInfo', 'set_medicineUsage2_primaryInfo', 'set_medicineDay2_primaryInfo', 'set_medicineTime2_primaryInfo', 'set_medicineAmount2_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 2 -->

																<!-- row 3 -->
																<div class="row" id="setMedicien_row3_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine3_primaryInfo"
																					name="medicine_3"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx3_primaryInfo', 'medicineUnite_Rx3_primaryInfo', 'set_medicineUsage3_primaryInfo', 'set_medicineDay3_primaryInfo', 'set_medicineTime3_primaryInfo', 'set_medicineAmount3_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_3"
																				   id="medicineDoze_Rx3_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx3_primaryInfo"
																					name="unit_3"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage3_primaryInfo"
																					name="usageType_3"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_3"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay3_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_3"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime3_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_3"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount3_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2"
																		 id="PRBtns3_primaryInfo">

																		<div class="plusRemovBtns">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn3_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row4_primaryInfo', 'plusbtn3_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row3_primaryInfo', 'plusbtn2_primaryInfo'),clearInput('set_medicine3_primaryInfo', 'medicineDoze_Rx3_primaryInfo', 'medicineUnite_Rx3_primaryInfo', 'set_medicineUsage3_primaryInfo', 'set_medicineDay3_primaryInfo', 'set_medicineTime3_primaryInfo', 'set_medicineAmount3_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 3 -->

																<!-- row 4 -->
																<div class="row" id="setMedicien_row4_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine4_primaryInfo"
																					name="medicine_4"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx4_primaryInfo', 'medicineUnite_Rx4_primaryInfo', 'set_medicineUsage4_primaryInfo', 'set_medicineDay4_primaryInfo', 'set_medicineTime4_primaryInfo', 'set_medicineAmount4_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_4"
																				   id="medicineDoze_Rx4_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx4_primaryInfo"
																					name="unit_4"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage4_primaryInfo"
																					name="usageType_4"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_4"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay4_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_4"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime4_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_4"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount4_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns4_primaryInfo">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn4_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row5_primaryInfo', 'plusbtn4_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?>
																						<_primaryInfo
																						/div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row4_primaryInfo', 'plusbtn3_primaryInfo'), clearInput('set_medicine4_primaryInfo', 'medicineDoze_Rx4_primaryInfo', 'medicineUnite_Rx4_primaryInfo', 'set_medicineUsage4_primaryInfo', 'set_medicineDay4_primaryInfo', 'set_medicineTime4_primaryInfo', 'set_medicineAmount4_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 4 -->


																<!-- row 5 -->
																<div class="row" id="setMedicien_row5_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?> <span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine5_primaryInfo"
																					name="medicine_5"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx5_primaryInfo', 'medicineUnite_Rx5_primaryInfo', 'set_medicineUsage5_primaryInfo', 'set_medicineDay5_primaryInfo', 'set_medicineTime5_primaryInfo', 'set_medicineAmount5_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_5"
																				   id="medicineDoze_Rx5_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx5_primaryInfo"
																					name="unit_5"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage5_primaryInfo"
																					name="usageType_5"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_5"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay5_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_5"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime5_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_5"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount5_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns5_primaryInfo" type="button">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn5_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row6_primaryInfo','plusbtn5_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row5_primaryInfo', 'plusbtn4_primaryInfo'), clearInput('set_medicine5_primaryInfo', 'medicineDoze_Rx5_primaryInfo', 'medicineUnite_Rx5_primaryInfo', 'set_medicineUsage5_primaryInfo', 'set_medicineDay5_primaryInfo', 'set_medicineTime5_primaryInfo', 'set_medicineAmount5_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 5 -->

																<!-- row 6 -->
																<div class="row" id="setMedicien_row6_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine6_primaryInfo"
																					name="medicine_6"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx6_primaryInfo', 'medicineUnite_Rx6_primaryInfo', 'set_medicineUsage6_primaryInfo', 'set_medicineDay6_primaryInfo', 'set_medicineTime6_primaryInfo', 'set_medicineAmount6_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_6"
																				   id="medicineDoze_Rx6_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx6_primaryInfo"
																					name="unit_6"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage6_primaryInfo"
																					name="usageType_6"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_6"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay6_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_6"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime6_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_6"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount6_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns4_primaryInfo">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn6_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row7_primaryInfo', 'plusbtn6_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row6_primaryInfo', 'plusbtn5_primaryInfo'), clearInput('set_medicine6_primaryInfo', 'medicineDoze_Rx6_primaryInfo', 'medicineUnite_Rx6_primaryInfo', 'set_medicineUsage6_primaryInfo', 'set_medicineDay6_primaryInfo', 'set_medicineTime6_primaryInfo', 'set_medicineAmount6_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 6 -->

																<!-- row 7 -->
																<div class="row" id="setMedicien_row7_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine7_primaryInfo"
																					name="medicine_7"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx7_primaryInfo', 'medicineUnite_Rx7_primaryInfo', 'set_medicineUsage7_primaryInfo', 'set_medicineDay7_primaryInfo', 'set_medicineTime7_primaryInfo', 'set_medicineAmount7_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_7"
																				   id="medicineDoze_Rx7_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx7_primaryInfo"
																					name="unit_7"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage7_primaryInfo"
																					name="usageType_7"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_7"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay7_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_7"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime7_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_7"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount7_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn7_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row8_primaryInfo', 'plusbtn7_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row7_primaryInfo', 'plusbtn6_primaryInfo'), clearInput('set_medicine7_primaryInfo', 'medicineDoze_Rx7_primaryInfo', 'medicineUnite_Rx7_primaryInfo', 'set_medicineUsage7_primaryInfo', 'set_medicineDay7_primaryInfo', 'set_medicineTime7_primaryInfo', 'set_medicineAmount7_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 7 -->

																<!-- row 8 -->
																<div class="row" id="setMedicien_row8_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine8_primaryInfo"
																					name="medicine_8"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx8_primaryInfo', 'medicineUnite_Rx8_primaryInfo', 'set_medicineUsage8_primaryInfo', 'set_medicineDay8_primaryInfo', 'set_medicineTime8_primaryInfo', 'set_medicineAmount8_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_8"
																				   id="medicineDoze_Rx8_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx8_primaryInfo"
																					name="unit_8"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage8_primaryInfo"
																					name="usageType_8"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_8"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay8_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_8"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime8_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_8"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount8_primaryInfo">

																		</div>
																	</div>
																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns4_primaryInfo">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn8_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row9_primaryInfo', 'plusbtn8_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row8_primaryInfo', 'plusbtn7_primaryInfo'), clearInput('set_medicine8_primaryInfo', 'medicineDoze_Rx8_primaryInfo', 'medicineUnite_Rx8_primaryInfo', 'set_medicineUsage8_primaryInfo', 'set_medicineDay8_primaryInfo', 'set_medicineTime8_primaryInfo', 'set_medicineAmount8_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 8 -->

																<!-- row 9 -->
																<div class="row" id="setMedicien_row9_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine9_primaryInfo"
																					name="medicine_9"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx9_primaryInfo', 'medicineUnite_Rx9_primaryInfo', 'set_medicineUsage9_primaryInfo', 'set_medicineDay9_primaryInfo', 'set_medicineTime9_primaryInfo', 'set_medicineAmount9_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_9"
																				   id="medicineDoze_Rx9_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx9_primaryInfo"
																					name="unit_9"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage9_primaryInfo"
																					name="usageType_9"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">

																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_9"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay9_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_9"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime9_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_9"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount9_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns4_primaryInfo">
																			<div class=""
																				 style="text-align: center;margin-top:5%">
																				<button id="plusbtn9_primaryInfo"
																						class="icon-btn add-btn"
																						type="button"
																						onclick="plusBtn('setMedicien_row10_primaryInfo', 'plusbtn9_primaryInfo')">
																					<div class="add-icon"></div>
																					<div
																						class="btn-txt"><?= $ci->lang('add') ?></div>
																				</button>
																			</div>
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row9_primaryInfo', 'plusbtn8_primaryInfo'), clearInput('set_medicine9_primaryInfo', 'medicineDoze_Rx9_primaryInfo', 'medicineUnite_Rx9_primaryInfo', 'set_medicineUsage9_primaryInfo', 'set_medicineDay9_primaryInfo', 'set_medicineTime9_primaryInfo', 'set_medicineAmount9_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 9 -->

																<!-- row 10 -->
																<div class="row" id="setMedicien_row10_primaryInfo"
																	 style="display: none;">

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Name') ?><span
																					class="text-red">*</span>
																			</label>
																			<!-- this is an important select tag remember it -->
																			<select id="set_medicine10_primaryInfo"
																					name="medicine_10"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>"
																					onchange="getMedicienInfo(this.value,'medicineDoze_Rx10_primaryInfo', 'medicineUnite_Rx10_primaryInfo', 'set_medicineUsage10_primaryInfo', 'set_medicineDay10_primaryInfo', 'set_medicineTime10_primaryInfo', 'set_medicineAmount10_primaryInfo')">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($medicines as $medicine) : ?>
																					<option
																						value="<?= $medicine['id'] ?>">
																						<?= $medicine['type'] ?>.
																						<?= $medicine['name'] ?>
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Doze') ?> <span
																					class="text-red">*</span>
																			</label>

																			<input type="number" name="doze_10"
																				   id="medicineDoze_Rx10_primaryInfo"
																				   class="form-control arrowLessInput">
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Unit') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="medicineUnite_Rx10_primaryInfo"
																					name="unit_10"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">
																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
																					<option value="<?= $unit ?>">
																						<?= $unit ?>
																					</option>
																				<?php endforeach; ?>
																			</select>

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Medicine Usage') ?> <span
																					class="text-red">*</span>
																			</label>

																			<select id="set_medicineUsage10_primaryInfo"
																					name="usageType_10"
																					class="form-control select2-show-search form-select"
																					data-placeholder="<?= $ci->lang('select') ?>">


																				<option
																					label="<?= $ci->lang('select') ?>"></option>
																				<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
																					<option value="<?= $type ?>">
																						<?= $type ?>
																					</option>
																				<?php endforeach; ?>


																			</select>
																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Day') ?>
																			</label>

																			<input type="number" name="day_10"
																				   class="form-control arrowLessInput"
																				   id="set_medicineDay10_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('Time') ?>
																			</label>

																			<input type="number" name="time_10"
																				   class="form-control arrowLessInput"
																				   id="set_medicineTime10_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-1">
																		<div class="form-group">
																			<label class="form-label">
																				<?= $ci->lang('amount') ?>
																			</label>

																			<input type="number" name="amount_10"
																				   class="form-control arrowLessInput"
																				   id="set_medicineAmount10_primaryInfo">

																		</div>
																	</div>

																	<div class="col-sm-12 col-md-2">

																		<div class="plusRemovBtns"
																			 id="PRBtns4_primaryInfo">
																			<!-- <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn10" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn10')">
                        <div class="add-icon"></div>
                        <div class="btn-txt"><?= $ci->lang('add') ?></div>
                      </button>
                    </div> -->
																			<div class=""
																				 style="text-align: center; margin-top: 8px;">
																				<button class="icon-btn add-btn"
																						type="button"
																						onclick="removeBtn('setMedicien_row10_primaryInfo', 'plusbtn9_primaryInfo'), clearInput('set_medicine10_primaryInfo', 'medicineDoze_Rx10_primaryInfo', 'medicineUnite_Rx10_primaryInfo', 'set_medicineUsage10_primaryInfo', 'set_medicineDay10_primaryInfo', 'set_medicineTime10_primaryInfo', 'set_medicineAmount10_primaryInfo')">
																					<div
																						class="btn-txt"><?= $ci->lang('remove') ?></div>
																				</button>
																			</div>
																		</div>


																	</div>

																</div>
																<!-- row 10 -->


															</div>
														</div>
													</form>


												</div>

												<div class="modal-footer">
													<button class="btn btn-secondary" data-bs-dismiss="modal">
														<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
													</button>
													<button class="btn btn-primary"
															onclick="xhrSubmitMultiTable('prescriptions_setMedicines_primaryInfo', '<?= base_url() ?>admin/insert_prescription_sample', 'prescription_table', 'rxModal_primaryInfo')">
														<?= $ci->lang('save') ?><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!--  rx insert Modal end	-->

									<!-- tab10 rx tab edn -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	function edit_service(id) {
		$.ajax({
			url: "<?= base_url('admin/single_service') ?>",
			type: 'POST',
			data: {slug: id},
			success: function (response) {
				const result = JSON.parse(response);
				if (result.type === 'success') {
					const {slug, price, name, department, processes} = result.content;

					// Populate form fields
					$('#slug_services').val(slug);
					$('#price').val(price);
					$('#nameOld').val(name);
					$('#name').val(name);
					$('#department').val(department).trigger('change');

					// Reset dynamic table
					const tableBody = document.querySelector("#edit_percentageTable tbody");
					tableBody.innerHTML = "";

					// Reset row count (IMPORTANT)
					window.editRowCount = 0;

					// Add rows
					if (Array.isArray(processes)) {
						processes.forEach((process, index) => {
							window.editRowCount++; // track dynamically

							const row = document.createElement("tr");
							row.setAttribute("draggable", "true");

							row.innerHTML = `
							<td>${editRowCount}</td>
							<td><input type="text" class="form-control" name="process_name[]" value="${process.name}" placeholder="<?= $ci->lang('name') ?>"></td>
							<td><input type="number" class="form-control" name="percentage[]" value="${process.percentage}" placeholder="<?= $ci->lang('percentage') ?>"></td>
							<td><button class="btn btn-danger deleteRow"><?= $ci->lang('delete') ?></button></td>
						`;

							row.querySelector(".deleteRow").addEventListener("click", () => {
								row.remove();
								updateEditRowNumbers();
							});

							if (typeof addEditDragAndDropEvents === 'function') {
								addEditDragAndDropEvents(row);
							}

							tableBody.appendChild(row);
						});

						updateEditRowNumbers();
					}

					$('#editServicesModal').modal('toggle');
				} else {
					$.growl.error1({
						title: result.alert?.title || 'Error',
						message: result.alert?.text || '<?= $ci->lang('problem') ?>'
					});
				}
			}
		});
	}

	function updateEditRowNumbers() {
		const rows = document.querySelectorAll("#edit_percentageTable tbody tr");
		rows.forEach((row, index) => {
			row.querySelector("td:first-child").textContent = index + 1;
		});
		window.editRowCount = rows.length; // keep it in sync
	}

	function addEditDragAndDropEvents(row) {
		row.addEventListener("dragstart", function () {
			editDraggedRow = row;
			setTimeout(() => (row.style.display = "none"), 0);
		});

		row.addEventListener("dragend", function () {
			setTimeout(() => (row.style.display = "table-row"), 0);
			editDraggedRow = null;
		});

		row.addEventListener("dragover", function (e) {
			e.preventDefault();
			const draggedOverRow = e.target.closest("tr");
			if (draggedOverRow && draggedOverRow !== editDraggedRow) {
				const bounding = draggedOverRow.getBoundingClientRect();
				const offset = e.clientY - bounding.top;
				if (offset > bounding.height / 2) {
					draggedOverRow.after(editDraggedRow);
				} else {
					draggedOverRow.before(editDraggedRow);
				}
			}
		});

		row.addEventListener("drop", function (e) {
			e.preventDefault();
			updateEditRowNumbers();
		});
	}

</script>

<script>
	function edit_medicine(id) {
		$.ajax({
			url: "<?= base_url('admin/single_medicine') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;
				console.log(medicienDatas);
				$('#medicineType_edit').val(medicienDatas.type).trigger('change');
				$('#medicineName_edit').val(medicienDatas.name);
				$('#medicineUnite_edit').val(medicienDatas.unit).trigger('change');
				$('#medicineDoze_edit').val(medicienDatas.doze);
				$('#medicineUsage_edit').val(medicienDatas.usageType).trigger('change');
				$('#medicineDay_edit').val(medicienDatas.day);
				$('#medicineTime_edit').val(medicienDatas.times);
				$('#medicineAmount_edit').val(medicienDatas.amount);
				$('#slug_medicine').val(medicienDatas.slug);
			}
		})

		$(`#Medicine_edit`).modal('toggle');
	}
</script>

<script>
	function edit_diagnose(id) {
		$.ajax({
			url: "<?= base_url('admin/single_diagnose') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_diagnose').val(result['content']['slug']);
					$('#nameOld_diagnose').val(result['content']['name']);
					$('#name_diagnose').val(result['content']['name']);
					$('#updateDiagnoseModal').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		})
	}
</script>
<!-- TODO: tab9 the edit function -->
<script>
	function edit_categories(id) {
		$.ajax({
			url: "<?= base_url('admin/single_categories') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_category').val(result['content']['slug']);
					$('#old_category_name').val(result['content']['name']);
					$('#update_category_name').val(result['content']['name']);
					$('#categories_type_data_update').val(result['content']['type']).trigger('change');
					$('#categories_update').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		})
	}
</script>

<!-- TODO: tab8 the edit function -->
<script>
	function edit_restorative(id) {
		$.ajax({
			url: "<?= base_url('admin/single_teeth_basic_info') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_restorative').val(result['content']['slug']);
					$('#restorative_update_name').val(result['content']['name']);
					$('#restorative_update_nameOld').val(result['content']['name']);
					$('#restorative_data_update').val(result['content']['categories_id']).trigger('change');
					$('#restorativeData_update').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		})
	}
</script>


<script>
	function edit_Endo(id) {
		$.ajax({
			url: "<?= base_url('admin/single_teeth_basic_info') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_endo').val(result['content']['slug']);
					$('#endo_update_name').val(result['content']['name']);
					$('#endo_update_nameOld').val(result['content']['name']);
					$('#endo_data_update').val(result['content']['categories_id']).trigger('change');
					$('#EndoData_update').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		})
	}
</script>

<?php $ci->render("primary_info/primary_info_js.php"); ?>
