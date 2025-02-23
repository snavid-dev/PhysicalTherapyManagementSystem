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
	<meta name="author" content="Spruko Technologies Private Limited">
	<meta name="keywords"
		  content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

	<!-- Styles -->
	<link href="<?= $ci->dentist->assets_url() ?>assets/css/scroll_style.css" rel="stylesheet">

	<!--	css(fonts) for pages-->
	<?php if ($_COOKIE['language'] == 'fa') : ?>
		<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/language/farsi.css">

	<?php elseif ($_COOKIE['language'] == 'pa') : ?>
		<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/language/pashto.css">
	<?php endif; ?>
	<?php if (!isset($_COOKIE['language'])) : ?>
		<script>
			window.location.reload();
		</script>
	<?php endif; ?>

</head>

<body>
<div class="scroll-down"><?= $ci->lang('Scroll Down') ?>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
		<path
			d="M16 3C8.832031 3 3 8.832031 3 16s5.832031 13 13 13 13-5.832031 13-13S23.167969 3 16 3zm0 2c6.085938 0 11 4.914063 11 11 0 6.085938-4.914062 11-11 11-6.085937 0-11-4.914062-11-11C5 9.914063 9.914063 5 16 5zm-1 4v10.28125l-4-4-1.40625 1.4375L16 23.125l6.40625-6.40625L21 15.28125l-4 4V9z"/>
	</svg>
</div>
<div class="logo">
	<img src="<?= $ci->dentist->assets_url() ?>assets/images/Crown_512px.png" alt="Logo">
</div>
<div class="container"></div>
<div class="modal">
	<div class="modal-container">
		<div class="modal-left">
			<h1 class="modal-title"><?= $ci->lang('welcome') ?></h1>
			<p class="modal-desc"><?= $ci->lang('Smile Slogan') ?></p>
			<form action="<?= base_url('Login/index') ?>" method="POST">
				<div class="input-block">
					<label for="email" class="input-label"><?= $ci->lang('username') ?></label>
					<input class="effect-19" type="text" name="username" id="userName"
						   placeholder="<?= $ci->lang('username') ?>">
				</div>
				<div class="input-block">
					<label for="password" class="input-label"><?= $ci->lang('password') ?></label>
					<div style="display: flex; justify-content: space-between">
						<input class="effect-19" type="password" name="password" id="password"
							   placeholder="<?= $ci->lang('password') ?>">
						<span onclick="activePassword('password', 'activePasswordEye')" style="cursor: pointer" id="activePasswordEye">
						<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
							 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							 class="feather feather-eye-off"><path
								d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line
								x1="1" y1="1" x2="23" y2="23"/></svg>
					</span>
					</div>

				</div>

				<div class="modal-buttons">
					<button type="submit" class="input-button"><?= $ci->lang('login') ?></button>
				</div>
			</form>

		</div>
		<div class="modal-right">
			<img
				src="https://images.unsplash.com/photo-1606811856475-5e6fcdc6e509?q=80&w=1936&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
				alt="">
		</div>
		<button class="icon-button close-button">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
				<path
					d="M 25 3 C 12.86158 3 3 12.86158 3 25 C 3 37.13842 12.86158 47 25 47 C 37.13842 47 47 37.13842 47 25 C 47 12.86158 37.13842 3 25 3 z M 25 5 C 36.05754 5 45 13.94246 45 25 C 45 36.05754 36.05754 45 25 45 C 13.94246 45 5 36.05754 5 25 C 5 13.94246 13.94246 5 25 5 z M 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.980469 15.990234 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 z"></path>
			</svg>
		</button>
	</div>
	<button class="modal-button"><?= $ci->lang('click here') ?></button>
</div>
<!--<script src="script.js" charset="utf-8"></script>-->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/scrollJs.js"></script>

<script>
	// TODO: login page active password
	function activePassword(passwordId, iconId) {
		const passwordInput = document.getElementById(passwordId);
		const icon = document.getElementById(iconId);

		if (passwordInput.type === "password") {
			passwordInput.type = "text";
			icon.classList.add('showPassword')
		} else {
			passwordInput.type = "password";
			icon.classList.remove('showPassword')
		}
	}
</script>
</body>
</html>


