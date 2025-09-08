<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0">
          <?= $ci->lang('accounts list') ?>
        </h3>
      </div>
      <div class="card-body">

        <!-- <div class="tab-menu-heading border-0 p-0">
          <div class="tabs-menu1">

            <ul class="nav panel-tabs product-sale" role="tablist">
              <li>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#labModal">
                  <?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
                </button>
              </li>
            </ul>

          </div>
        </div> -->

        <div class="table-responsive">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#labModal">
            <?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
          </button>
          <table class="table text-nowrap" id="turnsTable">
            <thead class="tableHead">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Lab</th>
                <th scope="col">Teeth</th>
                <th scope="col">Tooth Type</th>
                <th scope="col">Delivery Date</th>
                <th scope="col">Delivery Time</th>
                <th scope="col">payment</th>
                <th scope="col">Details</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr class="tableRow">
                <td scope="row">1</td>
                <td scope="row">lab1</td>
                <td scope="row">8-4-3</td>
                <td scope="row">Adulth</td>
                <td scope="row">2023/3/3</td>
                <td scope="row">FRI-01:30PM</td>
                <td scope="row">30$</td>
                <td scope="row">this is a detail</td>
                <td>
                  <div class="g-2">
                    <!-- controlBtns _______________________________________________________________________________________________________________start -->
                    <a class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
                      onclick="editModalToggle()"><span class="fa fa-edit"></span></a>


                    <a class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
                      onclick="prescriptionToggle()"><span class="fa fa-print"></span></a>
                    <a class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                      onclick="setMedicine()"><span class="fa fa-check-circle"></span></a>

                    <a onclick="viewPrescriptionsMedicines('5')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
                        class="fa fa-times-circle fs-14"></span></a>
                    <a class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
                        class="fa fa-trash"></span></a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>







<!-- Modal Start --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="labModal" role="dialog">

  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">
          Table Data Insert
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">

          <div class="col-md-12">

            <form id="tableInsert">
              <div class="row">

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Choose Lab <span class="text-red">*</span>
                    </label>
                    <!-- this is an important select tag remember it -->
                    <select id="selectLab" name="name" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="1">lab 1</option>
                      <option value="2">lab 2</option>
                      <option value="3">lab 3</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Choose Teeth <span class="text-red">*</span>
                    </label>

                    <select id="selectTeeth" name="location" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" multiple>
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="1">
                        <?= $ci->lang('up right') ?>
                      </option>
                      <option value="2">
                        <?= $ci->lang('up left') ?>
                      </option>
                      <option value="3">
                        <?= $ci->lang('down right') ?>
                      </option>
                      <option value="4">
                        <?= $ci->lang('down left') ?>
                      </option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-4">
                  <div class="form-group" id="main-divs">
                    <label class="form-label">
                      Tooth Type
                    </label>

                    <select id="selectToothType" name="type" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" multiple>
                      <option label="<?= $ci->lang('select') ?>"></option>

                      <option value="1">porcelain</option>
                      <option value="2">Metal</option>
                      <option value="3">Gold</option>
                      <option value="4">Partial Mobile</option>
                      <option value="5">Full Mobile</option>
                    </select>

                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Delivery Time<span class="text-red">*</span>
                    </label>

                    <input data-jdp type="text" id="deliveryDate" name="deliveryDate-date" class="form-control">

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Delivery Time<span class="text-red">*</span>
                    </label>

                    <select name="hour" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" id="deliveryTime">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->hours() as $hour):
                        ?>
                                                      <option value="<?= $hour['key'] ?>">
                                                        <?= $hour['value'] ?>
                                                      </option>
                      <?php endforeach; ?>
                    </select>

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Tooth Color<span class="text-red">*</span>
                    </label>

                    <select id="selectToothType" name="type" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>

                      <option value="1">A1</option>
                      <option value="2">A2</option>
                      <option value="3">A3</option>
                      <option value="4">A3.5</option>
                      <option value="5">B1</option>
                      <option value="5">B2</option>
                      <option value="5">C2</option>
                      <option value="5">A2E</option>
                      <option value="5">OP</option>
                      <option value="5">TL</option>

                    </select>

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
                    </label>
                    <input type="number" name="phone" id="payment" class="form-control"
                      placeholder="<?= $ci->lang('pay amount') ?>">
                  </div>
                </div>


              </div>
            </form>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-primary" onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
          <?= $ci->lang('save') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End -------------------------------------------------------------------------------------------------->

