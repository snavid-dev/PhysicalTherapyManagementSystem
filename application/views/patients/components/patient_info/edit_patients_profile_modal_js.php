
<script>
	let pains = document.getElementById('pains').innerHTML;
	let doctor_id = document.getElementById('doctor_id').innerHTML;
	let gender = document.getElementById('gender').innerHTML;

	function edit_profile(id = <?= $profile['id'] ?>)
	{
		$.ajax({
			url: "<?= base_url('admin/edit_patient') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				var result = JSON.parse(response);
				if (result['type'] == 'success') {
					$('#slug').val(result['content']['slug']);
					$('#name').val(result['content']['name']);
					$('#lname').val(result['content']['lname']);
					$('#age').val(result['content']['age']);
					$('#phone1').val(result['content']['phone1']);
					$('#phone2').val(result['content']['phone2']);
					$('#other_pains').val(result['content']['other_pains']);
					$('#address').val(result['content']['address']);
					$('#remarks').val(result['content']['remarks']);
					$('#model_value').val(result['content']['pains']);
					let pains_selected = result['content']['pains_select'];
					let pains_new = pains;
					pains_selected.map((item) => {
						pains_new = pains_new.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					});
					$("#pains").html(pains_new);

					let gender_new = gender;
					gender_new = gender_new.replace(`<option value="${result['content']['gender']}">`, `<option value="${result['content']['gender']}" selected>`);
					$("#gender").html(gender_new);


					let doctor = doctor_id;
					doctor = doctor.replace(`<option value="${result['content']['doctor_id']}">`, `<option value="${result['content']['doctor_id']}" selected>`);
					$("#doctor_id").html(doctor);


					// select_with_search('edit_profile');
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
