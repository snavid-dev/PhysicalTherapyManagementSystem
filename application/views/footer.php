<?php $ci = get_instance(); ?>
</div>
<!-- CONTAINER END -->
</div>
</div>
<!--app-content close-->
<!-- Country-selector modal-->
<div class="modal fade" id="country-selector">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content country-select-modal">
			<div class="modal-header">
				<h6 class="modal-title"><?= $ci->lang('choose language') ?></h6>
				<button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
						aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<ul class="row p-3">
					<li class="col-lg-4 mb-2">
						<a href="<?= base_url('admin/language/fa') ?>"
						   class="btn btn-country btn-lg btn-block <?= ($_COOKIE['language'] == 'fa') ? 'active' : '' ?> fa">
							<span class="country-selector"><img alt=""
																src="<?= $ci->dentist->assets_url() ?>assets/images/flags-img/afg.png"
																class="me-3 language"></span>فارسی
						</a>
					</li>
					<li class="col-lg-4 mb-2">
						<a href="<?= base_url('admin/language/en') ?>"
						   class="btn btn-country btn-lg btn-block <?= ($_COOKIE['language'] == 'en') ? 'active' : '' ?> en">
							<span class="country-selector"><img alt=""
																src="<?= $ci->dentist->assets_url() ?>assets/images/flags-img/usa.png"
																class="me-3 language"></span>English
						</a>
					</li>

					<li class="col-lg-4 mb-2">
						<a href="<?= base_url('admin/language/pa') ?>"
						   class="btn btn-country btn-lg btn-block <?= ($_COOKIE['language'] == 'pa') ? 'active' : '' ?> pa">
							<span class="country-selector"><img alt=""
																src="<?= $ci->dentist->assets_url() ?>assets/images/flags-img/afg.png"
																class="me-3 language"></span>پښتو
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- Country-selector modal-->


<!-- Change Password Modal -->

<div class="modal fade effect-scale" id="modify_password" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $ci->lang('change password') ?></h5>
				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="modify_password_form">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('old password') ?> <span
										class="text-red">*</span></label>
								<input type="password" name="oldPassword" class="form-control"
									   placeholder="<?= $ci->lang('old password') ?>">
							</div>
						</div>
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('new password') ?> <span
										class="text-red">*</span></label>
								<input type="password" name="newPassword" class="form-control"
									   placeholder="<?= $ci->lang('new password') ?>">
							</div>
						</div>
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label class="form-label"><?= $ci->lang('confirm new password') ?> <span
										class="text-red">*</span></label>
								<input type="password" name="confirmPassword" class="form-control"
									   placeholder="<?= $ci->lang('confirm new password') ?>">
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal"><?= $ci->lang('cancel') ?> <i
						class="fa fa-close"></i></button>
				<button class="btn btn-primary"
						onclick="xhrChangePassword('modify_password_form', '<?= base_url() ?>admin/update_password', 'modify_password')"><?= $ci->lang('update') ?>
					<i class="fa fa-lock"></i></button>
			</div>
		</div>
	</div>
</div>


<!-- Change Password Modal -->

<!-- FOOTER -->
<footer class="footer">
	<div class="container">
		<div class="row align-items-center flex-row-reverse">
			<div class="col-md-12 col-sm-12 text-center">
				<!-- Copyright © <span id="year"></span> <a href="javascript:void(0)">CyborgTech</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved. -->
				کپی رایت © <span>2022 - <?= date('Y') ?></span> <a href="https://cyborgtech.co/" target="_blank">
					سایبورگ تک</a>. دیزاین شده با <span class="fa fa-heart text-danger"></span> توسط <a
					href="https://cyborgtech.co/" target="_blank"> تیم خلاق ما </a>
			</div>
		</div>
	</div>
</footer>
<!-- FOOTER CLOSED -->
</div>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JQUERY JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/jquery.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>


<!-- <script src="<?= $ci->dentist->assets_url() ?>/assets/plugins/cropper/croppie.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"
		integrity="sha512-vUJTqeDCu0MKkOhuI83/MEX5HSNPW+Lw46BA775bAWIp1Zwgz3qggia/t2EnSGB9GoS2Ln6npDmbJTdNhHy1Yw=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->


