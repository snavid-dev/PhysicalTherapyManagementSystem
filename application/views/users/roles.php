<?php $ci = get_instance(); ?>
<!-- Row -->
<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mb-0"><?= $ci->lang('role and permission') ?></h3>
			</div>
			<div class="card-body">

				<div class="tab-menu-heading border-0 p-0">
					<div class="tabs-menu1">

						<ul class="nav panel-tabs product-sale" role="tablist">
							<li>
								<button class="btn btn-primary"
										onclick="window.location.href='<?= base_url() ?>admin/user_role'"><?= $ci->lang('add new') ?>
									<i
										class="fa fa-plus"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="table-responsive">
					<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
						<thead>
						<tr>
							<th class="border-bottom-0">#</th>
							<th class="border-bottom-0"><?= $ci->lang('name') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('totalUsers') ?></th>
							<th class="border-bottom-0"><?= $ci->lang('actions') ?></th>
						</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						foreach ($roles as $role) : ?>
							<tr id="<?= $role->id ?>">
								<td><?= $i ?></td>
								<td><?= $role->role_name ?></td>
								<td><?= $role->user_count ?></td>
								<td>
									<div class="g-2">
										<a href="javascript:delete_via_alert('<?= $role->id ?>', '<?= base_url() ?>admin/delete_user', 'file-datatable', null, true)"
										   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span
												class="fa fa-trash fs-14"></span></a>

										<a href="javascript:window.location.href='<?= base_url() ?>admin/edit_role/<?= $role->id ?>'"
										   class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light"
										   data-bs-toggle="tooltip" data-bs-original-title="<?= $ci->lang('delete') ?>"><span
												class="fa fa-edit fs-14"></span></a>
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
