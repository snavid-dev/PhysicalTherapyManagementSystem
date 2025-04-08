<?php $ci = get_instance(); ?>
<div id="teeth_list" class="mt-4">

	<?php if (count($teeth) != 0) : ?>
		<div class="table-responsive">
			<table class="table text-nowrap" id="teethTable">
				<thead class="tableHead">
				<tr>
					<th scope="col">#</th>
					<th scope="col"><?= $ci->lang('tooth name') ?></th>
					<th scope="col"><?= $ci->lang('tooth location') ?></th>
					<th scope="col"><?= $ci->lang('diagnose') ?></th>
					<th scope="col"><?= $ci->lang('services') ?></th>
					<th scope="col"><?= $ci->lang('pay amount') ?></th>
					<th scope="col"><?= $ci->lang('actions') ?></th>
				</tr>
				</thead>
				<tbody>
				<?php $i = 1;
				foreach ($teeth as $tooth) : ?>

					<tr id="<?= $tooth['id'] ?>" class="tableRow">
						<td scope="row"><?= $i ?></td>
						<td><?= $tooth['name'] ?></td>
						<td><?= $ci->dentist->find_location($tooth['location']) ?></td>
						<td><?= $tooth['diagnose'] ?></td>
						<td><?= $ci->services_name_multiple([$tooth['endo_services'], $tooth['restorative_services'], $tooth['prosthodontics_services']]) ?></td>
						<td><?= $tooth['price'] ?></td>
						<td>
							<div class="g-2">
								<?php if ($ci->auth->has_permission('Update Teeth')): ?>
									<a href="javascript:updateTeeth('<?= $tooth['id'] ?>', '<?= $profile['id'] ?>')"
									   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
											class="fa-regular fa-pen-to-square fs-14"></span></a>
								<?php endif; ?>
								<?php if ($ci->auth->has_permission('Delete Teeth')): ?>
									<a href="javascript:delete_via_alert('<?= $tooth['id'] ?>', '<?= base_url() ?>admin/delete_tooth', 'teethTable', update_balance)"
									   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
											class="fa-solid fa-trash-can fs-14"></span></a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
					<?php $i++;
				endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>

