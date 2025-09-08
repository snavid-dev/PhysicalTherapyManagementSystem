<?php
$ci = get_instance();
?>
	<div class="table-responsive">
		<table class="table text-nowrap text-center" id="filesTable">
			<thead class="tableHead">
			<tr>
				<th scope="col">#</th>
				<th scope="col"><?= $ci->lang('title') ?></th>
				<th scope="col"><?= $ci->lang('date and time') ?></th>
				<th scope="col"><?= $ci->lang('desc') ?></th>
				<th scope="col"><?= $ci->lang('actions') ?></th>
			</tr>
			</thead>
			<tbody>
			<?php $i = 1;
			foreach ($files as $file) : ?>
				<tr id="<?= $file['id'] ?>" class="tableRow">
					<td scope="row"><?= $i ?></td>
					<td><?= $file['title'] ?></td>
					<td><?= $file['date'] ?></td>
					<td><?= $file['desc'] ?></td>
					<td>
						<div class="g-2">
							<a href="<?= base_url('patient_files/' . $file['filename']) ?>"
							   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
							   target="_blank"><span class="fa fa-download"></span></a>
							<a href="javascript:delete_via_alert('<?= $file['id'] ?>', '<?= base_url('admin/delete_files') ?>', 'filesTable')"
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



