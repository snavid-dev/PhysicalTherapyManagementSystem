<?php $ci = get_instance(); ?>

<?php $ci->render("patients/js.php"); ?>

<div class="row">
	<!--Patient Information Card -->
	<?php $ci->render('patients/components/patient_info/patient.php') ?>

	<?php $ci->render('patients/components/main/tabs.php'); ?>

	<?php $ci->render('patients/components/status/status.php') ?>
</div>
<!--End::row-1 -->




<?php $ci->render('patients/modals.php'); ?>

