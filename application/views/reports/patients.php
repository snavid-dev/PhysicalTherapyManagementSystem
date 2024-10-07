<?php $ci = get_instance(); ?>
<div class="row">
  <div class="col-12 col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0"><?= $ci->lang('report patients') ?>:</h3>
      </div>
      <div class="card-body pt-4">
        <div class="grid-margin">
          <div class="">
            <div class="panel panel-primary">
              <div class="tab-menu-heading border-0 p-0">
                <div class="tabs-menu1">
                  <!-- Tabs -->
                  <ul class="nav panel-tabs product-sale" role="tablist">
                    <li><a href="#tab5" class="active" data-bs-toggle="tab" aria-selected="true" role="tab"><?= $ci->lang('paid') ?></a></li>
                    <li><a href="#tab6" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('Revenue') ?></a></li>
                    <li><a href="#tab7" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('Expenses') ?></a></li>
                    <li><a href="#tab8" data-bs-toggle="tab" class="text-dark" aria-selected="false" role="tab" tabindex="-1"><?= $ci->lang('Balance') ?></a></li>
                  </ul>
                </div>
              </div>
              <div class="panel-body tabs-menu-body border-0 pt-0">
                <div class="tab-content">
                  <div class="tab-pane active show" id="tab5" role="tabpanel">
                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('From Date') ?>
                          </label>

                          <input data-jdp type="text" class="form-control" id="paidfromdate" placeholder="<?= $ci->lang('select') ?>" name="from">
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">

                          <label class="form-label">
                            <?= $ci->lang('To Date') ?>

                          </label>

                          <input data-jdp type="text" class="form-control" id="paidtodate" placeholder="<?= $ci->lang('select') ?>" name="to">

                        </div>
                      </div>

                      <div class="col-md-4" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('Patient Name and Surname') ?>
                          </label>
                          <select id="paidcustomers" name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option label="<?= $ci->lang('select') ?>"></option>
                            <option value="0"><?= $ci->lang('none') ?></option>
                            <?php foreach ($patients as $patient) : ?>
                              <option value="<?= $patient['id'] ?>">
                                <?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button class="btn btn-primary-gradient btn-wave custom-btn" type="button" onclick="finnancialReport_paid()">
                          <?= $ci->lang('report') ?> <i class="las la-chart-pie"></i>
                        </button>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button type="submit" class="btn btn-success-gradient btn-wave custom-btn">
                          <?= $ci->lang('print') ?> <i class="las la-print"></i>
                      </div>

                    </div>


                    <div class="table-responsive" id="paidTable">

                    </div>
                  </div>

                  <div class="tab-pane" id="tab6" role="tabpanel">
                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('From Date') ?>
                          </label>

                          <input data-jdp type="text" class="form-control" id="revenuefromdate" placeholder="<?= $ci->lang('select') ?>" name="from">
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">

                          <label class="form-label">
                            <?= $ci->lang('To Date') ?>

                          </label>

                          <input data-jdp type="text" class="form-control" id="revenuetodate" placeholder="<?= $ci->lang('select') ?>" name="to">

                        </div>
                      </div>

                      <div class="col-md-4" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('Patient Name and Surname') ?>
                          </label>
                          <select id="revenuecustomers" name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option label="<?= $ci->lang('select') ?>"></option>
                            <option value="0"><?= $ci->lang('none') ?></option>
                            <?php foreach ($patients as $patient) : ?>
                              <option value="<?= $patient['id'] ?>">
                                <?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button class="btn btn-primary-gradient btn-wave custom-btn" type="button" onclick="finnancialReport_revenue()">
                          <?= $ci->lang('report') ?> <i class="las la-chart-pie"></i>
                        </button>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button type="submit" class="btn btn-success-gradient btn-wave custom-btn">
                          <?= $ci->lang('print') ?> <i class="las la-print"></i>
                      </div>

                    </div>
                    <div class="table-responsive" id="revenueTable">

                    </div>
                  </div>


                  <div class="tab-pane" id="tab7" role="tabpanel">

                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('From Date') ?>
                          </label>

                          <input data-jdp type="text" class="form-control" id="expensesfromdate" placeholder="<?= $ci->lang('select') ?>" name="from">
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">

                          <label class="form-label">
                            <?= $ci->lang('To Date') ?>

                          </label>

                          <input data-jdp type="text" class="form-control" id="expensestodate" placeholder="<?= $ci->lang('select') ?>" name="to">

                        </div>
                      </div>

                      <div class="col-md-2" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('laboratory') ?>
                          </label>
                          <select id="expenseslab" name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option label="<?= $ci->lang('select') ?>"></option>
                            <option value="0"><?= $ci->lang('none') ?></option>
                            <?php foreach ($lab_accounts as $lab_account) : ?>
                              <option value="<?= $lab_account['id'] ?>"><?= $ci->mylibrary->account_name($lab_account['name'], $lab_account['lname']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('Patient Name and Surname') ?>
                          </label>
                          <select id="expensescustomers" name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option label="<?= $ci->lang('select') ?>"></option>
                            <option value="0"><?= $ci->lang('none') ?></option>
                            <?php foreach ($patients as $patient) : ?>
                              <option value="<?= $patient['id'] ?>">
                                <?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2" style="margin-top: 36px;">

                        <button class="btn btn-primary-gradient btn-wave custom-btn" type="button" onclick="finnancialReport_expenses()">
                          <?= $ci->lang('report') ?> <i class="las la-chart-pie"></i>
                        </button>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button type="submit" class="btn btn-success-gradient btn-wave custom-btn">
                          <?= $ci->lang('print') ?> <i class="las la-print"></i>
                      </div>

                    </div>

                    <div class="table-responsive" id="exexpensesTable">
                    </div>
                  </div>

                  <div class="tab-pane" id="tab8" role="tabpanel">
                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('From Date') ?>
                          </label>

                          <input data-jdp type="text" class="form-control" id="balancefromdate" placeholder="<?= $ci->lang('select') ?>" name="from">
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">

                          <label class="form-label">
                            <?= $ci->lang('To Date') ?>

                          </label>

                          <input data-jdp type="text" class="form-control" id="balancetodate" placeholder="<?= $ci->lang('select') ?>" name="to">

                        </div>
                      </div>

                      <div class="col-md-2" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('Patient Name and Surname') ?>
                          </label>
                          <select id="balancecustomers" name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option label="<?= $ci->lang('select') ?>"></option>
                            <option value="0"><?= $ci->lang('none') ?></option>
                            <?php foreach ($patients as $patient) : ?>
                              <option value="<?= $patient['id'] ?>">
                                <?= $ci->mylibrary->get_patient_name($patient['name'], $patient['lname'], $patient['serial_id'], $patient['gender']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2" class="form-control select2-show-search form-select">
                        <div class="form-group">
                          <label class="form-label">
                            <?= $ci->lang('status') ?>
                          </label>
                          <select id="balancestatus" name="status" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                            <option value="0"><?= $ci->lang('all') ?></option>
                            <option value="p" selected><?= $ci->lang('pending') ?></option>
                            <option value="a"><?= $ci->lang('accepted') ?></option>
                            <option value="b"><?= $ci->lang('blocked') ?></option>

                          </select>
                        </div>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button class="btn btn-primary-gradient btn-wave custom-btn" type="button" onclick="finnancialReport_balance()">
                          <?= $ci->lang('report') ?> <i class="las la-chart-pie"></i>
                        </button>
                      </div>


                      <div class="col-md-2" style="margin-top: 36px;">

                        <button type="submit" class="btn btn-success-gradient btn-wave custom-btn">
                          <?= $ci->lang('print') ?> <i class="las la-print"></i>
                      </div>

                    </div>


                    <div class="table-responsive" id="balanceTable">

                    </div>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<script>
  function finnancialReport_paid() {

    const fromDate = $('#paidfromdate').val();
    const toDate = $('#paidtodate').val();
    const patientName = $('#paidcustomers').val();



    $.ajax({
      url: "<?= base_url('admin/report_ajax_patients_paid') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
        patient_id: patientName,
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['receipts'].length < 1) {
            var querytable = ``;
          } else {

            var querytable = `
           <table id="paid_datatable" class="table table-bordered text-nowrap mb-0">
                        <thead class="border-top">
                          <tr class="tableHead">
                            <th class="bg-transparent border-bottom-0">
                              #</th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('date') ?> </th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Patient Name and Surname') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              Id</th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('paid amount') ?> </th>
                          </tr>
                        </thead>
                        <tbody>
        `;

            let counter = 1;
            result["content"]["receipts"].map((item) => {
              querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.date}</td>
              <td>${item.patient_name}</td>
              <td>${item.serial_id}</td>
              <td>${item.cr}</td>
            </tr>
          `;
              counter++;
            });

            querytable += `
            
            <tr class="borderlessCell" style="background-color: #00000061 ;">
            <td></td>
            <td></td>
            <td></td>
            <td><?= $ci->lang('Balance') ?></td>
              <td>${result.content.sum_cr}</td>

            </tr>
            `


            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("paidTable").innerHTML = querytable;

        } else if (result['type'] == 'error') {
          $.growl.error1({
            title: field['alert']['title'],
            message: field['alert']['text']
          });
        }
      }
    });


  }
</script>


<script>
  function finnancialReport_revenue() {

    const fromDate = $('#revenuefromdate').val();
    const toDate = $('#revenuetodate').val();
    const patientName = $('#revenuecustomers').val();



    $.ajax({
      url: "<?= base_url('admin/report_ajax_patients_income') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
        patient_id: patientName,
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['receipts'].length < 1) {
            var querytable = ``;
          } else {


            var querytable = `
            <table id="revenue_datatable" class="table table-bordered text-nowrap mb-0">
                        <thead class="border-top">
                          <tr class="tableHead">
                            <th class="bg-transparent border-bottom-0">
                              #</th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('date') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Patient Name and Surname') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Tooth Position and Name') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('services') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Sum Deposit') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('description') ?></th>

                          </tr>
                        </thead>
                        <tbody>
        `;

            let counter = 1;
            result["content"]["receipts"].map((item) => {
              querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.date}</td>
              <td>${item.patient_name}</td>
              <td>${item.tooth_name}</td>
              <td>${item.services}</td>
              <td>${item.cr}</td>
              <td>${item.remarks}</td>
            </tr>
          `;
              counter++;
            });

            querytable += `
            
            <tr class="borderlessCell" style="background-color: #00000061 ;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $ci->lang('Balance') ?></td>
            <td>${result.content.sum_cr}</td>
            <td></td>

            </tr>
            `


            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("revenueTable").innerHTML = querytable;

        } else if (result['type'] == 'error') {
          $.growl.error1({
            title: field['alert']['title'],
            message: field['alert']['text']
          });
        }
      }
    });


  }