<!-- Modal Edit Start --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="editModal" role="dialog">

  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">
          Table Data Insert
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">

          <div class="col-md-12">

            <form id="tableInsert_edit">
              <div class="row">

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Choose Lab <span class="text-red">*</span>
                    </label>
                    <!-- this is an important select tag remember it -->
                    <select id="selectLab_edit" name="name" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="1">lab 1</option>
                      <option value="2">lab 2</option>
                      <option value="3">lab 3</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Choose Teeth <span class="text-red">*</span>
                    </label>

                    <select id="selectTeeth_edit" name="location" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" multiple>
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="1">
                        <?= $ci->lang('up right') ?>
                      </option>
                      <option value="2">
                        <?= $ci->lang('up left') ?>
                      </option>
                      <option value="3">
                        <?= $ci->lang('down right') ?>
                      </option>
                      <option value="4">
                        <?= $ci->lang('down left') ?>
                      </option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-4">
                  <div class="form-group" id="main-divs">
                    <label class="form-label">
                      Tooth Type
                    </label>

                    <select id="selectToothType_edit" name="type" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" multiple>
                      <option label="<?= $ci->lang('select') ?>"></option>

                      <option value="1">porcelain</option>
                      <option value="2">Metal</option>
                      <option value="3">Gold</option>
                      <option value="4">Partial Mobile</option>
                      <option value="5">Full Mobile</option>
                    </select>

                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Delivery Day<span class="text-red">*</span>
                    </label>

                    <input data-jdp type="text" id="deliveryDate_edit" name="deliveryDate-date" class="form-control">

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Delivery Time<span class="text-red">*</span>
                    </label>

                    <select name="hour" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>" id="deliveryTime_edit">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->hours() as $hour):
                        ?>
                                                      <option value="<?= $hour['key'] ?>">
                                                        <?= $hour['value'] ?>
                                                      </option>
                      <?php endforeach; ?>
                    </select>

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Tooth Color<span class="text-red">*</span>
                    </label>

                    <select id="selectToothColor_edit" name="type" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>

                      <option value="1">A1</option>
                      <option value="2">A2</option>
                      <option value="3">A3</option>
                      <option value="4">A3.5</option>
                      <option value="5">B1</option>
                      <option value="6">B2</option>
                      <option value="7">C2</option>
                      <option value="8">A2E</option>
                      <option value="9">OP</option>
                      <option value="10">TL</option>

                    </select>

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('pay amount') ?> <span class="text-red">*</span>
                    </label>
                    <input type="number" name="phone" id="payment_edit" class="form-control"
                      placeholder="<?= $ci->lang('pay amount') ?>">
                  </div>
                </div>

                <div class="col-sm-12 col-md-12">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('description') ?>
                    </label>
                    <textarea id="details_edit" class="form-control" name="remarks"
                      placeholder="<?= $ci->lang('description') ?>"></textarea>
                  </div>
                </div>

              </div>
            </form>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-primary" onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
          <?= $ci->lang('save') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit End -------------------------------------------------------------------------------------------------->



