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
							<input role="switch" type="checkbox" class="switch-input" id="turns_insert"/>
							<label class="switch-input-label">insert</label>
						</div>

					</div>

					<div class="customHr"></div>

					<button class="customSaveBTN" id="savePermissionBTN"
							onclick="setupSaveButton('savePermissionBTN', 'turns_Checkbox', 'turnsDetails', 'turnsPermission'); trackToggleStates('turnsPermission')">
						Save
					</button>

				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="turns_Checkbox"
					   onclick="setupCheckboxLogging('turns_Checkbox','turnsDetails', 'turnsPermission')">
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path
						d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
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
							<input role="switch" type="checkbox" class="switch-input" id="receipts_insert"/>
							<label class="switch-input-label">insert</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_update"/>
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_delete"/>
							<label class="switch-input-label">delete</label>
						</div>

					</div>

					<div class="customHr"></div>

					<div class="permissionContainer_footer">
						<!--						<button class="customSaveBTN" id="savePermissionBTN" onclick="setupSaveButton('savePermissionBTN', 'receipts_Checkbox', 'receiptsDetails', 'receiptsPermission'); trackToggleStates('receiptsPermission')"> Save </button>-->
						<button class="btn btn-primary" onclick="toggleAllToggles('receiptsPermission')"> Select all
						</button>
						<!--						setupCancelButton(cancelButtonId, checkboxId, detailsTagId, permissionContainer1)-->
						<button class="btn btn-primary" id="receiptsPermissionCancel"
								onclick="setupCancelButton('receiptsPermissionCancel','receipts_Checkbox','receiptsDetails', 'receiptsPermission')">
							cancel
						</button>
						<button class="btn btn-success" id="savePermissionBTN_recepts"
								onclick="setupSaveButton('savePermissionBTN_recepts', 'receipts_Checkbox', 'receiptsDetails', 'receiptsPermission'); trackToggleStates('receiptsPermission')">
							Save
						</button>
					</div>


				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="receipts_Checkbox"
					   onclick="setupCheckboxLogging('receipts_Checkbox','receiptsDetails', 'receiptsPermission')">
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path
						d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
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


	// document.addEventListener("DOMContentLoaded", function() {
	// 	// Select all permission checkboxes
	// 	const permissionCheckboxes = document.querySelectorAll(".permissionCheckBox");
	//
	// 	// Add a click event listener to each checkbox
	// 	permissionCheckboxes.forEach(checkbox => {
	// 		checkbox.addEventListener("click", function() {
	// 			// Get the id of the parent container div for the checked checkbox
	// 			const parentContainerId = this.closest(".col-sm-12.col-md-3").id;
	//
	// 			// Check if the checkbox is checked
	// 			if (this.checked) {
	// 				// Loop through each permission container div
	// 				document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
	// 					// Skip the parent container of the checked checkbox
	// 					if (container.id !== parentContainerId) {
	// 						// Add the disable-permission class
	// 						container.classList.add("disable-permission");
	// 					}
	// 				});
	// 			} else {
	// 				document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
	// 					container.classList.remove("disable-permission");
	// 				});
	// 			}
	// 		});
	// 	});
	// });
	//
	//
	// let originalPosition; // Global variable to store the original position
	//
	// function openDetails(detailsTagId, permissionContainer1) {
	// 	const detailsTag = document.getElementById(detailsTagId);
	// 	const permissionContainer = document.getElementById(permissionContainer1);
	//
	// 	if (!detailsTag || !permissionContainer) return; // Ensure elements exist
	//
	// 	detailsTag.setAttribute('open', '');
	// 	detailsTag.classList.remove('pointerEventsDisable');
	//
	// 	// Add Bootstrap classes as needed
	// 	permissionContainer.classList.remove('col-md-3');
	// 	permissionContainer.classList.add('col-md-12');
	//
	// 	// Store the original position if not already stored
	// 	const parentContainer = permissionContainer.parentNode;
	// 	if (!originalPosition && parentContainer) {
	// 		originalPosition = { parent: parentContainer, nextSibling: permissionContainer.nextElementSibling };
	// 	}
	//
	// 	// Move permissionContainer to the first child
	// 	if (parentContainer) {
	// 		parentContainer.insertBefore(permissionContainer, parentContainer.firstChild);
	// 	}
	// }
	//
	// function closeDetails(detailsTagId, permissionContainer1) {
	// 	const detailsTag = document.getElementById(detailsTagId);
	// 	const permissionContainer = document.getElementById(permissionContainer1);
	//
	// 	if (!detailsTag || !permissionContainer) return; // Ensure elements exist
	//
	// 	// Close the details tag
	// 	detailsTag.removeAttribute('open');
	// 	detailsTag.classList.add('pointerEventsDisable');
	//
	// 	// Revert Bootstrap classes
	// 	permissionContainer.classList.remove('col-md-12');
	// 	permissionContainer.classList.add('col-md-3');
	//
	// 	// Restore original position if stored
	// 	if (originalPosition && originalPosition.parent) {
	// 		originalPosition.parent.insertBefore(permissionContainer, originalPosition.nextSibling);
	// 	}
	//
	// 	// Remove the 'disable-permission' class from all elements
	// 	document.querySelectorAll('.disable-permission').forEach(element => {
	// 		element.classList.remove('disable-permission');
	// 	});
	// }
	//
	// function setupSaveButton(saveButtonId, checkboxId, detailsTagId, permissionContainer1) {
	// 	const saveButton = document.getElementById(saveButtonId);
	// 	const checkbox = document.getElementById(checkboxId);
	//
	// 	if (saveButton) {
	// 		saveButton.addEventListener('click', function () {
	// 			// Uncheck the checkbox
	// 			if (checkbox) {
	// 				checkbox.checked = false;
	// 			}
	// 			// Call the closeDetails function to reset everything
	// 			closeDetails(detailsTagId, permissionContainer1);
	// 		});
	// 	} else {
	// 		console.error(`Button with ID ${saveButtonId} not found.`);
	// 	}
	// }
	//
	// function setupCheckboxLogging(checkboxId, detailsTagId, permissionContainer1) {
	// 	const checkbox = document.getElementById(checkboxId);
	//
	// 	if (checkbox) {
	// 		checkbox.addEventListener('change', function () {
	// 			if (this.checked) {
	// 				openDetails(detailsTagId, permissionContainer1);
	// 			} else {
	// 				// Unchecking calls the closeDetails function to reset everything
	// 				closeDetails(detailsTagId, permissionContainer1);
	// 			}
	// 		});
	// 	} else {
	// 		console.error(`Checkbox with ID ${checkboxId} not found.`);
	// 	}
	// }
	//
	// function setupCancelButton(cancelButtonId, checkboxId, detailsTagId, permissionContainer1, sectionId) {
	// 	const cancelButton = document.getElementById(cancelButtonId);
	// 	const checkbox = document.getElementById(checkboxId);
	// 	const detailsTag = document.getElementById(detailsTagId);
	// 	const permissionContainer = document.getElementById(permissionContainer1);
	// 	const section = document.getElementById(sectionId);
	//
	// 	if (cancelButton) {
	// 		cancelButton.addEventListener('click', function () {
	// 			console.log("Cancel button clicked.");
	//
	// 			// 1. Uncheck the checkbox immediately
	// 			if (checkbox) {
	// 				checkbox.checked = false;
	// 				checkbox.dispatchEvent(new Event('change')); // Trigger change event to update state
	// 				console.log("Checkbox unchecked.");
	// 			} else {
	// 				console.warn(`Checkbox with ID ${checkboxId} not found.`);
	// 			}
	//
	// 			// 2. Close the details tag directly and disable pointer events
	// 			if (detailsTag) {
	// 				detailsTag.removeAttribute('open');
	// 				detailsTag.classList.add('pointerEventsDisable');
	// 				console.log("Details tag closed and disabled.");
	// 			} else {
	// 				console.warn(`Details tag with ID ${detailsTagId} not found.`);
	// 			}
	//
	// 			// 3. Reset permission container classes to default state
	// 			if (permissionContainer) {
	// 				permissionContainer.classList.remove('col-md-12');
	// 				permissionContainer.classList.add('col-md-3'); // Adjust as needed
	// 				console.log("Permission container classes reset.");
	// 			} else {
	// 				console.warn(`Permission container with ID ${permissionContainer1} not found.`);
	// 			}
	//
	// 			// 4. Remove 'disable-permission' class from all elements
	// 			document.querySelectorAll('.disable-permission').forEach(element => {
	// 				element.classList.remove('disable-permission');
	// 			});
	// 			console.log("All 'disable-permission' classes removed.");
	//
	// 			// 5. Untoggle all toggle switches in the specified section
	// 			if (section) {
	// 				const toggles = section.querySelectorAll('.switch-input');
	// 				toggles.forEach(toggle => {
	// 					toggle.checked = false; // Ensure all toggles are off
	// 				});
	// 				console.log(`All toggles in section ${sectionId} have been turned off.`);
	// 			} else {
	// 				console.warn(`Section with ID ${sectionId} not found.`);
	// 			}
	//
	// 			console.log("Cancel button action completed: state reset.");
	// 		});
	// 	} else {
	// 		console.error(`Button with ID ${cancelButtonId} not found.`);
	// 	}
	// }
	//
	// function trackToggleStates(category_id) {
	// 	// store the toggle states
	// 	const toggleStates = {};
	//
	// 	//permission containers
	// 	const permissionContainers = document.querySelectorAll(`[id^="${category_id}"]`); //remember this shit It may be usable for future
	//
	// 	permissionContainers.forEach(container => {
	// 		const containerId = container.id;
	// 		toggleStates[containerId] = []; // I copy this form stackoverflow (remember)
	//
	// 		// Find all switch checkbox
	// 		const switches = container.querySelectorAll('.switch-input');
	//
	// 		switches.forEach((input, index) => {
	// 			if (input.checked) {
	// 				// store the switches with theri ID
	// 				toggleStates[containerId].push({switchId: input.id });
	// 			}
	// 		});
	// 	});
	//
	// 	console.log(toggleStates); // Print the toggle states to the console
	// 	// return toggleStates; // Return the states if you want to use it further
	// }
	//
	// let toggleState = true; // Global state to track the toggle status
	//
	// function toggleAllToggles(sectionId) {
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
	// 	// Toggle each checkbox based on the global state
	// 	checkboxes.forEach(checkbox => {
	// 		checkbox.checked = toggleState;
	// 	});
	//
	// 	// Log the state change
	// 	console.log(`All toggles in ${sectionId} are now ${toggleState ? 'ON' : 'OFF'}.`);
	//
	// 	// Toggle the state for the next click
	// 	toggleState = !toggleState;
	// }


	// function setupCancelButton(cancelButtonId, checkboxId, detailsTagId, permissionContainer1) {
	// 	const cancelButton = document.getElementById(cancelButtonId);
	// 	const checkbox = document.getElementById(checkboxId);
	//
	// 	if (cancelButton) {
	// 		cancelButton.addEventListener('click', function () {
	// 			// Uncheck the checkbox
	// 			if (checkbox) {
	// 				checkbox.checked = false;
	// 			}
	// 			// Call the closeDetails function to reset everything
	// 			closeDetails(detailsTagId, permissionContainer1);
	//
	// 			// Optionally, remove the 'disable-permission' class if necessary
	// 			document.querySelectorAll('.disable-permission').forEach(element => {
	// 				element.classList.remove('disable-permission');
	// 			});
	// 		});
	// 	} else {
	// 		console.error(`Button with ID ${cancelButtonId} not found.`);
	// 	}
	// }


