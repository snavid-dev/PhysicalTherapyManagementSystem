<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Users') ?></h1>
		<p class="text-muted mb-0"><?= t('Login accounts and role assignments.') ?></p>
	</div>
	<a href="<?= base_url('users/create') ?>" class="btn btn-dark"><?= t('Add User') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead><tr><th><?= t('Name') ?></th><th><?= t('Username') ?></th><th><?= t('Role') ?></th><th><?= t('Status') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($users) : foreach ($users as $item) : ?>
					<tr>
						<td><?= html_escape($item['first_name'] . ' ' . $item['last_name']) ?></td>
						<td><?= html_escape($item['username']) ?></td>
						<td><?= html_escape($item['role_name']) ?></td>
						<td><?= $item['is_active'] ? t('Active') : t('Inactive') ?></td>
						<td class="text-end">
							<a href="<?= base_url('users/' . $item['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('users/' . $item['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this user?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="5" class="text-muted"><?= t('No users found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