<!-- prescription Modal ُstart --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="prescription" role="dialog">

  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">
          prescription Data insert
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">

          <div class="col-md-12">

            <form id="prescriptions_insert">
              <div class="row">

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      Medicine Type <span class="text-red">*</span>
                    </label>
                    <!-- this is an important select tag remember it -->
                    <select id="medicineType" name="name" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="1">Cap</option>
                      <option value="Jab">Jab</option>
                      <option value="Amp">Amp</option>
                      <option value="Sgr">Sgr</option>
                      <option value="Cream">Cream</option>
                      <option value="Jel">Jel</option>
                      <option value="Oint">Oint</option>
                      <option value="Serum">Serum</option>
                      <option value="Prop">Prop</option>
                      <option value="Solution">Solution</option>
                      <option value="Vial">Vial</option>
                    </select>
                  </div>
                </div>


                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Medicine Name <span class="text-red">*</span>
                    </label>

                    <input type="text" class="form-control" id="medicineName">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      Medicine Unite <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite" name="name" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="Ml">Ml</option>
                      <option value="Mg">Mg (Mili Gram)</option>
                    </select>

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      Medicine Doze <span class="text-red">*</span>
                    </label>

                    <select id="medicineDoze" name="location" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="250">250 Mg</option>
                      <option value="500">500 Mg</option>
                      <option value="700">700 Mg</option>
                      <option value="1000">1000 Mg</option>

                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      Medicine Usage <span class="text-red">*</span>
                    </label>

                    <select id="medicineUsage" name="location" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <option value="Iv">Iv</option>
                      <option value="Im">Im</option>
                      <option value="Oral">Oral</option>
                      <option value="Sc">Sc</option>
                      <option value="ID">ID</option>
                      <option value="Local">Local</option>


                    </select>
                  </div>
                </div>

              </div>


              <div class="row">

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Day
                    </label>

                    <input type="number" class="form-control" id="medicineDay">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <!-- <span><ion-icon name="close-outline"></ion-icon></span> -->
                  <span class="the_X">X</span>

                </div>

                <div class="col-sm-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      Time
                    </label>

                    <input type="number" class="form-control" id="medicineTime">

                  </div>
                </div>

                <div class="col-sm-12 col-md-3">
                  <div class="form-group">
                    <label class="form-label">
                      Amount
                    </label>

                    <input type="number" class="form-control" id="medicineAmount">

                  </div>
                </div>



              </div>
            </form>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-primary" onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
          <?= $ci->lang('save') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- prescription Modal end -------------------------------------------------------------------------------------------------->


