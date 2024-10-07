<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="row card-header">

        <form>
          <div class="row">
            
            <div class="col-md-2">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('From Date') ?>
                </label>
  
                <input data-jdp type="text" class="form-control" id="fromdate" placeholder="<?= $ci->lang('select') ?>"
                  name="from">
              </div>
            </div>
  
            <div class="col-md-2">
              <div class="form-group">
  
                <label class="form-label">
                  <?= $ci->lang('To Date') ?>
  
                </label>
  
                <input data-jdp type="text" class="form-control" id="todate" placeholder="<?= $ci->lang('select') ?>"
                  name="to">
  
              </div>
            </div>
  
            <div class="col-md-3" class="form-control select2-show-search form-select">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('account') ?>
                </label>
                <select id="receiptName" name="customers_id" class="form-control select2-show-search form-select"
                  data-placeholder="<?= $ci->lang('select') ?>">
                  <option value="0"><?= $ci->lang('none') ?></option>
                  <?php foreach ($accounts as $account): ?>
                    <option value="<?= $account['id'] ?>">
                      <?= $ci->mylibrary->finance_account_name($account['name'], $account['lname'], $account['type']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
  
  
            <div class="col-md-2" style="margin-top: 36px;">
  
              <button class="btn btn-primary-gradient btn-wave custom-btn" type="button" onclick="finnancialReport()">
                <?= $ci->lang('report') ?> <i class="las la-chart-pie"></i>
              </button>
            </div>
  
  
            <div class="col-md-2" style="margin-top: 36px;">
  
              <button type="submit" class="btn btn-success-gradient btn-wave custom-btn">
                <?= $ci->lang('print') ?> <i class="las la-print"></i>
            </div>

          </div>


        </form>
      </div>



      <div class="card-body">

        <div class="tab-menu-heading border-0 p-0">
          <div class="tabs-menu1">
          </div>
        </div>
        <div class="table-responsive scrollable" id="financialReport">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<script>
  function finnancialReport() {

    const fromDate = $('#fromdate').val();
    const toDate = $('#todate').val();
    const receiptName = $('#receiptName').val();



    $.ajax({
      url: "<?= base_url('admin/report_ajax_receipt') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
        customers_id: receiptName,
      },
      success: function (response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['receipts'].length < 1) {
            var querytable = ``;
          } else {
            let balance_crStyle = result.content.balance < 0 ? 'text-danger' : '';
            var querytable = `
            <table class="table text-nowrap text-center">

            <tbody>
            
            <tr style="background-color: transparent ;">
              <td></td>
              <td></td>
              <td></td>
              <td> <span><?= $ci->lang('Sum Deposit') ?>:</span> ${result.content.sum_cr}</td>
              <td> <span><?= $ci->lang('Sum Withdraw') ?>:</span>  ${result.content.sum_dr}</td>
              <td> <span><?= $ci->lang('Balance') ?>:</span> <span dir="ltr" class="${balance_crStyle}">${result.content.balance}</span></td>
            </tr>
            </tbody>
            </table>
            
            `;


            querytable += `
            <table class="table text-nowrap text-center">
            <thead class="tableHead">

              <tr>
                <th scope="col">#</th>
                <th scope="col">
                  <?= $ci->lang('date') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('account') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('cr') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('dr') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('desc') ?>
                </th>
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
              <td>${item.account_name}</td>
              <td>${((item.cr == null) ? '0' : item.cr)}</td>
              <td>${((item.dr == null) ? '0' : item.dr)}</td>
              <td>${item.remarks}</td>
            </tr>
          `;
              counter++;
            });

            querytable += `
            <tr style="background-color: transparent ;">
              <td><?= $ci->lang('Balance') ?></td>
              <td></td>
              <td></td>
              <td> ${result.content.sum_cr}</td>
              <td>${result.content.sum_dr}</td>
              <td><span dir="ltr" class="${balance_crStyle}">${result.content.balance}</span></td>
            </tr>
            `


            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("financialReport").innerHTML = querytable;

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
  document.addEventListener("DOMContentLoaded", function () {
    // jalaliDatepicker.startWatch();
    jalaliDatepicker.startWatch();
  });
</script>