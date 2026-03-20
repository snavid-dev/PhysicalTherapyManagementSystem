<!doctype html>
<html lang="<?= $current_locale === 'farsi' ? 'fa' : 'en' ?>" dir="<?= $is_rtl ? 'rtl' : 'ltr' ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body class="login-page <?= $is_rtl ? 'rtl' : 'ltr' ?>" data-theme="<?= html_escape($current_theme) ?>">
	<div class="container">
		<div class="row justify-content-center align-items-center min-vh-100">
			<div class="col-md-5">
				<div class="card shadow-sm border-0">
					<div class="card-body p-4">
						<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
							<div>
								<h1 class="h3 mb-2"><?= t('Physical Therapy Clinic') ?></h1>
								<p class="text-muted mb-0"><?= t('Simple management system for patients, turns, payments, reports, doctor leaves, and staff access.') ?></p>
							</div>
							<div class="d-flex gap-2">
								<a class="btn btn-outline-secondary btn-sm" href="<?= base_url('preferences/language/' . ($current_locale === 'farsi' ? 'english' : 'farsi')) ?>"><?= $current_locale === 'farsi' ? t('English') : t('Farsi') ?></a>
								<a class="btn btn-outline-secondary btn-sm" href="<?= base_url('preferences/theme/' . ($current_theme === 'dark' ? 'light' : 'dark')) ?>"><?= $current_theme === 'dark' ? t('Light Mode') : t('Dark Mode') ?></a>
							</div>
						</div>

						<?php if ($this->session->flashdata('success')) : ?>
							<div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
						<?php endif; ?>
						<?php if ($this->session->flashdata('error')) : ?>
							<div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
						<?php endif; ?>

						<?= form_open('login') ?>
							<div class="mb-3">
								<label class="form-label"><?= t('Username') ?></label>
								<input type="text" name="username" class="form-control" value="<?= set_value('username') ?>">
								<small class="text-danger"><?= form_error('username') ?></small>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= t('Password') ?></label>
								<input type="password" name="password" class="form-control">
								<small class="text-danger"><?= form_error('password') ?></small>
							</div>
							<button type="submit" class="btn btn-dark w-100"><?= t('Sign In') ?></button>
						<?= form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
