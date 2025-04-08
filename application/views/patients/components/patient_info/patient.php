<?php

$ci = get_instance();

?>
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
						<?php if ($ci->auth->has_permission('View Phone Numbers')): ?>
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
		<?php if ($ci->auth->has_permission('Update Personal Information')): ?>
			<div class="p-4 border-bottom border-block-end-dashed">

				<button type="button" class="btn btn-primary-gradient btn-wave custom-btn" data-bs-toggle="modal"
						data-bs-target="#edit_patient" onclick="edit_profile()"
						style=" display: flex;align-items: center; width: 100%; justify-content: space-between;"><?= $ci->lang('edit') ?>
					<i class="fa fa-edit"></i>
				</button>

				<!-- patients edit profile modal codes goes here - start -->
				<?php $ci->render('patients/components/patient_info/edit_patients_profile_modal.php') ?>
				<!-- patients edit profile modal codes goes here - end -->

			</div>
		<?php endif; ?>
	</div>
</div>
