<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'Dashboard/index';

$route['login'] = 'Login/index';
$route['logout'] = 'Login/logout';
$route['preferences/language/(:any)'] = 'Preferences/language/$1';
$route['preferences/theme/(:any)'] = 'Preferences/theme/$1';

$route['patients'] = 'Patients/index';
$route['patients/create'] = 'Patients/create';
$route['patients/store'] = 'Patients/store';
$route['patients/(:num)'] = 'Patients/show/$1';
$route['patients/(:num)/edit'] = 'Patients/edit/$1';
$route['patients/(:num)/update'] = 'Patients/update/$1';
$route['patients/(:num)/delete'] = 'Patients/delete/$1';

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
$route['turns/bulk-create'] = 'Turns/bulk_create';
$route['turns/store'] = 'Turns/store';
$route['turns/bulk-store'] = 'Turns/bulk_store';
$route['turns/(:num)/edit'] = 'Turns/edit/$1';
$route['turns/(:num)/update'] = 'Turns/update/$1';
$route['turns/(:num)/delete'] = 'Turns/delete/$1';

$route['payments'] = 'Payments/index';
$route['payments/create'] = 'Payments/create';
$route['payments/store'] = 'Payments/store';
$route['payments/(:num)/edit'] = 'Payments/edit/$1';
$route['payments/(:num)/update'] = 'Payments/update/$1';
$route['payments/(:num)/delete'] = 'Payments/delete/$1';

$route['reports'] = 'Reports/index';

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
