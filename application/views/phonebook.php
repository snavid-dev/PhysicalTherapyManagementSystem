<?php $ci = get_instance(); ?>
<!-- Row -->
<!-- TODO remove extra buttons  -->
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0">
          <?= $ci->lang('phonebook') ?>
        </h3>
      </div>
      <div class="card-body">

        <div class="tab-menu-heading border-0 p-0">
          <div class="tabs-menu1">

            <ul class="nav panel-tabs product-sale" role="tablist">

              <li style="position: relative; top:9px">
                <h6>
                  <?= $ci->lang('date') ?>:
                </h6>
              </li>

              <li style="width: 150px;">
                <input data-jdp type="text" class="form-control" id="datePicker" onchange="tableFilterPhoneBook(this.value)" placeholder="<?= $ci->lang('date') ?>">
              </li>

              <!-- <li>
                <button class="btn btn-success" onclick="sendSms()">
                  <?= $ci->lang('sms') ?> <i class="fa fa-sms" style="font-size: 15px;"></i>
                </button>
              </li> -->


            </ul>
            <!-- Modal Button -->


            <!-- Modal Call -->
            <div class="modal fade effect-scale" id="callmodal" role="dialog">
              <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">
                      <!-- change language -->
                      <?= $ci->lang('Failed Call Info') ?>
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="failedCallForm">
                      <div class="row">

                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('Call Failure Reason') ?></label>
                            <textarea type="text" name="remarks" id="callDetails1" class="form-control" rows="4" placeholder="<?= $ci->lang('desc') ?>"></textarea>
                          </div>
                        </div>
                        <input id="hiddencall1" type="hidden" name="type" class="form-control">
                        <input id="hiddenId" type="hidden" name="slug" class="form-control">

                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                      <?= $ci->lang('cancel') ?>
                    </button>
                    <button class="btn btn-primary" onclick="xhrUpdate('failedCallForm', '<?= base_url() ?>admin/failedCall', 'callmodal')">
                      <?= $ci->lang('Submit') ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal Call End -->

            <!-- Modal Call eye  -->
            <div class="modal fade effect-scale" id="eyemodal" role="dialog">
              <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">
                      <!-- change language -->
                      <!-- <?= $ci->lang('Failed Call Info') ?> -->
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="failedCallForm">
                      <div class="row">

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label">Date</label>
                            <input type="text" name="eyedate" id="eyedate" class="form-control">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label">Hour</label>
                            <input type="text" name="eyehour" id="eyehour" class="form-control">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('Call Failure Reason') ?></label>
                            <textarea type="text" name="remarks" id="eyeremark" class="form-control" rows="4" placeholder="<?= $ci->lang('desc') ?>"></textarea>
                          </div>
                        </div>

                      </div>
                    </form>

                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                      <?= $ci->lang('cancel') ?>
                    </button>
                    <button class="btn btn-primary" onclick="xhrUpdate('failedCallForm', '<?= base_url() ?>admin/failedCall', 'callmodal')">
                      <?= $ci->lang('Submit') ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal Call eye  -->


          </div>
        </div>

        <div class="table-responsive">
          <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
            <thead>
              <tr>
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0">
                  <?= $ci->lang('patient name') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('reference doctor') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('date') ?>
                </th>
                <th class="border-bottom-0">
                  <?= $ci->lang('hour') ?>
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
                    <?= $ci->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], $receipt['serial_id'], $receipt['gender']) ?>
                  </td>
                  <td>
                    <?= $receipt['doctor_name'] ?>
                  </td>
                  <td class="english">
                    <?= $receipt['date'] ?>
                  </td>
                  <td>
                    <bdo direction="ltr"><?= $receipt['from_time'] ?> - <?= $receipt['to_time'] ?></bdo>
                  </td>
                  <td>
                    <div class="g-2">
                      <a href="<?= base_url() ?>admin/single_patient/<?= $receipt['patient_id'] ?>" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-user-circle-o fs-14"></span></a>
                      <!-- <a href="javascript:accept_via_alert('<?= $receipt['id'] ?>', '<?= base_url() ?>admin/accept_turn');alert('navid')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-check-circle"></span></a> -->
                      <!-- <a href="javascript:turnPayment('<?= $receipt['id'] ?>')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-money fs-14"></span></a> -->


                      <?php if (empty($receipt['date_phone1'])) { ?>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call1')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-phone fs-14"></span></a>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call2')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light disabled"><span class="fa fa-phone fs-14"></span></a>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call3')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light disabled"><span class="fa fa-phone fs-14"></span></a>

                      <?php } elseif (empty($receipt['date_phone2'])) { ?>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call1')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call2')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-phone fs-14"></span></a>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call3')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light disabled"><span class="fa fa-phone fs-14"></span></a>
                      <?php } elseif (empty($receipt['date_phone2'])) { ?>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call1')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call2')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>
                        <a href="javascript:callModal('<?= $receipt['id'] ?>', 'call3')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-phone fs-14"></span></a>
                      <?php } else { ?>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call1')" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call2')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>
                        <a href="javascript:eye('<?= $receipt['id'] ?>', 'call3')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-eye fs-14"></span></a>

                      <?php } ?>
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

