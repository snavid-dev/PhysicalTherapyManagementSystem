<?php
$ci = get_instance();
?>

<script>
	function updateTeeth(id, patient_id) {
		$.ajax({
			url: "<?= base_url() ?>admin/single_tooth",
			type: 'POST',
			data: {slug: id},
			success: function (response) {
				let result = JSON.parse(response);
				let contents = result.content;

				if (contents && typeof contents.name !== "undefined") {
					clearFields(); // Clears all fields before setting new data

					if (typeof patient_id !== "undefined") {
						$('#patient_id_update').val(patient_id);
					}
					$('#tooth_id_update').val(id);

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
		$("#teethmodal_update select[multiple]").val([]).trigger('change');
	}

	// ✅ Updates Basic Input Fields
	function updateInputFields(contents) {
		updateFields({
			"#selectName_update": contents.name,
			"#locationSelector_update": contents.location,
			"#adulth_teeth_location_update": contents.imgAddress,
			"#adulth_teeth_location": contents.imgAddress
		});
	}

	// ✅ Fixes Diagnose Multi-Select Issue (Clears before updating)
	function updateDiagnoseSelect(diagnoseList) {
		let selectElement = $("#select_diagnose_update");
		const normalizedDiagnoseList = Array.isArray(diagnoseList) ? diagnoseList.map(String) : [];

		// Clear previous selections
		selectElement.val([]).trigger("change");

		// Select only existing <option> elements and mark them as selected
		selectElement.find("option").each(function () {
			this.selected = normalizedDiagnoseList.includes(String(this.value));
		});

		// Trigger change event after setting selections
		selectElement.trigger("change");
	}


	// ✅ Updates Images
	function updateImages(imgAddress) {
		const imgUrl = `https://canin-cdn.cyborgtech.co/assets/images/tooth${imgAddress}`;
		$('#modalImage_update_endo, #modalImage_update_resto, #modalImage_update_pro').attr('src', imgUrl);
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
			multiple_value('#pro_color_update', '#pro_colors_update');
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
		let selectElement = $("#" + $.escapeSelector(selectId));
		if (!selectElement.length) return;
		const normalizedItems = Array.isArray(items) ? items.map(String) : [];
		selectElement.val(normalizedItems).trigger("change");
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
	


</script>
