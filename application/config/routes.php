<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'Dashboard/index';
$route['safe'] = 'safe/index';
$route['safe/add-income'] = 'safe/add_income';
$route['safe/adjust'] = 'safe/adjust';

$route['login'] = 'Login/index';
$route['logout'] = 'Login/logout';
$route['preferences/language/(:any)'] = 'Preferences/language/$1';
$route['preferences/theme/(:any)'] = 'Preferences/theme/$1';
$route['preferences/diagnoses'] = 'Preferences/diagnoses';
$route['preferences/diagnoses/store'] = 'Preferences/diagnoses_store';
$route['preferences/diagnoses/update/(:num)'] = 'Preferences/diagnoses_update/$1';
$route['preferences/diagnoses/delete/(:num)'] = 'Preferences/diagnoses_delete/$1';
$route['preferences/expense-categories'] = 'Preferences/expense_categories';
$route['preferences/expense-categories/store'] = 'Preferences/expense_categories_store';
$route['preferences/expense-categories/update/(:num)'] = 'Preferences/expense_categories_update/$1';
$route['preferences/expense-categories/delete/(:num)'] = 'Preferences/expense_categories_delete/$1';

$route['patients'] = 'Patients/index';
$route['patients/create'] = 'Patients/create';
$route['patients/store'] = 'Patients/store';
$route['patients/add-discount/(:num)'] = 'patients/add_discount/$1';
$route['patients/delete-discount/(:num)/(:num)'] = 'patients/delete_discount/$1/$2';
$route['patients/(:num)'] = 'Patients/show/$1';
$route['patients/(:num)/wallet-topup'] = 'Patients/wallet_topup/$1';
$route['patients/(:num)/wallet-deduct'] = 'Patients/wallet_deduct/$1';
$route['patients/(:num)/debt-payment'] = 'Patients/debt_payment/$1';
$route['patients/(:num)/edit'] = 'Patients/edit/$1';
$route['patients/(:num)/update'] = 'Patients/update/$1';
$route['patients/(:num)/delete'] = 'Patients/delete/$1';

$route['reference_doctors'] = 'reference_doctors/index';
$route['reference_doctors/create'] = 'reference_doctors/create';
$route['reference_doctors/store'] = 'reference_doctors/store';
$route['reference_doctors/edit/(:num)'] = 'reference_doctors/edit/$1';
$route['reference_doctors/update/(:num)'] = 'reference_doctors/update/$1';
$route['reference_doctors/delete/(:num)'] = 'reference_doctors/delete/$1';
$route['reference_doctors/activate/(:num)'] = 'reference_doctors/activate/$1';
$route['reference_doctors/profile/(:num)'] = 'reference_doctors/profile/$1';
$route['reference_doctors/patient_count/(:num)'] = 'reference_doctors/patient_count/$1';

$route['users'] = 'Users/index';
$route['users/create'] = 'Users/create';
$route['users/store'] = 'Users/store';
$route['users/(:num)/edit'] = 'Users/edit/$1';
$route['users/(:num)/update'] = 'Users/update/$1';
$route['users/(:num)/delete'] = 'Users/delete/$1';

$route['roles'] = 'Roles/index';
$route['roles/create'] = 'Roles/create';
$route['roles/store'] = 'Roles/store';
$route['roles/(:num)/edit'] = 'Roles/edit/$1';
$route['roles/(:num)/update'] = 'Roles/update/$1';
$route['roles/(:num)/delete'] = 'Roles/delete/$1';

$route['turns'] = 'Turns/index';
$route['turns/create'] = 'Turns/create';
$route['turns/bulk'] = 'Turns/bulk_create';
$route['turns/bulk/store'] = 'Turns/bulk_store';
$route['turns/bulk-create'] = 'Turns/bulk_create';
$route['turns/store'] = 'Turns/store';
$route['turns/bulk-store'] = 'Turns/bulk_store';
$route['turns/get_section_data'] = 'turns/get_section_data';
$route['turns/get_patient_financial'] = 'turns/get_patient_financial';
$route['turns/get_session_number'] = 'turns/get_session_number';
$route['turns/edit/(:num)'] = 'Turns/edit/$1';
$route['turns/update/(:num)'] = 'Turns/update/$1';
$route['turns/delete/(:num)'] = 'Turns/delete/$1';
$route['turns/(:num)/edit'] = 'Turns/edit/$1';
$route['turns/(:num)/update'] = 'Turns/update/$1';
$route['turns/(:num)/delete'] = 'Turns/delete/$1';

$route['payments'] = 'Payments/index';
$route['payments/create'] = 'Payments/create';
$route['payments/store'] = 'Payments/store';
$route['payments/(:num)'] = 'Payments/show/$1';
$route['payments/(:num)/edit'] = 'Payments/edit/$1';
$route['payments/(:num)/update'] = 'Payments/update/$1';
$route['payments/(:num)/delete'] = 'Payments/delete/$1';

$route['expenses'] = 'expenses/index';
$route['expenses/create'] = 'expenses/create';
$route['expenses/store'] = 'expenses/store';
$route['expenses/edit/(:num)'] = 'expenses/edit/$1';
$route['expenses/update/(:num)'] = 'expenses/update/$1';
$route['expenses/delete/(:num)'] = 'expenses/delete/$1';

$route['salaries'] = 'salaries/index';
$route['salaries/pay/(:num)'] = 'salaries/pay/$1';
$route['salaries/store-payment'] = 'salaries/store_payment';
$route['salaries/get-calculation'] = 'salaries/get_calculation';

$route['reports'] = 'Reports/index';
$route['reports/daily-register'] = 'reports/daily_register';
$route['reports/daily-register/print'] = 'reports/daily_register_print';

$route['leaves'] = 'Leaves/index';
$route['leaves/create'] = 'Leaves/create';
$route['leaves/store'] = 'Leaves/store';
$route['leaves/(:num)/edit'] = 'Leaves/edit/$1';
$route['leaves/(:num)/update'] = 'Leaves/update/$1';
$route['leaves/(:num)/delete'] = 'Leaves/delete/$1';

$route['staff'] = 'staff/index';
$route['staff/create'] = 'staff/create';
$route['staff/store'] = 'staff/store';
$route['staff/edit/(:num)'] = 'staff/edit/$1';
$route['staff/update/(:num)'] = 'staff/update/$1';
$route['staff/delete/(:num)'] = 'staff/delete/$1';
$route['staff/activate/(:num)'] = 'staff/activate/$1';
$route['staff/profile/(:num)'] = 'staff/profile/$1';
$route['staff/calculate_salary/(:num)'] = 'staff/calculate_salary/$1';

$route['sections'] = 'sections/index';
$route['sections/create'] = 'sections/create';
$route['sections/store'] = 'sections/store';
$route['sections/(:num)'] = 'sections/show/$1';
$route['sections/(:num)/edit'] = 'sections/edit/$1';
$route['sections/(:num)/update'] = 'sections/update/$1';
$route['sections/(:num)/delete'] = 'sections/delete/$1';
