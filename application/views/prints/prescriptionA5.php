<?php $ci = get_instance(); ?>
<html>

<head>
	<title>
		<?= (isset($title)) ? $title : 'سایبورگ تک' ?>
	</title>
	<link href="<?= $ci->dentist->assets_url() . 'assets/' ?>favicon.png" rel="shortcut icon">

	<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/css/rtl.css">
	<link href="https://fonts.cdnfonts.com/css/freestyle-script" rel="stylesheet">

	<link rel="stylesheet" href="">
	<style>

		@import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@500;700&display=swap');


		body {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 12pt "Tahoma";
		}

		.page {
			border: 1px #D3D3D3 solid;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
			width: 147mm;
			height: 209mm;
			overflow: hidden;
		}

		.subpage {
			/* padding: 1cm; */
			/* border: 5px red solid; */
			height: fit-content;
		}

		.company-extra.text-right {
			/* margin-right: 5px; */
		}

		/* our custom css start here */
		.invoice-w {
			max-width: 100%;
			position: relative;
			overflow: hidden;
		}

		.invoice-w,
		.invoice-heading,
		.invoice-body,
		.invoice-footer {
			text-align: right;
			/* padding: 0 13px; */
		}

		.invoice-w .infos {
			position: relative;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-pack: justify;
			-ms-flex-pack: justify;
			justify-content: space-between;
			text-align: center;
		}

		.invoice-w .infos .info-1 {
			font-size: 1rem;
			width: 100%;
		}

		.invoice-w .infos .info-1 .company-name {
			font-size: 1.8rem;
			font-weight: 900;
		}

		.invoice-w .infos .info-1 .company-extra {
			/* font-size: 6rem; */
			color: black;
			/* margin-top: 1rem; */
		}

		.text-center {
			text-align: center !important;
		}

		.invoice-heading {
			border-top: solid;
			/* margin: 1rem 0; */
			position: relative;
			z-index: 2;
		}

		/* h3,
		.h3,
		.invoice-date,
		.table.table-lightfont td,
		.form-group,
		.btn {
			font-size: 1.9rem;
			font-weight: 900;
		} */

		.invoice-date {
			direction: ltr;
		}

		.text-right {
			text-align: right !important;
		}

		.text-left {
			text-align: left !important;
		}

		.invoice-body {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
		}

		.invoice-table {
			width: 100%;
		}

		.important {
			z-index: 2;
		}

		.row {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			/* margin-right: -10px;
			margin-left: -10px; */
		}

		.col-md-12 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 100%;
			flex: 0 0 100%;
			max-width: 100%;
		}

		.col-1,
		.col-2,
		.col-3,
		.col-4,
		.col-5,
		.col-6,
		.col-7,
		.col-8,
		.col-9,
		.col-10,
		.col-11,
		.col-12,
		.col,
		.col-auto,
		.col-sm-1,
		.col-sm-2,
		.col-sm-3,
		.col-sm-4,
		.col-sm-5,
		.col-sm-6,
		.col-sm-7,
		.col-sm-8,
		.col-sm-9,
		.col-sm-10,
		.col-sm-11,
		.col-sm-12,
		.col-sm,
		.col-sm-auto,
		.col-md-1,
		.col-md-2,
		.col-md-3,
		.col-md-4,
		.col-md-5,
		.col-md-6,
		.col-md-7,
		.col-md-8,
		.col-md-9,
		.col-md-10,
		.col-md-11,
		.col-md-12,
		.col-md,
		.col-md-auto,
		.col-lg-1,
		.col-lg-2,
		.col-lg-3,
		.col-lg-4,
		.col-lg-5,
		.col-lg-6,
		.col-lg-7,
		.col-lg-8,
		.col-lg-9,
		.col-lg-10,
		.col-lg-11,
		.col-lg-12,
		.col-lg,
		.col-lg-auto,
		.col-xl-1,
		.col-xl-2,
		.col-xl-3,
		.col-xl-4,
		.col-xl-5,
		.col-xl-6,
		.col-xl-7,
		.col-xl-8,
		.col-xl-9,
		.col-xl-10,
		.col-xl-11,
		.col-xl-12,
		.col-xl,
		.col-xl-auto,
		.col-xxl-1,
		.col-xxl-2,
		.col-xxl-3,
		.col-xxl-4,
		.col-xxl-5,
		.col-xxl-6,
		.col-xxl-7,
		.col-xxl-8,
		.col-xxl-9,
		.col-xxl-10,
		.col-xxl-11,
		.col-xxl-12,
		.col-xxl,
		.col-xxl-auto,
		.col-xxxl-1,
		.col-xxxl-2,
		.col-xxxl-3,
		.col-xxxl-4,
		.col-xxxl-5,
		.col-xxxl-6,
		.col-xxxl-7,
		.col-xxxl-8,
		.col-xxxl-9,
		.col-xxxl-10,
		.col-xxxl-11,
		.col-xxxl-12,
		.col-xxxl,
		.col-xxxl-auto,
		.col-xxxxl-1,
		.col-xxxxl-2,
		.col-xxxxl-3,
		.col-xxxxl-4,
		.col-xxxxl-5,
		.col-xxxxl-6,
		.col-xxxxl-7,
		.col-xxxxl-8,
		.col-xxxxl-9,
		.col-xxxxl-10,
		.col-xxxxl-11,
		.col-xxxxl-12,
		.col-xxxxl,
		.col-xxxxl-auto {
			position: relative;
			width: 100%;
			min-height: 1px;
			/* padding-right: 10px;
			padding-left: 10px; */
		}

		table tr td {
			text-align: center;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		td,
		th {
			font-weight: 900;
			border: 2px solid black;
			text-align: center;
			/* padding: 5px; */
		}

		/* .element-wrapper {
			padding-bottom: 3rem;
		} */

		/* .element-box,
		.invoice-w,
		.big-error-w {
			padding: 1.5rem 1.5rem;
			margin-bottom: 1rem;
		} */

		h3,
		.h3,
		.invoice-date,
		.table.table-lightfont td,
		.form-group,
		.btn {
			font-size: 1rem;
			font-weight: 900;
		}

		/* .border-bottom {
			padding-bottom: 30px;
		} */


		tr.no-border td,
		tr.no-border th {
			border: none !important;
		}

		.col-md-2 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 16.6666666667%;
			flex: 0 0 16.6666666667%;
			max-width: 16.6666666667%;
		}

		.col-md-3 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 25%;
			flex: 0 0 25%;
			max-width: 25%;
		}

		.col-md-4 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 33.3333333333%;
			flex: 0 0 33.3333333333%;
			max-width: 33.3333333333%;
		}

		.col-md-8 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 66.6666666667%;
			flex: 0 0 66.6666666667%;
			max-width: 66.6666666667%;
		}

		.full-width {
			width: 100%;
		}

		.red {
			color: red;
		}

		p {
			/* margin-top: 0;
			margin-bottom: 1rem; */
		}

		.col-md-5 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 41.6666666667%;
			flex: 0 0 41.6666666667%;
			max-width: 41.6666666667%;
		}

		.col-md-7 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 58.3333333333%;
			flex: 0 0 58.3333333333%;
			max-width: 58.3333333333%;
		}

		.col-md-6 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 50%;
			flex: 0 0 50%;
			max-width: 50%;
		}

		.element-box {
			direction: rtl;
			/* margin-right: 20px;
			margin-left: 20px; */
		}

		.form-group.row.text-center {
			border: solid 2px;
			border-top: 0;
		}

		.price {
			font-family: sans-serif;
		}

		tr.no-border td,
		tr.no-border th {
			border: none !important;
		}

		.watermark {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			opacity: 0.3;
		}

		.info {
			/* border: 3px solid red; */
			background-image: url('<?= $ci->dentist->assets_url() ?>assets/prescription/<?= $ci->dentist->info()['prescriptionName'] ?>');

			background-size: cover;
			/*background-image: url('*/
		<?php //= $ci->dentist->assets_url() ?> /*assets/images/DPCMYK@1x_1------x.jpg');*/
			background-repeat: no-repeat;
			width: 100%;
			height: 100%;
		}

		.patientInfo {
			direction: rtl;
			width: 50%;
			position: relative;
			top: 11%;
			left: auto;
			right: 30%;
			font-family: 'Noto Naskh Arabic', serif !important;
			font-size: 1.3rem;
			display: flex;
			flex-direction: column;
			gap: 7px;
			font-weight: 600;
			min-height: 108px;
		}

		.address {
			position: relative;
			left: 2px;
			bottom: 3px;
		}


		.patientSurname {
			position: relative;
			bottom: 7%;
		}


		.subinfo {
			display: flex;
			gap: 127px;
			direction: rtl;
			width: 50%;
			position: relative;
			top: 8.5%;
			left: auto;
			right: 30%;
			font-family: 'Noto Naskh Arabic', serif !important;
			font-size: 1.3rem;
			font-weight: 600;

		}

		.book {
			width: 147mm;
			height: 209mm;
		}

		.prescription {
			/* border: 2px solid blue; */
			width: 54%;

		}

		.quantity {
			text-align: end;
			width: 100%;
			font-family: 'Freestyle Script';
			font-size: 2rem;
			position: relative;
			top: 13px;
		}

		.mainMedicine {
			display: flex;
			gap: 31px;
			font-family: 'Freestyle Script';
		}

		.mainMedicine > span {
			font-size: 44px;
		}

		.medicineUsage {
			display: flex;
			gap: 65px;
			justify-content: center;
			text-align: center '

		}

		.medicineUsage > span {
			font-family: 'Freestyle Script';
			font-size: 1.5rem;
		}

		.prescriptionContainer {
			/* width: 80%; */
			position: relative;
			display: flex;
			direction: ltr;
			flex-direction: column;
			/* flex-flow: column wrap; */
			flex-wrap: wrap;
			/* gap: 15px; */
			top: 17%;
			left: 24%;
			/* border: 3px dotted black; */
		}
	</style>
