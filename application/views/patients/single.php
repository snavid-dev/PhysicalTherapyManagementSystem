<?php $ci = get_instance(); ?>
<!-- Start::row-1 -->
<div class="row">
	<div class="col-xxl-2">
		<div class="card custom-card overflow-hidden">
			<div class="card-body border-bottom">
				<div class="d-sm-flex  main-profile-cover">
					<div class="flex-fill main-profile-info my-auto" style="display: inline-flex;">
						<h5 class="fw-semibold mb-1 "><?= $ci->mylibrary->get_patient_name($profile['name'], $profile['lname'], '', $profile['gender']) ?></h5>
						<p class="mb-1 text-muted" style="margin: 0 10px"><bdo dir="ltr">(<?= $profile['serial_id'] ?>
								)</bdo></p>
					</div>
				</div>
			</div>
			<div class="card-body p-0 main-profile-info">
				<div class="d-flex align-items-center justify-content-between w-100">
					<div class="py-3 border-end w-100 text-center">
						<p class="fw-bold fs-20  text-shadow mb-0" id="sum_fees"><?= $sum_dr ?></p>
						<p class="mb-0 fs-12 text-muted "><?= $ci->lang('fees') ?></p>
					</div>
					<div class="py-3 border-end w-100 text-center">
						<p class="fw-bold fs-20  text-shadow mb-0" id="sum_paid"><?= $sum_cr ?></p>
						<p class="mb-0 fs-12 text-muted "><?= $ci->lang('paid') ?></p>
					</div>
					<div class="py-3 w-100 text-center">
						<p class="fw-bold fs-20  text-shadow mb-0 <?= ($balance > 0) ? 'text-danger' : '' ?>"
						   id="balance"><?= $balance ?></p>
						<p class="mb-0 fs-12 text-muted "><?= $ci->lang('balance') ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="card custom-card">
			<div class="p-4 border-bottom border-block-end-dashed">
				<div>
					<p class="fs-15 mb-2 me-4 fw-semibold"><?= $ci->lang('contact information') ?> :</p>
				</div>

				<ul class="list-group">
					<li class="list-group-item border-0">
						<div class="text-muted" id="contact">
							<p class="mb-3">
                <span class="avatar avatar-sm avatar-rounded me-2 bg-warning-transparent rounded">
                  <i class="fa-solid fa-phone-volume fs-14"></i>
                </span>
								<bdo dir="ltr">(+93) <?= $profile['phone1'] ?></bdo>
							</p>
							<?php if ($profile['phone2'] != 0) : ?>
								<p class="mb-3">
                  <span class="avatar avatar-sm avatar-rounded me-2 bg-warning-transparent rounded">
                    <i class="fa-solid fa-phone-volume fs-14"></i>
                  </span>
									<bdo dir="ltr">(+93) <?= $profile['phone2'] ?></bdo>
								</p>
							<?php endif; ?>
							<div class="d-flex">
								<p class="mb-0">
                  <span class="avatar avatar-sm avatar-rounded me-2 bg-success-transparent rounded">
                    <i class="fa-solid fa-map-location-dot fs-14"></i>
                  </span>
								</p>
								<p class="mb-0">
									<?= $profile['address'] ?></p>

							</div>
						</div>
					</li>
				</ul>


			</div>
			<div class="p-4  border-bottom border-block-end-dashed">
				<p class="fs-15 mb-2 me-4 fw-semibold"><?= $ci->lang('personal info') ?> :</p>
				<ul class="list-group" id="info">


					<li class="list-group-item border-0">
						<div class="d-flex flex-wrap align-items-center">
							<div class="me-2 fw-semibold">
								<?= $ci->lang('age') ?> :
							</div>
							<span class="fs-12 text-muted"><?= $profile['age'] ?></span>
						</div>
					</li>

					<li class="list-group-item border-0">
						<div class="d-flex flex-wrap align-items-center">
							<div class="me-2 fw-semibold">
								<?= $ci->lang('reference doctor') ?> :
							</div>
							<span class="fs-12 text-muted"><?= $profile['doctor_name'] ?></span>
						</div>
					</li>
					<?php if ($profile['pains'] != '') : ?>
						<li class="list-group-item border-0">
							<div class="d-flex flex-wrap align-items-center">
								<div class="me-2 fw-semibold">
									<?= $ci->lang('medical history') ?> :
								</div>
								<span class="fs-12 text-muted"><?= $profile['pains'] ?></span>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($profile['other_pains'] != '') : ?>
						<li class="list-group-item border-0">
							<div class="d-flex flex-wrap align-items-center">
								<div class="me-2 fw-semibold">
									<?= $ci->lang('other diseases') ?> :
								</div>
								<span class="fs-12 text-muted"><?= $profile['other_pains'] ?></span>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($profile['remarks'] != '') : ?>
						<li class="list-group-item border-0">
							<div class="d-flex flex-wrap align-items-center">
								<div class="me-2 fw-semibold">
									<?= $ci->lang('desc') ?> :
								</div>
								<span class="fs-12 text-muted"><?= $profile['remarks'] ?></span>
							</div>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="p-4 border-bottom border-block-end-dashed">

				<button type="button" class="btn btn-primary-gradient btn-wave custom-btn" data-bs-toggle="modal"
						data-bs-target="#edit_patient" onclick="edit_profile()"
						style=" display: flex;align-items: center; width: 100%; justify-content: space-between;"><?= $ci->lang('edit') ?>
					<i class="las la-edit"></i>
				</button>

				<!-- edit Patient -->
				<div class="modal fade effect-scale" id="edit_patient" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?= $ci->lang('Edit Profile') ?></h5>
								<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="insertAccount">
									<div class="row">
										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<input type="hidden" name="slug" id="slug">
												<label class="form-label"><?= $ci->lang('name') ?> <span
														class="text-red">*</span></label>
												<input type="text" name="name" id="name" class="form-control"
													   placeholder="<?= $ci->lang('name') ?>">
											</div>
										</div>

										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('lname') ?> <span
														class="text-red">*</span></label>
												<input type="text" name="lname" id="lname" class="form-control"
													   placeholder="<?= $ci->lang('lname') ?>">
											</div>
										</div>

										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('age') ?> <span
														class="text-red">*</span></label>
												<input type="number" name="age" id="age" class="form-control"
													   placeholder="<?= $ci->lang('age') ?>">
											</div>
										</div>
										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('gender') ?> <span
														class="text-red">*</span></label>
												<select name="gender" id="gender" class="form-control form-select">
													<option label="<?= $ci->lang('select') ?>"></option>
													<option value="m"><?= $ci->lang('male') ?></option>
													<option value="f"><?= $ci->lang('female') ?></option>
												</select>
											</div>
										</div>

										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('phone') ?> <span
														class="text-red">*</span></label>
												<input type="text" name="phone1" id="phone1" class="form-control"
													   placeholder="<?= $ci->lang('phone') ?>">
											</div>
										</div>

										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('phone2') ?></label>
												<input type="text" name="phone2" id="phone2" class="form-control"
													   placeholder="<?= $ci->lang('phone2') ?>">
											</div>
										</div>


										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('medical history') ?></label>
												<select name="" class="form-control select2-show-search form-select"
														onchange="multiple_value()" id="pains"
														data-placeholder="<?= $ci->lang('select') ?>" multiple>
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($ci->dentist->diseases() as $pain) : ?>
														<option value="<?= $pain ?>"><?= $pain ?></option>
													<?php endforeach; ?>
												</select>
												<input type="hidden" name="pains" id="model_value">
											</div>
										</div>

										<div class="col-sm-12 col-md-3">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('reference doctor') ?></label>
												<select name="doctor_id"
														class="form-control select2-show-search form-select"
														id="doctor_id" data-placeholder="<?= $ci->lang('select') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($doctors as $doctor) : ?>
														<option
															value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('other diseases') ?></label>
												<textarea type="text" name="other_pains" id="other_pains"
														  class="form-control" rows="4"
														  placeholder="<?= $ci->lang('other diseases') ?>"></textarea>
											</div>
										</div>

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('address') ?></label>
												<textarea type="text" name="address" id="address" class="form-control"
														  rows="4" placeholder="<?= $ci->lang('address') ?>"></textarea>
											</div>
										</div>


										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<label class="form-label"><?= $ci->lang('desc') ?></label>
												<textarea type="text" name="remarks" id="remarks" class="form-control"
														  rows="4" placeholder="<?= $ci->lang('desc') ?>"></textarea>
											</div>
										</div>


									</div>
								</form>

							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
										class="fa fa-close"></i></button>
								<button class="btn btn-primary"
										onclick="Patient_profile_update('insertAccount', '<?= base_url() ?>admin/update_patient', 'edit_patient')"><?= $ci->lang('save') ?>
									<i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>
				</div>
				<!-- Edit Patient End -->

			</div>
		</div>
	</div>
	<div class="col-xxl-8">
		<div class="row">
			<div class="col-xl-12">
				<div class=" custom-card">
					<div class="card-body p-0">
						<div class="border-block-end-dashed  bg-white rounded-2 p-2">
							<div>
								<ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="myTab"
									role="tablist">
									<li class="nav-item rounded" role="presentation">
										<button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
												data-bs-target="#activity-tab-pane" type="button" role="tab"
												aria-controls="activity-tab-pane" aria-selected="true"><i
												class="las la-tooth me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('teeth') ?>
										</button>
									</li>

									<li class="nav-item rounded" role="presentation">
										<button class="nav-link" id="prosthesis-tab" data-bs-toggle="tab"
												data-bs-target="#prosthesis-tab-pane" type="button" role="tab"
												aria-controls="prosthesis-tab-pane" aria-selected="false"><i
												class="las la-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('turns') ?>
										</button>
									</li>

									<li class="nav-item rounded" role="presentation">
										<button class="nav-link" id="gallery-tab" data-bs-toggle="tab"
												data-bs-target="#gallery-tab-pane" type="button" role="tab"
												aria-controls="gallery-tab-pane" aria-selected="false"><i
												class="las la-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('turns') ?>
										</button>
									</li>
									<li class="nav-item rounded" role="presentation">
										<button class="nav-link" id="posts-tab" data-bs-toggle="tab"
												data-bs-target="#posts-tab-pane" type="button" role="tab"
												aria-controls="posts-tab-pane" aria-selected="false"><i
												class="las la-vial me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('laboratory') ?>
										</button>
									</li>
									<li class="nav-item rounded" role="presentation">
										<button class="nav-link" id="followers-tab" data-bs-toggle="tab"
												data-bs-target="#followers-tab-pane" type="button" role="tab"
												aria-controls="followers-tab-pane" aria-selected="false"><i
												class="las la-prescription me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('prescription') ?>
										</button>
									</li>
									<li class="nav-item rounded" role="presentation">
										<button class="nav-link" id="followers-tab" data-bs-toggle="tab"
												data-bs-target="#archive-tab-pane" type="button" role="tab"
												aria-controls="archive-tab-pane" aria-selected="false"><i
												class="lar la-folder-open me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('archive') ?>
										</button>
									</li>
								</ul>
							</div>
						</div>
						<div class="py-4">
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3"
									 id="activity-tab-pane" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">

									<!-- the teeth container -------------------------------------------------------------------------------------------start -->
									<div id="teethBackground" class="teethContainer containerAdult"
										 style="<?= ($profile['age'] >= 5) ? '' : 'display: none;' ?>" dir="ltr">
										<div id="upperTeethAdult" class="upperTeethXray">


											<div class="qi">

												<div class="tooth" onclick="insertTooth(this, tooth1)">
													<h6>18</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth2)">
													<h6>17</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/2upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth3)">
													<h6>16</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/3upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth4)">
													<h6>15</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/4upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth5)">
													<h6>14</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/5upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth6)">
													<h6>13</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/6upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth7)">
													<h6>12</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/7upup.png"
														alt="" class="toothimg"/>
												</div>


												<div class="tooth" onclick="insertTooth(this, tooth8)">
													<h6>11</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
														alt="" class="toothimg"/>
												</div>

											</div>

											<div id="vspace1" class="v-space"></div>

											<div class="qii">


												<div class="tooth" onclick="insertTooth(this, tooth9)">
													<h6>21</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/9upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth10)">
													<h6>22</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/10upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth11)">
													<h6>23</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/11upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth12)">
													<h6>24</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/12upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth13)">
													<h6>25</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/13upup.png"
														alt="" class="toothimg"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth14)">
													<h6>26</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/14upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth15)">
													<h6>27</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/15upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth16)">
													<h6>28</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/16upup.png"
														alt="" class="toothimg bigtooth"/>
												</div>


											</div>


										</div>


										<div class="v2"></div>
										<div class="vl"></div>


										<div id="downTeethAdult" class="downTeethXray">

											<div class="qi">
												<div class="tooth" onclick="insertTooth(this, tooth32)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/17do.png"
														alt="" class="toothimg bigtooth"/>
													<h6>48</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth31)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/18do.png"
														alt="" class="toothimg  bigtooth"/>

													<h6>47</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth30)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/19do.png"
														alt="" class="toothimg  bigtooth"/>

													<h6>46</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth29)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/20do.png"
														alt="" class="toothimg "/>

													<h6>45</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth28)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/21do.png"
														alt="" class="toothimg "/>

													<h6>44</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth27)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/22do.png"
														alt="" class="toothimg "/>

													<h6>43</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth26)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/23do.png"
														alt="" class="toothimg "/>

													<h6>42</h6>
												</div>


												<div class="tooth" onclick="insertTooth(this, tooth25)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/24do.png"
														alt="" class="toothimg "/>

													<h6>41</h6>
												</div>
											</div>


											<div id="vspace2" class="v-space"></div>


											<div class="qii">
												<div class="tooth" onclick="insertTooth(this, tooth24)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/25do.png"
														alt="" class="toothimg "/>

													<h6>31</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth23)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/26do.png"
														alt="" class="toothimg "/>
													<h6>32</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth22)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/27do.png"
														alt="" class="toothimg "/>
													<h6>33</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth21)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/28do.png"
														alt="" class="toothimg "/>
													<h6>34</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth20)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/29do.png"
														alt="" class="toothimg "/>
													<h6>35</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth19)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/30do.png"
														alt="" class="toothimg bigtooth"/>
													<h6>36</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth18)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/31do.png"
														alt="" class="toothimg bigtooth"/>
													<h6>37</h6>
												</div>

												<div class="tooth" onclick="insertTooth(this, tooth17)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/32do.png"
														alt="" class="toothimg bigtooth"/>
													<h6>38</h6>
												</div>
											</div>

										</div>
									</div>
									<!-- the teeth container -------------------------------------------------------------------------------------------start -->


									<!-- Modal 3 --------------------------------------------------------------------------------------------------baby -->
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
									<!-- Modal 3 --------------------------------------------------------------------------------------------------baby -->


									<!-- the teeth container babies -------------------------------------------------------------------------------------------start -->
									<div class="teethContainer containerBaby"
										 style="<?= ($profile['age'] <= 13) ? '' : 'display: none;' ?>" dir="ltr">
										<div class="upperTeethSimple">


											<div class="qi">

												<div class="babytooth" onclick="toggleModal(this, toothj)">
													<h6>E</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/j.png"
														alt="" class="babytoothimg"/>
												</div>


												<div class="babytooth" onclick="toggleModal(this, toothi)">
													<h6>D</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/i.png"
														alt="" class="babytoothimg"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothh)">
													<h6>C</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/h.png"
														alt="" class="babytoothimg"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothg)">
													<h6>B</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/g.png"
														alt="" class="babytoothimg"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothf)">
													<h6>A</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/f.png"
														alt="" class="babytoothimg"/>
												</div>


											</div>

											<div id="vspace3" class="v-space"></div>

											<div class="qii">

												<div class="babytooth" onclick="toggleModal(this, toothe)">
													<h6>A</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/e.png"
														alt="" class="babytoothimg"/>
												</div>


												<div class="babytooth" onclick="toggleModal(this, toothd)">
													<h6>B</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/d.png"
														alt="" class="babytoothimg"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothc)">
													<h6>C</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/c.png"
														alt="" class="babytoothimg bigtooth"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothb)">
													<h6>D</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/b.png"
														alt="" class="babytoothimg bigtooth"/>
												</div>

												<div class="babytooth" onclick="toggleModal(this, tootha)">
													<h6>E</h6>
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/a.png"
														alt="" class="babytoothimg bigtooth"/>
												</div>


											</div>


										</div>


										<div class="downTeethSimple">

											<div class="qi">

												<div class="babytooth" onclick="toggleModal(this, toothk)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/k.png"
														alt="" class="babytoothimg bigtooth"/>
													<h6>E</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothl)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/l.png"
														alt="" class="babytoothimg  bigtooth"/>

													<h6>D</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothm)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/m.png"
														alt="" class="babytoothimg  bigtooth"/>

													<h6>C</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothn)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/n.png"
														alt="" class="babytoothimg "/>

													<h6>B</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, tootho)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/o.png"
														alt="" class="babytoothimg "/>

													<h6>A</h6>
												</div>
											</div>


											<div id="vspace4" class="v-space"></div>


											<div class="qii">

												<div class="babytooth" onclick="toggleModal(this, toothp)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/p.png"
														alt="" class="babytoothimg "/>

													<h6>A</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, toothq)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/q.png"
														alt="" class="babytoothimg "/>

													<h6>B</h6>
												</div>


												<div class="babytooth" onclick="toggleModal(this, toothr)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/r.png"
														alt="" class="babytoothimg "/>

													<h6>C</h6>
												</div>


												<div class="babytooth" onclick="toggleModal(this, tooths)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/s.png"
														alt="" class="babytoothimg "/>

													<h6>D</h6>
												</div>

												<div class="babytooth" onclick="toggleModal(this, tootht)">
													<img
														src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/t.png"
														alt="" class="babytoothimg "/>
													<h6>E</h6>
												</div>

											</div>

										</div>


									</div>
									<!-- the teeth container babies -------------------------------------------------------------------------------------------start -->


									<div id="teeth_list" class="mt-4">

										<?php if (count($teeth) != 0) : ?>
											<!-- the table start _______________________________________________________________________________ -->
											<div class="table-responsive">
												<table class="table text-nowrap" id="teethTable">
													<thead class="tableHead">
													<tr>
														<th scope="col">#</th>
														<th scope="col"><?= $ci->lang('tooth name') ?></th>
														<th scope="col"><?= $ci->lang('tooth location') ?></th>
														<th scope="col"><?= $ci->lang('diagnose') ?></th>
														<th scope="col"><?= $ci->lang('services') ?></th>
														<th scope="col"><?= $ci->lang('pay amount') ?></th>
														<th scope="col"><?= $ci->lang('actions') ?></th>
													</tr>
													</thead>
													<tbody>
													<?php $i = 1;
													foreach ($teeth as $tooth) : ?>

														<tr id="<?= $tooth['id'] ?>" class="tableRow">
															<td scope="row"><?= $i ?></td>
															<td><?= $tooth['name'] ?></td>
															<td><?= $ci->dentist->find_location($tooth['location']) ?></td>
															<td><?= $tooth['diagnose'] ?></td>
															<td><?= $ci->services_name_multiple([$tooth['endo_services'], $tooth['restorative_services'], $tooth['prosthodontics_services']]) ?></td>
															<td><?= $tooth['price'] ?></td>
															<td>
																<div class="g-2">
																	<a href="javascript:updateTeeth('<?= $tooth['id'] ?>')"
																	   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																			class="fa-regular fa-pen-to-square fs-14"></span></a>
																	<a href="javascript:delete_via_alert('<?= $tooth['id'] ?>', '<?= base_url() ?>admin/delete_tooth', 'teethTable', update_balance)"
																	   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																			class="fa-solid fa-trash-can fs-14"></span></a>
																</div>
															</td>
														</tr>
														<?php $i++;
													endforeach; ?>
													</tbody>
												</table>
											</div>
											<!-- the table end _______________________________________________________________________________ -->
										<?php endif; ?>
									</div>

								</div>

								<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="prosthesis-tab-pane"
									 role="tabpanel" aria-labelledby="prosthesis-tab" tabindex="0">
									<div class="prosthesisContainer">
										<div class="row">
											<div class="col-md-4">
												<div class="prosthesisChart">
													<div class="prosthesisUpTeeth">
														<svg id="Layer_2" data-name="Layer 2"
															 xmlns="http://www.w3.org/2000/svg"
															 viewBox="0 0 174 115.59">
															<defs>
																<style>
																	.cls-1 {
																		fill: #fff;
																	}

																	.cls-1,
																	.cls-2 {
																		stroke-width: 0px;
																	}

																	.cls-2 {
																		fill: #8f8f8f;
																	}
																</style>
															</defs>
															<g id="Layer_1-2" data-name="Layer 1">
																<g>
																	<path class="cls-1"
																		  d="m162.95,91.08c-1.51.62-1.82,1.42-1.91,2.12-.01.02-.01.03-.01.05-.06.23-.27.43-.51.43h-.03c-.27-.02-.48-.2-.47-.46.03-.65-.3-1.3-.84-1.71-.47-.34-1.07-.57-1.7-.66-.21.01-.41,0-.61-.05-.25-.05-.42-.29-.4-.55.03-.25.25-.44.51-.44.18,0,.35.01.53.04.5-.05,1.01-.34,1.32-.78.49-.71.41-1.56.31-2-.06-.3-.26-.99-.8-1.32-.07-.04-.14-.07-.21-.11-.23-.12-.33-.41-.21-.66.11-.24.4-.35.64-.24.09.03.17.07.24.12.94.44,2,.58,3.1.39,1.13-.2,2.2-.78,2.93-1.61h.01c.19-.21.5-.23.71-.05.21.18.23.5.04.71h0c-.86.97-1.23,2.18-1.04,3.32.2,1.19.97,2.29,2.07,2.94.23.13.31.43.19.67-.13.23-.42.33-.67.21-.01,0-1.87-.89-3.19-.36Z"/>
																	<!-- g1 -->
																	<g id="blabla"
																	   onclick="colorize(this,'g1cls1','g1cls2',null,'g1CounterValue','g1Location')">
																		<path id="g1cls1" class="cls-2"
																			  d="m98.03.59c-8.14-1.56-9.89,0-10.91,4.34-.05.21-.09.45-.12.7-.11.84-.11,1.89,0,3.04.26,2.71,1.14,5.99,2.77,8.37,1.25,1.84,2.76,2.89,4.49,3.13.29.04.58.06.87.06,2.02,0,4.12-.97,6.11-2.84,1.46-1.37,2.79-3.16,3.79-5.01.77-1.44,1.36-2.91,1.66-4.23.19-.82.27-1.59.22-2.26-.27-3.65-4.07-4.38-8.88-5.3Zm2.18,15.7c-1.34,1.27-3.43,2.7-5.74,2.39-1.29-.18-2.46-1.02-3.46-2.49-2.27-3.32-2.96-8.68-2.43-10.92.7-2.96,1.12-4.74,9.17-3.2,4.91.94,7.49,1.54,7.66,3.93.18,2.39-2.01,7.31-5.2,10.29Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m105.41,6c-.17-2.39-2.75-2.99-7.66-3.93-8.05-1.54-8.47.24-9.17,3.2-.53,2.24.16,7.6,2.43,10.92,1,1.47,2.17,2.31,3.46,2.49,2.31.31,4.4-1.12,5.74-2.39,3.19-2.98,5.38-7.9,5.2-10.29Zm-2.83.82c-.09.18-.27.28-.45.28-.08,0-.15-.02-.23-.06-4.77-2.41-9.82-2.07-9.87-2.07-.29.01-.52-.19-.54-.46-.02-.28.19-.52.46-.54.22-.02,5.37-.37,10.41,2.18.24.12.34.43.22.67Z"/>
																		<path id="g1cls2" class="cls-2"
																			  d="m102.58,6.82c-.09.18-.27.28-.45.28-.08,0-.15-.02-.23-.06-4.77-2.41-9.82-2.07-9.87-2.07-.29.01-.52-.19-.54-.46-.02-.28.19-.52.46-.54.22-.02,5.37-.37,10.41,2.18.24.12.34.43.22.67Z"/>
																	</g>
																	<!-- g2 -->
																	<g id="blabla2"
																	   onclick="colorize(this,'g2cls1','g2cls2',null,'g2CounterValue','g2Location')">
																		<path id="g2cls1" class="cls-2"
																			  d="m118.6,7.4c-7.06-3.98-9.28-3.06-11.86.67-.02.03-.04.05-.05.08-.7,1.03-1.3,2.54-1.66,4.23-.45,2.07-.54,4.39-.02,6.41.53,2.07,1.67,3.59,3.3,4.4,1.11.55,2.27.76,3.41.76,1.78,0,3.49-.52,4.83-1.09,1.79-.76,3.52-1.85,4.95-3.08.88-.76,1.65-1.56,2.24-2.37.58-.76,1-1.51,1.23-2.23,1.14-3.54-2.18-5.41-6.37-7.78Zm-2.64,14.08c-1.5.63-2.94.95-4.24.95-1.01,0-1.94-.19-2.74-.59-1.24-.62-2.09-1.77-2.51-3.43-.82-3.18.12-7.47,1.51-9.49,1.01-1.46,1.8-2.6,3.56-2.6,1.35,0,3.27.68,6.32,2.4,4.57,2.58,6.37,3.84,5.68,6-.64,1.98-3.71,5.12-7.58,6.76Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m117.86,8.72c-3.05-1.72-4.97-2.4-6.32-2.4-1.76,0-2.55,1.14-3.56,2.6-1.39,2.02-2.33,6.31-1.51,9.49.42,1.66,1.27,2.81,2.51,3.43.8.4,1.73.59,2.74.59,1.3,0,2.74-.32,4.24-.95,3.87-1.64,6.94-4.78,7.58-6.76.69-2.16-1.11-3.42-5.68-6Zm2.9,6.47s-.09.02-.14.02c-.22,0-.42-.14-.48-.36-.45-1.48-6.44-4.9-9.29-4.97-.28,0-.5-.23-.49-.51,0-.28.25-.5.51-.49,2.89.07,9.59,3.53,10.23,5.69.08.26-.07.54-.34.62Z"/>
																		<path id="g2cls2" class="cls-2"
																			  d="m120.76,15.19s-.09.02-.14.02c-.22,0-.42-.14-.48-.36-.45-1.48-6.44-4.9-9.29-4.97-.28,0-.5-.23-.49-.51,0-.28.25-.5.51-.49,2.89.07,9.59,3.53,10.23,5.69.08.26-.07.54-.34.62Z"/>
																	</g>
																	<!-- g3 -->
																	<g id="blabla3"
																	   onclick="colorize(this,'g3cls1','g3cls2',null,'g3CounterValue','g3Location')">
																		<path id="g3cls1" class="cls-2"
																			  d="m135.5,18.67c-5.77-4.56-9.18-3.43-11.76-1.26-.48.4-.92.84-1.35,1.28-.3.31-.6.68-.89,1.09-1.42,2.05-2.58,5.21-2.31,8.12.2,2.1,1.12,3.8,2.68,4.89,1.43,1.01,3.26,1.53,5.32,1.53,1.07,0,2.22-.14,3.4-.43,1.73-.41,3.41-1.11,4.82-1.96.56-.34,1.08-.7,1.55-1.07.97-.78,1.72-1.63,2.17-2.48.95-1.8,1.74-5.46-3.63-9.71Zm2.3,9.01c-1.02,1.94-4.2,3.94-7.56,4.75-1.07.26-2.1.39-3.06.39-1.74,0-3.27-.43-4.44-1.26-1.21-.85-1.89-2.13-2.05-3.8-.27-2.94,1.2-6.39,2.78-8.02,1.41-1.45,2.88-2.69,4.94-2.69,1.61,0,3.58.77,6.15,2.8,2.37,1.87,4.86,4.75,3.24,7.83Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m134.56,19.85c-2.57-2.03-4.54-2.8-6.15-2.8-2.06,0-3.53,1.24-4.94,2.69-1.58,1.63-3.05,5.08-2.78,8.02.16,1.67.84,2.95,2.05,3.8,1.17.83,2.7,1.26,4.44,1.26.96,0,1.99-.13,3.06-.39,3.36-.81,6.54-2.81,7.56-4.75,1.62-3.08-.87-5.96-3.24-7.83Zm-1.02,7.98c-.1.13-.25.2-.4.2-.11,0-.22-.03-.31-.1-.22-.17-.26-.48-.09-.7,1.05-1.39.94-3.49-.25-4.78-1.21-1.31-3.45-1.72-5.11-.93-.25.12-.55.01-.67-.24s-.01-.55.24-.66c2.06-.99,4.76-.49,6.28,1.15,1.54,1.66,1.67,4.27.31,6.06Z"/>
																		<path id="g3cls2" class="cls-2"
																			  d="m133.54,27.83c-.1.13-.25.2-.4.2-.11,0-.22-.03-.31-.1-.22-.17-.26-.48-.09-.7,1.05-1.39.94-3.49-.25-4.78-1.21-1.31-3.45-1.72-5.11-.93-.25.12-.55.01-.67-.24s-.01-.55.24-.66c2.06-.99,4.76-.49,6.28,1.15,1.54,1.66,1.67,4.27.31,6.06Z"/>
																	</g>
																	<!-- g4 -->
																	<g id="blabla4"
																	   onclick="colorize(this,'g4cls1','g4cls2','g4cls3','g4CounterValue','g4Location')">
																		<path id="g4cls1" class="cls-2"
																			  d="m149.62,34.74c-4.77-6.96-9.26-5.75-12.66-3.88-.03.02-.06.03-.09.05-.48.27-.97.61-1.46,1.02-1.89,1.56-3.7,4.07-4.26,6.78-.11.53-.16,1.04-.16,1.54,0,1.42.43,2.71,1.27,3.8,1.56,2.01,4.25,3.23,7.58,3.45.35.02.71.04,1.06.04,1.63,0,3.26-.24,4.71-.66,1.47-.44,2.76-1.06,3.7-1.83.16-.13.31-.26.45-.4,2.63-2.61,2.58-5.94-.14-9.91Zm-.92,8.84c-1.64,1.63-5.32,2.65-8.77,2.42-2.89-.19-5.19-1.21-6.48-2.87-.87-1.13-1.15-2.52-.83-4.12.6-2.94,3.01-5.69,4.98-6.79,2.96-1.65,6.48-2.89,10.78,3.37,3.04,4.44,1.64,6.68.32,7.99Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m148.38,35.59c-4.3-6.26-7.82-5.02-10.78-3.37-1.97,1.1-4.38,3.85-4.98,6.79-.32,1.6-.04,2.99.83,4.12,1.29,1.66,3.59,2.68,6.48,2.87,3.45.23,7.13-.79,8.77-2.42,1.32-1.31,2.72-3.55-.32-7.99Zm-6.68,8.4c-.09.16-.26.25-.43.25-.09,0-.17-.02-.25-.07-.24-.14-.33-.44-.19-.68.76-1.31.65-3.01-.25-4.23-.92-1.24-2.61-1.93-4.22-1.73-.27.03-.53-.16-.56-.43-.04-.28.15-.53.43-.56,1.95-.25,4.03.6,5.15,2.12,1.14,1.54,1.27,3.68.32,5.33Zm4.84-3.18c-.06.23-.26.38-.49.38-.04,0-.08,0-.12,0-1.71-.43-3.2-1.5-4.1-2.92-.91-1.44-1.19-3.19-.77-4.81.07-.27.34-.43.62-.36.26.07.43.34.35.61-.35,1.36-.11,2.82.65,4.02.76,1.21,2.04,2.11,3.49,2.48.27.07.44.34.37.61Z"/>
																		<path id="g4cls2" class="cls-2"
																			  d="m146.54,40.81c-.06.23-.26.38-.49.38-.04,0-.08,0-.12,0-1.71-.43-3.2-1.5-4.1-2.92-.91-1.44-1.19-3.19-.77-4.81.07-.27.34-.43.62-.36.26.07.43.34.35.61-.35,1.36-.11,2.82.65,4.02.76,1.21,2.04,2.11,3.49,2.48.27.07.44.34.37.61Z"/>
																		<path id="g4cls3" class="cls-2"
																			  d="m141.7,43.99c-.09.16-.26.25-.43.25-.09,0-.17-.02-.25-.07-.24-.14-.33-.44-.19-.68.76-1.31.65-3.01-.25-4.23-.92-1.24-2.61-1.93-4.22-1.73-.27.03-.53-.16-.56-.43-.04-.28.15-.53.43-.56,1.95-.25,4.03.6,5.15,2.12,1.14,1.54,1.27,3.68.32,5.33Z"/>
																	</g>
																	<!-- g5 -->
																	<g id="blabla5"
																	   onclick="colorize(this,'g5cls1','g5cls2','g5cls3','g5CounterValue','g5Location')">
																		<path id="g5cls1" class="cls-2"
																			  d="m159.32,50.06c-3.61-5.27-7.07-5.86-10.01-5.01-.97.27-1.89.7-2.74,1.18-.33.18-.65.4-.96.65-1.83,1.41-3.29,3.77-3.79,6.2-.48,2.37-.03,4.53,1.31,6.25,1.57,2.04,4.14,3.27,7.22,3.48.3.02.6.03.9.03.73,0,1.46-.06,2.17-.17,1.47-.22,2.86-.66,4.03-1.27.78-.41,1.46-.89,2.01-1.43,2.63-2.61,2.58-5.94-.14-9.91Zm-.92,8.84c-1.63,1.62-4.9,2.6-7.95,2.4-1.32-.08-2.52-.38-3.56-.88-1.04-.48-1.92-1.16-2.57-2.01-1.34-1.73-1.3-3.69-1.02-5.03.48-2.39,2.13-4.79,4-5.84,1.27-.71,2.64-1.34,4.13-1.34,1.99,0,4.19,1.13,6.65,4.71,3.04,4.43,1.64,6.68.32,7.99Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m158.08,50.91c-2.46-3.58-4.66-4.71-6.65-4.71-1.49,0-2.86.63-4.13,1.34-1.87,1.05-3.52,3.45-4,5.84-.28,1.34-.32,3.3,1.02,5.03.65.85,1.53,1.53,2.57,2.01,1.04.5,2.24.8,3.56.88,3.05.2,6.32-.78,7.95-2.4,1.32-1.31,2.72-3.56-.32-7.99Zm-6.54,6.9c-.01.08-.04.16-.1.23-.09.13-.25.2-.41.2-.08,0-.15-.02-.23-.05-.21-.12-.3-.44-.22-.68.41-1.23.25-2.44-.44-3.48-.7-1.06-2.13-1.78-3.64-1.85-.28-.01-.5-.24-.48-.52.01-.27.26-.5.52-.48,1.85.08,3.55.96,4.43,2.29.86,1.29,1.06,2.83.57,4.34Zm4.95-1.62c-.05.24-.26.4-.49.4-.04,0-.07-.01-.11-.01-1.65-.36-3.09-1.43-3.85-2.85-.77-1.44-.81-3.17-.11-4.64.12-.25.42-.36.67-.24s.36.42.24.67c-.56,1.18-.53,2.58.09,3.73.62,1.17,1.81,2.05,3.17,2.35.28.06.45.32.39.59Z"/>
																		<path id="g5cls2" class="cls-2"
																			  d="m151.54,57.81c-.01.08-.04.16-.1.23-.09.13-.25.2-.41.2-.08,0-.15-.02-.23-.05-.21-.12-.3-.44-.22-.68.41-1.23.25-2.44-.44-3.48-.7-1.06-2.13-1.78-3.64-1.85-.28-.01-.5-.24-.48-.52.01-.27.26-.5.52-.48,1.85.08,3.55.96,4.43,2.29.86,1.29,1.06,2.83.57,4.34Z"/>
																		<path id="g5cls3" class="cls-2"
																			  d="m156.49,56.19c-.05.24-.26.4-.49.4-.04,0-.07-.01-.11-.01-1.65-.36-3.09-1.43-3.85-2.85-.77-1.44-.81-3.17-.11-4.64.12-.25.42-.36.67-.24s.36.42.24.67c-.56,1.18-.53,2.58.09,3.73.62,1.17,1.81,2.05,3.17,2.35.28.06.45.32.39.59Z"/>
																	</g>
																	<!-- g6 -->
																	<g id="blabla6"
																	   onclick="colorize(this,'g6cls1','g6cls2',null,'g6CounterValue','g6Location')">
																		<path id="g6cls1" class="cls-2"
																			  d="m171.99,86.11c-1.79-6.13-4.93-7.74-8.07-7.78-1.14-.03-2.28.16-3.36.41-.78.19-1.83.44-2.93.87-2.7,1.04-5.69,3.15-5.69,8.01,0,.87.1,1.83.31,2.89.49,2.47,2.06,4.4,4.54,5.57,1.78.84,3.82,1.21,5.8,1.22h.11c1.02,0,2.02-.09,2.94-.27,1.22-.22,2.32-.58,3.19-1.05,3.47-1.85,4.53-5.17,3.16-9.87Zm-3.87,8.54c-2.44,1.31-7.23,1.71-10.69.07-2.06-.97-3.31-2.49-3.71-4.5-1.46-7.32,2.95-9.01,7.19-10.01,1.02-.24,1.99-.39,2.89-.39,2.96,0,5.25,1.59,6.74,6.71,1.18,4.03.41,6.61-2.42,8.12Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m170.54,86.53c-1.49-5.12-3.78-6.71-6.74-6.71-.9,0-1.87.15-2.89.39-4.24,1-8.65,2.69-7.19,10.01.4,2.01,1.65,3.53,3.71,4.5,3.46,1.64,8.25,1.24,10.69-.07,2.83-1.51,3.6-4.09,2.42-8.12Zm-3.73,4.7c-.13.23-.42.33-.67.21-.01,0-1.87-.89-3.19-.36-1.51.62-1.82,1.42-1.91,2.12-.01.02-.01.03-.01.05-.06.23-.27.43-.51.43h-.03c-.27-.02-.48-.2-.47-.46.03-.65-.3-1.3-.84-1.71-.47-.34-1.07-.57-1.7-.66-.21.01-.41,0-.61-.05-.25-.05-.42-.29-.4-.55.03-.25.25-.44.51-.44.18,0,.35.01.53.04.5-.05,1.01-.34,1.32-.78.49-.71.41-1.56.31-2-.06-.3-.26-.99-.8-1.32-.07-.04-.14-.07-.21-.11-.23-.12-.33-.41-.21-.66.11-.24.4-.35.64-.24.09.03.17.07.24.12.94.44,2,.58,3.1.39,1.13-.2,2.2-.78,2.93-1.61h.01c.19-.21.5-.23.71-.05.21.18.23.5.04.71h0c-.86.97-1.23,2.18-1.04,3.32.2,1.19.97,2.29,2.07,2.94.23.13.31.43.19.67Z"/>
																		<path id="g6cls2" class="cls-2"
																			  d="m166.62,90.56c-1.1-.65-1.87-1.75-2.07-2.94-.19-1.14.18-2.35,1.04-3.31h0c.19-.22.17-.54-.04-.72-.21-.18-.52-.16-.71.04h0s-.01.01-.01.01c-.73.83-1.8,1.41-2.93,1.61-1.1.19-2.16.05-3.1-.39-.07-.05-.15-.09-.24-.12-.24-.11-.53,0-.64.24-.12.25-.02.54.21.66.07.04.14.07.21.11.54.33.74,1.02.8,1.32.1.44.18,1.29-.31,2-.31.44-.82.73-1.32.78-.18-.03-.35-.04-.53-.04-.26,0-.48.19-.51.44-.02.26.15.5.4.55.2.05.4.06.61.05.63.09,1.23.32,1.7.66.54.41.87,1.06.84,1.71-.01.26.2.44.47.46h.03c.24,0,.45-.2.51-.43,0-.02,0-.03.01-.05.09-.7.4-1.5,1.91-2.12,1.32-.53,3.18.35,3.19.36.25.12.54.02.67-.21.12-.24.04-.54-.19-.67Zm-4.04-.41c-.91.37-1.56.84-1.99,1.47-.2-.35-.48-.66-.82-.91-.22-.17-.46-.31-.73-.43.24-.18.45-.39.61-.64.53-.75.69-1.74.48-2.78-.05-.21-.11-.41-.19-.61.69.12,1.4.11,2.13,0,.56-.1,1.1-.27,1.61-.52-.19.68-.24,1.38-.12,2.07.13.77.45,1.51.92,2.15-.61-.07-1.29-.04-1.9.21Z"/>
																	</g>
																	<!-- g7 -->
																	<g id="blabla7"
																	   onclick="colorize(this,'g7cls1','g7cls2',null,'g7CounterValue','g7Location')">
																		<path id="g7cls1" class="cls-2"
																			  d="m166.93,67.92c-2.66-6.21-6.24-7.1-9.48-6.52-.79.13-1.56.36-2.29.61-.49.17-1.09.38-1.74.66-2.45,1.05-5.53,3.04-5.53,7.41,0,1.15.21,2.48.71,3.99.77,2.34,2.53,4.04,5.08,4.9,1.26.43,2.61.62,3.95.64h.17c2.25,0,4.47-.51,6.12-1.28.42-.21.81-.42,1.15-.65,3.1-2.12,3.73-5.4,1.86-9.76Zm-2.71,8.51c-2.14,1.46-6.59,2.3-10.05,1.12-2.12-.72-3.52-2.05-4.14-3.95-2.24-6.79,1.73-8.81,5.63-10.17,3.25-1.13,6.95-1.76,9.89,5.09,1.59,3.7,1.16,6.22-1.33,7.91Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m165.55,68.52c-2.94-6.85-6.64-6.22-9.89-5.09-3.9,1.36-7.87,3.38-5.63,10.17.62,1.9,2.02,3.23,4.14,3.95,3.46,1.18,7.91.34,10.05-1.12,2.49-1.69,2.92-4.21,1.33-7.91Zm-2.62.53c-.28.18-.58.33-.89.44-.93.6-1.58,1.53-1.99,2.27-.68,1.27-.83,2.14-.53,3.22.01.05.02.09.02.14,0,.21-.15.41-.37.48-.04.01-.09.01-.13.01-.22,0-.42-.14-.48-.36-.68-2.45-2.23-2.84-5.26-2.84-.22,0-.44.01-.67.01-.25.03-.49-.2-.51-.46-.02-.26.17-.5.44-.54.02,0,1.98-.28,2.94-1.54.78-1.02,1.08-1.96.88-2.77-.06-.26.08-.51.33-.59.25-.09.52.03.62.27.4.93,1.16,1.61,2.09,1.86.71.2,1.49.17,2.19-.07.27-.17.56-.31.87-.42.24-.09.51.01.62.24.12.23.04.51-.17.65Z"/>
																		<path id="g7cls2" class="cls-2"
																			  d="m163.1,68.4c-.11-.23-.38-.33-.62-.24-.31.11-.6.25-.87.42-.7.24-1.48.27-2.19.07-.93-.25-1.69-.93-2.09-1.86-.1-.24-.37-.36-.62-.27-.25.08-.39.33-.33.59.2.81-.1,1.75-.88,2.77-.96,1.26-2.92,1.54-2.94,1.54-.27.04-.46.28-.44.54.02.26.26.49.51.46.23,0,.45-.01.67-.01,3.03,0,4.58.39,5.26,2.84.06.22.26.36.48.36.04,0,.09,0,.13-.01.22-.07.37-.27.37-.48,0-.05-.01-.09-.02-.14-.3-1.08-.15-1.95.53-3.22.41-.74,1.06-1.67,1.99-2.27.31-.11.61-.26.89-.44.21-.14.29-.42.17-.65Zm-3.93,2.88c-.28.51-.53,1.06-.67,1.66-.81-.92-1.94-1.3-3.32-1.45.41-.26.8-.59,1.12-1.01.51-.66.85-1.31,1.02-1.94.5.51,1.13.88,1.83,1.08.35.09.7.15,1.05.16-.43.5-.77,1.03-1.03,1.5Z"/>
																	</g>
																	<!-- g8 -->
																	<g id="blabla8"
																	   onclick="colorize(this,'g8cls1','g8cls2',null,'g8CounterValue','g8Location')">
																		<path id="g8cls1" class="cls-2"
																			  d="m173.85,105.46c-.98-7.02-4.61-8.46-8.21-8.43-.67.01-1.34.07-1.99.15l-.9.1s-.1.01-.16.02c-3.84.43-8.91,1.11-8.91,9.23,0,.37.01.75.03,1.15.14,2.69,1.34,4.84,3.46,6.21,1.86,1.2,4.29,1.7,6.65,1.7,1.99,0,3.94-.35,5.46-.94,3.68-1.44,5.23-4.53,4.57-9.19Zm-5.12,7.79c-2.54.98-7.55,1.44-10.75-.62-1.72-1.11-2.65-2.81-2.77-5.03-.42-7.91,3.71-8.37,7.71-8.82l.92-.11c.67-.08,1.32-.13,1.93-.13,3.43,0,5.82,1.55,6.59,7.13.56,3.99-.59,6.4-3.63,7.58Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m172.36,105.67c-.77-5.58-3.16-7.13-6.59-7.13-.61,0-1.26.05-1.93.13l-.92.11c-4,.45-8.13.91-7.71,8.82.12,2.22,1.05,3.92,2.77,5.03,3.2,2.06,8.21,1.6,10.75.62,3.04-1.18,4.19-3.59,3.63-7.58Zm-3.57,3.03c-.11.22-.37.33-.61.26-1.31-.42-2.68-.42-3.67-.01-.92.37-1.57,1.17-1.66,1.97.02.19.03.38.01.57-.02.25-.22.45-.48.46h-.02c-.24,0-.45-.18-.49-.42-.03-.19-.04-.38-.03-.58-.11-.6-.51-1.24-1.1-1.72-.55-.44-1.27-.67-2-.64-.21.05-.42.09-.64.08-.25,0-.47-.2-.49-.45-.03-.26.14-.49.39-.54.19-.05.39-.07.59-.08.97-.28,2.11-1.31,2.47-2.14.28-.66.05-1.22-.47-1.8-.85-.59-1.33-1.2-1.37-1.24-.16-.2-.14-.49.04-.67.18-.18.47-.19.67-.03.42.34.9.72,1.31,1.16.6.4,1.39.77,2.32.86,1.42.14,2.88-.32,3.92-1.2.2-.18.51-.16.69.04.19.2.18.5-.01.69-.68.68-1.06,1.67-.96,2.52.09.9.62,1.75,1.41,2.27.21.14.29.41.18.64Z"/>
																		<path id="g8cls2" class="cls-2"
																			  d="m168.61,108.06c-.79-.52-1.32-1.37-1.41-2.27-.1-.85.28-1.84.96-2.52.19-.19.2-.49.01-.69-.18-.2-.49-.22-.69-.04-1.04.88-2.5,1.34-3.92,1.2-.93-.09-1.72-.46-2.32-.86-.41-.44-.89-.82-1.31-1.16-.2-.16-.49-.15-.67.03-.18.18-.2.47-.04.67.04.04.52.65,1.37,1.24.52.58.75,1.14.47,1.8-.36.83-1.5,1.86-2.47,2.14-.2.01-.4.03-.59.08-.25.05-.42.28-.39.54.02.25.24.45.49.45.22.01.43-.03.64-.08.73-.03,1.45.2,2,.64.59.48.99,1.12,1.1,1.72-.01.2,0,.39.03.58.04.24.25.42.49.42h.02c.26-.01.46-.21.48-.46.02-.19,0-.38-.01-.57.09-.8.74-1.6,1.66-1.97.99-.41,2.36-.41,3.67.01.24.07.5-.04.61-.26.11-.23.03-.5-.18-.64Zm-4.48-.04c-.78.32-1.41.85-1.81,1.49-.25-.39-.57-.75-.96-1.06-.32-.26-.68-.46-1.08-.61.76-.55,1.41-1.29,1.7-1.99.22-.5.25-.97.16-1.4.41.14.85.24,1.33.29.98.09,1.99-.05,2.9-.4-.17.51-.23,1.05-.17,1.56.07.62.29,1.23.64,1.76-.98-.08-1.93.03-2.71.36Z"/>
																	</g>
																	<!-- g9 -->
																	<g id="blabla9"
																	   onclick="colorize(this,'g9cls1','g9cls2',null,'g9CounterValue','g9Location')">
																		<path id="g9cls1" class="cls-2"
																			  d="m87,5.63c-.03-.25-.07-.49-.12-.7-1.03-4.34-2.77-5.9-10.92-4.34-4.81.92-8.61,1.65-8.88,5.3-.05.67.03,1.44.22,2.26.3,1.31.88,2.77,1.66,4.2.99,1.86,2.32,3.67,3.79,5.04,1.99,1.87,4.09,2.84,6.12,2.84.29,0,.57-.02.86-.06,1.73-.24,3.24-1.29,4.5-3.13,1.63-2.39,2.51-5.66,2.77-8.37.11-1.15.11-2.2,0-3.04Zm-4.02,10.56c-1,1.47-2.16,2.31-3.46,2.49-2.31.31-4.39-1.12-5.74-2.39-3.19-2.98-5.37-7.9-5.2-10.29.18-2.39,2.75-2.99,7.66-3.93,2.03-.39,3.58-.57,4.76-.57,3.52,0,3.89,1.56,4.41,3.77.53,2.24-.15,7.6-2.43,10.92Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m85.41,5.27c-.52-2.21-.89-3.77-4.41-3.77-1.18,0-2.73.18-4.76.57-4.91.94-7.48,1.54-7.66,3.93-.17,2.39,2.01,7.31,5.2,10.29,1.35,1.27,3.43,2.7,5.74,2.39,1.3-.18,2.46-1.02,3.46-2.49,2.28-3.32,2.96-8.68,2.43-10.92Zm-3.44-.3s-5.1-.34-9.87,2.07c-.08.04-.15.06-.23.06-.18,0-.36-.1-.45-.28-.12-.24-.02-.55.22-.67,5.04-2.55,10.19-2.2,10.41-2.18.27.02.48.26.46.54-.02.27-.26.47-.54.46Z"/>
																		<path id="g9cls2" class="cls-2"
																			  d="m82.51,4.51c-.02.27-.26.47-.54.46-.04,0-5.1-.34-9.87,2.07-.08.04-.15.06-.23.06-.18,0-.36-.1-.45-.28-.12-.24-.02-.55.22-.67,5.04-2.55,10.19-2.2,10.41-2.18.27.02.48.26.46.54Z"/>
																	</g>
																	<!-- g10 -->
																	<g id="blabla10"
																	   onclick="colorize(this,'g10cls1','g10cls2',null,'g10CounterValue','g10Location')">
																		<path id="g10cls1" class="cls-2"
																			  d="m68.96,12.35c-.36-1.67-.96-3.18-1.66-4.2-.01-.03-.03-.05-.05-.08-2.58-3.73-4.8-4.65-11.85-.67-4.2,2.37-7.51,4.24-6.38,7.78.23.72.65,1.47,1.23,2.23.59.81,1.36,1.61,2.24,2.37,1.43,1.23,3.15,2.32,4.95,3.08,1.34.57,3.06,1.09,4.83,1.09,1.14,0,2.31-.21,3.41-.76,1.63-.81,2.77-2.33,3.31-4.4.52-2.02.42-4.36-.03-6.44Zm-1.43,6.06c-.43,1.66-1.27,2.81-2.52,3.43-1.81.9-4.29.77-6.98-.36-3.86-1.64-6.94-4.78-7.57-6.76-.7-2.16,1.1-3.42,5.68-6,3.04-1.72,4.97-2.4,6.32-2.4,1.75,0,2.54,1.14,3.55,2.6,1.4,2.02,2.34,6.31,1.52,9.49Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m66.01,8.92c-1.01-1.46-1.8-2.6-3.55-2.6-1.35,0-3.28.68-6.32,2.4-4.58,2.58-6.38,3.84-5.68,6,.63,1.98,3.71,5.12,7.57,6.76,2.69,1.13,5.17,1.26,6.98.36,1.25-.62,2.09-1.77,2.52-3.43.82-3.18-.12-7.47-1.52-9.49Zm-2.86.96c-2.84.07-8.84,3.49-9.29,4.97-.06.22-.26.36-.48.36-.05,0-.09-.01-.14-.02-.27-.08-.42-.36-.34-.62.65-2.16,7.35-5.62,10.23-5.69.26,0,.5.21.51.49.01.28-.21.51-.49.51Z"/>
																		<path id="g10cls2" class="cls-2"
																			  d="m63.64,9.37c.01.28-.21.51-.49.51-2.84.07-8.84,3.49-9.29,4.97-.06.22-.26.36-.48.36-.05,0-.09-.01-.14-.02-.27-.08-.42-.36-.34-.62.65-2.16,7.35-5.62,10.23-5.69.26,0,.5.21.51.49Z"/>
																	</g>
																	<!-- g11 -->
																	<g id="blabla11"
																	   onclick="colorize(this,'g11cls1','g11cls2',null,'g11CounterValue','g11Location')">
																		<path id="g11cls1" class="cls-2"
																			  d="m52.49,19.78c-.29-.41-.59-.78-.89-1.09-.43-.44-.87-.88-1.35-1.28-2.58-2.17-5.98-3.3-11.75,1.26-5.37,4.25-4.58,7.91-3.64,9.71.44.85,1.19,1.69,2.15,2.47.49.38,1.02.75,1.6,1.1,1.42.84,3.08,1.53,4.8,1.94,1.18.29,2.32.43,3.4.43,2.06,0,3.88-.52,5.31-1.53,1.56-1.1,2.49-2.79,2.68-4.89.27-2.92-.88-6.08-2.31-8.12Zm.81,7.98c-.15,1.67-.84,2.95-2.04,3.8-1.82,1.29-4.49,1.6-7.5.87-3.37-.81-6.55-2.81-7.56-4.75-1.62-3.08.86-5.96,3.23-7.83,2.57-2.03,4.54-2.8,6.16-2.8,2.05,0,3.53,1.24,4.93,2.69,1.58,1.63,3.06,5.08,2.78,8.02Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m50.52,19.74c-1.4-1.45-2.88-2.69-4.93-2.69-1.62,0-3.59.77-6.16,2.8-2.37,1.87-4.85,4.75-3.23,7.83,1.01,1.94,4.19,3.94,7.56,4.75,3.01.73,5.68.42,7.5-.87,1.2-.85,1.89-2.13,2.04-3.8.28-2.94-1.2-6.39-2.78-8.02Zm-3.23,1.54c-.12.25-.42.36-.67.24-1.65-.79-3.9-.38-5.11.93-1.19,1.29-1.3,3.39-.25,4.78.17.22.13.53-.09.7-.09.07-.2.1-.31.1-.15,0-.3-.07-.4-.2-1.36-1.79-1.22-4.4.31-6.06,1.52-1.64,4.22-2.14,6.28-1.15.25.11.36.41.24.66Z"/>
																		<path id="g11cls2" class="cls-2"
																			  d="m47.29,21.28c-.12.25-.42.36-.67.24-1.65-.79-3.9-.38-5.11.93-1.19,1.29-1.3,3.39-.25,4.78.17.22.13.53-.09.7-.09.07-.2.1-.31.1-.15,0-.3-.07-.4-.2-1.36-1.79-1.22-4.4.31-6.06,1.52-1.64,4.22-2.14,6.28-1.15.25.11.36.41.24.66Z"/>
																	</g>
																	<!-- g12 -->
																	<g id="blabla12"
																	   onclick="colorize(this,'g12cls1','g12cls2','g12cls3','g12CounterValue','g12Location')">
																		<path id="g12cls1" class="cls-2"
																			  d="m42.84,38.71c-.55-2.7-2.36-5.2-4.23-6.76-.5-.42-1-.77-1.48-1.04-.04-.02-.08-.04-.12-.06-3.39-1.87-7.88-3.05-12.64,3.89-2.72,3.97-2.77,7.3-.14,9.91.14.14.29.27.45.39.95.78,2.24,1.41,3.72,1.84,1.45.43,3.06.66,4.69.66.36,0,.71-.02,1.07-.04,1.67-.11,3.17-.47,4.45-1.05,1.28-.59,2.34-1.39,3.12-2.4,1.14-1.47,1.53-3.32,1.11-5.34Zm-2.3,4.42c-1.29,1.66-3.59,2.68-6.48,2.87-3.44.23-7.12-.79-8.77-2.42-1.32-1.31-2.72-3.55.33-7.99,2.45-3.58,4.65-4.71,6.64-4.71,1.49,0,2.87.64,4.13,1.34,1.97,1.1,4.38,3.85,4.98,6.79.09.43.13.85.13,1.25,0,1.08-.32,2.05-.96,2.87Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m41.37,39.01c-.6-2.94-3.01-5.69-4.98-6.79-1.26-.7-2.64-1.34-4.13-1.34-1.99,0-4.19,1.13-6.64,4.71-3.05,4.44-1.65,6.68-.33,7.99,1.65,1.63,5.33,2.65,8.77,2.42,2.89-.19,5.19-1.21,6.48-2.87.64-.82.96-1.79.96-2.87,0-.4-.04-.82-.13-1.25Zm-13.3,2.17s-.08,0-.12,0c-.22,0-.43-.15-.49-.38-.06-.27.1-.54.37-.61,1.46-.37,2.73-1.27,3.5-2.48.76-1.2.99-2.66.64-4.02-.07-.27.09-.54.36-.61.26-.07.54.09.61.36.42,1.62.14,3.37-.77,4.81-.9,1.42-2.39,2.49-4.1,2.92Zm9.57-3.65c-1.59-.2-3.3.49-4.22,1.73-.9,1.22-1,2.92-.25,4.23.14.24.06.54-.18.68-.08.05-.17.07-.25.07-.18,0-.35-.09-.44-.25-.95-1.65-.82-3.79.32-5.33,1.13-1.52,3.2-2.37,5.15-2.12.28.03.47.28.44.56-.04.27-.3.46-.57.43Z"/>
																		<path id="g12cls2" class="cls-2"
																			  d="m32.17,38.26c-.9,1.42-2.39,2.49-4.1,2.92-.04,0-.08,0-.12,0-.22,0-.43-.15-.49-.38-.06-.27.1-.54.37-.61,1.46-.37,2.73-1.27,3.5-2.48.76-1.2.99-2.66.64-4.02-.07-.27.09-.54.36-.61.26-.07.54.09.61.36.42,1.62.14,3.37-.77,4.81Z"/>
																		<path id="g12cls3" class="cls-2"
																			  d="m38.21,37.1c-.04.27-.3.46-.57.43-1.59-.2-3.3.49-4.22,1.73-.9,1.22-1,2.92-.25,4.23.14.24.06.54-.18.68-.08.05-.17.07-.25.07-.18,0-.35-.09-.44-.25-.95-1.65-.82-3.79.32-5.33,1.13-1.52,3.2-2.37,5.15-2.12.28.03.47.28.44.56Z"/>
																	</g>
																	<!-- g13 -->
																	<g id="blabla13"
																	   onclick="colorize(this,'g13cls1','g13cls2','g13cls3','g13CounterValue','g13Location')">
																		<path id="g13cls1" class="cls-2"
																			  d="m32.17,53.08c-.49-2.42-1.95-4.78-3.77-6.2-.31-.25-.64-.46-.97-.65-.85-.48-1.77-.91-2.75-1.19-2.94-.84-6.39-.25-10.01,5.02-2.72,3.97-2.77,7.3-.14,9.91.55.54,1.23,1.02,2.01,1.43,1.17.61,2.57,1.05,4.03,1.27.71.11,1.44.17,2.17.17.3,0,.6-.01.9-.03,1.54-.11,2.95-.47,4.18-1.05,1.22-.59,2.26-1.41,3.05-2.43,1.33-1.72,1.78-3.88,1.3-6.25Zm-2.49,5.33c-1.31,1.69-3.49,2.72-6.14,2.89-3.05.2-6.32-.78-7.95-2.4-1.32-1.31-2.72-3.56.32-7.99,2.46-3.58,4.66-4.71,6.65-4.71,1.49,0,2.86.64,4.13,1.34,1.87,1.05,3.52,3.45,4.01,5.84.27,1.34.32,3.3-1.02,5.03Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m30.7,53.38c-.49-2.39-2.14-4.79-4.01-5.84-1.27-.7-2.64-1.34-4.13-1.34-1.99,0-4.19,1.13-6.65,4.71-3.04,4.43-1.64,6.68-.32,7.99,1.63,1.62,4.9,2.6,7.95,2.4,2.65-.17,4.83-1.2,6.14-2.89,1.34-1.73,1.29-3.69,1.02-5.03Zm-12.59,3.2s-.07.01-.11.01c-.23,0-.44-.16-.49-.4-.06-.27.12-.53.39-.59,1.37-.3,2.55-1.18,3.18-2.35.61-1.15.65-2.55.08-3.73-.11-.25-.01-.55.24-.67s.55-.01.67.24c.7,1.47.66,3.2-.11,4.64-.76,1.42-2.2,2.49-3.85,2.85Zm9.4-4.4c-1.51.07-2.94.79-3.65,1.85-.69,1.04-.84,2.25-.44,3.48.08.24-.01.56-.22.68-.07.03-.15.05-.23.05-.16,0-.31-.07-.41-.2-.06-.07-.09-.15-.1-.23-.49-1.54-.28-3.07.57-4.34.89-1.33,2.59-2.21,4.43-2.29.28-.02.51.21.53.48.01.28-.21.51-.48.52Z"/>
																		<path id="g13cls2" class="cls-2"
																			  d="m27.99,51.66c.01.28-.21.51-.48.52-1.51.07-2.94.79-3.65,1.85-.69,1.04-.84,2.25-.44,3.48.08.24-.01.56-.22.68-.07.03-.15.05-.23.05-.16,0-.31-.07-.41-.2-.06-.07-.09-.15-.1-.23-.49-1.54-.28-3.07.57-4.34.89-1.33,2.59-2.21,4.43-2.29.28-.02.51.21.53.48Z"/>
																		<path id="g13cls3" class="cls-2"
																			  d="m21.96,53.73c-.76,1.42-2.2,2.49-3.85,2.85-.03,0-.07.01-.11.01-.23,0-.44-.16-.49-.4-.06-.27.12-.53.39-.59,1.37-.3,2.55-1.18,3.18-2.35.61-1.15.65-2.55.08-3.73-.11-.25-.01-.55.24-.67s.55-.01.67.24c.7,1.47.66,3.2-.11,4.64Z"/>
																	</g>
																	<!-- g14 -->
																	<g id="blabla14"
																	   onclick="colorize(this,'g14cls1','g14cls2',null,'g14CounterValue','g14Location')">
																		<path id="g14cls1" class="cls-2"
																			  d="m16.37,79.61c-1.1-.43-2.16-.68-2.94-.87-1.08-.25-2.22-.44-3.36-.41-3.14.04-6.28,1.65-8.07,7.78-1.37,4.7-.31,8.02,3.16,9.87.87.47,1.97.83,3.19,1.05.92.18,1.92.27,2.94.27h.11c1.98-.01,4.03-.38,5.81-1.22,2.48-1.17,4.05-3.1,4.54-5.57,1.38-6.95-2.2-9.67-5.38-10.9Zm3.9,10.61c-.4,2.01-1.65,3.53-3.7,4.5-3.47,1.64-8.25,1.24-10.7-.07-2.83-1.51-3.6-4.09-2.42-8.12,1.49-5.12,3.79-6.71,6.74-6.71.91,0,1.87.15,2.89.39,3.68.87,7.48,2.25,7.48,7.36,0,.79-.09,1.67-.29,2.65Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m13.08,80.21c-1.02-.24-1.98-.39-2.89-.39-2.95,0-5.25,1.59-6.74,6.71-1.18,4.03-.41,6.61,2.42,8.12,2.45,1.31,7.23,1.71,10.7.07,2.05-.97,3.3-2.49,3.7-4.5.2-.98.29-1.86.29-2.65,0-5.11-3.8-6.49-7.48-7.36Zm4.05,10.59c-.2.05-.4.06-.6.05-.64.09-1.24.32-1.7.66-.55.41-.88,1.06-.85,1.71,0,.26-.19.49-.46.52h-.04c-.25,0-.46-.19-.5-.43-.11-.81-.42-1.61-1.93-2.23-1.32-.53-3.17.35-3.19.36-.24.12-.54.02-.66-.21-.13-.24-.05-.54.18-.67,1.1-.65,1.87-1.75,2.07-2.94.2-1.14-.18-2.35-1.03-3.31h-.01c-.19-.22-.17-.54.04-.72s.52-.16.71.04c0,.01.01.01.01.02.73.82,1.8,1.4,2.93,1.6,1.1.19,2.16.05,3.1-.39.07-.05.15-.09.24-.12.24-.1.53,0,.65.24.11.25.02.54-.22.66-.07.04-.14.07-.21.11-.54.33-.74,1.02-.8,1.32-.1.44-.18,1.29.31,2,.31.44.82.73,1.32.78.18-.03.35-.04.53-.04.27,0,.48.19.51.44.03.26-.14.5-.4.55Z"/>
																		<path id="g14cls2" class="cls-2"
																			  d="m17.02,89.81c-.18,0-.35.01-.53.04-.5-.05-1.01-.34-1.32-.78-.49-.71-.41-1.56-.31-2,.06-.3.26-.99.8-1.32.07-.04.14-.07.21-.11.24-.12.33-.41.22-.66-.12-.24-.41-.34-.65-.24-.09.03-.17.07-.24.12-.94.44-2,.58-3.1.39-1.13-.2-2.2-.78-2.93-1.6,0-.01-.01-.01-.01-.02-.19-.2-.5-.22-.71-.04s-.23.5-.04.71h0s.01,0,.01,0c.85.96,1.23,2.17,1.03,3.31-.2,1.19-.97,2.29-2.07,2.94-.23.13-.31.43-.18.67.12.23.42.33.66.21.02,0,1.87-.89,3.19-.36,1.51.62,1.82,1.42,1.93,2.23.04.24.25.43.5.43h.04c.27-.03.47-.26.46-.52-.03-.65.3-1.3.85-1.71.46-.34,1.06-.57,1.7-.66.2.01.4,0,.6-.05.26-.05.43-.29.4-.55-.03-.25-.24-.45-.51-.44Zm-2.79.9c-.33.24-.61.55-.82.9-.42-.61-1.07-1.09-1.98-1.46-.44-.17-.9-.24-1.36-.24-.18,0-.37.01-.55.03.47-.64.79-1.38.92-2.15.12-.69.07-1.39-.12-2.07.51.25,1.06.42,1.61.52.73.12,1.44.13,2.13,0-.08.2-.14.4-.18.61-.22,1.04-.06,2.03.47,2.78.17.25.37.46.61.64-.26.12-.51.26-.73.43Z"/>
																	</g>
																	<!-- g15 -->
																	<g id="blabla15"
																	   onclick="colorize(this,'g15cls1','g15cls2',null,'g15CounterValue','g15Location')">
																		<path id="g15cls1" class="cls-2"
																			  d="m20.57,62.67c-.65-.28-1.25-.49-1.74-.66-.73-.25-1.5-.48-2.29-.61-3.24-.58-6.82.31-9.48,6.52-1.87,4.36-1.24,7.64,1.87,9.76.34.23.72.44,1.14.65,1.65.77,3.86,1.28,6.12,1.28h.18c1.34-.02,2.7-.21,3.94-.64,2.55-.86,4.31-2.56,5.08-4.9,2.4-7.25-1.72-10.08-4.82-11.4Zm3.39,10.93c-.62,1.9-2.01,3.23-4.13,3.95-3.47,1.18-7.92.34-10.06-1.12-2.49-1.69-2.91-4.21-1.33-7.91,1.95-4.54,4.23-5.79,6.5-5.79,1.15,0,2.3.32,3.4.7,3.11,1.09,6.27,2.59,6.27,6.62,0,1.01-.2,2.18-.65,3.55Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m18.34,63.43c-1.1-.38-2.25-.7-3.4-.7-2.27,0-4.55,1.25-6.5,5.79-1.58,3.7-1.16,6.22,1.33,7.91,2.14,1.46,6.59,2.3,10.06,1.12,2.12-.72,3.51-2.05,4.13-3.95.45-1.37.65-2.54.65-3.55,0-4.03-3.16-5.53-6.27-6.62Zm3.03,8.99c-3.47-.05-5.2.21-5.92,2.83-.06.22-.27.36-.49.36-.04,0-.09,0-.13-.01-.27-.08-.42-.36-.35-.62.1-.35.15-.68.15-1,0-.69-.22-1.36-.68-2.22-.41-.74-1.05-1.67-1.99-2.27-.31-.11-.61-.26-.89-.44-.21-.14-.28-.42-.17-.65s.38-.33.62-.24c.31.11.6.25.87.42.7.23,1.48.27,2.19.07.93-.26,1.69-.93,2.09-1.86.11-.24.37-.36.62-.27.25.08.39.33.33.59-.19.81.1,1.75.88,2.77.97,1.27,2.92,1.54,2.94,1.54.27.04.46.27.44.54-.02.26-.22.49-.51.46Z"/>
																		<path id="g15cls2" class="cls-2"
																			  d="m21.44,71.42s-1.97-.27-2.94-1.54c-.78-1.02-1.07-1.96-.88-2.77.06-.26-.08-.51-.33-.59-.25-.09-.51.03-.62.27-.4.93-1.16,1.6-2.09,1.86-.71.2-1.49.16-2.19-.07-.27-.17-.56-.31-.87-.42-.24-.09-.51.01-.62.24s-.04.51.17.65c.28.18.58.33.89.44.94.6,1.58,1.53,1.99,2.27.46.86.68,1.53.68,2.22,0,.32-.05.65-.15,1-.07.26.08.54.35.62.04.01.09.01.13.01.22,0,.43-.14.49-.36.72-2.62,2.45-2.88,5.92-2.83.29.03.49-.2.51-.46.02-.27-.17-.5-.44-.54Zm-5.93,1.53c-.15-.61-.4-1.16-.68-1.67-.26-.47-.59-1-1.03-1.5.35-.01.71-.07,1.05-.16.7-.2,1.33-.57,1.84-1.08.16.63.5,1.28,1.01,1.94.32.42.71.75,1.12,1.01-1.38.15-2.51.53-3.31,1.46Z"/>
																	</g>
																	<!-- g16 -->
																	<g id="blabla16"
																	   onclick="colorize(this,'g16cls1','g16cls2',null,'g16CounterValue','g16Location')">
																		<path id="g16cls1" class="cls-2"
																			  d="m11.4,97.3s-.1-.01-.15-.02l-.9-.1c-.65-.09-1.33-.14-2-.15-3.6-.03-7.23,1.41-8.21,8.43-.65,4.66.89,7.75,4.58,9.19,1.51.59,3.46.94,5.45.94,2.37,0,4.8-.5,6.66-1.7,2.12-1.37,3.31-3.52,3.46-6.21.48-9.21-4.88-9.93-8.89-10.38Zm7.38,10.3c-.12,2.22-1.05,3.92-2.77,5.03-3.2,2.06-8.21,1.6-10.75.62-3.03-1.18-4.19-3.59-3.63-7.58.78-5.58,3.17-7.13,6.59-7.13.62,0,1.26.05,1.94.13l.92.11c3.99.45,8.12.91,7.7,8.82Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m11.08,98.78l-.92-.11c-.68-.08-1.32-.13-1.94-.13-3.42,0-5.81,1.55-6.59,7.13-.56,3.99.6,6.4,3.63,7.58,2.54.98,7.55,1.44,10.75-.62,1.72-1.11,2.65-2.81,2.77-5.03.42-7.91-3.71-8.37-7.7-8.82Zm4.82,9.89c-.21,0-.42-.03-.64-.08-.73-.03-1.45.2-2,.64-.59.48-.99,1.12-1.1,1.72.01.2,0,.39-.03.58-.03.24-.25.42-.49.42h-.02c-.25-.01-.46-.21-.48-.46-.02-.19-.01-.38.01-.57-.09-.8-.74-1.6-1.66-1.97-.99-.41-2.36-.41-3.67.01-.24.07-.5-.04-.61-.26-.1-.23-.03-.5.18-.64.79-.52,1.32-1.37,1.42-2.27.09-.85-.29-1.84-.97-2.52-.19-.19-.19-.49-.01-.69s.49-.22.69-.04c1.04.88,2.51,1.34,3.92,1.2.94-.09,1.73-.46,2.32-.86.41-.44.89-.82,1.31-1.16.2-.16.49-.15.67.03s.2.47.04.67c-.03.04-.52.65-1.37,1.24-.52.58-.75,1.14-.47,1.8.36.83,1.51,1.86,2.47,2.14.2.01.4.03.59.08.25.05.42.28.39.54-.02.25-.23.45-.49.45Z"/>
																		<path id="g16cls2" class="cls-2"
																			  d="m16,107.68c-.19-.05-.39-.07-.59-.08-.96-.28-2.11-1.31-2.47-2.14-.28-.66-.05-1.22.47-1.8.85-.59,1.34-1.2,1.37-1.24.16-.2.14-.49-.04-.67s-.47-.19-.67-.03c-.42.34-.9.72-1.31,1.16-.59.4-1.38.77-2.32.86-1.41.14-2.88-.32-3.92-1.2-.2-.18-.51-.16-.69.04s-.18.5.01.69c.68.68,1.06,1.67.97,2.52-.1.9-.63,1.75-1.42,2.27-.21.14-.28.41-.18.64.11.22.37.33.61.26,1.31-.42,2.68-.42,3.67-.01.92.37,1.57,1.17,1.66,1.97-.02.19-.03.38-.01.57.02.25.23.45.48.46h.02c.24,0,.46-.18.49-.42.03-.19.04-.38.03-.58.11-.6.51-1.24,1.1-1.72.55-.44,1.27-.67,2-.64.22.05.43.08.64.08.26,0,.47-.2.49-.45.03-.26-.14-.49-.39-.54Zm-3.36.77c-.39.31-.71.67-.96,1.06-.4-.64-1.03-1.17-1.81-1.49-.61-.26-1.33-.38-2.08-.38-.21,0-.41.01-.63.02.35-.53.58-1.14.64-1.76.06-.51,0-1.05-.17-1.56.91.35,1.92.49,2.9.4.48-.05.92-.15,1.33-.29-.09.43-.06.9.16,1.4.29.7.94,1.44,1.7,1.99-.39.15-.76.35-1.08.61Z"/>
																	</g>
																</g>
															</g>
														</svg>
													</div>

													<div class="prosthesisDownTeeth">
														<svg id="Layer_2" data-name="Layer 2"
															 xmlns="http://www.w3.org/2000/svg"
															 viewBox="0 0 174 115.59">
															<defs>
																<style>
																	.cls-1 {
																		fill: #fff;
																	}

																	.cls-1,
																	.cls-2 {
																		stroke-width: 0px;
																	}

																	.cls-2 {
																		fill: #8f8f8f;
																	}
																</style>
															</defs>
															<g id="Layer_1-2" data-name="Layer 1">
																<g>
																	<path class="cls-1"
																		  d="m162.95,91.08c-1.51.62-1.82,1.42-1.91,2.12-.01.02-.01.03-.01.05-.06.23-.27.43-.51.43h-.03c-.27-.02-.48-.2-.47-.46.03-.65-.3-1.3-.84-1.71-.47-.34-1.07-.57-1.7-.66-.21.01-.41,0-.61-.05-.25-.05-.42-.29-.4-.55.03-.25.25-.44.51-.44.18,0,.35.01.53.04.5-.05,1.01-.34,1.32-.78.49-.71.41-1.56.31-2-.06-.3-.26-.99-.8-1.32-.07-.04-.14-.07-.21-.11-.23-.12-.33-.41-.21-.66.11-.24.4-.35.64-.24.09.03.17.07.24.12.94.44,2,.58,3.1.39,1.13-.2,2.2-.78,2.93-1.61h.01c.19-.21.5-.23.71-.05.21.18.23.5.04.71h0c-.86.97-1.23,2.18-1.04,3.32.2,1.19.97,2.29,2.07,2.94.23.13.31.43.19.67-.13.23-.42.33-.67.21-.01,0-1.87-.89-3.19-.36Z"/>
																	<!-- g17 -->
																	<g id="blabla17"
																	   onclick="colorize(this,'g17cls1','g17cls2',null,'g17CounterValue','g17Location')">
																		<path id="g17cls1" class="cls-2"
																			  d="m98.03.59c-8.14-1.56-9.89,0-10.91,4.34-.05.21-.09.45-.12.7-.11.84-.11,1.89,0,3.04.26,2.71,1.14,5.99,2.77,8.37,1.25,1.84,2.76,2.89,4.49,3.13.29.04.58.06.87.06,2.02,0,4.12-.97,6.11-2.84,1.46-1.37,2.79-3.16,3.79-5.01.77-1.44,1.36-2.91,1.66-4.23.19-.82.27-1.59.22-2.26-.27-3.65-4.07-4.38-8.88-5.3Zm2.18,15.7c-1.34,1.27-3.43,2.7-5.74,2.39-1.29-.18-2.46-1.02-3.46-2.49-2.27-3.32-2.96-8.68-2.43-10.92.7-2.96,1.12-4.74,9.17-3.2,4.91.94,7.49,1.54,7.66,3.93.18,2.39-2.01,7.31-5.2,10.29Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m105.41,6c-.17-2.39-2.75-2.99-7.66-3.93-8.05-1.54-8.47.24-9.17,3.2-.53,2.24.16,7.6,2.43,10.92,1,1.47,2.17,2.31,3.46,2.49,2.31.31,4.4-1.12,5.74-2.39,3.19-2.98,5.38-7.9,5.2-10.29Zm-2.83.82c-.09.18-.27.28-.45.28-.08,0-.15-.02-.23-.06-4.77-2.41-9.82-2.07-9.87-2.07-.29.01-.52-.19-.54-.46-.02-.28.19-.52.46-.54.22-.02,5.37-.37,10.41,2.18.24.12.34.43.22.67Z"/>
																		<path id="g17cls2" class="cls-2"
																			  d="m102.58,6.82c-.09.18-.27.28-.45.28-.08,0-.15-.02-.23-.06-4.77-2.41-9.82-2.07-9.87-2.07-.29.01-.52-.19-.54-.46-.02-.28.19-.52.46-.54.22-.02,5.37-.37,10.41,2.18.24.12.34.43.22.67Z"/>
																	</g>
																	<!-- g18 -->
																	<g id="blabla18"
																	   onclick="colorize(this,'g18cls1','g18cls2',null,'g18CounterValue','g18Location')">
																		<path id="g18cls1" class="cls-2"
																			  d="m118.6,7.4c-7.06-3.98-9.28-3.06-11.86.67-.02.03-.04.05-.05.08-.7,1.03-1.3,2.54-1.66,4.23-.45,2.07-.54,4.39-.02,6.41.53,2.07,1.67,3.59,3.3,4.4,1.11.55,2.27.76,3.41.76,1.78,0,3.49-.52,4.83-1.09,1.79-.76,3.52-1.85,4.95-3.08.88-.76,1.65-1.56,2.24-2.37.58-.76,1-1.51,1.23-2.23,1.14-3.54-2.18-5.41-6.37-7.78Zm-2.64,14.08c-1.5.63-2.94.95-4.24.95-1.01,0-1.94-.19-2.74-.59-1.24-.62-2.09-1.77-2.51-3.43-.82-3.18.12-7.47,1.51-9.49,1.01-1.46,1.8-2.6,3.56-2.6,1.35,0,3.27.68,6.32,2.4,4.57,2.58,6.37,3.84,5.68,6-.64,1.98-3.71,5.12-7.58,6.76Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m117.86,8.72c-3.05-1.72-4.97-2.4-6.32-2.4-1.76,0-2.55,1.14-3.56,2.6-1.39,2.02-2.33,6.31-1.51,9.49.42,1.66,1.27,2.81,2.51,3.43.8.4,1.73.59,2.74.59,1.3,0,2.74-.32,4.24-.95,3.87-1.64,6.94-4.78,7.58-6.76.69-2.16-1.11-3.42-5.68-6Zm2.9,6.47s-.09.02-.14.02c-.22,0-.42-.14-.48-.36-.45-1.48-6.44-4.9-9.29-4.97-.28,0-.5-.23-.49-.51,0-.28.25-.5.51-.49,2.89.07,9.59,3.53,10.23,5.69.08.26-.07.54-.34.62Z"/>
																		<path id="g18cls2" class="cls-2"
																			  d="m120.76,15.19s-.09.02-.14.02c-.22,0-.42-.14-.48-.36-.45-1.48-6.44-4.9-9.29-4.97-.28,0-.5-.23-.49-.51,0-.28.25-.5.51-.49,2.89.07,9.59,3.53,10.23,5.69.08.26-.07.54-.34.62Z"/>
																	</g>
																	<!-- g19 -->
																	<g id="blabla19"
																	   onclick="colorize(this,'g19cls1','g19cls2',null,'g19CounterValue','g19Location')">
																		<path id="g19cls1" class="cls-2"
																			  d="m135.5,18.67c-5.77-4.56-9.18-3.43-11.76-1.26-.48.4-.92.84-1.35,1.28-.3.31-.6.68-.89,1.09-1.42,2.05-2.58,5.21-2.31,8.12.2,2.1,1.12,3.8,2.68,4.89,1.43,1.01,3.26,1.53,5.32,1.53,1.07,0,2.22-.14,3.4-.43,1.73-.41,3.41-1.11,4.82-1.96.56-.34,1.08-.7,1.55-1.07.97-.78,1.72-1.63,2.17-2.48.95-1.8,1.74-5.46-3.63-9.71Zm2.3,9.01c-1.02,1.94-4.2,3.94-7.56,4.75-1.07.26-2.1.39-3.06.39-1.74,0-3.27-.43-4.44-1.26-1.21-.85-1.89-2.13-2.05-3.8-.27-2.94,1.2-6.39,2.78-8.02,1.41-1.45,2.88-2.69,4.94-2.69,1.61,0,3.58.77,6.15,2.8,2.37,1.87,4.86,4.75,3.24,7.83Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m134.56,19.85c-2.57-2.03-4.54-2.8-6.15-2.8-2.06,0-3.53,1.24-4.94,2.69-1.58,1.63-3.05,5.08-2.78,8.02.16,1.67.84,2.95,2.05,3.8,1.17.83,2.7,1.26,4.44,1.26.96,0,1.99-.13,3.06-.39,3.36-.81,6.54-2.81,7.56-4.75,1.62-3.08-.87-5.96-3.24-7.83Zm-1.02,7.98c-.1.13-.25.2-.4.2-.11,0-.22-.03-.31-.1-.22-.17-.26-.48-.09-.7,1.05-1.39.94-3.49-.25-4.78-1.21-1.31-3.45-1.72-5.11-.93-.25.12-.55.01-.67-.24s-.01-.55.24-.66c2.06-.99,4.76-.49,6.28,1.15,1.54,1.66,1.67,4.27.31,6.06Z"/>
																		<path id="g19cls2" class="cls-2"
																			  d="m133.54,27.83c-.1.13-.25.2-.4.2-.11,0-.22-.03-.31-.1-.22-.17-.26-.48-.09-.7,1.05-1.39.94-3.49-.25-4.78-1.21-1.31-3.45-1.72-5.11-.93-.25.12-.55.01-.67-.24s-.01-.55.24-.66c2.06-.99,4.76-.49,6.28,1.15,1.54,1.66,1.67,4.27.31,6.06Z"/>
																	</g>
																	<!-- g20 -->
																	<g id="blabla20"
																	   onclick="colorize(this,'g20cls1','g20cls2','g20cls3','g20CounterValue','g20Location')">
																		<path id="g20cls1" class="cls-2"
																			  d="m149.62,34.74c-4.77-6.96-9.26-5.75-12.66-3.88-.03.02-.06.03-.09.05-.48.27-.97.61-1.46,1.02-1.89,1.56-3.7,4.07-4.26,6.78-.11.53-.16,1.04-.16,1.54,0,1.42.43,2.71,1.27,3.8,1.56,2.01,4.25,3.23,7.58,3.45.35.02.71.04,1.06.04,1.63,0,3.26-.24,4.71-.66,1.47-.44,2.76-1.06,3.7-1.83.16-.13.31-.26.45-.4,2.63-2.61,2.58-5.94-.14-9.91Zm-.92,8.84c-1.64,1.63-5.32,2.65-8.77,2.42-2.89-.19-5.19-1.21-6.48-2.87-.87-1.13-1.15-2.52-.83-4.12.6-2.94,3.01-5.69,4.98-6.79,2.96-1.65,6.48-2.89,10.78,3.37,3.04,4.44,1.64,6.68.32,7.99Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m148.38,35.59c-4.3-6.26-7.82-5.02-10.78-3.37-1.97,1.1-4.38,3.85-4.98,6.79-.32,1.6-.04,2.99.83,4.12,1.29,1.66,3.59,2.68,6.48,2.87,3.45.23,7.13-.79,8.77-2.42,1.32-1.31,2.72-3.55-.32-7.99Zm-6.68,8.4c-.09.16-.26.25-.43.25-.09,0-.17-.02-.25-.07-.24-.14-.33-.44-.19-.68.76-1.31.65-3.01-.25-4.23-.92-1.24-2.61-1.93-4.22-1.73-.27.03-.53-.16-.56-.43-.04-.28.15-.53.43-.56,1.95-.25,4.03.6,5.15,2.12,1.14,1.54,1.27,3.68.32,5.33Zm4.84-3.18c-.06.23-.26.38-.49.38-.04,0-.08,0-.12,0-1.71-.43-3.2-1.5-4.1-2.92-.91-1.44-1.19-3.19-.77-4.81.07-.27.34-.43.62-.36.26.07.43.34.35.61-.35,1.36-.11,2.82.65,4.02.76,1.21,2.04,2.11,3.49,2.48.27.07.44.34.37.61Z"/>
																		<path id="g20cls2" class="cls-2"
																			  d="m146.54,40.81c-.06.23-.26.38-.49.38-.04,0-.08,0-.12,0-1.71-.43-3.2-1.5-4.1-2.92-.91-1.44-1.19-3.19-.77-4.81.07-.27.34-.43.62-.36.26.07.43.34.35.61-.35,1.36-.11,2.82.65,4.02.76,1.21,2.04,2.11,3.49,2.48.27.07.44.34.37.61Z"/>
																		<path id="g20cls3" class="cls-2"
																			  d="m141.7,43.99c-.09.16-.26.25-.43.25-.09,0-.17-.02-.25-.07-.24-.14-.33-.44-.19-.68.76-1.31.65-3.01-.25-4.23-.92-1.24-2.61-1.93-4.22-1.73-.27.03-.53-.16-.56-.43-.04-.28.15-.53.43-.56,1.95-.25,4.03.6,5.15,2.12,1.14,1.54,1.27,3.68.32,5.33Z"/>
																	</g>
																	<!-- g21 -->
																	<g id="blabla21"
																	   onclick="colorize(this,'g21cls1','g21cls2','g21cls3','g21CounterValue','g21Location')">
																		<path id="g21cls1" class="cls-2"
																			  d="m159.32,50.06c-3.61-5.27-7.07-5.86-10.01-5.01-.97.27-1.89.7-2.74,1.18-.33.18-.65.4-.96.65-1.83,1.41-3.29,3.77-3.79,6.2-.48,2.37-.03,4.53,1.31,6.25,1.57,2.04,4.14,3.27,7.22,3.48.3.02.6.03.9.03.73,0,1.46-.06,2.17-.17,1.47-.22,2.86-.66,4.03-1.27.78-.41,1.46-.89,2.01-1.43,2.63-2.61,2.58-5.94-.14-9.91Zm-.92,8.84c-1.63,1.62-4.9,2.6-7.95,2.4-1.32-.08-2.52-.38-3.56-.88-1.04-.48-1.92-1.16-2.57-2.01-1.34-1.73-1.3-3.69-1.02-5.03.48-2.39,2.13-4.79,4-5.84,1.27-.71,2.64-1.34,4.13-1.34,1.99,0,4.19,1.13,6.65,4.71,3.04,4.43,1.64,6.68.32,7.99Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m158.08,50.91c-2.46-3.58-4.66-4.71-6.65-4.71-1.49,0-2.86.63-4.13,1.34-1.87,1.05-3.52,3.45-4,5.84-.28,1.34-.32,3.3,1.02,5.03.65.85,1.53,1.53,2.57,2.01,1.04.5,2.24.8,3.56.88,3.05.2,6.32-.78,7.95-2.4,1.32-1.31,2.72-3.56-.32-7.99Zm-6.54,6.9c-.01.08-.04.16-.1.23-.09.13-.25.2-.41.2-.08,0-.15-.02-.23-.05-.21-.12-.3-.44-.22-.68.41-1.23.25-2.44-.44-3.48-.7-1.06-2.13-1.78-3.64-1.85-.28-.01-.5-.24-.48-.52.01-.27.26-.5.52-.48,1.85.08,3.55.96,4.43,2.29.86,1.29,1.06,2.83.57,4.34Zm4.95-1.62c-.05.24-.26.4-.49.4-.04,0-.07-.01-.11-.01-1.65-.36-3.09-1.43-3.85-2.85-.77-1.44-.81-3.17-.11-4.64.12-.25.42-.36.67-.24s.36.42.24.67c-.56,1.18-.53,2.58.09,3.73.62,1.17,1.81,2.05,3.17,2.35.28.06.45.32.39.59Z"/>
																		<path id="g21cls2" class="cls-2"
																			  d="m151.54,57.81c-.01.08-.04.16-.1.23-.09.13-.25.2-.41.2-.08,0-.15-.02-.23-.05-.21-.12-.3-.44-.22-.68.41-1.23.25-2.44-.44-3.48-.7-1.06-2.13-1.78-3.64-1.85-.28-.01-.5-.24-.48-.52.01-.27.26-.5.52-.48,1.85.08,3.55.96,4.43,2.29.86,1.29,1.06,2.83.57,4.34Z"/>
																		<path id="g21cls3" class="cls-2"
																			  d="m156.49,56.19c-.05.24-.26.4-.49.4-.04,0-.07-.01-.11-.01-1.65-.36-3.09-1.43-3.85-2.85-.77-1.44-.81-3.17-.11-4.64.12-.25.42-.36.67-.24s.36.42.24.67c-.56,1.18-.53,2.58.09,3.73.62,1.17,1.81,2.05,3.17,2.35.28.06.45.32.39.59Z"/>
																	</g>
																	<!-- g22 -->
																	<g id="blabla22"
																	   onclick="colorize(this,'g22cls1','g22cls2',null,'g22CounterValue','g22Location')">
																		<path id="g22cls1" class="cls-2"
																			  d="m171.99,86.11c-1.79-6.13-4.93-7.74-8.07-7.78-1.14-.03-2.28.16-3.36.41-.78.19-1.83.44-2.93.87-2.7,1.04-5.69,3.15-5.69,8.01,0,.87.1,1.83.31,2.89.49,2.47,2.06,4.4,4.54,5.57,1.78.84,3.82,1.21,5.8,1.22h.11c1.02,0,2.02-.09,2.94-.27,1.22-.22,2.32-.58,3.19-1.05,3.47-1.85,4.53-5.17,3.16-9.87Zm-3.87,8.54c-2.44,1.31-7.23,1.71-10.69.07-2.06-.97-3.31-2.49-3.71-4.5-1.46-7.32,2.95-9.01,7.19-10.01,1.02-.24,1.99-.39,2.89-.39,2.96,0,5.25,1.59,6.74,6.71,1.18,4.03.41,6.61-2.42,8.12Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m170.54,86.53c-1.49-5.12-3.78-6.71-6.74-6.71-.9,0-1.87.15-2.89.39-4.24,1-8.65,2.69-7.19,10.01.4,2.01,1.65,3.53,3.71,4.5,3.46,1.64,8.25,1.24,10.69-.07,2.83-1.51,3.6-4.09,2.42-8.12Zm-3.73,4.7c-.13.23-.42.33-.67.21-.01,0-1.87-.89-3.19-.36-1.51.62-1.82,1.42-1.91,2.12-.01.02-.01.03-.01.05-.06.23-.27.43-.51.43h-.03c-.27-.02-.48-.2-.47-.46.03-.65-.3-1.3-.84-1.71-.47-.34-1.07-.57-1.7-.66-.21.01-.41,0-.61-.05-.25-.05-.42-.29-.4-.55.03-.25.25-.44.51-.44.18,0,.35.01.53.04.5-.05,1.01-.34,1.32-.78.49-.71.41-1.56.31-2-.06-.3-.26-.99-.8-1.32-.07-.04-.14-.07-.21-.11-.23-.12-.33-.41-.21-.66.11-.24.4-.35.64-.24.09.03.17.07.24.12.94.44,2,.58,3.1.39,1.13-.2,2.2-.78,2.93-1.61h.01c.19-.21.5-.23.71-.05.21.18.23.5.04.71h0c-.86.97-1.23,2.18-1.04,3.32.2,1.19.97,2.29,2.07,2.94.23.13.31.43.19.67Z"/>
																		<path id="g22cls2" class="cls-2"
																			  d="m166.62,90.56c-1.1-.65-1.87-1.75-2.07-2.94-.19-1.14.18-2.35,1.04-3.31h0c.19-.22.17-.54-.04-.72-.21-.18-.52-.16-.71.04h0s-.01.01-.01.01c-.73.83-1.8,1.41-2.93,1.61-1.1.19-2.16.05-3.1-.39-.07-.05-.15-.09-.24-.12-.24-.11-.53,0-.64.24-.12.25-.02.54.21.66.07.04.14.07.21.11.54.33.74,1.02.8,1.32.1.44.18,1.29-.31,2-.31.44-.82.73-1.32.78-.18-.03-.35-.04-.53-.04-.26,0-.48.19-.51.44-.02.26.15.5.4.55.2.05.4.06.61.05.63.09,1.23.32,1.7.66.54.41.87,1.06.84,1.71-.01.26.2.44.47.46h.03c.24,0,.45-.2.51-.43,0-.02,0-.03.01-.05.09-.7.4-1.5,1.91-2.12,1.32-.53,3.18.35,3.19.36.25.12.54.02.67-.21.12-.24.04-.54-.19-.67Zm-4.04-.41c-.91.37-1.56.84-1.99,1.47-.2-.35-.48-.66-.82-.91-.22-.17-.46-.31-.73-.43.24-.18.45-.39.61-.64.53-.75.69-1.74.48-2.78-.05-.21-.11-.41-.19-.61.69.12,1.4.11,2.13,0,.56-.1,1.1-.27,1.61-.52-.19.68-.24,1.38-.12,2.07.13.77.45,1.51.92,2.15-.61-.07-1.29-.04-1.9.21Z"/>
																	</g>
																	<!-- g23 -->
																	<g id="blabla23"
																	   onclick="colorize(this,'g23cls1','g23cls2',null,'g23CounterValue','g23Location')">
																		<path id="g23cls1" class="cls-2"
																			  d="m166.93,67.92c-2.66-6.21-6.24-7.1-9.48-6.52-.79.13-1.56.36-2.29.61-.49.17-1.09.38-1.74.66-2.45,1.05-5.53,3.04-5.53,7.41,0,1.15.21,2.48.71,3.99.77,2.34,2.53,4.04,5.08,4.9,1.26.43,2.61.62,3.95.64h.17c2.25,0,4.47-.51,6.12-1.28.42-.21.81-.42,1.15-.65,3.1-2.12,3.73-5.4,1.86-9.76Zm-2.71,8.51c-2.14,1.46-6.59,2.3-10.05,1.12-2.12-.72-3.52-2.05-4.14-3.95-2.24-6.79,1.73-8.81,5.63-10.17,3.25-1.13,6.95-1.76,9.89,5.09,1.59,3.7,1.16,6.22-1.33,7.91Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m165.55,68.52c-2.94-6.85-6.64-6.22-9.89-5.09-3.9,1.36-7.87,3.38-5.63,10.17.62,1.9,2.02,3.23,4.14,3.95,3.46,1.18,7.91.34,10.05-1.12,2.49-1.69,2.92-4.21,1.33-7.91Zm-2.62.53c-.28.18-.58.33-.89.44-.93.6-1.58,1.53-1.99,2.27-.68,1.27-.83,2.14-.53,3.22.01.05.02.09.02.14,0,.21-.15.41-.37.48-.04.01-.09.01-.13.01-.22,0-.42-.14-.48-.36-.68-2.45-2.23-2.84-5.26-2.84-.22,0-.44.01-.67.01-.25.03-.49-.2-.51-.46-.02-.26.17-.5.44-.54.02,0,1.98-.28,2.94-1.54.78-1.02,1.08-1.96.88-2.77-.06-.26.08-.51.33-.59.25-.09.52.03.62.27.4.93,1.16,1.61,2.09,1.86.71.2,1.49.17,2.19-.07.27-.17.56-.31.87-.42.24-.09.51.01.62.24.12.23.04.51-.17.65Z"/>
																		<path id="g23cls2" class="cls-2"
																			  d="m163.1,68.4c-.11-.23-.38-.33-.62-.24-.31.11-.6.25-.87.42-.7.24-1.48.27-2.19.07-.93-.25-1.69-.93-2.09-1.86-.1-.24-.37-.36-.62-.27-.25.08-.39.33-.33.59.2.81-.1,1.75-.88,2.77-.96,1.26-2.92,1.54-2.94,1.54-.27.04-.46.28-.44.54.02.26.26.49.51.46.23,0,.45-.01.67-.01,3.03,0,4.58.39,5.26,2.84.06.22.26.36.48.36.04,0,.09,0,.13-.01.22-.07.37-.27.37-.48,0-.05-.01-.09-.02-.14-.3-1.08-.15-1.95.53-3.22.41-.74,1.06-1.67,1.99-2.27.31-.11.61-.26.89-.44.21-.14.29-.42.17-.65Zm-3.93,2.88c-.28.51-.53,1.06-.67,1.66-.81-.92-1.94-1.3-3.32-1.45.41-.26.8-.59,1.12-1.01.51-.66.85-1.31,1.02-1.94.5.51,1.13.88,1.83,1.08.35.09.7.15,1.05.16-.43.5-.77,1.03-1.03,1.5Z"/>
																	</g>
																	<!-- g24 -->
																	<g id="blabla24"
																	   onclick="colorize(this,'g24cls1','g24cls2',null,'g24CounterValue','g24Location')">
																		<path id="g24cls1" class="cls-2"
																			  d="m173.85,105.46c-.98-7.02-4.61-8.46-8.21-8.43-.67.01-1.34.07-1.99.15l-.9.1s-.1.01-.16.02c-3.84.43-8.91,1.11-8.91,9.23,0,.37.01.75.03,1.15.14,2.69,1.34,4.84,3.46,6.21,1.86,1.2,4.29,1.7,6.65,1.7,1.99,0,3.94-.35,5.46-.94,3.68-1.44,5.23-4.53,4.57-9.19Zm-5.12,7.79c-2.54.98-7.55,1.44-10.75-.62-1.72-1.11-2.65-2.81-2.77-5.03-.42-7.91,3.71-8.37,7.71-8.82l.92-.11c.67-.08,1.32-.13,1.93-.13,3.43,0,5.82,1.55,6.59,7.13.56,3.99-.59,6.4-3.63,7.58Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m172.36,105.67c-.77-5.58-3.16-7.13-6.59-7.13-.61,0-1.26.05-1.93.13l-.92.11c-4,.45-8.13.91-7.71,8.82.12,2.22,1.05,3.92,2.77,5.03,3.2,2.06,8.21,1.6,10.75.62,3.04-1.18,4.19-3.59,3.63-7.58Zm-3.57,3.03c-.11.22-.37.33-.61.26-1.31-.42-2.68-.42-3.67-.01-.92.37-1.57,1.17-1.66,1.97.02.19.03.38.01.57-.02.25-.22.45-.48.46h-.02c-.24,0-.45-.18-.49-.42-.03-.19-.04-.38-.03-.58-.11-.6-.51-1.24-1.1-1.72-.55-.44-1.27-.67-2-.64-.21.05-.42.09-.64.08-.25,0-.47-.2-.49-.45-.03-.26.14-.49.39-.54.19-.05.39-.07.59-.08.97-.28,2.11-1.31,2.47-2.14.28-.66.05-1.22-.47-1.8-.85-.59-1.33-1.2-1.37-1.24-.16-.2-.14-.49.04-.67.18-.18.47-.19.67-.03.42.34.9.72,1.31,1.16.6.4,1.39.77,2.32.86,1.42.14,2.88-.32,3.92-1.2.2-.18.51-.16.69.04.19.2.18.5-.01.69-.68.68-1.06,1.67-.96,2.52.09.9.62,1.75,1.41,2.27.21.14.29.41.18.64Z"/>
																		<path id="g24cls2" class="cls-2"
																			  d="m168.61,108.06c-.79-.52-1.32-1.37-1.41-2.27-.1-.85.28-1.84.96-2.52.19-.19.2-.49.01-.69-.18-.2-.49-.22-.69-.04-1.04.88-2.5,1.34-3.92,1.2-.93-.09-1.72-.46-2.32-.86-.41-.44-.89-.82-1.31-1.16-.2-.16-.49-.15-.67.03-.18.18-.2.47-.04.67.04.04.52.65,1.37,1.24.52.58.75,1.14.47,1.8-.36.83-1.5,1.86-2.47,2.14-.2.01-.4.03-.59.08-.25.05-.42.28-.39.54.02.25.24.45.49.45.22.01.43-.03.64-.08.73-.03,1.45.2,2,.64.59.48.99,1.12,1.1,1.72-.01.2,0,.39.03.58.04.24.25.42.49.42h.02c.26-.01.46-.21.48-.46.02-.19,0-.38-.01-.57.09-.8.74-1.6,1.66-1.97.99-.41,2.36-.41,3.67.01.24.07.5-.04.61-.26.11-.23.03-.5-.18-.64Zm-4.48-.04c-.78.32-1.41.85-1.81,1.49-.25-.39-.57-.75-.96-1.06-.32-.26-.68-.46-1.08-.61.76-.55,1.41-1.29,1.7-1.99.22-.5.25-.97.16-1.4.41.14.85.24,1.33.29.98.09,1.99-.05,2.9-.4-.17.51-.23,1.05-.17,1.56.07.62.29,1.23.64,1.76-.98-.08-1.93.03-2.71.36Z"/>
																	</g>
																	<!-- g25 -->
																	<g id="blabla25"
																	   onclick="colorize(this,'g25cls1','g25cls2',null,'g25CounterValue','g25Location')">
																		<path id="g25cls1" class="cls-2"
																			  d="m87,5.63c-.03-.25-.07-.49-.12-.7-1.03-4.34-2.77-5.9-10.92-4.34-4.81.92-8.61,1.65-8.88,5.3-.05.67.03,1.44.22,2.26.3,1.31.88,2.77,1.66,4.2.99,1.86,2.32,3.67,3.79,5.04,1.99,1.87,4.09,2.84,6.12,2.84.29,0,.57-.02.86-.06,1.73-.24,3.24-1.29,4.5-3.13,1.63-2.39,2.51-5.66,2.77-8.37.11-1.15.11-2.2,0-3.04Zm-4.02,10.56c-1,1.47-2.16,2.31-3.46,2.49-2.31.31-4.39-1.12-5.74-2.39-3.19-2.98-5.37-7.9-5.2-10.29.18-2.39,2.75-2.99,7.66-3.93,2.03-.39,3.58-.57,4.76-.57,3.52,0,3.89,1.56,4.41,3.77.53,2.24-.15,7.6-2.43,10.92Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m85.41,5.27c-.52-2.21-.89-3.77-4.41-3.77-1.18,0-2.73.18-4.76.57-4.91.94-7.48,1.54-7.66,3.93-.17,2.39,2.01,7.31,5.2,10.29,1.35,1.27,3.43,2.7,5.74,2.39,1.3-.18,2.46-1.02,3.46-2.49,2.28-3.32,2.96-8.68,2.43-10.92Zm-3.44-.3s-5.1-.34-9.87,2.07c-.08.04-.15.06-.23.06-.18,0-.36-.1-.45-.28-.12-.24-.02-.55.22-.67,5.04-2.55,10.19-2.2,10.41-2.18.27.02.48.26.46.54-.02.27-.26.47-.54.46Z"/>
																		<path id="g25cls2" class="cls-2"
																			  d="m82.51,4.51c-.02.27-.26.47-.54.46-.04,0-5.1-.34-9.87,2.07-.08.04-.15.06-.23.06-.18,0-.36-.1-.45-.28-.12-.24-.02-.55.22-.67,5.04-2.55,10.19-2.2,10.41-2.18.27.02.48.26.46.54Z"/>
																	</g>
																	<!-- g26 -->
																	<g id="blabla26"
																	   onclick="colorize(this,'g26cls1','g26cls2',null,'g26CounterValue','g26Location')">
																		<path id="g26cls1" class="cls-2"
																			  d="m68.96,12.35c-.36-1.67-.96-3.18-1.66-4.2-.01-.03-.03-.05-.05-.08-2.58-3.73-4.8-4.65-11.85-.67-4.2,2.37-7.51,4.24-6.38,7.78.23.72.65,1.47,1.23,2.23.59.81,1.36,1.61,2.24,2.37,1.43,1.23,3.15,2.32,4.95,3.08,1.34.57,3.06,1.09,4.83,1.09,1.14,0,2.31-.21,3.41-.76,1.63-.81,2.77-2.33,3.31-4.4.52-2.02.42-4.36-.03-6.44Zm-1.43,6.06c-.43,1.66-1.27,2.81-2.52,3.43-1.81.9-4.29.77-6.98-.36-3.86-1.64-6.94-4.78-7.57-6.76-.7-2.16,1.1-3.42,5.68-6,3.04-1.72,4.97-2.4,6.32-2.4,1.75,0,2.54,1.14,3.55,2.6,1.4,2.02,2.34,6.31,1.52,9.49Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m66.01,8.92c-1.01-1.46-1.8-2.6-3.55-2.6-1.35,0-3.28.68-6.32,2.4-4.58,2.58-6.38,3.84-5.68,6,.63,1.98,3.71,5.12,7.57,6.76,2.69,1.13,5.17,1.26,6.98.36,1.25-.62,2.09-1.77,2.52-3.43.82-3.18-.12-7.47-1.52-9.49Zm-2.86.96c-2.84.07-8.84,3.49-9.29,4.97-.06.22-.26.36-.48.36-.05,0-.09-.01-.14-.02-.27-.08-.42-.36-.34-.62.65-2.16,7.35-5.62,10.23-5.69.26,0,.5.21.51.49.01.28-.21.51-.49.51Z"/>
																		<path id="g26cls2" class="cls-2"
																			  d="m63.64,9.37c.01.28-.21.51-.49.51-2.84.07-8.84,3.49-9.29,4.97-.06.22-.26.36-.48.36-.05,0-.09-.01-.14-.02-.27-.08-.42-.36-.34-.62.65-2.16,7.35-5.62,10.23-5.69.26,0,.5.21.51.49Z"/>
																	</g>
																	<!-- g27 -->
																	<g id="blabla27"
																	   onclick="colorize(this,'g27cls1','g27cls2',null,'g27CounterValue','g27Location')">
																		<path id="g27cls1" class="cls-2"
																			  d="m52.49,19.78c-.29-.41-.59-.78-.89-1.09-.43-.44-.87-.88-1.35-1.28-2.58-2.17-5.98-3.3-11.75,1.26-5.37,4.25-4.58,7.91-3.64,9.71.44.85,1.19,1.69,2.15,2.47.49.38,1.02.75,1.6,1.1,1.42.84,3.08,1.53,4.8,1.94,1.18.29,2.32.43,3.4.43,2.06,0,3.88-.52,5.31-1.53,1.56-1.1,2.49-2.79,2.68-4.89.27-2.92-.88-6.08-2.31-8.12Zm.81,7.98c-.15,1.67-.84,2.95-2.04,3.8-1.82,1.29-4.49,1.6-7.5.87-3.37-.81-6.55-2.81-7.56-4.75-1.62-3.08.86-5.96,3.23-7.83,2.57-2.03,4.54-2.8,6.16-2.8,2.05,0,3.53,1.24,4.93,2.69,1.58,1.63,3.06,5.08,2.78,8.02Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m50.52,19.74c-1.4-1.45-2.88-2.69-4.93-2.69-1.62,0-3.59.77-6.16,2.8-2.37,1.87-4.85,4.75-3.23,7.83,1.01,1.94,4.19,3.94,7.56,4.75,3.01.73,5.68.42,7.5-.87,1.2-.85,1.89-2.13,2.04-3.8.28-2.94-1.2-6.39-2.78-8.02Zm-3.23,1.54c-.12.25-.42.36-.67.24-1.65-.79-3.9-.38-5.11.93-1.19,1.29-1.3,3.39-.25,4.78.17.22.13.53-.09.7-.09.07-.2.1-.31.1-.15,0-.3-.07-.4-.2-1.36-1.79-1.22-4.4.31-6.06,1.52-1.64,4.22-2.14,6.28-1.15.25.11.36.41.24.66Z"/>
																		<path id="g27cls2" class="cls-2"
																			  d="m47.29,21.28c-.12.25-.42.36-.67.24-1.65-.79-3.9-.38-5.11.93-1.19,1.29-1.3,3.39-.25,4.78.17.22.13.53-.09.7-.09.07-.2.1-.31.1-.15,0-.3-.07-.4-.2-1.36-1.79-1.22-4.4.31-6.06,1.52-1.64,4.22-2.14,6.28-1.15.25.11.36.41.24.66Z"/>
																	</g>
																	<!-- g28 -->
																	<g id="blabla28"
																	   onclick="colorize(this,'g28cls1','g28cls2','g28cls3','g28CounterValue','g28Location')">
																		<path id="g28cls1" class="cls-2"
																			  d="m42.84,38.71c-.55-2.7-2.36-5.2-4.23-6.76-.5-.42-1-.77-1.48-1.04-.04-.02-.08-.04-.12-.06-3.39-1.87-7.88-3.05-12.64,3.89-2.72,3.97-2.77,7.3-.14,9.91.14.14.29.27.45.39.95.78,2.24,1.41,3.72,1.84,1.45.43,3.06.66,4.69.66.36,0,.71-.02,1.07-.04,1.67-.11,3.17-.47,4.45-1.05,1.28-.59,2.34-1.39,3.12-2.4,1.14-1.47,1.53-3.32,1.11-5.34Zm-2.3,4.42c-1.29,1.66-3.59,2.68-6.48,2.87-3.44.23-7.12-.79-8.77-2.42-1.32-1.31-2.72-3.55.33-7.99,2.45-3.58,4.65-4.71,6.64-4.71,1.49,0,2.87.64,4.13,1.34,1.97,1.1,4.38,3.85,4.98,6.79.09.43.13.85.13,1.25,0,1.08-.32,2.05-.96,2.87Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m41.37,39.01c-.6-2.94-3.01-5.69-4.98-6.79-1.26-.7-2.64-1.34-4.13-1.34-1.99,0-4.19,1.13-6.64,4.71-3.05,4.44-1.65,6.68-.33,7.99,1.65,1.63,5.33,2.65,8.77,2.42,2.89-.19,5.19-1.21,6.48-2.87.64-.82.96-1.79.96-2.87,0-.4-.04-.82-.13-1.25Zm-13.3,2.17s-.08,0-.12,0c-.22,0-.43-.15-.49-.38-.06-.27.1-.54.37-.61,1.46-.37,2.73-1.27,3.5-2.48.76-1.2.99-2.66.64-4.02-.07-.27.09-.54.36-.61.26-.07.54.09.61.36.42,1.62.14,3.37-.77,4.81-.9,1.42-2.39,2.49-4.1,2.92Zm9.57-3.65c-1.59-.2-3.3.49-4.22,1.73-.9,1.22-1,2.92-.25,4.23.14.24.06.54-.18.68-.08.05-.17.07-.25.07-.18,0-.35-.09-.44-.25-.95-1.65-.82-3.79.32-5.33,1.13-1.52,3.2-2.37,5.15-2.12.28.03.47.28.44.56-.04.27-.3.46-.57.43Z"/>
																		<path id="g28cls2" class="cls-2"
																			  d="m32.17,38.26c-.9,1.42-2.39,2.49-4.1,2.92-.04,0-.08,0-.12,0-.22,0-.43-.15-.49-.38-.06-.27.1-.54.37-.61,1.46-.37,2.73-1.27,3.5-2.48.76-1.2.99-2.66.64-4.02-.07-.27.09-.54.36-.61.26-.07.54.09.61.36.42,1.62.14,3.37-.77,4.81Z"/>
																		<path id="g28cls3" class="cls-2"
																			  d="m38.21,37.1c-.04.27-.3.46-.57.43-1.59-.2-3.3.49-4.22,1.73-.9,1.22-1,2.92-.25,4.23.14.24.06.54-.18.68-.08.05-.17.07-.25.07-.18,0-.35-.09-.44-.25-.95-1.65-.82-3.79.32-5.33,1.13-1.52,3.2-2.37,5.15-2.12.28.03.47.28.44.56Z"/>
																	</g>
																	<!-- g29 -->
																	<g id="blabla29"
																	   onclick="colorize(this,'g29cls1','g29cls2','g29cls3','g29CounterValue','g29Location')">
																		<path id="g29cls1" class="cls-2"
																			  d="m32.17,53.08c-.49-2.42-1.95-4.78-3.77-6.2-.31-.25-.64-.46-.97-.65-.85-.48-1.77-.91-2.75-1.19-2.94-.84-6.39-.25-10.01,5.02-2.72,3.97-2.77,7.3-.14,9.91.55.54,1.23,1.02,2.01,1.43,1.17.61,2.57,1.05,4.03,1.27.71.11,1.44.17,2.17.17.3,0,.6-.01.9-.03,1.54-.11,2.95-.47,4.18-1.05,1.22-.59,2.26-1.41,3.05-2.43,1.33-1.72,1.78-3.88,1.3-6.25Zm-2.49,5.33c-1.31,1.69-3.49,2.72-6.14,2.89-3.05.2-6.32-.78-7.95-2.4-1.32-1.31-2.72-3.56.32-7.99,2.46-3.58,4.66-4.71,6.65-4.71,1.49,0,2.86.64,4.13,1.34,1.87,1.05,3.52,3.45,4.01,5.84.27,1.34.32,3.3-1.02,5.03Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m30.7,53.38c-.49-2.39-2.14-4.79-4.01-5.84-1.27-.7-2.64-1.34-4.13-1.34-1.99,0-4.19,1.13-6.65,4.71-3.04,4.43-1.64,6.68-.32,7.99,1.63,1.62,4.9,2.6,7.95,2.4,2.65-.17,4.83-1.2,6.14-2.89,1.34-1.73,1.29-3.69,1.02-5.03Zm-12.59,3.2s-.07.01-.11.01c-.23,0-.44-.16-.49-.4-.06-.27.12-.53.39-.59,1.37-.3,2.55-1.18,3.18-2.35.61-1.15.65-2.55.08-3.73-.11-.25-.01-.55.24-.67s.55-.01.67.24c.7,1.47.66,3.2-.11,4.64-.76,1.42-2.2,2.49-3.85,2.85Zm9.4-4.4c-1.51.07-2.94.79-3.65,1.85-.69,1.04-.84,2.25-.44,3.48.08.24-.01.56-.22.68-.07.03-.15.05-.23.05-.16,0-.31-.07-.41-.2-.06-.07-.09-.15-.1-.23-.49-1.54-.28-3.07.57-4.34.89-1.33,2.59-2.21,4.43-2.29.28-.02.51.21.53.48.01.28-.21.51-.48.52Z"/>
																		<path id="g29cls2" class="cls-2"
																			  d="m27.99,51.66c.01.28-.21.51-.48.52-1.51.07-2.94.79-3.65,1.85-.69,1.04-.84,2.25-.44,3.48.08.24-.01.56-.22.68-.07.03-.15.05-.23.05-.16,0-.31-.07-.41-.2-.06-.07-.09-.15-.1-.23-.49-1.54-.28-3.07.57-4.34.89-1.33,2.59-2.21,4.43-2.29.28-.02.51.21.53.48Z"/>
																		<path id="g29cls3" class="cls-2"
																			  d="m21.96,53.73c-.76,1.42-2.2,2.49-3.85,2.85-.03,0-.07.01-.11.01-.23,0-.44-.16-.49-.4-.06-.27.12-.53.39-.59,1.37-.3,2.55-1.18,3.18-2.35.61-1.15.65-2.55.08-3.73-.11-.25-.01-.55.24-.67s.55-.01.67.24c.7,1.47.66,3.2-.11,4.64Z"/>
																	</g>
																	<!-- g30 -->
																	<g id="blabla30"
																	   onclick="colorize(this,'g30cls1','g30cls2',null,'g30CounterValue','g30Location')">
																		<path id="g30cls1" class="cls-2"
																			  d="m16.37,79.61c-1.1-.43-2.16-.68-2.94-.87-1.08-.25-2.22-.44-3.36-.41-3.14.04-6.28,1.65-8.07,7.78-1.37,4.7-.31,8.02,3.16,9.87.87.47,1.97.83,3.19,1.05.92.18,1.92.27,2.94.27h.11c1.98-.01,4.03-.38,5.81-1.22,2.48-1.17,4.05-3.1,4.54-5.57,1.38-6.95-2.2-9.67-5.38-10.9Zm3.9,10.61c-.4,2.01-1.65,3.53-3.7,4.5-3.47,1.64-8.25,1.24-10.7-.07-2.83-1.51-3.6-4.09-2.42-8.12,1.49-5.12,3.79-6.71,6.74-6.71.91,0,1.87.15,2.89.39,3.68.87,7.48,2.25,7.48,7.36,0,.79-.09,1.67-.29,2.65Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m13.08,80.21c-1.02-.24-1.98-.39-2.89-.39-2.95,0-5.25,1.59-6.74,6.71-1.18,4.03-.41,6.61,2.42,8.12,2.45,1.31,7.23,1.71,10.7.07,2.05-.97,3.3-2.49,3.7-4.5.2-.98.29-1.86.29-2.65,0-5.11-3.8-6.49-7.48-7.36Zm4.05,10.59c-.2.05-.4.06-.6.05-.64.09-1.24.32-1.7.66-.55.41-.88,1.06-.85,1.71,0,.26-.19.49-.46.52h-.04c-.25,0-.46-.19-.5-.43-.11-.81-.42-1.61-1.93-2.23-1.32-.53-3.17.35-3.19.36-.24.12-.54.02-.66-.21-.13-.24-.05-.54.18-.67,1.1-.65,1.87-1.75,2.07-2.94.2-1.14-.18-2.35-1.03-3.31h-.01c-.19-.22-.17-.54.04-.72s.52-.16.71.04c0,.01.01.01.01.02.73.82,1.8,1.4,2.93,1.6,1.1.19,2.16.05,3.1-.39.07-.05.15-.09.24-.12.24-.1.53,0,.65.24.11.25.02.54-.22.66-.07.04-.14.07-.21.11-.54.33-.74,1.02-.8,1.32-.1.44-.18,1.29.31,2,.31.44.82.73,1.32.78.18-.03.35-.04.53-.04.27,0,.48.19.51.44.03.26-.14.5-.4.55Z"/>
																		<path id="g30cls2" class="cls-2"
																			  d="m17.02,89.81c-.18,0-.35.01-.53.04-.5-.05-1.01-.34-1.32-.78-.49-.71-.41-1.56-.31-2,.06-.3.26-.99.8-1.32.07-.04.14-.07.21-.11.24-.12.33-.41.22-.66-.12-.24-.41-.34-.65-.24-.09.03-.17.07-.24.12-.94.44-2,.58-3.1.39-1.13-.2-2.2-.78-2.93-1.6,0-.01-.01-.01-.01-.02-.19-.2-.5-.22-.71-.04s-.23.5-.04.71h0s.01,0,.01,0c.85.96,1.23,2.17,1.03,3.31-.2,1.19-.97,2.29-2.07,2.94-.23.13-.31.43-.18.67.12.23.42.33.66.21.02,0,1.87-.89,3.19-.36,1.51.62,1.82,1.42,1.93,2.23.04.24.25.43.5.43h.04c.27-.03.47-.26.46-.52-.03-.65.3-1.3.85-1.71.46-.34,1.06-.57,1.7-.66.2.01.4,0,.6-.05.26-.05.43-.29.4-.55-.03-.25-.24-.45-.51-.44Zm-2.79.9c-.33.24-.61.55-.82.9-.42-.61-1.07-1.09-1.98-1.46-.44-.17-.9-.24-1.36-.24-.18,0-.37.01-.55.03.47-.64.79-1.38.92-2.15.12-.69.07-1.39-.12-2.07.51.25,1.06.42,1.61.52.73.12,1.44.13,2.13,0-.08.2-.14.4-.18.61-.22,1.04-.06,2.03.47,2.78.17.25.37.46.61.64-.26.12-.51.26-.73.43Z"/>
																	</g>
																	<!-- g31 -->
																	<g id="blabla31"
																	   onclick="colorize(this,'g31cls1','g31cls2',null,'g31CounterValue','g31Location')">
																		<path id="g31cls1" class="cls-2"
																			  d="m20.57,62.67c-.65-.28-1.25-.49-1.74-.66-.73-.25-1.5-.48-2.29-.61-3.24-.58-6.82.31-9.48,6.52-1.87,4.36-1.24,7.64,1.87,9.76.34.23.72.44,1.14.65,1.65.77,3.86,1.28,6.12,1.28h.18c1.34-.02,2.7-.21,3.94-.64,2.55-.86,4.31-2.56,5.08-4.9,2.4-7.25-1.72-10.08-4.82-11.4Zm3.39,10.93c-.62,1.9-2.01,3.23-4.13,3.95-3.47,1.18-7.92.34-10.06-1.12-2.49-1.69-2.91-4.21-1.33-7.91,1.95-4.54,4.23-5.79,6.5-5.79,1.15,0,2.3.32,3.4.7,3.11,1.09,6.27,2.59,6.27,6.62,0,1.01-.2,2.18-.65,3.55Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m18.34,63.43c-1.1-.38-2.25-.7-3.4-.7-2.27,0-4.55,1.25-6.5,5.79-1.58,3.7-1.16,6.22,1.33,7.91,2.14,1.46,6.59,2.3,10.06,1.12,2.12-.72,3.51-2.05,4.13-3.95.45-1.37.65-2.54.65-3.55,0-4.03-3.16-5.53-6.27-6.62Zm3.03,8.99c-3.47-.05-5.2.21-5.92,2.83-.06.22-.27.36-.49.36-.04,0-.09,0-.13-.01-.27-.08-.42-.36-.35-.62.1-.35.15-.68.15-1,0-.69-.22-1.36-.68-2.22-.41-.74-1.05-1.67-1.99-2.27-.31-.11-.61-.26-.89-.44-.21-.14-.28-.42-.17-.65s.38-.33.62-.24c.31.11.6.25.87.42.7.23,1.48.27,2.19.07.93-.26,1.69-.93,2.09-1.86.11-.24.37-.36.62-.27.25.08.39.33.33.59-.19.81.1,1.75.88,2.77.97,1.27,2.92,1.54,2.94,1.54.27.04.46.27.44.54-.02.26-.22.49-.51.46Z"/>
																		<path id="g31cls2" class="cls-2"
																			  d="m21.44,71.42s-1.97-.27-2.94-1.54c-.78-1.02-1.07-1.96-.88-2.77.06-.26-.08-.51-.33-.59-.25-.09-.51.03-.62.27-.4.93-1.16,1.6-2.09,1.86-.71.2-1.49.16-2.19-.07-.27-.17-.56-.31-.87-.42-.24-.09-.51.01-.62.24s-.04.51.17.65c.28.18.58.33.89.44.94.6,1.58,1.53,1.99,2.27.46.86.68,1.53.68,2.22,0,.32-.05.65-.15,1-.07.26.08.54.35.62.04.01.09.01.13.01.22,0,.43-.14.49-.36.72-2.62,2.45-2.88,5.92-2.83.29.03.49-.2.51-.46.02-.27-.17-.5-.44-.54Zm-5.93,1.53c-.15-.61-.4-1.16-.68-1.67-.26-.47-.59-1-1.03-1.5.35-.01.71-.07,1.05-.16.7-.2,1.33-.57,1.84-1.08.16.63.5,1.28,1.01,1.94.32.42.71.75,1.12,1.01-1.38.15-2.51.53-3.31,1.46Z"/>
																	</g>
																	<!-- g32 -->
																	<g id="blabla32"
																	   onclick="colorize(this,'g32cls1','g32cls2',null,'g32CounterValue','g32Location')">
																		<path id="g32cls1" class="cls-2"
																			  d="m11.4,97.3s-.1-.01-.15-.02l-.9-.1c-.65-.09-1.33-.14-2-.15-3.6-.03-7.23,1.41-8.21,8.43-.65,4.66.89,7.75,4.58,9.19,1.51.59,3.46.94,5.45.94,2.37,0,4.8-.5,6.66-1.7,2.12-1.37,3.31-3.52,3.46-6.21.48-9.21-4.88-9.93-8.89-10.38Zm7.38,10.3c-.12,2.22-1.05,3.92-2.77,5.03-3.2,2.06-8.21,1.6-10.75.62-3.03-1.18-4.19-3.59-3.63-7.58.78-5.58,3.17-7.13,6.59-7.13.62,0,1.26.05,1.94.13l.92.11c3.99.45,8.12.91,7.7,8.82Z"/>
																		<path class="cls-1 hoverEx"
																			  d="m11.08,98.78l-.92-.11c-.68-.08-1.32-.13-1.94-.13-3.42,0-5.81,1.55-6.59,7.13-.56,3.99.6,6.4,3.63,7.58,2.54.98,7.55,1.44,10.75-.62,1.72-1.11,2.65-2.81,2.77-5.03.42-7.91-3.71-8.37-7.7-8.82Zm4.82,9.89c-.21,0-.42-.03-.64-.08-.73-.03-1.45.2-2,.64-.59.48-.99,1.12-1.1,1.72.01.2,0,.39-.03.58-.03.24-.25.42-.49.42h-.02c-.25-.01-.46-.21-.48-.46-.02-.19-.01-.38.01-.57-.09-.8-.74-1.6-1.66-1.97-.99-.41-2.36-.41-3.67.01-.24.07-.5-.04-.61-.26-.1-.23-.03-.5.18-.64.79-.52,1.32-1.37,1.42-2.27.09-.85-.29-1.84-.97-2.52-.19-.19-.19-.49-.01-.69s.49-.22.69-.04c1.04.88,2.51,1.34,3.92,1.2.94-.09,1.73-.46,2.32-.86.41-.44.89-.82,1.31-1.16.2-.16.49-.15.67.03s.2.47.04.67c-.03.04-.52.65-1.37,1.24-.52.58-.75,1.14-.47,1.8.36.83,1.51,1.86,2.47,2.14.2.01.4.03.59.08.25.05.42.28.39.54-.02.25-.23.45-.49.45Z"/>
																		<path id="g32cls2" class="cls-2"
																			  d="m16,107.68c-.19-.05-.39-.07-.59-.08-.96-.28-2.11-1.31-2.47-2.14-.28-.66-.05-1.22.47-1.8.85-.59,1.34-1.2,1.37-1.24.16-.2.14-.49-.04-.67s-.47-.19-.67-.03c-.42.34-.9.72-1.31,1.16-.59.4-1.38.77-2.32.86-1.41.14-2.88-.32-3.92-1.2-.2-.18-.51-.16-.69.04s-.18.5.01.69c.68.68,1.06,1.67.97,2.52-.1.9-.63,1.75-1.42,2.27-.21.14-.28.41-.18.64.11.22.37.33.61.26,1.31-.42,2.68-.42,3.67-.01.92.37,1.57,1.17,1.66,1.97-.02.19-.03.38-.01.57.02.25.23.45.48.46h.02c.24,0,.46-.18.49-.42.03-.19.04-.38.03-.58.11-.6.51-1.24,1.1-1.72.55-.44,1.27-.67,2-.64.22.05.43.08.64.08.26,0,.47-.2.49-.45.03-.26-.14-.49-.39-.54Zm-3.36.77c-.39.31-.71.67-.96,1.06-.4-.64-1.03-1.17-1.81-1.49-.61-.26-1.33-.38-2.08-.38-.21,0-.41.01-.63.02.35-.53.58-1.14.64-1.76.06-.51,0-1.05-.17-1.56.91.35,1.92.49,2.9.4.48-.05.92-.15,1.33-.29-.09.43-.06.9.16,1.4.29.7.94,1.44,1.7,1.99-.39.15-.76.35-1.08.61Z"/>
																	</g>
																</g>
															</g>
														</svg>
													</div>
												</div>
											</div>
											<div class="col-md-7"></div>
										</div>

									</div>
								</div>

								<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="gallery-tab-pane"
									 role="tabpanel" aria-labelledby="gallery-tab" tabindex="0">

									<!-- the table start _______________________________________________________________________________ -->
									<div class="table-responsive">
										<table class="table text-nowrap" id="turnsTable">
											<thead class="tableHead">
											<tr>
												<th scope="col">#</th>
												<th scope="col"><?= $ci->lang('reference doctor') ?></th>
												<th scope="col"><?= $ci->lang('date') ?></th>
												<th scope="col"><?= $ci->lang('hour') ?></th>
												<th scope="col"><?= $ci->lang('paid amount') ?></th>
												<th scope="col"><?= $ci->lang('actions') ?></th>
											</tr>
											</thead>
											<tbody>
											<?php $i = 1;
											foreach ($turns as $turn) : ?>
												<tr id="<?= $turn['id'] ?>" class="tableRow">
													<td scope="row"><?= $i ?></td>
													<td><?= $turn['doctor_name'] ?></td>
													<td><?= $turn['date'] ?></td>
													<td><?= $ci->dentist->find_time($turn['hour']) ?></td>
													<td><?= $turn['cr'] ?></td>
													<td>
														<div class="g-2">
															<a href="javascript:edit_turn('<?= $turn['id'] ?>')"
															   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-regular fa-pen-to-square fs-14"></span></a>
															<a href="javascript:print_turn('<?= $turn['id'] ?>')"
															   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-solid fa-print fs-14"></span></a>
															<?php if ($turn['status'] == 'p') : ?>
																<a href="javascript:changeStatus('<?= $turn['id'] ?>', '<?= base_url() ?>admin/accept_turn')"
																   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
																		class="fa-regular fa-circle-check fs-14"></span></a>
															<?php else : ?>
																<a href="javascript:changeStatus('<?= $turn['id'] ?>', '<?= base_url() ?>admin/pending_turn')"
																   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
																		class="las la-times-circle fs-14"></span></a>
															<?php endif; ?>
															<a href="javascript:delete_via_alert('<?= $turn['id'] ?>', '<?= base_url() ?>admin/delete_turn', 'turnsTable', update_balance)"
															   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-regular fa-trash-can fs-14"></span></a>
														</div>
													</td>
												</tr>
												<?php $i++;
											endforeach; ?>
											</tbody>
										</table>
									</div>
									<!-- the table end _______________________________________________________________________________ -->
								</div>

								<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="posts-tab-pane"
									 role="tabpanel" aria-labelledby="posts-tab" tabindex="0">
									<!-- the table start _______________________________________________________________________________ -->
									<div class="table-responsive">
										<table class="table text-nowrap" id="labsTable">
											<thead class="tableHead">
											<tr>
												<th scope="col">#</th>
												<th scope="col"><?= $ci->lang('laboratory') ?></th>
												<th scope="col"><?= $ci->lang('teeth') ?></th>
												<th scope="col"><?= $ci->lang('tooth type') ?></th>
												<th scope="col"><?= $ci->lang('delivery date') ?></th>
												<th scope="col"><?= $ci->lang('delivery time') ?></th>
												<th scope="col"><?= $ci->lang('pay amount') ?></th>
												<th scope="col"><?= $ci->lang('desc') ?></th>
												<th scope="col"><?= $ci->lang('actions') ?></th>
											</tr>
											</thead>
											<tbody>
											<?php $i = 1;
											foreach ($labs as $lab) : ?>
												<tr id="<?= $lab['id'] ?>" class="tableRow">
													<td scope="row"><?= $i ?></td>
													<td><?= $lab['lab_name'] ?></td>
													<?php
													$teeths = explode(',', $lab['teeth']);
													$teethName = '';
													foreach ($teeths as $tooth) {
														$info = $ci->tooth_by_id($tooth);
														$teethName .= $info['name'];
														$teethName .= ' (';
														$teethName .= $ci->dentist->find_location($info['location']);
														$teethName .= '),';
													}
													?>
													<td><?= substr($teethName, 0, -1) ?></td>
													<?php
													$types = explode(',', $lab['type']);
													$typesName = '';
													foreach ($types as $type) {
														$typesName .= $ci->lang($type);
														$typesName .= ',';
													}
													?>
													<td><?= substr($typesName, 0, -1) ?></td>
													<td><?= $lab['give_date'] ?></td>
													<td><?= $ci->dentist->find_time($lab['hour']) ?></td>
													<td><?= $lab['dr'] ?></td>
													<td><?= $lab['remarks'] ?></td>
													<td>
														<div class="g-2">
															<a href="javascript:edit_lab('<?= $lab['id'] ?>')"
															   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-regular fa-pen-to-square fs-14"></span></a>
															<a href=""
															   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
																	class="las la-check-circle"></span></a>
															<a href="javascript:print_lab('<?= $lab['id'] ?>')"
															   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-solid fa-print fs-14"></span></a>
															<a href="javascript:delete_via_alert('<?= $lab['id'] ?>', '<?= base_url() ?>admin/delete_lab', 'labsTable')"
															   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-solid fa-trash-can fs-14"></span></a>
														</div>
													</td>
												</tr>
												<?php $i++;
											endforeach; ?>
											</tbody>
										</table>
									</div>
									<!-- the table end _______________________________________________________________________________ -->
								</div>


								<div class="tab-pane fade p-0 border-0" id="followers-tab-pane" role="tabpanel"
									 aria-labelledby="followers-tab" tabindex="0">
									<!-- the table start _______________________________________________________________________________ -->
									<div class="table-responsive">
										<table class="table text-nowrap text-center" id="prescriptionTable">
											<thead class="tableHead">
											<tr>
												<th scope="col">#</th>
												<th scope="col"><?= $ci->lang('date and time') ?></th>
												<th scope="col"><?= $ci->lang('user') ?></th>
												<th scope="col"><?= $ci->lang('actions') ?></th>
											</tr>
											</thead>
											<tbody>
											<?php $i = 1;
											foreach ($prescriptions as $prescription) : ?>
												<tr id="<?= $prescription['id'] ?>" class="tableRow">
													<td scope="row"><?= $i ?></td>
													<td><?= $prescription['date_time'] ?></td>
													<td><?= $prescription['user_name'] ?></td>
													<td>
														<div class="g-2">
															<a href="javascript:viewPrescriptionsMedicines('<?= $prescription['id'] ?>')"
															   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
																	class="las la-eye fs-14"></span></a>
															<a href="javascript:print_prescription('<?= $prescription['id'] ?>')"
															   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-solid fa-print fs-14"></span></a>
														</div>
													</td>
												</tr>
												<?php $i++;
											endforeach; ?>
											</tbody>
										</table>
									</div>
									<!-- the table end _______________________________________________________________________________ -->
								</div>

								<div class="tab-pane fade p-0 border-0" id="archive-tab-pane" role="tabpanel"
									 aria-labelledby="archive-tab" tabindex="0">
									<!-- the table start _______________________________________________________________________________ -->
									<div class="table-responsive">
										<table class="table text-nowrap text-center" id="filesTable">
											<thead class="tableHead">
											<tr>
												<th scope="col">#</th>
												<th scope="col"><?= $ci->lang('title') ?></th>
												<th scope="col"><?= $ci->lang('date and time') ?></th>
												<th scope="col"><?= $ci->lang('desc') ?></th>
												<th scope="col"><?= $ci->lang('actions') ?></th>
											</tr>
											</thead>
											<tbody>
											<?php $i = 1;
											foreach ($files as $file) : ?>
												<tr id="<?= $file['id'] ?>" class="tableRow">
													<td scope="row"><?= $i ?></td>
													<td><?= $file['title'] ?></td>
													<td><?= $file['date'] ?></td>
													<td><?= $file['desc'] ?></td>
													<td>
														<div class="g-2">
															<a href="<?= base_url('patient_files/' . $file['filename']) ?>"
															   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
															   target="_blank"><span class="fa fa-download"></span></a>
															<a href="javascript:delete_via_alert('<?= $file['id'] ?>', '<?= base_url('admin/delete_files') ?>', 'filesTable')"
															   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
																	class="fa-solid fa-trash-can fs-14"></span></a>
														</div>
													</td>
												</tr>
												<?php $i++;
											endforeach; ?>
											</tbody>
										</table>
									</div>
									<!-- the table end _______________________________________________________________________________ -->
								</div>


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xxl-2">
		<div class="card custom-card">
			<div class="p-4 border-bottom border-block-end-dashed">
				<p class="fs-15 mb-2 fw-semibold"><?= $ci->lang('payment status') ?> :</p>
				<?php
				$percentage = ($sum_dr != 0) ? ($sum_cr * 100) / $sum_dr : 100;
				?>
				<p class="fw-semibold mb-2"
				   id="percentage"><?= $ci->language->languages('payment percent status', null, round($percentage)) ?></p>
				<div class="progress progress-sm progress-animate ">
					<div class="progress-bar bg-primary  ronded-1" role="progressbar" aria-valuenow="60"
						 aria-valuemin="0" aria-valuemax="100" style="width: <?= $percentage ?>%"></div>
				</div>
			</div>
		</div>
		<!-- dxdiag -->
		<div class="card custom-card" style="padding: 1rem !important;">
			<div class="form-group">
				<label class="form-label">
					<?= $ci->lang('Toggle View') ?>
				</label>
				<!-- this is an important select tag remember it -->
				<select id="selectToggleView" name="name" class="form-control select2-show-search form-select"
						data-placeholder="<?= $ci->lang('select') ?>" onchange="toogleView()">
					<option label="<?= $ci->lang('select') ?>"></option>
					<option value="simple"><?= $ci->lang('Simple') ?></option>
					<option value="Xray" selected><?= $ci->lang('X-Ray') ?></option>
				</select>
			</div>

		</div>
		<!-- dxdiag -->

		<!-- TODO: the new Action -->
		<div class="card custom-card" style="padding: 1rem !important;">
			<div class="form-group">
				<label class="form-label">
					<?= $ci->lang('actions') ?>
				</label>
				<select id="selectaction" name="actions" class="form-control select2-show-search form-select"
						data-placeholder="<?= $ci->lang('select') ?>" onchange="actions()">
					<option label="<?= $ci->lang('select') ?>"></option>
					<option value="1"><?= $ci->lang('turn') ?></option>
					<option value="2"><?= $ci->lang('cr') ?></option>
					<option value="3"><?= $ci->lang('laboratory') ?></option>
					<option value="4"><?= $ci->lang('prescription') ?></option>
					<option value="5"><?= $ci->lang('archive') ?></option>
				</select>
			</div>

		</div>
		<!-- TODO: the new Action end -->

	</div>
