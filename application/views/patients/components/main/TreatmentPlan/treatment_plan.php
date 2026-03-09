<?php
$ci = get_instance();
?>

<div class="table-responsive">
	<table class="table text-nowrap text-center" id="TreatmentPlanTable">
		<thead class="tableHead">
		<tr>
			<th scope="col">#</th>
			<th scope="col"><?= $ci->lang('title') ?></th>
			<th scope="col"><?= $ci->lang('sum of recommendation') ?></th>
			<th scope="col"><?= $ci->lang('actions') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php $i = 1;
		foreach ($treatment_plans as $treatment_plan) : ?>
			<?php $is_locked = !empty($treatment_plan['has_linked_turn']); ?>
			<tr id="<?= $treatment_plan['id'] ?>" class="tableRow">
				<td scope="row"><?= $i ?></td>
				<td><?= $treatment_plan['recommendation_name'] ?></td>
				<td><?= $treatment_plan['total_recommendations'] ?></td>
				<td>
					<div class="g-2">
						<a href="<?= $is_locked ? 'javascript:void(0)' : "javascript:edit_treatment_plan('{$treatment_plan['id']}')" ?>"
						   class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light<?= $is_locked ? ' disabled opacity-50' : '' ?>"
						   data-bs-toggle="tooltip"
						   data-bs-original-title="<?= $is_locked ? 'Linked to a turn' : $ci->lang('edit') ?>"><span
								class="fa fa-edit fs-14"></span></a>
						<a href="<?= $is_locked ? 'javascript:void(0)' : "javascript:delete_via_alert('{$treatment_plan['id']}', '" . base_url('admin/delete_treatment') . "', 'TreatmentPlanTable', list_treatment_plan)" ?>"
						   class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light<?= $is_locked ? ' disabled opacity-50' : '' ?>"
						   data-bs-toggle="tooltip"
						   data-bs-original-title="<?= $is_locked ? 'Linked to a turn' : $ci->lang('delete') ?>"><span
								class="fa-solid fa-trash-can fs-14"></span></a>
					</div>
				</td>
			</tr>
			<?php $i++;
		endforeach; ?>
		</tbody>
	</table>
</div>
