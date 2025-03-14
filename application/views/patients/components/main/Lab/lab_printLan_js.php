<?php $ci = get_instance(); ?>
<script>
	function print_lab(labId) {
		window.open(`<?= base_url() ?>admin/print_lab/${labId}`, '_blank');
	}
</script>

<script>
	function list_prostho_teeth(patient_id, selectId = '#selectTeeth') {
		$.ajax({
			url: "<?= base_url('admin/list_prostho_teeth') ?>",
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

	function print_turn(turnId) {
		window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
	}
</script>

<script>
	function edit_lab(id) {
		$.ajax({
			url: "<?= base_url('admin/single_lab') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function(response) {
				// request('#selectTeeth_edit');
				let results = JSON.parse(response);
				let data = results.content;
				console.log(data);

				$('#selectLab_edit').val(data.lab_id).trigger('change');


				let tooth = data.teeth;
				list_prostho_teeth('<?= $profile['id'] ?>', '#selectTeeth_edit')

				setTimeout(function () {
				$("#selectTeeth_edit").val(data.teeth).trigger('change');
				}, 500)

				$('#selectTeethHiddenInput_edit').val(data.teeth_hidden);



				let toothtypes = data.types;

				$("#selectToothType_edit").val(data.types).trigger('change');
				$('#selectToothTypeHiddenInput_edit').val(data.types_hidden);


				$('#deliveryDate_edit').val(data.delivery_date).trigger('change');
				$('#deliveryTime_edit').val(data.delivery_time);
				$('#selectToothColor_edit').val(data.tooth_color).trigger('change');
				$('#payment_edit').val(data.pay_amount).trigger('change');
				$('#details_edit').val(data.remarks).trigger('change');
				$('#labIdSlug').val(id);
			}
		})


		$(`#laboratoryEditModal`).modal('toggle');
	}
</script>

<script>
	function firstTry(id){
		$('#type_try').val('first');
		$('#hiddenId').val(id);
		$("#triesModal").modal('toggle');
	}
	function secondTry(id){
		$('#type_try').val('second');
		$('#hiddenId').val(id);
		$("#triesModal").modal('toggle');
	}

	function finish(id){
		$.ajax({
			url: "<?= base_url('admin/finish_lab') ?>",
			type: 'POST',
			data: {
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					list_labs();
					toastr["success"](result['alert']['text'], result['alert']['title'])
				}else{
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

	function showTry(id, type){
		$.ajax({
			url: "<?= base_url('admin/show_try') ?>",
			type: 'POST',
			data: {
				type: type,
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#details_lab').show();
					$('#details_lab').val(result['content']['message']);
					$('#datetime_lab').val(result['content']['datetime']);
					$("#showtriesModal").modal('toggle');
				}else{
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

	function showfinish(id){
		$.ajax({
			url: "<?= base_url('admin/show_try') ?>",
			type: 'POST',
			data: {
				type: 'finish',
				record: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#details_lab').hide();
					$('#datetime_lab').val(result['content']['datetime']);
					$("#showtriesModal").modal('toggle');
				}else{
					toastr["error"](result['messages'][step], result['title'])
				}

			}
		})
	}

</script>
