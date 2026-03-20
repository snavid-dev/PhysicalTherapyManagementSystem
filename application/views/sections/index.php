<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h1 class="h3 mb-1"><?= t('sections') ?></h1>
		<p class="text-muted mb-0"><?= t('manage_sections') ?></p>
	</div>
	<a href="<?= base_url('sections/create') ?>" class="btn btn-dark"><?= t('add_section') ?></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-middle">
				<thead>
					<tr>
						<th><?= t('Name') ?></th>
						<th><?= t('Default Fee') ?></th>
						<th class="text-end"><?= t('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($sections) : foreach ($sections as $section) : ?>
					<tr>
						<td><?= html_escape(t($section['name'])) ?></td>
						<td><?= format_amount($section['default_fee']) ?></td>
						<td class="text-end">
							<div class="d-flex gap-2 justify-content-end">
								<a href="<?= base_url('sections/' . $section['id']) ?>" class="btn btn-sm btn-outline-dark"><?= t('Chart') ?></a>
								<a href="<?= base_url('sections/' . $section['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= t('Edit') ?></a>
								<a href="<?= base_url('sections/' . $section['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= t('Delete this section?') ?>')"><?= t('Delete') ?></a>
							</div>
						</td>
					</tr>
				<?php endforeach; else : ?>
					<tr><td colspan="3" class="text-muted"><?= t('No sections found.') ?></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
