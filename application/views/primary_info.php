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
                    <li><a href="#services_tab" class="active" data-bs-toggle="tab" aria-selected="true" role="tab"><?= $ci->lang('services') ?></a></li>
                    <li><a href="#medicine_tab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('medicines') ?></a></li>
                    <li><a href="#diagnose_tab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('diagnoses') ?></a></li>
                    <li><a href="#restorativeTab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('restorative') ?></a></li>
                    <li><a href="#EndoTab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('Endodantic') ?></a></li>
                    <li><a href="#ProsthodonticsTab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('Prosthodontics') ?></a></li>
                    <li><a href="#categoriesTab" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('categories') ?></a></li>
                  </ul>
                </div>
              </div>
              <div class="panel-body tabs-menu-body border-0 pt-0">

                <div class="tab-content">

                  <!-- TODO:tab5 services start-->
                  <div class="tab-pane active show" id="services_tab" role="tabpanel">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertServicesModal"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>


                    <div class="table-responsive">
                      <table id="services_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_service('<?= $service['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $service['id'] ?>', '<?= base_url() ?>admin/delete_service', 'services_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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
					<div class="modal fade effect-scale" id="insertServicesModal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title"><?= $ci->lang('insert service') ?></h5>
									<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">

									<form id="insert">
										<div class="row">
											<div class="col-sm-12 col-md-4">
												<div class="form-group">
													<label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
													<input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
												</div>
											</div>

											<div class="col-sm-12 col-md-4">
												<div class="form-group">
													<label class="form-label"><?= $ci->lang('department') ?> <span class="text-red">*</span></label>
													<select name="department" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>
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
													<label class="form-label"><?= $ci->lang('price') ?> <span class="text-red">*</span></label>
													<input type="number" name="price" class="form-control" placeholder="<?= $ci->lang('price') ?>">
												</div>
											</div>

											<div class="col-sm-12 col-md-2">
												<button class="btn btn-danger" type="button" style="margin-top: 36px" id="addRowButton">
													add row
												</button>
											</div>

										</div>
									</form>

									<div class="percentageRow">
										<table id="percentageTable" class="table">
											<thead>
											<tr>
												<th>Number of Row</th>
												<th>Name</th>
												<th>Percentage</th>
												<th>Action</th>
											</tr>
											</thead>
											<tbody>
											<!-- Rows will be dynamically added here -->
											</tbody>
										</table>
									</div>

								</div>
								<div class="modal-footer">
									<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i class="fa fa-close"></i></button>
									<button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert', '<?= base_url() ?>admin/insert_service', 'services_table', 'insertServicesModal')"><?= $ci->lang('save') ?> <i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
					</div>
                  <!-- Modal End -->


                  <!-- Modal edit -->
                  <div class="modal fade effect-scale" id="editServicesModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title"><?= $ci->lang('edit service') ?></h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="update">
                            <div class="row">
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="hidden" name="slug" id="slug_services">
                                  <input type="text" name="name" class="form-control" id="name" placeholder="<?= $ci->lang('name') ?>">
                                </div>
                              </div>


                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('department') ?> <span class="text-red">*</span></label>
                                  <select name="department" id="department" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>
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
                                  <label class="form-label"><?= $ci->lang('price') ?> <span class="text-red">*</span></label>
                                  <input type="hidden" name="nameOld" id="nameOld">
                                  <input type="number" name="price" class="form-control" id="price" placeholder="<?= $ci->lang('price') ?>">
                                </div>
                              </div>
                            </div>
                          </form>

                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?><i class="fa fa-close"></i></button>
                          <button class="btn btn-primary" onclick="xhrUpdate('update', '<?= base_url() ?>admin/update_service', 'editServicesModal', false, 'services_table')"><?= $ci->lang('update') ?> <i class="fa fa-edit"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal End -->

                  <!-- End services Modals -->

                  <!-- TODO: tab6 medicine start  -->
                  <div class="tab-pane" id="medicine_tab" role="tabpanel">

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Medicine">
                      <?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
                    </button>


                    <div class="table-responsive">
                      <table id="medicine_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_medicine('<?= $medicine['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('edit') ?>"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $medicine['id'] ?>', '<?= base_url() ?>admin/delete_medicine', 'medicine_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span class="fa fa-trash fs-14"></span></a>
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
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                                        <?= $ci->lang('Medicine Type') ?> <span class="text-red">*</span>
                                      </label>
                                      <!-- this is an important select tag remember it -->
                                      <select id="medicineType" name="type" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                        <option label="<?= $ci->lang('select') ?>"></option>
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
                                        <?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
                                      </label>

                                      <input type="text" class="form-control" id="medicineName" name="name">

                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
                                      </label>

                                      <input type="number" name="doze" id="medicineDoze" class="form-control">
                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                      </label>

                                      <select id="medicineUnite" name="unit" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                        <option label="<?= $ci->lang('select') ?>"></option>
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
                                        <?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
                                      </label>

                                      <select id="medicineUsage" name="usageType" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">

                                        <option label="<?= $ci->lang('select') ?>"></option>
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

                                      <input type="number" class="form-control" id="medicineDay" value="1" name="day">

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

                                      <input type="number" class="form-control" id="medicineTime" value="3" name="times">

                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('amount') ?>
                                      </label>

                                      <input type="number" class="form-control" id="medicineAmount" name="amount">

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
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('medicine_insert', '<?= base_url() ?>admin/insert_medicine', 'medicine_table', 'Medicine')">
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
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                                        <?= $ci->lang('Medicine Type') ?> <span class="text-red">*</span>
                                      </label>
                                      <!-- this is an important select tag remember it -->
                                      <select id="medicineType_edit" name="type" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                        <option label="<?= $ci->lang('select') ?>"></option>
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
                                        <?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
                                      </label>
                                      <input type="hidden" name="slug" id="slug_medicine">
                                      <input type="text" class="form-control" id="medicineName_edit" name="name">

                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
                                      </label>

                                      <input type="number" name="doze" id="medicineDoze_edit" class="form-control">
                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                      </label>

                                      <select id="medicineUnite_edit" name="unit" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                        <option label="<?= $ci->lang('select') ?>"></option>
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
                                        <?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
                                      </label>

                                      <select id="medicineUsage_edit" name="usageType" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">

                                        <option label="<?= $ci->lang('select') ?>"></option>
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

                                      <input type="number" class="form-control" id="medicineDay_edit" value="1" name="day">

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

                                      <input type="number" class="form-control" id="medicineTime_edit" value="3" name="times">

                                    </div>
                                  </div>

                                  <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                      <label class="form-label">
                                        <?= $ci->lang('amount') ?>
                                      </label>

                                      <input type="number" class="form-control" id="medicineAmount_edit" name="amount">

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
                          <button class="btn btn-primary" onclick="xhrUpdate('medicine_edit_form', '<?= base_url() ?>admin/update_medicine', 'Medicine_edit', false, 'medicine_table')">
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertDiagnoseModal"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>


                    <div class="table-responsive">
                      <table id="diagnose_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_diagnose('<?= $diagnose['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $diagnose['id'] ?>', '<?= base_url() ?>admin/delete_diagnose', 'diagnose_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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
                  <div class="modal fade effect-scale" id="insertDiagnoseModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title"><?= $ci->lang('insert diagnose') ?></h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="insert_diagnose">
                            <div class="row">
                              <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                                </div>
                              </div>
                            </div>
                          </form>

                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i class="fa fa-close"></i></button>
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert_diagnose', '<?= base_url() ?>admin/insert_diagnose', 'diagnose_table', 'insertDiagnoseModal')"><?= $ci->lang('save') ?> <i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal End -->


                  <!-- Modal Update -->
                  <div class="modal fade effect-scale" id="updateDiagnoseModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title"><?= $ci->lang('update diagnose') ?></h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="update_diagnose">
                            <div class="row">
                              <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="hidden" name="nameOld" id="nameOld_diagnose">
                                  <input type="hidden" name="slug" id="slug_diagnose">
                                  <input type="text" name="name" class="form-control" id="name_diagnose" placeholder="<?= $ci->lang('name') ?>">
                                </div>
                              </div>
                            </div>
                          </form>

                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?><i class="fa fa-close"></i></button>
                          <button class="btn btn-primary" onclick="xhrUpdate('update_diagnose', '<?= base_url() ?>admin/update_diagnose', 'updateDiagnoseModal', false, 'diagnose_table')"><?= $ci->lang('update') ?><i class="fa fa-edit"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal End -->

                  <!-- End diagnose Modals -->

                  <!-- TODO: tab8 restorative start -->
                  <div class="tab-pane" id="restorativeTab" role="tabpanel">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#restorativeData_insert"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>


                    <div class="table-responsive">
                      <table id="restorative_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_restorative('<?= $restorative_basic_information['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $restorative_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'restorative_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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
                  <div class="modal fade effect-scale" tabindex="-1" id="restorativeData_insert" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('insert restorative data') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                                    <?= $ci->lang('choose the data type') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="restorative_data" name="categories_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>
                                    <?php foreach ($categories_teeth as $category) : ?>
                                      <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
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
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert_restorative', '<?= base_url() ?>admin/insert_teeth_info', 'restorative_table', 'restorativeData_insert')">
                            <?= $ci->lang('save') ?><i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- restorative insert Modals end -->



                  <!-- restorative update Modals start -->
                  <div class="modal fade effect-scale" tabindex="-1" id="restorativeData_update" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('update restorative data') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="update_restorative">
                            <div class="row">
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose the data type') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="restorative_data_update" name="categories_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>

                                    <option value="1" label="<?= $ci->lang('Caries Depth') ?>"> <?= $ci->lang('Caries Depth') ?> </option>
                                    <option value="2" label="<?= $ci->lang('base or liner material') ?>"> <?= $ci->lang('base or liner material') ?> </option>
                                    <option value="3" label="<?= $ci->lang('restorative material') ?>"> <?= $ci->lang('restorative material') ?> </option>
                                    <option value="4" label="<?= $ci->lang('composite brand') ?>"> <?= $ci->lang('composite brand') ?> </option>
                                    <option value="5" label="<?= $ci->lang('amalgam brand') ?>"> <?= $ci->lang('amalgam brand') ?> </option>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input id="restorative_update_name" type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                                  <input id="restorative_update_nameOld" type="hidden" name="nameOld" class="form-control">
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
                          <button class="btn btn-primary" onclick="xhrUpdate('update_restorative', '<?= base_url() ?>admin/update_basic_information_teeth', 'restorativeData_update', false, 'restorative_table')">
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#EndoData_insert"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>


                    <div class="table-responsive">
                      <table id="endo_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_Endo('<?= $Endo_basic_information['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $Endo_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'endo_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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
                  <div class="modal fade effect-scale" tabindex="-1" id="EndoData_insert" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('insert Endo data') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="insert_Endo">

                            <div class="row">
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose the data type') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="Endo_data" name="categories_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>
                                    <?php foreach ($categories_teeth as $category) : ?>
                                      <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
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
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert_Endo', '<?= base_url() ?>admin/insert_teeth_info', 'endo_table', 'EndoData_insert')">
                            <?= $ci->lang('save') ?><i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>



                  <!-- Endo update start -->
                  <div class="modal fade effect-scale" tabindex="-1" id="EndoData_update" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('Update Endo data') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="update_Endo">

                            <div class="row">
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose the data type') ?> <span cla ss="text-red">*</span>
                                  </label>

                                  <select id="endo_data_update" name="categories_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>
                                    <?php foreach ($categories_teeth as $category) : ?>
                                      <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" id="endo_update_name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                                </div>
                              </div>

                              <input type="hidden" id="slug_endo" name="slug">
                              <input type="hidden" name="nameOld" id="endo_update_nameOld">

                            </div>
                          </form>
                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
                          </button>
                          <button class="btn btn-primary" onclick="xhrUpdate('update_Endo', '<?= base_url() ?>admin/update_basic_information_teeth', 'EndoData_update', false, 'endo_table')">
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ProsthodonticsData_insert"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>


                    <div class="table-responsive">
                      <table id="Prosthodontics_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_Prosthodontics('<?= $Prosthodontics_basic_information['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $Prosthodontics_basic_information['id'] ?>', '<?= base_url() ?>admin/delete_basic_teeth_info', 'Prosthodontics_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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
                  <div class="modal fade effect-scale" tabindex="-1" id="ProsthodonticsData_insert" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('insert Prosthodontics data') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="insert_Prosthodontics">

                            <div class="row">
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose the data type') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="Prosthodontics_data" name="categories_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>
                                    <?php foreach ($categories_teeth as $category) : ?>
                                      <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                                </div>
                              </div>

                              <input type="hidden" name="department" value="Prosthodontics">

                            </div>
                          </form>
                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= $ci->lang('cancel') ?><i class="fa fa-close"></i>
                          </button>
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert_Prosthodontics', '<?= base_url() ?>admin/insert_teeth_info', 'Prosthodontics_table', 'ProsthodonticsData_insert')">
                            <?= $ci->lang('save') ?><i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>


                  <!-- tab8 restorative Modals end -->


                  <!-- TODO: tab9 categories start -->
                  <div class="tab-pane" id="categoriesTab" role="tabpanel">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categories_insert"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>

                    <div class="table-responsive">
                      <table id="categories_table" class="table table-bordered text-nowrap key-buttons border-bottom">
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
                                  <a href="javascript:edit_categories('<?= $category['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('<?= $category['id'] ?>', '<?= base_url() ?>admin/delete_categories', 'categories_table', null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
                                </div>
                              </td>
                            </tr>
                          <?php $i++;
                          endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- tab8 categories end -->


                  <!-- TODO: tab9 categories Modals start -->


                  <!-- categories insert Modals start -->
                  <div class="modal fade effect-scale" tabindex="-1" id="categories_insert" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('insert categories') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="insert_categories">
                            <div class="row">

                              <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose a category') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="categories_type_data" name="type" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>

                                    <option value="files" label="<?= $ci->lang('files') ?>"> <?= $ci->lang('files') ?> </option>
                                    <option value="teeth" label="<?= $ci->lang('teeth') ?>"> <?= $ci->lang('teeth') ?> </option>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
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
                          <button class="btn btn-primary" onclick="xhrSubmitMultiTable('insert_categories', '<?= base_url() ?>admin/insert_categories', 'categories_table', 'categories_insert')">
                            <?= $ci->lang('save') ?><i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- categories insert Modals end -->



                  <!-- categories update Modals start -->
                  <div class="modal fade effect-scale" tabindex="-1" id="categories_update" role="dialog">

                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

                      <div class="modal-content">

                        <div class="modal-header">

                          <h5 class="modal-title">
                            <?= $ci->lang('insert categories') ?>
                          </h5>
                          <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="update_categories">
                            <input type="hidden" name="slug" id="slug_category" class="form-control">

                            <div class="row">

                              <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">
                                    <?= $ci->lang('choose a category') ?> <span class="text-red">*</span>
                                  </label>

                                  <select id="categories_type_data_update" name="type" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                                    <option label="<?= $ci->lang('select') ?>"></option>

                                    <option value="files" label="<?= $ci->lang('files') ?>"> <?= $ci->lang('files') ?> </option>
                                    <option value="teeth" label="<?= $ci->lang('teeth') ?>"> <?= $ci->lang('teeth') ?> </option>
                                  </select>

                                </div>
                              </div>

                              <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                  <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                                  <input type="hidden" name="nameOld" id="old_category_name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                                  <input type="text" name="name" id="update_category_name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
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
                          <button class="btn btn-primary" onclick="xhrUpdate('update_categories', '<?= base_url() ?>admin/update_categories', 'categories_update', false, 'categories_table')"><?= $ci->lang('update') ?><i class="fa fa-edit"></i></button>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- categories update Modals end -->


                  <!-- tab9 categories Modals end -->
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
      data: {
        slug: id
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          $('#slug_services').val(result['content']['slug']);
          $('#price').val(result['content']['price']);
          $('#nameOld').val(result['content']['name']);
          $('#name').val(result['content']['name']);
          $('#department').val(result['content']['department']).trigger('change');
          $('#editServicesModal').modal('toggle');
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
  function edit_medicine(id) {
    $.ajax({
      url: "<?= base_url('admin/single_medicine') ?>",
      type: 'POST',
      data: {
        slug: id
      },
      success: function(response) {
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
      success: function(response) {
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
      success: function(response) {
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
      success: function(response) {
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
      success: function(response) {
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

<?php $ci->render("primary_info/primary_info_js.php");  ?>
