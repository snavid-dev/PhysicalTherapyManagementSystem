<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dentist
{
	public $usage_type = 'online';
	public function __construct()
	{
	}


	public function currency()
	{
		$currency = array(
			'a' => 'افغانی',
			't' => 'تومان',
			'dar' => 'درهم',
			'd' => 'دالر'
		);
		return $currency;
	}



	public function assets_url($filepath = null)
	{
		$usage_type = $this->usage_type;
		if (is_null($filepath)) {
			if ($usage_type == 'online') {
				$assets_url = 'https://canin-cdn.cyborgtech.co/';
			} else {
				$assets_url = 'http://localhost/cdn/';
			}
		} else {
			if ($usage_type == 'online') {
				$assets_url = 'https://canin-cdn.cyborgtech.co/' . $filepath;
			} else {
				$assets_url = 'http://localhost/cdn/' . $filepath;
			}
		}

		return $assets_url;
	}


	public function departments_list()
	{
		return array(
			[
				'name' => 'Endodantic'
			],
			[
				'name' => 'restorative'
			], [
				'name' => 'Prosthodontics'
			]
		);
	}

	public function hour_type()
	{
		// it can be full for 1 hour or semi for every 30 minutes
		return 'full';
	}

	public function info()
	{
		$info = array(
			'title' => 'شفاخانه تخصصی ثنا کوثر',
			'sub_title' => 'دیپارتمنت دندان',
			'print_patient_title' => 'دوسیه ثبت مریضان',
			'phone' => "+93 793 14 66 12",
			'tagline' => 'برای شادی و تندرستی خود از سلامت دهان تان مراقبت کنید',
			'address' => 'هرات، چهار راهی نمبر یک',
			'logoName' => 'logo.png',
			'prescriptionName' => 'logo.png',
		);
		return $info;
	}

	public function get_currency($currency)
	{
		switch ($currency) {
			case 'a':
				return 'افغانی';
			case 'd':
				return 'دالر';
			case 't':
				return 'تومان';
			case 'dar':
				return 'درهم';
			case 'k':
				return 'کلدار';
			default:
				return 'افغانی';
		}
	}

	public function customer_name($name, $fname, $page = '')
	{
		$fullname = $name;
		if ($fname !== '') {
			$fullname .= ' - ';
			$fullname .= $fname;
		}
		if ($page !== '' && $page !== '0') {
			$fullname .= ' (';
			$fullname .= $page;
			$fullname .= ')';
		}

		return $fullname;
	}


	public function diseases()
	{
		$ci = get_instance();
		return array(
			ucwords($ci->language->languages('cardiovascular')),
			ucwords($ci->language->languages('rheumatic fever')),
			ucwords($ci->language->languages('kidney')),
			ucwords($ci->language->languages('hepatitis')),
			ucwords($ci->language->languages('diabetes')),
			ucwords($ci->language->languages('High/low blood pressure')),
			ucwords($ci->language->languages('neurotic')),
			ucwords($ci->language->languages('Allergy to drugs')),
			ucwords($ci->language->languages('pregnant')),
		);
	}

	public function canal_names()
	{
		$ci = get_instance();
		return array(
			'central' => ucwords($ci->language->languages('central')),
			'mesial' => ucwords($ci->language->languages('mesial')),
			'distal' => ucwords($ci->language->languages('distal')),
			'lingual' => ucwords($ci->language->languages('lingual')),
			'platal' => ucwords($ci->language->languages('platal')),
			'buccal' => ucwords($ci->language->languages('buccal')),
			'mesobuccal' => ucwords($ci->language->languages('mesobuccal')),
			'mesobuccal2' => ucwords($ci->language->languages('mesobuccal2')),
			'distobuccal' => ucwords($ci->language->languages('distobuccal')),
			'mesolingual' => ucwords($ci->language->languages('mesolingual')),
			'distolingual' => ucwords($ci->language->languages('distolingual')),
			'mesoplatal' => ucwords($ci->language->languages('mesoplatal')),
			'distoplatal' => ucwords($ci->language->languages('distoplatal')),
		);
	}

	public function teeth_type()
	{
		$ci = get_instance();
		return array(
			'porcelain' => ucwords($ci->language->languages('porcelain')),
			'Metal' => ucwords($ci->language->languages('Metal')),
			'Gold' => ucwords($ci->language->languages('Gold')),
			'Partial Mobile' => ucwords($ci->language->languages('Partial Mobile')),
			'Full Mobile' => ucwords($ci->language->languages('Full Mobile')),
		);
	}

	function time_generator($from, $to, $time = 'am')
	{
		$ci = get_instance();
		$key = $from . ',' . $to;
		$from = explode(':', $from);
		$hour_from = $from[0];
		$minute_from = $from[1];
		$to = explode(':', $to);
		$hour_to = $to[0];
		$minute_to = $to[1];
		if ($hour_from > 12 || $hour_to > 12) {
			$time = 'pm';
			if ($hour_from > 12) {
				$hour_from = $hour_from - 12;
			}
			if ($hour_to > 12) {
				$hour_to = $hour_to - 12;
			}
		}
		$final = $hour_from . ':' . $minute_from . ' - ' . $hour_to . ':' . $minute_to . ' ' . $ci->language->languages($time);
		return array('key' => $key, 'value' => $final);
	}

	function teeth_color()
	{
		return array('A1', 'A2', 'A3', 'A3.5', 'B1', 'B2', 'C2', 'A2E', 'OP', 'TL');
	}

	function find_time($key)
	{
		$x = explode(',', $key);
		$from = $x[0];
		$to = $x[1];
		$time = $this->time_generator($from, $to);
		return $time['value'];
	}

	function hours()
	{
		if ($this->hour_type() == 'full') {
			return array(
				$this->time_generator('08:00', '09:00'),
				$this->time_generator('09:00', '10:00'),
				$this->time_generator('10:00', '11:00'),
				$this->time_generator('11:00', '12:00'),
				$this->time_generator('12:00', '13:00'),
				$this->time_generator('13:00', '14:00'),
				$this->time_generator('14:00', '15:00'),
				$this->time_generator('15:00', '16:00'),
				$this->time_generator('16:00', '17:00'),
				$this->time_generator('17:00', '18:00'),
				$this->time_generator('18:00', '19:00'),
				$this->time_generator('19:00', '20:00'),
				$this->time_generator('20:00', '21:00'),
				$this->time_generator('21:00', '22:00'),
				$this->time_generator('22:00', '23:00'),
				$this->time_generator('23:00', '24:00'),
			);
		} else {
			return array(
				$this->time_generator('08:00', '08:30'),
				$this->time_generator('08:30', '09:00'),
				$this->time_generator('09:00', '09:30'),
				$this->time_generator('09:30', '10:00'),
				$this->time_generator('10:00', '10:30'),
				$this->time_generator('10:30', '11:00'),
				$this->time_generator('11:00', '11:30'),
				$this->time_generator('11:30', '12:00'),
				$this->time_generator('12:00', '12:30'),
				$this->time_generator('12:30', '13:00'),
				$this->time_generator('13:00', '13:30'),
				$this->time_generator('13:30', '14:00'),
				$this->time_generator('14:00', '14:30'),
				$this->time_generator('14:30', '15:00'),
				$this->time_generator('15:00', '15:30'),
				$this->time_generator('15:30', '16:00'),
				$this->time_generator('16:00', '16:30'),
				$this->time_generator('16:30', '17:00'),
				$this->time_generator('17:00', '17:30'),
				$this->time_generator('17:30', '18:00'),
				$this->time_generator('18:00', '18:30'),
				$this->time_generator('18:30', '19:00'),
				$this->time_generator('19:00', '19:30'),
				$this->time_generator('19:30', '20:00'),
				$this->time_generator('20:00', '20:30'),
				$this->time_generator('20:30', '21:00'),
				$this->time_generator('21:00', '21:30'),
				$this->time_generator('21:30', '22:00'),
				$this->time_generator('22:00', '22:30'),
				$this->time_generator('22:30', '23:00'),
				$this->time_generator('23:00', '23:30'),
				$this->time_generator('23:30', '24:00'),
			);
		}
	}

	function find_location($location_number)
	{
		$ci = get_instance();
		switch ($location_number) {
			case 1:
				return $ci->language->languages('up right');
			case 2:
				return $ci->language->languages('up left');
			case 3:
				return $ci->language->languages('down right');
			case 4:
				return $ci->language->languages('down left');

			default:
				return $ci->language->languages('error');
		}
	}

	public function medicine_usage_type()
	{
		return array(
			'IV' => 'IV',
			'IM' => 'IM',
			'ORAL' => 'ORAL',
			"ORAL_BEFORE_MEAL" => "ORAL/P.C",
			"ORAL_AFTER_MEAL" => "ORAL/A.C",
			'SC' => 'SC',
			'ID' => 'ID',
			'LOCAL' => 'LOCAL',
		);
	}

	public function medicine_usage()
	{
		return array(
			'Cap' => 'Cap',
			'Tab' => 'Tab',
			'Amp' => 'Amp',
			'Syr' => 'Syr',
			'Cream' => 'Cream',
			'Jel' => 'Jel',
			'Oint' => 'Oint',
			'Serum' => 'Serum',
			'Prop' => 'Prop',
			'Volution' => 'Solution',
			'Vial' => 'Vial',
		);
	}

	public function medicine_units()
	{
		return array(
			'g' => 'g',
			'mg' => 'mg',
			'cc' => 'cc',
			'ml' => 'ml',
			'unit' => 'unit',
			'Bottle' => 'Bottle',
			'Tube' => 'Tube',
			'I' => 'I',
		);
	}

	function hour_for_insert()
	{
		$next_hour = date('H') + 1;
		if ($this->hour_type() == 'full') {
			return date('H:00') . ',' . $next_hour . ':00';
		} // if the hour type is semi
		else {
			// if the current hour is between H:00 and H:30
			if (date('H:00') <= date('H:i') && date('H:i') < date('H:30')) {
				return date('H:00') . ',' . date('H:30');
			} // if the current hour is between H:30 and H+1:00
			else {
				return date('H:30') . ',' . $next_hour . ':00';
			}
		}
	}
}
