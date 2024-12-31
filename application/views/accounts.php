<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0"><?= $ci->lang('accounts list') ?></h3>
      </div>
      <div class="card-body">

        <div class="tab-menu-heading border-0 p-0">
          <div class="tabs-menu1">

            <ul class="nav panel-tabs product-sale" role="tablist">
              <li>
				  <?php if ($ci->auth->has_permission('Create New Account')): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#extralargemodal"><?= $ci->lang('add new') ?> <i class="fa fa-plus"></i></button>
				  <?php endif; ?>
              </li>
            </ul>
            <!-- Modal Button -->
            <!-- expenses Modal -->
			  <?php if ($ci->auth->has_permission('Create New Account')): ?>
            <div class="modal fade effect-scale" id="extralargemodal" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><?= $ci->lang('insert account') ?></h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="insertAccount">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('lname') ?></label>
                            <input type="text" name="lname" class="form-control" placeholder="<?= $ci->lang('lname') ?>">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('phone') ?></label>
                            <input type="number" name="phone" class="form-control" placeholder="<?= $ci->lang('phone') ?>">
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label class="form-label"><?= $ci->lang('account type') ?> <span class="text-red">*</span></label>
                            <select name="type" class="form-control form-select">
                              <option label="<?= $ci->lang('select') ?>"></option>
                              <?php foreach ($ci->mylibrary->list_account_type() as $key => $value) : ?>
                                <option value="<?= $key ?>"><?= $ci->lang($value) ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </form>

                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i class="fa fa-close" ></i></button>  
                    <button class="btn btn-primary" onclick="xhrSubmit('insertAccount', '<?= base_url() ?>admin/insert_account')"><?= $ci->lang('save') ?> <i class="fa fa-plus" ></i></button> 
                  </div>
                </div>
              </div>
            </div>
			  <?php endif; ?>
            <!-- expenses Modal End -->
          </div>
        </div>
        <div class="table-responsive">
          <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
            <thead>
              <tr>
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0"><?= $ci->lang('name') ?></th>
                <th class="border-bottom-0"><?= $ci->lang('lname') ?></th>
                <th class="border-bottom-0"><?= $ci->lang('phone') ?></th>
                <th class="border-bottom-0"><?= $ci->lang('account type') ?></th>
                <th class="border-bottom-0"><?= $ci->lang('user') ?></th>
                <th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($accounts as $account) : ?>
                <tr id="<?= $account['id'] ?>">
                  <td><?= $i ?></td>
                  <td><?= $account['name'] ?></td>
                  <td><?= $account['lname'] ?></td>
                  <td class="english"><?= $account['phone'] ?></td>
                  <td><?= $ci->mylibrary->elsewise($account['type'], 'l', $ci->lang('laboratory'), $ci->lang('expenses')) ?></td>
                  <td><?= $ci->mylibrary->account_name($account['fname'], $account['lastname'], $account['role']) ?></td>
                  <td>
                    <div class="g-2">
                      <a href="javascript:edit_account('<?= $account['id'] ?>')" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-edit fs-14"></span></a>
                      <a href="javascript:delete_via_alert('<?= $account['id'] ?>', '<?= base_url() ?>admin/delete_account')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-trash fs-14"></span></a>
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





<!-- Modal edit -->
<div class="modal fade effect-scale" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $ci->lang('edit account') ?></h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label class="form-label"><?= $ci->lang('name') ?> <span class="text-red">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="<?= $ci->lang('name') ?>">
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label class="form-label"><?= $ci->lang('lname') ?></label>
                <input type="hidden" name="slug" id="slug">
                <input type="text" name="lname" id="lname" class="form-control" placeholder="<?= $ci->lang('lname') ?>">
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label class="form-label"><?= $ci->lang('phone') ?></label>
                <input type="number" name="phone" id="phone" class="form-control" placeholder="<?= $ci->lang('phone') ?>">
              </div>
            </div>

            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label class="form-label"><?= $ci->lang('account type') ?> <span class="text-red">*</span></label>
                <select name="type" id="type" class="form-control form-select">
                  <option label="<?= $ci->lang('select') ?>"></option>
                  <?php foreach ($ci->mylibrary->list_account_type() as $key => $value) : ?>
                    <option value="<?= $key ?>"><?= $ci->lang($value) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i class="fa fa-close" ></i></button>
        <button class="btn btn-primary" onclick="xhrUpdate('update', '<?= base_url() ?>admin/update_account')"><?= $ci->lang('update') ?> <i class="fa fa-edit" ></i></button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End -->


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
