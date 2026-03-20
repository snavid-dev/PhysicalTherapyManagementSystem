<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('Roles') ?></h1>
		<p class="text-muted mb-0"><?= t('Simple role and permission management.') ?></p>
	</div>
	<a href="<?= base_url('roles/create') ?>" class="btn btn-dark"><?= t('Add Role') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead><tr><th><?= t('Role Name') ?></th><th><?= t('Slug') ?></th><th><?= t('Users') ?></th><th></th></tr></thead>
				<tbody>
				<?php if ($roles) : foreach ($roles as $role) : ?>
					<tr>
						<td><?= html_escape($role['name']) ?></td>
						<td><?= html_escape($role['slug']) ?></td>
						<td><?= (int) $role['user_count'] ?></td>
						<td class="text-end">
							<a href="<?= base_url('roles/' . $role['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
							<a href="<?= base_url('roles/' . $role['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this role?') ?>')"><?= t('Delete') ?></a>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="4" class="text-muted"><?= t('No roles found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
