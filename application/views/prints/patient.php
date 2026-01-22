<?php $ci = get_instance(); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>برنامه جامع درمانی بیمار</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700&display=swap');

		/* General Styles */
		body {
			margin: 0;
			padding: 20px 0;
			background-color: #e9edf2;
			font-family: 'Vazirmatn', sans-serif;
			color: #2c3e50;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		.treatment-plan-container {
			position: relative; /* Needed for the watermark pseudo-element */
			max-width: 850px;
			background-color: #ffffff;
			margin: 30px auto;
			padding: 40px;
			box-shadow: 0 15px 40px rgba(44, 62, 80, 0.1);
			border-radius: 12px;
			border-top: 5px solid #0056b3;
			box-sizing: border-box;
			overflow: hidden; /* Ensures pseudo-element doesn't overflow */
		}

		/* Watermark */
		.treatment-plan-container::before {
			content: "";
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 400px;
			height: 400px;
			/* --- IMPORTANT: REPLACE WITH YOUR LOGO URL --- */
			background-image: url('<?= base_url() ?>path/to/your/watermark-logo.png');
			background-repeat: no-repeat;
			background-position: center;
			background-size: contain;
			opacity: 0.06;
			z-index: 1;
		}

		.main-content {
			position: relative;
			z-index: 2; /* Ensures content is on top of the watermark */
		}

		@media print {
			body {
				background-color: #fff;
				padding: 0;
			}
			.treatment-plan-container {
				box-shadow: none;
				margin: 0;
				border-radius: 0;
				border-top: none;
				max-width: 100%;
			}
		}

		@media screen and (max-width: 768px) {
			.treatment-plan-container {
				width: 95%;
				margin: 15px auto;
				padding: 25px;
			}
		}

		/* Header Section */
		.header {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			border-bottom: 2px solid #dee2e6;
			padding-bottom: 20px;
			margin-bottom: 25px;
		}

		.header .logo-container {
			display: flex;
			align-items: center;
		}

		.header img {
			height: 55px;
			margin-left: 15px;
		}

		.header h1 {
			margin: 0;
			font-size: 26px;
			font-weight: 700;
			color: #0056b3;
		}

		.header .clinic-info {
			text-align: left;
			font-size: 13px;
			color: #555;
			line-height: 1.7;
		}

		.clinic-info strong {
			font-weight: 600;
			color: #2c3e50;
		}

		/* Patient Info & Diagnosis Section */
		.patient-details-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 20px;
			margin-bottom: 25px;
			padding: 20px;
			background-color: #f8f9fa;
			border-radius: 8px;
		}

		.info-box {
			font-size: 14px;
			line-height: 2;
		}

		.info-box strong {
			color: #0056b3;
			font-weight: 600;
			display: inline-block;
			margin-bottom: 5px;
		}

		/* Treatment Details Section */
		.treatment-section h2 {
			font-size: 20px;
			color: #0056b3;
			border-bottom: 2px solid #dee2e6;
			padding-bottom: 10px;
			margin-bottom: 20px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 25px;
			font-size: 14px;
		}

		table th, table td {
			padding: 14px;
			text-align: right;
			border-bottom: 1px solid #e9ecef;
			vertical-align: middle;
		}

		table th {
			background-color: #f1f3f5;
			color: #343a40;
			font-weight: 600;
		}

		table tr:last-child td { border-bottom: none; }

		.status-badge {
			padding: 5px 12px;
			border-radius: 15px;
			font-weight: 600;
			text-align: center;
			display: inline-block;
			min-width: 100px;
		}

		.status-completed {
			background-color: #d4edda;
			color: #155724;
		}

		.status-scheduled {
			background-color: #fff3cd;
			color: #856404;
		}

		/* Notes Section */
		.notes-section {
			margin-top: 25px;
			padding: 20px;
			background-color: #f8f9fa;
			border-radius: 8px;
			font-size: 13px;
			color: #555;
			line-height: 1.8;
		}
		.notes-section strong {
			color: #0056b3;
			font-size: 14px;
		}

		/* Footer */
		.footer {
			text-align: center;
			padding-top: 25px;
			margin-top: 25px;
			font-size: 13px;
			color: #888;
			border-top: 2px solid #e9ecef;
		}
	</style>
