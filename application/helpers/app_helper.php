<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('app_locale')) {
	function app_locale()
	{
		$ci =& get_instance();
		return $ci->session->userdata('app_locale') ?: 'farsi';
	}
}

if (!function_exists('app_theme')) {
	function app_theme()
	{
		$ci =& get_instance();
		return $ci->session->userdata('app_theme') ?: 'light';
	}
}

if (!function_exists('is_rtl_locale')) {
	function is_rtl_locale($locale = NULL)
	{
		$locale = $locale ?: app_locale();
		return in_array($locale, array('farsi', 'persian', 'fa'), TRUE);
	}
}

if (!function_exists('t')) {
	function t($key)
	{
		$ci =& get_instance();
		$line = $ci->lang->line($key);
		return $line ? $line : $key;
	}
}

if (!function_exists('format_number')) {
	function format_number($number, $decimals = 0)
	{
		$formatted = number_format((float) $number, (int) $decimals);

		if (!is_rtl_locale()) {
			return $formatted;
		}

		return strtr($formatted, array(
			'0' => '۰',
			'1' => '۱',
			'2' => '۲',
			'3' => '۳',
			'4' => '۴',
			'5' => '۵',
			'6' => '۶',
			'7' => '۷',
			'8' => '۸',
			'9' => '۹',
			',' => '٬',
			'.' => '٫',
		));
	}
}

if (!function_exists('format_amount')) {
	function format_amount($number)
	{
		$number = (float) $number;
		$decimals = ((int) $number == $number) ? 0 : 2;
		return format_number($number, $decimals);
	}
}

if (!function_exists('safe_turn_cash_note')) {
	function safe_turn_cash_note($turn_id)
	{
		return t('Cash payment for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_wallet_topup_note')) {
	function safe_turn_wallet_topup_note($turn_id)
	{
		return t('Wallet top-up for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_cash_reversal_note')) {
	function safe_turn_cash_reversal_note($turn_id)
	{
		return t('Reversal of cash payment for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_wallet_topup_reversal_note')) {
	function safe_turn_wallet_topup_reversal_note($turn_id)
	{
		return t('Reversal of wallet top-up for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_patient_payment_note')) {
	function safe_patient_payment_note($payment_id)
	{
		return t('Patient payment') . ' #' . format_number($payment_id);
	}
}

if (!function_exists('safe_patient_wallet_topup_note')) {
	function safe_patient_wallet_topup_note($patient_id)
	{
		return t('Wallet top-up for patient') . ' #' . format_number($patient_id);
	}
}

if (!function_exists('safe_salary_payment_note')) {
	function safe_salary_payment_note($staff_id, $month = NULL)
	{
		$note = t('Salary payment for staff') . ' #' . format_number($staff_id);

		if ($month !== NULL && trim((string) $month) !== '') {
			$display_month = preg_match('/^\d{4}-\d{2}$/', trim((string) $month))
				? gregorian_month_to_shamsi($month)
				: $month;
			$note .= ' ' . t('month') . ' ' . $display_month;
		}

		return $note;
	}
}

if (!function_exists('safe_reference_label')) {
	function safe_reference_label($reference_table, $reference_id)
	{
		$reference_table = trim((string) $reference_table);
		$reference_id = (int) $reference_id;

		if ($reference_id <= 0 || $reference_table === '') {
			return '&mdash;';
		}

		switch ($reference_table) {
			case 'turns':
				return html_escape(t('Turn No.') . ' ' . format_number($reference_id));

			case 'expenses':
				return html_escape(t('Expense No.') . ' ' . format_number($reference_id));

			case 'payments':
				return html_escape(t('Payment No.') . ' ' . format_number($reference_id));

			case 'patient_wallet_transactions':
				return html_escape(t('Wallet Transaction No.') . ' ' . format_number($reference_id));

			case 'staff_salary_payments':
				return html_escape(t('Salary Payment No.') . ' ' . format_number($reference_id));

			default:
				return html_escape($reference_table . ' #' . $reference_id);
		}
	}
}

if (!function_exists('to_shamsi')) {
	function to_shamsi($date, $format = 'Y/m/d')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->to_shamsi($date, $format);
	}
}

if (!function_exists('to_gregorian')) {
	function to_gregorian($shamsi_date)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->to_gregorian($shamsi_date);
	}
}

if (!function_exists('shamsi_today')) {
	function shamsi_today($format = 'Y/m/d')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_today($format);
	}
}

if (!function_exists('shamsi_now')) {
	function shamsi_now($format = 'Y/m/d H:i')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_now($format);
	}
}

