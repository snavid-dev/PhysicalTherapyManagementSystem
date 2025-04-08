<?php $ci = get_instance(); ?>

<div class="row row-sm">
	<div class="col-sm-12 col-md-12" style="border: 1px solid white; padding: 30px">
		<!-- input area -->
		<div class="col-sm-12 col-md-6" style="margin: 0 auto">
			<div class="form-group">
				<label class="form-label">
					<?= $ci->lang('enter the role name'); ?>
				</label>
				<input type="text" name="notset" class="form-control" placeholder="<?= $ci->lang('role name') ?>"
					   style="height: 50px" id="roleName">
			</div>
		</div>

		<!--columns area start-->
		<?php
		$ci->render('users/components/permissions_group_column.php');
		?>
		<!--columns area end-->
		<div class="customHr"></div>

		<div style="text-align: end;">

			<button class="btn btn-primary" style="margin: 0 20px" onclick="cancelAllToggles()">
				<?= $ci->lang('cancel all') ?>
			</button>
			<button class="btn btn-success" onclick="saveRole()">
				<?= $ci->lang('save') ?>
			</button>

		</div>
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

		// Transform the object to the desired array format
		const resultArray = Object.entries(toggledStatesByCategory).map(([key, value]) => ({
			category_id: parseInt(key, 10), // Convert key to integer if necessary
			permissions: value.map(Number)  // Ensure values are numbers
		}));

		$.ajax({
			url: '<?= base_url() ?>Roles/insert_role',
			type: 'POST',
			data: {
				role_name: roleName,
				categories: resultArray
			},
			dataType: 'json',
			success: function (response) {
				if (response.status === 'success') {
					toastr["success"](response.message, response.title)
				} else {
					toastr["error"](response.message, response.title)
				}

				cancelAllToggles();
				location.reload();
			},
			error: function () {
				toastr["error"]("something went wrong . . . ", '<?= $ci->lang('error') ?>')
			}
		});
	}

	function cancelAllToggles() {

		const allToggles = document.querySelectorAll(".switch-input");
		const roleNameInput = document.getElementById('roleName');

		if (allToggles.length === 0) {
			console.warn("No toggles found on the page.");
			return;
		}

		// trigger from original cancel button
		allToggles.forEach(toggle => {
			toggle.checked = false;
			toggle.dispatchEvent(new Event("change")); // remember this shit it maybe useful
		});

		console.log(`${allToggles.length} toggles have been untoggled.`);
		roleNameInput.value = '';
	}


</script>

