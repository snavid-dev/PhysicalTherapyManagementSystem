<?php
$ci = get_instance();

//Insert File modal
$ci->render('patients/components/main/Archive/insert_modal.php');


//Insert Prescription Modal
$ci->render('patients/components/main/prescription/insert_modal.php');

//View Prescription modal
$ci->render('patients/components/main/prescription/view_modal.php');


//Insert Lab Modal
$ci->render('patients/components/main/Lab/insert_modal.php');

//View Lab modal
$ci->render('patients/components/main/Lab/update_modal.php');


//Insert Turn Modal
$ci->render('patients/components/main/Turns/insert_modal.php');

//Insert payment Modal
$ci->render('patients/components/main/Turns/payment_modal.php');

//Update Turns
$ci->render('patients/components/main/Turns/update_modal.php');


$ci->render('patients/components/main/modals/adult/insert/teethmodal.php');


