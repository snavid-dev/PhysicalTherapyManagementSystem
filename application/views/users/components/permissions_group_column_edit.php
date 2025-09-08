<?php $ci = get_instance(); ?>

<style>
	.hide {
		display: none;
	}
</style>

<div class="row" id="permissionContainer" style="margin-top: 50px">
	<?php
	foreach ($permissions as $category):
		?>
		<div class="col-sm-12 col-md-3 permissionDev" id="<?= $category['category_id'] ?>">
			<div id="customAccordion">
				<details class="customAccordion__details pointerEventsDisable"
						 id="details<?= $category['category_id'] ?>">
					<summary class="customAccordion__summary">
						<?= $ci->lang($category['category_name']); ?>
					</summary>
					<div class="customAccordion__content">
						<div class="row" style="row-gap: 20px">

							<?php
							foreach ($category['permissions'] as $permission):
								// Check if the permission is assigned
								$permission_names = array_column($assigned_permissions, 'id');
								$isChecked = in_array($permission['permission_id'], $permission_names) ? 'checked' : '';
								?>
								<div class="col-sm-12 col-md-4">
									<div class="switch flex_switch">
										<input role="switch" type="checkbox" class="switch-input"
											   value="<?= $permission['permission_id'] ?>" <?= $isChecked ?>/>
										<label
											class="switch-input-label"><?= $ci->lang($permission['permission_name']) ?></label>
									</div>
								</div>
							<?php
							endforeach;
							?>

						</div>
						<div class="customHr"></div>
						<div class="permissionContainer_footer">
							<button class="btn btn-primary" id="echo<?= $category['category_id'] ?>"
									onclick="initializeToggleFunctionality('echo<?= $category['category_id'] ?>', '<?= $category['category_id'] ?>')">
								<?= $ci->lang('select all') ?>
							</button>
							<button class="btn btn-primary" id="PermissionCancel<?= $category['category_id'] ?>"
									onclick='setupCancelButton("PermissionCancel<?= $category['category_id'] ?>","checkbox<?= $category['category_id'] ?>","details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>")'>
								<?= $ci->lang('cancel') ?>
							</button>
							<button class="btn btn-success" id="savePermissionBTN_<?= $category['category_id'] ?>"
									onclick='setupSaveButton("savePermissionBTN_<?= $category['category_id'] ?>", "checkbox<?= $category['category_id'] ?>", "details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>"); trackToggleStates("<?= $category['category_id'] ?>")'>
								<?= $ci->lang('save') ?>
							</button>
						</div>
					</div>
				</details>
				<label class="custom-checkbox-container" id="label<?= $category['category_id'] ?>"
					   style="margin-top: 16px">
					<input type="checkbox" class="custom-checkbox-input permissionCheckBox"
						   id="checkbox<?= $category['category_id'] ?>"
						   onclick='setupCheckboxLogging("checkbox<?= $category['category_id'] ?>","details<?= $category['category_id'] ?>", "<?= $category['category_id'] ?>");toggle_view_label("<?= $category['category_id'] ?>")'>
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
		toggle_view_label(permissionContainerId);
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


	function setupCancelButton(cancelButtonId, checkboxId, detailsTagId, permissionContainerId) {
		toggle_view_label(permissionContainerId);
		const cancelButton = document.getElementById(cancelButtonId);
		const checkbox = document.getElementById(checkboxId);
		const detailsTag = document.getElementById(detailsTagId);
		const permissionContainer = document.getElementById(permissionContainerId);

		if (cancelButton) {
			// cancelButton.addEventListener("click", function () {
			// Uncheck the main checkbox and trigger its change event
			if (checkbox) {
				checkbox.checked = false;
				checkbox.dispatchEvent(new Event("change"));
			}

			// Close the details tag and disable pointer events
			if (detailsTag) {
				detailsTag.removeAttribute("open");
				detailsTag.classList.add("pointerEventsDisable");
			}

			// Reset the permission container layout classes
			if (permissionContainer) {
				permissionContainer.classList.remove("col-md-12");
				permissionContainer.classList.add("col-md-3");
			}

			// Clear any disable-permission classes (assuming this is a utility function)
			clearDisablePermissions();

			// Untoggle all toggles within the details tag
			if (detailsTag) {
				const toggles = detailsTag.querySelectorAll(".switch-input");
				toggles.forEach(toggle => {
					toggle.checked = false;
					// Trigger a change event for toggles
					toggle.dispatchEvent(new Event("change"));
				});
			}
			// });
		} else {
			console.error(`Cancel button with ID "${cancelButtonId}" not found.`);
		}
	}


	function initializeToggleFunctionality(buttonId, containerId) {
		const toggleContainer = document.getElementById(containerId);
		const selectAllButton = document.getElementById(buttonId);
		const toggles = toggleContainer.querySelectorAll('.switch-input');

		const allSelected = Array.from(toggles).every(toggle => toggle.checked);

		toggles.forEach(toggle => {
			toggle.checked = !allSelected;
		});

		updateButtonText(selectAllButton, !allSelected);

		toggles.forEach(toggle => {
			toggle.addEventListener('change', () => {
				const allSelected = Array.from(toggles).every(toggle => toggle.checked);
				updateButtonText(selectAllButton, allSelected);
			});
		});
	}

	function updateButtonText(button, allSelected) {
		button.textContent = allSelected ? "<?= $ci->lang('deselect all') ?>" : "<?= $ci->lang('select all') ?>";
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


<script>
	function toggle_view_label(label_id) {
		$(`#label${label_id}`).toggleClass("hide");
	}
</script>
