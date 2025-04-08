<?php $ci = get_instance(); ?>
<!-- ROW-1 -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
		<div class="row">
			<!--			patients modal			-->
			<?php if ($ci->auth->has_permission('Create Patient')): ?>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xl-3" onclick="show_patient_modal()">
					<div class="card overflow-hidden bg-success-gradient clickable">
						<div class="card-body">
							<div class="d-flex">
								<div class="mt-2">
									<h2 class="mb-0 number-font"><?= $ci->lang('patients') ?></h2>
								</div>
								<div class="ms-auto">
									<div class="chart-wrapper mt-1 img_icon_style">
										<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/toothache.png"
											 alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!--			rx modal-->
			<div class="col-lg-4 col-md-4 col-sm-12 col-xl-3" onclick="$(`#rxModal_home`).modal('toggle');">
				<div class="card overflow-hidden bg_rx clickable">
					<div class="card-body">
						<div class="d-flex">
							<div class="mt-2">
								<h2 class="mb-0 number-font"><?= $ci->lang('prescription') ?></h2>
							</div>
							<div class="ms-auto">
								<div class="chart-wrapper mt-1 img_icon_style">
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/rx.png" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--			expenses -->
			<?php if ($ci->auth->has_permission('Create Expenses')): ?>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xl-2" onclick="$(`#receiptModal`).modal('toggle');">
					<div class="card overflow-hidden bg-warning-gradient clickable">
						<div class="card-body">
							<div class="d-flex">
								<div class="mt-2">
									<h2 class="mb-0 number-font"><?= $ci->lang('expenses') ?></h2>
								</div>
								<div class="ms-auto">
									<div class="chart-wrapper mt-1 img_icon_style">
										<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/bill.png"
											 alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!--			search modal-->
			<div class="col-lg-4 col-md-4 col-sm-12 col-xl-2" onclick="$(`#searchModal`).modal('toggle');">
				<div class="card overflow-hidden bg-danger-gradient clickable">
					<div class="card-body">
						<div class="d-flex">
							<div class="mt-2">
								<h2 class="mb-0 number-font"><?= $ci->lang('search') ?></h2>
							</div>
							<div class="ms-auto">
								<div class="chart-wrapper mt-1 img_icon_style">
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/search.png" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--			turns Modal-->
			<?php if ($ci->auth->has_permission('Create New Turn')): ?>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xl-2" onclick="$(`#turnsModal`).modal('toggle');">
					<div class="card overflow-hidden bg-info-gradient clickable">
						<div class="card-body">
							<div class="d-flex">
								<div class="mt-2">
									<h2 class="mb-0 number-font"><?= $ci->lang('turns') ?></h2>
								</div>
								<div class="ms-auto">
									<div class="chart-wrapper mt-1 img_icon_style">
										<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/queue.png"
											 alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<!-- ROW-1 END -->


<!-- ROW-4 -->

<!-- ROW-4 END -->


<!-- ROW-3 -->
<div class="row" id="temp_list_div" <?= (count($temp_patients) == 0) ? 'style="display: none"' : '' ?>>
	<div class="col-xl-12 col-md-12">
		<div class="card">
			<div class="card-header turens_header">
				<h4 class="card-title fw-semibold"><?= $ci->lang('list temp patients') ?></h4>

			</div>
			<div class="card-body pb-0">
				<!-- the table start _______________________________________________________________________________ -->
				<div class="table-responsive scrollTable" id="temp_patient_list">
					<table class="table text-nowrap" id="tempTable">
						<thead class="tableHead">
						<tr>
							<th scope="col">#</th>
							<th scope="col">
								<?= $ci->lang('fullname') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('phone1') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('phone2') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('reference doctor') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('medical history') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('other diseases') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('desc') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('actions') ?>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($temp_patients as $temp_patient) : ?>
							<tr id="temp<?= $temp_patient['id'] ?>" class="tableRow">
								<td>
									<?= $i ?>
								</td>
								<td class="">
									<?= $ci->mylibrary->get_patient_name($temp_patient['name'], $temp_patient['lname'], '', $temp_patient['gender']) ?>
								</td>
								<td class="">
									<?= $temp_patient['phone1'] ?>
								</td>
								<td class="">
									<?= $temp_patient['phone2'] ?>
								</td>
								<td class="">
									<?= $temp_patient['doctor_name'] ?>
								</td>
								<td class="">
									<?= $temp_patient['pains'] ?>
								</td>
								<td class="">
									<?= $temp_patient['other_pains'] ?>
								</td>
								<td class="">
									<?= $temp_patient['remarks'] ?>
								</td>
								<td>
									<div class="g-2">
										<a href="javascript:temp_to_permenant('<?= $temp_patient['id'] ?>', '<?= base_url() ?>admin/temp_to_permenant', 'tempTable')"
										   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-check"></span></a>
										<a href="javascript:archive_temp_patient('<?= $temp_patient['id'] ?>', '<?= base_url() ?>admin/archive_temp_patient', 'tempTable')"
										   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
												class="fa-solid fa-archive fs-14"></span></a>
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
<!-- ROW-3 END -->


