<?php
$ci = get_instance();
?>
<div class="col-xxl-8">
	<div class="row">
		<div class="col-xl-12">
			<div class=" custom-card">
				<div class="card-body p-0">
					<!-- TODO: the tabs bar start -->
					<div class="border-block-end-dashed  bg-white rounded-2 p-2">
						<div>
							<ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="myTab"
								role="tablist">
								<li class="nav-item rounded" role="presentation">
									<button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
											data-bs-target="#activity-tab-pane" type="button" role="tab"
											aria-controls="activity-tab-pane" aria-selected="true"><i
											class="fa fa-tooth me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('teeth') ?>
									</button>
								</li>

								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="other-treatment-tab" data-bs-toggle="tab"
											data-bs-target="#other_treatment_tab" type="button" role="tab"
											aria-controls="other_treatment_tab" aria-selected="false"><i
											class="fa fa-user-friends me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('other treatments') ?>
									</button>
								</li>

								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="gallery-tab" data-bs-toggle="tab"
											data-bs-target="#gallery-tab-pane" type="button" role="tab"
											aria-controls="gallery-tab-pane" aria-selected="false"><i
											class="fa fa-calendar me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('turns') ?>
									</button>
								</li>
								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="followers-tab" data-bs-toggle="tab"
											data-bs-target="#treatment-plan-tab-pane" type="button" role="tab"
											aria-controls="treatment-plan-tab-pane" aria-selected="false"><i
											class="fa fa-clipboard me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('treatment plan') ?>
									</button>
								</li>
								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="posts-tab" data-bs-toggle="tab"
											data-bs-target="#posts-tab-pane" type="button" role="tab"
											aria-controls="posts-tab-pane" aria-selected="false"><i
											class="fa fa-vial me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('laboratory') ?>
									</button>
								</li>
								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="followers-tab" data-bs-toggle="tab"
											data-bs-target="#followers-tab-pane" type="button" role="tab"
											aria-controls="followers-tab-pane" aria-selected="false"><i
											class="fa fa-prescription me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('prescription') ?>
									</button>
								</li>
								<li class="nav-item rounded" role="presentation">
									<button class="nav-link" id="followers-tab" data-bs-toggle="tab"
											data-bs-target="#archive-tab-pane" type="button" role="tab"
											aria-controls="archive-tab-pane" aria-selected="false"><i
											class="fa fa-archive me-1 align-middle d-inline-block fs-16"></i><?= $ci->lang('archive') ?>
									</button>
								</li>

							</ul>
						</div>
					</div>
					<!-- the tabs bar end -->


					<div class="py-4">
						<div class="tab-content" id="myTabContent">
							<!-- Tabs contents start -->

							<!-- teeth tab start -->
							<div class="tab-pane show active fade p-0 border-0 bg-white p-4 rounded-3"
								 id="activity-tab-pane" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">

								<?php $ci->render('patients/components/main/Teeth/index.php') ?>

							</div>
							<!-- teeth tab start -->

							<!-- Other Treatments tab start -->
							<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="other_treatment_tab"
								 role="tabpanel" aria-labelledby="other_treatment_tab" tabindex="0">

								<!-- Other Treatments teb contents start -->
								<?php $ci->render('patients/components/main/other_treatment/main.php'); ?>
								<!-- Other Treatments tab contents end -->

							</div>
							<!-- Other Treatments tab End -->

							<!-- turns tab start -->
							<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="gallery-tab-pane"
								 role="tabpanel" aria-labelledby="gallery-tab" tabindex="0">

								<!-- turns tab contents start -->
								<?php $ci->render('patients/components/main/Turns/turns.php') ?>
								<!-- turns tab contents end -->

							</div>
							<!-- turns tab end -->

							<!-- Lab tab start -->
							<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="posts-tab-pane"
								 role="tabpanel" aria-labelledby="posts-tab" tabindex="0">

								<!-- lab tab contents start -->
								<?php $ci->render('patients/components/main/Lab/lab.php') ?>
								<!-- lab tab contents end -->

							</div>
							<!-- Lab tab end -->

							<!-- Prescription tab start -->
							<div class="tab-pane fade p-0 border-0" id="followers-tab-pane" role="tabpanel"
								 aria-labelledby="followers-tab" tabindex="0">

								<!-- prescription contents start -->
								<?php $ci->render('patients/components/main/prescription/prescription.php') ?>
								<!-- prescription contents end -->

							</div>
							<!-- Prescription tab end -->

							<!-- Archive tab start -->
							<div class="tab-pane fade p-0 border-0" id="archive-tab-pane" role="tabpanel"
								 aria-labelledby="archive-tab" tabindex="0">
								<!-- Archive tab contents start -->
								<?php $ci->render('patients/components/main/Archive/archive.php') ?>
								<!-- Archive tab contents end -->
							</div>
							<!-- Archive tab end -->


							<!-- Archive tab start -->
							<div class="tab-pane fade p-0 border-0" id="treatment-plan-tab-pane" role="tabpanel"
								 aria-labelledby="treatment-plan-tab" tabindex="0">
								<!-- Archive tab contents start -->
								<?php $ci->render('patients/components/main/TreatmentPlan/treatment_plan.php') ?>
								<!-- Archive tab contents end -->
							</div>
							<!-- Archive tab end -->
							<!-- Tabs contents end -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
