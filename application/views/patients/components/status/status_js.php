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
		if (actionValues == 6) {
			$('#patient_id').val(<?= $profile['id'] ?>); // Dynamically set patient ID

			list_teeth_recommended();
			$(`#recommended_processes`).modal("toggle");
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
			success: function (response) {
				let result = JSON.parse(response);
				let labs = result.content.labs;
				console.log(labs);
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
							let first_try_status = ``;
							let second_try_status = ``;
							let finish = ``;
							let money = ``;
							let init = ``;
							let install = ``;

							if (lab['init_date'] != '') {
								if (lab['first_try_status'] == 'p') {
									first_try_status += `<a href="javascript:firstTry('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-check-circle"></span></a> `;
								} else {
									first_try_status += `<a href="javascript:showTry('${lab['id']}', 'first')"
							   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-eye"></span></a> `;
								}

								if (lab['second_try_status'] == 'p') {
									second_try_status += `<a href="javascript:secondTry('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-check-circle"></span></a> `;
								} else {
									second_try_status += `<a href="javascript:showTry('${lab['id']}', 'second')"
							   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-eye"></span></a> `;
								}

								if (lab['status'] == 'p') {
									finish += `<a href="javascript:finish('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-check-circle"></span></a> `;
								} else {
									finish += `<a href="javascript:showfinish('${lab['id']}')"
							   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-eye"></span></a> `;
								}

								if (lab['install_time'] == null) {
									install += `<a href="javascript:install('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-tooth"></span></a> `;
								} else {
									install += `<a href="javascript:showinstall('${lab['id']}', 'first')"
							   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-eye"></span></a> `;
								}

								if (lab['status'] != 'm') {
									let locked = (lab['status'] != 'a' || lab['install_time'] == null) ? 'locked' : '';
									money += `<a href="javascript:payLab('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light ${locked}"><span
									class="fa fa-money"></span></a> `;
								}
							} else {
								init += `<a href="javascript:init_lab('${lab['id']}')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-check-circle"></span></a> `;
							}

							let buttons = first_try_status + second_try_status + finish + install + money + init;


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
                            ${buttons}
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

<script>
	function list_teeth_recommended(selectId = '#process_teeth') {
		let patient_id = '<?= $profile['id'] ?>';

		$.ajax({
			url: "<?= base_url('admin/list_process_teeth') ?>",
			type: 'POST',
			data: {
				record: patient_id
			},
			success: function (response) {
				$(selectId).html('');
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					let options = ``;
					result['content'].map((item) => {
						options += `<option value="${item.id}">${item.location}${item.name}</option>`;
					})
					$(selectId).html(options);
				}
			}
		})
	}
</script>


<script>
	function get_teeth_process() {
		const selectedTeeth = $('#process_teeth').val();
		const container = $('#teeth_processes_container');
		container.empty();

		if (!selectedTeeth || !selectedTeeth.length) return;

		$.ajax({
				url: "<?= base_url('admin/get_tooth_processes_by_teeth') ?>",
			type: 'POST',
			data: {
				teeth_ids: selectedTeeth,
				patient_id: '<?= $profile['id'] ?>'
			},
			dataType: 'json',
			success: function (res) {
				if (res.type !== 'success' || !Array.isArray(res.content)) return;
				if (res.content.length === 0) return;

				res.content.forEach((tooth, toothIndex) => {
					if (!Array.isArray(tooth.departments)) return;

					const toothId = tooth.tooth_id;
					const toothName = tooth.tooth_name;
					const recommended = tooth.recommended || {};
					const done = tooth.done || []; // array of process_ids (numbers or strings)
					const doneSet = new Set(done.map(p => String(p))); // for easy lookup

					let html = `
				<div class="row nthHrLine">
					<div class="col-12 greyline">
						<div class="customMargin">
							<div class="processHeader">
								<h2 style="margin-bottom: 30px">${toothName}</h2>
								<input type="hidden" name="tooth_id[]" value="${toothId}">
							</div>`;

					tooth.departments.forEach((dept, deptIndex) => {
						html += `<h5 class="text-primary">${dept.department}</h5><div class="row">`;

						const recommendedIds = (recommended[dept.department] || []).map(p => String(p.process_id));
						const otherText = recommended[`${dept.department}_other`] || '';
						const otherId = `other_textarea_${toothIndex}_${deptIndex}`;
						const otherVisible = otherText.trim() !== '';

						if (Array.isArray(dept.services)) {
							dept.services.forEach(service => {
								if (Array.isArray(service.processes)) {
									service.processes.forEach(process => {
										const processId = String(process.id);
										const isChecked = recommendedIds.includes(processId);
										const isDone = doneSet.has(processId);

										html += `
									<div class="col-sm-12 col-md-2 customMargin_processCheckbox">
										<label class="cl-checkbox">
											<input type="checkbox"
												${isDone ? 'disabled' : ''}
												${isChecked ? 'checked' : ''}
												${!isDone ? `name="processes[${toothId}][]"` : ''}
												value="${isDone ? '' : processId}">
											<span>${process.name}${isDone ? ' 🔒' : ''}</span>
										</label>
									</div>`;
									});
								}
							});
						}

						html += `
					<div class="col-12 col-md-12 mt-2">
						<label class="cl-checkbox">
							<input type="checkbox" data-target="${otherId}" onclick="otherProcess(this)" ${otherVisible ? 'checked' : ''}>
							<span><?= $ci->lang('other') ?></span>
						</label>
						<div class="mt-2" id="${otherId}" style="display: ${otherVisible ? 'block' : 'none'}">
							<label><?= $ci->lang('other process') ?></label>
							<textarea class="form-control" name="custom_process[${toothId}][${dept.department}]">${otherText}</textarea>
						</div>
					</div>`;

						html += '</div>'; // end dept row
					});

					html += '</div></div></div>'; // end tooth block
					container.append(html);
				});
			},
			error: function (xhr, status, error) {
				console.error("AJAX error:", error);
				container.empty();
			}
		});
	}

	function update_process_completion() {
		let patient_id = '<?= $profile['id'] ?>';
		$.ajax({
			url: "<?= base_url('admin/get_patient_process_completion') ?>",
			type: 'POST',
			data: { patient_id },
			dataType: 'json',
			success: function (response) {
				if (response.type === 'success') {
					const percentage = response.percentage || 0;

					// Update text
					$('#process_percentage').text(response.percentage_text);

					// Update progress bar
					document.querySelector('.progress-bar.bg-secondary.ronded-1').style.width = percentage + '%';
				}
			},
			error: function () {
				console.error("Failed to update process completion.");
			}
		});
	}


</script>
