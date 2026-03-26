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
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
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
				<?php if ($this->auth->has_permission('manage_reference_doctors')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'reference_doctors' ? 'active' : '' ?>" href="<?= base_url('reference_doctors') ?>"><?= t('Reference Doctors') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_turns')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'turns' ? 'active' : '' ?>" href="<?= base_url('turns') ?>"><?= t('Turns') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_payments')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'payments' ? 'active' : '' ?>" href="<?= base_url('payments') ?>"><?= t('Payments') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_expenses')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'expenses' ? 'active' : '' ?>" href="<?= base_url('expenses') ?>"><?= t('expenses') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('view_safe')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'safe' ? 'active' : '' ?>" href="<?= base_url('safe') ?>"><?= t('safe') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_salaries')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'salaries' ? 'active' : '' ?>" href="<?= base_url('salaries') ?>"><?= t('salaries') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('view_reports')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'reports' ? 'active' : '' ?>" href="<?= base_url('reports') ?>"><?= t('Reports') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_leaves')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'leaves' ? 'active' : '' ?>" href="<?= base_url('leaves') ?>"><?= t('Doctor Leaves') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_staff')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'staff' ? 'active' : '' ?>" href="<?= base_url('staff') ?>"><?= t('Staff') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_sections')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'sections' ? 'active' : '' ?>" href="<?= base_url('sections') ?>"><?= t('Sections') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_users')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'users' ? 'active' : '' ?>" href="<?= base_url('users') ?>"><?= t('Users') ?></a></li>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('manage_roles')) : ?>
					<li class="nav-item"><a class="nav-link <?= $current_section === 'roles' ? 'active' : '' ?>" href="<?= base_url('roles') ?>"><?= t('Roles') ?></a></li>
				<?php endif; ?>
			</ul>
			<div class="d-flex align-items-center gap-3 header-tools">
				<?php if ($this->auth->has_permission('manage_patients') || $this->auth->has_permission('manage_expenses')) : ?>
					<div class="dropdown">
						<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" type="button"><?= t('Preferences') ?></button>
						<ul class="dropdown-menu dropdown-menu-end">
							<?php if ($this->auth->has_permission('manage_patients')) : ?>
								<li><a class="dropdown-item" href="<?= base_url('preferences/diagnoses') ?>"><?= t('manage_diagnoses') ?></a></li>
							<?php endif; ?>
							<?php if ($this->auth->has_permission('manage_expenses')) : ?>
								<li><a class="dropdown-item" href="<?= base_url('preferences/expense-categories') ?>"><?= t('expense_categories') ?></a></li>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
				<div class="dropdown">
					<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" type="button"><?= t('Language') ?></button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li><a class="dropdown-item" href="<?= base_url('preferences/language/farsi') ?>"><?= t('Farsi') ?></a></li>
						<li><a class="dropdown-item" href="<?= base_url('preferences/language/english') ?>"><?= t('English') ?></a></li>
					</ul>
				</div>
				<a class="btn btn-outline-secondary btn-sm" href="<?= base_url('preferences/theme/' . ($current_theme === 'dark' ? 'light' : 'dark')) ?>">
					<?= $current_theme === 'dark' ? t('Light Mode') : t('Dark Mode') ?>
				</a>
				<span class="small text-muted"><?= html_escape($auth_user['first_name'] . ' ' . $auth_user['last_name']) ?></span>
				<a class="btn btn-outline-dark btn-sm" href="<?= base_url('logout') ?>"><?= t('Logout') ?></a>
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
