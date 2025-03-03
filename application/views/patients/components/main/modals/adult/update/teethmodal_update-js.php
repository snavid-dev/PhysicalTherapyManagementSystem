<?php
$ci = get_instance();
?>

<script>
	function updateTeeth(id) {
		$.ajax({
			url: "<?= base_url() ?>admin/single_tooth",
			type: 'POST',
			data: {
				slug: id,
			},
			success: function (response) {
				let result = JSON.parse(response);
				let contents = result.content;
				console.log(contents);
				if (typeof contents.name === 'string' && !isNaN(contents.name)) {

					let alldiagnose = contents.diagnose;
					var diagnoses_select = document.getElementById("select_diagnose_update").innerHTML;
					alldiagnose.map((item) => {
						diagnoses_select = diagnoses_select.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
					})
					document.getElementById("select_diagnose_update").innerHTML = diagnoses_select;


					$('#modalImage2_update_restro').attr('src', `https://canin-cdn.cyborgtech.co/assets/images/tooth${contents.imgAddress}`);


					// added by navid
					$('#selectName_update').val(contents.name).trigger('change');
					$('#locationSelector_update').val(contents.location).trigger('change');
					// added by Arsalan Venom33! this part checks the checkboxes
					initializeCheckboxes(contents);

					if (contents.is_endo === "true") {
						$('#canalselector_update').val(contents.endo.root_number).trigger('change');


						$('#canalLocation1_update').val(contents.endo.r_name1).trigger('change');
						$('#c_length1_update').val(contents.endo.r_width1).trigger('change');

						$('#canalLocation2_update').val(contents.endo.r_name2).trigger('change');
						$('#c_length2_update').val(contents.endo.r_width2).trigger('change');

						$('#canalLocation3_update').val(contents.endo.r_name3).trigger('change');
						$('#c_length3_update').val(contents.endo.r_width3).trigger('change');

						$('#canalLocation4_update').val(contents.endo.r_name4).trigger('change');
						$('#c_length4_update').val(contents.endo.r_width4).trigger('change');

						$('#canalLocation5_update').val(contents.endo.r_name5).trigger('change');
						$('#c_length5_update').val(contents.endo.r_width5).trigger('change');

						// TODO: this part have some problems due to its not done yet!!!

						// let endoServices = contents.endo.services;
						// var updateInnerHTML = document.getElementById("services_update").innerHTML;
						// endoServices.map((item) => {
						// 	updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						// })
						// document.getElementById("services_update").innerHTML = updateInnerHTML;


						$('#price_tooth_update').val(contents.endo.price).trigger('change');
						$('#details_update').val(contents.endo.details).trigger('change');
						$('#instypeObturation_update').val(contents.endo.typeObturation).trigger('change');
						$('#insTypeSealer_update').val(contents.endo.TypeSealer).trigger('change');
						$('#insTypeIrrigation_update').val(contents.endo.TypeIrrigation).trigger('change');

						$('#modalImage_update').attr('src', `https://canin-cdn.cyborgtech.co/assets/images/tooth${contents.imgAddress}`);

					}

					if (contents.is_restorative === "true") {
						$('#updateCariesDepth').val(contents.restorative.CariesDepth).trigger('change');
						$('#updateMaterial').val(contents.restorative.Material).trigger('change');


						$('#updateRestorativeMaterial').val(contents.restorative.RestorativeMaterial).trigger('change');
						$('#updateCompositeBrand').val(contents.restorative.CompositeBrand).trigger('change');
						$('#updateBondingBrand').val(contents.restorative.bondingBrand).trigger('change');
						$('#updateAmalgamBrand').val(contents.restorative.AmalgamBrand).trigger('change');

						let restorativeServices = contents.restorative.services;
						var updateInnerHTML = document.getElementById("services_restorative_update").innerHTML;
						restorativeServices.map((item) => {
							updateInnerHTML = updateInnerHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_restorative_update").innerHTML = updateInnerHTML;

						$('#price_tooth_restorative_update').val(contents.restorative.price).trigger('change');
						$('#restorative_details_update').val(contents.restorative.details).trigger('change');


					}

					if (contents.is_prosthodontic === 'true'){
						$('#type_pro_update').val(contents.prosthodontic.post).trigger('change');
						$('#filling_material_update').val(contents.prosthodontic.filling_material).trigger('change');
						$('#type_restoration_update').val(contents.prosthodontic.type_restoration).trigger('change');
						$('#post_update').val(contents.prosthodontic.post).trigger('change');
						$('#metal_screw_post_update').val(contents.prosthodontic.customPost).trigger('change')
						$('#crown_material_update').val(contents.prosthodontic.crown_material).trigger('change');
						$('#fiber_post_update').val(contents.prosthodontic.PrefabricatedPost).trigger('change');

						let procolors = contents.prosthodontic.color;
						var updateproColorHTML = document.getElementById("pro_color_update").innerHTML;
						procolors.map((item) => {
							updateproColorHTML = updateproColorHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("pro_color_update").innerHTML = updateproColorHTML;

						$('#impression_technique_update').val(contents.prosthodontic.impression_technique).trigger('change');
						$('#impression_material_update').val(contents.prosthodontic.impression_material).trigger('change');
						$('#content_material_update').val(contents.prosthodontic.CementMaterial).trigger('change');

						let proservices = contents.prosthodontic.services;
						var updateservicesHTML = document.getElementById("services_pro_update").innerHTML;
						proservices.map((item) => {
							updateservicesHTML = updateservicesHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
						})
						document.getElementById("services_pro_update").innerHTML = updateservicesHTML;

						$('#protextarea_update').val(contents.prosthodontic.details).trigger('change');




					}

					$(`#teethmodal_update`).modal('toggle');
				} else if (typeof contents.name === 'string' && /^[a-tA-T]+$/.test(contents.name)) {

				}
			}
		});


	}
</script>
