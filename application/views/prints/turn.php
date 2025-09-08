<?php $ci = get_instance(); ?>
<html>

<head>
	<title><?= (isset($title)) ? $title : 'Cyborg Tech Creative Agency' ?></title>
	<link href="<?= $ci->dentist->assets_url() . 'assets/images/brand/' ?>favicon.ico" rel="shortcut icon">

	<link rel="stylesheet" href="<?= $ci->dentist->assets_url() ?>assets/printFiles/rtl.css">

	<!--QR Code Generator-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

	<style>
		div#qrcode img {
			margin: auto;
		}

		body {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 12pt "Tahoma";
		}

		.page {
			/* min-height: 217.47mm; */
			width: 92mm;
			/* padding: 5mm 20mm 5mm 5mm; */
			/* margin: 0mm auto; */
			border: 1px #D3D3D3 solid;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}

		.subpage {
			/* padding: 1cm; */
			/* border: 5px red solid; */
			height: fit-content;
		}

		.company-extra.text-right {
			margin-right: 5px;
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
			padding: 0 13px;
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
			font-size: 1.5rem;
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
			margin: 1rem 0;
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

		.borderless {
			border: 0 !important;
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
			text-align: right;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		td,
		th {
			font-weight: 900;
			border: 2px solid black;
			text-align: left;
			padding: 5px;
		}

		/* .element-wrapper {
			padding-bottom: 3rem;
		} */

		/* .element-box,
		.invoice-w,
		.big-error-w {
			padding: 1.5rem 2rem;
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

		p {
			margin-top: 0;
			margin-bottom: 1rem;
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
	</style>
</head>

<body>
<div class="book">
	<div class="page">
		<div class="subpage">
			<div class="invoice-w">
				<div class="infos">
					<div class="info-1">
						<div class="company-name">
							<img
								src="<?= $ci->dentist->assets_url() ?>assets/clinic/<?= $ci->dentist->info()['logoName'] ?>"
								width="55%" style="padding: 13px 0;">
						</div>
						<div class="company-name">
							<?= $ci->dentist->info()['title'] ?>
						</div>
						<div class="company-name">
							<?= $ci->dentist->info()['sub_title'] ?>
						</div>
						<div class="company-name" style="font-size: 1rem;">
							شماره تماس: <bdo dir="ltr"><?= $ci->dentist->info()['phone'] ?></bdo>
						</div>

					</div>
				</div>
				<div class="invoice-heading text-left">
					<div class="invoice-date text-center">
						<?= '(' . $ci->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s') . ')' ?> تاریخ
						و ساعت
					</div>
				</div>
				<div class="invoice-body">
					<div class="invoice-table  important" style="width: 100%;">
						<div class="row">
							<div class="col-md-12">
								<div class="element-wrapper">
									<div class="element-box">

										<table>
											<tbody>
											<tr>
												<td>نام مریض</td>
												<td><?= ($single['gender'] == 'm') ? 'آقای‌' : 'خانم' ?> <?= $single['name'] ?> <?= $single['lname'] ?></td>
											</tr>
											<tr>
												<td>سریال نمبر</td>
												<td><bdo dir="ltr"><?= $single['serial_id'] ?></bdo></td>
											</tr>
											<tr>
												<td>داکتر مربوطه</td>
												<td><?= $single['doctor_name'] ?></td>
											</tr>

											<tr>
												<td>تاریخ مراجعه بعدی</td>
												<td><bdo dir="ltr"><?= $single['date'] ?></bdo></td>
											</tr>
											<tr>
												<?php
												$dateString = $single['from_time']; // Example time string in H:i format

												// Convert the time string to a DateTime object
												$date = DateTime::createFromFormat('H:i', $dateString);

												// Extract the hour
												$hour = (int)$date->format('H'); // 'H' returns hour in 24-hour format

												?>

												<td>ساعت</td>
												<td><bdo dir="ltr"><?= ($hour > 12) ? $hour - 12 : $hour ?>:<?= $date->format('i') ?></bdo> <?= ($hour < 12) ? $ci->lang('am') : $ci->lang('pm') ?></td>
											</tr>

											<tr>
												<td>مجموعه هزینه</td>
												<td><?= $sum_dr ?> ؋</td>
											</tr>

											<tr>
												<td>مجموعه پرداخت شده</td>
												<td><?= $sum_cr ?> ؋</td>
											</tr>

											<tr style="font-size: 1.6rem; border: solid 3px;">
												<td class="borderless text-center">باقیمانده</td>
												<td class="borderless text-center"><?= $balance ?> ؋</td>
											</tr>

											</tbody>
										</table>

										<div class="company-extra text-center"
											 style="font-size: 0.8rem; font-weight: 700;margin-top: 10px;"
											 id="qrcode"></div>
										<div class="company-extra text-center"
											 style="font-size: 0.8rem; font-weight: 700;margin-top: 10px;">
											<sup>*</sup><?= $ci->dentist->info()['tagline'] ?><sup>*</sup></div>
										<div class="company-extra text-center"
											 style="font-size: 0.9rem; font-weight: 700;margin-top: 10px;"><?= $ci->dentist->info()['address'] ?></div>
										<div class="company-extra text-center"
											 style="font-size: 0.6rem; font-weight: 700;margin-top: 10px;">نرم افزار
											مدیریت کلینیک دندان(سایبورگ)
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= $ci->dentist->assets_url() . 'assets/' ?>js/jquery.min.js"></script>

<script>
	function generateQRCode() {
		// Clear the previous QR code if any
		document.getElementById("qrcode").innerHTML = "";

		// Get the text input value
		const text = '<?= $single['serial_id'] ?>';

		// Check if input is empty
		if (text.trim() === "") {
			alert("Please enter some text.");
			return;
		}

		// Create a new QRCode object
		const qrcode = new QRCode(document.getElementById("qrcode"), {
			text: text, // Text to be converted into QR Code
			width: 100, // Width of the QR Code
			height: 100, // Height of the QR Code
			colorDark: "#000000", // Dark color of QR Code
			colorLight: "#ffffff", // Light color of QR Code
			correctLevel: QRCode.CorrectLevel.H // Error correction level
		});
	}

	jQuery(document).ready(function () {
		setTimeout(() => {
			generateQRCode();
			window.print();
			self.close();
		}, 1000);
	});
</script>
</body>

</html>
