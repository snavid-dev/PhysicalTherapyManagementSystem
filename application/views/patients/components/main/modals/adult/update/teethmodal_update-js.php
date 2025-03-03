<?php
$ci = get_instance();
?>

<script>
	function updateTeeth(id) {
		$.ajax({
			url: "<?= base_url() ?>admin/single_tooth",
			type: 'POST',
			data: {slug: id},
			success: function (response) {
				let result = JSON.parse(response);
				let contents = result.content;
				console.log(contents);

				if (typeof contents.name === 'string' && !isNaN(contents.name)) {
					clearFields(); // Clears all fields before setting new data

					updateInputFields(contents);
					updateDiagnoseSelect(contents.diagnose);
					updateImages(contents.imgAddress);
					initializeCheckboxes(contents);

					if (contents.is_endo === "true") handleEndo(contents.endo);
					if (contents.is_restorative === "true") handleRestorative(contents.restorative);
					if (contents.is_prosthodontic === "true") handleProsthodontic(contents.prosthodontic);

					$("#teethmodal_update").modal('toggle');
				}
			}
		});
	}

	// ✅ Clears only relevant fields inside #teethmodal_update without triggering onchange events
	function clearFields() {
		$("#teethmodal_update input, #teethmodal_update select:not([multiple]), #teethmodal_update textarea")
			.val("")
			.trigger('change');

		// Disable onchange events temporarily for multiple selects
		const multiSelects = [
			"#services_restorative_update",
			"#services_endo_update",
			"#services_pro_update",
			"#select_diagnose_update",
			"#pro_color_update"
		];

		multiSelects.forEach(select => {
			$(select).off("change").val([]).trigger("change").on("change", function () {
				// Re-enable onchange event after clearing
			});
		});
	}

	// ✅ Updates Basic Input Fields
	function updateInputFields(contents) {
		updateFields({
			"#selectName_update": contents.name,
			"#locationSelector_update": contents.location
		});
	}

	// ✅ Fixes Diagnose Multi-Select Issue (Clears before updating)
	function updateDiagnoseSelect(diagnoseList) {
		let selectElement = $("#select_diagnose_update");
		selectElement.off("change").val([]).trigger("change"); // Clears previous selections

		let optionsHTML = selectElement.html();
		diagnoseList.forEach(item => {
			optionsHTML = optionsHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
		});

		selectElement.html(optionsHTML).trigger("change").on("change", function () {
			// Re-enable onchange event
		});
	}

	// ✅ Updates Images
	function updateImages(imgAddress) {
		const imgUrl = `https://canin-cdn.cyborgtech.co/assets/images/tooth${imgAddress}`;
		$('#modalImage2_update_restro, #modalImage_update').attr('src', imgUrl);
	}

	// ✅ Handles Endodontic Updates
	function handleEndo(endo) {
		updateMultiSelect("services_endo_update", endo.services);
		setTimeout(function () {
			updateEndoFields(endo);
			updateEndoCanals(endo);
			insert_endo_price_update(endo.price);
		}, 800);
	}

	function updateEndoFields(endo) {
		updateFields({
			"#canalselector_update": endo.root_number,
			"#price_tooth_endo_update": endo.price,
			"#details_update": endo.details,
			"#instypeObturation_update": endo.typeObturation,
			"#insTypeSealer_update": endo.TypeSealer,
			"#insTypeIrrigation_update": endo.TypeIrrigation
		});
	}

	function updateEndoCanals(endo) {
		for (let i = 1; i <= 5; i++) {
			if (endo[`r_name${i}`] !== undefined) {
				updateFields({
					[`#canalLocation${i}_update`]: endo[`r_name${i}`],
					[`#c_length${i}_update`]: endo[`r_width${i}`]
				});
			}
		}
	}

	// ✅ Handles Restorative Updates
	function handleRestorative(restorative) {
		updateMultiSelect("services_restorative_update", restorative.services);
		setTimeout(function () {
			updateRestorativeFields(restorative);
			insert_resto_price_update(restorative.price);
		}, 1000);
	}

	function updateRestorativeFields(restorative) {
		updateFields({
			"#updateCariesDepth": restorative.CariesDepth,
			"#updateMaterial": restorative.Material,
			"#updateRestorativeMaterial": restorative.RestorativeMaterial,
			"#updateCompositeBrand": restorative.CompositeBrand,
			"#updateBondingBrand": restorative.bondingBrand,
			"#updateAmalgamBrand": restorative.AmalgamBrand,
			"#price_tooth_restorative_update": restorative.price,
			"#restorative_details_update": restorative.details
		});
	}

	// ✅ Handles Prosthodontic Updates
	function handleProsthodontic(prosthodontic) {

		if (prosthodontic.color.length > 0) {

			updateMultiSelect("pro_color_update", prosthodontic.color);
		}


		updateMultiSelect("services_pro_update", prosthodontic.services);
		setTimeout(function () {
			updateProsthodonticFields(prosthodontic);
			insert_pro_price_update(prosthodontic.price);
		}, 1200);

		determineProsthodonticType(prosthodontic);
	}

	function updateProsthodonticFields(prosthodontic) {
		updateFields({
			"#filling_material_update": prosthodontic.filling_material,
			"#type_restoration_update": prosthodontic.type_restoration,
			"#post_update": prosthodontic.post,
			"#metal_screw_post_update": prosthodontic.customPost,
			"#crown_material_update": prosthodontic.crown_material,
			"#fiber_post_update": prosthodontic.PrefabricatedPost,
			"#impression_technique_update": prosthodontic.impression_technique,
			"#impression_material_update": prosthodontic.impression_material,
			"#content_material_update": prosthodontic.CementMaterial,
			"#protextarea_update": prosthodontic.details,
			"#price_tooth_pro_update": prosthodontic.price,
		});
	}

	// ✅ Determines if Prosthodontic Type is "abutment" or "pontic"
	function determineProsthodonticType(prosthodontic) {
		const checkFields = [
			prosthodontic.CementMaterial,
			prosthodontic.PrefabricatedPost,
			prosthodontic.color,
			prosthodontic.crown_material,
			prosthodontic.customPost,
			prosthodontic.filling_material,
			prosthodontic.post,
			prosthodontic.type_restoration
		];

		// If any of these fields have a value, it's "abutment"; otherwise, it's "pontic"
		const isAbutment = checkFields.some(field => field && field !== "");

		$("#type_pro_update").val(isAbutment ? "abutment" : "pontic").trigger("change");
	}

	// ✅ Generic Field Updater
	function updateFields(fields) {
		Object.keys(fields).forEach(selector => {
			if (fields[selector] !== undefined) {
				$(selector).val(fields[selector]).trigger('change');
			}
		});
	}

	// ✅ Generic Multi-Select Updater with Disabled `onchange` While Setting Values
	function updateMultiSelect(selectId, items) {
		let selectElement = $("#" + $.escapeSelector(selectId)); // Fix selector issue
		if (!selectElement.length) return;

		selectElement.off("change").val([]).trigger("change"); // Temporarily disable onchange

		let optionsHTML = selectElement.html();
		items.forEach(item => {
			optionsHTML = optionsHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
		});

		selectElement.html(optionsHTML).trigger("change").on("change", function () {
			// Re-enable onchange event after setting values
		});
	}

	// Function to calculate the sum of prosthodontic price and all other prices
	function calculateProsthodonticTotal() {
		let priceProsthodontics = parseFloat($('#price_tooth_pro_update').val()) || 0;
		let priceRestoUpdate = parseFloat($('#price_tooth_restorative_update').val()) || 0;
		let priceService = parseFloat($('#price_tooth_endo_update').val()) || 0;

		// Calculate the sum
		const sum = priceProsthodontics + priceRestoUpdate + priceService;

		// Update the prosthodontic price input fields
		$('#priceTag_pro_update').val(sum);

		return sum; // If you need to use the value somewhere else
	}

	// ✅ Add event listener to recalculate whenever the prosthodontic price changes
	$('#price_tooth_pro_update, #price_tooth_restorative_update, #price_tooth_endo_update').on('change', calculateProsthodonticTotal);

	// Call the function initially in case there are pre-filled values
	calculateProsthodonticTotal();


</script>
