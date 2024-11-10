<?php $ci = get_instance(); ?>

<div class="row" id="permissionContainer" style="margin-top: 50px">


	<div class="col-sm-12 col-md-3" id="turnsPermission">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="turnsDetails">
				<summary class="customAccordion__summary">
					Turns
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="turns_insert" />
							<label class="switch-input-label">insert</label>
						</div>

					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN" onclick="setupSaveButton('savePermissionBTN', 'turns_Checkbox', 'turnsDetails', 'turnsPermission'); trackToggleStates('turnsPermission')"> Save </button>

				</div>
			</details>
			<?php
//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="turns_Checkbox" onclick="setupCheckboxLogging('turns_Checkbox','turnsDetails', 'turnsPermission')" >
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						  pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>

	</div>

	<div class="col-sm-12 col-md-3" id="receiptsPermission">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="receiptsDetails">
				<summary class="customAccordion__summary">
					receipts
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_insert" />
							<label class="switch-input-label">insert</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_update" />
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_delete" />
							<label class="switch-input-label">delete</label>
						</div>

					</div>

					<div class="customHr"></div>

					<div class="permissionContainer_footer">
<!--						<button class="customSaveBTN" id="savePermissionBTN" onclick="setupSaveButton('savePermissionBTN', 'receipts_Checkbox', 'receiptsDetails', 'receiptsPermission'); trackToggleStates('receiptsPermission')"> Save </button>-->
						<button class="btn btn-primary" onclick="toggleAllToggles('receiptsPermission')"> Select all </button>
						<button class="btn btn-success" id="savePermissionBTN" onclick="setupSaveButton('savePermissionBTN', 'receipts_Checkbox', 'receiptsDetails', 'receiptsPermission'); trackToggleStates('receiptsPermission')"> Save </button>
					</div>


				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="receipts_Checkbox" onclick="setupCheckboxLogging('receipts_Checkbox','receiptsDetails', 'receiptsPermission')" >
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

<script>
	function trackToggleStates(category_id) {
		// store the toggle states
		const toggleStates = {};

		//permission containers
		const permissionContainers = document.querySelectorAll(`[id^="${category_id}"]`); //remember this shit It may be usable for future

		permissionContainers.forEach(container => {
			const containerId = container.id;
			toggleStates[containerId] = []; // I copy this form stackoverflow (remember)

			// Find all switch checkbox
			const switches = container.querySelectorAll('.switch-input');

			switches.forEach((input, index) => {
				if (input.checked) {
					// store the switches with theri ID
					toggleStates[containerId].push({switchId: input.id });
				}
			});
		});

		console.log(toggleStates); // Print the toggle states to the console
		// return toggleStates; // Return the states if you want to use it further
	}


	// function toggleAllToggles(sectionId, toggleState) {
	// 	// Find the section element by its ID
	// 	const section = document.getElementById(sectionId);
	//
	// 	if (!section) {
	// 		console.warn(`Section with ID ${sectionId} not found.`);
	// 		return;
	// 	}
	//
	// 	// Get all checkboxes within the section
	// 	const checkboxes = section.querySelectorAll('.switch-input');
	//
	// 	// Toggle each checkbox based on the provided state
	// 	checkboxes.forEach(checkbox => {
	// 		checkbox.checked = toggleState;
	// 	});
	//
	// 	console.log(`All toggles in ${sectionId} are now ${toggleState ? 'ON' : 'OFF'}.`);
	// }


	let toggleState = true; // Global state to track the toggle status

	function toggleAllToggles(sectionId) {
		// Find the section element by its ID
		const section = document.getElementById(sectionId);

		if (!section) {
			console.warn(`Section with ID ${sectionId} not found.`);
			return;
		}

		// Get all checkboxes within the section
		const checkboxes = section.querySelectorAll('.switch-input');

		// Toggle each checkbox based on the global state
		checkboxes.forEach(checkbox => {
			checkbox.checked = toggleState;
		});

		// Log the state change
		console.log(`All toggles in ${sectionId} are now ${toggleState ? 'ON' : 'OFF'}.`);

		// Toggle the state for the next click
		toggleState = !toggleState;
	}





</script>