<!-- Modal pay turn -->
<div class="modal fade effect-scale" id="paymentModal_turns" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <?= $ci->lang('insert payment') ?>
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <form id="insertPayment">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label class="form-label">
                    <?= $ci->lang('amount') ?> <span class="text-red">*</span>
                  </label>
                  <input type="hidden" class="form-control" id="turnsPaymentInfo" name="slug">
                  <input type="number" name="cr" class="form-control" placeholder="<?= $ci->lang('amount') ?>" id="" autocomplete="off">
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <?= $ci->lang('cancel') ?>
        </button>
        <button class="btn btn-warning" onclick="update_and_delete('insertPayment', '<?= base_url() ?>admin/pay_turn', 'paymentModal_turns', print_payment, 'print');">
          <?= $ci->lang('save and print') ?> <i class="fa fa-print"></i>
        </button>
        <button class="btn btn-primary" onclick="update_and_delete('insertPayment', '<?= base_url() ?>admin/pay_turn','paymentModal_turns')">
          <?= $ci->lang('save') ?> <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End -->

<script>
  function turnPayment(id) {

    $(`#turnsPaymentInfo`).val(id);


    $(`#paymentModal_turns`).modal('toggle');
  }
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // jalaliDatepicker.startWatch();
    jalaliDatepicker.startWatch();
  });
</script>


<script>
  function edit_account(id) {

    $.ajax({
      url: "<?= base_url('admin/single_account') ?>",
      type: 'POST',
      data: {
        slug: id
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          $('#slug').val(result['content']['slug']);
          $('#name').val(result['content']['name']);
          $('#lname').val(result['content']['lname']);
          $('#type').val(result['content']['type']);
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

<script>
  function check_turns() {
    const patientName = document.getElementById("patientName");
    const patientNameOption = patientName.value;

    const date = document.getElementById("test-date-id-date");
    const dateValue = date.value;

    const doctorName = document.getElementById("doctorName");
    const doctorNameOption = doctorName.value


    const time = document.getElementById("patientTime");
    const timeOption = time.value;


    $.ajax({
      url: "<?= base_url('admin/check_turns') ?>",
      type: 'POST',
      data: {
        patient_id: patientNameOption,
        date: dateValue,
        doctor: ((doctorNameOption != 'all') ? doctorNameOption : null),
        patient_time: timeOption
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result['type'] == 'success') {
          if (result['content']['turns'].length < 1) {
            var querytable = ``;
          } else {
            var querytable = `
          <table class="table text-nowrap table-striped">
            <thead>
              <tr>
                <th scope="col">
                  <?= $ci->lang('patient name') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('reference doctor') ?>
                </th>
                <th scope="col">
                  <?= $ci->lang('hour') ?>
                </th>
              </tr>
            </thead>
            <tbody>
        `;

            result["content"]["turns"].map((item) => {
              querytable += `
            <tr>
              <td>${item.patient_name}</td>
              <td>${item.doctor_name}</td>
              <td>${item.hour}</td>
            </tr>
          `;
            });

            querytable += `
            </tbody>
          </table>
        `;

          }

          document.getElementById("queryTable").innerHTML = querytable;
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
  function print_turn(turnId) {
    window.open(`<?= base_url() ?>admin/print_turn/${turnId}`, '_blank');
  }
</script>


<script>
  function tableFilterPhoneBook(date) {
    $.ajax({
      url: "<?= base_url('admin/list_phonebook_json') ?>",
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
              item.btns,
            ]).node();
            row.id = item.id;
          });
        }

        table.draw(false);
      }
    });
  }
</script>

<script>
  function print_payment(turnId) {
    window.open(`<?= base_url() ?>admin/print_payment/${turnId}`, '_blank');
  }

  function sendSms() {
    let dates = $('#datePicker').val();
    console.log(dates);
    if (dates == '') {
      return;
    } else {
      $.ajax({
        url: "<?= base_url('admin/sms_turns_list') ?>",
        type: 'POST',
        data: {
          date: dates,
        },
        success: function(response) {

        }
      })
    }
  }
</script>

<script>
  function callModal(id, type) {
    $(`#hiddencall1`).val(type);
    $('#failedCallForm').trigger("reset");
    $('#hiddenId').val(id);
    $("#callmodal").modal('toggle');
  }
  </script>


<script>
  function eye(id, callnum) {

    $.ajax({
      url: "<?= base_url('admin/single_phonebook') ?>",
      type: 'POST',
      data: {
        slug: id,
        type: callnum,
      },
      success: function(response) {
        let result = JSON.parse(response);
        let callLog = result.content;

        $('#eyedate').val(callLog.date);
        $('#eyehour').val(callLog.hour);
        $('#eyeremark').val(callLog.remarks);


      }
    })
    $(`#eyemodal`).modal('toggle');

  }
</script>