<!-- TypeHead js -->

<!-- FORM WIZARD JS-->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/formwizard/jquery.smartWizard.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/formwizard/fromwizard.js"></script>


<!-- INTERNAl Jquery.steps js -->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/jquery-steps/jquery.steps.min.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>

<!-- INTERNAL Accordion-Wizard-Form js-->
<script
	src="<?= $ci->dentist->assets_url() ?>assets/plugins/accordion-Wizard-Form/jquery.accordion-wizard.min.js"></script>


<!-- SWEET-ALERT JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/sweetalert/sweetalert.min.js"></script>


<!-- INTERNAL Notifications js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?php if ($_COOKIE['language'] == 'fa') : ?>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/jquery.dataTables.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/dataTables.bootstrap5.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/dataTables.buttons.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/buttons.bootstrap5.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/jszip.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/pdfmake/pdfmake.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/pdfmake/vfs_fonts.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/buttons.html5.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/buttons.print.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/js/buttons.colVis.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/dataTables.responsive.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable-fa/responsive.bootstrap5.min.js"></script>
<?php elseif ($_COOKIE['language'] == 'en') : ?>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/jszip.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/buttons.html5.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/buttons.print.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/js/buttons.colVis.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/dataTables.responsive.min.js"></script>
	<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<?php endif; ?>
<script src="<?= $ci->dentist->assets_url() ?>assets/js/table-data.js"></script>

<!-- Perfect SCROLLBAR JS-->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/p-scroll/perfect-scrollbar.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/p-scroll/pscroll.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/p-scroll/pscroll-1.js"></script>

<!-- SIDE-MENU JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/sidemenu/sidemenu.js"></script>

<!-- SIDEBAR JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/sidebar/sidebar.js"></script>

<!-- Color Theme js -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/themeColors.js"></script>

<!-- Sticky js -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/sticky.js"></script>
<!-- <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script> -->

<script type="text/javascript"
		src="<?= $ci->dentist->assets_url() ?>/assets/plugins/jalalidatepicker/jalalidatepicker.min.js"></script>

<!-- TODO: cropper.js CDN for profile page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<!-- CUSTOM JS -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/xhrFunctions.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/js/custom.js"></script>