<!-- prescription SetMedicines Start --------------------------------------------------------------------------------------------- -->
<div class="modal fade effect-scale" tabindex="-1" id="setMedicines" role="dialog">

  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">
          Set Prescription Medicines
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
                    <!-- this is an important select tag remember it -->
                    <select id="set_medicine1" name="medicine_1" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx1', 'medicineUnite_Rx1', 'set_medicineUsage1', 'set_medicineDay1', 'set_medicineTime1', 'set_medicineAmount1')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_1" id="medicineDoze_Rx1" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx1" name="unit_1" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_1" class="form-control arrowLessInput" id="set_medicineDay1">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_1" class="form-control arrowLessInput" id="set_medicineTime1">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_1" class="form-control arrowLessInput" id="set_medicineAmount1">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusBtn1" class="icon-btn add-btn" type="button"
                        onclick=" plusBtn('setMedicien_row2', 'plusBtn1')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <!-- <div  style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button" onclick="removeBtn('', 'plusbtn1')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine2" name="medicine_2" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_2" id="medicineDoze_Rx2" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx2" name="unit_2" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_2" class="form-control arrowLessInput" id="set_medicineDay2">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_2" class="form-control arrowLessInput" id="set_medicineTime2">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_2" class="form-control arrowLessInput" id="set_medicineAmount2">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2" id="PRBtns2">

                  <div class="plusRemovBtns">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn2" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row3', 'plusbtn2')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row2', 'plusBtn1'), clearInput('set_medicine2', 'medicineDoze_Rx2', 'medicineUnite_Rx2', 'set_medicineUsage2', 'set_medicineDay2', 'set_medicineTime2', 'set_medicineAmount2')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine3" name="medicine_3" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_3" id="medicineDoze_Rx3" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx3" name="unit_3" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_3" class="form-control arrowLessInput" id="set_medicineDay3">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_3" class="form-control arrowLessInput" id="set_medicineTime3">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_3" class="form-control arrowLessInput" id="set_medicineAmount3">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2" id="PRBtns3">

                  <div class="plusRemovBtns">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn3" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row4', 'plusbtn3')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row3', 'plusbtn2'),clearInput('set_medicine3', 'medicineDoze_Rx3', 'medicineUnite_Rx3', 'set_medicineUsage3', 'set_medicineDay3', 'set_medicineTime3', 'set_medicineAmount3')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine4" name="medicine_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_4" id="medicineDoze_Rx4" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx4" name="unit_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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

                    <select id="set_medicineUsage4" name="usageType_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_4" class="form-control arrowLessInput" id="set_medicineDay4">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_4" class="form-control arrowLessInput" id="set_medicineTime4">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_4" class="form-control arrowLessInput" id="set_medicineAmount4">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns4">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn4" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn4')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row4', 'plusbtn3'), clearInput('set_medicine4', 'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine5" name="medicine_5" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_5" id="medicineDoze_Rx5" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx5" name="unit_5" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_5" class="form-control arrowLessInput" id="set_medicineDay5">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_5" class="form-control arrowLessInput" id="set_medicineTime5">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_5" class="form-control arrowLessInput" id="set_medicineAmount5">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns5" type="button">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn5" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row6','plusbtn5')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row5', 'plusbtn4'), clearInput('set_medicine5', 'medicineDoze_Rx5', 'medicineUnite_Rx5', 'set_medicineUsage5', 'set_medicineDay5', 'set_medicineTime5', 'set_medicineAmount5')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine6" name="medicine_6" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_6" id="medicineDoze_Rx6" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx6" name="unit_6" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_6" class="form-control arrowLessInput" id="set_medicineDay6">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_6" class="form-control arrowLessInput" id="set_medicineTime6">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_6" class="form-control arrowLessInput" id="set_medicineAmount6">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns4">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn6" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row7', 'plusbtn6')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row6', 'plusbtn5'), clearInput('set_medicine6', 'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine7" name="medicine_7" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_7" id="medicineDoze_Rx7" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx7" name="unit_7" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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

                    <select id="set_medicineUsage7" name="usageType_1"
                      class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_7" class="form-control arrowLessInput" id="set_medicineDay7">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_7" class="form-control arrowLessInput" id="set_medicineTime7">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_7" class="form-control arrowLessInput" id="set_medicineAmount7">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn7" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row8', 'plusbtn7')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row7', 'plusbtn6'), clearInput('set_medicine7', 'medicineDoze_Rx7', 'medicineUnite_Rx7', 'set_medicineUsage7', 'set_medicineDay7', 'set_medicineTime7', 'set_medicineAmount7')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine8" name="medicine_8" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_8" id="medicineDoze_Rx8" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx8" name="unit_8" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_8" class="form-control arrowLessInput" id="set_medicineDay8">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_8" class="form-control arrowLessInput" id="set_medicineTime8">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_8" class="form-control arrowLessInput" id="set_medicineAmount8">

                  </div>
                </div>
                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns4">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn8" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row9', 'plusbtn8')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row8', 'plusbtn7'), clearInput('set_medicine8', 'medicineDoze_Rx8', 'medicineUnite_Rx8', 'set_medicineUsage8', 'set_medicineDay8', 'set_medicineTime8', 'set_medicineAmount8')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine9" name="medicine_9" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_9" id="medicineDoze_Rx9" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx9" name="unit_9" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_9" class="form-control arrowLessInput" id="set_medicineDay9">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_9" class="form-control arrowLessInput" id="set_medicineTime9">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_9" class="form-control arrowLessInput" id="set_medicineAmount9">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns4">
                    <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn9" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row10', 'plusbtn9')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div>
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row9', 'plusbtn8'), clearInput('set_medicine9', 'medicineDoze_Rx9', 'medicineUnite_Rx9', 'set_medicineUsage9', 'set_medicineDay9', 'set_medicineTime9', 'set_medicineAmount9')">
                        <div class="btn-txt">Remove</div>
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
                    <select id="set_medicine10" name="medicine_10" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_10" id="medicineDoze_Rx10" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx10" name="unit_10" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_10" class="form-control arrowLessInput" id="set_medicineDay10">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_10" class="form-control arrowLessInput" id="set_medicineTime10">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_10" class="form-control arrowLessInput" id="set_medicineAmount10">

                  </div>
                </div>

                <div class="col-sm-12 col-md-2">

                  <div class="plusRemovBtns" id="PRBtns4">
                    <!-- <div class="" style="text-align: center;margin-top:5%">
                      <button id="plusbtn10" class="icon-btn add-btn" type="button"
                        onclick="plusBtn('setMedicien_row5', 'plusbtn10')">
                        <div class="add-icon"></div>
                        <div class="btn-txt">Add</div>
                      </button>
                    </div> -->
                    <div class="" style="text-align: center; margin-top: 8px;">
                      <button class="icon-btn add-btn" type="button"
                        onclick="removeBtn('setMedicien_row10', 'plusbtn9'), clearInput('set_medicine10', 'medicineDoze_Rx10', 'medicineUnite_Rx10', 'set_medicineUsage10', 'set_medicineDay10', 'set_medicineTime10', 'set_medicineAmount10')">
                        <div class="btn-txt">Remove</div>
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
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-primary" onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
          <?= $ci->lang('save') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- prescription SetMedicines End -------------------------------------------------------------------------------------------------->

