<?php
$ci = get_instance();
?>
<div class="modal fade effect-scale" tabindex="-1" id="viewPrescriptionsMedicines" role="dialog">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">
					View Prescription's Medicines
				</h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">

					<div class="col-md-12">

						<form id="prescriptions_viewMedicines">
							<!-- row 1 -->
							<div class="row viewRows" id="prescription_row1" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine1" name="medicine_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_1" id="medicineDoze_Rx1_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx1_view" name="unit_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage1" name="usageType_1"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_1" class="form-control arrowLessInput"
											   id="view_medicineDay1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_1" class="form-control arrowLessInput"
											   id="view_medicineTime1">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_1" class="form-control arrowLessInput"
											   id="view_medicineAmount1">

									</div>
								</div>

							</div>
							<!-- row 1 -->

							<!-- row 2 -->
							<div class="row viewRows" id="prescription_row2" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine2" name="medicine_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_2" id="medicineDoze_Rx2_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx2_view" name="unit_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage2" name="usageType_2"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_2" class="form-control arrowLessInput"
											   id="view_medicineDay2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_2" class="form-control arrowLessInput"
											   id="view_medicineTime2">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_2" class="form-control arrowLessInput"
											   id="view_medicineAmount2">

									</div>
								</div>


							</div>
							<!-- row 2 -->

							<!-- row 3 -->
							<div class="row viewRows" id="prescription_row3" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine3" name="medicine_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_3" id="medicineDoze_Rx3_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx3_view" name="unit_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage3" name="usageType_3"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_3" class="form-control arrowLessInput"
											   id="view_medicineDay3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_3" class="form-control arrowLessInput"
											   id="view_medicineTime3">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_3" class="form-control arrowLessInput"
											   id="view_medicineAmount3">

									</div>
								</div>

							</div>
							<!-- row 3 -->

							<!-- row 4 -->
							<div class="row viewRows" id="prescription_row4" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine4" name="medicine_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_4" id="medicineDoze_Rx4_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx4_view" name="unit_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage4" name="usageType_4"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_4" class="form-control arrowLessInput"
											   id="view_medicineDay4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_4" class="form-control arrowLessInput"
											   id="view_medicineTime4">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_4" class="form-control arrowLessInput"
											   id="view_medicineAmount4">

									</div>
								</div>

							</div>
							<!-- row 4 -->


							<!-- row 5 -->
							<div class="row viewRows" id="prescription_row5" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine5" name="medicine_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_5" id="medicineDoze_Rx5_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx5_view" name="unit_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage5" name="usageType_5"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_5" class="form-control arrowLessInput"
											   id="view_medicineDay5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_5" class="form-control arrowLessInput"
											   id="view_medicineTime5">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_5" class="form-control arrowLessInput"
											   id="view_medicineAmount5">

									</div>
								</div>


							</div>
							<!-- row 5 -->

							<!-- row 6 -->
							<div class="row viewRows" id="prescription_row6" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine6" name="medicine_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>"
												onchange="getMedicienInfo(this.value,'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_6" id="medicineDoze_Rx6_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx6_view" name="unit_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage6" name="usageType_6"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_6" class="form-control arrowLessInput"
											   id="view_medicineDay6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_6" class="form-control arrowLessInput"
											   id="view_medicineTime6">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_6" class="form-control arrowLessInput"
											   id="view_medicineAmount6">

									</div>
								</div>

							</div>
							<!-- row 6 -->

							<!-- row 7 -->
							<div class="row viewRows" id="prescription_row7" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine7" name="medicine_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_7" id="medicineDoze_Rx7_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx7_view" name="unit_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage7" name="usageType_7"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_7" class="form-control arrowLessInput"
											   id="view_medicineDay7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_7" class="form-control arrowLessInput"
											   id="view_medicineTime7">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_7" class="form-control arrowLessInput"
											   id="view_medicineAmount7">

									</div>
								</div>

							</div>
							<!-- row 7 -->

							<!-- row 8 -->
							<div class="row viewRows" id="prescription_row8" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine8" name="medicine_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_8" id="medicineDoze_Rx8_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx8_view" name="unit_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage8" name="usageType_8"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_8" class="form-control arrowLessInput"
											   id="view_medicineDay8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_8" class="form-control arrowLessInput"
											   id="view_medicineTime8">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_8" class="form-control arrowLessInput"
											   id="view_medicineAmount8">

									</div>
								</div>

							</div>
							<!-- row 8 -->

							<!-- row 9 -->
							<div class="row viewRows" id="prescription_row9" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine9" name="medicine_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_9" id="medicineDoze_Rx9_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx9_view" name="unit_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage9" name="usageType_9"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">

											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_9" class="form-control arrowLessInput"
											   id="view_medicineDay9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_9" class="form-control arrowLessInput"
											   id="view_medicineTime9">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_9" class="form-control arrowLessInput"
											   id="view_medicineAmount9">

									</div>
								</div>

							</div>
							<!-- row 9 -->

							<!-- row 10 -->
							<div class="row viewRows" id="prescription_row10" style="display: none;">

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
										</label>
										<!-- this is an important select tag remember it -->
										<select id="view_medicine10" name="medicine_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($medicines as $medicine) : ?>
												<option value="<?= $medicine['id'] ?>">
													<?= $medicine['type'] ?>.
													<?= $medicine['name'] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Doze') ?> <span class="text-red">*</span>
										</label>

										<input type="number" name="doze_10" id="medicineDoze_Rx10_view"
											   class="form-control arrowLessInput">
									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
										</label>

										<select id="medicineUnite_Rx10_view" name="unit_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">
											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_units() as $unit) : ?>
												<option value="<?= $unit ?>">
													<?= $unit ?>
												</option>
											<?php endforeach; ?>
										</select>

									</div>
								</div>

								<div class="col-sm-12 col-md-2">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Medicine Usage') ?> <span class="text-red">*</span>
										</label>

										<select id="view_medicineUsage10" name="usageType_10"
												class="form-control select2-show-search form-select"
												data-placeholder="<?= $ci->lang('select') ?>">


											<option label="<?= $ci->lang('select') ?>"></option>
											<?php foreach ($ci->dentist->medicine_usage_type() as $type) : ?>
												<option value="<?= $type ?>">
													<?= $type ?>
												</option>
											<?php endforeach; ?>


										</select>
									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Day') ?>
										</label>

										<input type="number" name="day_10" class="form-control arrowLessInput"
											   id="view_medicineDay10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('Time') ?>
										</label>

										<input type="number" name="time_10" class="form-control arrowLessInput"
											   id="view_medicineTime10">

									</div>
								</div>

								<div class="col-sm-12 col-md-1">
									<div class="form-group">
										<label class="form-label">
											<?= $ci->lang('amount') ?>
										</label>

										<input type="number" name="amount_10" class="form-control arrowLessInput"
											   id="view_medicineAmount10">

									</div>
								</div>

							</div>
							<!-- row 10 -->


						</form>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">
					<?= $ci->lang('cancel') ?>
				</button>
				<button class="btn btn-primary"
						onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
					<?= $ci->lang('save') ?>
				</button>
			</div>
		</div>
	</div>
</div>
