<?php $ci = get_instance(); ?>

<div class="row" id="permissionContainer" style="margin-top: 50px">


	<div class="col-sm-12 col-md-3" id="permissionContainer1">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="myDetails1">
				<summary class="customAccordion__summary">
					Permission 1
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN" onclick="setupSaveButton('savePermissionBTN', 'permission1', 'myDetails1', 'permissionContainer1');"> Save </button>

				</div>
			</details>
			<?php
//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="permission1" onclick="setupCheckboxLogging('permission1','myDetails1', 'permissionContainer1')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>

	<div class="col-sm-12 col-md-3" id="permissionContainer2">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="myDetails2">
				<summary class="customAccordion__summary">
					Permission 2
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN2" onclick="setupSaveButton('savePermissionBTN2', 'permission2', 'myDetails2', 'permissionContainer2');"> Save </button>

				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="permission2" onclick="setupCheckboxLogging('permission2','myDetails2', 'permissionContainer2')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>

	<div class="col-sm-12 col-md-3" id="permissionContainer3">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="myDetails3">
				<summary class="customAccordion__summary">
					Permission 3
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN3" onclick="setupSaveButton('savePermissionBTN3', 'permission3', 'myDetails3', 'permissionContainer3');"> Save </button>

				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="permission3" onclick="setupCheckboxLogging('permission3','myDetails3', 'permissionContainer3')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>

	<div class="col-sm-12 col-md-3" id="permissionContainer4">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="myDetails4">
				<summary class="customAccordion__summary">
					Permission 4
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN4" onclick="setupSaveButton('savePermissionBTN4', 'permission4', 'myDetails4', 'permissionContainer4');"> Save </button>

				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="permission4" onclick="setupCheckboxLogging('permission4','myDetails4', 'permissionContainer4')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>

	<div class="col-sm-12 col-md-3" id="permissionContainer5">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="myDetails5">
				<summary class="customAccordion__summary">
					Permission 5
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="switchId" />
							<label class="switch-input-label">permission1</label>
						</div>
					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN5" onclick="setupSaveButton('savePermissionBTN5', 'permission5', 'myDetails5', 'permissionContainer5');"> Save </button>

				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="permission5" onclick="setupCheckboxLogging('permission5','myDetails5', 'permissionContainer5')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>



</div>

<script>
	// 	function setupCheckboxLogging(checkboxId, detailsTagId, permissionContainer1) {
	// 	const detailsTag = document.getElementById(detailsTagId);
	// 	const permissionContainer = document.getElementById(permissionContainer1);
	// 	const checkbox = document.getElementById(checkboxId);
	//
	// 	if (checkbox) {
	// 		checkbox.addEventListener('change', function () {
	// 			if (this.checked) {
	// 				detailsTag.setAttribute('open', '');
	// 				detailsTag.classList.remove('pointerEventsDisable');
	//
	// 				permissionContainer.classList.remove('col-md-2');
	// 				permissionContainer.classList.add('col-md-12');
	//
	// 				// Move permissionContainer to be the first child in its parent container
	// 				const parentContainer = permissionContainer.parentNode;
	// 				if (parentContainer) {
	// 					parentContainer.insertBefore(permissionContainer, parentContainer.firstChild);
	// 				}
	// 			} else {
	// 				detailsTag.removeAttribute('open');
	// 				detailsTag.classList.add('pointerEventsDisable');
	//
	// 				permissionContainer.classList.remove('col-md-12');
	// 				permissionContainer.classList.add('col-md-2');
	//
	// 				// Optional: Move permissionContainer back to its original position if (check this shit later)
	// 				const parentContainer = permissionContainer.parentNode;
	// 				const referenceNode = parentContainer.querySelector(':scope > *:nth-child(2)');
	// 				if (parentContainer && referenceNode) {
	// 					parentContainer.insertBefore(permissionContainer, referenceNode);
	// 				}
	// 			}
	// 		});
	// 	} else {
	// 		console.error(`Checkbox with ID ${checkboxId} not found.`);
	// 	}
	// }

	function openDetails(detailsTagId, permissionContainer1) {
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainer1);

		detailsTag.setAttribute('open', '');
		detailsTag.classList.remove('pointerEventsDisable');

		permissionContainer.classList.remove('col-md-2');
		permissionContainer.classList.add('col-md-12');

		// Move permissionContainer to be the first child in its parent container
		const parentContainer = permissionContainer.parentNode;
		if (parentContainer) {
			parentContainer.insertBefore(permissionContainer, parentContainer.firstChild);
		}
	}

	function closeDetails(detailsTagId, permissionContainer1) {
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainer1);

		detailsTag.removeAttribute('open');
		detailsTag.classList.add('pointerEventsDisable');

		permissionContainer.classList.remove('col-md-12');
		permissionContainer.classList.add('col-md-2');

		// Move permissionContainer back to its original position
		const parentContainer = permissionContainer.parentNode;
		const referenceNode = parentContainer.querySelector(':scope > *:nth-child(2)');
		if (parentContainer && referenceNode) {
			parentContainer.insertBefore(permissionContainer, referenceNode);
		}
	}

	function setupSaveButton(saveButtonId, checkboxId, detailsTagId, permissionContainer1) {
		const saveButton = document.getElementById(saveButtonId);
		const checkbox = document.getElementById(checkboxId);

		if (saveButton) {
			saveButton.addEventListener('click', function () {
				// Uncheck the checkbox
				if (checkbox) {
					checkbox.checked = false;
				}
				// Call the closeDetails function
				closeDetails(detailsTagId, permissionContainer1);

				document.querySelectorAll('.disable-permission').forEach(element => {
					element.classList.remove('disable-permission');
				});
			});
		} else {
			console.error(`Button with ID ${saveButtonId} not found.`);
		}
	}


	function setupCheckboxLogging(checkboxId, detailsTagId, permissionContainer1) {
		const checkbox = document.getElementById(checkboxId);

		if (checkbox) {
			checkbox.addEventListener('change', function () {
				if (this.checked) {
					openDetails(detailsTagId, permissionContainer1);
				} else {
					closeDetails(detailsTagId, permissionContainer1);
				}
			});
		} else {
			console.error(`Checkbox with ID ${checkboxId} not found.`);
		}
	}


</script>


<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Select all permission checkboxes
		const permissionCheckboxes = document.querySelectorAll(".permissionCheckBox");

		// Add a click event listener to each checkbox
		permissionCheckboxes.forEach(checkbox => {
			checkbox.addEventListener("click", function() {
				// Get the id of the parent container div for the checked checkbox
				const parentContainerId = this.closest(".col-sm-12.col-md-3").id;

				// Check if the checkbox is checked
				if (this.checked) {
					// Loop through each permission container div
					document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
						// Skip the parent container of the checked checkbox
						if (container.id !== parentContainerId) {
							// Add the disable-permission class
							container.classList.add("disable-permission");
						}
					});
				} else {
					document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
						container.classList.remove("disable-permission");
					});
				}
			});
		});
	});

</script>