</div>
<!--End::row-1 -->


<!-- Modal insert turn -->
<div class="modal fade effect-scale" id="extralargemodal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert turn') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<form id="insertTurn">
						<div class="row">

							<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">

							<div class="col-sm-12 col-md-4">
								<div class="form-group jdp" id="main-div">
									<label class="form-label"><?= $ci->lang('date') ?> <span
											class="text-red">*</span></label>
									<input data-jdp="date0" type="text" id="test-date-id-date" name="date"
										   class="form-control" onchange="check_turns()"
										   placeholder="<?= $ci->lang('date') ?>" autocomplete="off">
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span
											class="text-red">*</span></label>
									<select name="doctor_id" class="form-control select2-show-search form-select"
											onchange="check_turns()" id="reference_doctor"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option
												value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('hour') ?> <span
											class="text-red">*</span></label>
									<select name="hour" class="form-control select2-show-search form-select"
											id="hour_insert" onchange="check_turns()"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($ci->dentist->hours() as $hour) :
											?>
											<option value="<?= $hour['key'] ?>"><?= $hour['value'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="turnsTableModal">
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-warning"
						onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable','extralargemodal', print_turn, 'print')"><?= $ci->lang('save and print') ?>
					<i class="fa fa-print"></i></button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable')"><?= $ci->lang('save') ?>
					<i class="fa fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->

<!-- Modal update turn -->
<div class="modal fade effect-scale" id="update_turn" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('update turn') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="updateTurn">
						<div class="row">

							<input type="hidden" name="patient_id" id="patient_id_turn" value="<?= $profile['id'] ?>">
							<input type="hidden" name="slug" id="slug_turn">

							<div class="col-sm-12 col-md-4">
								<div class="form-group jdp" id="main-div">
									<input type="hidden" name="dateOld" id="dateTurnOld">
									<label class="form-label"><?= $ci->lang('date') ?> <span
											class="text-red">*</span></label>
									<input data-jdp="date0" type="text" id="date_turn" name="date" class="form-control"
										   placeholder="<?= $ci->lang('date') ?>"
										   onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), doctor = $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
										   autocomplete="off">
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('reference doctor') ?> <span
											class="text-red">*</span></label>
									<input type="hidden" name="doctorOld" id="doctorTurnOld">
									<select name="doctor_id" class="form-control select2-show-search form-select"
											onchange="check_turns(document.getElementById('doctor_id_turn'), $('#date_turn').val(), doctor = $('#doctor_id_turn').val(), '#turnsTableModalupdate')"
											id="doctor_id_turn" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option
												value="<?= $doctor['id'] ?>"><?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('hour') ?> <span
											class="text-red">*</span></label>
									<input type="hidden" name="hourOld" id="hourTurnOld">
									<select name="hour" class="form-control select2-show-search form-select"
											id="hour_turn" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($ci->dentist->hours() as $hour) :
											?>
											<option value="<?= $hour['key'] ?>"><?= $hour['value'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="turnsTableModalupdate">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?></button>
				<button class="btn btn-primary"
						onclick="updateWithoutDatatable('updateTurn', '<?= base_url() ?>admin/update_turn', 'turnsTable', 'update_turn')"><?= $ci->lang('save') ?></button>
			</div>
		</div>
	</div>
</div>


<!-- Modal pay turn -->
<div class="modal fade effect-scale" id="paymentModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert payment') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertPayment">
						<div class="row">

							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('select turn') ?> <span
											class="text-red">*</span></label>
									<select name="slug" class="form-control select2-show-search form-select"
											id="select_turns" data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('amount') ?> <span class="text-red">*</span></label>
									<input type="number" name="cr" class="form-control"
										   placeholder="<?= $ci->lang('amount') ?>" id="" autocomplete="off">
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-warning"
						onclick="updateWithoutDatatable('insertPayment', '<?= base_url() ?>admin/pay_turn', 'turnsTable', 'paymentModal', print_payment, 'print');"><?= $ci->lang('save and print') ?>
					<i class="fa fa-print"></i></button>
				<button class="btn btn-primary"
						onclick="updateWithoutDatatable('insertPayment', '<?= base_url() ?>admin/pay_turn', 'turnsTable', 'paymentModal', update_balance)"><?= $ci->lang('save') ?>
					<i class="fa fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->


<!-- Modal insert Files -->

<!-- Modal pay turn -->
<div class="modal fade effect-scale" id="filesModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('insert files') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertFile">
						<div class="row">

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('select file') ?> <span
											class="text-red">*</span></label>
									<input type="file" name="to_upload" id="" class="form-control">
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('title') ?> <span class="text-red">*</span></label>
									<input type="text" name="title" class="form-control"
										   placeholder="<?= $ci->lang('title') ?>" id="" autocomplete="off">
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label"><?= $ci->lang('category') ?> <span
											class="text-red">*</span></label>
									<select class="form-control select2-show-search form-select" name="categories_id"
											style="width: 300px !important;"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>

										<?php foreach ($categories_files as $categories_file) : ?>

											<option value="<?= $categories_file['id'] ?>">
												<?= $categories_file['name'] ?>
											</option>

										<?php endforeach; ?>

									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="form-group">
									<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
									<label class="form-label"><?= $ci->lang('desc') ?></label>
									<textarea name="desc" id="" rows="4" class="form-control"
											  placeholder="<?= $ci->lang('desc') ?>"></textarea>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('insertFile', '<?= base_url() ?>admin/insert_files', 'filesTable', 'filesModal')"><?= $ci->lang('save') ?>
					<i class="fa fa-upload"></i></button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->

<!-- End Modal insert Files -->


<!-- Modal start --------------------------------------------------------------------------------------------------- adulth insert -->
<div class="modal fade effect-scale" tabindex="-1" id="teethmodal" role="dialog">


	<!-- TODO: Here is the sidebar start -->
	<header class="sidebar_header">
		<div id="toggle" class="sidebar_toggle">
			<span><?= $ci->lang('Departments') ?> <i class="fa fa-bars"></i></span>
		</div>
		<form id="checkboxes">
			<div id="menu">
				<ul class="unstyled-list">
					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('restorative') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox1" id="checkbox_resto" class="checkbox"
									   onchange="calculate_sum()" value="restorative" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Endodantic') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox2" class="checkbox" id="checkbox_endo"
									   onchange="calculate_sum()" value="endo" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Prosthodontics') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox3" id="checkbox_prosthodontics" class="checkbox"
									   onchange="calculate_sum()" value="Prosthodontics" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>


				</ul>
			</div>
		</form>

	</header>
	<!-- TODO: Here is the sidebar end -->


	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header" style="justify-content: space-between !important">
				<!-- adulth institle -->
				<h5 class="modal-title">
					<?= $ci->lang('Insert Tooth') ?>
				</h5>

				<form id="demo_form" style="width: 75%; display: inline-flex; align-items: center;">

					<div style="width: 500px;display: flex;gap: 10px;">
						<label class="form-label" style="display: flex;">
							<?= $ci->lang('diagnose') ?> <span class="text-red">*</span>
						</label>
						<select class="form-control select2-show-search form-select" name="diagnoses"
								style="width: 300px !important;"
								onchange="multiple_value('#select_diagnose', '#diagnose_adult')" id="select_diagnose"
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
					<ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="insertTabs"
						role="tablist">
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link active" id="Restorative" data-bs-toggle="tab"
									data-bs-target="#Restorative-pane" type="button" role="tab"
									aria-controls="Restorative-pane" aria-selected="true"><i
									class="las la-tooth me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('restorative') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="endo" data-bs-toggle="tab" data-bs-target="#endo-pane"
									type="button" role="tab" aria-controls="endo-pane" aria-selected="false"><i
									class="las la-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Endodantic') ?>
							</button> <!--Translate-->
						</li>
						<li class="nav-item rounded" role="presentation">
							<button class="nav-link" id="pros-tab" data-bs-toggle="tab" data-bs-target="#pros-pane"
									type="button" role="tab" aria-controls="pros-pane" aria-selected="false"><i
									class="las la-vial me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('Prosthodontics') ?>
							</button> <!--Translate-->
						</li>
					</ul>
				</div>
			</div>


			<!-- insTabContent start -->
			<div class="tab-content" id="myITabContent">

				<!--TODO:insFixing  -->
				<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3" id="Restorative-pane"
					 role="tabpanel" aria-labelledby="Restorative-pane" tabindex="0">
					<div class="modal-body">
						<div class="row">

							<!-- fixing modal contents -->
							<div class="col-md-10">

								<div class="row">

									<!-- fixing modal contents -->
									<div class="col-md-12">

										<form id="insertFixing">

											<div class="row">
												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('Caries Depth') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertCariesDepth" name="CariesDepth"
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
														<select id="insertMaterial" name="Material"
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
														<select id="insertRestorativeMaterial"
																name="RestorativeMaterial"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="showBonding('#insertRestorativeMaterial','composite', 'bonding', 'amalgam')">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($RestorativeMaterialList as $Restorativeaterial) : ?>
																<option
																	value="<?= $Restorativeaterial['id'] ?>"><?= $Restorativeaterial['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-6" id="composite" style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('composite brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertCompositeBrand" name="CompositeBrand"
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

												<div class="col-sm-12 col-md-6" id="bonding" style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('bonding brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertBondingBrand" name="bondingBrand"
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


												<div class="col-sm-12 col-md-12" id="amalgam" style="display:none;">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('amalgam brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertAmalgamBrand" name="AmalgamBrand"
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
																id="services_restorative"
																onchange="service_price_resto(), calculate_sum()"
																data-placeholder="<?= $ci->lang('select') ?>" multiple>
															<?php foreach ($restorative_services as $service) : ?>

																<option
																	value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

															<?php endforeach; ?>

														</select>
														<input type="hidden" name="imgAddress"
															   id="adulth_teeth_location" value="">
														<input type="hidden" name="restorative_services"
															   id="services_input_restorative">
													</div>
												</div>

												<div class="col-sm-12 col-md-6">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('pay amount') ?> <span
																class="text-red">*</span>
														</label>
														<input type="number" name="price_restorative"
															   id="price_tooth_restorative" class="form-control"
															   placeholder="<?= $ci->lang('pay amount') ?>">
													</div>
												</div>


												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('description') ?>
														</label>
														<textarea class="form-control" name="restorativeDescription"
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
										 class="modalimg" id="modalImage2">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_resto" class="form-control" name="total_price">
									</div>

								</div>

							</div>

							</form>

						</div>

					</div>
				</div>
				<!--insFixing  -->


				<!-- TODO:insEndo -->
				<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="endo-pane" role="tabpanel"
					 aria-labelledby="Endo-pane" tabindex="0">

					<div class="modal-body">
						<div class="row">
							<div class="col-md-10">

								<form id="insertTooth">
									<div class="row">

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
												<label class="form-label">
													<?= $ci->lang('tooth name') ?> <span class="text-red">*</span>
												</label>
												<!-- this is an important select tag remember it -->
												<select id="selectName" name="name"
														class="form-control select2-show-search form-select"
														data-placeholder="<?= $ci->lang('select') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
												</select>
											</div>
										</div>

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('tooth location') ?> <span class="text-red">*</span>
												</label>

												<select id="locationSelector" name="location"
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
											<div class="form-group jdp" id="main-divs">
												<label class="form-label">
													<?= $ci->lang('number of canal') ?>
												</label>

												<select id="canalselector" name="root_number"
														class="form-control select2-show-search form-select"
														data-placeholder="<?= $ci->lang('select') ?>"
														onchange="showRow()">
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
										<div class="row" id="firstRow" style="display: none;">
											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>
													</label>


													<select name="r_name1"
															class="form-control select2-show-search form-select"
															id="canalLocation1"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>
													<input type="number" name="r_width1" class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">
												</div>
											</div>

										</div>

										<div class="row" id="secoundRow" style="display: none;">


											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name2"
															class="form-control select2-show-search form-select"
															id="canalLocation2"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>


													<input type="number" name="r_width2" class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="thirdRow" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>
													</label>


													<select name="r_name3"
															class="form-control select2-show-search form-select"
															id="canalLocation3"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>


													<input type="number" name="r_width3" class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="fourthRow" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name4"
															class="form-control select2-show-search form-select"
															id="canalLocation4"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>

													</label>


													<input type="number" name="r_width4" class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="fifthRow" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name5"
															class="form-control select2-show-search form-select"
															id="canalLocation5"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>

													</label>


													<input type="number" name="r_width5" class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>


										</div>

									</div>

									<div class="row">
										<div
											style="border-bottom: 1px solid gray;margin: 50px 0 30px 0;opacity: 0.5;"></div>
										<!-- TODO the new select start-->
										<div class="col-sm-12 col-md-6"> <!-- type of obturation -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of obturation') ?>
												</label>
												<select name="typeObturation"
														class="form-control select2-show-search form-select"
														id="instypeObturation"
														data-placeholder="<?= $ci->lang('type of obturation') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfAbturationList as $typeOfAbturation) : ?>
														<option
															value="<?= $typeOfAbturation['id'] ?>"><?= $typeOfAbturation['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<!-- _________________________________________________________________________________________________________________________________________________________________________________ -->
										<div class="col-sm-12 col-md-6"> <!-- type of sealer -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of sealer') ?>
												</label>

												<select name="TypeSealer"
														class="form-control select2-show-search form-select"
														id="insTypeSealer"
														data-placeholder="<?= $ci->lang('type of sealer') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfSealerList as $typeOfSealer) : ?>
														<option
															value="<?= $typeOfSealer['id'] ?>"><?= $typeOfSealer['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<!-- _________________________________________________________________________________________________________________________________________________________________________________ -->
										<div class="col-sm-12 col-md-12"> <!-- type of irrigation -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of irrigation') ?>
												</label>

												<select name="TypeIrrigation"
														class="form-control select2-show-search form-select"
														id="insTypeIrrigation"
														data-placeholder="<?= $ci->lang('type of irrigation') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfIrregationList as $typeOfIrregation) : ?>
														<option
															value="<?= $typeOfIrregation['id'] ?>"><?= $typeOfIrregation['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<!-- TODO the new select end-->


										<div class="col-sm-12 col-md-6">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('treatment') ?> <span class="text-red">*</span>
												</label>
												<input type="hidden" name="diagnose" id="diagnose_adultOld">
												<select class="form-control select2-show-search form-select"
														id="services" onchange="service_price(), calculate_sum()"
														data-placeholder="<?= $ci->lang('select') ?>" multiple>
													<?php foreach ($endo_services as $service) : ?>

														<option
															value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

													<?php endforeach; ?>

												</select>
												<input type="hidden" name="imgAddress" id="adulth_teeth_location"
													   value="">
												<input type="hidden" name="endo_services" id="services_input">
											</div>
										</div>

										<div class="col-sm-12 col-md-6">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
												</label>
												<input type="number" name="price" id="price_tooth" class="form-control"
													   placeholder="<?= $ci->lang('pay amount') ?>">
											</div>

										</div>

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
										 class="modalimg" id="modalImage">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_endo" class="form-control" name="total_price">
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
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
								</p>
							</div>
						</div>
					</div>
					<!-- TODO: tooltip div Endo -->
				</div>
				<!-- insEndo -->


				<!-- TODO:insPro -->
				<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="pros-pane" role="tabpanel"
					 aria-labelledby="Pros-pane" tabindex="0">

					<div class="modal-body">

						<div class="row">


							<!-- Pro modal content -->
							<div class="col-md-10">
								<div class="row">

									<div class="col-sm-12">
										<form action="" id="insertPro">
											<div class="row">

												<div class="col-sm-12 col-md-12">
													<label class="form-label">
														<?= ucwords('type of Prosthodontics') ?>
													</label>
													<!-- this is an important select tag remember it -->
													<select id="type_pro" name="type_pro" onchange="check_pro_type()"
															class="form-control select2-show-search form-select"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option value="abutment"
																selected><?= ucwords('abutment') ?></option>
														<option value="pontic"><?= ucwords('pontic') ?></option>
													</select>
												</div>


												<div class="col-sm-12 col-md-6 abutment">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of restoration') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="type_restoration" name="type_restoration"
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

														<label class="form-label">
															<?= ucwords('Filling material (Core)') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="filling_material" name="filling_material"
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
												<!-- post -->
												<div class="col-sm-12 col-md-6 abutment">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="post" name="post"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="showSelect()">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($PostList as $Post) : ?>
																<option
																	value="<?= $Post['id'] ?>"><?= ucwords($Post['name']) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
												<!-- fiber post -->
												<div class="col-sm-12 col-md-6 nonDisplay abutment" id="fiber_post_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of prefabricated post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<!--  -->
														<select id="fiber_post" name="PrefabricatedPost"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<?php foreach ($PrefabricatedPostList as $PrefabricatedPost) : ?>
																<option
																	value="<?= $PrefabricatedPost['id'] ?>"><?= ucwords($PrefabricatedPost['name']) ?></option>
															<?php endforeach; ?>
														</select>
														</select>
													</div>
												</div>
												<!-- metal screw post -->
												<div class="col-sm-12 col-md-6 nonDisplay abutment"
													 id="metal_screw_post_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of custom post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="metal_screw_post" name="customPost"
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

												<div class="col-sm-12 col-md-6 abutment" id="type_crown_material_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of crown material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="crown_material" name="crown_material"
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

														<label class="form-label">
															<?= ucwords('color') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="pro_color" name="pro_color"
																onchange="multiple_value('#pro_color', '#pro_colors')"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																multiple>
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($ColorList as $Color) : ?>
																<option
																	value="<?= $Color['id'] ?>"><?= ucwords($Color['name']) ?></option>
															<?php endforeach; ?>
														</select>
														<input type="hidden" name="color" id="pro_colors">
													</div>
												</div>


												<div class="col-sm-12 col-md-4 abutment ">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Impression Technique') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="impression_techniq" name="impression_technique"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="ImpressionTechniq()">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($ImpressionTechniqueList as $ImpressionTechnique) : ?>
																<option
																	value="<?= $ImpressionTechnique['id'] ?>"><?= ucwords($ImpressionTechnique['name']) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4 nonDisplay abutment"
													 id="impression_material_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Impression material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="impression_material" name="impression_material"
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
														<label class="form-label">
															<?= ucwords('Cement Material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="content_material" name="CementMaterial"
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
														<label class="form-label">
															<?= ucwords('Pontic Design') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="pontic_design" name="pontic_design"
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
															<label class="form-label">
																<?= $ci->lang('treatment') ?> <span
																	class="text-red">*</span>
															</label>
															<input type="hidden" name="diagnose"
																   id="diagnose_adult">
															<select
																class="form-control select2-show-search form-select"
																id="services_pro"
																onchange="service_price_pro(), calculate_sum()"
																data-placeholder="<?= $ci->lang('select') ?>"
																multiple>
																<?php foreach ($Prosthodontics_services as $service) : ?>

																	<option
																		value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

																<?php endforeach; ?>

															</select>
															<input type="hidden" name="pro_services"
																   id="services_input_pro">
														</div>
													</div>

													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('pay amount') ?> <span
																	class="text-red">*</span>
															</label>
															<input type="number" name="price_pro"
																   id="price_tooth_pro"
																   class="form-control"
																   placeholder="<?= $ci->lang('pay amount') ?>">
														</div>

													</div>

													<div class="col-sm-12 col-md-12">
														<div class="form-group">
															<label class="form-label">
																<?= $ci->lang('description') ?>
															</label>
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
										 class="modalimg" id="modalImage2">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_pro" class="form-control" name="total_price">
									</div>

								</div>

							</div>
						</div>

					</div>

				</div>
				<!-- insPro -->

			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>

				<button class="btn btn-primary"
						onclick="submitWithoutDatatableMulti(['insertTooth', 'demo_form', 'insertFixing', 'insertPro', 'checkboxes'], '<?= base_url() ?>admin/insert_tooth', '','teethmodal', list_teeth)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>

			</div>
		</div>
	</div>
</div>
<!-- Modal end ---------------------------------------------------------------------------------------------------adulth insert -->


<!-- TODO: (Update) Modal Srart _________________________________________________________________________________________________ asulth edit -->
<div class="modal fade effect-scale" tabindex="-1" id="teethmodal_update" role="dialog">


	<!-- TODO: (Update) Here is the sidebar start -->
	<header class="sidebar_header">
		<div id="toggle" class="sidebar_toggle">
			<span><?= $ci->lang('Departments') ?> <i class="fa fa-bars"></i></span>
		</div>
		<form id="checkboxes_update">
			<div id="menu">
				<ul class="unstyled-list">
					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('restorative') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<!-- TODO (Important) change these input ids according to the check boxes to toggle them -->
								<input type="checkbox" name="checkbox1" id="checkbox_resto" class="checkbox"
									   onchange="calculate_sum()" value="restorative" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Endodantic') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox2" class="checkbox" id="checkbox_endo"
									   onchange="calculate_sum()" value="endo" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Prosthodontics') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox1" id="checkbox_resto" class="checkbox"
									   onchange="calculate_sum()" value="restorative" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>


				</ul>
			</div>
		</form>

	</header>
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
				<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3" id="Restorative_update-pane"
					 role="tabpanel" aria-labelledby="Restorative_update-pane" tabindex="0">
					<div class="modal-body">
						<div class="row">

							<!-- fixing modal contents update -->
							<div class="col-md-10">

								<div class="row">

									<!-- fixing modal contents update -->
									<div class="col-md-12">

										<form id="insertFixing_update">

											<div class="row">
												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('Caries Depth') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertCariesDepth_update" name="CariesDepth"
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
														<select id="insertMaterial_update" name="Material"
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
														<select id="insertRestorativeMaterial_update"
																name="RestorativeMaterial"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="showBonding('#insertRestorativeMaterial_update', 'composite_update', 'bonding_update', 'amalgam_update')">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($RestorativeMaterialList as $Restorativeaterial) : ?>
																<option
																	value="<?= $Restorativeaterial['id'] ?>"><?= $Restorativeaterial['name'] ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-6" id="composite_update"
													 style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('composite brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertCompositeBrand_update" name="CompositeBrand"
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

												<div class="col-sm-12 col-md-6" id="bonding_update"
													 style="display:none;">
													<div class="form-group">

														<label class="form-label">
															<?= $ci->lang('bonding brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertBondingBrand_update" name="bondingBrand"
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


												<div class="col-sm-12 col-md-12" id="amalgam_update"
													 style="display:none;">
													<div class="form-group">
														<label class="form-label">
															<?= $ci->lang('amalgam brand') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="insertAmalgamBrand_update" name="AmalgamBrand"
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
																onchange="service_price_resto('#services_restorative_update', '#services_input_restorative_update', '#price_tooth_restorative_update'), calculate_sum()"
																data-placeholder="<?= $ci->lang('select') ?>" multiple>
															<?php foreach ($restorative_services as $service) : ?>

																<option
																	value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

															<?php endforeach; ?>

														</select>
														<input type="hidden" name="imgAddress"
															   id="adulth_teeth_location_update" value="">
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
														<textarea class="form-control" name="restorativeDescription"
																  id="restorative_details_update"
																  placeholder="<?= $ci->lang('desc') ?>"
																  rows="7"></textarea>
													</div>
												</div>


											</div>

										</form>


									</div>


								</div>


							</div>

							<!-- fixing modal picture (Update) -->

							<div class="col-md-2">

								<div class="modal-image-area">
									<h2 class="modal-Title"></h2>
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
										 class="modalimg" id="modalImage2_update_restro">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_resto" class="form-control" name="total_price">
									</div>

								</div>

							</div>

							</form>

						</div>

					</div>
				</div>
				<!--insFixing update  -->


				<!-- TODO: (Update) insEndo -->
				<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="endo_update-pane" role="tabpanel"
					 aria-labelledby="Endo_update-pane" tabindex="0">

					<div class="modal-body">
						<div class="row">
							<div class="col-md-10">

								<form id="insertTooth_update">
									<div class="row">

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
												<label class="form-label">
													<?= $ci->lang('tooth name') ?> <span class="text-red">*</span>
												</label>
												<!-- this is an important select tag remember it -->
												<select id="selectName_update" name="name"
														class="form-control select2-show-search form-select"
														data-placeholder="<?= $ci->lang('select') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
												</select>
											</div>
										</div>

										<div class="col-sm-12 col-md-4">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('tooth location') ?> <span class="text-red">*</span>
												</label>

												<select id="locationSelector_update" name="location"
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
											<div class="form-group jdp" id="main-divs">
												<label class="form-label">
													<?= $ci->lang('number of canal') ?>
												</label>

												<select id="canalselector_update" name="root_number"
														class="form-control select2-show-search form-select"
														data-placeholder="<?= $ci->lang('select') ?>"
														onchange="showRow('#canalselector_update','#firstRow_update', '#secoundRow_update', '#thirdRow_update', '#fourthRow_update', '#fifthRow_update')">
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
										<div class="row" id="firstRow_update" style="display: none;">
											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>
													</label>


													<select name="r_name1"
															class="form-control select2-show-search form-select"
															id="canalLocation1_update"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>
													<input type="number" id="c_length1_update" name="r_width1"
														   class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">
												</div>
											</div>

										</div>

										<div class="row" id="secoundRow_update" style="display: none;">


											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name2"
															class="form-control select2-show-search form-select"
															id="canalLocation2_update"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>


													<input type="number" id="c_length2_update" name="r_width2"
														   class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="thirdRow_update" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>
													</label>


													<select name="r_name3"
															class="form-control select2-show-search form-select"
															id="canalLocation3_update"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>
													</label>


													<input type="number" id="c_length3_update" name="r_width3"
														   class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="fourthRow_update" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name4"
															class="form-control select2-show-search form-select"
															id="canalLocation4_update"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>

													</label>


													<input type="number" id="c_length4_update" name="r_width4"
														   class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>

										</div>

										<div class="row" id="fifthRow_update" style="display: none;">

											<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal location') ?>

													</label>


													<select name="r_name5"
															class="form-control select2-show-search form-select"
															id="canalLocation5_update"
															data-placeholder="<?= $ci->lang('select') ?>">
														<option label="<?= $ci->lang('select') ?>"></option>

														<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
															<option value="<?= $key ?>"><?= $value ?></option>
														<?php endforeach; ?>

													</select>
												</div>
											</div>


											<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
												<div class="form-group">
													<label class="form-label">
														<?= $ci->lang('canal length') ?>

													</label>


													<input type="number" id="c_length5_update" name="r_width5"
														   class="form-control"
														   placeholder="<?= $ci->lang('canal length') ?>">

												</div>
											</div>


										</div>

									</div>

									<div class="row">
										<div
											style="border-bottom: 1px solid gray;margin: 50px 0 30px 0;opacity: 0.5;"></div>
										<!-- TODO: (Update) the new select start-->
										<div class="col-sm-12 col-md-6"> <!-- type of obturation -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of obturation') ?>
												</label>
												<select name="typeObturation"
														class="form-control select2-show-search form-select"
														id="instypeObturation_update"
														data-placeholder="<?= $ci->lang('type of obturation') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfAbturationList as $typeOfAbturation) : ?>
														<option
															value="<?= $typeOfAbturation['id'] ?>"><?= $typeOfAbturation['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<!-- _________________________________________________________________________________________________________________________________________________________________________________ -->
										<div class="col-sm-12 col-md-6"> <!-- type of sealer -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of sealer') ?>
												</label>

												<select name="TypeSealer"
														class="form-control select2-show-search form-select"
														id="insTypeSealer_update"
														data-placeholder="<?= $ci->lang('type of sealer') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfSealerList as $typeOfSealer) : ?>
														<option
															value="<?= $typeOfSealer['id'] ?>"><?= $typeOfSealer['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<!-- _________________________________________________________________________________________________________________________________________________________________________________ -->
										<div class="col-sm-12 col-md-12"> <!-- type of irrigation -->
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('type of irrigation') ?>
												</label>

												<select name="TypeIrrigation"
														class="form-control select2-show-search form-select"
														id="insTypeIrrigation_update"
														data-placeholder="<?= $ci->lang('type of irrigation') ?>">
													<option label="<?= $ci->lang('select') ?>"></option>
													<?php foreach ($typeOfIrregationList as $typeOfIrregation) : ?>
														<option
															value="<?= $typeOfIrregation['id'] ?>"><?= $typeOfIrregation['name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<!-- TODO: (Update) the new select end-->


										<div class="col-sm-12 col-md-6">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('treatment') ?> <span class="text-red">*</span>
												</label>
												<input type="hidden" name="diagnose" id="diagnose_adult_update">
												<select class="form-control select2-show-search form-select"
														id="services_update" onchange="service_price(), calculate_sum()"
														data-placeholder="<?= $ci->lang('select') ?>" multiple>
													<?php foreach ($endo_services as $service) : ?>

														<option
															value="<?= $service['id'] ?>"><?= $service['name'] ?></option>

													<?php endforeach; ?>

												</select>
												<input type="hidden" name="imgAddress" id="adulth_teeth_location_update"
													   value="">
												<input type="hidden" name="endo_services" id="services_input_update">
											</div>
										</div>

										<div class="col-sm-12 col-md-6">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
												</label>
												<input type="number" name="price" id="price_tooth_update"
													   class="form-control"
													   placeholder="<?= $ci->lang('pay amount') ?>">
											</div>

										</div>

										<div class="col-sm-12 col-md-12">
											<div class="form-group">
												<label class="form-label">
													<?= $ci->lang('description') ?>
												</label>
												<textarea class="form-control" name="details" id="details_update"
														  placeholder="<?= $ci->lang('description') ?>"></textarea>
											</div>
										</div>


									</div>

							</div>
							<div class="col-md-2">

								<div class="modal-image-area">

									<h2 class="modal-Title"></h2>
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png"
										 class="modalimg" id="modalImage_update">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_endo" class="form-control" name="total_price">
									</div>
								</div>

							</div>

							</form>
						</div>


					</div>
					<!-- TODO: (Update) tooltip div Endo -->
					<div class="item-hints">
						<div class="hint" data-position="4">
							<!-- is-hint -->
							<span class="hint-radius"></span>
							<span class="hint-dot"></span>
							<div class="hint-content do--split-children">
								<p>
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
									The point of using Lorem Ipsum is that it has a more-or-less normal
									distribution of letters, as opposed to using 'Content here, content
									here', making it look like readable English.
								</p>
							</div>
						</div>
					</div>
					<!-- TODO: (Update) tooltip div Endo -->
				</div>
				<!-- insEndo -->


				<!-- TODO: (Update) insPro -->
				<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="pros_update-pane" role="tabpanel"
					 aria-labelledby="pros_update-pane" tabindex="0">

					<div class="modal-body">

						<div class="row">


							<!-- Pro modal content update -->
							<div class="col-md-10">
								<div class="row">

									<div class="col-sm-12">
										<form action="" id="insertPro_update">
											<div class="row">

												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of restoration') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="type_restoration_update" name="type_restoration"
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
															<?= ucwords('Filling material (Core)') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="filling_material_update" name="filling_material"
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
												<!-- post update -->
												<div class="col-sm-12 col-md-6">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="post_update" name="post"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>"
																onchange="showSelect()">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>
												<!-- fiber post update -->
												<div class="col-sm-12 col-md-6 nonDisplay" id="fiber_post_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of prefabricated post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="fiber_post_update" name="fiber_post"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>
												<!-- metal screw post update-->
												<div class="col-sm-12 col-md-6 nonDisplay" id="metal_screw_post_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of custom post') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="metal_screw_post_update" name="metal_screw_post"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1"></option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-6" id="type_crown_material_div">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('type of crown material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="crown_material_update" name="crown_material"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('color') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="pro_color_update" name="pro_color"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>" multiple>
															<option label="<?= $ci->lang('select') ?>"></option>

														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Pontic Design') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="pontic_design_update" name="pontic_design"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Impression Technique') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="impression_techniq_update"
																name="impression_technique"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>

												<div class="col-sm-12 col-md-4">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Impression material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="impression_techniq_update"
																name="Impression_material"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>


												<div class="col-sm-12 col-md-4">
													<div class="form-group">

														<label class="form-label">
															<?= ucwords('Cement Material') ?>
														</label>
														<!-- this is an important select tag remember it -->
														<select id="content_material_update" name="content_material"
																class="form-control select2-show-search form-select"
																data-placeholder="<?= $ci->lang('select') ?>">
															<option label="<?= $ci->lang('select') ?>"></option>

															<option value="1">PreFibricated Post</option>
															<option value="2">Custom Post</option>
														</select>
													</div>
												</div>

											</div>
										</form>
									</div>

								</div>
							</div>

							<!-- Pro modal picture update -->
							<div class="col-md-2">

								<div class="modal-image-area">
									<h2 class="modal-Title"></h2>
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
										 class="modalimg" id="modalImage2">
									<div>
										<label class="form-label">
											<?= $ci->lang('pay amount') ?>
										</label>
										<input type="text" id="priceTag_resto" class="form-control" name="total_price">
									</div>

								</div>

							</div>
						</div>

					</div>

				</div>
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
<!-- Modal End _________________________________________________________________________________________________ asulth edit -->


<script>
	var ageOld = <?= $profile['age']; ?>;

	function multiple_value(selectId = '#pains', inputId = '#model_value') {
		const select = $(selectId).val();
		let text = select.join()
		$(inputId).val(text);
	}

	// TODO: show prices indo
	// function service_price(serviceSelectId = '#services', hiddenInputId = '#services_input', priceInputId = '#price_tooth') {
	//   const select = $(serviceSelectId).val();
	//   let text = select.join();
	//   $(hiddenInputId).val(text);

	//   $.ajax({
	//     url: "<?= base_url('admin/check_service_price') ?>",
	//     type: 'POST',
	//     data: {
	//       service: select
	//     },
	//     success: function(response) {
	//       let result = JSON.parse(response);
	//       $(priceInputId).val(result[0]);

	//     }
	//   })
	// }

	// TODO: show prices restorative
	// function service_price_resto(serviceSelectId = '#services_restorative', hiddenInputId = '#services_input_restorative', priceInputId = '#price_tooth_restorative') {
	//   const select = $(serviceSelectId).val();
	//   let text = select.join();
	//   $(hiddenInputId).val(text);

	//   $.ajax({
	//     url: "<?= base_url('admin/check_service_price') ?>",
	//     type: 'POST',
	//     data: {
	//       service: select
	//     },
	//     success: function(response) {
	//       let result = JSON.parse(response);
	//       $(priceInputId).val(result[0]);
	//     }
	//   })
	// }


	// TODO: the fucking services start
	// Update service_price_resto to accept a callback function
	function service_price_resto(serviceSelectId = '#services_restorative', hiddenInputId = '#services_input_restorative', priceInputId = '#price_tooth_restorative', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Update service_price to accept a callback function
	function service_price(serviceSelectId = '#services', hiddenInputId = '#services_input', priceInputId = '#price_tooth', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}


	function service_price_pro(serviceSelectId = '#services_pro', hiddenInputId = '#services_input_pro', priceInputId = '#price_tooth_pro', callback) {
		const select = $(serviceSelectId).val();
		let text = select.join();
		$(hiddenInputId).val(text);

		$.ajax({
			url: "<?= base_url('admin/check_service_price') ?>",
			type: 'POST',
			data: {
				service: select
			},
			success: function (response) {
				let result = JSON.parse(response);
				$(priceInputId).val(result[0]);
				// Call the callback function with the result value
				if (typeof callback === 'function') {
					callback(result[0]);
				}
			}
		});
	}

	// Function to calculate the sum of values from service_price_resto and service_price
	function calculate_sum() {
		let priceResto = 0;
		let priceService = 0;
		let priceProsthodontics = 0;

		let is_endo = ($('#checkbox_endo').is(':checked')) ? true : false;
		let is_resto = ($('#checkbox_resto').is(':checked')) ? true : false;
		let is_prosthodontics = ($('#checkbox_prosthodontics').is(':checked')) ? true : false;

		// Callback function for service_price_resto
		function handlePriceResto(price) {
			if (is_resto) {
				priceResto = price;
				handleResults();
			}
		}

		function handlePriceProsthodontics(price) {
			if (is_prosthodontics) {
				priceProsthodontics = price;
				handleResults();
			}
		}


		// Callback function for service_price
		function handlePriceService(price) {
			if (is_endo) {
				priceService = price;
				handleResults();
			}
		}

		// Function to handle the results and update the inputs accordingly
		function handleResults() {
			// Calculate the sum
			const sum = priceResto + priceService + priceProsthodontics;

			// Determine which inputs to update based on the values of priceResto and priceService
			if (priceResto === 0 && priceService !== 0) {
				// If priceResto is zero, update only the "priceTag_resto" input
				$('#priceTag_endo').val(sum);
				$('#priceTag_resto').val(sum);
				$('#priceTag_pro').val(sum);
			} else if (priceService === 0 && priceResto !== 0) {
				// If priceService is zero, update only the "priceTag_endo" input
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);

			} else {
				// If both priceResto and priceService have non-zero values, update both inputs
				$('#priceTag_resto').val(sum);
				$('#priceTag_endo').val(sum);
				$('#priceTag_pro').val(sum);
			}
		}

		// Add event listeners to handle changes in price_tooth_restorative and price_tooth inputs
		document.getElementById('price_tooth_restorative').addEventListener('change', () => {
			priceResto = parseFloat(document.getElementById('price_tooth_restorative').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth_pro').addEventListener('change', () => {
			priceProsthodontics = parseFloat(document.getElementById('price_tooth_pro').value) || 0;
			handleResults();
		});

		document.getElementById('price_tooth').addEventListener('change', () => {
			priceService = parseFloat(document.getElementById('price_tooth').value) || 0;
			handleResults();
		});

		// Call the functions with the appropriate callback functions
		service_price_resto('#services_restorative', '#services_input_restorative', '#price_tooth_restorative', handlePriceResto);
		service_price('#services', '#services_input', '#price_tooth', handlePriceService);
		service_price_pro('#services_pro', '#services_input_pro', '#price_tooth_pro', handlePriceProsthodontics);
	}

	// TODO: the fucking services end


	let pains = document.getElementById('pains').innerHTML;
	let doctor_id = document.getElementById('doctor_id').innerHTML;
	let gender = document.getElementById('gender').innerHTML;

	function edit_profile(id = <?= $profile['id'] ?>) {
		$.ajax({
			url: "<?= base_url('admin/edit_patient') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug').val(result['content']['slug']);
					$('#name').val(result['content']['name']);
					$('#lname').val(result['content']['lname']);
					$('#age').val(result['content']['age']);
					$('#phone1').val(result['content']['phone1']);
					$('#phone2').val(result['content']['phone2']);
					$('#other_pains').val(result['content']['other_pains']);
					$('#address').val(result['content']['address']);
					$('#remarks').val(result['content']['remarks']);
					$('#model_value').val(result['content']['pains']);
					let pains_selected = result['content']['pains_select'];
					let pains_new = pains;
					pains_selected.map((item) => {
						pains_new = pains_new.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					});
					$("#pains").html(pains_new);

					let gender_new = gender;
					gender_new = gender_new.replace(`<option value="${result['content']['gender']}">`, `<option value="${result['content']['gender']}" selected>`);
					$("#gender").html(gender_new);


					let doctor = doctor_id;
					doctor = doctor.replace(`<option value="${result['content']['doctor_id']}">`, `<option value="${result['content']['doctor_id']}" selected>`);
					$("#doctor_id").html(doctor);


					// select_with_search('edit_profile');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		jalaliDatepicker.startWatch();
	});
</script>

<script>
	function check_turns(selectElement = document.getElementById('reference_doctor'), date = $('#test-date-id-date').val(), doctor = $('#reference_doctor').val(), tableId = '#turnsTableModal') {
		const time = document.getElementById("hour_insert");
		const timeOption = time.value;
		$.ajax({
			url: "<?= base_url('admin/check_turns') ?>",
			type: 'POST',
			data: {
				date: date,
				doctor: doctor,
				patient_time: timeOption
			},
			success: function (response) {
				var result = JSON.parse(response);
				var turns = result['content']['turns'];


				if (result['type'] == 'success') {
					if (turns.length != 0) {
						var tableTemplate =
							`<table class="table text-nowrap table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col"><?= $ci->lang('patient name') ?></th>
                                            <th scope="col"><?= $ci->lang('reference doctor') ?></th>
                                            <th scope="col"><?= $ci->lang('hour') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

						turns.map((item) => {
							tableTemplate += `<tr>
                                            <th scope="row">${item['patient_name']}</th>
                                            <td>${item['doctor_name']}</td>
                                            <td>${item['hour']}</td>
                                        </tr>`;
						})


						tableTemplate += `</tbody>
                                </table>`;
					} else {
						var tableTemplate = ``;
					}
					$(tableId).html(tableTemplate);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}

				// if in the date & time turn was already taken this will alert for the user
				if (result['alert']) {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>


<script>
	function edit_turn(id) {
		$.ajax({
			url: "<?= base_url('admin/single_turn') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug_turn').val(result['content']['slug']);
					$('#date_turn').val(result['content']['date']);
					$('#dateTurnOld').val(result['content']['date']);
					$('#hourTurnOld').val(result['content']['hour']);
					$('#doctorTurnOld').val(result['content']['doctor_id']);
					let doctor = $('#doctor_id_turn').html();
					replacer(doctor, result['content']['doctor_id'], 'doctor_id_turn');

					let hour = $('#hour_turn').html();
					replacer(hour, result['content']['hour'], 'hour_turn');
					$('#update_turn').modal('toggle');
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>

<script>
	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}
</script>

<script>
	function print_payment(turnId) {
		update_balance();
		window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
	}
</script>

<script>
	function print_lab(labId) {
		window.open(`<?= base_url() ?>admin/print_lab/${labId}`, '_blank');
	}
</script>

<script>
	function print_prescription(prescriptionId) {
		window.open(`<?= base_url() ?>admin/print_prescription/${prescriptionId}`, '_blank');
	}
</script>

<script>
	function payment_modal_clicked() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/list_turns_pending') ?>",
			type: 'POST',
			data: {
				slug: patient_id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					let turns = result['content']['turns'];
					let template = ``;
					if (turns.length > 0) {
						turns.map((item) => {
							template += `<option value="${item['id']}">${item['date']} - ${item['hour']}</option>`;
						})
					} else {
						template += `<option></option>`;
					}
					$('#select_turns').html(template);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		})
	}
</script>


<script>
	function update_balance() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/balance_json') ?>",
			type: 'POST',
			data: {
				record: patient_id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					let balanceTemplate =
						`
          <div class="d-flex align-items-center justify-content-between w-100">
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0" id="sum_fees">${result['balance']['sum_dr']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('fees') ?></p>
            </div>
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0" id="sum_paid">${result['balance']['sum_cr']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('paid') ?></p>
            </div>
            <div class="py-3 w-100 text-center">
              <p class="fw-bold fs-20  text-shadow mb-0 ${((result['balance']['sum'] > 0) ? `text-danger` : '')}" id="balance">${result['balance']['sum']}</p>
              <p class="mb-0 fs-12 text-muted "><?= $ci->lang('balance') ?></p>
            </div>
          </div>
          `;
					$('#percentage').html(result['balance']['percentage_text']);
					document.querySelector('.progress-bar.bg-primary.ronded-1').style.width = ((result['balance']['sum_dr'] != 0) ? (result['balance']['sum_cr'] * 100) / result['balance']['sum_dr'] : 100) + '%';

					document.querySelector(".card-body.p-0.main-profile-info").innerHTML = balanceTemplate;
				}
			}
		})
	}
</script>

<script>
	function list_teeth() {
		$.ajax({
			url: "<?= base_url('admin/list_teeth_json') ?>",
			type: 'POST',
			data: {
				record: <?= $profile['id'] ?>
			},
			success: function (response) {
				let result = JSON.parse(response);
				let teeth = result.content.teeth;
				if (result['type'] == 'success') {
					if (teeth.length != 0) {
						let tableTemplate =
							`
              <div class="table-responsive">
                <table class="table text-nowrap" id="teethTable">
                  <thead class="tableHead">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col"><?= $ci->lang('tooth name') ?></th>
                      <th scope="col"><?= $ci->lang('tooth location') ?></th>
                      <th scope="col"><?= $ci->lang('diagnose') ?></th>
                      <th scope="col"><?= $ci->lang('services') ?></th>
                      <th scope="col"><?= $ci->lang('pay amount') ?></th>
                      <th scope="col"><?= $ci->lang('actions') ?></th>
                    </tr>
                  </thead>
                  <tbody>`;

						teeth.map((tooth) => {
							tableTemplate +=
								`
                    <tr id="${tooth['id']}" class="tableRow">
                        <td scope="row">${tooth['number']}</td>
                        <td>${tooth['name']}</td>
                        <td>${tooth['location']}</td>
                        <td>${tooth['diagnose']}</td>
                        <td>${tooth['services']}</td>
                        <td>${tooth['price']}</td>
                        <td>
                          <div class="g-2">
                            <a href="javascript:updateTeeth('${tooth['id']}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-pen-to-square fs-14"></span></a>
                            <a href="javascript:delete_via_alert('${tooth['id']}', '<?= base_url() ?>admin/delete_tooth', 'teethTable', update_balance)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-trash-can fs-14"></span></a>
                          </div>
                        </td>
                      </tr>
                    `;
						})

						tableTemplate +=
							`
                  </tbody>
                </table>
              </div>
            `;
						$('#teeth_list').html(tableTemplate);
						update_balance();
					} else {
						var tableTemplate = ``;
						$('#teeth_list').html(tableTemplate);
					}
					// $('#teeth_list').html(tableTemplate);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		});
	}
</script>


<!-- Modal 4 (baby-edit) --------------------------------------------------------------------------------------------------baby -->
<div class="modal fade effect-scale" tabindex="-1" id="teethmodal_baby" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('Edit Child\'s Teeth') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-10">
						<form id="update_Toothbaby">
							<div class="row">


								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('tooth name') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="toothnamebaby4" name="name"
												class="form-control select2-show-search form-select"
												onchange="imageChange('#toothnamebaby4', '#toothlocationbaby4', '#modalImageb_edit', '#modalTitleb', '#imgAddress_update_baby')"
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
										<input type="hidden" name="slug" id="slug_update_teeth_baby">
										<input type="hidden" name="imgAddress" id="imgAddress_update_baby">
										<label class="form-label">
											<?= $ci->lang('tooth location') ?> <span class="text-red">*</span>
										</label>

										<select id="toothlocationbaby4" name="location"
												class="form-control select2-show-search form-select"
												onchange="imageChange('#toothnamebaby4', '#toothlocationbaby4', '#modalImageb_edit', '#modalTitleb', '#imgAddress_update_baby')"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
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

										<select id="toothbcanal4" name="root_number"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="showRow('#toothbcanal4', '#firstRowb4', '#secoundRowb4', '#thirdRowb4', '#fourthRowb4', '#fifthRowb4')">
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
							<!-- maybe you shold wrap these rows onto a div just div -->
							<div class="row" id="firstRowb4" style="display: none;">
								<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>
										</label>

										<select name="r_name1" class="form-control select2-show-search form-select"
												id="canalbLocation14" data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>">
													<?= $value ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>


								<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>
										</label>
										<input id="canalblength14" type="number" name="r_width1" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">
									</div>
								</div>

							</div>

							<div class="row" id="secoundRowb4" style="display: none;">


								<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>

										</label>


										<select name="r_name2" class="form-control select2-show-search form-select"
												id="canalbLocation24" data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>">
													<?= $value ?>
												</option>
											<?php endforeach; ?>

										</select>
									</div>
								</div>


								<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>
										</label>


										<input id="canalblength24" type="number" name="r_width2" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">

									</div>
								</div>

							</div>

							<div class="row" id="thirdRowb4" style="display: none;">

								<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>
										</label>


										<select name="r_name3" class="form-control select2-show-search form-select"
												id="canalbLocation34" data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>">
													<?= $value ?>
												</option>
											<?php endforeach; ?>

										</select>
									</div>
								</div>


								<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>
										</label>


										<input id="canalblength34" name="r_width3" type="number" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">

									</div>
								</div>

							</div>

							<div class="row" id="fourthRowb4" style="display: none;">

								<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>

										</label>


										<select name="r_name4" class="form-control select2-show-search form-select"
												id="canalbLocation44" data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>">
													<?= $value ?>
												</option>
											<?php endforeach; ?>

										</select>
									</div>
								</div>


								<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>

										</label>


										<input id="canalblength44" name="r_width4" type="number" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">

									</div>
								</div>

							</div>

							<div class="row" id="fifthRowb4" style="display: none;">

								<div class="col-sm-12 col-md-6"> <!-- Canal Location -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal location') ?>

										</label>


										<select name="r_name5" class="form-control select2-show-search form-select"
												id="canalbLocation54" data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->canal_names() as $key => $value) : ?>
												<option value="<?= $key ?>">
													<?= $value ?>
												</option>
											<?php endforeach; ?>

										</select>
									</div>
								</div>


								<div class="col-sm-12 col-md-6"> <!-- Canal Length -->
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('canal length') ?>

										</label>


										<input id="canalblength54" name="r_width5" type="number" class="form-control"
											   placeholder="<?= $ci->lang('canal length') ?>">

									</div>
								</div>

							</div>


							<div class="row">

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('diagnose') ?> <span class="text-red">*</span>
										</label>
										<textarea class="form-control" name="diagnose"
												  placeholder="<?= $ci->lang('diagnose') ?>" id='bdiagnose4'></textarea>
									</div>
								</div>

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('description') ?>
										</label>
										<textarea class="form-control" name="details"
												  placeholder="<?= $ci->lang('description') ?>"
												  id="bdescription4"></textarea>
									</div>
								</div>


								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('treatment') ?> <span class="text-red">*</span>
										</label>


										<select name="type" class="form-control select2-show-search form-select"
												id="bservices4"
												onchange="service_price('#bservices4', '#services_inputb', '#price_toothb4')"
												data-placeholder="<?= $ci->lang('select') ?>" multiple>
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($services as $service) : ?>

												<option value="<?= $service['id'] ?>">
													<?= $service['name'] ?>
												</option>

											<?php endforeach; ?>

										</select>
										<input type="hidden" name="services" id="services_inputb">
									</div>
								</div>

								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="price" id="price_toothb4" class="form-control"
											   placeholder="<?= $ci->lang('pay amount') ?>">
									</div>
								</div>


							</div>

						</form>
					</div>

					<div class="col-md-2">

						<div class="modal-image-area">
							<h2 id="modalTitleb">teeth</h2>
							<img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png"
								 class="modalimg" id="modalImageb_edit">
						</div>

					</div>


				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('update_Toothbaby', '<?= base_url() ?>admin/update_tooth', '','teethmodal_baby', list_teeth)">
					<?= $ci->lang('save') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal 4 (baby-edit) --------------------------------------------------------------------------------------------------baby -->


<!-- TODO: Old edit update -->
<!-- edit function Old ------------------------ start-->
<!-- <script>
  function dummymodal(id) {
    $.ajax({
      url: "<?= base_url('admin/single_tooth') ?>",
      type: 'POST',
      data: {
        slug: id,
      },
      success: function(response) {
        let result = JSON.parse(response);
        let dummydent = result.content;
        if (typeof dummydent.name === 'string' && !isNaN(dummydent.name)) {

          $('#toothdname').val(dummydent.name).trigger('change');
          $('#toothdlocation').val(dummydent.location).trigger('change');
          $('#toothdcanal').val(dummydent.root_number).trigger('change');

          $('#canaldLocation1').val(dummydent.r_name1).trigger('change');
          $('#canaldlength1').val(dummydent.r_width1).trigger('change');

          $('#canaldLocation2').val(dummydent.r_name2).trigger('change');
          $('#canaldlength2').val(dummydent.r_width2).trigger('change');

          $('#canaldLocation3').val(dummydent.r_name3).trigger('change');
          $('#canaldlength3').val(dummydent.r_width3).trigger('change');

          $('#canaldLocation4').val(dummydent.r_name4).trigger('change');
          $('#canaldlength4').val(dummydent.r_width4).trigger('change');

          $('#canaldLocation5').val(dummydent.r_name5).trigger('change');
          $('#canaldlength5').val(dummydent.r_width5).trigger('change');

          let dentServices = dummydent.services;
          $('#services_input232').val(dummydent.servicesNoArray);
          var servicesinnerHTML = document.getElementById("dservices").innerHTML;
          dentServices.map((item) => {
            servicesinnerHTML = servicesinnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
          })
          document.getElementById("dservices").innerHTML = servicesinnerHTML;
          $('#price_toothd').val(dummydent.price).trigger('change');
          $('#slug_update_teeth_adult').val(dummydent.slug);
          $('#imgAddress_update_adult').val(dummydent.imgAddress);
          $('#modalImage2').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${dummydent.imgAddress}`);

          $('#ddiagnose').val(dummydent.diagnose).trigger('change');
          $('#ddescription').val(dummydent.details).trigger('change');


          $(`#teethmodal_dummy`).modal('toggle');
        } else if (typeof dummydent.name === 'string' && /^[a-tA-T]+$/.test(dummydent.name)) {

          $('#slug_update_teeth_baby').val(dummydent.slug);

          $('#services_inputb').val(dummydent.servicesNoArray);
          $('#imgAddress_update_baby').val(dummydent.imgAddress);
          $('#modalImageb_edit').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${dummydent.imgAddress}`);

          $('#toothnamebaby4').val(dummydent.name).trigger('change');
          $('#toothlocationbaby4').val(dummydent.location).trigger('change');
          $('#toothbcanal4').val(dummydent.root_number).trigger('change');

          $('#canalbLocation14').val(dummydent.r_name1).trigger('change');
          $('#canalblength14').val(dummydent.r_width1).trigger('change');

          $('#canalbLocation24').val(dummydent.r_name2).trigger('change');
          $('#canalblength24').val(dummydent.r_width2).trigger('change');

          $('#canalbLocation34').val(dummydent.r_name3).trigger('change');
          $('#canaldlength34').val(dummydent.r_width3).trigger('change');

          $('#canaldLocation44').val(dummydent.r_name4).trigger('change');
          $('#canalblength44').val(dummydent.r_width4).trigger('change');

          $('#canaldLocation54').val(dummydent.r_name5).trigger('change');
          $('#canalblength54').val(dummydent.r_width5).trigger('change');

          let dentServices = dummydent.services;
          var servicesinnerHTML = document.getElementById("bservices4").innerHTML;
          dentServices.map((item) => {
            servicesinnerHTML = servicesinnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
          })
          document.getElementById("bservices4").innerHTML = servicesinnerHTML;

          $('#price_toothb4').val(dummydent.price).trigger('change');


          $('#bdiagnose4').val(dummydent.diagnose).trigger('change');
          $('#bdescription4').val(dummydent.details).trigger('change');


          $(`#teethmodal_baby`).modal('toggle');
        } else {
          console.alert('Invalid value for "name"');
        }

      }
    });


  }
</script> -->
<!-- edit function Old ------------------------ start-->


<!-- conpied -->
<script>
	function showRow(canalselectorid = "#canalselector", firstRowid = "#firstRow", secoundRowid = "#secoundRow", thirdRowid = "#thirdRow", fourthRowid = "#fourthRow", fifthRowid = "#fifthRow") {
		let canalselector = $(canalselectorid).val();
		if (canalselector == 1) {
			$(firstRowid).show();
			$(secoundRowid).hide();
			$(thirdRowid).hide();
			$(fourthRowid).hide();
			$(fifthRowid).hide();
		} else if (canalselector == 2) {
			$(firstRowid).show();
			$(secoundRowid).show();
			$(thirdRowid).hide();
			$(fourthRowid).hide();
			$(fifthRowid).hide();
		} else if (canalselector == 3) {
			$(firstRowid).show();
			$(secoundRowid).show();
			$(thirdRowid).show();
			$(fourthRowid).hide();
			$(fifthRowid).hide();
		} else if (canalselector == 4) {
			$(firstRowid).show();
			$(secoundRowid).show();
			$(thirdRowid).show();
			$(fourthRowid).show();
			$(fifthRowid).hide();
		} else if (canalselector == 5) {
			$(firstRowid).show();
			$(secoundRowid).show();
			$(thirdRowid).show();
			$(fourthRowid).show();
			$(fifthRowid).show();
		} else {
			$(firstRowid).hide();
			$(secoundRowid).hide();
			$(thirdRowid).hide();
			$(fourthRowid).hide();
			$(fifthRowid).hide();
		}

	}
</script>


<script>
	function imageChange(toothNameId, toothLocationId, imagePlace, tittleId, imgAddress) {
		var toothName = $(toothNameId).val();
		var toothLocation = $(toothLocationId).val();
		// console.log(`id: ${typeof toothName} and location ${typeof toothLocation}`);

		if (typeof toothName === 'string' && !isNaN(toothName)) {
			// up q1 --------------------------------------------------------------------------------------------------
			if (toothName == "1" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png');
				$(imgAddress).val('/v2/up/PNG/8upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/7upup.png');
				$(imgAddress).val('/v2/up/PNG/7upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/6upup.png');
				$(imgAddress).val('/v2/up/PNG/6upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/5upup.png');
				$(imgAddress).val('/v2/up/PNG/5upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/4upup.png');
				$(imgAddress).val('/v2/up/PNG/4upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/3upup.png');
				$(imgAddress).val('/v2/up/PNG/3upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/2upup.png');
				$(imgAddress).val('/v2/up/PNG/2upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/1upup.png');
				$(imgAddress).val('/v2/up/PNG/1upup.png');
				$(tittleId).text(toothName);
			}
			// up q2--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/9upup.png');
				$(imgAddress).val('/v2/up/PNG/9upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/10upup.png');
				$(imgAddress).val('/v2/up/PNG/10upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/11upup.png');
				$(imgAddress).val('/v2/up/PNG/11upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/12upup.png');
				$(imgAddress).val('/v2/up/PNG/12upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/13upup.png');
				$(imgAddress).val('/v2/up/PNG/13upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/14upup.png');
				$(imgAddress).val('/v2/up/PNG/14upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/15upup.png');
				$(imgAddress).val('/v2/up/PNG/15upup.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/16upup.png');
				$(imgAddress).val('/v2/up/PNG/16upup.png');
				$(tittleId).text(toothName);
			}
			// down q1--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/24do.png');
				$(imgAddress).val('/v2/down/PNG/24do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/23do.png');
				$(imgAddress).val('/v2/down/PNG/23do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/22do.png');
				$(imgAddress).val('/v2/down/PNG/22do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/21do.png');
				$(imgAddress).val('/v2/down/PNG/21do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/20do.png');
				$(imgAddress).val('/v2/down/PNG/20do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/19do.png');
				$(imgAddress).val('/v2/down/PNG/19do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/18do.png');
				$(imgAddress).val('/v2/down/PNG/18do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/17do.png');
				$(imgAddress).val('/v2/down/PNG/17do.png');
				$(tittleId).text(toothName);
			}
			// down q1--------------------------------------------------------------------------------------------------
			else if (toothName == "1" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/25do.png');
				$(imgAddress).val('/v2/down/PNG/25do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "2" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/26do.png');
				$(imgAddress).val('/v2/down/PNG/26do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "3" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/27do.png');
				$(imgAddress).val('/v2/down/PNG/27do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "4" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/28do.png');
				$(imgAddress).val('/v2/down/PNG/28do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "5" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/29do.png');
				$(imgAddress).val('/v2/down/PNG/29do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "6" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/30do.png');
				$(imgAddress).val('/v2/down/PNG/30do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "7" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/31do.png');
				$(imgAddress).val('/v2/down/PNG/31do.png');
				$(tittleId).text(toothName);
			} else if (toothName == "8" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/down/PNG/32do.png');
				$(imgAddress).val('/v2/down/PNG/32do.png');
				$(tittleId).text(toothName);
			}
		} else if (typeof toothName === 'string' && /^[a-tA-T]+$/.test(toothName)) {
			// up q1 -baby --------------------------------------------------------------------------------------------------
			if (toothName == "A" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/f.png');
				$(imgAddress).val('/v2/baby/f.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/g.png');
				$(imgAddress).val('/v2/baby/g.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/h.png');
				$(imgAddress).val('/v2/baby/h.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/i.png');
				$(imgAddress).val('/v2/baby/i.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "1") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/j.png');
				$(imgAddress).val('/v2/baby/j.png');
				$(tittleId).text(toothName);
			}
			// up q2 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/e.png');
				$(imgAddress).val('/v2/baby/e.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/d.png');
				$(imgAddress).val('/v2/baby/d.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/c.png');
				$(imgAddress).val('/v2/baby/c.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/b.png');
				$(imgAddress).val('/v2/baby/b.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "2") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/a.png');
				$(imgAddress).val('/v2/baby/a.png');
				$(tittleId).text(toothName);
			}
			// down q1 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/o.png');
				$(imgAddress).val('/v2/baby/o.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/n.png');
				$(imgAddress).val('/v2/baby/n.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/m.png');
				$(imgAddress).val('/v2/baby/m.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/l.png');
				$(imgAddress).val('/v2/baby/l.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "3") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/k.png');
				$(imgAddress).val('/v2/baby/k.png');
				$(tittleId).text(toothName);
			}
			// down q2 -baby --------------------------------------------------------------------------------------------------
			else if (toothName == "A" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/p.png');
				$(imgAddress).val('/v2/baby/p.png');
				$(tittleId).text(toothName);
			} else if (toothName == "B" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/q.png');
				$(imgAddress).val('/v2/baby/q.png');
				$(tittleId).text(toothName);
			} else if (toothName == "C" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/r.png');
				$(imgAddress).val('/v2/baby/r.png');
				$(tittleId).text(toothName);
			} else if (toothName == "D" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/s.png');
				$(imgAddress).val('/v2/baby/s.png');
				$(tittleId).text(toothName);
			} else if (toothName == "E" && toothLocation == "4") {
				$(imagePlace).attr('src', '<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/t.png');
				$(imgAddress).val('/v2/baby/t.png');
				$(tittleId).text(toothName);
			}
		} else {

		}
	}
</script>


<!-- Modal Start laboratoryInsertModal --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="laboratoryInsertModal" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					<?= $ci->lang('insert laboratory expenses') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">

					<div class="col-md-12">

						<form id="tableInsert">
							<div class="row">

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('laboratory') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="selectLab" name="customers_id"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($lab_accounts as $lab_account) : ?>
												<option
													value="<?= $lab_account['id'] ?>"><?= $ci->mylibrary->account_name($lab_account['name'], $lab_account['lname']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('teeth') ?> <span class="text-red">*</span>
										</label>

										<select id="selectTeeth" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectTeeth', '#selectTeethHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($teeth as $tooth) : ?>
												<option value="<?= $tooth["id"] ?>"> <?= $tooth["name"] ?> -
													(<?= $ci->dentist->find_location($tooth['location']) ?>)
												</option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="selectTeethHiddenInput" name="teeth">
										<input type="hidden" id="" name="patient_id" value="<?= $profile['id'] ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group" id="main-divs">
										<label class="form-label">
											<?= $ci->lang('tooth type') ?>
										</label>

										<select id="selectToothType"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectToothType', '#selectToothTypeHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->teeth_type() as $key => $value) : ?>
												<option value="<?= $key ?>"><?= $value ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="selectToothTypeHiddenInput" name="type">

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('tooth color') ?><span class="text-red">*</span>
										</label>

										<select id="ToothColor" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#ToothColor', '#selectToothColorTypeHiddenInput')">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->teeth_color() as $color) : ?>

												<option value="<?= $color ?>"><?= $color ?></option>

											<?php endforeach; ?>

										</select>

										<input type="hidden" name="color" id="selectToothColorTypeHiddenInput">

									</div>
								</div>


							</div>
							<div class="row">

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('delivery date') ?> <span class="text-red">*</span>
										</label>

										<input data-jdp type="text" id="deliveryDate" name="give_date"
											   class="form-control">

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('delivery time') ?> <span class="text-red">*</span>
										</label>

										<select name="hour" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" id="deliveryTime">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->hours() as $hour) :
												?>
												<option value="<?= $hour['key'] ?>">
													<?= $hour['value'] ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="dr" id="payment" class="form-control"
											   placeholder="<?= $ci->lang('pay amount') ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Number of units') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="numberofUnits" id="numberofunite"
											   class="form-control" placeholder="<?= $ci->lang('Number of units') ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-12">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('description') ?>
										</label>
										<textarea class="form-control" name="remarks"
												  placeholder="<?= $ci->lang('description') ?>" rows="5"></textarea>
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
				<button class="btn btn-warning"
						onclick="submitWithoutDatatable('tableInsert', '<?= base_url() ?>admin/insert_lab', 'labsTable', 'laboratoryInsertModal', print_lab ,'print'); list_labs()">
					<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('tableInsert', '<?= base_url() ?>admin/insert_lab', 'labsTable', 'laboratoryInsertModal', list_labs)">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End laboratoryInsertModal -------------------------------------------------------------------------------------------------->


<script>
	function request(selectId = "#selectTeeth") {

		$.ajax({
			url: "<?= base_url('admin/patient_teeth') ?>",
			type: 'POST',
			data: {
				id: <?= $profile["id"] ?>,
			},
			success: function (response) {
				let result = JSON.parse(response);
				let options = "";
				if (result.type == "success") {
					result.teeth.map((item) => {
						options += `<option value="${item.id}">${item.name} (${item.location})</option>`
					})
				}
				$(selectId).html(options);


			}
		});


	}

	function imageReplacer() {

	}
</script>


<!-- Modal Start laboratoryEditModal --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="laboratoryEditModal" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					<?= $ci->lang('insert laboratory expenses') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">

					<div class="col-md-12">

						<form id="tableEdit">
							<div class="row">

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('laboratory') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="selectLab_edit" name="customers_id"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($lab_accounts as $lab_account) : ?>
												<option
													value="<?= $lab_account['id'] ?>"><?= $ci->mylibrary->account_name($lab_account['name'], $lab_account['lname']) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('teeth') ?> <span class="text-red">*</span>
										</label>

										<select id="selectTeeth_edit"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectTeeth_edit', '#selectTeethHiddenInput_edit')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($teeth as $tooth) : ?>
												<option value="<?= $tooth["id"] ?>"> <?= $tooth["name"] ?> -
													(<?= $ci->dentist->find_location($tooth['location']) ?>)
												</option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="selectTeethHiddenInput_edit" name="teeth">
									</div>
								</div>

								<div class="col-sm-12 col-md-4">
									<div class="form-group" id="main-divs">
										<label class="form-label">
											<?= $ci->lang('tooth type') ?>
										</label>

										<select id="selectToothType_edit"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" multiple
												onchange="multiple_value('#selectToothType_edit', '#selectToothTypeHiddenInput_edit')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->teeth_type() as $key => $value) : ?>
												<option value="<?= $key ?>"><?= $value ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="selectToothTypeHiddenInput_edit" name="type">

									</div>
								</div>
							</div>
							<div class="row">

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('delivery date') ?> <span class="text-red">*</span>
										</label>

										<input data-jdp type="text" id="deliveryDate_edit" name="give_date"
											   class="form-control">

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('delivery time') ?> <span class="text-red">*</span>
										</label>

										<select name="hour" class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>" id="deliveryTime_edit">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->hours() as $hour) :
												?>
												<option value="<?= $hour['key'] ?>">
													<?= $hour['value'] ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('tooth color') ?><span class="text-red">*</span>
										</label>

										<select id="selectToothColor_edit" name="color"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>

											<?php foreach ($ci->dentist->teeth_color() as $color) : ?>

												<option value="<?= $color ?>"><?= $color ?></option>

											<?php endforeach; ?>

										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-3">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
										</label>
										<input type="number" name="dr" id="payment_edit" class="form-control"
											   placeholder="<?= $ci->lang('pay amount') ?>">
										<input type="hidden" name="slug" id="labIdSlug">
									</div>
								</div>

								<div class="col-sm-12 col-md-12">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('description') ?>
										</label>
										<textarea class="form-control" name="remarks" id="details_edit"
												  placeholder="<?= $ci->lang('description') ?>"></textarea>
									</div>
								</div>

							</div>
						</form>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('tableEdit', '<?= base_url() ?>admin/update_lab', 'labsTable', 'laboratoryEditModal', list_labs )">
					<?= $ci->lang('update') ?> <i class="fa fa-edit"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End laboratoryEditModal -------------------------------------------------------------------------------------------------->


<!-- Modal start laboratoryIEditModal_Script -------------------------------------------------------------------------------------------------->
<script>
	function edit_lab(id) {
		$.ajax({
			url: "<?= base_url('admin/single_lab') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				request('#selectTeeth_edit');
				let results = JSON.parse(response);
				let data = results.content;
				// console.log(data);

				$('#selectLab_edit').val(data.lab_id).trigger('change');


				let tooth = data.teeth;
				var teethInnerHTML = document.getElementById("selectTeeth_edit").innerHTML;
				tooth.map((item) => {
					console.log(item);
					teethInnerHTML = teethInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
				})
				document.getElementById("selectTeeth_edit").innerHTML = teethInnerHTML;
				$('#selectTeethHiddenInput_edit').val(data.teeth_hidden);


				let toothtypes = data.types;
				var toothtypesInnerHTML = document.getElementById("selectToothType_edit").innerHTML;
				toothtypes.map((item) => {
					console.log(`this is the items: ${item}`);
					toothtypesInnerHTML = toothtypesInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
				})
				document.getElementById("selectToothType_edit").innerHTML = toothtypesInnerHTML;
				$('#selectToothTypeHiddenInput_edit').val(data.types_hidden);


				$('#deliveryDate_edit').val(data.delivery_date).trigger('change');
				$('#deliveryTime_edit').val(data.delivery_time).trigger('change');
				$('#selectToothColor_edit').val(data.tooth_color).trigger('change');
				$('#payment_edit').val(data.pay_amount).trigger('change');
				$('#details_edit').val(data.remarks).trigger('change');
				$('#labIdSlug').val(id);
			}
		})


		$(`#laboratoryEditModal`).modal('toggle');
	}
</script>
<!-- Modal End laboratoryEdittModal_Script -------------------------------------------------------------------------------------------------->


<!-- list labs function---start--- -->
<script>
	function list_labs() {
		$.ajax({
			url: "<?= base_url('admin/list_labs_json') ?>",
			type: 'POST',
			data: {
				record: <?= $profile['id'] ?>
			},
			success: function (response) {
				let result = JSON.parse(response);
				let labs = result.content.labs;
				if (result['type'] == 'success') {
					if (labs.length != 0) {
						let tableTemplate =
							`
              <div class="table-responsive">
                <table class="table text-nowrap" id="labsTable">
                  <thead class="tableHead">
                    <tr>
                    <th scope="col">#</th>
                          <th scope="col"><?= $ci->lang('laboratory') ?></th>
                          <th scope="col"><?= $ci->lang('teeth') ?></th>
                          <th scope="col"><?= $ci->lang('tooth type') ?></th>
                          <th scope="col"><?= $ci->lang('delivery date') ?></th>
                          <th scope="col"><?= $ci->lang('delivery time') ?></th>
                          <th scope="col"><?= $ci->lang('pay amount') ?></th>
                          <th scope="col"><?= $ci->lang('desc') ?></th>
                          <th scope="col"><?= $ci->lang('actions') ?></th>
                    </tr>
                  </thead>
                  <tbody>`;

						labs.map((lab) => {
							tableTemplate +=
								`
                    <tr id="${lab['id']}" class="tableRow">
                        <td scope="row">${lab['number']}</td>
                        <td>${lab['lab_name']}</td>
                        <td>${lab['teeth']}</td>
                        <td>${lab['type']}</td>
                        <td>${lab['delivery_date']}</td>
                        <td>${lab['delivery_time']}</td>
                        <td>${lab['pay_amount']}</td>
                        <td>${lab['remarks']}</td>
                        <td>
                          <div class="g-2">
                            <a href="javascript:edit_lab('${lab['id']}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa-regular fa-pen-to-square fs-14"></span></a>
                            <a href="javascript:print_lab('${lab['id']}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-print fs-14"></span></a>
                            <a href="javascript:delete_via_alert('${lab['id']}', '<?= base_url() ?>admin/delete_lab', 'labsTable')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-trash-can fs-14"></span></a>
                          </div>
                        </td>
                      </tr>
                    `;
						})

						tableTemplate +=
							`
                  </tbody>
                </table>
              </div>
            `;
						$('#posts-tab-pane').html(tableTemplate);
						update_balance();
					} else {
						var tableTemplate = ``;
						$('#posts-tab-pane').html(tableTemplate);
					}
					// $('#teeth_list').html(tableTemplate);
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		});
	}
</script>
<!-- list labs function---end--- -->


<!-- prescription Prescription Start --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="insertPrescription" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					<?= $ci->lang('insert prescription') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">

					<div class="col-md-12">

						<form id="prescriptions_setMedicines">
							<!-- row 1 -->
							<div class="row">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
										<!-- this is an important select tag remember it -->
										<select id="set_medicine1" name="medicine_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx1', 'medicineUnite_Rx1', 'set_medicineUsage1', 'set_medicineDay1', 'set_medicineTime1', 'set_medicineAmount1')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_1" id="medicineDoze_Rx1"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx1" name="unit_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage1" name="usageType_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_1" class="form-control arrowLessInput"
											   id="set_medicineDay1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_1" class="form-control arrowLessInput"
											   id="set_medicineTime1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_1" class="form-control arrowLessInput"
											   id="set_medicineAmount1">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusBtn1" class="icon-btn add-btn" type="button"
													onclick=" plusBtn('setMedicien_row2', 'plusBtn1')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
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
							<div class="row" id="setMedicien_row2" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine2" name="medicine_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_2" id="medicineDoze_Rx2"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx2" name="unit_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage2" name="usageType_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_2" class="form-control arrowLessInput"
											   id="set_medicineDay2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_2" class="form-control arrowLessInput"
											   id="set_medicineTime2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_2" class="form-control arrowLessInput"
											   id="set_medicineAmount2">

									</div>
								</div>

								<div class="col-sm-12 col-md-2" id="PRBtns2">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn2" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row3', 'plusbtn2')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row2', 'plusBtn1'), clearInput('set_medicine2', 'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 2 -->

							<!-- row 3 -->
							<div class="row" id="setMedicien_row3" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine3" name="medicine_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_3" id="medicineDoze_Rx3"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx3" name="unit_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage3" name="usageType_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_3" class="form-control arrowLessInput"
											   id="set_medicineDay3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_3" class="form-control arrowLessInput"
											   id="set_medicineTime3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_3" class="form-control arrowLessInput"
											   id="set_medicineAmount3">

									</div>
								</div>

								<div class="col-sm-12 col-md-2" id="PRBtns3">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn3" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row4', 'plusbtn3')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row3', 'plusbtn2'),clearInput('set_medicine3', 'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 3 -->

							<!-- row 4 -->
							<div class="row" id="setMedicien_row4" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine4" name="medicine_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_4" id="medicineDoze_Rx4"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx4" name="unit_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage4" name="usageType_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_4" class="form-control arrowLessInput"
											   id="set_medicineDay4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_4" class="form-control arrowLessInput"
											   id="set_medicineTime4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_4" class="form-control arrowLessInput"
											   id="set_medicineAmount4">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn4" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row5', 'plusbtn4')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row4', 'plusbtn3'), clearInput('set_medicine4', 'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 4 -->


							<!-- row 5 -->
							<div class="row" id="setMedicien_row5" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine5" name="medicine_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_5" id="medicineDoze_Rx5"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx5" name="unit_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage5" name="usageType_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_5" class="form-control arrowLessInput"
											   id="set_medicineDay5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_5" class="form-control arrowLessInput"
											   id="set_medicineTime5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_5" class="form-control arrowLessInput"
											   id="set_medicineAmount5">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns5" type="button">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn5" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row6','plusbtn5')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row5', 'plusbtn4'), clearInput('set_medicine5', 'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 5 -->

							<!-- row 6 -->
							<div class="row" id="setMedicien_row6" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine6" name="medicine_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_6" id="medicineDoze_Rx6"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx6" name="unit_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage6" name="usageType_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_6" class="form-control arrowLessInput"
											   id="set_medicineDay6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_6" class="form-control arrowLessInput"
											   id="set_medicineTime6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_6" class="form-control arrowLessInput"
											   id="set_medicineAmount6">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn6" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row7', 'plusbtn6')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row6', 'plusbtn5'), clearInput('set_medicine6', 'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 6 -->

							<!-- row 7 -->
							<div class="row" id="setMedicien_row7" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine7" name="medicine_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_7" id="medicineDoze_Rx7"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx7" name="unit_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage7" name="usageType_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_7" class="form-control arrowLessInput"
											   id="set_medicineDay7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_7" class="form-control arrowLessInput"
											   id="set_medicineTime7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_7" class="form-control arrowLessInput"
											   id="set_medicineAmount7">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn7" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row8', 'plusbtn7')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row7', 'plusbtn6'), clearInput('set_medicine7', 'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 7 -->

							<!-- row 8 -->
							<div class="row" id="setMedicien_row8" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine8" name="medicine_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_8" id="medicineDoze_Rx8"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx8" name="unit_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage8" name="usageType_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_8" class="form-control arrowLessInput"
											   id="set_medicineDay8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_8" class="form-control arrowLessInput"
											   id="set_medicineTime8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_8" class="form-control arrowLessInput"
											   id="set_medicineAmount8">

									</div>
								</div>
								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn8" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row9', 'plusbtn8')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row8', 'plusbtn7'), clearInput('set_medicine8', 'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 8 -->

							<!-- row 9 -->
							<div class="row" id="setMedicien_row9" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine9" name="medicine_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_9" id="medicineDoze_Rx9"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx9" name="unit_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage9" name="usageType_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_9" class="form-control arrowLessInput"
											   id="set_medicineDay9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_9" class="form-control arrowLessInput"
											   id="set_medicineTime9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_9" class="form-control arrowLessInput"
											   id="set_medicineAmount9">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn9" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row10', 'plusbtn9')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row9', 'plusbtn8'), clearInput('set_medicine9', 'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 9 -->

							<!-- row 10 -->
							<div class="row" id="setMedicien_row10" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine10" name="medicine_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_10" id="medicineDoze_Rx10"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx10" name="unit_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="set_medicineUsage10" name="usageType_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">


											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_10" class="form-control arrowLessInput"
											   id="set_medicineDay10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_10" class="form-control arrowLessInput"
											   id="set_medicineTime10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_10" class="form-control arrowLessInput"
											   id="set_medicineAmount10">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4">
										<!-- <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn10" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn10')">
                        <div class="add-icon"></div>
                        <div class="btn-txt"><?= $ci->lang('add') ?></div>
                      </button>
                    </div> -->
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row10', 'plusbtn9'), clearInput('set_medicine10', 'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 10 -->


						</form>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
				</button>
				<button class="btn btn-warning"
						onclick="submitWithoutDatatable('prescriptions_setMedicines', '<?= base_url() ?>admin/insert_prescription', 'prescriptionTable', 'insertPrescription', print_prescription, 'print')">
					<?= $ci->lang('save and print') ?> <i class="fe fe-printer"></i>
				</button>
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('prescriptions_setMedicines', '<?= base_url() ?>admin/insert_prescription', 'prescriptionTable', 'insertPrescription')">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- prescription SetMedicines End -------------------------------------------------------------------------------------------------->

<script>
	function getMedicienInfo(id, dozeId, unitId, usageId, dayId, timeId, amountId) {
		$.ajax({
			url: "<?= base_url('admin/single_medicine') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;
				$('#' + dozeId).val(medicienDatas.doze);
				$('#' + unitId).val(medicienDatas.unit).trigger('change');
				$('#' + usageId).val(medicienDatas.usageType).trigger('change');
				$('#' + dayId).val(medicienDatas.day);
				$('#' + timeId).val(medicienDatas.times);
				$('#' + amountId).val(medicienDatas.amount);
			}
		})

	}
</script>


<!-- prescription ViewMedicines Start --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="viewPrescriptionsMedicines" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					View Prescription's Medicines
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">

					<div class="col-md-12">

						<form id="prescriptions_viewMedicines">
							<!-- row 1 -->
							<div class="row viewRows" id="prescription_row1" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine1" name="medicine_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_1" id="medicineDoze_Rx1_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx1_view" name="unit_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage1" name="usageType_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_1" class="form-control arrowLessInput"
											   id="view_medicineDay1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_1" class="form-control arrowLessInput"
											   id="view_medicineTime1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_1" class="form-control arrowLessInput"
											   id="view_medicineAmount1">

									</div>
								</div>

							</div>
							<!-- row 1 -->

							<!-- row 2 -->
							<div class="row viewRows" id="prescription_row2" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine2" name="medicine_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_2" id="medicineDoze_Rx2_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx2_view" name="unit_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage2" name="usageType_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_2" class="form-control arrowLessInput"
											   id="view_medicineDay2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_2" class="form-control arrowLessInput"
											   id="view_medicineTime2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_2" class="form-control arrowLessInput"
											   id="view_medicineAmount2">

									</div>
								</div>


							</div>
							<!-- row 2 -->

							<!-- row 3 -->
							<div class="row viewRows" id="prescription_row3" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine3" name="medicine_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_3" id="medicineDoze_Rx3_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx3_view" name="unit_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage3" name="usageType_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_3" class="form-control arrowLessInput"
											   id="view_medicineDay3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_3" class="form-control arrowLessInput"
											   id="view_medicineTime3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_3" class="form-control arrowLessInput"
											   id="view_medicineAmount3">

									</div>
								</div>

							</div>
							<!-- row 3 -->

							<!-- row 4 -->
							<div class="row viewRows" id="prescription_row4" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine4" name="medicine_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_4" id="medicineDoze_Rx4_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx4_view" name="unit_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage4" name="usageType_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_4" class="form-control arrowLessInput"
											   id="view_medicineDay4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_4" class="form-control arrowLessInput"
											   id="view_medicineTime4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_4" class="form-control arrowLessInput"
											   id="view_medicineAmount4">

									</div>
								</div>

							</div>
							<!-- row 4 -->


							<!-- row 5 -->
							<div class="row viewRows" id="prescription_row5" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine5" name="medicine_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_5" id="medicineDoze_Rx5_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx5_view" name="unit_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage5" name="usageType_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_5" class="form-control arrowLessInput"
											   id="view_medicineDay5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_5" class="form-control arrowLessInput"
											   id="view_medicineTime5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_5" class="form-control arrowLessInput"
											   id="view_medicineAmount5">

									</div>
								</div>


							</div>
							<!-- row 5 -->

							<!-- row 6 -->
							<div class="row viewRows" id="prescription_row6" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine6" name="medicine_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_6" id="medicineDoze_Rx6_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx6_view" name="unit_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage6" name="usageType_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_6" class="form-control arrowLessInput"
											   id="view_medicineDay6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_6" class="form-control arrowLessInput"
											   id="view_medicineTime6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_6" class="form-control arrowLessInput"
											   id="view_medicineAmount6">

									</div>
								</div>

							</div>
							<!-- row 6 -->

							<!-- row 7 -->
							<div class="row viewRows" id="prescription_row7" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine7" name="medicine_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_7" id="medicineDoze_Rx7_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx7_view" name="unit_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage7" name="usageType_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_7" class="form-control arrowLessInput"
											   id="view_medicineDay7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_7" class="form-control arrowLessInput"
											   id="view_medicineTime7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_7" class="form-control arrowLessInput"
											   id="view_medicineAmount7">

									</div>
								</div>

							</div>
							<!-- row 7 -->

							<!-- row 8 -->
							<div class="row viewRows" id="prescription_row8" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine8" name="medicine_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_8" id="medicineDoze_Rx8_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx8_view" name="unit_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage8" name="usageType_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_8" class="form-control arrowLessInput"
											   id="view_medicineDay8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_8" class="form-control arrowLessInput"
											   id="view_medicineTime8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_8" class="form-control arrowLessInput"
											   id="view_medicineAmount8">

									</div>
								</div>

							</div>
							<!-- row 8 -->

							<!-- row 9 -->
							<div class="row viewRows" id="prescription_row9" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine9" name="medicine_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_9" id="medicineDoze_Rx9_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx9_view" name="unit_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage9" name="usageType_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_9" class="form-control arrowLessInput"
											   id="view_medicineDay9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_9" class="form-control arrowLessInput"
											   id="view_medicineTime9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_9" class="form-control arrowLessInput"
											   id="view_medicineAmount9">

									</div>
								</div>

							</div>
							<!-- row 9 -->

							<!-- row 10 -->
							<div class="row viewRows" id="prescription_row10" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine10" name="medicine_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
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
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_10" id="medicineDoze_Rx10_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx10_view" name="unit_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
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

										<select id="view_medicineUsage10" name="usageType_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">


											<option label="<?= $ci->lang('select') ?>"></option>
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

										<input type="number" name="day_10" class="form-control arrowLessInput"
											   id="view_medicineDay10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_10" class="form-control arrowLessInput"
											   id="view_medicineTime10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_10" class="form-control arrowLessInput"
											   id="view_medicineAmount10">

									</div>
								</div>

							</div>
							<!-- row 10 -->


						</form>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary"
						onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
					<?= $ci->lang('save') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- prescription ViewMedicines End -------------------------------------------------------------------------------------------------->


<script>
	function viewPrescriptionsMedicines(id) {
		$.ajax({
			url: "<?= base_url('admin/single_prescription') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;

				// row1 -------------
				if (medicienDatas.medicine_1 != 0) {
					$("#prescription_row1").show();
					$('#view_medicine1').val(medicienDatas.medicine_1).trigger('change');
					$('#medicineDoze_Rx1_view').val(medicienDatas.doze_1);
					$('#medicineUnite_Rx1_view').val(medicienDatas.unit_1).trigger('change');
					$('#view_medicineUsage1').val(medicienDatas.usageType_1).trigger('change');
					$('#view_medicineDay1').val(medicienDatas.day_1);
					$('#view_medicineTime1').val(medicienDatas.time_1);
					$('#view_medicineAmount1').val(medicienDatas.amount_1);
				} else {
					$("#prescription_row1").hide();
				}
				// row1 -------------


				// row2 -------------
				if (medicienDatas.medicine_2 != 0) {
					$("#prescription_row2").show();
					$('#view_medicine2').val(medicienDatas.medicine_2).trigger('change');
					$('#medicineDoze_Rx2_view').val(medicienDatas.doze_2);
					$('#medicineUnite_Rx2_view').val(medicienDatas.unit_2).trigger('change');
					$('#view_medicineUsage2').val(medicienDatas.usageType_2).trigger('change');
					$('#view_medicineDay2').val(medicienDatas.day_2);
					$('#view_medicineTime2').val(medicienDatas.time_2);
					$('#view_medicineAmount2').val(medicienDatas.amount_2);
				} else {
					$("#prescription_row2").hide();
				}
				// row2 -------------


				// row3 -------------
				if (medicienDatas.medicine_3 != 0) {
					$("#prescription_row3").show();
					$('#view_medicine3').val(medicienDatas.medicine_3).trigger('change');
					$('#medicineDoze_Rx3_view').val(medicienDatas.doze_3);
					$('#medicineUnite_Rx3_view').val(medicienDatas.unit_3).trigger('change');
					$('#view_medicineUsage3').val(medicienDatas.usageType_3).trigger('change');
					$('#view_medicineDay3').val(medicienDatas.day_3);
					$('#view_medicineTime3').val(medicienDatas.time_3);
					$('#view_medicineAmount3').val(medicienDatas.amount_3);
				} else {
					$("#prescription_row3").hide();
				}
				// row3 -------------


				// row4 -------------
				if (medicienDatas.medicine_4 != 0) {
					$("#prescription_row4").show();
					$('#view_medicine4').val(medicienDatas.medicine_4).trigger('change');
					$('#medicineDoze_Rx4_view').val(medicienDatas.doze_4);
					$('#medicineUnite_Rx4_view').val(medicienDatas.unit_4).trigger('change');
					$('#view_medicineUsage4').val(medicienDatas.usageType_4).trigger('change');
					$('#view_medicineDay4').val(medicienDatas.day_4);
					$('#view_medicineTime4').val(medicienDatas.time_4);
					$('#view_medicineAmount4').val(medicienDatas.amount_4);
				} else {
					$("#prescription_row4").hide();
				}
				// row4 -------------


				// row5 -------------
				if (medicienDatas.medicine_5 != 0) {
					$("#prescription_row5").show();
					$('#view_medicine5').val(medicienDatas.medicine_5).trigger('change');
					$('#medicineDoze_Rx5_view').val(medicienDatas.doze_5);
					$('#medicineUnite_Rx5_view').val(medicienDatas.unit_5).trigger('change');
					$('#view_medicineUsage5').val(medicienDatas.usageType_5).trigger('change');
					$('#view_medicineDay5').val(medicienDatas.day_5);
					$('#view_medicineTime5').val(medicienDatas.time_5);
					$('#view_medicineAmount5').val(medicienDatas.amount_5);
				} else {
					$("#prescription_row5").hide();
				}
				// row5 -------------


				// row6 -------------
				if (medicienDatas.medicine_6 != 0) {
					$("#prescription_row6").show();
					$('#view_medicine6').val(medicienDatas.medicine_6).trigger('change');
					$('#medicineDoze_Rx6_view').val(medicienDatas.doze_6);
					$('#medicineUnite_Rx6_view').val(medicienDatas.unit_6).trigger('change');
					$('#view_medicineUsage6').val(medicienDatas.usageType_6).trigger('change');
					$('#view_medicineDay6').val(medicienDatas.day_6);
					$('#view_medicineTime6').val(medicienDatas.time_6);
					$('#view_medicineAmount6').val(medicienDatas.amount_6);
				} else {
					$("#prescription_row6").hide();
				}
				// row6 -------------


				// row7 -------------
				if (medicienDatas.medicine_7 != 0) {
					$("#prescription_row7").show();
					$('#view_medicine7').val(medicienDatas.medicine_7).trigger('change');
					$('#medicineDoze_Rx7_view').val(medicienDatas.doze_7);
					$('#medicineUnite_Rx7_view').val(medicienDatas.unit_7).trigger('change');
					$('#view_medicineUsage7').val(medicienDatas.usageType_7).trigger('change');
					$('#view_medicineDay7').val(medicienDatas.day_7);
					$('#view_medicineTime7').val(medicienDatas.time_7);
					$('#view_medicineAmount7').val(medicienDatas.amount_7);
				} else {
					$("#prescription_row7").hide();
				}
				// row7 -------------


				// row8 -------------
				if (medicienDatas.medicine_8 != 0) {
					$("#prescription_row8").show();
					$('#view_medicine8').val(medicienDatas.medicine_8).trigger('change');
					$('#medicineDoze_Rx8_view').val(medicienDatas.doze_8);
					$('#medicineUnite_Rx8_view').val(medicienDatas.unit_8).trigger('change');
					$('#view_medicineUsage8').val(medicienDatas.usageType_8).trigger('change');
					$('#view_medicineDay8').val(medicienDatas.day_8);
					$('#view_medicineTime8').val(medicienDatas.time_8);
					$('#view_medicineAmount8').val(medicienDatas.amount_8);
				} else {
					$("#prescription_row8").hide();
				}
				// row8 -------------


				// row9 -------------
				if (medicienDatas.medicine_9 != 0) {
					$("#prescription_row9").show();
					$('#view_medicine9').val(medicienDatas.medicine_9).trigger('change');
					$('#medicineDoze_Rx9_view').val(medicienDatas.doze_9);
					$('#medicineUnite_Rx9_view').val(medicienDatas.unit_9).trigger('change');
					$('#view_medicineUsage9').val(medicienDatas.usageType_9).trigger('change');
					$('#view_medicineDay9').val(medicienDatas.day_9);
					$('#view_medicineTime9').val(medicienDatas.time_9);
					$('#view_medicineAmount9').val(medicienDatas.amount_9);
				} else {
					$("#prescription_row9").hide();
				}
				// row9 -------------


				// row10 -------------
				if (medicienDatas.medicine_10 != 0) {
					$("#prescription_row10").show();
					$('#view_medicine10').val(medicienDatas.medicine_10).trigger('change');
					$('#medicineDoze_Rx10_view').val(medicienDatas.doze_10);
					$('#medicineUnite_Rx10_view').val(medicienDatas.unit_10).trigger('change');
					$('#view_medicineUsage10').val(medicienDatas.usageType_10).trigger('change');
					$('#view_medicineDay10').val(medicienDatas.day_10);
					$('#view_medicineTime10').val(medicienDatas.time_10);
					$('#view_medicineAmount10').val(medicienDatas.amount_10);
				} else {
					$("#prescription_row10").hide();
				}
				// row10 -------------
			}
		});

		$(`#viewPrescriptionsMedicines`).modal('toggle');
	}
</script>


<script>
	// TODO the actoins of the patients page
	function actions() {
		let actionValues = $('#selectaction').val();
		console.log(actionValues);
		if (actionValues == 1) {
			$(`#extralargemodal`).modal("toggle");
			$('#selectaction').val('').trigger('change');
		}
		if (actionValues == 2) {
			payment_modal_clicked();
			$(`#paymentModal`).modal("toggle");
			$('#selectaction').val('').trigger('change');
		}
		if (actionValues == 3) {
			request();
			$(`#laboratoryInsertModal`).modal("toggle");
			$('#selectaction').val('').trigger('change');
		}
		if (actionValues == 4) {
			$(`#insertPrescription`).modal("toggle");
			$('#selectaction').val('').trigger('change');
		}
		if (actionValues == 5) {
			$(`#filesModal`).modal("toggle");
			$('#selectaction').val('').trigger('change');
		}
	}
</script>


<!--  TODO the checkboxes sctipt -->

<script>
	// Get the checkboxes, divs, and buttons
	const checkboxes = document.querySelectorAll('.checkbox');
	const div1 = document.getElementById('Restorative-pane');
	const div2 = document.getElementById('endo-pane');
	const div3 = document.getElementById('pros-pane');
	const button1 = document.getElementById('Restorative');
	const button2 = document.getElementById('endo');
	const button3 = document.getElementById('pros-tab');

	// Function to check if a checkbox is unchecked
	function isCheckboxUnchecked(checkbox) {
		return !checkbox.checked;
	}

	// Function to lock or unlock a specific div based on checkbox state
	function lockOrUnlockDiv(div, checkbox) {
		if (isCheckboxUnchecked(checkbox)) {
			div.classList.add('locked');
		} else {
			div.classList.remove('locked');
		}
	}

	// Function to lock or unlock a specific button based on div state
	function lockOrUnlockButton(button, div) {
		if (div.classList.contains('locked')) {
			button.disabled = true;
		} else {
			button.disabled = false;
		}
	}

	// Add event listener to each checkbox
	checkboxes.forEach((checkbox, index) => {
		checkbox.addEventListener('change', function () {
			if (index === 0) {
				lockOrUnlockDiv(div1, checkbox);
				lockOrUnlockButton(button1, div1);
			} else if (index === 1) {
				lockOrUnlockDiv(div2, checkbox);
				lockOrUnlockButton(button2, div2);
			} else if (index === 2) {
				lockOrUnlockDiv(div3, checkbox);
				lockOrUnlockButton(button3, div3);
			}
		});
	});

	// Initially lock or unlock the divs and buttons based on checkbox state
	lockOrUnlockDiv(div1, checkboxes[0]);
	lockOrUnlockDiv(div2, checkboxes[1]);
	lockOrUnlockDiv(div3, checkboxes[2]);
	lockOrUnlockButton(button1, div1);
	lockOrUnlockButton(button2, div2);
	lockOrUnlockButton(button3, div3);
</script>

<!-- TODO: bonding and composite  -->
<script>
	function showBonding(restorativeMaterial_id, compositeDiv_id, bondingDiv_id, amalgamDiv_id) {

		let restorativeMaterial = $(restorativeMaterial_id).val();
		var compositeDiv = document.getElementById(compositeDiv_id);
		var bondingDiv = document.getElementById(bondingDiv_id);
		var amalgamDiv = document.getElementById(amalgamDiv_id);

		if (restorativeMaterial == 1) {
			compositeDiv.style.display = 'block';
			bondingDiv.style.display = 'block';
			amalgamDiv.style.display = 'none';
		} else if (restorativeMaterial == 2) {
			compositeDiv.style.display = 'none';
			bondingDiv.style.display = 'none';
			amalgamDiv.style.display = 'block';
		} else {
			compositeDiv.style.display = 'none';
			bondingDiv.style.display = 'none';
			amalgamDiv.style.display = 'none';
		}
	}
</script>


<!-- TODO new edit -->
<!-- TODO: AJAX -->

<!-- edit function new ------------------------ start-->
<script>
	function updateTeeth(id) {
		$.ajax({
			url: "<?= base_url('admin/single_tooth') ?>",
			type: 'POST',
			data: {
				slug: id,
			},
			success: function (response) {
				let result = JSON.parse(response);
				let contents = result.content;
				console.log(contents);
				if (typeof contents.name === 'string' && !isNaN(contents.name)) {

					let alldiagnose = contents.diagnose;
					var diagnoses_select = document.getElementById("select_diagnose_update").innerHTML;
					alldiagnose.map((item) => {
						diagnoses_select = diagnoses_select.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					})
					document.getElementById("select_diagnose_update").innerHTML = diagnoses_select;


					$('#modalImage2_update_restro').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${contents.imgAddress}`);


					// added by navid
					$('#selectName_update').val(contents.name).trigger('change');
					$('#locationSelector_update').val(contents.location).trigger('change');


					if (contents.is_endo === "true") {
						$('#canalselector_update').val(contents.endo.root_number).trigger('change');


						$('#canalLocation1_update').val(contents.endo.r_name1).trigger('change');
						$('#c_length1_update').val(contents.endo.r_width1).trigger('change');

						$('#canalLocation2_update').val(contents.endo.r_name2).trigger('change');
						$('#c_length2_update').val(contents.endo.r_width2).trigger('change');

						$('#canalLocation3_update').val(contents.endo.r_name3).trigger('change');
						$('#c_length3_update').val(contents.endo.r_width3).trigger('change');

						$('#canalLocation4_update').val(contents.endo.r_name4).trigger('change');
						$('#c_length4_update').val(contents.endo.r_width4).trigger('change');

						$('#canalLocation5_update').val(contents.endo.r_name5).trigger('change');
						$('#c_length5_update').val(contents.endo.r_width5).trigger('change');

						let endoServices = contents.endo.services;
						var updateInnerHTML = document.getElementById("services_update").innerHTML;
						endoServices.map((item) => {
							updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_update").innerHTML = updateInnerHTML;


						$('#price_tooth_update').val(contents.endo.price).trigger('change');
						$('#details_update').val(contents.endo.details).trigger('change');
						$('#instypeObturation_update').val(contents.endo.typeObturation).trigger('change');
						$('#insTypeSealer_update').val(contents.endo.TypeSealer).trigger('change');
						$('#insTypeIrrigation_update').val(contents.endo.TypeIrrigation).trigger('change');

						$('#modalImage_update').attr('src', `<?= $ci->dentist->assets_url() ?>assets/images/tooth${contents.imgAddress}`);

					}

					if (contents.is_restorative === "true") {
						$('#insertCariesDepth_update').val(contents.restorative.CariesDepth).trigger('change');
						$('#insertMaterial_update').val(contents.restorative.Material).trigger('change');


						$('#insertRestorativeMaterial_update').val(contents.restorative.RestorativeMaterial).trigger('change');
						$('#insertCompositeBrand_update').val(contents.restorative.CompositeBrand).trigger('change');
						$('#insertBondingBrand_update').val(contents.restorative.bondingBrand).trigger('change');
						$('#insertAmalgamBrand_update').val(contents.restorative.AmalgamBrand).trigger('change');

						let restorativeServices = contents.restorative.services;
						var updateInnerHTML = document.getElementById("services_restorative_update").innerHTML;
						restorativeServices.map((item) => {
							updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_restorative_update").innerHTML = updateInnerHTML;

						$('#price_tooth_restorative_update').val(contents.restorative.price).trigger('change');
						$('#restorative_details_update').val(contents.restorative.details).trigger('change');


					}

					$(`#teethmodal_update`).modal('toggle');
				} else if (typeof contents.name === 'string' && /^[a-tA-T]+$/.test(contents.name)) {

				}
			}
		});


	}
</script>
<!-- edit function new ------------------------ end-->


<script>
	function check_pro_type() {
		let pro_type = $('#type_pro').val();

		if (pro_type == 'abutment') {
			$('.abutment').show();
			$('.pontic').hide();
		} else if (pro_type == 'pontic') {
			$('.abutment').hide();
			$('.pontic').show();
		}
	}
</script>