<!-- ROW-3 -->
<div class="row">
	<div class="col-xl-9 col-md-12">
		<div class="card">
			<div class="card-header turens_header">
				<h4 class="card-title fw-semibold"><?= $ci->lang('turns list') ?></h4>


				<div class="col-sm-12 col-md-5">
					<div class="form-group formGroup">
						<label class="form-label" style="width: 300px;">
							<?= $ci->lang('reference doctor') ?>
						</label>
						<select name="" class="form-control select2-show-search form-select"
								onchange="update_turns_by_doctor(this.value)"
								data-placeholder="<?= $ci->lang('select') ?>">
							<option value="0"><?= $ci->lang('all') ?></option>
							<?php foreach ($doctors as $doctor) : ?>
								<option value="<?= $doctor['id'] ?>">
									<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>


			</div>
			<div class="card-body pb-0">
				<!-- the table start _______________________________________________________________________________ -->
				<div class="table-responsive scrollTable" id="turns_list">
					<table class="table text-nowrap" id="turnsTable">
						<thead class="tableHead">
						<tr>
							<th scope="col">#</th>
							<th scope="col"><?= $ci->lang('patient name') ?></th>
							<th scope="col"><?= $ci->lang('reference doctor') ?></th>
							<th scope="col"><?= $ci->lang('date') ?></th>
							<th scope="col"><?= $ci->lang('hour') ?></th>
							<th scope="col"><?= $ci->lang('actions') ?></th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($turns as $turn) : ?>
							<tr id="<?= $turn['id'] ?>" class="tableRow">
								<td scope="row"><?= $i ?></td>
								<td><?= $ci->mylibrary->get_patient_name($turn['name'], $turn['lname'], '', $turn['gender']) ?></td>
								<td><?= $turn['doctor_name'] ?></td>
								<td><bdo dir="ltr"><?= $turn['date'] ?></bdo></td>
								<td><?= $turn['from_time'] . ' - ' . $turn['to_time'] ?></td>
								<td>
									<div class="g-2">
										<?php if ($ci->auth->has_permission('Read Patient Profile')): ?>
											<a href="<?= base_url() ?>admin/single_patient/<?= $turn['patient_id'] ?>"
											   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
													class="fa fa-user-circle-o fs-14"></span></a>
										<?php endif; ?>
										<a href="javascript:print_turn('<?= $turn['id'] ?>', '<?= base_url() ?>admin/delete_turn')"
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
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
		<div class="card overflow-hidden">
			<div class="card-body pb-0 bg-recentorder">
				<h3 class="card-title text-white"><?= $ci->lang('today balance') ?></h3>
				<div class="chartjs-wrapper-demo">
					<canvas id="recentorders" class="chart-dropshadow"></canvas>
				</div>
			</div>
			<div id="flotback-chart" class="flot-background"></div>
			<div class="card-body">
				<div class="d-flex mb-4 mt-3">
					<div class="avatar avatar-md bg-secondary-transparent text-secondary bradius me-3">
						<i class="fa fa-check"></i>
					</div>
					<div class="">
						<h6 class="mb-1 fw-semibold"><?= $ci->lang('income') ?></h6>
						<p class="fw-normal fs-12"><span class="text-success">3.5%</span>
							increased </p>
					</div>
					<div class=" ms-auto my-auto">
						<p class="fw-bold fs-20"> <?= number_format($sum_income) ?> </p>
					</div>
				</div>
				<div class="d-flex mb-4 mt-3">
					<div class="avatar  avatar-md bg-pink-transparent text-pink bradius me-3">
						<i class="fa fa-times"></i>
					</div>
					<div class="">
						<h6 class="mb-1 fw-semibold"><?= $ci->lang('paid') ?></h6>
						<p class="fw-normal fs-12"><span class="text-success">1.2%</span>
							increased </p>
					</div>
					<div class=" ms-auto my-auto">
						<p class="fw-bold fs-20 mb-0"> <?= number_format($sum_paid) ?> </p>
					</div>
				</div>
				<div class="d-flex mb-4 mt-3">
					<div class="avatar  avatar-md bg-pink-transparent text-pink bradius me-3">
						<i class="fa fa-times"></i>
					</div>
					<div class="">
						<h6 class="mb-1 fw-semibold"><?= $ci->lang('expenses') ?></h6>
						<p class="fw-normal fs-12"><span class="text-success">1.2%</span>
							increased </p>
					</div>
					<div class=" ms-auto my-auto">
						<p class="fw-bold fs-20 mb-0"> <?= number_format($sum_expenses) ?> </p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ROW-3 END -->


<!-- ROW-2 -->
<?php if (isset($needed)) : ?>
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12 col-xl-9">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Sales Analytics</h3>
				</div>
				<div class="card-body">
					<div class="d-flex mx-auto text-center justify-content-center mb-4">
						<div class="d-flex text-center justify-content-center me-3"><span
								class="dot-label bg-primary my-auto"></span>Total Sales
						</div>
						<div class="d-flex text-center justify-content-center"><span
								class="dot-label bg-secondary my-auto"></span>Total Orders
						</div>
					</div>
					<div class="chartjs-wrapper-demo">
						<canvas id="transactions" class="chart-dropshadow"></canvas>
					</div>
				</div>
			</div>
		</div>
		<!-- COL END -->
		<div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
			<div class="card overflow-hidden">
				<div class="card-body pb-0 bg-recentorder">
					<h3 class="card-title text-white">Recent Orders</h3>
					<div class="chartjs-wrapper-demo">
						<canvas id="recentorders" class="chart-dropshadow"></canvas>
					</div>
				</div>
				<div id="flotback-chart" class="flot-background"></div>
				<div class="card-body">
					<div class="d-flex mb-4 mt-3">
						<div class="avatar avatar-md bg-secondary-transparent text-secondary bradius me-3">
							<i class="fa fa-circle-check"></i>
						</div>
						<div class="">
							<h6 class="mb-1 fw-semibold">Delivered Orders</h6>
							<p class="fw-normal fs-12"><span class="text-success">3.5%</span>
								increased </p>
						</div>
						<div class=" ms-auto my-auto">
							<p class="fw-bold fs-20"> 1,768 </p>
						</div>
					</div>
					<div class="d-flex">
						<div class="avatar  avatar-md bg-pink-transparent text-pink bradius me-3">
							<i class="fa fa-close"></i>
						</div>
						<div class="">
							<h6 class="mb-1 fw-semibold">Cancelled Orders</h6>
							<p class="fw-normal fs-12"><span class="text-success">1.2%</span>
								increased </p>
						</div>
						<div class=" ms-auto my-auto">
							<p class="fw-bold fs-20 mb-0"> 3,675 </p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- COL END -->
	</div>
	<!-- ROW-2 END -->
<?php endif; ?>

<script>
	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}

	function print_payment(turnId) {
		window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
	}

	function print_expense(expenseId) {
		window.open(`<?= base_url() ?>admin/print_expense/${expenseId}`, '_blank');
		$(`#receiptModal`).modal('toggle');

	}
</script>

<script>
	function turnPayment(id) {

		$(`#turnsPaymentInfo`).val(id);


		$(`#paymentModal_turns`).modal('toggle');
	}
</script>

<!-- Modal pay turn -->
<div class="modal fade effect-scale" id="paymentModal_turns" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('insert payment') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="insertPayment">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('amount') ?> <span class="text-red">*</span>
									</label>
									<input type="hidden" class="form-control" id="turnsPaymentInfo" name="slug">
									<input type="number" name="cr" class="form-control"
										   placeholder="<?= $ci->lang('amount') ?>" id="" autocomplete="off">
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-warning"
						onclick="update_and_delete_simple('insertPayment', '<?= base_url() ?>admin/pay_turn', 'paymentModal_turns', 'turnsTable', print_payment, 'print');">
					<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->


