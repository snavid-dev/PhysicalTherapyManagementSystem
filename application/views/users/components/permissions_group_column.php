<?php $ci = get_instance(); ?>

<div class="row" id="permissionContainer" style="margin-top: 50px">


	<div class="col-sm-12 col-md-3 permissionDev" id="receiptsPermission">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="receiptsDetails">
				<summary class="customAccordion__summary">
					receipts
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_insert" value="insert"/>
							<label class="switch-input-label">insert</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_update" value="update"/>
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="receipts_delete" value="delete"/>
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

	<div class="col-sm-12 col-md-3 permissionDev" id="callLogPermission">
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="callLogDetails">
				<summary class="customAccordion__summary">
					Call Log
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="callLog_insert"/>
							<label class="switch-input-label">insert</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="callLog_update"/>
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="callLog_delete"/>
							<label class="switch-input-label">delete</label>
						</div>

					</div>

					<div class="customHr"></div>

					<div class="permissionContainer_footer">
						<button class="btn btn-primary" onclick="toggleAllToggles('callLogPermission')"> Select all
						</button>
						<button class="btn btn-primary" id="callLogPermissionCancel"
								onclick="setupCancelButton('callLogPermissionCancel','callLog_Checkbox','callLogDetails', 'callLogPermission')">
							cancel
						</button>
						<button class="btn btn-success" id="savePermissionBTN_callLog"
								onclick="setupSaveButton('savePermissionBTN_callLog', 'callLog_Checkbox', 'callLogDetails', 'callLogPermission'); trackToggleStates('callLogPermission')">
							Save
						</button>
					</div>


				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="callLog_Checkbox"
					   onclick="setupCheckboxLogging('callLog_Checkbox','callLogDetails', 'callLogPermission')">
				<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
					<path
						d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
						pathLength="575.0541381835938" class="custom-checkbox-path">
					</path>
				</svg>
			</label>

		</div>
	</div>

	<div class="col-sm-12 col-md-3 permissionDev" id="turnsPermission">
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

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="turns_update"/>
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" id="turns_delete"/>
							<label class="switch-input-label">delete</label>
						</div>

					</div>

					<div class="customHr"></div>

					<div class="permissionContainer_footer">
						<button class="btn btn-primary" onclick="toggleAllToggles('turnsPermission')"> Select all
						</button>
						<button class="btn btn-primary" id="turnsPermissionCancel"
								onclick="setupCancelButton('turnsPermissionCancel','turns_Checkbox','turnsDetails', 'turnsPermission')">
							cancel
						</button>
						<button class="btn btn-success" id="savePermissionBTN_turns"
								onclick="setupSaveButton('savePermissionBTN_turns', 'turns_Checkbox', 'turnsDetails', 'turnsPermission'); trackToggleStates('turnsPermission')">
							Save
						</button>
					</div>


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

	<div class="col-sm-12 col-md-3 permissionDev" id="patientsPermission">
<!--		1-->
		<div id="customAccordion">
			<details class="customAccordion__details pointerEventsDisable" id="patientsDetails">
				<summary class="customAccordion__summary">
					Patients
				</summary>
				<div class="customAccordion__content">
					<div class="customAccordion__permissions">

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" value="1"/>
							<label class="switch-input-label">insert</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" value="2"/>
							<label class="switch-input-label">update</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" value="3"/>
							<label class="switch-input-label">delete</label>
						</div>

						<div class="switch flex_switch">
							<input role="switch" type="checkbox" class="switch-input" value="4"/>
							<label class="switch-input-label">list</label>
						</div>

					</div>

					<div class="customHr"></div>

					<div class="permissionContainer_footer">
						<button class="btn btn-primary" onclick="toggleAllToggles('patientsPermission')"> Select all
						</button>
						<button class="btn btn-primary" id="patientsPermissionCancel"
								onclick="setupCancelButton('patientsPermissionCancel','patients_Checkbox','patientsDetails', 'patientsPermission')">
							cancel
						</button>
						<button class="btn btn-success" id="savePermissionBTN_patients"
								onclick="setupSaveButton('savePermissionBTN_patients', 'patients_Checkbox', 'patientsDetails', 'patientsPermission'); trackToggleStates('patientsPermission')">
							Save
						</button>
					</div>


				</div>
			</details>
			<?php
			//			$ci->render('users/components/custom_checkBox.php');
			?>

			<label class="custom-checkbox-container" style="margin-top: 16px">
				<input type="checkbox" class="custom-checkbox-input permissionCheckBox" id="patients_Checkbox"
					   onclick="setupCheckboxLogging('patients_Checkbox','patientsDetails', 'patientsPermission')">
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

<!--permission scripts-->
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
		// Create an array to store values of checked switches
		const activeSwitchValues = [];

		// Select all elements with IDs starting with categoryId
		const permissionContainers = document.querySelectorAll(`[id^="${categoryId}"]`);

		permissionContainers.forEach(container => {
			// Find all switch checkboxes within the container
			const switches = container.querySelectorAll('.switch-input');

			// Collect values of checked switches
			switches.forEach(input => {
				if (input.checked) {
					activeSwitchValues.push(input.value); // Use value instead of id
				}
			});
		});

		console.log(activeSwitchValues); // Print the list of active switch values to the console
		return activeSwitchValues; // Return the list if you want to use it further
	}



	let toggleState = true; // Global state to track the toggle status

</script>
