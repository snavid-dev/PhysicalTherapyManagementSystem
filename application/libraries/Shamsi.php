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

		$days_in_month = (int) date('t', strtotime(sprintf('%04d-%02d-01', $year, $month)));

		for ($day = 1; $day <= $days_in_month; $day++) {
			$shamsi = $this->to_shamsi(sprintf('%04d-%02d-%02d', $year, $month, $day));
			if ($shamsi === '') {
				continue;
			}

			$parts = explode('/', $shamsi);
			if (count($parts) === 3 && $parts[2] === '01') {
				return $parts[0] . '/' . $parts[1];
			}
		}

		$fallback = $this->to_shamsi(sprintf('%04d-%02d-01', $year, $month));

		if ($fallback === '') {
			return '';
		}

		$parts = explode('/', $fallback);
		return count($parts) === 3 ? ($parts[0] . '/' . $parts[1]) : '';
	}

	public function shamsi_month_to_gregorian($shamsi_ym)
	{
		$shamsi_ym = $this->normalize_digits(trim((string) $shamsi_ym));

		if (!preg_match('/^(\d{4})[\/-](\d{2})$/', $shamsi_ym, $matches)) {
			return '';
		}

		$gregorian = $this->to_gregorian(sprintf('%04d/%02d/01', (int) $matches[1], (int) $matches[2]));

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
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if ($this->is_gregorian_leap($gy)) {
			$g_days_in_month[1] = 29;
		}

		if ($gy > 1600) {
			$jy = 979;
			$gy -= 1600;
		} else {
			$jy = 0;
			$gy -= 621;
		}

		$gy2 = $gm > 2 ? $gy + 1 : $gy;
		$days = (365 * $gy)
			+ intdiv($gy2 + 3, 4)
			- intdiv($gy2 + 99, 100)
			+ intdiv($gy2 + 399, 400)
			- 80
			+ $gd;

		for ($i = 0; $i < $gm - 1; $i++) {
			$days += $g_days_in_month[$i];
		}

		$jy += 33 * intdiv($days, 12053);
		$days %= 12053;
		$jy += 4 * intdiv($days, 1461);
		$days %= 1461;

		if ($days > 365) {
			$jy += intdiv($days - 1, 365);
			$days = ($days - 1) % 365;
		}

		if ($days < 186) {
			$jm = 1 + intdiv($days, 31);
			$jd = 1 + ($days % 31);
		} else {
			$jm = 7 + intdiv($days - 186, 30);
			$jd = 1 + (($days - 186) % 30);
		}

		return array(
			'year' => $jy,
			'month' => $jm,
			'day' => $jd,
		);
	}

	protected function jalali_to_gregorian($jy, $jm, $jd)
	{
		$jy = (int) $jy;
		$jm = (int) $jm;
		$jd = (int) $jd;

		if ($jy > 979) {
			$gy = 1600;
			$jy -= 979;
		} else {
			$gy = 621;
		}

		$days = (365 * $jy)
			+ (intdiv($jy, 33) * 8)
			+ intdiv(($jy % 33) + 3, 4)
			+ 78
			+ $jd;

		if ($jm < 7) {
			$days += ($jm - 1) * 31;
		} else {
			$days += (($jm - 7) * 30) + 186;
		}

		$gy += 400 * intdiv($days, 146097);
		$days %= 146097;

		if ($days > 36524) {
			$days--;
			$gy += 100 * intdiv($days, 36524);
			$days %= 36524;

			if ($days >= 365) {
				$days++;
			}
		}

		$gy += 4 * intdiv($days, 1461);
		$days %= 1461;

		if ($days > 365) {
			$gy += intdiv($days - 1, 365);
			$days = ($days - 1) % 365;
		}

		$gd = $days + 1;
		$g_days_in_month = array(31, $this->is_gregorian_leap($gy) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$gm = 1;

		foreach ($g_days_in_month as $days_in_month) {
			if ($gd <= $days_in_month) {
				break;
			}

			$gd -= $days_in_month;
			$gm++;
		}

		return array(
			'year' => $gy,
			'month' => $gm,
			'day' => $gd,
		);
	}
}
