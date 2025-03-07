<?php $ci = get_instance(); ?>
<script>
	// TODO the actions for patients single page
	function actions() {
		let actionValues = $("#selectaction").val();
		console.log(actionValues);
		if (actionValues == 1) {
			$(`#extralargemodal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 2) {
			payment_modal_clicked();
			$(`#paymentModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 3) {
			// request();
			list_prostho_teeth('<?= $profile['id'] ?>');

			$(`#laboratoryInsertModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 4) {
			$(`#insertPrescription`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
		if (actionValues == 5) {
			$(`#filesModal`).modal("toggle");
			$("#selectaction").val("").trigger("change");
		}
	}

	// TODO: toogleView function please note that this function placed at the cdn/assets/js/teeth.js

	function toogleView() {
		let toggleSelect = $("#selectToggleView").val();
		let upperTeeth = document.getElementById("upperTeethAdult");
		let downTeeth = document.getElementById("downTeethAdult");
		let teethBackground = document.getElementById("teethBackground");
		let vl = document.querySelector("div.vl");
		let v2 = document.querySelector("div.v2");
		let vSpace1 = document.getElementById("vspace1");
		let vSpace2 = document.getElementById("vspace2");
		let vSpace3 = document.getElementById("vspace3");
		let vSpace4 = document.getElementById("vspace4");

		if (toggleSelect == "simple") {
			upperTeeth.classList.remove("upperTeethXray");
			downTeeth.classList.remove("downTeethXray");
			teethBackground.classList.remove("teethContainer" && "containerAdult");

			upperTeeth.classList.toggle("upperTeethSimple");
			downTeeth.classList.toggle("downTeethSimple");

			vl.style.display = "block";
			v2.style.display = "block";
			vSpace1.style.display = "block";
			vSpace2.style.display = "block";
			vSpace3.style.display = "block";
			vSpace4.style.display = "block";
		} else {
			upperTeeth.classList.remove("upperTeethSimple");
			downTeeth.classList.remove("downTeethSimple");

			upperTeeth.classList.toggle("upperTeethXray");
			downTeeth.classList.toggle("downTeethXray");
			teethBackground.classList.toggle("teethContainer" && "containerAdult");
			vl.style.display = "none";
			v2.style.display = "none";
			vSpace1.style.display = "none";
			vSpace2.style.display = "none";
			vSpace3.style.display = "none";
			vSpace4.style.display = "none";
		}
	}

	function list_labs() {
		$.ajax({
			url: "<?= base_url('admin/list_labs_json') ?>",
			type: 'POST',
			data: {
				record: <?= $profile['id'] ?>
			},
			success: function(response) {
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
                            <a href="javascript:edit_lab('${lab['id']}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit"></span></a>
                            <a href="javascript:print_lab('${lab['id']}')" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-print"></span></a>
                            <a href="javascript:delete_via_alert('${lab['id']}', '<?= base_url() ?>admin/delete_lab', 'labsTable')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash"></span></a>
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
					toastr["error"](result['alert']['text'], result['alert']['title'])
				}
			}
		});
	}

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
