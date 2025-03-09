<?php
$ci = get_instance();
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>

	<!-- META DATA -->
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="CyborgTech – Bootstrap 5  Admin & Dashboard Template">
	<meta name="author" content="Sayed Navid Azimi">
	<meta name="keywords"
		  content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

	<?php if (!isset($_COOKIE['language'])) : ?>
		<script>
			window.location.reload();
		</script>
	<?php endif; ?>
	<!-- FAVICON -->
	<link rel="shortcut icon" href="<?= $ci->dentist->assets_url() ?>assets/images/brand/favicon.ico"
		  type="image/x-icon">

	<!-- TITLE -->
	<title><?= $title ?></title>


	<!-- BOOTSTRAP CSS -->
	<link id="style" href="<?= $ci->dentist->assets_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>"
		  rel="stylesheet">

	<!-- manifist -->

	<link rel="manifest" href="<?= base_url('manifest.json') ?>">

	<!--Qr Code Scanner-->
	<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>


	<script>
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.register('<?= base_url('service-worker.js') ?>')
				.then(function (registration) {
					console.log('Service Worker registered with scope:', registration.scope);
				})
				.catch(function (error) {
					console.log('Service Worker registration failed:', error);
				});
		}
	</script>

	<!-- STYLE CSS -->
	<link href="<?= $ci->dentist->assets_url() ?>assets/css/style.css" rel="stylesheet">

	<!-- Plugins CSS -->
	<link href="<?= $ci->dentist->assets_url() ?>assets/css/plugins.css" rel="stylesheet">

	<!--- FONT-ICONS CSS -->
	<link href="<?= $ci->dentist->assets_url() ?>assets/css/icons.css" rel="stylesheet">

	<!-- TODO: the font-awesome___________________________________________________________________________________________________________________________________________________________________________ -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<!-- TODO: the font-awesome___________________________________________________________________________________________________________________________________________________________________________ -->


	<!-- <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css"> -->
	<link rel="stylesheet"
		  href="<?= $ci->dentist->assets_url() ?>/assets/plugins/jalalidatepicker/jalalidatepicker.min.css">


	<!-- Croppie Js -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css"
		  integrity="sha512-2eMmukTZtvwlfQoG8ztapwAH5fXaQBzaMqdljLopRSA0i6YKM8kBAOrSSykxu9NN9HrtD45lIqfONLII2AFL/Q=="
		  crossorigin="anonymous" referrerpolicy="no-referrer"/>
	<!-- <link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>/assets/plugins/cropper/croppie.css"> -->
	<!-- <script src="<?= $ci->dentist->assets_url() ?>/assets/plugins/cropper/croppie.js"></script> -->

	<!-- TODO: Cropper.js css CDN -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

	<!-- TODO: users modal css -->
	<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/users_cropper_modal.css">

	<!-- INTERNAL Switcher css -->
	<link href="<?= $ci->dentist->assets_url() ?>assets/switcher/css/switcher.css" rel="stylesheet">
	<link href="<?= $ci->dentist->assets_url() ?>assets/switcher/demo.css" rel="stylesheet">


	<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/custom.css">
	<?php if ($_COOKIE['language'] == 'fa') : ?>
		<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/language/farsi.css">

	<?php elseif ($_COOKIE['language'] == 'pa') : ?>
		<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/language/pashto.css">

	<?php endif; ?>

	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feathericon@1.0.2/build/css/feathericon.min.css" integrity="sha256-obzGI3z5BUpmd1YT/GIEbWtcqHm3+/SGKgDaI3TB+V0=" crossorigin="anonymous"> -->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>


</head>

<body class="app sidebar-mini ltr light-mode">

