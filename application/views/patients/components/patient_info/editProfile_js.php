<?php $ci = get_instance(); ?>
<script>
	var ageOld = <?= $profile['age']; ?>;

	function multiple_value(selectId = '#pains', inputId = '#model_value') {
		const select = $(selectId).val();
		let text = select.join()
		$(inputId).val(text);
	}

	// let pains = document.getElementById('pains').innerHTML;


	function edit_profile(id = <?= $profile['id'] ?>) {
		$.ajax({
			url: "<?= base_url('admin/edit_patient') ?>",
			type: 'POST',
			data: {slug: id},
			success: function (response) {
				var result = JSON.parse(response);

				if (result['type'] === 'success') {
					let content = result['content'];

					// Set input values
					$('#slug').val(content['slug']);
					$('#name').val(content['name']);
					$('#lname').val(content['lname']);
					$('#age').val(content['age']);
					$('#phone1').val(content['phone1']);
					$('#phone2').val(content['phone2']);
					$('#other_pains').val(content['other_pains']);
					$('#address').val(content['address']);
					$('#remarks').val(content['remarks']);
					$('#model_value').val(content['pains']);

					// Set single select values
					$("#doctor_id").val(content['doctor_id']).trigger('change');
					$("#gender").val(content['gender']).trigger('change');

					// Set multiple select (Select2)
					if (content['pains_select'].length > 0) {
						$("#pains").val(content['pains_select']).trigger('change');
					}

				} else if (result['type'] === 'error') {
					$.growl.error({
						title: result['alert']['title'],
						message: result['alert']['text']
					});
				}
			}
		});
	}

</script>
