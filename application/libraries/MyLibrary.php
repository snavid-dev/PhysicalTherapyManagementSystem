<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mylibrary
{

  private  function div($a, $b)
  {
    return (int) ($a / $b);
  }

  public function hash($field)
  {
    return md5(md5(md5($field) . md5('CdsaffddsafyBOrGTfge$cdasfh')) . 'CyBbgjnuhOrGTe$ch');
  }

  public function hash_session($session)
  {
    return md5(md5(md5($session) . md5('CdsafyBOrGTfge$cdasfh')) . 'CyBOrGTe$ch');
  }

  public function elsewise($status, $equal, $if, $else)
  {
    if ($status == $equal) {
      return $if;
    } else {
      return $else;
    }
  }

  public function btn_group($buttons = null)
  {
    $template = '<div class="g-2">' . $buttons . '</div>';
    return $template;
  }

  public function generateBtnDelete($id, $urls, $tableId = null, $extraFunctions = null)
  {
    $ci = get_instance();
    $url = base_url($urls);
    if (is_null($tableId)) {
      $btns = '<a href="javascript:delete_via_alert(' . $id . ', `' . $url . '`)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('delete') . '"><span class="fa fa-trash fs-14"></span></a> ';
    } else {
      if (is_null($extraFunctions)) {
        $btns = '<a href="javascript:delete_via_alert(' . $id . ', `' . $url . '`, `' . $tableId . '`)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('delete') . '"><span class="fa fa-trash fs-14"></span></a> ';
      } else {
        $btns = '<a href="javascript:delete_via_alert(' . $id . ', `' . $url . '`, `' . $tableId . '`, ' . $extraFunctions . ')" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('delete') . '"><span class="fa fa-trash fs-14"></span></a> ';
      }
    }
    return $btns;
  }
  public function generateBtnDeleteMultiDataTable($id, $urls, $tableId = null, $extraFunctions = null)
  {
    $ci = get_instance();
    $url = base_url($urls);
    $btns = '<a href="javascript:delete_via_alert(' . $id . ', `' . $url . '`, `' . $tableId . '`, null, true)" class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('delete') . '"><span class="fa fa-trash fs-14"></span></a> ';
    return $btns;
  }


  public function generateBtnAccept($id, $urls, $tableId = null, $extraFunctions = null)
  {
    $ci = get_instance();
    $url = base_url($urls);
    if (is_null($tableId)) {
      $btns = '<a href="javascript:accept_via_alert(' . $id . ', `' . $url . '`)" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('accept') . '"><span class="fa fa-check-circle fs-14"></span></a> ';
    } else {
      if (is_null($extraFunctions)) {
        $btns = '<a href="javascript:accept_via_alert(' . $id . ', `' . $url . '`, `' . $tableId . '`)" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('accept') . '"><span class="fa fa-check-circle fs-14"></span></a> ';
      } else {
        $btns = '<a href="javascript:accept_via_alert(' . $id . ', `' . $url . '`, `' . $tableId . '`, ' . $extraFunctions . ')" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('accept') . '"><span class="fa fa-check-circle fs-14"></span></a> ';
      }
    }
    return $btns;
  }

  function generateSelect2($modal_id = null, $selectid = null)
  {
    if (is_null($modal_id)) {
      return "select_with_search();";
    } elseif (!is_null($selectid)) {
      return "initializeSelect2('$modal_id', '$selectid');";
    } else {
      return "select_with_search('$modal_id');";
    }
  }

  public function generateBtnStatus($id, $urls, $status = 'p')
  {

    $url = base_url($urls);
    $ci = get_instance();
    if ($status == 'p') {
      $tooltip = $ci->lang('accept');
      $icon =  'fa fa-check-circle fs-14';
    } else {
      $tooltip = $ci->lang('cancel');
      $icon = 'las la-times-circle fs-14';
    }
    $btns = '<a href="javascript:changeStatus(`' . $id . '`, `' . $url . '`)" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $tooltip . '"><span class="' . $icon . '"></span></a> ';
    return $btns;
  }


  public function generateBtnUpdate($edit_function = 'edit_service', $id)
  {
    $ci = get_instance();
    $btns = '<a href="javascript:' . $edit_function . '(`' . $id . '`)" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="fa fa-edit fs-14"></span></a> ';
    return $btns;
  }

  public function generateBtnBan($id, $urls = '', $edit_function = 'xhrChangeStatusUsers')
  {
    $url = base_url($urls);
    $ci = get_instance();
    $btns = '<a href="javascript:' . $edit_function . '(`' . $url . '`, `' . $id . '`, `P`)" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="fa fa-minus-circle fs-14"></span></a> ';
    return $btns;
  }

  public function generateBtnUnlock($id, $urls = '', $edit_function = 'xhrChangeStatusUsers')
  {
    $url = base_url($urls);
    $ci = get_instance();
    $btns = '<a href="javascript:' . $edit_function . '(`' . $url . '`, `' . $id . '`, `A`)" class="btn btn-icon btn-outline-primary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="fa fa-unlock fs-14"></span></a> ';
    return $btns;
  }

  public function generateUserBadge($status = 'P')
  {
    if (ucfirst($status) == 'A') {
      $class = 'bg-success-transparent text-success';
    } else {
      $class = 'bg-danger-transparent text-danger';
    }
    $ci = get_instance();
    $btns = '<span class="badge ' . $class . ' rounded-pill  p-2 px-3"> ' . $this->check_status($status) . '</span>';
    return $btns;
  }



  public function generateBtnPayment($id, $edit_function = 'tyrnPayment')
  {
    $ci = get_instance();
    $btns = '<a href="javascript:' . $edit_function . '(`' . $id . '`)" class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="fa fa-money fs-14"></span></a> ';
    return $btns;
  }

  public function generateBtnCall($id, $type = 'call1', $class = 'btn-outline-success', $disabled = false, $btnType = 'call')
  {
    $ci = get_instance();
    if ($btnType == 'call') {
      if ($disabled == true) {
        $classname = 'disabled';
      } else {
        $classname = '';
      }
      $btns = '<a href="javascript:callModal(`' . $id . '`, `' . $type . '`)" class="btn btn-icon ' . $class . ' rounded-pill btn-wave waves-effect waves-light ' . $classname . '" data-bs-toggle="tooltip"><span class="fa fa-phone fs-14"></span></a> ';
    } else {
      $btns = '<a href="javascript:eye(`' . $id . '`, `' . $type . '`)" class="btn btn-icon ' . $class . ' rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="fa fa-eye fs-14"></span></a> ';
    }
    return $btns;
  }


  public function generateBtnProfilePatient($patient_id)
  {
    $ci = get_instance();
    $btns = '<a href="' . base_url() . 'admin/single_patient/' . $patient_id . '" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-user-circle-o fs-14"></span></a> ';
    return $btns;
  }


  public function generateBtnView($edit_function = 'edit_service', $id)
  {
    $ci = get_instance();
    $btns = '<a href="javascript:' . $edit_function . '(`' . $id . '`)" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip"><span class="las la-eye fs-14"></span></a> ';
    return $btns;
  }

  public function generateBtnDownload($filename, $download_url = null)
  {
    if (is_null($download_url)) {
      $download_url = base_url('patient_files/');
    }
    $ci = get_instance();
    $btns = '<a href="' . $download_url . $filename . '" target="_blank" class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span class="fa fa-download"></span></a> ';
    return $btns;
  }


  public function generateBtnPrint($print_function = 'edit_service', $id)
  {
    $ci = get_instance();
    $btns = '<a href="javascript:' . $print_function . '(`' . $id . '`)" class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="' . $ci->lang('print') . '"><span class="fa fa-print fs-14"></span></a> ';
    return $btns;
  }

  public function getCurrentShamsiDate()
  {
    $dateTime = explode(' ', date("Y-m-d H:i:s"));
    $date = explode('-', $dateTime[0]);

    $date = $this->gregorian_to_jalali($date[0], $date[1], $date[2], "/");
    $dateupdate = explode('/', $date);

    $year = $dateupdate[0];
    $month = ($dateupdate[1] > 9) ? $dateupdate[1] : '0' . $dateupdate[1];
    $day = ($dateupdate[2] > 9) ? $dateupdate[2] : '0' . $dateupdate[2];


    $dateupdate = $year . '/' . $month . '/' . $day;

    $time = explode(':', $dateTime[1]);
    $hours = $time[0];
    $minets = $time[1];
    $seconds = $time[2];

    $ampm = $hours >= 12 ? 'PM' : 'AM';
    $hours = $hours % 12;
    $hours = $hours ? $hours : 12;
    $time = $hours . ':' . $minets . ':' . $seconds . ' ' . $ampm;

    $serial = substr($year, 2) . '_' . $month . '_';

    return array('date' => $dateupdate, 'time' => $time, 'serial' => $serial);
  }

  private  function gregorian_to_jalali($g_y, $g_m, $g_d, $str)
  {
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);


    $gy = $g_y - 1600;
    $gm = $g_m - 1;
    $gd = $g_d - 1;

    $g_day_no = 365 * $gy + $this->div($gy + 3, 4) - $this->div($gy + 99, 100) + $this->div($gy + 399, 400);

    for ($i = 0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
    if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
      /* leap and after Feb */
      $g_day_no++;
    $g_day_no += $gd;

    $j_day_no = $g_day_no - 79;

    $j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
    $j_day_no = $j_day_no % 12053;

    $jy = 979 + 33 * $j_np + 4 * $this->div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */

    $j_day_no %= 1461;

    if ($j_day_no >= 366) {
      $jy += $this->div($j_day_no - 1, 365);
      $j_day_no = ($j_day_no - 1) % 365;
    }

    for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
      $j_day_no -= $j_days_in_month[$i];
    $jm = $i + 1;
    $jd = $j_day_no + 1;
    if ($str)
      return $jy . '/' . $jm . '/' . $jd;
    return array($jy, $jm, $jd);
  }
  public  function getCurrentShamsiDate_back()
  {
    $dateTime = explode(' ', date("Y-m-d H:i:s"));
    $date = explode('-', $dateTime[0]);

    $date = $this->gregorian_to_jalali($date[0], $date[1], $date[2], "/");
    $dateupdate = explode('/', $date);

    $year = $dateupdate[0];
    $month = ($dateupdate[1] > 9) ? $dateupdate[1] : '0' . $dateupdate[1];
    $day = ($dateupdate[2] > 9) ? $dateupdate[2] : '0' . $dateupdate[2];


    $dateupdate = $year . '_' . $month . '_' . $day;

    $time = explode(':', $dateTime[1]);
    $hours = $time[0];
    $minets = $time[1];
    $seconds = $time[2];

    $ampm = $hours >= 12 ? 'PM' : 'AM';
    $hours = $hours % 12;
    $hours = $hours ? $hours : 12;
    $time = $hours . ':' . $minets . ':' . $seconds . ' ' . $ampm;
    return array('date' => $dateupdate, 'time' => $time);
  }

  public function check_theme()
  {
    if (!isset($_COOKIE['color'])) {
      setcookie('color', 'light', time() + (86400 * 30), "/");
    }

    if (!isset($_COOKIE['menu'])) {
      setcookie('menu', 'top', time() + (86400 * 30), "/");
    }

    if (!isset($_COOKIE['full_screen'])) {
      setcookie('full_screen', true, time() + (86400 * 30), "/");
    }
  }

  public function check_status($status = 'P')
  {
    $ci = get_instance();
    if (ucwords($status) == 'P') {
      return $ci->language->languages('pending');
    } elseif (ucwords($status) == 'A') {
      return $ci->language->languages('accepted');
    } elseif (ucwords($status) == 'B') {
      return $ci->language->languages('blocked');
    }
  }

  public function list_user_type()
  {
    return array(
      'A' => 'admin',
      'U' => 'user',
      'D' => 'doctor'
    );
  }

  public function list_account_type()
  {
    return array(
      'l' => 'laboratory',
      'm' => 'expenses',
    );
  }

  public function check_account_type($type = 'l')
  {
    switch (ucfirst($type)) {
      case 'M':
        return 'expenses';
      case 'L':
        return 'laboratory';

      default:
        return 'expenses';
    }
  }


  public function check_user_type($type = 'A')
  {
    switch (ucfirst($type)) {
      case 'D':
        return 'doctor';
      case 'U':
        return 'user';

      default:
        return 'admin';
    }
  }

  public function account_name($name, $fname, $role = '')
  {
    $fullname = $name;
    if ($fname !== '') {
      $fullname .= ' - ';
      $fullname .= $fname;
    }
    if ($role !== '' && $role !== '') {
      $fullname .= ' (';
      $ci = get_instance();
      $role = $ci->language->languages($this->check_user_type($role));
      $fullname .= $role;
      $fullname .= ')';
    }

    return $fullname;
  }

  public function user_name($fname, $lname, $role = '')
  {
    $fullname = $fname;
    if ($fname !== '') {
      $fullname .= ' - ';
      $fullname .= $lname;
    }
    if ($role !== '' && $role !== '') {
      $fullname .= ' (';
      $ci = get_instance();
      $role = $ci->language->languages($this->check_user_type($role));
      $fullname .= $role;
      $fullname .= ')';
    }

    return $fullname;
  }

  public function finance_account_name($name, $fname, $role = '')
  {
    $fullname = $name;
    if ($fname !== '') {
      $fullname .= ' - ';
      $fullname .= $fname;
    }
    if ($role != '' && $role != '') {
      $fullname .= ' (';
      $ci = get_instance();
      $role = $ci->language->languages($this->check_account_type($role));
      $fullname .= $role;
      $fullname .= ')';
    }
    return $fullname;
  }

  public function check_cr_dr($cr_amount, $dr_amount, $type = 'type')
  {
    $ci = get_instance();
    if ($type == 'type') {
      if (!is_null($cr_amount) && $cr_amount != 0) {
        return $ci->language->languages('cr');
      } elseif (!is_null($dr_amount) && $dr_amount != 0) {
        return $ci->language->languages('dr');
      } else {
        return $ci->language->languages('error');
      }
    } elseif ($type == 'amount') {
      if (!is_null($cr_amount) && $cr_amount !== 0) {
        return $cr_amount;
      } elseif (!is_null($dr_amount) && $dr_amount !== 0) {
        return $dr_amount;
      } else {
        return $ci->language->languages('error');
      }
    }
  }

  public function get_patient_name($name, $lname, $serial_id = '', $gender = '')
  {
    $fullname = '';
    if ($gender !== '') {
      $ci = get_instance();

      if ($gender == 'm') {
        $fullname .= $ci->language->languages('Mr');
      } else {
        $fullname .= $ci->language->languages('Ms');
      }
    }


    $fullname .= $name;
    if ($lname !== '') {
      $fullname .= ' - ';
      $fullname .= $lname;
    }
    if ($serial_id !== '') {
      $fullname .= ' (';
      $fullname .= $serial_id;
      $fullname .= ')';
    }

    return $fullname;
  }

  public static function themes($theme)
  {
    switch ($theme) {
      case 'dark':
        setcookie('color', 'dark', time() + (86400 * 30), "/");
        header('Location: ../');
        break;

      case 'light':
        setcookie('color', 'light', time() + (86400 * 30), "/");
        header('Location: ../');
        break;

      case 'full_screen':
        setcookie('full_screen', true, time() + (86400 * 30), "/");
        header('Location: ../');
        break;

      case 'border_less':
        setcookie('full_screen', 'border_less', time() + (86400 * 30), "/");
        header('Location: ../');
        break;

      case 'right':
        setcookie('menu', 'right', time() + (86400 * 30), "/");
        header('Location: ../');
        break;

      case 'top':
        setcookie('menu', 'top', time() + (86400 * 30), "/");
        header('Location: ../');
        break;


      default:
        setcookie('color', 'light', time() + (86400 * 30), "/");
        header('Location: ../');
        break;
    }
  }

  public function number_company($phone_number)
  {
    $company_number = substr($phone_number, 4, 1);
    $company = 'unknown';
    if ($company_number == 9 || $company_number == 2) {
      $company = 'roshan';
    } elseif ($company_number == 8 || $company_number == 3) {
      $company = 'etisalat';
    } elseif ($company_number == 6) {
      $company = 'mtn';
    } elseif ($company_number == 0) {
      $company = 'awcc';
    }
    return $company;
  }



  public function sendSms($to = null, $message, $from = null)
  {
//    $ci = get_instance();
//    $smsinfo = $ci->dentist->smsinfo();
//    if ($smsinfo['status'] == 'active') {
//      $apiKey = $smsinfo['api_key'];
//
//      if ($from == null) {
//        $company_name = $this->number_company($to);
//        $clinic_numbers = $smsinfo['phones'];
//        if ($company_name == 'roshan' && $clinic_numbers['roshan'] != '') {
//          $from = $clinic_numbers['roshan'];
//        } elseif ($company_name == 'etisalat' && $clinic_numbers['etisalat'] != '') {
//          $from = $clinic_numbers['etisalat'];
//        } elseif ($company_name == 'awcc' && $clinic_numbers['awcc'] != '') {
//          $from = $clinic_numbers['awcc'];
//        } elseif ($company_name == 'mtn' && $clinic_numbers['mtn'] != '') {
//          $from = $clinic_numbers['mtn'];
//        }
//      }
//
//      $options = array(
//        'http' => array(
//          'method'  => 'POST',
//          'content' => json_encode([
//            'content' => $message,
//            'from'    => $from, // Put the correct phone number here
//            'to'      => $to  // Put the correct phone number here
//          ]),
//          'header' =>  "Content-Type: application/json\r\n" .
//            "Accept: application/json\r\n" .
//            "x-api-key: $apiKey\r\n"
//        )
//      );
//
//      $context  = stream_context_create($options);
//      $result = file_get_contents("https://api.httpsms.com/v1/messages/send", false, $context);
//
//      if ($result !== '') {
//        return true;
//      } else {
//        false;
//      }
//    } else {
//      return true;
//    }
	  return true;
  }

  public function script_datepicker($input_date_id = 'test-date-id-date', $container = 'div#main-div', $variable_name = 'input_date')
  {
    $x = "let ";
    $x .= $variable_name;
    $x .= " = document.getElementById('$input_date_id');";
    $x .= $variable_name;
    $x .= ".addEventListener('click', function() {
      console.log('-------------------------',jalaliDatepicker);
      jalaliDatepicker.startWatch({
              'container': '$container'
      });
      jalaliDatepicker.show(";
    $x .= $variable_name;

    $x .= ");
  });
  ";
    return $x;
  }
}
