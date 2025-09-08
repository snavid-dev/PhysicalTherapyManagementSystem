<?php $ci = get_instance(); ?>
<script>
	function print_prescription(prescriptionId) {
		window.open(`<?= base_url() ?>admin/print_prescription/${prescriptionId}`, '_blank');
	}

	function viewPrescriptionsMedicines(id) {
		$.ajax({
			url: "<?= base_url('admin/single_prescription') ?>",
			type: 'POST',
			data: {
				slug: id
			},
			success: function (response) {
				let result = JSON.parse(response);
				let medicienDatas = result.content;

				// this part counts how many has to be shown-start
				var medicineCount = 0;

				for (var key in medicienDatas) {
					if (key.startsWith("medicine_") && medicienDatas[key] != 0) {
						console.log(key)
						medicineCount++;
					}
				}

				console.log(medicineCount);

				showRows(medicineCount);
				// this part counts how many has to be shown-end

				// row1 -------------
				$('#view_medicine1').val(medicienDatas.medicine_1).trigger('change');
				$('#medicineDoze_Rx1_view').val(medicienDatas.doze_1);
				$('#medicineUnite_Rx1_view').val(medicienDatas.unit_1).trigger('change');
				$('#view_medicineUsage1').val(medicienDatas.usageType_1).trigger('change');
				$('#view_medicineDay1').val(medicienDatas.day_1);
				$('#view_medicineTime1').val(medicienDatas.time_1);
				$('#view_medicineAmount1').val(medicienDatas.amount_1);
				// row1 -------------


				// row2 -------------
				$('#view_medicine2').val(medicienDatas.medicine_2).trigger('change');
				$('#medicineDoze_Rx2_view').val(medicienDatas.doze_2);
				$('#medicineUnite_Rx2_view').val(medicienDatas.unit_2).trigger('change');
				$('#view_medicineUsage2').val(medicienDatas.usageType_2).trigger('change');
				$('#view_medicineDay2').val(medicienDatas.day_2);
				$('#view_medicineTime2').val(medicienDatas.time_2);
				$('#view_medicineAmount2').val(medicienDatas.amount_2);
				// row2 -------------


				// row3 -------------
				$('#view_medicine3').val(medicienDatas.medicine_3).trigger('change');
				$('#medicineDoze_Rx3_view').val(medicienDatas.doze_3);
				$('#medicineUnite_Rx3_view').val(medicienDatas.unit_3).trigger('change');
				$('#view_medicineUsage3').val(medicienDatas.usageType_3).trigger('change');
				$('#view_medicineDay3').val(medicienDatas.day_3);
				$('#view_medicineTime3').val(medicienDatas.time_3);
				$('#view_medicineAmount3').val(medicienDatas.amount_3);
				// row3 -------------

				// row4 -------------
				$('#view_medicine4').val(medicienDatas.medicine_4).trigger('change');
				$('#medicineDoze_Rx4_view').val(medicienDatas.doze_4);
				$('#medicineUnite_Rx4_view').val(medicienDatas.unit_4).trigger('change');
				$('#view_medicineUsage4').val(medicienDatas.usageType_4).trigger('change');
				$('#view_medicineDay4').val(medicienDatas.day_4);
				$('#view_medicineTime4').val(medicienDatas.time_4);
				$('#view_medicineAmount4').val(medicienDatas.amount_4);
				// row4 -------------


				// row5 -------------
				$('#view_medicine5').val(medicienDatas.medicine_5).trigger('change');
				$('#medicineDoze_Rx5_view').val(medicienDatas.doze_5);
				$('#medicineUnite_Rx5_view').val(medicienDatas.unit_5).trigger('change');
				$('#view_medicineUsage5').val(medicienDatas.usageType_5).trigger('change');
				$('#view_medicineDay5').val(medicienDatas.day_5);
				$('#view_medicineTime5').val(medicienDatas.time_5);
				$('#view_medicineAmount5').val(medicienDatas.amount_5);
				// row5 -------------


				// row6 -------------
				$('#view_medicine6').val(medicienDatas.medicine_6).trigger('change');
				$('#medicineDoze_Rx6_view').val(medicienDatas.doze_6);
				$('#medicineUnite_Rx6_view').val(medicienDatas.unit_6).trigger('change');
				$('#view_medicineUsage6').val(medicienDatas.usageType_6).trigger('change');
				$('#view_medicineDay6').val(medicienDatas.day_6);
				$('#view_medicineTime6').val(medicienDatas.time_6);
				$('#view_medicineAmount6').val(medicienDatas.amount_6);
				// row6 -------------


				// row7 -------------
				$('#view_medicine7').val(medicienDatas.medicine_7).trigger('change');
				$('#medicineDoze_Rx7_view').val(medicienDatas.doze_7);
				$('#medicineUnite_Rx7_view').val(medicienDatas.unit_7).trigger('change');
				$('#view_medicineUsage7').val(medicienDatas.usageType_7).trigger('change');
				$('#view_medicineDay7').val(medicienDatas.day_7);
				$('#view_medicineTime7').val(medicienDatas.time_7);
				$('#view_medicineAmount7').val(medicienDatas.amount_7);
				// row7 -------------

				// row8 -------------
				$('#view_medicine8').val(medicienDatas.medicine_8).trigger('change');
				$('#medicineDoze_Rx8_view').val(medicienDatas.doze_8);
				$('#medicineUnite_Rx8_view').val(medicienDatas.unit_8).trigger('change');
				$('#view_medicineUsage8').val(medicienDatas.usageType_8).trigger('change');
				$('#view_medicineDay8').val(medicienDatas.day_8);
				$('#view_medicineTime8').val(medicienDatas.time_8);
				$('#view_medicineAmount8').val(medicienDatas.amount_8);
				// row8 -------------

				// row9 -------------
				$('#view_medicine9').val(medicienDatas.medicine_9).trigger('change');
				$('#medicineDoze_Rx9_view').val(medicienDatas.doze_9);
				$('#medicineUnite_Rx9_view').val(medicienDatas.unit_9).trigger('change');
				$('#view_medicineUsage9').val(medicienDatas.usageType_9).trigger('change');
				$('#view_medicineDay9').val(medicienDatas.day_9);
				$('#view_medicineTime9').val(medicienDatas.time_9);
				$('#view_medicineAmount9').val(medicienDatas.amount_9);
				// row9 -------------

				// row10 -------------
				$('#view_medicine10').val(medicienDatas.medicine_10).trigger('change');
				$('#medicineDoze_Rx10_view').val(medicienDatas.doze_10);
				$('#medicineUnite_Rx10_view').val(medicienDatas.unit_10).trigger('change');
				$('#view_medicineUsage10').val(medicienDatas.usageType_10).trigger('change');
				$('#view_medicineDay10').val(medicienDatas.day_10);
				$('#view_medicineTime10').val(medicienDatas.time_10);
				$('#view_medicineAmount10').val(medicienDatas.amount_10);
				// row10 -------------

			}
		});

		$(`#viewPrescriptionsMedicines`).modal('toggle');
	}


	function showRows(rownumber) {
		if (rownumber == 1) {
			$("#prescription_row1").show();
			$("#prescription_row2").hide();
			$("#prescription_row3").hide();
			$("#prescription_row4").hide();
			$("#prescription_row5").hide();
			$("#prescription_row6").hide();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 2) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").hide();
			$("#prescription_row4").hide();
			$("#prescription_row5").hide();
			$("#prescription_row6").hide();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 3) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").hide();
			$("#prescription_row5").hide();
			$("#prescription_row6").hide();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 4) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").hide();
			$("#prescription_row6").hide();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 5) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").hide();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 6) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").show();
			$("#prescription_row7").hide();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 7) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").show();
			$("#prescription_row7").show();
			$("#prescription_row8").hide();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 8) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").show();
			$("#prescription_row7").show();
			$("#prescription_row8").show();
			$("#prescription_row9").hide();
			$("#prescription_row10").hide();
		} else if (rownumber == 9) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").show();
			$("#prescription_row7").show();
			$("#prescription_row8").show();
			$("#prescription_row9").show();
			$("#prescription_row10").hide();
		} else if (rownumber == 10) {
			$("#prescription_row1").show();
			$("#prescription_row2").show();
			$("#prescription_row3").show();
			$("#prescription_row4").show();
			$("#prescription_row5").show();
			$("#prescription_row6").show();
			$("#prescription_row7").show();
			$("#prescription_row8").show();
			$("#prescription_row9").show();
			$("#prescription_row10").show();
		}
	}
</script>

