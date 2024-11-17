<?php $ci = get_instance(); ?>

	<div class="row row-sm">
		<div class="col-sm-12 col-md-12" style="border: 1px solid white; padding: 30px">
			<!-- input area -->
			<div class="col-sm-12 col-md-6" style="margin: 0 auto">
				<div class="form-group">
					<label class="form-label">
						Enter the Permission
					</label>
					<input type="text" name="notset" class="form-control" placeholder="Permission Name"
						   style="height: 50px" id="roleName">
				</div>
			</div>

			<!--columns area start-->
			<?php
			$ci->render('users/components/permissions_group_column.php');
			?>
			<!--columns area end-->

			<button class="btn btn-secondary"  style="float: right" onclick="saveRole()">
				Save
			</button>
		</div>
	</div>


<?php
//$ci->render('users/components/test.php');
?>

<!--permissions ajax-->
<script>
	function saveRole() {
		let roleName = document.getElementById('roleName').value;
		// Object to hold all toggled switch states by permission category
		const toggledStatesByCategory = {};

		// Select all elements with the class 'permissionDev'
		const permissionContainers = document.querySelectorAll('.permissionDev');

		permissionContainers.forEach(container => {
			// Use the container's ID as the category key
			const categoryId = container.id;

			// Call the trackToggleStates function to get the toggled switch values for this category
			const toggledSwitchValues = trackToggleStates(categoryId);

			// Store the result in the object using the categoryId as the key
			toggledStatesByCategory[categoryId] = toggledSwitchValues;
		});

		// Log or handle the collected toggled states as needed
		console.log(toggledStatesByCategory);
		console.log(roleName);
		// return toggledStatesByCategory;
	}

	$.ajax({
		url: '<?= base_url() ?>Roles/insert_role',
		type: 'POST',
		data: {
			role_name: 'Doctor',
			categories: [
				{ category_id: 1, permissions: [1, 2] },
				{ category_id: 2, permissions: [3, 4] }
			]
		},
		dataType: 'json',
		success: function(response) {
			if (response.status === 'success') {
				alert('Role and permissions added successfully!');
			} else {
				alert('Error: ' + response.message);
			}
		},
		error: function() {
			alert('An error occurred while processing the request.');
		}
	});
</script>