</head>
<body>
<div class="treatment-plan-container">
	<div class="main-content">
		<!-- Header -->
		<header class="header">
			<div class="logo-container">
				<h1>برنامه جامع درمانی</h1>
			</div>
			<div class="clinic-info">
				<img src="<?= base_url() ?>application/views/prints/logo.png" alt="لوگو" />
			</div>
		</header>

		<!-- Patient Info -->
		<section class="patient-details-grid">
			<div class="info-box">
				<strong>نام مریض:</strong> <?= $ci->mylibrary->get_patient_name($profile['name'], $profile['lname'], '', $profile['gender']) ?><br>
				<strong>سریال نمبر:</strong> <bdo dir="ltr"><?= $profile['serial_id'] ?></bdo><br>
			</div>
			<div class="info-box">
				<strong>آدرس:</strong> <?= $profile['address'] ?><br>
				<strong>تلفن تماس:</strong> <bdo dir="ltr">(+93) <?= $profile['phone1'] ?></bdo><br>
			</div>
			<div class="info-box">
				<strong>تاریخ تنظیم طرح:</strong> <bdo dir="ltr"><?= $ci->mylibrary->getCurrentShamsiDate()['date'] ?></bdo>
			</div>
		</section>

		<!-- Treatment Details Table -->
		<section class="treatment-section">
			<h2>جزئیات جلسات درمانی</h2>
			<table>
				<thead>
				<tr>
					<th>جلسه</th>
					<th>شرح اقدام درمانی</th>
					<th>دندان‌ها</th>
					<th>وضعیت</th>
					<th>تاریخ و ساعت نوبت</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($treatment_plan)): ?>
					<?php $session_number = 1; ?>
					<?php foreach ($treatment_plan as $session): ?>
						<tr>
							<td><?= $ci->mylibrary->persion_number($session_number++) ?></td>
							<td>
								<?= htmlspecialchars($session['aggregated_processes'] ?? '---') ?>
							</td>
							<td>
								<bdo dir="ltr"><?= htmlspecialchars($session['aggregated_teeth'] ?? '---') ?></bdo>
							</td>
							<td>
								<?php
								// 'c' means completed, any other status is considered scheduled.
								if ($session['status'] == 'c') {
									echo '<span class="status-badge status-completed">انجام شد</span>';
								} else {
									echo '<span class="status-badge status-scheduled">برنامه ریزی شده</span>';
								}
								?>
							</td>
							<td>
								<bdo dir="ltr">
									<?= htmlspecialchars($session['date'] ?? '') ?> - <?= htmlspecialchars($session['from_time'] ?? '') ?>
								</bdo>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="5" style="text-align: center; padding: 20px;">
							هیچ برنامه درمانی برای این بیمار ثبت نشده است.
						</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</section>

		<!-- Notes Section -->
		<section class="notes-section">
			<strong>توصیه‌ها و نکات مهم:</strong><br>
			- لطفاً ۱۵ دقیقه قبل از زمان تعیین شده در کلینیک حضور داشته باشید.<br>
			- در صورت نیاز به تغییر یا لغو نوبت، حتماً از ۴ ساعت قبل به کلینیک اطلاع دهید.<br>
			- رعایت بهداشت دهان طبق دستورالعمل‌های ارائه شده، برای موفقیت درمان ضروری است.
		</section>

		<!-- Footer -->
		<footer class="footer">
			از انتخاب شما سپاسگزاریم و برایتان آرزوی سلامتی داریم.
		</footer>
	</div>
</div>
</body>
</html>