</script>

<script>
	// document.addEventListener("DOMContentLoaded", function() {
	// 	// Select all permission checkboxes
	// 	const permissionCheckboxes = document.querySelectorAll(".permissionCheckBox");
	//
	// 	// Add a click event listener to each checkbox
	// 	permissionCheckboxes.forEach(checkbox => {
	// 		checkbox.addEventListener("click", function() {
	// 			// Get the id of the parent container div for the checked checkbox
	// 			const parentContainerId = this.closest(".col-sm-12.col-md-3").id;
	//
	// 			// Check if the checkbox is checked
	// 			if (this.checked) {
	// 				// Loop through each permission container div
	// 				document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
	// 					// Skip the parent container of the checked checkbox
	// 					if (container.id !== parentContainerId) {
	// 						// Add the disable-permission class
	// 						container.classList.add("disable-permission");
	// 					}
	// 				});
	// 			} else {
	// 				document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3").forEach(container => {
	// 					container.classList.remove("disable-permission");
	// 				});
	// 			}
	// 		});
	// 	});
	// });

</script>

<script>
	// function trackToggleStates(category_id) {
	// 	// store the toggle states
	// 	const toggleStates = {};
	//
	// 	//permission containers
	// 	const permissionContainers = document.querySelectorAll(`[id^="${category_id}"]`); //remember this shit It may be usable for future
	//
	// 	permissionContainers.forEach(container => {
	// 		const containerId = container.id;
	// 		toggleStates[containerId] = []; // I copy this form stackoverflow (remember)
	//
	// 		// Find all switch checkbox
	// 		const switches = container.querySelectorAll('.switch-input');
	//
	// 		switches.forEach((input, index) => {
	// 			if (input.checked) {
	// 				// store the switches with theri ID
	// 				toggleStates[containerId].push({switchId: input.id });
	// 			}
	// 		});
	// 	});
	//
	// 	console.log(toggleStates); // Print the toggle states to the console
	// 	// return toggleStates; // Return the states if you want to use it further
	// }
	//
	// let toggleState = true; // Global state to track the toggle status
	//
	// function toggleAllToggles(sectionId) {
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
	// 	// Toggle each checkbox based on the global state
	// 	checkboxes.forEach(checkbox => {
	// 		checkbox.checked = toggleState;
	// 	});
	//
	// 	// Log the state change
	// 	console.log(`All toggles in ${sectionId} are now ${toggleState ? 'ON' : 'OFF'}.`);
	//
	// 	// Toggle the state for the next click
	// 	toggleState = !toggleState;
	// }


