<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shamsi
{
	public function to_shamsi($gregorian_date, $format = 'Y/m/d')
	{
		$gregorian_date = trim((string) $gregorian_date);

		if ($gregorian_date === '') {
			return '';
		}

		$datetime = $this->parse_gregorian_datetime($gregorian_date);

		if (!$datetime) {
			return '';
		}

		$jalali = $this->gregorian_to_jalali(
			(int) $datetime->format('Y'),
			(int) $datetime->format('m'),
			(int) $datetime->format('d')
		);

		return $this->format_output($format, array(
			'Y' => str_pad((string) $jalali['year'], 4, '0', STR_PAD_LEFT),
			'm' => str_pad((string) $jalali['month'], 2, '0', STR_PAD_LEFT),
			'd' => str_pad((string) $jalali['day'], 2, '0', STR_PAD_LEFT),
			'H' => $datetime->format('H'),
			'i' => $datetime->format('i'),
			's' => $datetime->format('s'),
		));
	}

	public function to_gregorian($shamsi_date)
	{
		$parsed = $this->parse_shamsi_date($shamsi_date);

		if (!$parsed) {
			return '';
		}

		if (!$this->is_valid_shamsi_date($parsed['year'], $parsed['month'], $parsed['day'])) {
			return '';
		}

		$gregorian = $this->jalali_to_gregorian($parsed['year'], $parsed['month'], $parsed['day']);

		return sprintf('%04d-%02d-%02d', $gregorian['year'], $gregorian['month'], $gregorian['day']);
	}

	public function shamsi_today($format = 'Y/m/d')
	{
		return $this->to_shamsi(date('Y-m-d'), $format);
	}

	public function shamsi_month_range($shamsi_year, $shamsi_month)
	{
		$shamsi_year = (int) $shamsi_year;
		$shamsi_month = (int) $shamsi_month;

		if ($shamsi_year <= 0 || $shamsi_month < 1 || $shamsi_month > 12) {
			return array('from' => '', 'to' => '');
		}

		$last_day = 31;

		while ($last_day >= 29 && !$this->is_valid_shamsi_date($shamsi_year, $shamsi_month, $last_day)) {
			$last_day--;
		}

		if ($last_day < 29) {
			return array('from' => '', 'to' => '');
		}

		return array(
			'from' => $this->to_gregorian(sprintf('%04d/%02d/01', $shamsi_year, $shamsi_month)),
			'to' => $this->to_gregorian(sprintf('%04d/%02d/%02d', $shamsi_year, $shamsi_month, $last_day)),
		);
	}

	public function shamsi_now($format = 'Y/m/d H:i')
	{
		return $this->to_shamsi(date('Y-m-d H:i:s'), $format);
	}

	public function gregorian_month_to_shamsi($gregorian_ym)
	{
		$gregorian_ym = trim((string) $gregorian_ym);

		if (!preg_match('/^(\d{4})-(\d{2})$/', $gregorian_ym, $matches)) {
			return '';
		}

		$year = (int) $matches[1];
		$month = (int) $matches[2];

		if ($month < 1 || $month > 12) {
			return '';
		}

		// Map to the Shamsi month that DOMINATES this Gregorian month by sampling
		// the 15th (mid-month). Keying off the 1st-of-a-Shamsi-month that merely
		// begins inside the Gregorian month was wrong: e.g. June mostly falls in
		// Khordad (month 3) yet Tir (month 4) begins on ~June 22, so the old logic
		// reported month 4. Mid-month sampling reports Khordad as expected and stays
		// consistent with shamsi_month_to_gregorian (which also samples the 15th).
		$shamsi = $this->to_shamsi(sprintf('%04d-%02d-15', $year, $month));

		if ($shamsi === '') {
			return '';
		}

		$parts = explode('/', $shamsi);
		return count($parts) === 3 ? ($parts[0] . '/' . $parts[1]) : '';
	}

	public function shamsi_month_to_gregorian($shamsi_ym)
	{
		$shamsi_ym = $this->normalize_digits(trim((string) $shamsi_ym));

		if (!preg_match('/^(\d{4})[\/-](\d{2})$/', $shamsi_ym, $matches)) {
			return '';
		}

		// Sample the 15th (mid-month) so a Shamsi month maps to the Gregorian month
		// it mostly overlaps, keeping the round-trip with gregorian_month_to_shamsi
		// stable (both directions sample mid-month).
		$gregorian = $this->to_gregorian(sprintf('%04d/%02d/15', (int) $matches[1], (int) $matches[2]));

		return $gregorian === '' ? '' : substr($gregorian, 0, 7);
	}

	protected function parse_gregorian_datetime($value)
	{
		$value = trim((string) $value);
		$formats = array(
			'Y-m-d H:i:s',
			'Y-m-d H:i',
			'Y-m-d',
		);

		foreach ($formats as $format) {
			$datetime = DateTime::createFromFormat($format, $value);
			if ($datetime && $datetime->format($format) === $value) {
				if ($format === 'Y-m-d') {
					$datetime->setTime(0, 0, 0);
				}
				if ($format === 'Y-m-d H:i') {
					$datetime->setTime((int) $datetime->format('H'), (int) $datetime->format('i'), 0);
				}
				return $datetime;
			}
		}

		return FALSE;
	}

	protected function parse_shamsi_date($value)
	{
		$value = $this->normalize_digits(trim((string) $value));

		if ($value === '') {
			return FALSE;
		}

		if (!preg_match('/^(\d{4})[\/-](\d{2})[\/-](\d{2})$/', $value, $matches)) {
			return FALSE;
		}

		return array(
			'year' => (int) $matches[1],
			'month' => (int) $matches[2],
			'day' => (int) $matches[3],
		);
	}

	protected function normalize_digits($value)
	{
		return strtr((string) $value, array(
			'۰' => '0',
			'۱' => '1',
			'۲' => '2',
			'۳' => '3',
			'۴' => '4',
			'۵' => '5',
			'۶' => '6',
			'۷' => '7',
			'۸' => '8',
			'۹' => '9',
			'٠' => '0',
			'١' => '1',
			'٢' => '2',
			'٣' => '3',
			'٤' => '4',
			'٥' => '5',
			'٦' => '6',
			'٧' => '7',
			'٨' => '8',
			'٩' => '9',
		));
	}

	protected function format_output($format, $parts)
	{
		$output = '';
		$length = strlen((string) $format);

		for ($i = 0; $i < $length; $i++) {
			$token = $format[$i];
			$output .= array_key_exists($token, $parts) ? $parts[$token] : $token;
		}

		return $output;
	}

	protected function is_valid_shamsi_date($year, $month, $day)
	{
		$year = (int) $year;
		$month = (int) $month;
		$day = (int) $day;

		if ($year <= 0 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
			return FALSE;
		}

		$gregorian = $this->jalali_to_gregorian($year, $month, $day);
		$round_trip = $this->gregorian_to_jalali($gregorian['year'], $gregorian['month'], $gregorian['day']);

		return $round_trip['year'] === $year
			&& $round_trip['month'] === $month
			&& $round_trip['day'] === $day;
	}

	protected function is_gregorian_leap($year)
	{
		return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
	}

	protected function gregorian_to_jalali($gy, $gm, $gd)
	{
		$g_days_in_month = array(31, $this->is_gregorian_leap($gy) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		$gy = (int) $gy - 1600;
		$gm = (int) $gm - 1;
		$gd = (int) $gd - 1;

		$g_day_no = (365 * $gy)
			+ intdiv($gy + 3, 4)
			- intdiv($gy + 99, 100)
			+ intdiv($gy + 399, 400);

		for ($i = 0; $i < $gm; $i++) {
			$g_day_no += $g_days_in_month[$i];
		}

		$g_day_no += $gd;
		$j_day_no = $g_day_no - 79;
		$j_np = intdiv($j_day_no, 12053);
		$j_day_no %= 12053;
		$jy = 979 + (33 * $j_np) + (4 * intdiv($j_day_no, 1461));
		$j_day_no %= 1461;

		if ($j_day_no >= 366) {
			$jy += intdiv($j_day_no - 1, 365);
			$j_day_no = ($j_day_no - 1) % 365;
		}

		for ($i = 0; $i < 11; $i++) {
			$dim = $i < 6 ? 31 : 30;
			if ($j_day_no < $dim) {
				return array(
					'year' => $jy,
					'month' => $i + 1,
					'day' => $j_day_no + 1,
				);
			}
			$j_day_no -= $dim;
		}

		return array(
			'year' => $jy,
			'month' => 12,
			'day' => $j_day_no + 1,
		);
	}

	protected function jalali_to_gregorian($jy, $jm, $jd)
	{
		$jy = (int) $jy - 979;
		$jm = (int) $jm - 1;
		$jd = (int) $jd - 1;

		$j_day_no = (365 * $jy)
			+ (intdiv($jy, 33) * 8)
			+ intdiv(($jy % 33) + 3, 4);

		for ($i = 0; $i < $jm; $i++) {
			$j_day_no += $i < 6 ? 31 : 30;
		}

		$j_day_no += $jd;
		$g_day_no = $j_day_no + 79;

		$gy = 1600 + (400 * intdiv($g_day_no, 146097));
		$g_day_no %= 146097;

		$leap = TRUE;
		if ($g_day_no >= 36525) {
			$g_day_no--;
			$gy += 100 * intdiv($g_day_no, 36524);
			$g_day_no %= 36524;

			if ($g_day_no >= 365) {
				$g_day_no++;
			} else {
				$leap = FALSE;
			}
		}

		$gy += 4 * intdiv($g_day_no, 1461);
		$g_day_no %= 1461;

		if ($g_day_no >= 366) {
			$leap = FALSE;
			$g_day_no--;
			$gy += intdiv($g_day_no, 365);
			$g_day_no %= 365;
		}

		$g_days_in_month = array(31, $leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$gm = 0;

		while ($g_day_no >= $g_days_in_month[$gm]) {
			$g_day_no -= $g_days_in_month[$gm];
			$gm++;
		}

		return array(
			'year' => $gy,
			'month' => $gm + 1,
			'day' => $g_day_no + 1,
		);
	}
}
