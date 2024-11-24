<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Language
{
	public function __construct()
	{
	}

	public function startup($language = 'fa')
	{
		if (!isset($_COOKIE['language'])) {
			setcookie('language', $language, time() + (86400 * 30), "/");
		}
	}

	public static function change($lang)
	{
		switch ($lang) {
			case 'fa':
				setcookie('language', 'fa', time() + (86400 * 30), "/");
				if (isset($_SERVER["HTTP_REFERER"])) {
					header("Location: " . $_SERVER["HTTP_REFERER"]);
				}
				break;

			case 'en':
				setcookie('language', 'en', time() + (86400 * 30), "/");
				if (isset($_SERVER["HTTP_REFERER"])) {
					header("Location: " . $_SERVER["HTTP_REFERER"]);
				}
				break;

			case 'pa':
				setcookie('language', 'pa', time() + (86400 * 30), "/");
				if (isset($_SERVER["HTTP_REFERER"])) {
					header("Location: " . $_SERVER["HTTP_REFERER"]);
				}
				break;


				setcookie('menu', 'top', time() + (86400 * 30), "/");
				header('Location: ../');
				break;


			default:
				setcookie('language', 'fa', time() + (86400 * 30), "/");
				if (isset($_SERVER["HTTP_REFERER"])) {
					header("Location: " . $_SERVER["HTTP_REFERER"]);
				}
				break;
		}
	}


	public function languages($key = 'language', $lan = null, $amount = 0)
	{
		if (isset($_COOKIE['language'])) {

			if (is_null($lan)) {
				$lan = $_COOKIE['language'];
			}
		} else {
			$lan = 'fa';
		}
		$lang = [
			//switcher start
			'vertical menu' => self::lang_arr('Vertical Menu', 'منو عمودی', 'عمودی مینو'),
			'horizontal click' => self::lang_arr('Horizontal Click Menu', 'منوی کلیک افقی', 'افقی کلیک مینو'),
			'horizontal hover' => self::lang_arr('Horizontal Hover Menu', 'منوی شناور افقی', 'افقی هور مینو'),
			'navigation style' => self::lang_arr('navigation style', 'استایل منو', 'د مینو سټایل'),

			'horizontal style' => self::lang_arr('HORIZONTAL LAYOUT STYLE', 'استایل چیدمان افقی', 'د افقی ترتیب سټایل'),
			'default logo' => self::lang_arr('default logo', 'لوگو پیش فرض', 'ډیفالټ لوگو'),
			'center logo' => self::lang_arr('center logo', 'لوگوی مرکز', 'د مرکز لوګو'),

			'light theme style' => self::lang_arr('light theme style', 'سبک تم روشن', 'د رڼا ډیزاین سټایل'),
			'light theme' => self::lang_arr('light theme', 'تم روشن', 'د رڼا ډیزاین'),
			'light primary' => self::lang_arr('light primary', 'رنگ اصلی تم روشن', 'د رڼا ډیزاین لومړنی رنګ'),

			'dark theme style' => self::lang_arr('dark theme style', 'سبک تم تاریک', 'تیاره موضوع ډیزاین'),
			'dark theme' => self::lang_arr('dark theme', 'تم تاریک', 'تیاره ډیزاین'),
			'dark primary' => self::lang_arr('dark primary', 'رنگ اصلی تم تاریک', 'تیاره ډیزاین لومړني رنګ'),


			'switcher' => self::lang_arr('switcher', 'تنظیمات', 'ترتیبات'),

			//switcher end

			// Start DataTable

			'search...' => self::lang_arr('search . . .', 'جستجو . . .', 'لټون . . .'),

			// End DataTable

			// Start diseases

			'cardiovascular' => self::lang_arr('Cardiovascular', 'قلبی عروقی', 'د زړه'),
			'rheumatic fever' => self::lang_arr('rheumatic fever', 'تب روماتسمی', 'روماتیکه تبه'),
			'kidney' => self::lang_arr('kidney', 'کلیوی', 'پښتورګي'),
			'hepatitis' => self::lang_arr('hepatitis', 'هپاتیت', 'هیپاتیت'),
			'diabetes' => self::lang_arr('diabetes', 'دیابت', 'د شکرې ناروغي'),
			'High/low blood pressure' => self::lang_arr('High/low blood pressure', 'فشار خون بالا/پایین', 'د وینی لوړ/ ټیټ فشار'),
			'neurotic' => self::lang_arr('neurotic', 'عصبی', 'عصبي'),
			'Allergy to drugs' => self::lang_arr('Allergy to drugs', 'حساسیت به دارو ها', 'د مخدره توکو سره حساسیت'),
			'pregnant' => self::lang_arr('pregnant', 'حامله', 'حامله'),
			// End diseases


			// Start canal names
			'central' => self::lang_arr('central', 'سنترال', 'سنترال'),
			'mesial' => self::lang_arr('mesial', 'میزیال', 'میزیال'),
			'distal' => self::lang_arr('distal', 'دیستال', 'دیستال'),
			'lingual' => self::lang_arr('Lingual', 'لنگوال', 'لنگوال'),
			'platal' => self::lang_arr('platal', 'پلاتال', 'پلاتال'),
			'buccal' => self::lang_arr('buccal', 'بوکال', 'بوکال'),
			'mesobuccal' => self::lang_arr('mesobuccal', 'میزوبوکال', 'میزوبوکال'),
			'mesobuccal2' => self::lang_arr('mesobuccal 2', 'میزو بوکال 2', 'میزو بوکال 2'),
			'distobuccal' => self::lang_arr('distobuccal', 'دیستوبوکال', 'دیستوبوکال'),
			'mesolingual' => self::lang_arr('mesolingual', 'میزولنگوال', 'میزولنگوال'),
			'distolingual' => self::lang_arr('distolingual', 'دیستولنگوال', 'دیستولنگوال'),
			'mesoplatal' => self::lang_arr('mesoplatal', 'میزوپلاتال', 'میزوپلاتال'),
			'distoplatal' => self::lang_arr('distoplatal', 'دیستوپلاتال', 'دیستوپلاتال'),
			// End canal names


			// start status
			'pending' => self::lang_arr('Pending', 'معلق', 'پاتې'),
			'accepted' => self::lang_arr('Accepted', 'پذیرفته شده', 'منل شوی'),
			'blocked' => self::lang_arr('blocked', 'بلاک', 'بند شوی'),
			// end status

			// start users page

			'name' => self::lang_arr('name', 'نام', 'نوم'),
			'lname' => self::lang_arr('last name', 'نام خانوادگی', 'تخلص'),
			'username' => self::lang_arr('username', 'نام کاربری', 'کارن نوم'),
			'status' => self::lang_arr('status', 'وضعیت', 'حالت'),
			'account type' => self::lang_arr('account type', 'نوع حساب', 'د حساب ډول'),
			'actions' => self::lang_arr('actions', 'عملیات', 'کړنې'),
			'ban' => self::lang_arr('ban', 'ممنوع کردن', 'بندیز'),
			'allow' => self::lang_arr('allow', 'اجازه دادن', 'اجازه ورکول'),
			'delete' => self::lang_arr('delete', 'حذف', 'ړنګول'),
			'users list' => self::lang_arr('users list', 'لیست کاربران', 'د کاروونکو لیست'),
			'insert user' => self::lang_arr('insert user', 'افزودن کاربر', 'کارن داخل کړئ'),
			// TODO: pashto not set
			'Crop Image' => self::lang_arr('Crop Image', 'برش تصویر', 'not set'),
			// end users page

			//Role & Perimission
			'role and permission' => self::lang_arr('roles and permissions', 'نقش ها و دسترسی ها', 'رولونه او اجازې'),
			//Role & Perimission


			// Start Patients page

			'patient list' => self::lang_arr('Patients list', 'لیست مریضان', 'د ناروغانو لیست'),
			'serial id' => self::lang_arr('Serial Number', 'نمبر مسلسل', 'د سلسلې نمره'),
			'fullname' => self::lang_arr('full name', 'نام کامل', 'پوره نوم'),
			'phone1' => self::lang_arr('phone number 1', 'شماره تماس 1', 'د تلیفون شمیره 1'),
			'phone2' => self::lang_arr('phone 2', 'شماره تماس 2', 'د تلیفون شمیره 2'),
			'age' => self::lang_arr('age', 'سن', 'عمر'),
			'gender' => self::lang_arr('gender', 'جنسیت', 'جنسیت'),
			'address' => self::lang_arr('address', 'آدرس', 'آدرس'),
			'medical history' => self::lang_arr('medical history', 'تاریخچه طبی', 'طبي تاریخ'),
			'other diseases' => self::lang_arr('Miscellaneous diseases', 'بیماری های متفرقه', 'متفرقه ناروغۍ'),
			'desc' => self::lang_arr('remarks', 'ملاحظات', 'تبصرې'),
			'print' => self::lang_arr('print', 'چاپ', 'پرنت'),

			// end Page patients


			// Start Single Patient page

			'contact information' => self::lang_arr('contact information', 'اطلاعات تماس', 'د اړیکې معلومات'),
			'personal info' => self::lang_arr('Personal Info', 'اطلاعات شخصی', 'شخصي معلومات'),
			'fees' => self::lang_arr('fees', 'فیس', 'فیس'),
			'paid' => self::lang_arr('paid', 'پرداخت شده', 'تادیه شوی'),
			'balance' => self::lang_arr('balance', 'الباقی', 'پاتې پیسې'),
			'update patient success' => self::lang_arr('The Patient Profile was successfully updated', 'پروفایل مریض موفقانه بروزرسانی شد', 'مالي حساب په بریالیتوب سره تازه شو'),
			'payment status' => self::lang_arr('payment status', 'وضعیت مالی', 'د تادیې حالت'),
			// TODO:ask this
			'payment percent status' => self::lang_arr('(' . $amount . '%) is completed', '(' . $amount . '%)پرداخت شده', ' حساب په بریالیتوب سره تازه شو'),

			// end Page Single patient


			// Start users Insert Form
			'image' => self::lang_arr('Image', 'عکس', 'انځور'),
			'select' => self::lang_arr('Select', 'انتخاب', 'وټاکئ'),

			// End users insert form

			// the dxdiag
			'Toggle View' => self::lang_arr('Toggle View', 'حالت نمایش', 'not set'),
			'Simple' => self::lang_arr('Simple', 'ساده', 'not set'),
			'X-Ray' => self::lang_arr('X-Ray', 'X-Ray', 'X-Ray'),

			'department' => self::lang_arr('Department', 'دیپارتمنت', 'څانګې'),
			'Departments' => self::lang_arr('Departments', 'دیپارتمنت ها', 'څانګې'),
			// TODO: what is the Endodantic and also ask about the what should be داخله?
			'Endodantic' => self::lang_arr('Endodantic', 'اندودنتیک', '؟؟؟؟'),
			'surgery' => self::lang_arr('surgery', 'جراحی', 'جراحي'),
			'Prosthodontics' => self::lang_arr('Prosthodontics', 'پرستودنتیک', 'پرستودنتیک'),
			'Orthodontic' => self::lang_arr('Orthodontic', 'ارتودنسی', 'ارتوډونټیک'),
			'Periodontology' => self::lang_arr('Periodontology', 'پریودنتولوژی', 'پریودنتولوژی'),
			'prosthesis' => self::lang_arr('prosthesis', 'پروتز', 'مصنوعي خواږه'),
			'restorative' => self::lang_arr('restorative', 'ترمیمی و زیبایی', 'رغونکی'),

			// End dxdiag

			// Start users type

			'admin' => self::lang_arr('admin', 'مدیر', 'اډمین'),
			'user' => self::lang_arr('user', 'کاربر', 'کارن'),
			'doctor' => self::lang_arr('doctor', 'داکتر', 'ډاکټر'),

			// End users type

			// Start Modal

			'add new' => self::lang_arr('Add New', 'افزودن', 'اضافه کړئ'),

			// End Modal


			// start services page
			'price' => self::lang_arr('price', 'قیمت', 'قیمت'),
			'services list' => self::lang_arr('services list', 'لیست خدمات', 'د خدماتو لیست'),
			'insert service' => self::lang_arr('insert service', 'افزودن خدمات', 'خدمات داخل کړئ'),
			'edit service' => self::lang_arr('edit service', 'ویرایش خدمات', 'د سمون خدمت'),
			'edit' => self::lang_arr('edit', 'ویرایش', 'اصلاح'),
			'treatment' => self::lang_arr('treatment', 'تداوی', 'درملنه'),
			// end services page

			// start accounts page
			'accounts' => self::lang_arr('financial accounts', 'حساب های مالی', 'مالي حسابونه'),
			'accounts list' => self::lang_arr('financial accounts list', 'لیست حساب های مالی', 'د مالي حسابونو لیست'),
			'update account success' => self::lang_arr('The account was successfully updated', 'حساب مالی موفقانه بروزرسانی شد', 'مالي حساب په بریالیتوب سره تازه شو'),
			'insert account' => self::lang_arr('insert financial account', 'افزودن حساب های مالی', 'مالي حسابونه داخل کړئ'),
			'phone' => self::lang_arr('phone number', 'شماره تماس', 'د تلیفون شمیره'),
			'edit financial account' => self::lang_arr('edit financial account', 'ویرایش حساب های مالی', 'مالي حسابونه ایډیټ کړئ'),
			'expenses' => self::lang_arr('expenses', 'مصارف', 'لګښتونه'),
			'finance account has receipts' => self::lang_arr('this account has financial receipts', 'این حساب دارای رسیدهای مالی است', 'دا حساب مالي رسیدونه لري'),
			'edit account' => self::lang_arr('edit account', 'ویرایش حساب', 'د سمون حساب'),
			'delete account' => self::lang_arr('finance account was deleted successfully', 'حساب مالی با موفقیت حذف شد', 'مالي حساب په بریالیتوب سره ړنګ شو'),
			// end accounts page


			// Start Receipts Page
			'account' => self::lang_arr('financial account', 'حساب مالی', 'مالي حساب'),
			'type' => self::lang_arr('type', 'نوعیت', 'ډول'),
			'amount' => self::lang_arr('amount', 'مقدار', 'اندازه'),
			'date' => self::lang_arr('date', 'تاریخ', 'نیټه'),
			'cr' => self::lang_arr('Deposit', 'پرداخت ', 'تادیه'),
			'dr' => self::lang_arr('Withdraw', 'برداشت', 'ویستل'),
			'description' => self::lang_arr('description', 'توضیحات', 'تفصیل'),
			'insert receipt' => self::lang_arr('insert receipt', 'افزودن رسیدات مالی', 'مالي رسیدونه اضافه کړئ'),
			'update receipt' => self::lang_arr('update receipt', 'ویرایش رسیدات مالی', 'مالي رسیدونه تازه کړئ'),
			// End Receipts page


			// Start Medicine Page
			'medicines' => self::lang_arr('medicines', 'دوا ها', 'داروګانې'),
			'medicines list' => self::lang_arr('medicines list', 'لیست دوا ها', 'د درملو لیست'),
			'insert medicine' => self::lang_arr('insert medicine', 'افزودن دوا', 'درمل داخل کړئ'),
			'edit medicine' => self::lang_arr('edit medicine', 'ویرایش دوا', 'درمل تازه کړئ'),
			'Medicine Type' => self::lang_arr('Type', 'نوع', 'ډول'),
			'Medicine Name' => self::lang_arr('Name', 'نام', 'نوم'),
			'Medicine Unit' => self::lang_arr('Unit', 'واحد', 'واحد'),
			'Medicine Doze' => self::lang_arr('Doze', 'دوز', 'دوز'),
			'Medicine Usage' => self::lang_arr('Usage', 'طرز استفاده', 'کارول'),
			'Day' => self::lang_arr('Day', 'روز', 'ورځ'),
			'Time' => self::lang_arr('Time', 'ساعت', 'وخت'),
			'medicine delete not allowed' => self::lang_arr('Because this medicine is prescribed in one of the prescriptions, you are not able to remove it', 'به دلیل اینکه این دوا در یکی از نسخه ها تجویز شده است شما قادر به حذف آن نیستید', 'ناروغ معلومات لري له همدې امله تاسو اجازه نلرئ چې دا ناروغ حذف کړئ'),
			'delete medicine' => self::lang_arr('medicine deleted successfully', 'دوا موفقانه حذف شد', 'ناروغ په بریالیتوب سره حذف شو'),
			'insert medicine success' => self::lang_arr('The medicine was successfully added', 'دوا موفقانه افزوده شد', 'درمل په بریالیتوب سره اضافه شو'),
			'update medicine success' => self::lang_arr('The medicine was successfully updated', 'دوا موفقانه بروزرسانی شد', 'درمل په بریالیتوب سره تازه شو'),
			// End Medicine Page


			// Start Prescriptions

			'prescription' => self::lang_arr('prescription', 'نسخه', 'نسخې'),
			'insert prescription' => self::lang_arr('insert prescription', 'افزودن نسخه', 'نسخې داخل کړئ'),
			'add' => self::lang_arr('add', 'افزودن', 'اضافه کول'),
			'remove' => self::lang_arr('remvoe', 'حذف', 'لرې کړئ'),
			'date and time' => self::lang_arr('date and time', 'تاریخ و زمان', 'نیټه او وخت'),

			//End Prescriptions

			// Start Files

			'title' => self::lang_arr('title', 'عنوان', 'عنوان'),
			'insert files' => self::lang_arr('insert file', 'درج فایل', 'فایل داخل کړئ'),
			'select file' => self::lang_arr('select file', 'انتخاب فایل', 'فایل غوره کړئ'),
			'insert file success' => self::lang_arr('The Patient File was successfully inserted', 'فایل مریض موفقانه افزوده شد', 'د ناروغ فایل په بریالیتوب سره داخل شو'),

			//End Files


			// medicine errors
			'insert file title error' => self::err_gen('title', 'عنوان', 'عنوان'),
			'insert file category error' => self::err_gen('category', 'دسته بندی', 'not set'),
			'empty file error' => self::lang_arr('file field should not be empty', 'فیلد فایل نباید خالی باشد', 'د فایل ساحه باید خالي نه وي'),
			// end medicine errors

			// medicine errors
			'insert medicine type error' => self::err_gen('type', 'نوع', 'ډول'),
			'insert medicine name error' => self::err_gen('medicine name', 'نام دوا', 'د درملو نوم'),
			// end medicine errors

			// Start Turns Page
			'insert turn' => self::lang_arr('insert turn', 'افزودن نوبت', 'وار اضافه کړئ'),
			'update turn' => self::lang_arr('update turn', 'ویرایش نوبت', 'وار تازه کړئ'),
			// TODO what is the meaning of this word (لست نوب ها)?
			'turns list' => self::lang_arr('List of turns', 'لیست نوبت ها', 'مالي حساب'),
			'select turn' => self::lang_arr('select turns', 'انتخاب نوبت ها', 'مالي حساب'),
			// ---------------------------------------------------------------------------------------------------------------------------------------------------------turns clould not had translated yet
			'turn' => self::lang_arr('Queue', 'نوبت ', 'مالي حساب'),
			'patient name' => self::lang_arr('Patient Name', 'نام مریض', 'د ناروغ نوم'),
			'reference doctor' => self::lang_arr('reference doctor', 'داکتر مربوطه', 'حواله ډاکټر'),
			'hour' => self::lang_arr('Time', 'ساعت', 'وخت'),
			'paid amount' => self::lang_arr('paid amount', 'مقدار پرداخت شده', 'تادیه شوی مقدار'),
			// End Turns page

			// Start Payment
			'insert payment' => self::lang_arr('add payment', 'افزودن پول پرداختی', 'تادیه اضافه کړئ'),
			'insert payment turn error' => self::err_gen('Select Turn', 'انتخاب نوبت', '??????'),
			'insert payment amount error' => self::err_gen('Paid amount', 'مقدار پرداختی ', 'قدار پرداختی '),
			'insert payment success' => self::lang_arr('The Patient payment was successfully inserted', 'پرداخت مریض موفقانه افزوده شد', ' حساب په بریالیتوب سره اضافه شو'),
			// End Payment


			// Patients Expressions
			'Mr' => self::lang_arr('Mr. ', 'آقای ', 'ښاغلی '),
			'Ms' => self::lang_arr('Ms. ', 'خانم ', 'اغلی'),

			'male' => self::lang_arr('male', 'مذکر', 'نارینه'),
			'female' => self::lang_arr('female', 'مونث', 'ښځینه'),
			'teeth' => self::lang_arr('teeth', 'دندان ها', 'غاښونه'),
			'turns' => self::lang_arr('turns', 'نوبت ها', '??????'),
			'archive' => self::lang_arr('Archive', 'آرشیف', 'آرشیف'),
			'experiments' => self::lang_arr('experiments', 'آزمایشات', 'آزمایشات'),

			'block' => self::lang_arr('block', 'معلق کردن', 'بند کول'),
			'diagnose' => self::lang_arr('diagnose', 'تشخیص', 'تشخیص کول'),
			// End Patients Expressions

			// start time expressions
			'am' => self::lang_arr('A.M', 'ق.ظ', 'ق.ظ'),
			'pm' => self::lang_arr('P.M', 'ب.ظ', 'ب.ظ'),
			// End time expressions


			// start tooth expressions
			'up right' => self::lang_arr('upper right', 'بالا-راست', 'پورتنۍ ښي'),
			'up left' => self::lang_arr('upper left', 'بالا-چپ', 'پورتنۍ چپه'),
			'down left' => self::lang_arr('down left', 'پایین-چپ', 'ښکته چپه'),
			'down right' => self::lang_arr('down right', 'پایین-راست', 'ښکته ښیې'),
			'tooth location' => self::lang_arr('tooth location', 'موقعیت دندان', 'د غاښ موقعیت'),
			'tooth name' => self::lang_arr('tooth name', 'نام دندان', 'د غاښ نوم'),
			'number of canal' => self::lang_arr('number of canal', 'تعداد کانال ها', 'د کانال شمیر'),
			'canal location' => self::lang_arr('canal location', 'موقعیت کانال', 'د کانال موقعیت'),
			'canal length' => self::lang_arr('canal length', 'طول کانال', 'د کانال اوږدوالی'),
			'pay amount' => self::lang_arr('pay amount', 'مقدار پرداختی', 'د تادیاتو اندازه'),
			'type of irrigation' => self::lang_arr('Type of Irrigation', 'نوع اریگیشن', 'Type of Irrigation'),
			'type of sealer' => self::lang_arr('Type of Sealer', 'نوع سیلر', 'Type of Sealer'),
			'type of obturation' => self::lang_arr('Type of Obturation', 'نوع آبجوریشن', 'Type of Obturation'),
			// End tooth expressions


			// Start tooth expressions
			'Insert Tooth' => self::lang_arr('Insert Tooth', 'افزودن دندان', 'غاښ دننه کړئ'),
			'Edit Tooth' => self::lang_arr('Edit Tooth', 'ویرایش دندان', ' غاښ تازه کړئ'),
			'Edit Profile' => self::lang_arr('Edit Profile', 'ویرایش پروفایل', 'پروفایل تازه کول'),
			'Insert Child\'s Teeth' => self::lang_arr('Insert Child\'s Teeth', 'افزودن دندان اطفال', 'د ماشومانو غاښ اضافه کول'),
			'Edit Child\'s Teeth' => self::lang_arr('Edith Child\'s Teeth', 'ویرایش دندان اطفال', 'د ماشومانو غاښ تازه کول'),
			// End tooth expressions

			// start teeth type

			'porcelain' => self::lang_arr('porcelain', 'پرسلنت', 'پرسلنت'),
			'Metal' => self::lang_arr('Metal', 'میتال', 'میتال'),
			'Gold' => self::lang_arr('Gold', 'گلد', 'گلد'),
			'Partial Mobile' => self::lang_arr('Partial Mobile', 'پارشیل متحرک', 'پارشیل ګرځنده'),
			'Full Mobile' => self::lang_arr('Full Mobile', 'متحرک کامل', 'بشپړه ګرځنده'),

			// end teeth type

			// Start Laboratory expressions
			'tooth type' => self::lang_arr('tooth type', 'نوعیت دندان', 'د غاښ ډول'),
			'delivery date' => self::lang_arr('delivery date', 'تاریخ تحویل گیری', 'د سپارنې نېټه'),
			'delivery time' => self::lang_arr('delivery time', 'ساعت تحویل گیری', 'د سپارنې وخت'),
			'tooth color' => self::lang_arr('tooth color', 'رنگ دندان', 'د غاښ رنګ'),
			'insert laboratory expenses' => self::lang_arr('insert laboratory expenses', 'افزودن مصارف لابراتوار', 'د لابراتوار لګښتونه داخل کړئ'),
			// TODO: pashto not set
			'Number of units' => self::lang_arr('Number of units', 'تعداد واحد', 'د لابراتوار لګښتونه داخل کړئ'),
			// end Laboratory expressions

			'language' => self::lang_arr('english', 'فارسی', 'پښتو'),
			'wrong user or pass' => self::lang_arr('your username or password is wrong', 'نام کاربری یا رمز عبور شما اشتباه است', 'ستاسو کارن نوم یا پټنوم غلط دی'),
			'system name' => self::lang_arr('Expert and research system of Crown', 'سیستم تخصصی و تحقیقی دندان تاج', 'د تاج متخصص او څیړنیز سیسټم'),
			'general' => self::lang_arr('main', 'عمومی', 'عمومی'),
			'logout' => self::lang_arr('logout', 'خروج', 'وتل'),
			'password' => self::lang_arr('password', 'رمز عبور', 'رمز'),
			'old password' => self::lang_arr('old password', 'رمز عبور قدیمی', 'not set'),
			'new password' => self::lang_arr('new password', 'رمز عبور جدید', 'not set'),
			'confirm password' => self::lang_arr('confirm password', 'تائید رمز عبور', 'پټنوم تایید کړئ'),
			'confirm new password' => self::lang_arr('confirm new password', 'تائید رمز عبور جدید', 'not set'),
			'change password' => self::lang_arr('modify password', 'تغییر رمز عبور', 'not set'),
			'users' => self::lang_arr('users', 'کاربران', 'کاروونکي'),
			'main' => self::lang_arr('main', 'اصلی', 'اصلي'),
			'home' => self::lang_arr('Home', 'خانه', 'کور'),
			'search' => self::lang_arr('search', 'جستجو', 'کور'),
			'scan' => self::lang_arr('scan', 'اسکن', 'کور'),
			'patients' => self::lang_arr('patients', 'مریضان', 'ناروغان'),
			'services' => self::lang_arr('services', 'خدمات', 'خدمتونه'),
			'finances' => self::lang_arr('finances', 'امور مالی', 'مالي'),
			'laboratory' => self::lang_arr('laboratory', 'لابراتوار', 'لابراتوار'),
			'financial accounts' => self::lang_arr('financial accounts', 'حساب های مالی', 'مالي حسابونه'),
			'reports' => self::lang_arr('reports', 'گزارشات', 'راپورونه'),
			'receipts' => self::lang_arr('receipts', 'رسیدات مالی', 'رسیدونه'),
			'manage' => self::lang_arr('manage', 'مدیریت', 'اداره کول'),
			'back to home' => self::lang_arr('Back to Home', 'بازگشت به خانه', 'بیرته کور ته'),
			'404 error' => self::lang_arr('Sorry, an error has occured, Requested page not found!', 'متاسفیم! کدام مشکلی پیش آمده است، صفحه مورد نظر یافت نشد!', 'بخښنه غواړم، یوه تېروتنه رامنځته شوه، غوښتنه شوې پاڼه ونه موندل شوه!'),
			'login' => self::lang_arr('Login', 'ورود', 'دننه کیدل'),
			'sms' => self::lang_arr('sms', 'پیام', 'پیغام'),
			'sms sent' => self::lang_arr('sms successfully sent', 'پیام موفقانه ارسال شد', 'پیغام په بریالیتوب سره لیږل شوی'),
			'sign up' => self::lang_arr('Sign Up', 'ثبت نام', 'Sign Up'),
			'save' => self::lang_arr('save changes', 'ذخیره', 'save changes'),
			'save and print' => self::lang_arr('save and print', 'ذخیره و چاپ', 'خوندي او چاپ کړئ'),
			'choose language' => self::lang_arr('choose language', 'انتخاب زبان', 'ژبه غوره کړه'),

			'update' => self::lang_arr('update', 'به روز رسانی', 'تازه کول'),
			'not member' => self::lang_arr('Not a member?', 'تا به حال عضو نیستید؟', 'غړی نه دی؟'),
			'accept' => self::lang_arr('accept', 'پذیرفتن', 'accept'),

			// Start Alerts
			'error' => self::lang_arr('warning', 'هشدار', 'اخطار'),
			'yes' => self::lang_arr('yes', 'بله', 'هو'),
			'cancel' => self::lang_arr('cancel', 'لغو', 'لغوه کول'),
			'cancel all' => self::lang_arr('cancel all', 'لغو همه', 'لغوه کول'),
			'select all' => self::lang_arr('select all', 'انتخاب همه', 'لغوه کول'),
			'deselect all' => self::lang_arr('deselect all', 'لغو انتخاب همه', 'لغوه کول'),
			'delete alert title' => self::lang_arr('are you sure?', 'آیا شما مطمئن هستید؟', 'تاسو ډاډه یاست؟'),
			'delete alert text' => self::lang_arr('If it is deleted, it will not be returned!', 'اگر حذف شود دیگر برگردانده نمیشود!', 'که دا حذف شي، دا به بیرته نه راځي!'),
			'success' => self::lang_arr('success', 'موفقیت', 'بریالی'),
			'delete service' => self::lang_arr('Service deleted successfully', 'خدمات موفقانه حذف شد', 'خدمت په بریالیتوب سره حذف شو'),
			'update service success' => self::lang_arr('The service was successfully updated', 'خدمات موفقانه بروزرسانی شد', 'خدمت په بریالیتوب سره تازه شو'),
			'insert service success' => self::lang_arr('The service was successfully added', 'خدمات موفقانه افزوده شد', 'خدمت په بریالیتوب سره اضافه شو'),
			'delete receipt' => self::lang_arr('Receipt deleted successfully', 'رسید مالی موفقانه حذف شد', 'رسید په بریالیتوب سره حذف شو'),
			'delete lab' => self::lang_arr('Laboratory expense deleted successfully', 'مصارف لابراتوار موفقانه حذف شد', 'رسید په بریالیتوب سره حذف شو'),
			// TODO:could not translated
			'delete turn' => self::lang_arr('turn deleted successfully', 'نوبت موفقانه حذف شد', '????'),
			'accept turn' => self::lang_arr('turn accepted successfully', 'نوبت موفقانه پذیرفته شد', '????'),
			// End Alerts

			// Start Primary Information

			'primary information' => self::lang_arr('primary information', 'اطلاعات اولیه', 'لومړني معلومات'),
			'diagnoses' => self::lang_arr('diagnoses', 'تشخیص ها', 'تشخیص'),
			// TODO: Pashto not set
			'insert restorative data' => self::lang_arr('insert restorative data', '  افزودن اطلاعات ترمیمی', 'not set'),
			'update restorative data' => self::lang_arr('update restorative data', '  ویرایش اطلاعات ترمیمی', 'not set'),
			'insert Endo data' => self::lang_arr('insert Endodantic data', ' افزودن اطلاعات داخله', 'not set'),
			'Update Endo data' => self::lang_arr('Update Endodantic data', ' به روز رسانی اطلاعات داخله', 'not set'),
			'choose the data type' => self::lang_arr('choose the data you want to insert', 'انتخاب نوع اطلاعات مورد نظر', 'not set'),
			'categories' => self::lang_arr('categories', 'دسته بندی ها', 'not set'),
			'category' => self::lang_arr('category', 'دسته بندی', 'not set'),

			// End Primary Information


			// start Diagnose

			'diagnose delete not allowed' => self::lang_arr('Because the diagnosis is used for teeth, you are not allowed to remove it!', 'به این دلیل که تشخیص برای دندان ها استفاده شده شما مجاز به حذف آن نیستید!', 'ځکه چې تشخیص د غاښونو لپاره کارول کیږي، تاسو د دې لرې کولو اجازه نه لرئ!'),
			'delete diagnose' => self::lang_arr('diagnose deleted successfully', 'تشخیص موفقانه حذف شد', 'تشخیص په بریالیتوب سره حذف شو'),
			'insert diagnose' => self::lang_arr('insert diagnose', 'افزودن تشخیص', 'تشخیص داخل کړئ'),
			'update diagnose' => self::lang_arr('edit diagnose', 'ویرایش تشخیص', 'د سمون تشخیص'),
			'insert diagnose success' => self::lang_arr('The diagnose was successfully added', 'تشخیص موفقانه افزوده شد', 'تشخیص په بریالیتوب سره اضافه شو'),

			// End Diagnose


			// diagnose errors

			'insert diagnose name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert diagnose name error unique' => self::err_gen('name', 'نام', 'نوم', 'unique'),

			// end diagnose errors

			// services errors
			'insert service name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert service name error unique' => self::err_gen('name', 'نام', 'نوم', 'unique'),
			'insert service error price' => self::err_gen('price', 'قیمت', 'قیمت'),
			'insert service error department' => self::err_gen('department', 'دیپارتمنت', 'not set'),
			'problem' => self::lang_arr('something went wrong!!', 'کدام مشکلی پیش آمده است', 'کومه تیروتنه وشوه'),
			'services delete not allowed' => self::lang_arr('Because the service is used for teeth, you are not allowed to remove it!', 'به این دلیل که تداوی برای دندان ها استفاده شده شما مجاز به حذف آن نیستید!', 'not set'),
			// end services errors


			// categories errors
			'insert categories name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert categories name error unique' => self::err_gen('name', 'نام', 'نوم', 'unique'),
			'insert categories error type' => self::err_gen('category type', 'نوع کتگوری', 'not set'),
			'category delete not allowed' => self::lang_arr('The category is not allowed to be removed because it is used in the archive or teeth section!', 'این دسته به دلیل استفاده در بخش آرشیف یا دندان مجاز به حذف نیست!', 'کټګورۍ د لرې کولو اجازه نلري ځکه چې دا د آرشیف یا غاښونو برخه کې کارول کیږي!'),
			'delete categories' => self::lang_arr('category deleted successfully', 'دسته بندی موفقانه حذف شد', 'کتگوری په بریالیتوب سره حذف شو'),
			'update categories success' => self::lang_arr('The categories was successfully updated', 'کتگوری موفقانه بروزرسانی شد', 'کتگوری په بریالیتوب سره تازه شو'),
			// end categories errors


			// basic teeth info errors
			'insert basic_teeth_info name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert basic_teeth_info error department' => self::err_gen('department', 'دیپارتمنت', 'not set'),
			'insert basic_teeth_info name error unique' => self::err_gen('name', 'نام', 'نوم', 'unique'),
			'insert basic_teeth_info error categories_id' => self::err_gen('Category', 'کتگوری', 'not set'),
			'delete basic_teeth_info' => self::lang_arr('information deleted successfully', 'اطلاعات موفقانه حذف شد', 'not set'),
			'insert basic_teeth_info success' => self::lang_arr('The information was successfully inserted', 'اطلاعات موفقانه ذخیره شد', 'not set'),
			'update basic_teeth_info success' => self::lang_arr('The information was successfully updated', 'اطلاعات موفقانه بروزرسانی شد', 'not set'),
			'basic_teeth_info delete not allowed' => self::lang_arr('Because the Basic information was used for teeth, you are not allowed to remove it!', 'از آنجایی که اطلاعات پایه برای دندان استفاده شده است، شما مجاز به حذف آن نیستید!', 'not set'),
			// end basic teeth info errors

			// account errors
			'insert account name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert account error type' => self::err_gen('account type', 'نوع حساب', 'د حساب ډول'),
			'insert account success' => self::lang_arr('The Account was successfully added', 'حساب مالی موفقانه افزوده شد', 'حساب په بریالیتوب سره اضافه شو'),
			// end account errors

			// turn errors
			'insert turn patient_id error' => self::err_gen('patient', 'مریض', 'ناروغ'),
			'insert turn doctor_id error' => self::err_gen('reference doctor', 'داکتر مربوطه', 'حواله ډاکټر'),
			'insert turn date error' => self::err_gen('date', 'تاریخ', 'نیټه'),
			'insert turn hour error' => self::err_gen('hour', 'ساعت', 'ساعت'),
			// TODO: could not be treanslated
			'insert turn success' => self::lang_arr('The turn was successfully added', 'نوبت موفقانه افزوده شد', '????'),
			'turn already taken' => self::lang_arr('On the selected date and time, the relevant doctor has a patient', 'در تاریخ و ساعت انتخاب شده داکتر مربوطه مریض دارد', 'په ټاکل شوې نیټه او وخت کې، اړوند ډاکټر ناروغ لري'),
			'update turn success' => self::lang_arr('The turn was successfully updated', 'نوبت موفقانه بروزرسانی شد', '????'),
			// end turn errors

			// lab errors
			'insert lab customers_id error' => self::err_gen('laboratory', 'لابراتوار', 'لابراتوار'),
			'insert lab teeth error' => self::err_gen('teeth', 'دندان ها', 'غاښونه'),
			'insert lab type error' => self::err_gen('tooth type', 'نوعیت دندان', 'د غاښونو ډول'),
			'insert lab give_date error' => self::err_gen('delivery date', 'تاریخ تحویل دهی', 'د سپارنې نېټه'),
			'insert lab hour error' => self::err_gen('delivery time', 'ساعت تحویل دهی', 'د لېږدون وخت'),
			'insert lab color error' => self::err_gen('teeth color', 'رنگ دندان ها', 'د غاښونو رنګ'),
			'insert lab dr error' => self::err_gen('pay amount', 'مقدار پرداختی', 'پیسې ورکول'),
			'insert lab number of unit error' => self::err_gen('number of unit', 'تعداد واحد', 'د واحد شمیر'),
			'insert lab success' => self::lang_arr('The Laboratory expense was successfully inserted', 'مصارف لابراتوار موفقانه افزوده شد', 'د لابراتوار لګښت په بریالیتوب سره داخل شو'),
			'update lab success' => self::lang_arr('The Laboratory expense was successfully updated', 'مصارف لابراتوار موفقانه بروزرسانی شد', 'د لابراتوار لګښت په بریالیتوب سره تازه شو'),

			//end lab errors

			// receipt errors
			'insert receipt customers_id error' => self::err_gen('name', 'نام', 'نوم'),
			'insert receipt type error' => self::err_gen('type', 'نوعیت', 'ډول'),
			'insert receipt amount error' => self::err_gen('amount', 'مقدار', 'مقدار'),
			'insert receipt date error' => self::err_gen('date', 'تاریخ', 'نیټه'),
			'insert receipt success' => self::lang_arr('The Receipt was successfully added', 'رسید مالی موفقانه افزوده شد', 'حساب په بریالیتوب سره اضافه شو'),
			'update receipt success' => self::lang_arr('The Receipt was successfully update', 'رسید مالی موفقانه بروز رسانی شد', 'حساب په بریالیتوب سره تازه شو'),
			// end receipt errors

			// Patient errors
			'insert patient' => self::lang_arr('insert patient', 'افزودن مریض', 'ناروغ داخل کړئ'),
			'insert patient name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert patient lname error' => self::err_gen('lastname', 'نام خانواده گی', 'تخلص'),
			'insert patient phone1 error' => self::err_gen('phone number', 'شماره تماس', 'د تلیفون شمیره'),
			'insert patient age error' => self::err_gen('age', 'سن', 'عمر'),
			'insert patient gender error' => self::err_gen('gender', 'جنسیت', 'جنسیت'),
			'insert patient doctor_id error' => self::err_gen('reference doctor', 'داکتر مربوطه', 'حواله ډاکټر'),
			'insert patient success' => self::lang_arr('The Patient was successfully added', 'مریض موفقانه افزوده شد', 'ناروغ په بریالیتوب سره اضافه شو'),
			'patient delete not allowed' => self::lang_arr('Patient has data that\'s why you\'re not allowed to delete this patient', 'مریض اطلاعات دارد به همین دلیل شما مجاز به حذف این مریض نیستید', 'ناروغ معلومات لري له همدې امله تاسو اجازه نلرئ چې دا ناروغ حذف کړئ'),
			'delete patient' => self::lang_arr('Patient deleted successfully', 'مریض موفقانه حذف شد', 'ناروغ په بریالیتوب سره حذف شو'),
			'accept patient' => self::lang_arr('The patient status has been successfully changed to Completed', 'وضعیت مریض موفقانه به حالت تمام شده تغییر داده شد', 'د ناروغ حالت په بریالیتوب سره بشپړ شوی ته بدل شوی'),
			'block patient' => self::lang_arr('The patient status has been successfully changed to Blocked', 'وضعیت مریض موفقانه به حالت معلق تغییر داده شد', 'د ناروغ حالت په بریالیتوب سره بلاک شوی ته بدل شوی'),
			'pending patient' => self::lang_arr('The patient status has been successfully changed to pending', 'وضعیت مریض موفقانه به حالت تمام نشده تغییر داده شد', 'د ناروغ حالت په بریالیتوب سره په انتظار کې بدل شوی'),
			'patient payment remain' => self::lang_arr('The patient has (' . number_format($amount) . ') Afghanis of residuals', 'مریض مقدار (' . number_format($amount) . ') افغانی باقیمانده مالی دارد', 'ناروغ ته (' . number_format($amount) . ') افغانۍ پاتې دي'),
			// end Patient errors

			// tooth errors
			'insert tooth name error' => self::err_gen('tooth name', 'نام دندان', 'د غاښ نوم'),
			'insert tooth patient_id error' => self::err_gen('Patient information', 'اطلاعات مریض', 'د ناروغ معلومات'),
			'insert tooth services error' => self::err_gen('services', 'خدمات', 'خدمتونه'),
			'insert tooth location error' => self::err_gen('tooth location', 'موقعیت دندان', 'د غاښ ځای'),
			'insert tooth diagnose error' => self::err_gen('diagnose', 'تشخیص', 'تشخیص کول'),
			'insert tooth price error' => self::err_gen('pay amount', 'مقدار پرداختی', 'پیسې ورکول'),
			'insert tooth success' => self::lang_arr('The tooth was successfully added', 'دندان موفقانه افزوده شد', 'غاش په بریالیتوب سره اضافه شو'),
			'update tooth success' => self::lang_arr('The tooth was successfully updated', 'دندان موفقانه بروزرسانی شد', 'غاش په بریالیتوب سره تازه شو'),
			'delete tooth' => self::lang_arr('tooth was deleted successfully', 'دندان با موفقیت حذف شد', 'غاښ په بریالیتوب سره حذف شوی'),
			// end tooth errors


			// Prescription errors
			'insert prescription medicine error' => self::err_gen('medicine name', 'نام دوا', 'د درملو نوم'),
			'insert prescription unit error' => self::err_gen('unit', 'واحد', 'واحد'),
			'insert prescription doze error' => self::err_gen('doze', 'دوز', 'دوز'),
			'insert prescription day error' => self::err_gen('day', 'روز', 'ورځ'),
			'insert prescription time error' => self::err_gen('time', 'ساعت', 'وخت'),
			'insert prescription amount error' => self::err_gen('amount', 'مقدار', 'مقدار'),
			'insert prescription success' => self::lang_arr('The prescription was successfully added', 'نسخه   موفقانه افزوده شد', 'نسخه په بریالیتوب سره اضافه شوه'),
			// end Prescription errors

			// user errors
			'insert user name error' => self::err_gen('name', 'نام', 'نوم'),
			'insert user image error' => self::err_gen('profile image', 'تصویر پروفایل', 'د پروفایل انځور'),
			'insert user lname error' => self::err_gen('lastname', 'نام خانواده گی', 'تخلص'),
			'insert user username error' => self::err_gen('username', 'نام کاربری', 'کارن نوم'),
			'insert user username unique error' => self::err_gen('username', 'نام کاربری', 'کارن نوم', 'unique'),
			'insert user role error' => self::err_gen('account type', 'نوعیت حساب کاربری', 'د حساب ډول'),
			'insert user status error' => self::err_gen('status', 'وضعیت', 'حالت'),
			'insert user password error' => self::err_gen('password', 'رمز عبور', 'رمز'),
			'insert user confirm error' => self::err_gen('confirm password', 'تائید رمز عبور', 'پاسورډ تایید کړه'),
			'insert user confirm matches error' => self::err_gen('The password confirmation field must be the same as the password', 'قسمت تائید رمز عبور با رمز عبور باید یکسان باشد', 'د پټنوم تایید ساحه باید د پټنوم په څیر وي'),
			'insert user success' => self::lang_arr('The User was successfully added', 'حساب کاربری موفقانه افزوده شد', 'کارن په بریالیتوب سره اضافه شو'),
			'user delete not allowed' => self::lang_arr('user has data that\'s why you\'re not allowed to delete this user', 'کاربر اطلاعات دارد به همین دلیل شما مجاز به حذف این کاربر نیستید', 'کارن معلومات لري له همدې امله تاسو اجازه نلرئ چې دا کارن حذف کړئ'),
			'user edit not allowed' => self::lang_arr('You are not allow to edit your user', 'شما مجاز به ویرایش حساب خود نیست', 'not set'),
			'user status changed' => self::lang_arr('user status changed successfully', 'وضعیت کاربر موفقانه تغییر کرد', 'not set'),
			'delete user' => self::lang_arr('user deleted successfully', 'کاربر موفقانه حذف شد', 'کارن په بریالیتوب سره حذف شو'),

			// end user errors


			// Start Reports
			'report receipts' => self::lang_arr('report receipts', 'گزارش رسیدات مالی', 'د راپور رسیدونه'),
			'report' => self::lang_arr('report', 'گزارش ', 'راپور'),
			'To Date' => self::lang_arr('To Date', 'به تاریخ ', 'تر نیټې'),
			'From Date' => self::lang_arr('From Date', 'از تاریخ ', 'له نیټې څخه'),
			'Sum Deposit' => self::lang_arr('Sum Deposit', 'مجموعه پرداخت', 'د تادیاتو ټول مجموعه'),
			'Sum Withdraw' => self::lang_arr('Sum Withdraw', 'مجموعه برداشت', 'د وتلو ټوله مجموعه'),
			'Balance' => self::lang_arr('Balance', 'بیلانس', 'بیلانس'),
			'today balance' => self::lang_arr('Today\'s balance', 'بیلانس روز', 'نن ورځ توازن'),
			'income' => self::lang_arr('income', 'عواید', 'عایدات'),
			'report patients' => self::lang_arr('financial Patients', 'مالی مریضان', 'مالي ناروغان'),
			// End Reports

			// Random Start
			'Submit' => self::lang_arr('Submit', 'ارسال', 'سپارل'),

			// Random End

			// financial tables start

			'Revenue' => self::lang_arr('Revenue', 'عواید', 'عایدات'),
			'Expenses' => self::lang_arr('Expenses', 'مصارف', 'لګښتونه'),

			'Patient Name and Surname' => self::lang_arr('Patient\'s Full Name', 'نام و تخلص مریض', 'د ناروغ نوم او تخلص'),

			'Tooth Position and Name' => self::lang_arr('Tooth Position and Name', 'نام و موقیعت دندان', 'د غاښونو موقعیت او نوم'),

			'none' => self::lang_arr('none', 'هیچکدام', 'هیڅ نه'),

			'all' => self::lang_arr('all', 'همه', 'ټول'),

			// financial tables end

			// other treatment start
			'other treatments' => self::lang_arr('other treatment', 'سایر درمان ها', '?????'),
			// other treatment end

			// failed Call
			'Failed Call Info' => self::lang_arr('Failed Call Info', 'اطلاعات تماس های ناموفق', 'ناکامه تلیفون معلومات'),
			'Call Failure Reason' => self::lang_arr('Call Failure Reason', 'علت تماس ناموفق', 'د کال د ناکامۍ دلیل'),
			// failed Call

			// phonebook information
			'phonebook' => self::lang_arr('call log', 'تاریخچه تماس', 'د تلیفون کتاب'),
			// end phonebook information

			//Perimissions
			'enter the role name' => self::lang_arr('enter the role name', 'نام نقش را وارد کنید', 'د رول نوم دننه کړئ'),
			'role name' => self::lang_arr('role name', 'نام نقش', 'د رول نوم'),
			//Perimissions

			//Perimissions Categories
			'Patient Management' => self::lang_arr('Patient Management', 'مدیریت بیماران	', 'د ناروغان مدیریت'),
			'Turn Management' => self::lang_arr('Turn Management', 'مدیریت نوبت', 'د نوبت مدیریت'),
			'Financial Management' => self::lang_arr('Financial Management', 'مدیریت مالی', 'مالي مدیریت'),
			'Reporting' => self::lang_arr('Reporting', 'گزارش‌دهی	', 'راپور ورکول'),
			'Communication Management' => self::lang_arr('Communication Management', 'مدیریت ارتباطات', 'د اړیکو مدیریت'),
			'Service Management' => self::lang_arr('Service Management', 'مدیریت خدمات', 'د خدمتونو مدیریت'),
			'User Management' => self::lang_arr('User Management', 'مدیریت کاربران', 'د کاروونکو مدیریت'),
			'Patient Profile Details' => self::lang_arr('Patient Profile Details', 'جزئیات پروفایل بیمار	', 'د ناروغ د پروفایل جزئیات'),
			//Perimissions Categories

			//List of Perimissions
			'Create Patient' => self::lang_arr('Create Patient', 'ایجاد بیمار', 'د ناروغ جوړول'),
			'Read Patients' => self::lang_arr('Read Patients', 'خواندن بیماران', 'د ناروغان لوستل'),
			'Read Patient Profile' => self::lang_arr('Read Patient Profile', 'خواندن پروفایل بیمار', 'د ناروغ پروفایل لوستل'),
			'Update Patient Acceptance' => self::lang_arr('Update Patient Acceptance', 'به‌روزرسانی پذیرش بیمار', 'د ناروغ د منلو تازه کول'),
			'Update Blocked Patient' => self::lang_arr('Update Blocked Patient', 'به‌روزرسانی بیمار مسدود شده', 'بند شوی ناروغ تازه کول'),
			'Delete Patient' => self::lang_arr('Delete Patient', 'حذف بیمار', 'د ناروغ حذف کول'),
			'Create New Patient' => self::lang_arr('Create New Patient', 'ایجاد بیمار جدید', 'نوی ناروغ جوړول'),
			'Update Personal Information' => self::lang_arr('Update Personal Information', 'به‌روزرسانی اطلاعات شخصی', 'شخصي معلومات تازه کول'),
			'Delete Teeth' => self::lang_arr('Delete Teeth', 'حذف دندان‌ها', 'غاښونه حذف کړئ'),
			'Delete Personal Turn' => self::lang_arr('Delete Personal Turn', 'حذف نوبت شخصی', 'شخصي نوبت حذف کړئ'),
			'Create Turn' => self::lang_arr('Create Turn', 'ایجاد نوبت', 'نوبت جوړول'),
			'Read Today\'s Turn List' => self::lang_arr('Read Today\'s Turn List', 'خواندن لیست نوبت‌های امروز', 'د نن ورځې د نوبتونو لیست لوستل'),
			'Read Turns Index' => self::lang_arr('Read Turns Index', 'خواندن شاخص نوبت‌ها', 'د نوبت شاخص لوستل'),
			'Create New Turn' => self::lang_arr('Create New Turn', 'ایجاد نوبت جدید', 'نوې نوبت جوړول'),
			'Read Sent SMS' => self::lang_arr('Read Sent SMS', 'خواندن پیامک‌های ارسال‌شده', 'لیږل شوي SMS لوستل'),
			'Update Turn Acceptance' => self::lang_arr('Update Turn Acceptance', 'به‌روزرسانی پذیرش نوبت', 'د نوبت منلو تازه کول'),
			'Read Printed Turns' => self::lang_arr('Read Printed Turns', 'خواندن نوبت‌های چاپ‌شده', 'چاپ شوي نوبتونه لوستل'),
			'Update Personal Turn' => self::lang_arr('Update Personal Turn', 'به‌روزرسانی نوبت شخصی', 'شخصي نوبت تازه کول'),
			'Create Expenses' => self::lang_arr('Create Expenses', 'ایجاد هزینه‌ها', 'لګښتونه جوړ کړئ'),
			'Read Today\'s Balance Sheet' => self::lang_arr('Read Today\'s Balance Sheet', 'خواندن ترازنامه امروز', 'د نن ورځې د بیلانس پاڼه لوستل'),
			'Create Payment' => self::lang_arr('Create Payment', 'ایجاد پرداخت', 'تادیه جوړول'),
			'Read Printed Payment' => self::lang_arr('Read Printed Payment', 'خواندن پرداخت‌های چاپ‌شده', 'چاپ شوي تادیات لوستل'),
			'Create New Account' => self::lang_arr('Create New Account', 'ایجاد حساب جدید', 'نوې حساب جوړ کړئ'),
			'Update Account' => self::lang_arr('Update Account', 'به‌روزرسانی حساب', 'حساب تازه کول'),
			'Delete Account' => self::lang_arr('Delete Account', 'حذف حساب', 'حساب حذف کول'),
			'Delete Receipt' => self::lang_arr('Delete Receipt', 'حذف رسید', 'رسید حذف کړئ'),
			'Update Receipt' => self::lang_arr('Update Receipt', 'به‌روزرسانی رسید', 'رسید تازه کول'),
			'Read Filtered Receipts by Date' => self::lang_arr('Read Filtered Receipts by Date', 'خواندن رسیدهای فیلتر‌شده بر اساس تاریخ', 'د نیټې پراساس فلټر شوي رسیدونه لوستل'),
			'Read Financial Accounts Index' => self::lang_arr('Read Financial Accounts Index', 'خواندن شاخص حساب‌های مالی', 'د مالي حسابونو شاخص لوستل'),
			'Create New Receipt' => self::lang_arr('Create New Receipt', 'ایجاد رسید جدید', 'نوی رسید جوړ کړئ'),
			'Read Balance' => self::lang_arr('Read Balance', 'خواندن موجودی', 'بیلانس لوستل'),
			'Read Financials of Patient' => self::lang_arr('Read Financials of Patient', 'خواندن اطلاعات مالی بیمار', 'د ناروغ مالي معلومات لوستل'),
			'Read Paid' => self::lang_arr('Read Paid', 'خواندن پرداخت‌ها', 'تادیه شوي لوستل'),
			'Read Revenue' => self::lang_arr('Read Revenue', 'خواندن درآمد', 'عواید لوستل'),
			'Read Expenses' => self::lang_arr('Read Expenses', 'خواندن هزینه‌ها', 'لګښتونه لوستل'),
			'Read Printed Report' => self::lang_arr('Read Printed Report', 'خواندن گزارش چاپ‌شده', 'چاپ شوی راپور لوستل'),
			'Read Report Receipts Index' => self::lang_arr('Read Report Receipts Index', 'خواندن شاخص رسید گزارش‌ها', 'د راپور رسیدونو شاخص لوستل'),
			'Read Printed Receipts' => self::lang_arr('Read Printed Receipts', 'خواندن رسیدهای چاپ‌شده', 'چاپ شوي رسیدونه لوستل'),
			'Read Call Log Index' => self::lang_arr('Read Call Log Index', 'خواندن شاخص تماس‌ها', 'د اړیکو شاخص لوستل'),
			'Read Calls' => self::lang_arr('Read Calls', 'خواندن تماس‌ها', 'اړیکې لوستل'),
			'Create Services' => self::lang_arr('Create Services', 'ایجاد خدمات', 'خدمتونه جوړ کړئ'),
			'Read Services' => self::lang_arr('Read Services', 'خواندن خدمات', 'خدمتونه لوستل'),
			'Update Services' => self::lang_arr('Update Services', 'به‌روزرسانی خدمات', 'خدمتونه تازه کړئ'),
			'Delete Services' => self::lang_arr('Delete Services', 'حذف خدمات', 'خدمتونه حذف کړئ'),
			'Create Medicine' => self::lang_arr('Create Medicine', 'ایجاد دارو', 'درمل جوړ کړئ'),
			'Read Medicine' => self::lang_arr('Read Medicine', 'خواندن دارو', 'درمل لوستل'),
			'Update Medicine' => self::lang_arr('Update Medicine', 'به‌روزرسانی دارو', 'درمل تازه کړئ'),
			'Delete Medicine' => self::lang_arr('Delete Medicine', 'حذف دارو', 'درمل حذف کړئ'),
			'Create Diagnoses' => self::lang_arr('Create Diagnoses', 'ایجاد تشخیص', 'تشخیصونه جوړ کړئ'),
			'Read Diagnoses' => self::lang_arr('Read Diagnoses', 'خواندن تشخیص‌ها', 'تشخیصونه لوستل'),
			'Update Diagnoses' => self::lang_arr('Update Diagnoses', 'به‌روزرسانی تشخیص‌ها', 'تشخیصونه تازه کړئ'),
			'Delete Diagnoses' => self::lang_arr('Delete Diagnoses', 'حذف تشخیص‌ها', 'تشخیصونه حذف کړئ'),
			'Read Users Index' => self::lang_arr('Read Users Index', 'خواندن شاخص کاربران', 'د کاروونکو شاخص لوستل'),
			'Create User' => self::lang_arr('Create User', 'ایجاد کاربر', 'کاروونکی جوړ کړئ'),
			'Update User Block' => self::lang_arr('Update User Block', 'به‌روزرسانی بلاک کاربر', 'د کاروونکي بلاک تازه کړئ'),
			'Delete User' => self::lang_arr('Delete User', 'حذف کاربر', 'کاروونکی حذف کړئ'),
			'Update User Acceptance' => self::lang_arr('Update User Acceptance', 'به‌روزرسانی پذیرش کاربر', 'د کاروونکي منل تازه کړئ'),
			'Update User' => self::lang_arr('Update User', 'به‌روزرسانی کاربر', 'کاروونکی تازه کړئ'),
			'Read Payment Information' => self::lang_arr('Read Payment Information', 'خواندن اطلاعات پرداخت', 'د تادیاتو معلومات لوستل'),
			'Read Personal Information' => self::lang_arr('Read Personal Information', 'خواندن اطلاعات شخصی', 'شخصي معلومات لوستل'),
			'Create Teeth Entry' => self::lang_arr('Create Teeth Entry', 'ایجاد ورودی دندان', 'د غاښونو ننوتنه جوړ کړئ'),
			'Create Restorative Entry' => self::lang_arr('Create Restorative Entry', 'ایجاد ورودی ترمیمی', 'ترمیمي ننوتنه جوړ کړئ'),
			'Create Endodontic Entry' => self::lang_arr('Create Endodontic Entry', 'ایجاد ورودی اندودنتیک', 'اندودنتیک ننوتنه جوړ کړئ'),
			'Create Prosthetic Entry' => self::lang_arr('Create Prosthetic Entry', 'ایجاد ورودی پروستتیک', 'پروستیتیک ننوتنه جوړ کړئ'),
			'Update Restorative Entry' => self::lang_arr('Update Restorative Entry', 'به‌روزرسانی ورودی ترمیمی', 'ترمیمي ننوتنه تازه کړئ'),
			'Update Endodontic Entry' => self::lang_arr('Update Endodontic Entry', 'به‌روزرسانی ورودی اندودنتیک', 'اندودنتیک ننوتنه تازه کړئ'),
			'Update Prosthetic Entry' => self::lang_arr('Update Prosthetic Entry', 'به‌روزرسانی ورودی پروستتیک', 'پروستیتیک ننوتنه تازه کړئ'),
			'Update Teeth Entry' => self::lang_arr('Update Teeth Entry', 'به‌روزرسانی ورودی دندان', 'د غاښونو ننوتنه تازه کړئ'),
			'Create Lab Entry' => self::lang_arr('Create Lab Entry', 'ایجاد ورودی آزمایشگاه', 'د لابراتوار ننوتنه جوړ کړئ'),
			'Read Lab Entry' => self::lang_arr('Read Lab Entry', 'خواندن ورودی آزمایشگاه', 'د لابراتوار ننوتنه لوستل'),
			'Update Lab Entry' => self::lang_arr('Update Lab Entry', 'به‌روزرسانی ورودی آزمایشگاه', 'د لابراتوار ننوتنه تازه کړئ'),
			'Delete Lab Entry' => self::lang_arr('Delete Lab Entry', 'حذف ورودی آزمایشگاه', 'د لابراتوار ننوتنه حذف کړئ'),

			//List of Perimissions

			// Restorative Tab
			'Caries Depth' => self::lang_arr('Caries Depth', 'عمق پوسیدگی', 'د تلیفون کتاب'),
			'restorative material' => self::lang_arr('Restorative Material', 'ماده ترمیم', 'د تلیفون کتاب'),
			'composite brand' => self::lang_arr('Composite Brand', 'برند کامپوزیت', 'د تلیفون کتاب'),
			'amalgam brand' => self::lang_arr('Amalgam Brand', 'برند امالگام', 'د تلیفون کتاب'),
			'base or liner material' => self::lang_arr('Base or Liner Material', 'ماده بیس یا لاینر', 'د تلیفون کتاب'),
			'surface' => self::lang_arr('Surface', 'سطحی', 'د تلیفون کتاب'),
			'intermediate' => self::lang_arr('Intermediate', 'متوسط', 'د تلیفون کتاب'),
			'deep' => self::lang_arr('Deep', 'عمیق', 'د تلیفون کتاب'),
			'Zinc Oxide Eugenol' => self::lang_arr('Zinc Oxide Eugenol', 'زینک اکساید اوژینول', 'د تلیفون کتاب'),
			'Calcium Hydroxide' => self::lang_arr(' Calcium Hydroxide', 'کلسیم هایدروکساید', 'د تلیفون کتاب'),
			'Glass Ionomer' => self::lang_arr('Glass Ionomer', 'گلسی آینومر', 'د تلیفون کتاب'),
			'Zinc Phosphate' => self::lang_arr('Zinc Phosphate', 'زینک فاسفات', 'د تلیفون کتاب'),
			'MTA' => self::lang_arr('MTA', 'MTA', 'د تلیفون کتاب'),
			'amalgam' => self::lang_arr('Amalgam', 'MTA', 'د تلیفون کتاب'),
			'composite' => self::lang_arr('Composite', 'MTA', 'د تلیفون کتاب'),
			'glass ionomer' => self::lang_arr('Glass Ionomer', 'MTA', 'د تلیفون کتاب'),
			'HARDVARD' => self::lang_arr('HARDVARD', 'HARDVARD', 'د تلیفون کتاب'),
			'Hybrsun classic' => self::lang_arr('Hybrsun classic', 'Hybrsun classic', 'د تلیفون کتاب'),
			'Nano Concept' => self::lang_arr('Nano Concept', 'Nano Concept', 'د تلیفون کتاب'),
			'G-aenial' => self::lang_arr('G-aenial', 'G-aenial', 'د تلیفون کتاب'),
			'Empress Direct' => self::lang_arr('Empress Direct', 'Empress Direct', 'د تلیفون کتاب'),
			'Gradia' => self::lang_arr('Gradia', 'Gradia', 'د تلیفون کتاب'),
			'Tokoyama' => self::lang_arr('Tokoyama', 'Tokoyama', 'د تلیفون کتاب'),
			'SDK' => self::lang_arr('SDK', 'SDK', 'د تلیفون کتاب'),
			'GK' => self::lang_arr('GK', 'GK', 'د تلیفون کتاب'),
			'Ardent' => self::lang_arr('Ardent', 'Ardent', 'د تلیفون کتاب'),
			'World work' => self::lang_arr('World work', 'World work', 'د تلیفون کتاب'),
			'Solaray' => self::lang_arr('Solaray', 'Solaray', 'د تلیفون کتاب'),
			'bonding brand' => self::lang_arr('bonding brand', 'برند باندیگ', 'not set'),
			// timer
			'Page will Reload In:' => self::lang_arr('Page will Reload In:', 'تازه سازی صفحه تا زمان:', 'not set'),
			// timer

			// categories at the primary Infos
			'files' => self::lang_arr('files', 'فایل ها', 'not set'),
			'choose a category' => self::lang_arr('choose a category', 'انتخاب یک دسته', 'not set'),
			'insert categories' => self::lang_arr('insert categories', 'افزودن دسته بندی ها', 'not set'),
			'insert categories success' => self::lang_arr('The category was successfully added', 'کتگوری موفقانه افزوده شد', 'کتگوری په بریالیتوب سره اضافه شو'),

			// categories at the primary Infos

			// 404 page
			'404Message' => self::lang_arr('The page you are locking for is NOT FOUND!', 'صفحه مورد نظر یافت نشد!', 'not set'),
			'goHome' => self::lang_arr('Home Page', 'باز گشت به خانه', 'not set'),
			// 404 page

			// lockScreen
			'unlockMessage' => self::lang_arr('Enter Your Password', 'رمز عبور خود را وارد کنید', 'not set'),
			'unlock' => self::lang_arr('Unlock', 'قفل گشایی', 'not set'),
			'forget password' => self::lang_arr('Forget Password', 'آیا رمز عبور خور را فراموش کرده اید؟', 'not set'),
			// lockScreen

		];

		return ucwords($lang[$key][$lan]);
	}

	static function err_gen($word_en, $word_fa, $word_pa, $type = 'required')
	{
		switch ($type) {
			case "required":
				$en = $word_en . ' field is required';
				$fa = 'بخش ' . $word_fa . ' الزامی است';
				$pa = 'د ' . $word_pa . ' ساحه اړینه ده';
				break;
			case 'unique':
				$en = $word_en . ' is already submitted';
				$fa = 'این ' . $word_fa . ' قبلا ثبت شده است';
				$pa = 'دا ' . $word_pa . ' لا دمخه سپارل شوی دی';
				break;


			default:
				$en = '';
				$fa = '';
				$pa = '';
		}
		return self::lang_arr($en, $fa, $pa);
	}

	static function lang_arr($en = '', $fa = 'translation not found', $pa = 'translation not found')
	{
		return ['en' => $en, 'fa' => $fa, 'pa' => $pa];
	}
}
