<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0"><?= $ci->lang('role and permission') ?></h3>
			</div>
			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">
							<li>
								<button class="btn btn-primary"
										onclick="window.location.href='<?= base_url() ?>admin/user_role'"><?= $ci->lang('add new') ?>
									<i
										class="fe fe-plus"></i></button>
							</li>
						</ul>
						<!-- Modal Button -->
						<!--TODO cropper modal start--------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="cropperModal" class="cropper-modal" tabindex="-1">
							<div class="cropper-modal-content">
								<span class="cropper-close">&times;</span>
								<div id="cropperContainer">
									<div>
										<img id="cropperImage" src="" alt="Crop Image">
									</div>
									<button id="cropButton" class="button-19"><?= $ci->lang('Crop Image') ?></button>
								</div>
							</div>
						</div>
						<!--TODO cropper modal end--------------------------------------------------------------------------------------------------------------------------------------------- -->

						<!-- Modal -->
						<div class="modal fade effect-scale" id="extralargemodal" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title"><?= $ci->lang('insert user') ?></h5>
										<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="insert_user">
											<div class="row">
												<div class="col-sm-12 col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('image') ?> <span
																class="text-red">*</span></label>
														<input type="file" id="inputImage" class="form-control">
														<input type="hidden" id="inputImageHiddenInput"
															   name="uploadedImage" class="form-control">
													</div>
												</div>
											</div>
											<div class="row">

												<div class="col-sm-12 col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('name') ?> <span
																class="text-red">*</span></label>
														<input type="text" name="name" class="form-control"
															   placeholder="<?= $ci->lang('name') ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('lname') ?> <span
																class="text-red">*</span></label>
														<input type="text" name="lname" class="form-control"
															   placeholder="<?= $ci->lang('lname') ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('username') ?> <span
																class="text-red">*</span></label>
														<input type="text" name="username" class="form-control"
															   placeholder="<?= $ci->lang('username') ?>"
															   autocomplete="username">
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label"><?= ucwords('role') ?> <span
																class="text-red">*</span></label>
														<select class="form-control form-select" name="user_role"
																data-bs-placeholder="Select" tabindex="-1"
																aria-hidden="true">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($user_roles as $role) : ?>
																<option
																	value="<?= $role->id ?>"><?= ucwords($role->role_name) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('account type') ?> <span
																class="text-red">*</span></label>
														<select class="form-control form-select" name="role"
																data-bs-placeholder="Select" tabindex="-1"
																aria-hidden="true">
															<option label="<?= $ci->lang('select') ?>"></option>
															<?php foreach ($ci->mylibrary->list_user_type() as $key => $value) : ?>
																<option
																	value="<?= $key ?>"><?= $ci->lang($value) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('status') ?> <span
																class="text-red">*</span></label>
														<select name="status" class="form-control form-select">
															<option value="A"
																	selected><?= $ci->lang('accepted') ?></option>
															<option value="P"><?= $ci->lang('pending') ?></option>
														</select>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('password') ?> <span
																class="text-red">*</span></label>
														<input type="password" name="password" class="form-control"
															   placeholder="<?= $ci->lang('password') ?>"
															   autocomplete="off">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label"><?= $ci->lang('confirm password') ?>
															<span class="text-red">*</span></label>
														<input type="password" name="confirm" class="form-control"
															   placeholder="<?= $ci->lang('confirm password') ?>"
															   autocomplete="off">
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
												onclick="xhrSubmitMultiTable('insert_user', '<?= base_url('admin/insert_user') ?>', 'file-datatable', 'extralargemodal')"><?= $ci->lang('save') ?>
											<i class="fa fa-plus"></i></button>
									</div>
								</div>
							</div>
						</div>
						<!-- Modal End -->


					</div>
				</div>
				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('lname') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('username') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('status') ?></th>
							<th class="border-bottom-0"><?= ucwords('role') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('account type') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($users as $user) : ?>
							<tr id="<?= $user['id'] ?>">
								<td><?= $i ?></td>
								<td><?= $user['fname'] ?></td>
								<td><?= $user['lname'] ?></td>
								<td><?= $user['username'] ?></td>
								<td><?= $ci->mylibrary->generateUserBadge($user['status']) ?></td>
								<td><?= $user['role_name'] ?></td>
								<td><?= $ci->lang($ci->mylibrary->check_user_type($user['role'])) ?></td>
								<td>
									<div class="g-2">
										<?php if (ucfirst($user['status']) == 'A') : ?>

											<a href="javascript:xhrChangeStatusUsers('<?= base_url('admin/change_status_user/') ?>', '<?= $user['id'] ?>', 'P')"
											   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
													class="fa fa-minus-circle fs-14"></span></a>
										<?php else : ?>
											<a href="javascript:xhrChangeStatusUsers('<?= base_url('admin/change_status_user/') ?>', '<?= $user['id'] ?>', 'A')"
											   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
													class="fa fa-unlock fs-14"></span></a>
										<?php endif; ?>
										<a href="javascript:delete_via_alert('<?= $user['id'] ?>', '<?= base_url() ?>admin/delete_user', 'file-datatable', null, true)"
										   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span
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
		</div>
	</div>
</div>


<!-- TODO cropper scripts -->
<script>
	// Get the modal
	var modal = document.getElementById("cropperModal");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("cropper-close")[0];

	var cropper;

	// When the user clicks on the button, open the modal
	document.getElementById('inputImage').addEventListener('change', function (e) {
		var image = document.getElementById('cropperImage');

		// Reset previous cropper instance and image source
		if (cropper) {
			cropper.destroy();
			URL.revokeObjectURL(image.src); // Revoke the previous object URL to release memory
		}

		image.src = URL.createObjectURL(e.target.files[0]);
		$('#extralargemodal').modal('hide');
		modal.style.display = "block";
		// modal.style.position = ""

		cropper = new Cropper(image, {
			aspectRatio: 1, // You can adjust the aspect ratio as per your requirements
			viewMode: 2,
			guides: true,
			minCropBoxWidth: 50,
			minCropBoxHeight: 50,
			autoCropArea: 1,
			ready: function () {
				// Do something when cropper is ready
			}
		});
	});

	// When the user clicks on <span> (x), close the modal
	span.onclick = function () {
		modal.style.display = "none";
		cropper.destroy();
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function (event) {
		if (event.target == modal) {
			modal.style.display = "none";
			cropper.destroy();
		}
	}

	// Function to save the cropped image as base64 PNG
	document.getElementById('cropButton').addEventListener('click', function () {
		var croppedCanvas = cropper.getCroppedCanvas();
		document.getElementById("inputImageHiddenInput").value = croppedCanvas.toDataURL('image/png'); // Convert to base64 PNG

		// Do something with base64Image, like displaying or uploading
		// base64Image = hiddenInput;
		//   console.log(`here is the base64Image: ${hiddenInput}`);

		// Close the modal after saving
		modal.style.display = "none";
		cropper.destroy();
		$('#extralargemodal').modal('show');

	});
</script>
