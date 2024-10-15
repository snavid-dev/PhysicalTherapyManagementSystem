<?php $ci = get_instance() ?>
<div class="table-responsive">
	<table class="table text-nowrap" id="labsTable">
		<thead class="tableHead">
		<tr>
			<th scope="col">#</th>
			<th scope="col"><?= $ci->lang('laboratory') ?></th>
			<th scope="col"><?= $ci->lang('teeth') ?></th>
			<th scope="col"><?= $ci->lang('tooth type') ?></th>
			<th scope="col"><?= $ci->lang('delivery date') ?></th>
			<th scope="col"><?= $ci->lang('delivery time') ?></th>
			<th scope="col"><?= $ci->lang('pay amount') ?></th>
			<th scope="col"><?= $ci->lang('desc') ?></th>
			<th scope="col"><?= $ci->lang('actions') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php $i = 1;
		foreach ($labs as $lab) : ?>
			<tr id="<?= $lab['id'] ?>" class="tableRow">
				<td scope="row"><?= $i ?></td>
				<td><?= $lab['lab_name'] ?></td>
				<?php
				$teeths = explode(',', $lab['teeth']);
				$teethName = '';
				foreach ($teeths as $tooth) {
					$info = $ci->tooth_by_id($tooth);
					$teethName .= $info['name'];
					$teethName .= ' (';
					$teethName .= $ci->dentist->find_location($info['location']);
					$teethName .= '),';
				}
				?>
				<td><?= substr($teethName, 0, -1) ?></td>
				<?php
				$types = explode(',', $lab['type']);
				$typesName = '';
				foreach ($types as $type) {
					$typesName .= $ci->lang($type);
					$typesName .= ',';
				}
				?>
				<td><?= substr($typesName, 0, -1) ?></td>
				<td><?= $lab['give_date'] ?></td>
				<td><?= $ci->dentist->find_time($lab['hour']) ?></td>
				<td><?= $lab['dr'] ?></td>
				<td><?= $lab['remarks'] ?></td>
				<td>
					<div class="g-2">
						<a href="javascript:edit_lab('<?= $lab['id'] ?>')"
						   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-regular fa-pen-to-square fs-14"></span></a>
						<a href=""
						   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
								class="las la-check-circle"></span></a>
						<a href="javascript:print_lab('<?= $lab['id'] ?>')"
						   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-solid fa-print fs-14"></span></a>
						<a href="javascript:delete_via_alert('<?= $lab['id'] ?>', '<?= base_url() ?>admin/delete_lab', 'labsTable')"
						   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-solid fa-trash-can fs-14"></span></a>
					</div>
				</td>
			</tr>
			<?php $i++;
		endforeach; ?>
		</tbody>
	</table>
</div>