<script>
	function update_turns_by_doctor(doctorId = '0') {
		$.ajax({
			url: "<?= base_url('admin/list_turns_json_dashboard') ?>",
			type: 'POST',
			data: {
				doctor_id: doctorId
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					if (result['content'].length < 1) {
						var querytable = ``;
					} else {

						var querytable = `
            <table class="table text-nowrap" id="turnsTable">
            <thead class="tableHead">
              <tr>
                <th scope="col">#</th>
                <th scope="col"><?= $ci->lang('patient name') ?></th>
                <th scope="col"><?= $ci->lang('reference doctor') ?></th>
                <th scope="col"><?= $ci->lang('date') ?></th>
                <th scope="col"><?= $ci->lang('hour') ?></th>
                <th scope="col"><?= $ci->lang('actions') ?></th>
              </tr>
            </thead>
            <tbody>
        `;

						let counter = 1;
						result.content.map((item) => {
							let profile = '';
							if (item.profile_access) {
								profile = ` <a href="<?= base_url() ?>admin/single_patient/${item.patient_id}" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-user-circle-o fs-14"></span></a> `;
							}
							querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.patient_name}</td>
              <td>${item.doctor_name}</td>
              <td>${item.date}</td>
              <td>${item.hour}</td>
              <td>
                    <div class="g-2">
                    ${profile}
                      <a href="javascript:print_turn('${item.id}', '<?= base_url() ?>admin/delete_turn')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-print"></span></a>
                    </div>
                  </td>
            </tr>
          `;
							counter++;
						});
						querytable += `
            </tbody>
          </table>
        `;

					}

					document.getElementById("turns_list").innerHTML = querytable;

				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		});
	}
</script>


<!-- Modal -->
<?php if ($ci->auth->has_permission('Create Patient')): ?>

	<div class="modal fade effect-scale" id="extralargemodal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<?= $ci->lang('insert patient') ?>
					</h5>
					<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="insertPatient">
						<div class="row">
							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('name') ?> <span class="text-red">*</span>
									</label>
									<input type="text" name="name" class="form-control"
										   placeholder="<?= $ci->lang('name') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('lname') ?> <span class="text-red">*</span>
									</label>
									<input type="text" name="lname" class="form-control"
										   placeholder="<?= $ci->lang('lname') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('age') ?> <span class="text-red">*</span>
									</label>
									<input type="number" name="age" class="form-control"
										   placeholder="<?= $ci->lang('age') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('phone') ?> <span class="text-red">*</span>
									</label>
									<input type="text" name="phone1" class="form-control"
										   placeholder="<?= $ci->lang('phone') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('phone2') ?>
									</label>
									<input type="text" name="phone2" class="form-control"
										   placeholder="<?= $ci->lang('phone2') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('gender') ?> <span class="text-red">*</span>
									</label>
									<select name="gender" class="form-control form-select">
										<option label="<?= $ci->lang('select') ?>"></option>
										<option value="m">
											<?= $ci->lang('male') ?>
										</option>
										<option value="f">
											<?= $ci->lang('female') ?>
										</option>
									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('medical history') ?>
									</label>
									<select name="pains_select" class="form-control select2-show-search form-select"
											id="model" data-placeholder="<?= $ci->lang('select') ?>" multiple
											onchange="multiple_value()">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($ci->dentist->diseases() as $pain) : ?>
											<option value="<?= $pain ?>">
												<?= $pain ?>
											</option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" name="pains" id="model_value">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('reference doctor') ?>
									</label>
									<select name="doctor_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($doctors as $doctor) : ?>
											<option value="<?= $doctor['id'] ?>">
												<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('other diseases') ?>
									</label>
									<textarea type="text" name="other_pains" class="form-control" rows="4"
											  placeholder="<?= $ci->lang('other diseases') ?>"></textarea>
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('address') ?>
									</label>
									<textarea type="text" name="address" class="form-control" rows="4"
											  placeholder="<?= $ci->lang('address') ?>"></textarea>
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('desc') ?>
									</label>
									<textarea type="text" name="remarks" class="form-control" rows="4"
											  placeholder="<?= $ci->lang('desc') ?>"></textarea>
								</div>
							</div>


						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">
						<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
					</button>
					<button class="btn btn-primary"
							onclick="xhr_insert_patient('insertPatient', '<?= base_url() ?>admin/insert_patient','<?= base_url() ?>admin/single_patient/')">
						<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
					</button>
					<button class="btn btn-primary"
							onclick="xhr_insert_patient('insertPatient', '<?= base_url() ?>admin/insert_temp_patient'); setTimeout(list_temp_patients, 500);">
						<?= $ci->lang('save') ?> <i class="fa fa-archive"></i>
					</button>

				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- Modal End -->


<?php if ($ci->auth->has_permission('Create Patient')): ?>
	<script>
		function show_patient_modal() {
			$(`#extralargemodal`).modal('toggle');
		}
	</script>
<?php endif; ?>


<!-- turns Modal -->
<?php if ($ci->auth->has_permission('Create New Turn')): ?>
	<div class="modal fade effect-scale" id="turnsModal" role="dialog">
		<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<?= $ci->lang('insert turn') ?>
					</h5>
					<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="insertTurn">
						<div class="row">

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('patient name') ?> <span class="text-red">*</span>
									</label>
									<select name="patient_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>" id="patientName"
											onchange="check_turns()">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($patients as $patient) : ?>
											<option value="<?= $patient['id'] ?>">
												<?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>


							<div class="col-sm-12 col-md-4">
								<div class="form-group jdp" id="main-div">
									<label class="form-label">
										<?= $ci->lang('date') ?> <span class="text-red">*</span>
									</label>
									<input data-jdp type="text" id="test-date-id-date" name="date"
										   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
										   class="form-control" placeholder="<?= $ci->lang('date') ?>"
										   onchange="check_turns()">
									<div></div>
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('reference doctor') ?> <span class="text-red">*</span>
									</label>
									<select name="doctor_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>" id="doctorName"
											onchange="check_turns()">
										<option label="<?= $ci->lang('select') ?>"></option>
										<option value="all">
											<?= $ci->lang("select") ?>
										</option>
										<?php foreach ($doctors as $doctor) : ?>
											<option value="<?= $doctor['id'] ?>">
												<?= $ci->mylibrary->user_name($doctor['fname'], $doctor['lname']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('hour') ?> <span class="text-red">*</span>
									</label>
									<input type="time" name="from_time" list="from_times" class="form-control"
										   id="from_time">
									<datalist id="from_times">
										<option value="08:00">
										<option value="08:30">
										<option value="09:00">
										<option value="09:30">
										<option value="10:00">
										<option value="10:30">
										<option value="11:00">
										<option value="11:30">
										<option value="12:00">
										<option value="12:30">
										<option value="13:00">
										<option value="13:30">
										<option value="14:00">
										<option value="14:30">
										<option value="15:00">
										<option value="15:30">
										<option value="16:00">
									</datalist>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('hour') ?> <span class="text-red">*</span>
									</label>
									<input name="to_time" class="form-control" type="time" list="from_times"
										   id="to_time">
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive" id="queryTable">

							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">
						<?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
					</button>
					<button class="btn btn-warning"
							onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable','turnsModal', print_turn, 'print'); update_turns_by_doctor();"><?= $ci->lang('save and print') ?>
						<i class="fa fa-print"></i></button>
					<button class="btn btn-primary"
							onclick="submitWithoutDatatable('insertTurn', '<?= base_url() ?>admin/insert_turn', 'turnsTable', 'turnsModal', update_turns_by_doctor);update_turns_by_doctor();"><?= $ci->lang('save') ?>
						<i class="fa fa-plus"></i></button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- turns Modal End -->


<!-- Modal -->
<div class="modal fade effect-scale hide" id="searchModal" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?= $ci->lang('search') ?>
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="insertAccount">
					<div class="row">

						<div id="reader" style="width:500px; height: 500px; display: none"></div>


						<div class="col-sm-12 col-md-1">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('scan') ?>
								</label>
								<button class="btn btn-primary" type="button" onclick="startScanning(list_patients)"><i
										class="fa fa-qrcode"></i></button>
							</div>
						</div>

						<div class="col-sm-12 col-md-5">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('serial id') ?>
								</label>
								<input type="text" class="form-control" id="serial_id" onchange="list_patients()">
							</div>
						</div>

						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="form-label" for="fullname">
									<?= $ci->lang('fullname') ?>
								</label>
								<input type="text" class="form-control" id="fullname" onkeyup="list_patients()"
									   autocomplete="off" name="fullname">
							</div>
						</div>

					</div>
				</form>

				<div class="row" style="margin-top: 2%">
					<div class="col-md-12">
						<div class="table-responsive" id="listpatients">
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
					<i class="fa fa-close"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->

<script>
	function multiple_value() {
		const select = $('#model').val();
		let text = select.join()
		$('#model_value').val(text);
	}
</script>

<script>
	function check_available_times() {
		const patientName = document.getElementById("patientName").value;
		const dateValue = document.getElementById("test-date-id-date").value;
		const doctorName = document.getElementById("doctorName").value;

		$.ajax({
			url: "<?= base_url('admin/check_available_times') ?>",
			type: 'POST',
			data: {
				patient_id: patientName,
				date: dateValue,
				doctor: (doctorName !== 'all' ? doctorName : null)
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] === 'success') {
					let querytable = `
                    <table class="table text-nowrap table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $ci->lang('available time slots') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                `;

					if (result["content"]["times"].length < 1) {
						querytable += `
                        <tr>
                            <td><?= $ci->lang('no available slots') ?></td>
                        </tr>
                    `;
					} else {
						result["content"]["times"].map((timeSlot) => {
							querytable += `
                            <tr>
                                <td>${timeSlot}</td>
                            </tr>
                        `;
						});
					}

					querytable += `
                        </tbody>
                    </table>
                `;

					document.getElementById("queryTable").innerHTML = querytable;
				} else if (result['type'] === 'error') {
					$.growl.error1({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		});
	}

</script>


<!-- Expenses Modal -->
<?php if ($ci->auth->has_permission('Create Expenses')): ?>
	<div class="modal fade effect-scale hide" id="receiptModal" role="dialog">
		<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<?= $ci->lang('insert receipt') ?>
					</h5>
					<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="insertAccount">
						<div class="row">


							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('account') ?> <span class="text-red">*</span>
									</label>
									<select name="customers_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($accounts as $account) : ?>
											<option value="<?= $account['id'] ?>">
												<?= $ci->mylibrary->finance_account_name($account['name'], $account['lname'], $account['type']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('type') ?>
									</label>
									<select name="type" id="" class="form-control form-select">
										<option label="<?= $ci->lang('select') ?>"></option>
										<option value="cr">
											<?= $ci->lang('cr') ?>
										</option>
										<option value="dr">
											<?= $ci->lang('dr') ?>
										</option>
									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('amount') ?>
									</label>
									<input type="number" name="amount" class="form-control"
										   placeholder="<?= $ci->lang('amount') ?>">
								</div>
							</div>

							<div class="col-sm-12 col-md-3">
								<div class="form-group jdp" id="main-div">
									<label class="form-label">
										<?= $ci->lang('date') ?> <span class="text-red">*</span>
									</label>
									<!-- <input id="test-date-id-date" data-jdp type="text" name="phone" class="form-control" placeholder="<?= $ci->lang('amount') ?>" autocomplete="off"> -->
									<input data-jdp type="text" id="test-date-id-date" name="shamsi"
										   value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>"
										   class="form-control" placeholder="<?= $ci->lang('date') ?>">
									<div></div>
								</div>
							</div>


							<div class="col-sm-12 col-md-12 ">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('description') ?> <span class="text-red"></span>
									</label>
									<!-- <input id="test-date-id" type="text" name="phone" class="form-control" placeholder="<?= $ci->lang('amount') ?>"> -->
									<textarea class="form-control" name="remarks"
											  placeholder="<?= $ci->lang('description') ?>"></textarea>
								</div>
							</div>

						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">
						<?= $ci->lang('cancel') ?>
						<i class="fa fa-close"></i>
					</button>
					<!-- TODO: Create Function for update the today balance of today after finishing the print functionality  -->
					<button class="btn btn-warning" data-bs-dismiss="modal"
							onclick="submitWithoutDatatable('insertAccount', '<?= base_url() ?>admin/insert_receipt', '','receiptModal', print_expense, 'print');">
						<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i></button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- Expenses Modal End -->


<!--rx modal -->
<div class="modal fade effect-scale" tabindex="-1" id="rxModal_home" role="dialog">

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


				<form id="prescriptions_setMedicines_home">

					<div class="row">

						<div class="row">

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('patient name') ?> <span class="text-red">*</span>
									</label>
									<select name="patient_id" class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>" id="patientName_rx">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($patients as $patient) : ?>
											<option value="<?= $patient['id'] ?>">
												<?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-sm-12 col-md-4">
								<div class="form-group">
									<label class="form-label">
										<?= $ci->lang('type') ?>
									</label>
									<select class="form-control select2-show-search form-select"
											data-placeholder="<?= $ci->lang('select') ?>" id=""
											onchange="viewPrescriptionsMedicines(this.value)">
										<option label="<?= $ci->lang('select') ?>"></option>
										<?php foreach ($prescriptions as $prescription) : ?>
											<option value="<?= $prescription['id'] ?>">
												<?= ucwords($prescription['name']) ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

						</div>
						<div class="customHr2"></div>
						<div class="col-md-12">

							<!-- row 1 -->
							<div class="row" id="setMedicien_row1_home">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine1_home" name="medicine_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx1_home', 'medicineUnite_Rx1_home', 'set_medicineUsage1_home', 'set_medicineDay1_home', 'set_medicineTime1_home', 'set_medicineAmount1_home')">
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

										<input type="number" name="doze_1" id="medicineDoze_Rx1_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx1_home" name="unit_1"
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

										<select id="set_medicineUsage1_home" name="usageType_1"
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
											   id="set_medicineDay1_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_1" class="form-control arrowLessInput"
											   id="set_medicineTime1_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_1" class="form-control arrowLessInput"
											   id="set_medicineAmount1_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusBtn1_home" class="icon-btn add-btn" type="button"
													onclick=" plusBtn('setMedicien_row2_home', 'plusBtn1_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 1 -->

							<!-- row 2 -->
							<div class="row" id="setMedicien_row2_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine2_home" name="medicine_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx2_home', 'medicineUnite_Rx2_home', 'set_medicineUsage2_home', 'set_medicineDay2_home', 'set_medicineTime2_home', 'set_medicineAmount2_home')">
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

										<input type="number" name="doze_2" id="medicineDoze_Rx2_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx2_home" name="unit_2"
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

										<select id="set_medicineUsage2_home" name="usageType_2"
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
											   id="set_medicineDay2_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_2" class="form-control arrowLessInput"
											   id="set_medicineTime2_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_2" class="form-control arrowLessInput"
											   id="set_medicineAmount2_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2" id="PRBtns2_home">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn2_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row3_home', 'plusbtn2_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row2_home', 'plusBtn1_home'), clearInput('set_medicine2_home', 'medicineDoze_Rx2_home', 'medicineUnite_Rx2_home', 'set_medicineUsage2_home', 'set_medicineDay2_home', 'set_medicineTime2_home', 'set_medicineAmount2_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 2 -->

							<!-- row 3 -->
							<div class="row" id="setMedicien_row3_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine3_home" name="medicine_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx3_home', 'medicineUnite_Rx3_home', 'set_medicineUsage3_home', 'set_medicineDay3_home', 'set_medicineTime3_home', 'set_medicineAmount3_home')">
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

										<input type="number" name="doze_3" id="medicineDoze_Rx3_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx3_home" name="unit_3"
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

										<select id="set_medicineUsage3_home" name="usageType_3"
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
											   id="set_medicineDay3_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_3" class="form-control arrowLessInput"
											   id="set_medicineTime3_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_3" class="form-control arrowLessInput"
											   id="set_medicineAmount3_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2" id="PRBtns3_home">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn3_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row4_home', 'plusbtn3_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row3_home', 'plusbtn2_home'),clearInput('set_medicine3_home', 'medicineDoze_Rx3_home', 'medicineUnite_Rx3_home', 'set_medicineUsage3_home', 'set_medicineDay3_home', 'set_medicineTime3_home', 'set_medicineAmount3_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 3 -->

							<!-- row 4 -->
							<div class="row" id="setMedicien_row4_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine4_home" name="medicine_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx4_home', 'medicineUnite_Rx4_home', 'set_medicineUsage4_home', 'set_medicineDay4_home', 'set_medicineTime4_home', 'set_medicineAmount4_home')">
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

										<input type="number" name="doze_4" id="medicineDoze_Rx4_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx4_home" name="unit_4"
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

										<select id="set_medicineUsage4_home" name="usageType_4"
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
											   id="set_medicineDay4_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_4" class="form-control arrowLessInput"
											   id="set_medicineTime4_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_4" class="form-control arrowLessInput"
											   id="set_medicineAmount4_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4_home">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn4_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row5_home', 'plusbtn4_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row4_home', 'plusbtn3_home'), clearInput('set_medicine4_home', 'medicineDoze_Rx4_home', 'medicineUnite_Rx4_home', 'set_medicineUsage4_home', 'set_medicineDay4_home', 'set_medicineTime4_home', 'set_medicineAmount4_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 4 -->


							<!-- row 5 -->
							<div class="row" id="setMedicien_row5_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine5_home" name="medicine_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx5_home', 'medicineUnite_Rx5_home', 'set_medicineUsage5_home', 'set_medicineDay5_home', 'set_medicineTime5_home', 'set_medicineAmount5_home')">
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

										<input type="number" name="doze_5" id="medicineDoze_Rx5_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx5_home" name="unit_5"
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

										<select id="set_medicineUsage5_home" name="usageType_5"
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
											   id="set_medicineDay5_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_5" class="form-control arrowLessInput"
											   id="set_medicineTime5_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_5" class="form-control arrowLessInput"
											   id="set_medicineAmount5_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns5_home" type="button">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn5_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row6_home','plusbtn5_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row5_home', 'plusbtn4_home'), clearInput('set_medicine5_home', 'medicineDoze_Rx5_home', 'medicineUnite_Rx5_home', 'set_medicineUsage5_home', 'set_medicineDay5_home', 'set_medicineTime5_home', 'set_medicineAmount5_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 5 -->

							<!-- row 6 -->
							<div class="row" id="setMedicien_row6_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine6_home" name="medicine_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx6_home', 'medicineUnite_Rx6_home', 'set_medicineUsage6_home', 'set_medicineDay6_home', 'set_medicineTime6_home', 'set_medicineAmount6_home')">
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

										<input type="number" name="doze_6" id="medicineDoze_Rx6_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx6_home" name="unit_6"
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

										<select id="set_medicineUsage6_home" name="usageType_6"
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
											   id="set_medicineDay6_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_6" class="form-control arrowLessInput"
											   id="set_medicineTime6_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_6" class="form-control arrowLessInput"
											   id="set_medicineAmount6_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4_home">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn6_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row7_home', 'plusbtn6_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row6_home', 'plusbtn5_home'), clearInput('set_medicine6_home', 'medicineDoze_Rx6_home', 'medicineUnite_Rx6_home', 'set_medicineUsage6_home', 'set_medicineDay6_home', 'set_medicineTime6_home', 'set_medicineAmount6_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 6 -->

							<!-- row 7 -->
							<div class="row" id="setMedicien_row7_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine7_home" name="medicine_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx7_home', 'medicineUnite_Rx7_home', 'set_medicineUsage7_home', 'set_medicineDay7_home', 'set_medicineTime7_home', 'set_medicineAmount7_home')">
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

										<input type="number" name="doze_7" id="medicineDoze_Rx7_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx7_home" name="unit_7"
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

										<select id="set_medicineUsage7_home" name="usageType_7"
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
											   id="set_medicineDay7_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_7" class="form-control arrowLessInput"
											   id="set_medicineTime7_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_7" class="form-control arrowLessInput"
											   id="set_medicineAmount7_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn7_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row8_home', 'plusbtn7_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row7_home', 'plusbtn6_home'), clearInput('set_medicine7_home', 'medicineDoze_Rx7_home', 'medicineUnite_Rx7_home', 'set_medicineUsage7_home', 'set_medicineDay7_home', 'set_medicineTime7_home', 'set_medicineAmount7_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 7 -->

							<!-- row 8 -->
							<div class="row" id="setMedicien_row8_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine8_home" name="medicine_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx8_home', 'medicineUnite_Rx8_home', 'set_medicineUsage8_home', 'set_medicineDay8_home', 'set_medicineTime8_home', 'set_medicineAmount8_home')">
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

										<input type="number" name="doze_8" id="medicineDoze_Rx8_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx8_home" name="unit_8"
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

										<select id="set_medicineUsage8_home" name="usageType_8"
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
											   id="set_medicineDay8_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_8" class="form-control arrowLessInput"
											   id="set_medicineTime8_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_8" class="form-control arrowLessInput"
											   id="set_medicineAmount8_home">

									</div>
								</div>
								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4_home">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn8_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row9_home', 'plusbtn8_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row8_home', 'plusbtn7_home'), clearInput('set_medicine8_home', 'medicineDoze_Rx8_home', 'medicineUnite_Rx8_home', 'set_medicineUsage8_home', 'set_medicineDay8_home', 'set_medicineTime8_home', 'set_medicineAmount8_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 8 -->

							<!-- row 9 -->
							<div class="row" id="setMedicien_row9_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine9_home" name="medicine_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx9_home', 'medicineUnite_Rx9_home', 'set_medicineUsage9_home', 'set_medicineDay9_home', 'set_medicineTime9_home', 'set_medicineAmount9_home')">
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

										<input type="number" name="doze_9" id="medicineDoze_Rx9_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx9_home" name="unit_9"
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

										<select id="set_medicineUsage9_home" name="usageType_9"
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
											   id="set_medicineDay9_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_9" class="form-control arrowLessInput"
											   id="set_medicineTime9_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_9" class="form-control arrowLessInput"
											   id="set_medicineAmount9_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4_home">
										<div class="" style="text-align: center;margin-top:5%">
											<button id="plusbtn9_home" class="icon-btn add-btn" type="button"
													onclick="plusBtn('setMedicien_row10_home', 'plusbtn9_home')">
												<div class="add-icon"></div>
												<div class="btn-txt"><?= $ci->lang('add') ?></div>
											</button>
										</div>
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row9_home', 'plusbtn8_home'), clearInput('set_medicine9_home', 'medicineDoze_Rx9_home', 'medicineUnite_Rx9_home', 'set_medicineUsage9_home', 'set_medicineDay9_home', 'set_medicineTime9_home', 'set_medicineAmount9_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
											</button>
										</div>
									</div>


								</div>

							</div>
							<!-- row 9 -->

							<!-- row 10 -->
							<div class="row" id="setMedicien_row10_home" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="set_medicine10_home" name="medicine_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx10_home', 'medicineUnite_Rx10_home', 'set_medicineUsage10_home', 'set_medicineDay10_home', 'set_medicineTime10_home', 'set_medicineAmount10_home')">
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

										<input type="number" name="doze_10" id="medicineDoze_Rx10_home"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx10_home" name="unit_10"
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

										<select id="set_medicineUsage10_home" name="usageType_10"
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
											   id="set_medicineDay10_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_10" class="form-control arrowLessInput"
											   id="set_medicineTime10_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_10" class="form-control arrowLessInput"
											   id="set_medicineAmount10_home">

									</div>
								</div>

								<div class="col-sm-12 col-md-2">

									<div class="plusRemovBtns" id="PRBtns4_home">
										<!-- <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn10" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn10')">
                        <div class="add-icon"></div>
                        <div class="btn-txt"><?= $ci->lang('add') ?></div>
                      </button>
                    </div> -->
										<div class="" style="text-align: center; margin-top: 8px;">
											<button class="icon-btn add-btn" type="button"
													onclick="removeBtn('setMedicien_row10_home', 'plusbtn9_home'), clearInput('set_medicine10_home', 'medicineDoze_Rx10_home', 'medicineUnite_Rx10_home', 'set_medicineUsage10_home', 'set_medicineDay10_home', 'set_medicineTime10_home', 'set_medicineAmount10_home')">
												<div class="btn-txt"><?= $ci->lang('remove') ?></div>
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

				<button class="btn btn-warning" data-bs-dismiss="modal"
						onclick="submitWithoutDatatable('prescriptions_setMedicines_home', '<?= base_url() ?>admin/insert_prescription', '','rxModal_home', print_prescription, 'print');">
					<?= $ci->lang('save and print') ?> <i class="fa fa-print"></i></button>

			</div>
		</div>
	</div>
</div>
<!--rx modal end-->


<script>
	document.addEventListener("DOMContentLoaded", function () {
		// jalaliDatepicker.startWatch();
		jalaliDatepicker.startWatch();
	});
</script>


<script>
	function list_patients(open_profile = false) {
		const serial_id = document.getElementById("serial_id");
		const serial = serial_id.value;

		const fullname = document.getElementById("fullname");
		const full_name = fullname.value;

		$.ajax({
			url: "<?= base_url('admin/list_patients') ?>",
			type: 'POST',
			data: {
				serial_id: serial,
				fullname: full_name
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					if (result['content']['patients'].length < 1) {
						var querytable = ``;
					} else {

						var querytable = `
          <table class="table text-nowrap table-striped">
            <thead>
              <tr>
                <th scope="col">
                  <?= $ci->lang('serial id') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('fullname') ?>
                </th>
                 <th scope="col">
                  <?= $ci->lang('phone1') ?>
                </th>
                 <th scope="col">
                  <?= $ci->lang('medical history') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('reference doctor') ?>
                </th>
              </tr>
            </thead>
            <tbody>
        `;

						result["content"]["patients"].map((item) => {
							let profile = '';
							if (item.profile_access) {
								profile = `<td><a href="<?= base_url('admin/single_patient/') ?>${item.id}" target="_blank">${item.fullname}</a></td> `;
							} else {
								profile = `<td>${item.fullname}</td> `;
							}
							querytable += `
            <tr>
              <td dir="ltr" style="text-align:right;">${item.serial_id}</td>
			  ${profile}
              <td>${item.phone1}</td>
              <td>${item.pains}</td>
              <td>${item.doctor_name}</td>
            </tr>
          `;
						});

						querytable += `
            </tbody>
          </table>
        `;

					}

					document.getElementById("listpatients").innerHTML = querytable;
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
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
		});
	}
</script>


<script>
	function list_temp_patients() {
		$.ajax({
			url: "<?= base_url('admin/list_temp_patients') ?>",
			type: 'GET',
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					if (result.content.length == 0 || typeof result.content == 'undefined') {
						$('#temp_list_div').hide();
						var querytable = ``;
					} else {

						var querytable = `
            <table class="table text-nowrap" id="tempTable">
            <thead class="tableHead">
              <tr>
               	<th scope="col">#</th>
							<th scope="col">
								<?= $ci->lang('fullname') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('phone1') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('phone2') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('reference doctor') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('medical history') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('other diseases') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('desc') ?>
							</th>
							<th scope="col">
								<?= $ci->lang('actions') ?>
							</th>
              </tr>
            </thead>
            <tbody>
        `;

						let counter = 1;
						result.content.map((item) => {
							querytable += `
            <tr class="tableRow" id="temp${item.id}">
              <td>${counter}</td>
              <td>${item.patient_name}</td>
              <td>${item.phone1}</td>
              <td>${item.phone2}</td>
              <td>${item.doctor_name}</td>
              <td>${item.pains}</td>
              <td>${item.other_pains}</td>
              <td>${item.remarks}</td>
              <td>
                    <div class="g-2">
                      <a href="javascript:temp_to_permenant('${item.id}', '<?= base_url() ?>admin/temp_to_permenant', 'tempTable')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-check fs-14"></span></a>
                      <a href="javascript:archive_temp_patient('${item.id}', '<?= base_url() ?>admin/archive_temp_patient', 'tempTable')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-archive fs-14"></span></a>
                    </div>
                  </td>
            </tr>
          `;
							counter++;
						});
						querytable += `
            </tbody>
          </table>
        `;

					}

					document.getElementById("temp_patient_list").innerHTML = querytable;
					$('#temp_list_div').show();
				} else if (result['type'] == 'error') {
					$.growl.error1({
						title: field['alert']['title'],
						message: field['alert']['text']
					});
				}
			}
		});
	}
</script>

<script>
	function archive_temp_patient(id, url, tableId) {
		// Call the alert function immediately
		accept_via_alert(id, url, tableId);

		// Schedule the list update function to execute after 1000ms
		setTimeout(() => {
			list_temp_patients();
		}, 2000);
	}
</script>

<script>
	function temp_to_permenant(id, url, tableId) {

		swal({
			title: '<?= $ci->lang('delete alert title') ?>',
			text: "<?= $ci->lang('delete alert text') ?>",
			icon: "info",
			buttons: ['<?= $ci->lang('cancel') ?>', '<?= $ci->lang('yes') ?>'],
			dangerMode: '<?= $ci->lang('yes') ?>',
		})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						url: url,
						type: 'POST',
						data: {
							record: id
						},
						success: function (response) {
							var result = JSON.parse(response);

							if (result['type'] == 'success') {
								toastr["success"](result['alert']['text'], result['alert']['title'])
								window.location.href = '<?= base_url('admin/single_patient/') ?>' + result['id'];
							} else if (result['type'] == 'error') {
								toastr["error"](result['alert']['text'], result['alert']['title'])
							}
						}
					});
				}
			});
		// Schedule the list update function to execute after 1000ms

	}
</script>

<script>
	function check_turns(selectElement = document.getElementById('doctorName'), date = $('#test-date-id-date').val(), doctor = $('#doctorName').val(), tableId = '#queryTable') {
		const time = document.getElementById("from_time");
		const timeOption = time ? time.value : '';  // Get selected time range

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


				// Check for success type
				if (result['type'] === 'success') {
					document.querySelector('#from_time').removeAttribute('readonly');

					document.querySelector('#to_time').removeAttribute('readonly');

					if (typeof result['content']['time_slots'] != 'undefined') {
						var timeSlots = result['content']['time_slots'];  // assuming we get 'time_slots' as part of the response
					}
					if (timeSlots.length > 0 && typeof timeSlots != 'undefined') {
						if (timeSlots[0].range == "On Leave") {
							document.querySelector('#from_time').setAttribute('readonly', true);
							document.querySelector('#from_time').value = '';

							document.querySelector('#to_time').setAttribute('readonly', true);
							document.querySelector('#to_time').value = '';
						}
						var tableTemplate = `<table class="table text-nowrap table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col"><?= $ci->lang('Available Time Range') ?></th>
                                                    <th scope="col"><?= $ci->lang('Status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

						// Loop through each time slot and add it to the table
						timeSlots.forEach((slot) => {
							tableTemplate += `<tr>
                                              <td>${slot.range}</td>
                                              <td>${slot.status}</td>
                                          </tr>`;
						});

						tableTemplate += `</tbody>
                                    </table>`;
						$(tableId).html(tableTemplate);
					} else {
						var tableTemplate = `<p>No available time slots for this date and doctor.</p>`;
						$(tableId).html(tableTemplate);
					}
				} else if (result['type'] === 'error') {
					document.querySelector('#from_time').setAttribute('readonly', true);
					document.querySelector('#from_time').value = '';

					document.querySelector('#to_time').setAttribute('readonly', true);
					document.querySelector('#to_time').value = '';

					var tableTemplate = ``;

					$(tableId).html(tableTemplate);
					toastr["error"](result['alert']['text'], result['alert']['title'])
				}
			},
			error: function () {
				toastr["error"]('An error occurred while fetching the available time slots.', 'Error')
			}
		});
	}

</script>

<!--rx js-->
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

	function plusBtn(rowId, plusbtnId) {
		$(`#${rowId}`).show();
		$(`#${plusbtnId}`).hide();
	}

	function removeBtn(rowId, plusbtnId) {
		$(`#${rowId}`).hide();
		$(`#${plusbtnId}`).show();
	}

	function clearInput(medicineId, dozeId, unitId, usageId, dayId, timeId, amountId) {
		$('#' + medicineId).val('').trigger('change');
		$('#' + dozeId).val('');
		$('#' + unitId).val('').trigger('change');
		$('#' + usageId).val('').trigger('change');
		$('#' + dayId).val('');
		$('#' + timeId).val('');
		$('#' + amountId).val('');
	}

	function print_prescription(prescriptionId) {
		window.open(`<?= base_url() ?>admin/print_prescription/${prescriptionId}`, '_blank');
	}


	function viewPrescriptionsMedicines(id) {
		$.ajax({
			url: "<?= base_url('admin/single_prescription_sample') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;

				// this part counts how many has to be shown-start
				var medicineCount = 0;

				for (var key in medicienDatas) {
					if (key.startsWith("medicine_") && medicienDatas[key] != 0) {
						console.log(key)
						medicineCount++;
					}
				}

				console.log(medicineCount);

				showRows(medicineCount);
				// this part counts how many has to be shown-end

				// row1 -------------
				$('#set_medicine1_home').val(medicienDatas.medicine_1).trigger('change');
				$('#medicineDoze_Rx1_home').val(medicienDatas.doze_1);
				$('#medicineUnite_Rx1_home').val(medicienDatas.unit_1).trigger('change');
				$('#set_medicineUsage1_home').val(medicienDatas.usageType_1).trigger('change');
				$('#set_medicineDay1_home').val(medicienDatas.day_1);
				$('#set_medicineTime1_home').val(medicienDatas.time_1);
				$('#set_medicineAmount1_home').val(medicienDatas.amount_1);
				// row1 -------------

				// row2 -------------
				$('#set_medicine2_home').val(medicienDatas.medicine_2).trigger('change');
				$('#medicineDoze_Rx2_home').val(medicienDatas.doze_2);
				$('#medicineUnite_Rx2_home').val(medicienDatas.unit_2).trigger('change');
				$('#set_medicineUsage2_home').val(medicienDatas.usageType_2).trigger('change');
				$('#set_medicineDay2_home').val(medicienDatas.day_2);
				$('#set_medicineTime2_home').val(medicienDatas.time_2);
				$('#set_medicineAmount2_home').val(medicienDatas.amount_2);
				// row2 -------------

				// row3 -------------
				$('#set_medicine3_home').val(medicienDatas.medicine_3).trigger('change');
				$('#medicineDoze_Rx3_home').val(medicienDatas.doze_3);
				$('#medicineUnite_Rx3_home').val(medicienDatas.unit_3).trigger('change');
				$('#set_medicineUsage3_home').val(medicienDatas.usageType_3).trigger('change');
				$('#set_medicineDay3_home').val(medicienDatas.day_3);
				$('#set_medicineTime3_home').val(medicienDatas.time_3);
				$('#set_medicineAmount3_home').val(medicienDatas.amount_3);
				// row3 -------------

				// row4 -------------
				$('#set_medicine4_home').val(medicienDatas.medicine_4).trigger('change');
				$('#medicineDoze_Rx4_home').val(medicienDatas.doze_4);
				$('#medicineUnite_Rx4_home').val(medicienDatas.unit_4).trigger('change');
				$('#set_medicineUsage4_home').val(medicienDatas.usageType_4).trigger('change');
				$('#set_medicineDay4_home').val(medicienDatas.day_4);
				$('#set_medicineTime4_home').val(medicienDatas.time_4);
				$('#set_medicineAmount4_home').val(medicienDatas.amount_4);
				// row4 -------------

				// row5 -------------
				$('#set_medicine5_home').val(medicienDatas.medicine_5).trigger('change');
				$('#medicineDoze_Rx5_home').val(medicienDatas.doze_5);
				$('#medicineUnite_Rx5_home').val(medicienDatas.unit_5).trigger('change');
				$('#set_medicineUsage5_home').val(medicienDatas.usageType_5).trigger('change');
				$('#set_medicineDay5_home').val(medicienDatas.day_5);
				$('#set_medicineTime5_home').val(medicienDatas.time_5);
				$('#set_medicineAmount5_home').val(medicienDatas.amount_5);
				// row5 -------------

				// row6 -------------
				$('#set_medicine6_home').val(medicienDatas.medicine_6).trigger('change');
				$('#medicineDoze_Rx6_home').val(medicienDatas.doze_6);
				$('#medicineUnite_Rx6_home').val(medicienDatas.unit_6).trigger('change');
				$('#set_medicineUsage6_home').val(medicienDatas.usageType_6).trigger('change');
				$('#set_medicineDay6_home').val(medicienDatas.day_6);
				$('#set_medicineTime6_home').val(medicienDatas.time_6);
				$('#set_medicineAmount6_home').val(medicienDatas.amount_6);
				// row6 -------------

				// row7 -------------
				$('#set_medicine7_home').val(medicienDatas.medicine_7).trigger('change');
				$('#medicineDoze_Rx7_home').val(medicienDatas.doze_7);
				$('#medicineUnite_Rx7_home').val(medicienDatas.unit_7).trigger('change');
				$('#set_medicineUsage7_home').val(medicienDatas.usageType_7).trigger('change');
				$('#set_medicineDay7_home').val(medicienDatas.day_7);
				$('#set_medicineTime7_home').val(medicienDatas.time_7);
				$('#set_medicineAmount7_home').val(medicienDatas.amount_7);
				// row7 -------------

				// row8 -------------
				$('#set_medicine8_home').val(medicienDatas.medicine_8).trigger('change');
				$('#medicineDoze_Rx8_home').val(medicienDatas.doze_8);
				$('#medicineUnite_Rx8_home').val(medicienDatas.unit_8).trigger('change');
				$('#set_medicineUsage8_home').val(medicienDatas.usageType_8).trigger('change');
				$('#set_medicineDay8_home').val(medicienDatas.day_8);
				$('#set_medicineTime8_home').val(medicienDatas.time_8);
				$('#set_medicineAmount8_home').val(medicienDatas.amount_8);
				// row8 -------------

				// row9 -------------
				$('#set_medicine9_home').val(medicienDatas.medicine_9).trigger('change');
				$('#medicineDoze_Rx9_home').val(medicienDatas.doze_9);
				$('#medicineUnite_Rx9_home').val(medicienDatas.unit_9).trigger('change');
				$('#set_medicineUsage9_home').val(medicienDatas.usageType_9).trigger('change');
				$('#set_medicineDay9_home').val(medicienDatas.day_9);
				$('#set_medicineTime9_home').val(medicienDatas.time_9);
				$('#set_medicineAmount9_home').val(medicienDatas.amount_9);
				// row9 -------------

				// row10 -------------
				$('#set_medicine10_home').val(medicienDatas.medicine_10).trigger('change');
				$('#medicineDoze_Rx10_home').val(medicienDatas.doze_10);
				$('#medicineUnite_Rx10_home').val(medicienDatas.unit_10).trigger('change');
				$('#set_medicineUsage10_home').val(medicienDatas.usageType_10).trigger('change');
				$('#set_medicineDay10_home').val(medicienDatas.day_10);
				$('#set_medicineTime10_home').val(medicienDatas.time_10);
				$('#set_medicineAmount10_home').val(medicienDatas.amount_10);
				// row10 -------------


			}
		});

		$(`#viewPrescriptionsMedicines`).modal('toggle');
	}


	function showRows(rownumber) {
		if (rownumber == 1) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").hide();
			$("#setMedicien_row3_home").hide();
			$("#setMedicien_row4_home").hide();
			$("#setMedicien_row5_home").hide();
			$("#setMedicien_row6_home").hide();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 2) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").hide();
			$("#setMedicien_row4_home").hide();
			$("#setMedicien_row5_home").hide();
			$("#setMedicien_row6_home").hide();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 3) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").hide();
			$("#setMedicien_row5_home").hide();
			$("#setMedicien_row6_home").hide();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 4) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").hide();
			$("#setMedicien_row6_home").hide();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 5) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").hide();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 6) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").show();
			$("#setMedicien_row7_home").hide();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 7) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").show();
			$("#setMedicien_row7_home").show();
			$("#setMedicien_row8_home").hide();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 8) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").show();
			$("#setMedicien_row7_home").show();
			$("#setMedicien_row8_home").show();
			$("#setMedicien_row9_home").hide();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 9) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").show();
			$("#setMedicien_row7_home").show();
			$("#setMedicien_row8_home").show();
			$("#setMedicien_row9_home").show();
			$("#setMedicien_row10_home").hide();
		} else if (rownumber == 10) {
			$("#setMedicien_row1_home").show();
			$("#setMedicien_row2_home").show();
			$("#setMedicien_row3_home").show();
			$("#setMedicien_row4_home").show();
			$("#setMedicien_row5_home").show();
			$("#setMedicien_row6_home").show();
			$("#setMedicien_row7_home").show();
			$("#setMedicien_row8_home").show();
			$("#setMedicien_row9_home").show();
			$("#setMedicien_row10_home").show();
		}
	}
</script>