<!-- prescription ViewMedicines Start --------------------------------------------------------------------------------------------- -->
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
              <div class="row viewRows" id="prescription_row1"  style="display: none;">

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Name') ?><span class="text-red">*</span>
                    </label>
                    <!-- this is an important select tag remember it -->
                    <select id="view_medicine1" name="medicine_1" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_1" id="medicineDoze_Rx1_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx1_view" name="unit_1" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_1" class="form-control arrowLessInput" id="view_medicineDay1">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_1" class="form-control arrowLessInput" id="view_medicineTime1">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_1" class="form-control arrowLessInput" id="view_medicineAmount1">

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
                    <select id="view_medicine2" name="medicine_2" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_2" id="medicineDoze_Rx2_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx2_view" name="unit_2" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_2" class="form-control arrowLessInput" id="view_medicineDay2">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_2" class="form-control arrowLessInput" id="view_medicineTime2">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_2" class="form-control arrowLessInput" id="view_medicineAmount2">

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
                    <select id="view_medicine3" name="medicine_3" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_3" id="medicineDoze_Rx3_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx3_view" name="unit_3" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_3" class="form-control arrowLessInput" id="view_medicineDay3">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_3" class="form-control arrowLessInput" id="view_medicineTime3">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_3" class="form-control arrowLessInput" id="view_medicineAmount3">

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
                    <select id="view_medicine4" name="medicine_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx4', 'medicineUnite_Rx4', 'set_medicineUsage4', 'set_medicineDay4', 'set_medicineTime4', 'set_medicineAmount4')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_4" id="medicineDoze_Rx4_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx4_view" name="unit_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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

                    <select id="view_medicineUsage4" name="usageType_4" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">

                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_4" class="form-control arrowLessInput" id="view_medicineDay4">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_4" class="form-control arrowLessInput" id="view_medicineTime4">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_4" class="form-control arrowLessInput" id="view_medicineAmount4">

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
                    <select id="view_medicine5" name="medicine_5" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_5" id="medicineDoze_Rx5_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx5_view" name="unit_5" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_5" class="form-control arrowLessInput" id="view_medicineDay5">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_5" class="form-control arrowLessInput" id="view_medicineTime5">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_5" class="form-control arrowLessInput" id="view_medicineAmount5">

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
                    <select id="view_medicine6" name="medicine_6" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>"
                      onchange="getMedicienInfo(this.value,'medicineDoze_Rx6', 'medicineUnite_Rx6', 'set_medicineUsage6', 'set_medicineDay6', 'set_medicineTime6', 'set_medicineAmount6')">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_6" id="medicineDoze_Rx6_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx6_view" name="unit_6" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_6" class="form-control arrowLessInput" id="view_medicineDay6">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_6" class="form-control arrowLessInput" id="view_medicineTime6">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_6" class="form-control arrowLessInput" id="view_medicineAmount6">

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
                    <select id="view_medicine7" name="medicine_7" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_7" id="medicineDoze_Rx7_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx7_view" name="unit_7" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_7" class="form-control arrowLessInput" id="view_medicineDay7">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_7" class="form-control arrowLessInput" id="view_medicineTime7">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_7" class="form-control arrowLessInput" id="view_medicineAmount7">

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
                    <select id="view_medicine8" name="medicine_8" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_8" id="medicineDoze_Rx8_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx8_view" name="unit_8" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_8" class="form-control arrowLessInput" id="view_medicineDay8">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_8" class="form-control arrowLessInput" id="view_medicineTime8">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_8" class="form-control arrowLessInput" id="view_medicineAmount8">

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
                    <select id="view_medicine9" name="medicine_9" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_9" id="medicineDoze_Rx9_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx9_view" name="unit_9" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_9" class="form-control arrowLessInput" id="view_medicineDay9">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_9" class="form-control arrowLessInput" id="view_medicineTime9">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_9" class="form-control arrowLessInput" id="view_medicineAmount9">

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
                    <select id="view_medicine10" name="medicine_10" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($medicines as $medicine): ?>
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

                    <input type="number" name="doze_10" id="medicineDoze_Rx10_view" class="form-control arrowLessInput">
                  </div>
                </div>

                <div class="col-sm-12 col-md-2">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Medicine Unit') ?> <span class="text-red">*</span>
                    </label>

                    <select id="medicineUnite_Rx10_view" name="unit_10" class="form-control select2-show-search form-select"
                      data-placeholder="<?= $ci->lang('select') ?>">
                      <option label="<?= $ci->lang('select') ?>"></option>
                      <?php foreach ($ci->dentist->medicine_units() as $unit): ?>
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
                      <?php foreach ($ci->dentist->medicine_usage_type() as $type): ?>
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

                    <input type="number" name="day_10" class="form-control arrowLessInput" id="view_medicineDay10">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('Time') ?>
                    </label>

                    <input type="number" name="time_10" class="form-control arrowLessInput" id="view_medicineTime10">

                  </div>
                </div>

                <div class="col-sm-12 col-md-1">
                  <div class="form-group">
                    <label class="form-label">
                      <?= $ci->lang('amount') ?>
                    </label>

                    <input type="number" name="amount_10" class="form-control arrowLessInput" id="view_medicineAmount10">

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
        <button class="btn btn-primary" onclick="xhrSubmit('insertTooth', '<?= base_url() ?>admin/insert_account')">
          <?= $ci->lang('save') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- prescription ViewMedicines End -------------------------------------------------------------------------------------------------->



