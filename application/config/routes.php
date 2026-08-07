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
$route['patients/datatable'] = 'Patients/datatable';
$route['patients/create'] = 'Patients/create';
$route['patients/store'] = 'Patients/store';
$route['patients/add-discount/(:num)'] = 'patients/add_discount/$1';
$route['patients/delete-discount/(:num)/(:num)'] = 'patients/delete_discount/$1/$2';
$route['patients/(:num)'] = 'Patients/show/$1';
$route['patients/(:num)/wallet-topup'] = 'Patients/wallet_topup/$1';
$route['patients/(:num)/wallet-historical-credit'] = 'Patients/wallet_historical_credit/$1';
$route['patients/(:num)/wallet-deduct'] = 'Patients/wallet_deduct/$1';
$route['patients/(:num)/debt-payment'] = 'Patients/debt_payment/$1';
$route['patients/(:num)/refund'] = 'Patients/refund/$1';
$route['patients/(:num)/payments/(:num)/edit'] = 'Patients/edit_debt_payment/$1/$2';
$route['patients/(:num)/payments/(:num)/delete'] = 'Patients/delete_debt_payment/$1/$2';
$route['patients/(:num)/refunds/(:num)/edit'] = 'Patients/edit_refund/$1/$2';
$route['patients/(:num)/refunds/(:num)/delete'] = 'Patients/delete_refund/$1/$2';
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
$route['turns/datatable'] = 'Turns/datatable';
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

$route['expenses'] = 'expenses/index';
$route['expenses/create'] = 'expenses/create';
$route['expenses/store'] = 'expenses/store';
$route['expenses/edit/(:num)'] = 'expenses/edit/$1';
$route['expenses/update/(:num)'] = 'expenses/update/$1';
$route['expenses/delete/(:num)'] = 'expenses/delete/$1';

$route['salaries'] = 'salaries/index';
$route['salaries/pay/(:num)'] = 'salaries/pay/$1';
$route['salaries/store-payment'] = 'salaries/store_payment';
$route['salaries/payment/(:num)/delete'] = 'salaries/delete_payment/$1';
$route['salaries/settle/(:num)'] = 'salaries/settle/$1';
$route['salaries/reopen/(:num)'] = 'salaries/reopen/$1';
$route['salaries/get-calculation'] = 'salaries/get_calculation';

$route['store'] = 'Store/index';
$route['store/products'] = 'Store/products';
$route['store/create_product'] = 'Store/create_product';
$route['store/edit_product/(:num)'] = 'Store/edit_product/$1';
$route['store/create_variant/(:num)'] = 'Store/create_variant/$1';
$route['store/edit_variant/(:num)'] = 'Store/edit_variant/$1';
$route['store/categories'] = 'Store/categories';
$route['store/create_category'] = 'Store/create_category';
$route['store/edit_category/(:num)'] = 'Store/edit_category/$1';
$route['store/delete_category/(:num)'] = 'Store/delete_category/$1';
$route['store/stock'] = 'Store/stock';
$route['store/stock/(:num)'] = 'Store/stock/$1';
$route['store/set_opening_stock'] = 'Store/set_opening_stock';
$route['store/requisitions'] = 'Store/requisitions';
$route['store/create_requisition'] = 'Store/create_requisition';
$route['store/approve_requisition/(:num)'] = 'Store/approve_requisition/$1';
$route['store/receive_requisition/(:num)'] = 'Store/receive_requisition/$1';
$route['store/sell'] = 'Store/sell';
$route['store/receipt/(:num)'] = 'Store/receipt/$1';
$route['store/clear_sale_debt/(:num)'] = 'Store/clear_sale_debt/$1';
$route['store/refund_sale/(:num)'] = 'Store/refund_sale/$1';
$route['store/bulk_sell'] = 'Store/bulk_sell';
$route['store/sale_batches'] = 'Store/sale_batches';
$route['store/approve_sale_batch/(:num)'] = 'Store/approve_sale_batch/$1';
$route['store/reports'] = 'Store/reports';
$route['store/suppliers'] = 'Store/suppliers';
$route['store/create_supplier'] = 'Store/create_supplier';
$route['store/edit_supplier/(:num)'] = 'Store/edit_supplier/$1';
$route['store/receive_stock'] = 'Store/receive_stock';
$route['store/stock_receipts'] = 'Store/stock_receipts';
$route['store/view_stock_receipt/(:num)'] = 'Store/view_stock_receipt/$1';

$route['reports'] = 'Reports/index';
$route['reports/daily-register'] = 'reports/daily_register';
$route['reports/daily-register/print'] = 'reports/daily_register_print';
$route['reports/outstanding-balances'] = 'Reports/outstanding_balances';
$route['reports/patient-financial-summary'] = 'Reports/patient_financial_summary';
$route['reports/debtors'] = 'Reports/debtors';
$route['reports/debtors/print'] = 'Reports/debtors_print';
$route['reports/new-patients'] = 'Reports/new_patients';

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