</script>


<script>
  function finnancialReport_expenses() {

    const fromDate = $('#expensesfromdate').val();
    const toDate = $('#expensestodate').val();
    const lab_ID = $('#expenseslab').val();
    const patientName = $('#expensescustomers').val();



    $.ajax({
      url: "<?= base_url('admin/report_ajax_psatients_expenses') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
        lab_id: lab_ID,
        patient_id: patientName,
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['receipts'].length < 1) {
            var querytable = ``;
          } else {


            var querytable = `
            <table id="expenses_datatable" class="table table-bordered text-nowrap mb-0">
                        <thead class="border-top">
                          <tr class="tableHead">
                            <th class="bg-transparent border-bottom-0">
                              #</th>
                              <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Patient Name and Surname') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('laboratory') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('teeth') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('tooth type') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('delivery date') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Sum Deposit') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('description') ?></th>

                          </tr>
                        </thead>
                        <tbody>
        `;

            let counter = 1;
            result["content"]["receipts"].map((item) => {
              querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.patient_name}</td>
              <td>${item.laboratory}</td>
              <td>${item.teeth}</td>
              <td>${item.tooth_type}</td>
              <td>${item.delivery_date}</td>
              <td>${item.dr}</td>
              <td>${item.remarks}</td>
            </tr>
          `;
              counter++;
            });

            querytable += `
            
            <tr class="borderlessCell" style="background-color: #00000061 ;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $ci->lang('Balance') ?></td>
            <td>${result.content.sum_dr}</td>
            <td></td>

            </tr>
            `


            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("exexpensesTable").innerHTML = querytable;

        } else if (result['type'] == 'error') {
          $.growl.error1({
            title: field['alert']['title'],
            message: field['alert']['text']
          });
        }
      }
    });


  }
</script>



<script>
  function finnancialReport_balance() {

    const fromDate = $('#balancefromdate').val();
    const toDate = $('#balancetodate').val();
    const status = $('#balancestatus').val();
    const patientName = $('#balancecustomers').val();



    $.ajax({
      url: "<?= base_url('admin/report_ajax_psatients_balance') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
        status: status,
        patient_id: patientName,
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['receipts'].length < 1) {
            var querytable = ``;
          } else {

            let balance_crStyle = result.content.balance < 0 ? 'text-danger' : '';
            var querytable =`
            <table class="table text-nowrap text-center">

            <tbody>
            
            <tr class="borderlessCell" style="background-color: #00000061 ;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $ci->lang('cr') ?> : ${result.content.sum_cr}</td>
            <td><?= $ci->lang('dr') ?> :${result.content.sum_dr}</td>
            <td><span dir="ltr" class="${balance_crStyle}"><?= $ci->lang('balance') ?> : ${result.content.balance}</span></td>

            </tr>
            </tbody>
            </table>
            `


             querytable += `
            <table id="expenses_datatable" class="table table-bordered text-nowrap mb-0">
                        <thead class="border-top">
                          <tr class="tableHead">
                            <th class="bg-transparent border-bottom-0">
                              #</th>
                              <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('Patient Name and Surname') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('phone') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('date and time') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('status') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('desc') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('cr') ?></th>
                            <th class="bg-transparent border-bottom-0">
                              <?= $ci->lang('dr') ?></th>
                            <th class="bg-transparent border-bottom-0">
                            <?= $ci->lang('Balance') ?></th>

                          </tr>
                        </thead>
                        <tbody>
        `;

            let counter = 1;
            result["content"]["receipts"].map((item) => {
            let balance_crStyle_item = item.balance < 0 ? 'text-danger' : '';
              querytable += `
            <tr class="tableRow">
              <td>${counter}</td>
              <td>${item.patient_name}</td>
              <td>${item.phone}</td>
              <td>${item.dateTime}</td>
              <td>${item.status}</td>
              <td>${item.remarks}</td>
              <td>${item.cr}</td>
              <td>${item.dr}</td>
              <td> <span dir="ltr" class="${balance_crStyle_item}">${item.balance}</span> </td>
            </tr>
          `;
              counter++;
            });

            querytable += `
            
            <tr class="borderlessCell" style="background-color: #00000061 ;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $ci->lang('Balance') ?></td>
            <td>${result.content.sum_cr}</td>
            <td>${result.content.sum_dr}</td>
            <td><span dir="ltr" class="${balance_crStyle}">${result.content.balance}</span></td>

            </tr>
            `


            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("balanceTable").innerHTML = querytable;

        } else if (result['type'] == 'error') {
          $.growl.error1({
            title: field['alert']['title'],
            message: field['alert']['text']
          });
        }
      }
    });


  }
</script>



<script>
  document.addEventListener("DOMContentLoaded", function() {
    // jalaliDatepicker.startWatch();
    jalaliDatepicker.startWatch();
  });
</script>