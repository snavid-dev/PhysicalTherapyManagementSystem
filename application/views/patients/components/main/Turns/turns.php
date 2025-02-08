<?php $ci = get_instance(); ?>


<?php //print_r($turns); exit(); ?>

<div class="table-responsive">
	<table class="table text-nowrap" id="turnsTable">
		<thead class="tableHead">
		<tr>
			<th scope="col">#</th>
			<th scope="col"><?= $ci->lang('reference doctor') ?></th>
			<th scope="col"><?= $ci->lang('date') ?></th>
			<th scope="col"><?= $ci->lang('hour') ?></th>
			<th scope="col"><?= $ci->lang('paid amount') ?></th>
			<th scope="col"><?= $ci->lang('actions') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php $i = 1;
		foreach ($turns as $turn) : ?>
			<tr id="<?= $turn['id'] ?>" class="tableRow">
				<td scope="row"><?= $i ?></td>
				<td><?= $turn['doctor_name'] ?></td>
				<td><?= $turn['date'] ?></td>
				<td><bdo dir="ltr"><?= $turn['from_time'] ?> - <?= $turn['to_time'] ?></bdo></td>
				<td><?= $turn['cr'] ?></td>
				<td>
					<div class="g-2">
						<a href="javascript:edit_turn('<?= $turn['id'] ?>')"
						   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-regular fa-pen-to-square fs-14"></span></a>
						<a href="javascript:print_turn('<?= $turn['id'] ?>')"
						   class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-solid fa-print fs-14"></span></a>
						<?php if ($turn['status'] == 'p') : ?>
							<a href="javascript:changeStatus('<?= $turn['id'] ?>', '<?= base_url() ?>admin/accept_turn')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa-regular fa-circle-check fs-14"></span></a>
						<?php else : ?>
							<a href="javascript:changeStatus('<?= $turn['id'] ?>', '<?= base_url() ?>admin/pending_turn')"
							   class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"><span
									class="fa fa-times-circle fs-14"></span></a>
						<?php endif; ?>
						<a href="javascript:delete_via_alert('<?= $turn['id'] ?>', '<?= base_url() ?>admin/delete_turn', 'turnsTable', update_balance)"
						   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"><span
								class="fa-regular fa-trash-can fs-14"></span></a>
					</div>
				</td>
			</tr>
			<?php $i++;
		endforeach; ?>
		</tbody>
	</table>
</div>
