<!doctype html>
<html lang="<?= $current_locale === 'farsi' ? 'fa' : 'en' ?>" dir="<?= $is_rtl ? 'rtl' : 'ltr' ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= isset($title) ? html_escape($title) : t('Physical Therapy Clinic') ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
	<!-- Jalali Datepicker CSS fallback -->
	<link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
	<script>
		window.APP_LANG = <?= json_encode($current_locale ?? ($this->session->userdata('app_locale') ?: 'farsi')) ?>;
		window.DT_I18N = {
			search: <?= json_encode(t('dt_search')) ?>,
			show: <?= json_encode(t('dt_show')) ?>,
			entries: <?= json_encode(t('dt_entries')) ?>,
			noData: <?= json_encode(t('dt_no_data')) ?>,
			exportExcel: <?= json_encode(t('dt_export_excel')) ?>,
			exportPdf: <?= json_encode(t('dt_export_pdf')) ?>,
			print: <?= json_encode(t('dt_print')) ?>,
			balanceSortNote: <?= json_encode(t('balance_sort_note')) ?>
		};
	</script>
</head>
<body data-theme="<?= html_escape($current_theme) ?>" class="<?= $is_rtl ? 'rtl' : 'ltr' ?>">
<?php $current_section = isset($current_section) ? $current_section : ''; ?>
<?php if (!empty($auth_user)) : ?>
<nav class="navbar navbar-expand-lg clinic-nav border-bottom">
	<div class="container">
		<a class="navbar-brand fw-semibold" href="<?= base_url('dashboard') ?>"><?= t('Physical Therapy Clinic') ?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clinicNav">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="clinicNav">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link <?= $current_section === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>"><?= t('Dashboard') ?></a></li>
				<?php if ($this->auth->has_permission('manage_patients')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'patients' ? 'active' : '' ?>" href="<?= base_url('patients') ?>"><?= t('Patients') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_turns')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'turns' ? 'active' : '' ?>" href="<?= base_url('turns') ?>"><?= t('Turns') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('view_reports')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'reports' ? 'active' : '' ?>" href="<?= base_url('reports') ?>"><?= t('Reports') ?></a></li>
				<?php endif; ?>

				<?php $show_clinic_menu = $this->auth->has_permission('manage_reference_doctors') || $this->auth->has_permission('manage_staff') || $this->auth->has_permission('manage_sections') || $this->auth->has_permission('manage_leaves'); ?>
				<?php if ($show_clinic_menu) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle <?= in_array($current_section, ['reference_doctors', 'staff', 'sections', 'leaves']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown"><?= t('Clinic') ?></a>
						<ul class="dropdown-menu">
							<?php if ($this->auth->has_permission('manage_reference_doctors')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'reference_doctors' ? 'active' : '' ?>" href="<?= base_url('reference_doctors') ?>"><?= t('Reference Doctors') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_staff')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'staff' ? 'active' : '' ?>" href="<?= base_url('staff') ?>"><?= t('Staff') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_sections')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'sections' ? 'active' : '' ?>" href="<?= base_url('sections') ?>"><?= t('Sections') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_leaves')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'leaves' ? 'active' : '' ?>" href="<?= base_url('leaves') ?>"><?= t('Employee Leaves') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php $show_finance_menu = $this->auth->has_permission('manage_expenses') || $this->auth->has_permission('view_safe') || $this->auth->has_permission('manage_salaries') || $this->auth->has_permission('view_store'); ?>
				<?php if ($show_finance_menu) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle <?= in_array($current_section, ['expenses', 'safe', 'salaries', 'store']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown"><?= t('Finance') ?></a>
						<ul class="dropdown-menu">
							<?php if ($this->auth->has_permission('manage_expenses')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'expenses' ? 'active' : '' ?>" href="<?= base_url('expenses') ?>"><?= t('expenses') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('view_safe')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'safe' ? 'active' : '' ?>" href="<?= base_url('safe') ?>"><?= t('safe') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_salaries')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'salaries' ? 'active' : '' ?>" href="<?= base_url('salaries') ?>"><?= t('salaries') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('view_store')) : ?>
								<?php $store_pending_total = (int) ($pending_requisitions_count ?? 0) + (int) ($pending_sale_batches_count ?? 0); ?>
								<li>
									<a class="dropdown-item d-flex align-items-center justify-content-between <?= $current_section === 'store' ? 'active' : '' ?>" href="<?= base_url('store') ?>">
										<?= t('Store') ?>
										<?php if ($store_pending_total > 0) : ?>
											<span class="badge bg-warning text-dark rounded-pill"><?= $store_pending_total ?></span>
										<?php endif; ?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php $show_admin_menu = $this->auth->has_permission('manage_users') || $this->auth->has_permission('manage_roles'); ?>
				<?php if ($show_admin_menu) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle <?= in_array($current_section, ['users', 'roles']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown"><?= t('Admin') ?></a>
						<ul class="dropdown-menu">
							<?php if ($this->auth->has_permission('manage_users')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'users' ? 'active' : '' ?>" href="<?= base_url('users') ?>"><?= t('Users') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_roles')) : ?>
								<li><a class="dropdown-item <?= $current_section === 'roles' ? 'active' : '' ?>" href="<?= base_url('roles') ?>"><?= t('Roles') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
			<div class="d-flex align-items-center gap-3 header-tools">
				<div class="dropdown">
					<button class="btn btn-outline-secondary btn-sm dropdown-toggle btn-icon" data-bs-toggle="dropdown" type="button"><i class="bi bi-person-circle" aria-hidden="true"></i> <?= html_escape($auth_user['first_name'] . ' ' . $auth_user['last_name']) ?></button>
					<ul class="dropdown-menu dropdown-menu-end">
						<?php if ($this->auth->has_permission('manage_patients')) : ?>
							<li><a class="dropdown-item btn-icon" href="<?= base_url('preferences/diagnoses') ?>"><i class="bi bi-clipboard2-pulse" aria-hidden="true"></i> <?= t('manage_diagnoses') ?></a></li>
						<?php endif; ?>
						<?php if ($this->auth->has_permission('manage_expenses')) : ?>
							<li><a class="dropdown-item btn-icon" href="<?= base_url('preferences/expense-categories') ?>"><i class="bi bi-tags" aria-hidden="true"></i> <?= t('expense_categories') ?></a></li>
						<?php endif; ?>
						<?php if ($this->auth->has_permission('manage_patients') || $this->auth->has_permission('manage_expenses')) : ?>
							<li><hr class="dropdown-divider"></li>
						<?php endif; ?>
						<li><a class="dropdown-item btn-icon <?= $current_locale === 'farsi' ? 'active' : '' ?>" href="<?= base_url('preferences/language/farsi') ?>"><i class="bi bi-translate" aria-hidden="true"></i> <?= t('Farsi') ?></a></li>
						<li><a class="dropdown-item btn-icon <?= $current_locale === 'english' ? 'active' : '' ?>" href="<?= base_url('preferences/language/english') ?>"><i class="bi bi-translate" aria-hidden="true"></i> <?= t('English') ?></a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item btn-icon" href="<?= base_url('preferences/theme/' . ($current_theme === 'dark' ? 'light' : 'dark')) ?>"><i class="bi bi-<?= $current_theme === 'dark' ? 'sun' : 'moon-stars' ?>" aria-hidden="true"></i> <?= $current_theme === 'dark' ? t('Light Mode') : t('Dark Mode') ?></a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item btn-icon" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> <?= t('Logout') ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</nav>
<?php endif; ?>
<main class="container py-4">
	<?php if ($this->session->flashdata('success')) : ?>
		<div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('error')) : ?>
		<div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
	<?php endif; ?>
