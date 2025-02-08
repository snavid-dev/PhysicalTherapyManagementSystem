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

        <div class="tab-menu-heading border-0 p-0">
          <div class="tabs-menu1">

            <ul class="nav panel-tabs product-sale" role="tablist">

              <li>
                <input type="text" class="form-control" placeholder="<?= $ci->lang('select') ?>" data-jdp id="fromDate">
              </li>

              <li>
                <input type="text" class="form-control" placeholder="<?= $ci->lang('select') ?>" data-jdp id="toDate">
              </li>

              <li>
                <button class="btn btn-primary" onclick="tableUpdate()">
                  <?= $ci->lang('Submit') ?> <i class="fa fa-share"></i> </i>
                </button>
              </li>



              <li>
				  <?php if ($ci->auth->has_permission('Create Expenses')): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#extralargemodal">
                  <?= $ci->lang('add new') ?> <i class="fa fa-plus"></i>
                </button>
				  <?php endif; ?>
              </li>

            </ul>
            <!-- Modal Button -->
            <!-- ReceiptsModal -->
			  <?php if ($ci->auth->has_permission('Create Expenses')): ?>
            <div class="modal fade effect-scale" id="extralargemodal" role="dialog">
              <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">
                      <?= $ci->lang('insert receipt') ?>
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="insertAccount">
                      <div class="row">



                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label class="form-label">
                              <?= $ci->lang('account') ?> <span class="text-red">*</span>
                            </label>
                            <select name="customers_id" class="form-control select2-show-search form-select" data-placeholder="<?= $ci->lang('select') ?>">
                              <option label="<?= $ci->lang('select') ?>"></option>
                              <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account['id'] ?>">
                                  <?= $ci->mylibrary->finance_account_name($account['name'], $account['lname'], $account['type']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label class="form-label">
                              <?= $ci->lang('type') ?>
                            </label>
                            <select name="type" id="" class="form-control form-select">
                              <option label="<?= $ci->lang('select') ?>"></option>
                              <option value="cr">
                                <?= $ci->lang('cr') ?>
                              </option>
                              <option value="dr">
                                <?= $ci->lang('dr') ?>
                              </option>
                            </select>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label class="form-label">
                              <?= $ci->lang('amount') ?>
                            </label>
                            <input type="number" name="amount" class="form-control" placeholder="<?= $ci->lang('amount') ?>">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group jdp" id="main-div">
                            <label class="form-label">
                              <?= $ci->lang('date') ?> <span class="text-red">*</span>
                            </label>
                            <!-- <input id="test-date-id-date" data-jdp type="text" name="phone" class="form-control" placeholder="<?= $ci->lang('amount') ?>" autocomplete="off"> -->
                            <input data-jdp type="text" id="test-date-id-date" name="shamsi" value="<?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?>" class="form-control" placeholder="<?= $ci->lang('date') ?>">
                            <div></div>
                          </div>
                        </div>


                        <div class="col-sm-12 col-md-12 ">
                          <div class="form-group">
                            <label class="form-label">
                              <?= $ci->lang('description') ?> <span class="text-red"></span>
                            </label>
                            <!-- <input id="test-date-id" type="text" name="phone" class="form-control" placeholder="<?= $ci->lang('amount') ?>"> -->
                            <textarea class="form-control" name="remarks" placeholder="<?= $ci->lang('description') ?>"></textarea>
                          </div>
                        </div>

                      </div>
                    </form>

                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                      <?= $ci->lang('cancel') ?> <i class="fa fa-close" ></i>
                    </button>
                    <button class="btn btn-primary" onclick="xhrSubmit('insertAccount', '<?= base_url() ?>admin/insert_receipt')">
                      <?= $ci->lang('save') ?> <i class="fa fa-plus" ></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
			  <?php endif; ?>
            <!-- ReceiptsModal End -->
          </div>
        </div>



        <div class="table-responsive">
          <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
            <thead>
              <tr>
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0">
                  <?= $ci->lang('account') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('type') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('amount') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('date') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('user') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('desc') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('actions') ?>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($receipts as $receipt) : ?>
                <tr id="<?= $receipt['id'] ?>">
                  <td>
                    <?= $i ?>
                  </td>
                  <td>
                    <?= $ci->mylibrary->finance_account_name($receipt['name'], $receipt['lname'], $receipt['type']) ?>
                  </td>
                  <td>
                    <?= $ci->mylibrary->check_cr_dr($receipt['cr'], $receipt['dr']) ?>
                  </td>
                  <td class="english">
                    <?= $ci->mylibrary->check_cr_dr($receipt['cr'], $receipt['dr'], 'amount') ?>
                  </td>
                  <td>
                    <?= $receipt['shamsi'] ?>
                  </td>
                  <td>
                    <?= $ci->mylibrary->account_name($receipt['firstname'], $receipt['lastname'], $receipt['role']) ?>
                  </td>
                  <td>
                    <?= $receipt['remarks'] ?>
                  </td>
                  <td>
                    <div class="g-2">
                      <a href="javascript:edit_receipt('<?= $receipt['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit"></span></a>
                      <a href="javascript:delete_via_alert('<?= $receipt['id'] ?>', '<?= base_url() ?>admin/delete_receipt')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash"></span></a>
                    </div>
                  </td>
                </tr>
              <?php $i++;
              endforeach; ?>
            </tbody>
          </table>
        </div>




      </div>
    </div>
  </div>
</div>
<!-- End Row -->



<!-- Modal -->
<div class="modal fade effect-scale" id="editModal" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <?= $ci->lang('update receipt') ?>
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateReceipt">
          <div class="row">



            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('account') ?> <span class="text-red">*</span>
                </label>
                <select name="customers_id" class="form-control select2-show-search form-select" id="customers_id" data-placeholder="<?= $ci->lang('select') ?>">
                  <option label="<?= $ci->lang('select') ?>"></option>
                  <?php foreach ($accounts as $account) : ?>
                    <option value="<?= $account['id'] ?>">
                      <?= $ci->mylibrary->finance_account_name($account['name'], $account['lname'], $account['type']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('type') ?>
                </label>
                <select name="type" id="type" class="form-control form-select">
                  <option label="<?= $ci->lang('select') ?>"></option>
                  <option value="cr">
                    <?= $ci->lang('cr') ?>
                  </option>
                  <option value="dr">
                    <?= $ci->lang('dr') ?>
                  </option>
                </select>
              </div>
            </div>

            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('amount') ?>
                </label>
                <input type="hidden" id="slug" name="slug">
                <input type="number" id="amount" name="amount" class="form-control" placeholder="<?= $ci->lang('amount') ?>">
              </div>
            </div>

            <div class="col-sm-12 col-md-3">
              <div class="form-group jdp" id="update-date-div">
                <label class="form-label">
                  <?= $ci->lang('date') ?> <span class="text-red">*</span>
                </label>
                <input data-jdp type="text" id="update_date" name="shamsi" value="" class="form-control" placeholder="<?= $ci->lang('date') ?>">
                <div></div>
              </div>
            </div>


            <div class="col-sm-12 col-md-12 ">
              <div class="form-group">
                <label class="form-label">
                  <?= $ci->lang('description') ?> <span class="text-red"></span>
                </label>
                <!-- <input id="test-date-id" type="text" name="phone" class="form-control" placeholder="<?= $ci->lang('amount') ?>"> -->
                <textarea class="form-control" name="remarks" id="remarks" placeholder="<?= $ci->lang('description') ?>"></textarea>
              </div>
            </div>

          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-primary" onclick="xhrUpdate('updateReceipt', '<?= base_url() ?>admin/update_receipt')">
          <?= $ci->lang('update') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End -->



<script>
  function tableUpdate() {
    const fromDate = $('#fromDate').val();
    const toDate = $('#toDate').val();

    console.log(`${fromDate} ${toDate}`);

    $.ajax({
      url: "<?= base_url('admin/report_ajax_receipt') ?>",
      type: 'POST',
      data: {
        from: fromDate,
        to: toDate,
      },
      success: function(response) {
        let result = JSON.parse(response);
        var table = $('#file-datatable').DataTable();
        table.rows().remove();
        if (result.content.receipts.length > 0) {
          result.content.receipts.map((item) => {
            let crOrDr = item.cr !== null ? item.cr : item.dr !== null ? item.dr : '';
            let row = table.row.add([
              item.id,
              item.account_name,
              item.type,
              crOrDr,
              item.date,
              item.user_name,
              item.remarks,
              `<div class="g-2">
            <a href="javascript:edit_receipt('${item.id}')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                                  <a href="javascript:delete_via_alert('${item.id}', '<?= base_url() ?>admin/delete_receipt')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
            </div>`
            ]).node();
            row.id = item.id;
          });
        }

        table.draw(false);
      }
    });



  }
</script>

<!-- <script>
  function tableFilter(date) {
    $.ajax({
      url: "<?= base_url('admin/list_turns_json') ?>",
      type: 'POST',
      data: {
        date: date,
      },
      success: function(response) {
        let result = JSON.parse(response);
        var table = $('#file-datatable').DataTable();
        table.rows().remove();
        if (result.content.length > 0) {
          result.content.map((item) => {
            let row = table.row.add([
              item.number,
              item.patient_name,
              item.doctor_name,
              item.date,
              item.time,
              ` <div class="g-2">
              <a href="<?= base_url() ?>admin/single_patient/${item.patient_id}"
                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-user-circle-o fs-14"></span></a>
                      <a href="javascript:accept_via_alert('${item.id}', '<?= base_url() ?>admin/accept_turn')"
                        class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-check-circle"></span></a>
                      <a href="javascript:print_turn('${item.id}', '<?= base_url() ?>admin/delete_turn')"
                        class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-print"></span></a>
                      <a href="javascript:turnPayment('${item.id}')"
                        class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
                          class="fa fa-money fs-14"></span></a>
                      </div>`
            ]).node();
            row.id = item.id;
          });
        }

        table.draw(false);
      }
    });
  }
</script> -->

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // jalaliDatepicker.startWatch();
    jalaliDatepicker.startWatch();
  });
</script>









<script>
  let type = document.getElementById('type').innerHTML;
  let customers_id = document.getElementById('customers_id').innerHTML;

  function edit_receipt(id) {
    $.ajax({
      url: "<?= base_url('admin/single_receipt') ?>",
      type: 'POST',
      data: {
        slug: id
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          $('#slug').val(result['content']['slug']);
          $('#amount').val(result['content']['amount']);
          $('#update_date').val(result['content']['shamsi']);
          $('#remarks').val(result['content']['remarks']);

          let type_new = type;
          type_new = type_new.replace(`<option value="${result['content']['type']}">`, `<option value="${result['content']['type']}" selected>`);
          $("#type").html(type_new);

          let customer_new = customers_id;
          customer_new = customer_new.replace(`<option value="${result['content']['customers_id']}">`, `<option value="${result['content']['customers_id']}" selected>`);
          $("#customers_id").html(customer_new);

          $('#editModal').modal('toggle');
        } else if (result['type'] == 'error') {
          $.growl.error1({
            title: field['alert']['title'],
            message: field['alert']['text']
          });
        }
      }
    })
  }
</script>
