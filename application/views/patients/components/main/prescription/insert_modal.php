<?php
$ci = get_instance();
?>
<div class="modal fade effect-scale" tabindex="-1" id="insertPrescription" role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <?= $ci->lang('insert prescription') ?>
                </h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="col-md-12">

                        <form id="prescriptions_setMedicines">
                            <!-- row 1 -->
                            <div class="row">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <input type="hidden" name="patient_id" value="<?= $profile['id'] ?>">
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine1" name="medicine_1"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx1', 'medicineUnite_Rx1', 'set_medicineUsage1', 'set_medicineDay1', 'set_medicineTime1', 'set_medicineAmount1')">
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

                                        <input type="number" name="doze_1" id="medicineDoze_Rx1"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx1" name="unit_1"
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

                                        <select id="set_medicineUsage1" name="usageType_1"
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
                                            id="set_medicineDay1">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_1" class="form-control arrowLessInput"
                                            id="set_medicineTime1">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_1" class="form-control arrowLessInput"
                                            id="set_medicineAmount1">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusBtn1" class="icon-btn add-btn" type="button"
                                                onclick=" plusBtn('setMedicien_row2', 'plusBtn1')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <!-- <div  style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button" onclick="removeBtn('', 'plusbtn1')">
                        <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                      </button>
                    </div> -->
                                    </div>


                                </div>

                            </div>
                            <!-- row 1 -->

                            <!-- row 2 -->
                            <div class="row" id="setMedicien_row2" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine2" name="medicine_2"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
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

                                        <input type="number" name="doze_2" id="medicineDoze_Rx2"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx2" name="unit_2"
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

                                        <select id="set_medicineUsage2" name="usageType_2"
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
                                            id="set_medicineDay2">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_2" class="form-control arrowLessInput"
                                            id="set_medicineTime2">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_2" class="form-control arrowLessInput"
                                            id="set_medicineAmount2">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2" id="PRBtns2">

                                    <div class="plusRemovBtns">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn2" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row3', 'plusbtn2')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row2', 'plusBtn1'), clearInput('set_medicine2', 'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 2 -->

                            <!-- row 3 -->
                            <div class="row" id="setMedicien_row3" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine3" name="medicine_3"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
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

                                        <input type="number" name="doze_3" id="medicineDoze_Rx3"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx3" name="unit_3"
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

                                        <select id="set_medicineUsage3" name="usageType_3"
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
                                            id="set_medicineDay3">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_3" class="form-control arrowLessInput"
                                            id="set_medicineTime3">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_3" class="form-control arrowLessInput"
                                            id="set_medicineAmount3">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2" id="PRBtns3">

                                    <div class="plusRemovBtns">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn3" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row4', 'plusbtn3')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row3', 'plusbtn2'),clearInput('set_medicine3', 'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 3 -->

                            <!-- row 4 -->
                            <div class="row" id="setMedicien_row4" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine4" name="medicine_4"
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

                                        <input type="number" name="doze_4" id="medicineDoze_Rx4"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx4" name="unit_4"
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

                                        <select id="set_medicineUsage4" name="usageType_4"
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
                                            id="set_medicineDay4">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_4" class="form-control arrowLessInput"
                                            id="set_medicineTime4">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_4" class="form-control arrowLessInput"
                                            id="set_medicineAmount4">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns4">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn4" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row5', 'plusbtn4')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row4', 'plusbtn3'), clearInput('set_medicine4', 'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 4 -->


                            <!-- row 5 -->
                            <div class="row" id="setMedicien_row5" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?> <span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine5" name="medicine_5"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
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

                                        <input type="number" name="doze_5" id="medicineDoze_Rx5"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx5" name="unit_5"
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

                                        <select id="set_medicineUsage5" name="usageType_5"
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
                                            id="set_medicineDay5">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_5" class="form-control arrowLessInput"
                                            id="set_medicineTime5">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_5" class="form-control arrowLessInput"
                                            id="set_medicineAmount5">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns5" type="button">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn5" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row6','plusbtn5')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row5', 'plusbtn4'), clearInput('set_medicine5', 'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 5 -->

                            <!-- row 6 -->
                            <div class="row" id="setMedicien_row6" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine6" name="medicine_6"
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

                                        <input type="number" name="doze_6" id="medicineDoze_Rx6"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx6" name="unit_6"
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

                                        <select id="set_medicineUsage6" name="usageType_6"
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
                                            id="set_medicineDay6">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_6" class="form-control arrowLessInput"
                                            id="set_medicineTime6">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_6" class="form-control arrowLessInput"
                                            id="set_medicineAmount6">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns4">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn6" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row7', 'plusbtn6')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row6', 'plusbtn5'), clearInput('set_medicine6', 'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 6 -->

                            <!-- row 7 -->
                            <div class="row" id="setMedicien_row7" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine7" name="medicine_7"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
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

                                        <input type="number" name="doze_7" id="medicineDoze_Rx7"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx7" name="unit_7"
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

                                        <select id="set_medicineUsage7" name="usageType_7"
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
                                            id="set_medicineDay7">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_7" class="form-control arrowLessInput"
                                            id="set_medicineTime7">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_7" class="form-control arrowLessInput"
                                            id="set_medicineAmount7">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn7" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row8', 'plusbtn7')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row7', 'plusbtn6'), clearInput('set_medicine7', 'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 7 -->

                            <!-- row 8 -->
                            <div class="row" id="setMedicien_row8" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine8" name="medicine_8"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
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

                                        <input type="number" name="doze_8" id="medicineDoze_Rx8"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx8" name="unit_8"
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

                                        <select id="set_medicineUsage8" name="usageType_8"
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
                                            id="set_medicineDay8">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_8" class="form-control arrowLessInput"
                                            id="set_medicineTime8">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_8" class="form-control arrowLessInput"
                                            id="set_medicineAmount8">

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns4">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn8" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row9', 'plusbtn8')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row8', 'plusbtn7'), clearInput('set_medicine8', 'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 8 -->

                            <!-- row 9 -->
                            <div class="row" id="setMedicien_row9" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine9" name="medicine_9"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
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

                                        <input type="number" name="doze_9" id="medicineDoze_Rx9"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx9" name="unit_9"
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

                                        <select id="set_medicineUsage9" name="usageType_9"
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
                                            id="set_medicineDay9">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_9" class="form-control arrowLessInput"
                                            id="set_medicineTime9">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_9" class="form-control arrowLessInput"
                                            id="set_medicineAmount9">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns4">
                                        <div class="" style="text-align: center;margin-top:5%">
                                            <button id="plusbtn9" class="icon-btn add-btn" type="button"
                                                onclick="plusBtn('setMedicien_row10', 'plusbtn9')">
                                                <div class="add-icon"></div>
                                                <div class="btn-txt"><?= $ci->lang('add') ?></div>
                                            </button>
                                        </div>
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row9', 'plusbtn8'), clearInput('set_medicine9', 'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- row 9 -->

                            <!-- row 10 -->
                            <div class="row" id="setMedicien_row10" style="display: none;">

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="set_medicine10" name="medicine_10"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="getMedicienInfo(this.value,'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
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

                                        <input type="number" name="doze_10" id="medicineDoze_Rx10"
                                            class="form-control arrowLessInput">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                                        </label>

                                        <select id="medicineUnite_Rx10" name="unit_10"
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

                                        <select id="set_medicineUsage10" name="usageType_10"
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
                                            id="set_medicineDay10">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('Time') ?>
                                        </label>

                                        <input type="number" name="time_10" class="form-control arrowLessInput"
                                            id="set_medicineTime10">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <?= $ci->lang('amount') ?>
                                        </label>

                                        <input type="number" name="amount_10" class="form-control arrowLessInput"
                                            id="set_medicineAmount10">

                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">

                                    <div class="plusRemovBtns" id="PRBtns4">
                                        <!-- <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn10" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn10')">
                        <div class="add-icon"></div>
                        <div class="btn-txt"><?= $ci->lang('add') ?></div>
                      </button>
                    </div> -->
                                        <div class="" style="text-align: center; margin-top: 8px;">
                                            <button class="icon-btn add-btn" type="button"
                                                onclick="removeBtn('setMedicien_row10', 'plusbtn9'), clearInput('set_medicine10', 'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
                                                <div class="btn-txt"><?= $ci->lang('remove') ?></div>
                                            </button>
                                        </div>
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
                    <?= $ci->lang('cancel') ?> <i class="fa fa-close"></i>
                </button>
                <button class="btn btn-warning"
                    onclick="submitWithoutDatatable('prescriptions_setMedicines', '<?= base_url() ?>admin/insert_prescription', 'prescriptionTable', 'insertPrescription', print_prescription, 'print')">
                    <?= $ci->lang('save and print') ?> <i class="fe fe-printer"></i>
                </button>
                <button class="btn btn-primary"
                    onclick="submitWithoutDatatable('prescriptions_setMedicines', '<?= base_url() ?>admin/insert_prescription', 'prescriptionTable', 'insertPrescription')">
                    <?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>