if (!function_exists('shamsi_month_range')) {
	function shamsi_month_range($shamsi_year, $shamsi_month)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_month_range($shamsi_year, $shamsi_month);
	}
}

if (!function_exists('gregorian_month_to_shamsi')) {
	function gregorian_month_to_shamsi($gregorian_ym)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->gregorian_month_to_shamsi($gregorian_ym);
	}
}

if (!function_exists('shamsi_month_to_gregorian')) {
	function shamsi_month_to_gregorian($shamsi_ym)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_month_to_gregorian($shamsi_ym);
	}
}

if (!function_exists('print_report_styles')) {
	function print_report_styles()
	{
		$rtl = is_rtl_locale();
		$font_stack = $rtl ? "'Vazir', Tahoma, sans-serif" : "'Inter', 'Segoe UI', Tahoma, sans-serif";
		$dir = $rtl ? 'rtl' : 'ltr';
		$border_side = $rtl ? 'left' : 'right';

		return '
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
	:root {
		--ink: #16212e;
		--muted: #5c6b7a;
		--line: #dde3ea;
		--accent: #0d5c63;
		--accent-tint: #eaf3f2;
		--danger: #a3242c;
		--warn: #9a5b12;
	}
	* { box-sizing: border-box; }
	@page { size: A4; margin: 14mm 12mm; }
	body {
		font-family: ' . $font_stack . ';
		font-size: 12px;
		line-height: 1.5;
		color: var(--ink);
		margin: 0;
		padding: 10mm 12mm 14mm;
		direction: ' . $dir . ';
	}
	.print-toolbar { display: flex; gap: 8px; margin-bottom: 16px; }
	.print-toolbar button {
		display: inline-flex; align-items: center; gap: .4rem;
		border: 1px solid var(--line); background: #fff; color: var(--ink);
		padding: 6px 14px; border-radius: 6px; cursor: pointer; font-size: 12px; font-family: inherit;
	}
	.print-toolbar button.primary { background: var(--accent); border-color: var(--accent); color: #fff; }
	@media print { .print-toolbar { display: none; } }

	.report-letterhead { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px; padding-bottom: 10px; }
	.report-eyebrow { text-transform: uppercase; letter-spacing: .12em; font-size: 9.5px; color: var(--accent); font-weight: 700; margin: 0 0 3px; }
	.report-title { font-size: 19px; font-weight: 700; margin: 0; color: var(--ink); }
	.report-meta { text-align: ' . ($rtl ? 'left' : 'right') . '; font-size: 10px; }
	.report-meta div { margin-bottom: 2px; }
	.report-meta .label { text-transform: uppercase; letter-spacing: .06em; color: var(--muted); font-size: 8.5px; }
	.report-meta .value { color: var(--ink); font-weight: 600; }
	.report-rule { height: 3px; background: var(--accent); border-radius: 2px; margin-bottom: 2px; }
	.report-rule-hair { height: 1px; background: var(--line); margin-bottom: 18px; }

	.stat-strip { display: flex; flex-wrap: wrap; margin-bottom: 18px; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
	.stat-strip .stat { flex: 1 1 0; min-width: 108px; padding: 10px 14px; border-' . $border_side . ': 1px solid var(--line); }
	.stat-strip .stat:last-child { border: none; }
	.stat-label { text-transform: uppercase; letter-spacing: .06em; font-size: 8.5px; color: var(--muted); margin: 0 0 4px; }
	.stat-value { font-size: 15px; font-weight: 700; font-variant-numeric: tabular-nums; color: var(--ink); }
	.stat-value.accent { color: var(--accent); }
	.stat-value.danger { color: var(--danger); }
	.stat-value.warn { color: var(--warn); }
	.stat-hint { font-size: 8.5px; color: var(--muted); margin-top: 3px; }

	.section-title { font-size: 12.5px; font-weight: 700; margin: 0 0 8px; color: var(--ink); }

	table.report-table { width: 100%; border-collapse: collapse; font-size: 10.5px; margin-bottom: 16px; }
	table.report-table th {
		background: var(--accent-tint); color: var(--accent);
		text-transform: uppercase; letter-spacing: .03em; font-size: 9px; font-weight: 700;
		border-bottom: 1.5px solid var(--accent); padding: 7px 8px; text-align: start;
	}
	table.report-table td { border-bottom: 1px solid var(--line); padding: 6px 8px; vertical-align: top; text-align: start; }
	table.report-table tbody tr:nth-child(even) { background: rgba(13, 92, 99, .035); }
	table.report-table tfoot td { border-top: 2px solid var(--ink); border-bottom: none; font-weight: 700; background: var(--accent-tint); padding: 8px; }
	table.report-table .num { text-align: end; font-variant-numeric: tabular-nums; }
	table.report-table .foot-label { display: block; font-size: 8px; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); font-weight: 600; margin-bottom: 1px; }

	.text-danger { color: var(--danger); font-weight: 600; }
	.text-warn { color: var(--warn); font-weight: 600; }
	.badge-pill { display: inline-block; padding: 2px 9px; border-radius: 999px; background: var(--accent-tint); color: var(--accent); font-size: 10px; font-weight: 700; }

	.report-footer { margin-top: 6px; padding-top: 8px; border-top: 1px solid var(--line); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 6px; font-size: 9px; color: var(--muted); }
</style>';
	}
}

if (!function_exists('print_report_toolbar')) {
	function print_report_toolbar()
	{
		return '<div class="print-toolbar">
			<button type="button" class="primary" onclick="window.print()"><i class="bi bi-printer" aria-hidden="true"></i> ' . html_escape(t('dt_print')) . '</button>
			<button type="button" onclick="window.close()"><i class="bi bi-x-lg" aria-hidden="true"></i> ' . html_escape(t('Close')) . '</button>
		</div>';
	}
}

if (!function_exists('print_report_letterhead')) {
	function print_report_letterhead($report_title, $range_label = '', $extra_meta = array())
	{
		$meta_rows = array();

		if ($range_label !== '') {
			$meta_rows[] = array('label' => t('register_date_range'), 'value' => $range_label);
		}

		foreach ((array) $extra_meta as $row) {
			$meta_rows[] = $row;
		}

		$meta_rows[] = array('label' => t('generated_at'), 'value' => shamsi_now());

		ob_start();
		?>
		<div class="report-letterhead">
			<div>
				<p class="report-eyebrow"><?= html_escape(t('clinic_name_print')) ?></p>
				<h1 class="report-title"><?= html_escape($report_title) ?></h1>
			</div>
			<div class="report-meta">
				<?php foreach ($meta_rows as $row) : ?>
					<div><span class="label"><?= html_escape($row['label']) ?>:</span> <span class="value"><?= html_escape($row['value']) ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="report-rule"></div>
		<div class="report-rule-hair"></div>
		<?php
		return ob_get_clean();
	}
}

if (!function_exists('print_report_footer')) {
	function print_report_footer()
	{
		ob_start();
		?>
		<div class="report-footer">
			<span><?= html_escape(t('clinic_name_print')) ?></span>
			<span><?= html_escape(t('generated_at')) ?>: <?= html_escape(shamsi_now()) ?></span>
		</div>
		<?php
		return ob_get_clean();
	}
}

if (!function_exists('print_report_autoprint_script')) {
	function print_report_autoprint_script()
	{
		return '<script>
			window.onload = function() {
				setTimeout(function() { window.print(); }, 500);
			};
		</script>';
	}
}