<!-- GLOBAL-LOADER -->
<div id="global-loader">
	<img src="<?= $ci->dentist->assets_url() ?>assets/images/Crown_512px.png" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
	<div class="page-main">

		<!-- app-Header -->
		<div class="app-header header sticky">
			<div class="container-fluid main-container">
				<div class="d-flex">
					<a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
					   href="javascript:void(0)">
						<i class="fa fa-bars"></i>
					</a>
					<!-- sidebar-toggle-->
					<a class="logo-horizontal " href="<?= base_url() ?>admin">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/logo-white.png"
							 class="header-brand-img desktop-logo" alt="logo">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/Artboard 1logo 4x.png"
							 class="header-brand-img light-logo1" alt="logo">
					</a>
					<!-- TODO: timer  -->
					<div class="text-primary" style="display: flex; align-items: center;">
						<i class="fa fa-clock" style="font-size: 25px; margin: 5px"></i>
						<span style="color: gray;"><?= $ci->lang('Page will Reload In:') ?></span>

						<span id="timer" style="margin-left: 3px; font-size: large; margin-right: 3px;"> </span>
					</div>

					<!-- LOGO -->
					<div class="d-flex order-lg-2 ms-auto header-right-icons">
						<!-- SEARCH -->
						<button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
								data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
								aria-controls="navbarSupportedContent-4" aria-expanded="false"
								aria-label="Toggle navigation">
							<span class="navbar-toggler-icon fa fa-ellipsis-vertical"></span>
						</button>
						<div class="navbar navbar-collapse responsive-navbar p-0">
							<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
								<div class="d-flex order-lg-2">
									<div class="dropdown d-lg-none d-flex">
										<a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
											<i class="fa fa-search"></i>
										</a>
										<div class="dropdown-menu header-search dropdown-menu-start">
											<div class="input-group w-100 p-2">
												<input type="text" class="form-control" placeholder="Search....">
												<div class="input-group-text btn btn-primary">
													<i class="fa fa-search" aria-hidden="true"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="d-flex country">
										<a class="nav-link icon text-center" data-bs-target="#country-selector"
										   data-bs-toggle="modal">
											<i class="fa fa-globe"></i><span
												class="fs-16 ms-2 d-none d-xl-block"><?= ucfirst($ci->language->languages('language')) ?></span>
										</a>
									</div>
									<!-- COUNTRY -->
									<div class="d-flex">
										<a class="nav-link icon theme-layout nav-link-bg layout-setting">
											<span class="dark-layout"><i class="fa fa-moon"></i></span>
											<span class="light-layout"><i class="fa fa-sun"></i></span>
										</a>
									</div>
									<!-- Theme-Layout -->
									<div class="dropdown d-flex">
										<a class="nav-link icon full-screen-link nav-link-bg">
											<i class="fa fa-minimize fullscreen-button"></i>
										</a>
									</div>
									<!-- FULL-SCREEN -->
									<div class="dropdown d-flex profile-1">
										<a href="javascript:void(0)" data-bs-toggle="dropdown"
										   class="nav-link leading-none d-flex">
											<img
												src="<?= base_url() ?>user_images/<?= $ci->session->userdata($ci->mylibrary->hash_session('u_photo')) ?>"
												alt="profile-user" class="avatar  profile-user brround cover-image">
										</a>
										<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
											<div class="drop-heading">
												<div class="text-center">
													<h5 class="text-dark mb-0 fs-14 fw-semibold"><?= $ci->session->userdata($ci->mylibrary->hash_session('u_fullname')) ?></h5>
													<small
														class="text-muted"><?= $ci->lang($ci->mylibrary->list_user_type()[$ci->session->userdata($ci->mylibrary->hash_session('u_role'))]) ?></small>
												</div>
											</div>
											<div class="dropdown-divider m-0"></div>
											<!--											<a class="dropdown-item" href="profile.html">-->
											<!--												<i class="dropdown-icon fa fa-user"></i> Profile-->
											<!--											</a>-->
											<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
											   data-bs-target="#modify_password">
												<i class="dropdown-icon fa fa-key"></i> <?= $ci->lang('change password') ?>
											</a>
											<!--											<a class="dropdown-item" href="-->
											<?php //= base_url('admin/lock/') ?><!--">-->
											<!--												<i class="dropdown-icon fa fa-lock"></i> Lockscreen-->
											<!--											</a>-->
											<a class="dropdown-item" href="<?= base_url('admin/logout') ?>">
												<i class="dropdown-icon fa fa-exclamation-circle"></i> <?= $ci->lang('logout') ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /app-Header -->

		<!--APP-SIDEBAR-->
		<div class="sticky">
			<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
			<div class="app-sidebar">
				<div class="side-header">
					<a class="header-brand1" href="<?= base_url() ?>admin">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/logo-white.png"
							 class="header-brand-img desktop-logo" alt="logo">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/icon-white.png"
							 class="header-brand-img toggle-logo" alt="logo">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/Artboard 3logo 4x.png"
							 class="header-brand-img light-logo" alt="logo">
						<img src="<?= $ci->dentist->assets_url() ?>assets/images/brand/Artboard 1logo 4x.png"
							 class="header-brand-img light-logo1" alt="logo">
					</a>
					<!-- LOGO -->
				</div>
				<div class="main-sidemenu" id="scrollableDiv">
					<div class="slide-left disabled" id="slide-left">
						<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
							 viewBox="0 0 24 24">
							<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/>
						</svg>
					</div>
					<ul class="side-menu">
						<li class="sub-category">
							<h3><?= $ci->lang('main') ?></h3>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link" data-bs-toggle="slide" href="<?= base_url() ?>admin"><i
									class="side-menu__icon fa fa-home"></i><span
									class="side-menu__label"><?= $ci->language->languages('home') ?></span></a>
						</li>
						<?php if ($ci->auth->has_permission('Read Patients') || $ci->auth->has_permission('Read Turns Index') || $ci->auth->has_permission('Read Call Log Index')): ?>
							<li class="sub-category">
								<h3><?= $ci->lang('manage') ?></h3>
							</li>
						<?php endif; ?>

						<?php if ($ci->auth->has_permission('Read Patients')): ?>
							<li class="slide">
								<a class="side-menu__item has-link" data-bs-toggle="slide"
								   href="<?= base_url() ?>admin/patients"><i
										class="side-menu__icon fa fa-user"></i><span
										class="side-menu__label"><?= $ci->language->languages('patients') ?></span></a>
							</li>
						<?php endif; ?>
						<?php if ($ci->auth->has_permission('Read Turns Index')): ?>
							<li>
								<a class="side-menu__item has-link" href="<?= base_url() ?>admin/turns"><i
										class="side-menu__icon fa fa-calendar"></i><span
										class="side-menu__label"><?= $ci->lang('turns') ?></span></a>
							</li>
						<?php endif; ?>
						<?php if ($ci->auth->has_permission('Read Call Log Index')): ?>
							<li>
								<a class="side-menu__item has-link"
								   href="<?= ($page == 'phonebook') ? 'javascript:void(0)' : base_url('admin/phonebook') ?>"><i
										class="side-menu__icon fa fa-phone"></i><span
										class="side-menu__label"><?= $ci->lang('phonebook') ?></span></a>
							</li>
						<?php endif; ?>
						<li class="sub-category">
							<h3><?= $ci->lang('finances') ?></h3>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
									class="side-menu__icon fa fa-list"></i><span
									class="side-menu__label"><?= $ci->lang('laboratory') ?></span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide"
							   href="<?= ($page == 'receipts') ? 'javascript:void(0)' : base_url('admin/receipts') ?>"><i
									class="side-menu__icon fa fa-dollar-sign"></i><span
									class="side-menu__label"><?= $ci->lang('receipts') ?></span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide"
							   href="<?= ($page == 'accounts') ? 'javascript:void(0)' : base_url('admin/accounts') ?>"><i
									class="side-menu__icon fa fa-id-card"></i><span
									class="side-menu__label"><?= $ci->lang('financial accounts') ?></span>
							</a>
						</li>
						<li class="sub-category">
							<h3><?= $ci->lang('reports') ?></h3>
						</li>
						<li class="slide">
							<a class="side-menu__item <?= ($page == 'report_patients') ? 'active' : '' ?>"
							   data-bs-toggle="slide"
							   href="<?= ($page == 'report_patients') ? 'javascript:void(0)' : base_url('admin/report_patients') ?>"><i
									class="side-menu__icon fa fa-tasks"></i><span
									class="side-menu__label"><?= $ci->lang('report patients') ?></span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item <?= ($page == 'report_receipts') ? 'active' : '' ?>"
							   data-bs-toggle="slide"
							   href="<?= ($page == 'report_receipts') ? 'javascript:void(0)' : base_url('admin/report_receipts') ?>"><i
									class="side-menu__icon fa fa-pie-chart"></i><span
									class="side-menu__label"><?= $ci->lang('receipts') ?></span>
							</a>
						</li>
						<li class="sub-category">
							<h3><?= $ci->lang('general') ?></h3>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link <?= ($page == 'users') ? 'active' : '' ?>"
							   data-bs-toggle="slide" href="<?= base_url() ?>admin/users"><i
									class="side-menu__icon fa fa-users"></i><span
									class="side-menu__label"><?= $ci->lang('users') ?></span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link <?= ($page == 'roles') ? 'active' : '' ?>"
							   data-bs-toggle="slide" href="<?= base_url() ?>admin/roles"><i
									class="side-menu__icon fas fa-user-shield"></i><span
									class="side-menu__label"><?= $ci->lang('role and permission') ?></span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link <?= ($page == 'leave') ? 'active' : '' ?>"
							   data-bs-toggle="slide" href="<?= base_url() ?>admin/leave"><i
									class="side-menu__icon fas fa-user-md"></i><span
									class="side-menu__label"><?= $ci->lang('doctors leave requests') ?></span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link <?= ($page == 'primary_info') ? 'active' : '' ?>"
							   data-bs-toggle="slide"
							   href="<?= ($page == 'primary_info') ? 'javascript:void(0)' : base_url('admin/primary_info') ?>"><i
									class="side-menu__icon fa fa-info"></i><span
									class="side-menu__label"><?= $ci->language->languages('primary information') ?></span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link <?= ($page == 'switcher') ? 'active' : '' ?>"
							   data-bs-toggle="slide" href="<?= base_url() ?>admin/switcher"><i
									class="side-menu__icon fa fa-edit"></i><span
									class="side-menu__label"><?= $ci->lang('switcher') ?></span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item has-link" data-bs-toggle="slide"
							   href="<?= base_url() ?>admin/logout"><i class="side-menu__icon fa fa-sign-in"></i><span
									class="side-menu__label"><?= $ci->lang('logout') ?></span></a>
						</li>
					</ul>
					<div class="slide-right" id="slide-right">
						<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
							 viewBox="0 0 24 24">
							<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<!--/APP-SIDEBAR-->


		<!--app-content open-->
		<div class="main-content app-content mt-0">
			<div class="side-app">

				<!-- CONTAINER -->
				<div class="main-container container-fluid">


					<!-- PAGE-HEADER -->
					<div class="page-header">
						<h1 class="page-title"><?= $title ?></h1>
						<div>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="<?= ($page == 'home') ? 'javascript:void(0)' : base_url('admin/') ?>"><?= $ci->language->languages('home') ?></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
							</ol>
						</div>
					</div>
					<!-- PAGE-HEADER END -->