</head>

<body>
<div class="book">
	<div class="page">


		<div class="info">
			<div class="patientInfo">

				<div class="patientName">
					<span><?= $prescription['name'] ?>  <?= $prescription['lname'] ?></span>
				</div>


				<div class="address">
					<span><?= $prescription['address'] ?> </span>
				</div>

			</div>
			<div class="subinfo">
				<span class="patientAge"><?= $prescription['age'] ?></span>
				<span class="date"><?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?></span>
			</div>

			<div class="prescriptionContainer">

				<?php if ($prescription['medicine_1'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_1']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_1'] ?> <?= $prescription['unit_1'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_1'] ?></span>
							<span><?= $prescription['day_1'] ?>x<?= $prescription['time_1'] ?></span>
							<span><?= $prescription['amount_1'] ?></span>
						</div>

					</div>
				<?php endif; ?>


				<?php if ($prescription['medicine_2'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_2']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_2'] ?> <?= $prescription['unit_2'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_2'] ?></span>
							<span><?= $prescription['day_2'] ?>x<?= $prescription['time_2'] ?></span>
							<span><?= $prescription['amount_2'] ?></span>
						</div>

					</div>
				<?php endif; ?>

				<?php if ($prescription['medicine_3'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_3']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_3'] ?> <?= $prescription['unit_3'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_3'] ?></span>
							<span><?= $prescription['day_3'] ?>x<?= $prescription['time_3'] ?></span>
							<span><?= $prescription['amount_3'] ?></span>
						</div>

					</div>
				<?php endif; ?>


				<?php if ($prescription['medicine_4'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_4']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_4'] ?> <?= $prescription['unit_4'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_4'] ?></span>
							<span><?= $prescription['day_4'] ?>x<?= $prescription['time_4'] ?></span>
							<span><?= $prescription['amount_4'] ?></span>
						</div>

					</div>
				<?php endif; ?>

				<?php if ($prescription['medicine_5'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_5']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_5'] ?> <?= $prescription['unit_5'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_5'] ?></span>
							<span><?= $prescription['day_5'] ?>x<?= $prescription['time_5'] ?></span>
							<span><?= $prescription['amount_5'] ?></span>
						</div>

					</div>
				<?php endif; ?>


				<?php if ($prescription['medicine_6'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_6']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_6'] ?> <?= $prescription['unit_6'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_6'] ?></span>
							<span><?= $prescription['day_6'] ?>x<?= $prescription['time_6'] ?></span>
							<span><?= $prescription['amount_6'] ?></span>
						</div>

					</div>
				<?php endif; ?>



				<?php if ($prescription['medicine_7'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_7']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_7'] ?> <?= $prescription['unit_7'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_7'] ?></span>
							<span><?= $prescription['day_7'] ?>x<?= $prescription['time_7'] ?></span>
							<span><?= $prescription['amount_7'] ?></span>
						</div>

					</div>
				<?php endif; ?>



				<?php if ($prescription['medicine_8'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_8']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_8'] ?> <?= $prescription['unit_8'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_8'] ?></span>
							<span><?= $prescription['day_8'] ?>x<?= $prescription['time_8'] ?></span>
							<span><?= $prescription['amount_8'] ?></span>
						</div>

					</div>
				<?php endif; ?>


				<?php if ($prescription['medicine_9'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_9']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_9'] ?> <?= $prescription['unit_9'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_9'] ?></span>
							<span><?= $prescription['day_9'] ?>x<?= $prescription['time_9'] ?></span>
							<span><?= $prescription['amount_9'] ?></span>
						</div>

					</div>
				<?php endif; ?>


				<?php if ($prescription['medicine_10'] != 0) : ?>
					<?php $medicine = $ci->medicine_by_id($prescription['medicine_10']); ?>
					<div class="prescription">

						<div class="quantity">
							<span><?= $prescription['doze_10'] ?> <?= $prescription['unit_10'] ?></span>
						</div>

						<div class="mainMedicine">
							<span><?= ucfirst($medicine['type']) ?>.</span>
							<span><?= ucfirst($medicine['name']) ?></span>
						</div>

						<div class="medicineUsage">
							<span><?= $prescription['usageType_10'] ?></span>
							<span><?= $prescription['day_10'] ?>x<?= $prescription['time_10'] ?></span>
							<span><?= $prescription['amount_10'] ?></span>
						</div>

					</div>
				<?php endif; ?>

			</div>

		</div>

	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		setTimeout(function () {
			window.print();
			self.close();
		}, 1000);
	});
</script>
</body>

</html>