</script>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		setupPermissionHandling();
		setupSaveButton("savePermissionBTN", "permissionCheckBoxId", "detailsTagId", "permissionContainer1");
		setupCancelButton("cancelButtonId", "permissionCheckBoxId", "detailsTagId", "permissionContainer1", "sectionId");
		setupCheckboxLogging("permissionCheckBoxId", "detailsTagId", "permissionContainer1");
	});

	function setupPermissionHandling() {
		const permissionContainer = document.querySelector("#permissionContainer");
		if (permissionContainer) {
			permissionContainer.addEventListener("click", function (event) {
				if (event.target.classList.contains("permissionCheckBox")) {
					handlePermissionToggle(event.target);
				}
			});
		}
	}

	function handlePermissionToggle(checkbox) {
		const parentContainer = checkbox.closest(".col-sm-12.col-md-3");
		if (!parentContainer) return;

		const parentContainerId = parentContainer.id;
		const containers = document.querySelectorAll("#permissionContainer .col-sm-12.col-md-3");

		containers.forEach(container => {
			container.classList.toggle("disable-permission", checkbox.checked && container.id !== parentContainerId);
		});
	}

	function openDetails(detailsTagId, permissionContainerId) {
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainerId);

		if (!detailsTag || !permissionContainer) return;

		detailsTag.setAttribute("open", "");
		detailsTag.classList.remove("pointerEventsDisable");

		permissionContainer.classList.remove("col-md-3");
		permissionContainer.classList.add("col-md-12");

		storeOriginalPosition(permissionContainer);
		moveToTop(permissionContainer);
	}

	function closeDetails(detailsTagId, permissionContainerId) {
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainerId);

		if (!detailsTag || !permissionContainer) return;

		detailsTag.removeAttribute("open");
		detailsTag.classList.add("pointerEventsDisable");

		permissionContainer.classList.remove("col-md-12");
		permissionContainer.classList.add("col-md-3");

		restoreOriginalPosition(permissionContainer);
		clearDisablePermissions();
	}

	function storeOriginalPosition(permissionContainer) {
		if (!permissionContainer.dataset.originalParent) {
			const parent = permissionContainer.parentNode;
			permissionContainer.dataset.originalParent = parent.id;
			permissionContainer.dataset.nextSibling = permissionContainer.nextElementSibling ? permissionContainer.nextElementSibling.id : null;
		}
	}

	function moveToTop(permissionContainer) {
		const parentContainer = permissionContainer.parentNode;
		if (parentContainer) {
			parentContainer.insertBefore(permissionContainer, parentContainer.firstChild);
		}
	}

	function restoreOriginalPosition(permissionContainer) {
		const parentId = permissionContainer.dataset.originalParent;
		const nextSiblingId = permissionContainer.dataset.nextSibling;
		const parent = document.getElementById(parentId);
		const nextSibling = nextSiblingId ? document.getElementById(nextSiblingId) : null;

		if (parent) {
			parent.insertBefore(permissionContainer, nextSibling);
		}
	}

	function clearDisablePermissions() {
		document.querySelectorAll(".disable-permission").forEach(element => {
			element.classList.remove("disable-permission");
		});
	}

	function setupSaveButton(saveButtonId, checkboxId, detailsTagId, permissionContainerId) {
		const saveButton = document.getElementById(saveButtonId);
		const checkbox = document.getElementById(checkboxId);

		if (saveButton) {
			if (checkbox) checkbox.checked = false;
			closeDetails(detailsTagId, permissionContainerId);

		} else {
			console.error(`Button with ID ${saveButtonId} not found.`);
		}
	}

	function setupCheckboxLogging(checkboxId, detailsTagId, permissionContainerId) {
		const checkbox = document.getElementById(checkboxId);

		if (checkbox) {
			checkbox.addEventListener("change", function () {
				if (this.checked) {
					openDetails(detailsTagId, permissionContainerId);
				} else {
					closeDetails(detailsTagId, permissionContainerId);
				}
			});
		} else {
			console.error(`Checkbox with ID ${checkboxId} not found.`);
		}
	}

	function setupCancelButton(cancelButtonId, checkboxId, detailsTagId, permissionContainerId, sectionId) {
		const cancelButton = document.getElementById(cancelButtonId);
		const checkbox = document.getElementById(checkboxId);
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainerId);
		const section = document.getElementById(sectionId);

		if (cancelButton) {
			// cancelButton.addEventListener("click", function () {
				if (checkbox) {
					checkbox.checked = false;
					checkbox.dispatchEvent(new Event("change"));
				}

				if (detailsTag) {
					detailsTag.removeAttribute("open");
					detailsTag.classList.add("pointerEventsDisable");
				}

				if (permissionContainer) {
					permissionContainer.classList.remove("col-md-12");
					permissionContainer.classList.add("col-md-3");
				}

				clearDisablePermissions();

				if (section) {
					const toggles = section.querySelectorAll(".switch-input");
					toggles.forEach(toggle => (toggle.checked = false));
				}
			// });
		} else {
			console.error(`Button with ID ${cancelButtonId} not found.`);
		}
	}

	function toggleAllToggles(sectionId) {
		const section = document.getElementById(sectionId);

		if (!section) {
			console.warn(`Section with ID ${sectionId} not found.`);
			return;
		}

		const checkboxes = section.querySelectorAll(".switch-input");
		checkboxes.forEach(checkbox => (checkbox.checked = toggleState));
		toggleState = !toggleState;
	}

	function trackToggleStates(categoryId) {
		// Create an array to store all checked switch IDs
		const activeSwitchIds = [];

		// Permission containers
		const permissionContainers = document.querySelectorAll(`[id^="${categoryId}"]`);

		permissionContainers.forEach(container => {
			// Find all switch checkboxes
			const switches = container.querySelectorAll('.switch-input');

			// Collect IDs of checked switches
			switches.forEach(input => {
				if (input.checked) {
					activeSwitchIds.push(input.id);
				}
			});
		});

		console.log(activeSwitchIds); // Print the list of active switch IDs to the console
		return activeSwitchIds; // Return the list if you want to use it further
	}


	let toggleState = true; // Global state to track the toggle status
	//
	// function toggleAllToggles(sectionId) {
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
	// 	// Toggle each checkbox based on the global state
	// 	checkboxes.forEach(checkbox => {
	// 		checkbox.checked = toggleState;
	// 	});
	//
	// 	// Log the state change
	// 	console.log(`All toggles in ${sectionId} are now ${toggleState ? 'ON' : 'OFF'}.`);
	//
	// 	// Toggle the state for the next click
	// 	toggleState = !toggleState;
	// }

</script>
