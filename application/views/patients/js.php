<?php
$ci = get_instance();
?>

<!--all services functions --- this mf is so important-->
<?php $ci->render('patients/components/main/modals/adult/services_js.php'); ?>

<!--edit profile function-->
<?php $ci->render('patients/components/patient_info/editProfile_js.php'); ?>

<!--date picker (jalali) loader-->
<script>
	document.addEventListener("DOMContentLoaded", function () {
		jalaliDatepicker.startWatch();
	});
</script>

<!--check turns and editTurns functions as turnsFunctions-->
<?php $ci->render('patients/components/main/Turns/turnsFunctions_js.php'); ?>


<script>
	function print_payment(turnId) {
		update_balance();
		window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
	}
</script>

<!--print lab-->
<?php $ci->render('patients/components/main/Lab/lab_printLan_js.php'); ?>


<!--print prescription-->
<?php $ci->render('patients/components/main/prescription/prescription_js.php'); ?>

<script>
	function payment_modal_clicked() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/list_turns_payment_pending') ?>",
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
		});
		update_process_completion();
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
							let delete_button = '';
							if (result.content.delete_access) {
								delete_button = `<a href="javascript:delete_via_alert('${tooth['id']}', '<?= base_url() ?>admin/delete_tooth', 'teethTable', update_balance)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa-solid fa-trash-can fs-14"></span></a>`;
							}
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
${delete_button}
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