<script>
function viewPrescriptionsMedicines(id) {
  $.ajax({
    url: "<?= base_url('admin/single_prescription') ?>",
    type: 'POST',
    data: {
      slug: id
    },
    success: function(response) {
      let result = JSON.parse(response);
      let medicienDatas = result.content;
      
     // this part counts how many has to be shown-start
      var medicineCount = 0;

      for (var key in medicienDatas) {
        if (key.startsWith("medicine_")) {
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
    }else if(rownumber == 2){
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
    }else if(rownumber == 3){
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
    }else if(rownumber == 4){
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
    }else if(rownumber == 5){
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
    }else if(rownumber == 6){
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
    }else if(rownumber == 7){
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
    }else if(rownumber == 8){
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
    }else if(rownumber == 9){
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
    }else if(rownumber == 10){
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


























<script>
  function prescriptionToggle() {
    $(`#prescription`).modal('toggle');
  }
</script>

<script>
  function setMedicine() {
    $(`#setMedicines`).modal('toggle');
  }

  function plusBtn(rowId, plusbtnId) {
    $(`#${rowId}`).show();
    $(`#${plusbtnId}`).hide();
  }

  function removeBtn(rowId, plusbtnId) {
    $(`#${rowId}`).hide();
    $(`#${plusbtnId}`).show();
  }

  function clearInput(medicineId, dozeId, unitId, usageId, dayId, timeId, amountId) {
    $('#' + medicineId).val('').trigger('change');
    $('#' + dozeId).val('');
    $('#' + unitId).val('').trigger('change');
    $('#' + usageId).val('').trigger('change');
    $('#' + dayId).val('');
    $('#' + timeId).val('');
    $('#' + amountId).val('');
  }

  function getMedicienInfo(id, dozeId, unitId, usageId, dayId, timeId, amountId) {
    $.ajax({
      url: "<?= base_url('admin/single_medicine') ?>",
      type: 'POST',
      data: {
        slug: id
      },
      success: function (response) {
        let result = JSON.parse(response);
        let medicienDatas = result.content;
        $('#' + dozeId).val(medicienDatas.doze);
        $('#' + unitId).val(medicienDatas.unit).trigger('change');
        $('#' + usageId).val(medicienDatas.usageType).trigger('change');
        $('#' + dayId).val(medicienDatas.day);
        $('#' + timeId).val(medicienDatas.times);
        $('#' + amountId).val(medicienDatas.amount);
      }
    })

  }
</script>

<!-- ---------------------------------------------------------------- -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    jalaliDatepicker.startWatch();
  });
</script>

<script>
  function editModalToggle() {
    editData = {
      "type": "success",
      "content": {
        "slug": "3244",
        "lab": "1",
        "cteeth": [1, 2],
        "ttype": [1, 3],
        "ddate": "1402/10/12",
        "dtime": "8:00,9:00",
        "tcolor": "4",
        "payment": "12000",
        "details": "this is very long ass(Appologise) details",
      }
    }
    let datas = editData.content;

    $('#selectLab_edit').val(datas.lab).trigger('change');

    let teeth = datas.cteeth;
    var teethinnetHTML = document.getElementById("selectTeeth_edit").innerHTML;
    teeth.map((item) => {
      // console.log(item);
      teethinnetHTML = teethinnetHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
    })
    document.getElementById("selectTeeth_edit").innerHTML = teethinnetHTML;


    let types = datas.ttype;
    var typesinnetHTML = document.getElementById("selectToothType_edit").innerHTML;
    types.map((item) => {
      // console.log(item);
      typesinnetHTML = typesinnetHTML.replace(`<option value="${item}">`, `<option value="${item}" selected>`);
    })
    document.getElementById("selectToothType_edit").innerHTML = typesinnetHTML;


    $('#deliveryDate_edit').val(datas.ddate).trigger('change');
    $('#deliveryTime_edit').val(datas.dtime).trigger('change');
    $('#selectToothColor_edit').val(datas.tcolor).trigger('change');
    $('#payment_edit').val(datas.payment).trigger('change');
    $('#details_edit').val(datas.details).trigger('change');






    $(`#editModal`).modal('toggle');
  }
</script>
<!-- ________________________________________________________________ -->
