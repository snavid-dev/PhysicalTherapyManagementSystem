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

<!--			turns Modal-->
			<?php if ($ci->auth->has_permission('Create Turn')): ?>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xl-3" onclick="$(`#turnsModal`).modal('toggle');">
				<div class="card overflow-hidden bg-info-gradient clickable">
					<div class="card-body">
						<div class="d-flex">
							<div class="mt-2">
								<h2 class="mb-0 number-font"><?= $ci->lang('turns') ?></h2>
							</div>
							<div class="ms-auto">
								<div class="chart-wrapper mt-1 img_icon_style">
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/queue.png" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

<!--			expenses -->
			<?php if ($ci->auth->has_permission('Create Expenses')): ?>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xl-3" onclick="$(`#receiptModal`).modal('toggle');">
				<div class="card overflow-hidden bg-warning-gradient clickable">
					<div class="card-body">
						<div class="d-flex">
							<div class="mt-2">
								<h2 class="mb-0 number-font"><?= $ci->lang('expenses') ?></h2>
							</div>
							<div class="ms-auto">
								<div class="chart-wrapper mt-1 img_icon_style">
									<img src="<?= $ci->dentist->assets_url() ?>assets/images/D_icons/bill.png" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xl-3" onclick="$(`#searchModal`).modal('toggle');">
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
		</div>
	</div>
</div>
<!-- ROW-1 END -->


<!-- ROW-4 -->

<!-- ROW-4 END -->


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
								<td><?= $ci->dentist->find_time($turn['hour']) ?></td>
								<td>
									<div class="g-2">
										<a href="<?= base_url() ?>admin/single_patient/<?= $turn['patient_id'] ?>"
										   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-user-circle-o fs-14"></span></a>
										<a href="javascript:accept_via_alert('<?= $turn['id'] ?>', '<?= base_url() ?>admin/accept_turn', 'turnsTable')"
										   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
												class="fa-regular fa-circle-check fs-16"></span></a>
										<a href="javascript:print_turn('<?= $turn['id'] ?>', '<?= base_url() ?>admin/delete_turn')"
										   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
												class="fa-solid fa-print fs-14"></span></a>
										<a href="javascript:turnPayment('<?= $turn['id'] ?>')"
										   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
												class="fa fa-money fs-14"></span></a>
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
						<i class="las la-check"></i>
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
						<i class="las la-times"></i>
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
						<i class="las la-times"></i>
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
							<i class="fe fe-check"></i>
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
							<i class="fe fe-x"></i>
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
				<button class="btn btn-primary"
						onclick="update_and_delete_simple('insertPayment', '<?= base_url() ?>admin/pay_turn','paymentModal_turns', 'turnsTable')">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
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
							querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.patient_name}</td>
              <td>${item.doctor_name}</td>
              <td>${item.date}</td>
              <td>${item.hour}</td>
              <td>
                    <div class="g-2">
                      <a href="<?= base_url() ?>admin/single_patient/${item.patient_id}" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-user-circle-o fs-14"></span></a>
                      <a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/accept_turn', 'turnsTable')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-check-circle fs-14"></span></a>
                      <a href="javascript:print_turn('${item.id}', '<?= base_url() ?>admin/delete_turn')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fe fe-printer fs-14"></span></a>
                      <a href="javascript:turnPayment('${item.id}')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-money fs-14"></span></a>
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
<?php if ($ci->auth->has_permission('Create Turn')): ?>
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

						<div class="col-sm-12 col-md-3">
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


						<div class="col-sm-12 col-md-3">
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

						<div class="col-sm-12 col-md-3">
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
						<div class="col-sm-12 col-md-3">
							<div class="form-group">
								<label class="form-label">
									<?= $ci->lang('hour') ?> <span class="text-red">*</span>
								</label>
								<select name="hour" class="form-control select2-show-search form-select"
										data-placeholder="<?= $ci->lang('select') ?>" id="patientTime"
										onchange="check_turns()">
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

<script>
	function multiple_value() {
		const select = $('#model').val();
		let text = select.join()
		$('#model_value').val(text);
	}
</script>

<script>
	function check_turns() {
		const patientName = document.getElementById("patientName");
		const patientNameOption = patientName.value;

		const date = document.getElementById("test-date-id-date");
		const dateValue = date.value;

		const doctorName = document.getElementById("doctorName");
		const doctorNameOption = doctorName.value


		const time = document.getElementById("patientTime");
		const timeOption = time.value;


		$.ajax({
			url: "<?= base_url('admin/check_turns') ?>",
			type: 'POST',
			data: {
				patient_id: patientNameOption,
				date: dateValue,
				doctor: ((doctorNameOption != 'all') ? doctorNameOption : null),
				patient_time: timeOption
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					if (result['content']['turns'].length < 1) {
						var querytable = ``;
					} else {
						var querytable = `
          <table class="table text-nowrap table-striped">
            <thead>
              <tr>
                <th scope="col">
                  <?= $ci->lang('patient name') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('reference doctor') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('hour') ?>
                </th>
              </tr>
            </thead>
            <tbody>
        `;

						result["content"]["turns"].map((item) => {
							querytable += `
            <tr>
              <td>${item.patient_name}</td>
              <td>${item.doctor_name}</td>
              <td>${item.hour}</td>
            </tr>
          `;
						});

						querytable += `
            </tbody>
          </table>
        `;

					}

					document.getElementById("queryTable").innerHTML = querytable;
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
				<button class="btn btn-primary"
						onclick="submitWithoutDatatable('insertAccount', '<?= base_url() ?>admin/insert_receipt', '','receiptModal')">
					<?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<!-- Expenses Modal End -->


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
	document.addEventListener("DOMContentLoaded", function () {
		// jalaliDatepicker.startWatch();
		jalaliDatepicker.startWatch();
	});
</script>


<script>
	document.addEventListener("DOMContentLoaded", function () {
		feather.replace();
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
							querytable += `
            <tr>
              <td dir="ltr" style="text-align:right;">${item.serial_id}</td>
              <td><a href="<?= base_url('admin/single_patient/') ?>${item.id}" target="_blank">${item.fullname}</a></td>
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

