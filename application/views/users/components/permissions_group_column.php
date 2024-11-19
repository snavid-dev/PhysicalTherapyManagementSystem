<?php $ci = get_instance(); ?>


<div class="row" id="permissionContainer" style="margin-top: 50px">

	<?php
	foreach ($permissions as $category):
		?>
		<div class="col-sm-12 col-md-3 permissionDev" id="<?= $category['category_id'] ?>">
			<div id="customAccordion">
				<details class="customAccordion__details pointerEventsDisable"
						 id="details<?= $category['category_id'] ?>">
					<summary class="customAccordion__summary">
						<?= $category['category_name'] ?>
					</summary>
					<div class="customAccordion__content">
						<div class="customAccordion__permissions">

							<?php
							foreach ($category['permissions'] as $permission):
								?>
								<div class="switch flex_switch">
									<input role="switch" type="checkbox" class="switch-input"
										   value="<?= $permission['permission_id'] ?>"/>
									<label class="switch-input-label"><?= $permission['permission_name'] ?></label>
								</div>
							<?php
							endforeach;
							?>

						</div>

						<div class="customHr"></div>

						<div class="permissionContainer_footer">
							<button class="btn btn-primary"
									onclick="toggleAllToggles(<?= $category['category_id'] ?>)"> Select all
							</button>
							<button class="btn btn-primary" id="PermissionCancel<?= $category['category_id'] ?>"
									onclick='setupCancelButton("PermissionCancel<?= $category['category_id'] ?>","checkbox<?= $category['category_id'] ?>","details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>")'>
								cancel
							</button>
							<button class="btn btn-success" id="savePermissionBTN_<?= $category['category_id'] ?>"
									onclick='setupSaveButton("savePermissionBTN_<?= $category['category_id'] ?>", "checkbox<?= $category['category_id'] ?>", "details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>"); trackToggleStates("<?= $category['category_id'] ?>")'>
								Save
							</button>
						</div>


					</div>
				</details>


				<label class="custom-checkbox-container" style="margin-top: 16px">
					<input type="checkbox" class="custom-checkbox-input permissionCheckBox"
						   id="checkbox<?= $category['category_id'] ?>"
						   onclick='setupCheckboxLogging("checkbox<?= $category['category_id'] ?>","details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>")'>
					<svg viewBox="0 0 64 64" height="1.5em" width="1.5em" class="custom-checkbox-svg">
						<path
							d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16"
							pathLength="575.0541381835938" class="custom-checkbox-path">
						</path>
					</svg>
				</label>

			</div>

		</div>
	<?php
	endforeach;
	?>

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

		// console.log(activeSwitchValues); // Print the list of active switch values to the console
		return activeSwitchValues; // Return the list if you want to use it further
	}


	let toggleState = true; // Global state to track the toggle status

</script>