<script>
	function direction(dir) {
		if (dir == 'rtl') {
			$('body').addClass('rtl');

			$('#slide-left').removeClass('d-none');
			$('#slide-right').removeClass('d-none');
			$("html[lang=en]").attr("dir", "rtl");
			$('body').removeClass('ltr');
			$("head link#style").attr("href", $(this));
			(document.getElementById("style").setAttribute("href", "<?= $ci->dentist->assets_url() ?>assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
			var carousel = $('.owl-carousel');
			$.each(carousel, function (index, element) {
				// element == this
				var carouselData = $(element).data('owl.carousel');
				carouselData.settings.rtl = true; //don't know if both are necessary
				carouselData.options.rtl = true;
				$(element).trigger('refresh.owl.carousel');
			});
			localStorage.setItem('CyborgTechrtl', true)
			localStorage.removeItem('CyborgTechltr')

		} else {
			$('body').addClass('ltr');

			$('#slide-left').removeClass('d-none');
			$('#slide-right').removeClass('d-none');
			$("html[lang=en]").attr("dir", "ltr");
			$('body').removeClass('rtl');
			$("head link#style").attr("href", $(this));
			(document.getElementById("style").setAttribute("href", "<?= $ci->dentist->assets_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css"));
			var carousel = $('.owl-carousel');
			$.each(carousel, function (index, element) {
				// element == this
				var carouselData = $(element).data('owl.carousel');
				carouselData.settings.rtl = false; //don't know if both are necessary
				carouselData.options.rtl = false;
				$(element).trigger('refresh.owl.carousel');
			});
			localStorage.setItem('CyborgTechltr', true)
			localStorage.removeItem('CyborgTechrtl')
		}
	}
</script>

<script>
	<?php if ($_COOKIE['language'] == 'fa' || $_COOKIE['language'] == 'pa') : ?>
	direction('rtl');
	<?php else : ?>
	direction('ltr');
	<?php endif; ?>
</script>
<!-- Custom-switcher -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/custom-swicher.js"></script>
<!-- TODO: timer -->
<script src="<?= $ci->dentist->assets_url() ?>assets/js/timer.js"></script>

<!-- Switcher js -->
<script src="<?= $ci->dentist->assets_url() ?>assets/switcher/js/switcher.js"></script>

<script src="<?= $ci->dentist->assets_url() ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= $ci->dentist->assets_url() ?>assets/js/select2.js"></script>

<script>
	function delete_via_alert(id, href, tableId = null, callback = null, datatable = false) {
		swal({
			title: '<?= $ci->lang('delete alert title') ?>',
			text: "<?= $ci->lang('delete alert text') ?>",
			icon: "warning",
			buttons: ['<?= $ci->lang('cancel') ?>', '<?= $ci->lang('yes') ?>'],
			dangerMode: '<?= $ci->lang('yes') ?>',
		})
			.then((willDelete) => {
				if (willDelete) {
					if (tableId == null) {
						xhrDelete(id, href);
					} else {
						if (callback != null) {
							deleteSimple(id, href, tableId, callback);
						} else if (datatable) {
							xhrDelete(id, href, false, tableId);
						} else if (callback == null) {
							deleteSimple(id, href, tableId);
						}
					}
				}
			});
	}
</script>


<script>
	function accept_via_alert(id, href, tableId = null) {
		swal({
			title: '<?= $ci->lang('delete alert title') ?>',
			text: "<?= $ci->lang('delete alert text') ?>",
			icon: "info",
			buttons: ['<?= $ci->lang('cancel') ?>', '<?= $ci->lang('yes') ?>'],
			dangerMode: '<?= $ci->lang('yes') ?>',
		})
			.then((willDelete) => {
				if (willDelete) {
					if (tableId == null) {
						xhrDelete(id, href);
					} else {
						deleteSimple(id, href, tableId);
					}
				}
			});
	}
</script>


<?php if (isset($script)) : ?>
	<script>
		$(function (e) {
			<?= $script ?>
		})
	</script>
<?php endif; ?>

<?php if (isset($script_date)) : ?>
	<script>
		<?= $script_date ?>
	</script>
<?php endif; ?>

<?php if (isset($script_single_patient)) : ?>
	<?php foreach ($script_single_patient as $script_address) : ?>
		<script src="<?= base_url() ?><?= $script_address ?>"></script>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($script_single_patient_assets)) : ?>
	<?php foreach ($script_single_patient_assets as $asset) : ?>
		<script src="<?= $ci->dentist->assets_url() ?><?= $asset ?>"></script>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ($ci->dentist->usage_type == 'online'): ?>

	<?php if ($_COOKIE['language'] == 'en') : ?>


		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			var Tawk_API = Tawk_API || {},
				Tawk_LoadStart = new Date();
			(function () {
				var s1 = document.createElement("script"),
					s0 = document.getElementsByTagName("script")[0];
				s1.async = true;
				s1.src = 'https://embed.tawk.to/662df48f1ec1082f04e85579/1hsho6l8h';
				s1.charset = 'UTF-8';
				s1.setAttribute('crossorigin', '*');
				s0.parentNode.insertBefore(s1, s0);
			})();
		</script>
		<!--End of Tawk.to Script-->


	<?php else : ?>


		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			var Tawk_API = Tawk_API || {},
				Tawk_LoadStart = new Date();
			(function () {
				var s1 = document.createElement("script"),
					s0 = document.getElementsByTagName("script")[0];
				s1.async = true;
				s1.src = 'https://embed.tawk.to/662df2e9a0c6737bd131ceda/1hshnpoq4';
				s1.charset = 'UTF-8';
				s1.setAttribute('crossorigin', '*');
				s0.parentNode.insertBefore(s1, s0);
			})();
		</script>
		<!--End of Tawk.to Script-->
	<?php endif; ?>
<?php endif; ?>
</body>

</html>
