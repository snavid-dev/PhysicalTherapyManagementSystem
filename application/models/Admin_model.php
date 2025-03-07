<?php

class Admin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_users()
	{
		return $this->db->query("SELECT users.*, roles.role_name FROM users INNER JOIN roles ON users.role_id = roles.id")->result_array();
	}

	public function single_user($id)
	{
		return $this->db->query("SELECT users.*, roles.role_name FROM users INNER JOIN roles ON users.role_id = roles.id WHERE users.id = '$id'")->result_array();
	}

	public function insert_user($data = array())
	{
		$log = $this->db->insert('users', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function insert_leave($data = array())
	{
		$log = $this->db->insert('doctor_leave', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function single_user_update($where = array())
	{
		return $this->db->get_where('users', $where)->result_array();
	}

	function delete_leave($where = array())
	{
		return $this->db->delete('doctor_leave', $where);
	}

	public function get_leave_by_id($leave_id)
	{
		$this->db->select('doctor_leave.*, users.fname, users.lname');
		$this->db->from('doctor_leave');
		$this->db->join('users', 'users.id = doctor_leave.doctor_id', 'left');
		$this->db->where('doctor_leave.id', $leave_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}

		return false;
	}

	public function update_leave_record($datas, $leave_id)
	{
		$this->db->where('id', $leave_id);
		return $this->db->update('doctor_leave', $datas);
	}


	public function get_leave_details($leave_id)
	{
		// Get the details of the leave from the 'doctor_leaves' table
		$this->db->select('doctor_id, leave_start_date, leave_end_date, reason, status');
		$this->db->from('doctor_leave');
		$this->db->where('id', $leave_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$leave = $query->row_array();
			$doctor = $this->get_doctor_by_id($leave['doctor_id']);  // Assuming a function to fetch doctor's name
			$leave['doctor_name'] = $doctor['fname'] . ' ' . $doctor['lname'];
			return $leave;
		}
		return false;
	}

	public function get_doctor_by_id($doctor_id)
	{
		// Select relevant doctor details from the 'users' table (or the table where doctor data is stored)
		$this->db->select('id, fname, lname');  // Adjust fields as needed
		$this->db->from('users');  // Assuming 'users' table stores doctor info
		$this->db->where('id', $doctor_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();  // Return doctor details as an associative array
		}

		// Return false if doctor is not found
		return false;
	}

	public function get_leave_requests()
	{
		// Start by selecting the leave request fields
		$this->db->select('doctor_leave.id, doctor_leave.leave_start_date, doctor_leave.leave_end_date, doctor_leave.reason, doctor_leave.status, doctor_leave.doctor_id, users.fname AS doctor_fname, users.lname AS doctor_lname');

		// Join the 'users' table (doctor details) on 'doctor_id'
		$this->db->from('doctor_leave');  // Assuming 'doctor_leave' stores leave data
		$this->db->join('users', 'doctor_leave.doctor_id = users.id', 'left');  // Join doctors from 'users' table

		// Order by the leave start date in descending order
		$this->db->order_by('doctor_leave.leave_start_date DESC');

		// Execute the query
		$query = $this->db->get();

		// Check if the query returns any results
		if ($query->num_rows() > 0) {
			return $query->result_array();  // Return leave requests as an array of associative arrays
		}

		// If no leave requests found, return false
		return false;
	}


	function table_by_user($tablename, $users_id)
	{
		return $this->db->get_where($tablename, array('users_id' => $users_id))->result_array();
	}


	function table_by_doctor($tablename, $doctor_id)
	{
		return $this->db->get_where($tablename, array('doctor_id' => $doctor_id))->result_array();
	}

	function single_tooth($where)
	{
		return $this->db->get_where('tooth', $where)->result_array();
	}

	public function delete_user($data = array())
	{
		return $this->db->delete('users', array('id' => $data['id']));
	}

	public function get_customers_by_users_id($id)
	{
		return $this->db->get_where('customers', array('users_id' => $id))->result_array();
	}

	public function get_balance_sheet_by_users_id($id)
	{
		return $this->db->get_where('balance_sheet', array('users_id' => $id))->result_array();
	}

	public function get_loan_by_users_id($id)
	{
		return $this->db->get_where('loan', array('users_id' => $id))->result_array();
	}

	public function get_customers()
	{
		return $this->db->query("SELECT customers.*, users.fname AS 'firstname', users.lname AS 'lastname' FROM customers INNER JOIN users ON customers.users_id = users.id  ORDER BY customers.id DESC")->result_array();
	}

	public function insert_customer($data = array())
	{
		$log = $this->db->insert('customers', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function get_balance_sheet_by_customer_id($id)
	{
		return $this->db->get_where('balance_sheet', array('customers_id' => $id))->result_array();
	}

	public function delete_customer($where = array())
	{
		return $this->db->delete('customers', $where);
	}

	public function single_customer($where = array())
	{
		return $this->db->get_where('customers', $where)->result_array();
	}

	public function update_customer($data, $id)
	{
		return $this->db->update('customers', $data, array('id' => $id));
	}

	public function user_by_customer_id($id)
	{
		return $this->db->query("SELECT users.fname, users.lname FROM `customers` INNER JOIN users ON customers.users_id = users.id WHERE customers.id = $id")->result_array();
	}

	public function user_by_balance_id($id)
	{
		return $this->db->query("SELECT users.fname, users.lname FROM `balance_sheet` INNER JOIN users ON balance_sheet.users_id = users.id WHERE balance_sheet.id = $id")->result_array();
	}

	public function get_balance_sheet()
	{
		$date = $this->mylibrary->getCurrentShamsiDate()['date'];
		return $this->db->query("SELECT `balance_sheet`.*, users.fname AS 'firstname', users.lname AS 'lastname', customers.name, customers.fname FROM `balance_sheet` INNER JOIN `users` ON balance_sheet.users_id = users.id INNER JOIN customers ON balance_sheet.customers_id = customers.id WHERE DATE(`balance_sheet`.`shamsi`) BETWEEN DATE('" . $date . "') AND DATE('" . $date . "')  
        ORDER BY `balance_sheet`.`id` DESC")->result_array();
	}

	function get_single_balance_with_join($id)
	{
		return $this->db->query("SELECT `balance_sheet`.id, balance_sheet.cr, balance_sheet.dr, balance_sheet.shamsi, balance_sheet.remarks, CONCAT(users.fname, ' - ' ,users.lname) AS 'user', customers.name, customers.lname , customers.type FROM `balance_sheet` INNER JOIN customers ON balance_sheet.customers_id = customers.id INNER JOIN users ON balance_sheet.users_id = users.id WHERE balance_sheet.id = '$id'")->result_array();
	}

	public function get_report_balance_sheet($extra = null)
	{
		if (is_null($extra)) {
			$extra = "DATE(shamsi) = DATE('" . $this->mylibrary->getCurrentShamsiDate()['date'] . "')";
		}

		return $this->db->query("SELECT `balance_sheet`.*,  CONCAT(customers.name, ' - ', customers.lname) AS 'customer_name', CONCAT(users.fname, ' - ', users.lname) AS 'user_name' FROM `balance_sheet` INNER JOIN `customers` ON balance_sheet.customers_id = customers.id INNER JOIN `users` ON `balance_sheet`.users_id = users.id WHERE " . $extra . " ORDER BY `balance_sheet`.`shamsi` ASC;")->result_array();
	}


	public function get_tooth_income($extra = null)
	{
		if (is_null($extra)) {
			$extra = "DATE(tooth.create_date) = DATE('" . $this->mylibrary->getCurrentShamsiDate()['date'] . "')";
		}

		return $this->db->query("SELECT tooth.name AS 'tooth_name', tooth.location, tooth.price, tooth.create_date, patient.name, patient.lname, patient.gender FROM `tooth` INNER JOIN patient ON tooth.patient_id = patient.id WHERE " . $extra . " ORDER BY `tooth`.`create_date` ASC;")->result_array();
	}


	public function get_turns_paid($extra = null)
	{
		// Default condition for today's date if no extra condition is provided
		if (is_null($extra)) {
			$currentDate = $this->mylibrary->getCurrentShamsiDate()['date'];
			$extra = "DATE(turn.date) = DATE(?)";
			$params = [$currentDate];
		} else {
			$params = []; // Assume `extra` already contains conditions and doesn't need parameters
		}

		// Base SQL query
		$sql = "SELECT 
                turn.*, 
                patient.name, 
                patient.gender, 
                patient.lname, 
                patient.serial_id 
            FROM `turn` 
            INNER JOIN patient ON turn.patient_id = patient.id 
            WHERE $extra 
            ORDER BY `turn`.`date` ASC";

		// Execute query with parameters if applicable
		return empty($params)
			? $this->db->query($sql)->result_array()
			: $this->db->query($sql, $params)->result_array();
	}


	public function get_labs_expenses($extra = 1)
	{
		if (is_null($extra) || $extra == '') {
			$extra = 1;
		}
		return $this->db->query("SELECT `labs`.*, CONCAT(customers.name, ' - ', customers.lname) AS 'lab_name', patient.name, patient.lname, patient.gender FROM `labs` INNER JOIN patient ON labs.patient_id = patient.id INNER JOIN customers ON labs.customers_id = customers.id WHERE " . $extra . "  
    ORDER BY `labs`.`give_date` ASC")->result_array();
	}


	public function get_patient_balance_report($extra = 1)
	{
		if (is_null($extra) || $extra == '') {
			$extra = 1;
		}
		return $this->db->query("SELECT patient.id, patient.name, patient.remarks, patient.lname, patient.gender, patient.serial_id, patient.phone1, patient.create, patient.status FROM `patient` WHERE " . $extra . " ORDER BY `create` DESC")->result_array();
	}

	public function insert_receipt($data = array())
	{
		$log = $this->db->insert('balance_sheet', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function get_customer_balance($customer_id = null)
	{
		return $this->db->query("SELECT SUM(cr) AS 'cr', SUM(dr) AS 'dr', currency FROM `balance_sheet` WHERE customers_id = '$customer_id' GROUP BY currency;")->result_array();
	}

	public function customer_by_balance_id($id = null)
	{
		return $this->db->query("SELECT customers.name, customers.fname, customers.page FROM `balance_sheet` INNER JOIN customers ON balance_sheet.customers_id = customers.id WHERE balance_sheet.id = $id")->result_array();
	}

	function balance_by_id($data = array())
	{
		$id = $data['id'];

		return $this->db->query("SELECT `balance_sheet`.*, users.fname AS 'firstname', users.lname AS 'lastname', users.role, customers.name, customers.lname, customers.type FROM `balance_sheet` INNER JOIN `users` ON balance_sheet.users_id = users.id INNER JOIN customers ON balance_sheet.customers_id = customers.id WHERE `balance_sheet`.id = '$id'")->result_array();
	}

	public function delete_balance($where)
	{
		return $this->db->delete('balance_sheet', $where);
	}

	public function single_balance($where = array())
	{
		return $this->db->get_where('balance_sheet', $where)->result_array();
	}

	public function get_loan()
	{
		return $this->db->query("SELECT loan.*, users.fname, users.lname FROM `loan` INNER JOIN users ON loan.users_id = users.id")->result_array();
	}

	public function update_balance($data, $id)
	{
		return $this->db->update('balance_sheet', $data, array('id' => $id));
	}

	public function insert_loan($data = array())
	{
		$log = $this->db->insert('loan', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function delete_loan($where = array())
	{
		return $this->db->delete('loan', $where);
	}

	public function single_loan($where = array())
	{
		return $this->db->get_where('loan', $where)->result_array();
	}

	public function update_loan($data = array(), $id = null)
	{
		return $this->db->update('loan', $data, array('id' => $id));
	}

	public function user_by_loan_id($id)
	{
		return $this->db->query("SELECT users.fname, users.lname FROM `loan` INNER JOIN users ON loan.users_id = users.id WHERE loan.id = $id")->result_array();
	}

	public function sum_loan()
	{
		return $this->db->query("SELECT SUM(price) AS 'sum_price', currency FROM `loan` GROUP BY currency ORDER BY currency ASC;")->result_array();
	}

	public function users_dr_balance()
	{
		return $this->db->query("SELECT customers.name, customers.fname, `balance_sheet`.currency, SUM(`balance_sheet`.cr) AS 'sum_cr', SUM(`balance_sheet`.dr) AS 'sum_dr' FROM balance_sheet INNER JOIN customers ON balance_sheet.customers_id = customers.id GROUP BY balance_sheet.customers_id, balance_sheet.currency  ORDER BY balance_sheet.customers_id DESC;")->result_array();
	}

	public function balance_currency()
	{
		return $this->db->query("SELECT SUM(cr) AS 'cr', SUM(dr) AS 'dr', balance_sheet.currency FROM balance_sheet GROUP BY currency;")->result_array();
	}


	public function update_user($data = array(), $id)
	{
		return $this->db->update('users', $data, array('id' => $id));
	}

	function update_password($data = array(), $user_id)
	{
		return $this->db->update('users', $data, array('id' => $user_id));
	}

	public function change_user_status($status, $where = array())
	{
		return $this->db->update('users', array('status' => $status), $where);
	}

	public function count_balance()
	{
		$date = $this->mylibrary->getCurrentShamsiDate()['date'];
		return $this->db->query("SELECT * FROM `balance_sheet` WHERE shamsi = '$date'")->result_array();
	}

	// Start Services

	public function get_services($department = null)
	{
		if (is_null($department)) {
			return $this->db->get('services')->result_array();
		} else {
			return $this->db->get_where('services', array('department' => $department))->result_array();
		}
	}

	public function delete_service($where = array())
	{
		return $this->db->delete('services', $where);
	}

	public function insert_service($data = array())
	{
		$log = $this->db->insert('services', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function single_service($where = array())
	{
		return $this->db->get_where('services', $where)->result_array();
	}

	// End Services


	// Start Endo has Services

	public function insert_endo_has_services($data)
	{
		return $this->db->insert('endo_has_services', $data);
	}

	public function get_endo_has_service($where = array())
	{
		return $this->db->get_where('endo_has_services', $where)->result_array();
	}
	// End Endo has Services

	// Start restorative services
	public function insert_restorative_has_services($data)
	{
		return $this->db->insert('restorative_has_services', $data);
	}

	public function get_restorative_has_service($where = array())
	{
		return $this->db->get_where('restorative_has_services', $where)->result_array();
	}
	// End restorative services

	// Start prosthodontics services

	public function insert_prosthodontics_has_services($data)
	{
		return $this->db->insert('prosthodontics_has_services', $data);
	}

	// end prosthodontics services

	public function get_patients($status = 'p')
	{
		return $this->db->query("SELECT `patient`.*, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `patient` INNER JOIN users ON patient.doctor_id = users.id WHERE patient.status = '$status' ORDER BY patient.create DESC")->result_array();
	}

	public function get_patients_extra($extra = null)
	{
		return $this->db->query("SELECT `patient`.*, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `patient` INNER JOIN users ON patient.doctor_id = users.id WHERE " . $extra . " ORDER BY patient.create DESC")->result_array();
	}

	public function get_temp_patients_extra($extra = "temp_patient.status = 'p'")
	{
		return $this->db->query("SELECT `temp_patient`.*, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `temp_patient` INNER JOIN users ON temp_patient.doctor_id = users.id WHERE " . $extra . " ORDER BY temp_patient.create DESC")->result_array();
	}


	function get_patients_for_report()
	{
		return $this->db->query("SELECT id, name, lname, serial_id, gender FROM `patient` WHERE status != 'b' ORDER BY `id`  DESC")->result_array();
	}

	public function update_service($data = array(), $id)
	{
		return $this->db->update('services', $data, array('id' => $id));
	}


	// Start Categories


	public function insert_categories($data = array())
	{
		$log = $this->db->insert('categories', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function delete_categories($where = array())
	{
		return $this->db->delete('categories', $where);
	}

	public function table_by_category($tablename, $categories_id)
	{
		return $this->db->get_where($tablename, array('categories_id' => $categories_id))->result_array();
	}

	public function get_categories()
	{
		return $this->db->get('categories')->result_array();
	}

	function categories_by_type($type)
	{
		return $this->db->get_where('categories', array('type' => $type))->result_array();
	}

	public function single_category($id)
	{
		return $this->db->get_where('categories', array('id' => $id))->result_array();
	}

	public function update_categories($data = array(), $id)
	{
		return $this->db->update('categories', $data, array('id' => $id));
	}

	// End Categories


	// Start Basic_information_teeth

	function insert_basic_information_teeth($data = array())
	{
		$log = $this->db->insert('basic_information_teeth', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function teeth_basic_information_list($department)
	{
		return $this->db->query("SELECT `basic_information_teeth`.*, categories.name AS 'category_name' FROM `basic_information_teeth` INNER JOIN categories ON categories.id = basic_information_teeth.categories_id WHERE basic_information_teeth.department = '$department'")->result_array();
	}

	public function single_basic_teeth_info($where)
	{
		return $this->db->get_where('basic_information_teeth', $where)->result_array();
	}

	public function single_basic_teeth_info_with_category($id)
	{
		return $this->db->query("SELECT basic_information_teeth.*, categories.name AS 'category_name' FROM basic_information_teeth INNER JOIN categories ON basic_information_teeth.categories_id = categories.id WHERE basic_information_teeth.id = '$id'")->result_array();
	}

	public function update_basic_information_teeth($data = array(), $id)
	{
		return $this->db->update('basic_information_teeth', $data, array('id' => $id));
	}

	public function check_basic_info($id, $department = 'endo')
	{
		return $this->db->get_where($department . '_has_basic_information_teeth', array('basic_information_teeth_id' => $id))->result_array();
	}

	public function delete_basic_teeth_info($where = array())
	{
		return $this->db->delete('basic_information_teeth', $where);
	}


	// End Basic_information_teeth

	public function get_accounts()
	{
		return $this->db->query("SELECT `customers`.*, users.fname, users.role, users.lname AS 'lastname' FROM `customers` INNER JOIN users ON customers.users_id = users.id")->result_array();
	}

	public function get_lab_account($type = 'l')
	{
		return $this->db->get_where('customers', array('type' => $type))->result_array();
	}

	function labs_patient_id($id)
	{
		return $this->db->query("SELECT labs.*, CONCAT(customers.name, ' - ', customers.lname) AS 'lab_name' FROM `labs` INNER JOIN customers ON labs.customers_id = customers.id WHERE labs.patient_id = '$id' ORDER BY labs.give_date ASC")->result_array();
	}


	public function get_account_with_no_user()
	{
		return $this->db->get('customers')->result_array();
	}

	public function delete_account($where = array())
	{
		return $this->db->delete('customers', $where);
	}

	public function account_receipts($id)
	{
		return $this->db->get_where('balance_sheet', array('customers_id' => $id))->result_array();
	}

	public function insert_account($data = array())
	{
		$log = $this->db->insert('customers', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function single_account($where = array())
	{
		return $this->db->get_where('customers', $where)->result_array();
	}

	public function update_account($data = array(), $id)
	{
		return $this->db->update('customers', $data, array('id' => $id));
	}

	public function single_account_with_user($id)
	{
		return $this->db->query("SELECT `customers`.*, users.fname, users.lname AS 'lastname', users.role FROM `customers` INNER JOIN users ON customers.users_id = users.id WHERE customers.id = '$id'")->result_array();
	}


	public function get_receipts()
	{
		$ci = get_instance();
		$date = $ci->mylibrary->getCurrentShamsiDate()['date'];
		return $this->db->query("SELECT `balance_sheet`.*, users.fname AS 'firstname', users.lname AS 'lastname', users.role, customers.name, customers.lname, customers.type FROM `balance_sheet` INNER JOIN `users` ON balance_sheet.users_id = users.id INNER JOIN customers ON balance_sheet.customers_id = customers.id WHERE DATE(`balance_sheet`.`shamsi`) BETWEEN DATE('" . $date . "') AND DATE('" . $date . "')  
    ORDER BY `balance_sheet`.`id` DESC")->result_array();
	}


	public function get_turns($date = null)
	{
		// Base SQL query
		$sql = "SELECT 
                turn.*, 
                patient.name, 
                patient.phone1, 
                patient.lname, 
                patient.serial_id, 
                patient.gender, 
                CONCAT(users.fname, ' - ', users.lname) AS doctor_name 
            FROM `turn` 
            INNER JOIN patient ON turn.patient_id = patient.id 
            INNER JOIN users ON turn.doctor_id = users.id 
            WHERE turn.status = 'p'";

		// Parameters for the query
		$params = [];

		// Add date condition if provided
		if (!is_null($date)) {
			$sql .= " AND turn.date = ?";
			$params[] = $date;
		}

		// Add sorting by from_time
		$sql .= " ORDER BY turn.from_time ASC";

		// Execute query with parameters
		return $this->db->query($sql, $params)->result_array();
	}


	public function get_turns_where($where)
	{
		return $this->db->query("SELECT turn.*, patient.name, patient.lname, patient.serial_id, patient.gender, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `turn` INNER JOIN patient ON turn.patient_id = patient.id INNER JOIN users ON turn.doctor_id = users.id WHERE turn.status = 'p' " . $where)->result_array();
	}


	public function get_turns_phonebook($date = null)
	{
		$ci = get_instance();
		$today = $ci->mylibrary->getCurrentShamsiDate()['date'];
		if (is_null($date)) {
			return $this->db->query("SELECT turn.*, patient.name, patient.lname, patient.serial_id, patient.gender, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `turn` INNER JOIN patient ON turn.patient_id = patient.id INNER JOIN users ON turn.doctor_id = users.id WHERE patient.status != 'b' AND turn.status = 'p' AND DATE(turn.date) < DATE('$today') ORDER BY `turn`.`from_time` ASC")->result_array();
		} else {
			return $this->db->query("SELECT turn.*, patient.name, patient.lname, patient.serial_id, patient.gender, CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' FROM `turn` INNER JOIN patient ON turn.patient_id = patient.id INNER JOIN users ON turn.doctor_id = users.id WHERE patient.status != 'b' AND turn.status = 'p' AND DATE(turn.date) < DATE('$today') AND turn.date = '$date' ORDER BY `turn`.`from_time` ASC")->result_array();
		}
	}


	public function get_turns_page($date = null, $doctor_id = null)
	{
		$ci = get_instance();
		$today = $ci->mylibrary->getCurrentShamsiDate()['date'];

		$query = "SELECT turn.*, patient.name, patient.lname, patient.serial_id, patient.gender, 
                     CONCAT(users.fname, ' - ', users.lname) AS 'doctor_name' 
              FROM `turn` 
              INNER JOIN patient ON turn.patient_id = patient.id 
              INNER JOIN users ON turn.doctor_id = users.id 
              WHERE patient.status != 'b' 
              AND turn.status = 'p' 
              AND DATE(turn.date) >= DATE('$today')";

		if (!empty($date)) {
			$query .= " AND turn.date = '$date'";
		}

		if (!empty($doctor_id)) {
			$query .= " AND turn.doctor_id = '$doctor_id'";
		}

		$query .= " ORDER BY `turn`.`from_time` ASC";

		return $this->db->query($query)->result_array();
	}


	public function get_turns_extra($extra = null)
	{
		// Base SQL query
		$sql = "SELECT 
                turn.*, 
                patient.name, 
                patient.phone1, 
                patient.lname, 
                patient.serial_id, 
                patient.gender, 
                CONCAT(users.fname, ' - ', users.lname) AS doctor_name 
            FROM `turn` 
            INNER JOIN patient ON turn.patient_id = patient.id 
            INNER JOIN users ON turn.doctor_id = users.id 
            WHERE turn.status = 'p'";

		// Add extra conditions if provided
		if (!empty($extra)) {
			$sql .= " " . $extra;
		}

		// Sort by `from_time` (start of the turn)
		$sql .= " ORDER BY `turn`.`from_time` ASC";

		// Execute query
		return $this->db->query($sql)->result_array();
	}


	public function single_turn($where = array())
	{
		return $this->db->get_where('turn', $where)->result_array();
	}

	function insert_turn($data = array())
	{
		return $this->db->insert('turn', $data);
	}

	public function update_turn($data = array(), $id)
	{
		return $this->db->update('turn', $data, array('id' => $id));
	}

	public function insert_turn_form($data = array())
	{
		$log = $this->db->insert('turn', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function delete_turn($where = array())
	{
		return $this->db->delete('turn', $where);
	}

	public function delete_lab($where = array())
	{
		return $this->db->delete('labs', $where);
	}

	public function get_teeth_with_prosthodontics($patient_id) {
		$this->db->select('tooth.*');
		$this->db->from('tooth');
		$this->db->join('prosthodontics', 'tooth.id = prosthodontics.tooth_id', 'inner');
		$this->db->where('tooth.patient_id', $patient_id);
		return $this->db->get()->result_array();
	}



	function insert_lab($data)
	{
		$log = $this->db->insert('labs', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function update_lab($data = array(), $where)
	{
		return $this->db->update('labs', $data, $where);
	}

	public function single_lab($where = array())
	{
		return $this->db->get_where('labs', $where)->result_array();
	}

	function single_lab_print($id)
	{
		return $this->db->query("SELECT `labs`.*, CONCAT(customers.name, ' - ', customers.lname) AS 'lab_name', patient.name, patient.lname, patient.serial_id, patient.gender FROM `labs` INNER JOIN customers ON labs.customers_id = customers.id INNER JOIN patient ON labs.patient_id = patient.id WHERE labs.id = '$id'")->result_array();
	}

	function check_turns($date, $doctor = null, $from_time = null, $to_time = null)
	{
		// Base SQL query
		$sql = "SELECT 
                patient.name, 
                patient.lname, 
                patient.serial_id, 
                CONCAT(users.fname, ' (', users.lname, ')') AS doctor_name, 
                turn.* 
            FROM `turn` 
            INNER JOIN patient ON turn.patient_id = patient.id 
            INNER JOIN users ON turn.doctor_id = users.id 
            WHERE turn.date = ? AND turn.status = 'p'";

		// Parameters for the query
		$params = [$date];

		// Add doctor condition if provided
		if (!is_null($doctor)) {
			$sql .= " AND turn.doctor_id = ?";
			$params[] = $doctor;
		}

		// Add time range condition if both from_time and to_time are provided
		if (!is_null($from_time) && !is_null($to_time)) {
			$sql .= " AND (turn.from_time < ? AND turn.to_time > ?)";
			$params[] = $to_time;  // Overlapping check
			$params[] = $from_time; // Overlapping check
		}

		// Add sorting
		$sql .= " ORDER BY turn.from_time ASC";

		// Execute query with parameters
		return $this->db->query($sql, $params)->result_array();
	}
	// Fetch doctor working hours
// Fetch doctor's working hours
	public function get_doctor_working_hours($doctor_id)
	{
		$this->db->select('working_start_time, working_end_time');
		$this->db->from('users');
		$this->db->where('id', $doctor_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	// Fetch doctor's leaves for the given date
	public function get_doctor_leaves($doctor_id, $date)
	{
		$this->db->select('leave_start_date, leave_end_date, reason');
		$this->db->from('doctor_leave');
		$this->db->where('doctor_id', $doctor_id);
		$this->db->where('leave_start_date <=', $date);
		$this->db->where('leave_end_date >=', $date);
		$query = $this->db->get();
		return $query->result_array();
	}

	// Fetch booked appointments for the given doctor on a specific date
	public function get_booked_appointments($doctor_id, $date)
	{
		$this->db->select('from_time, to_time');
		$this->db->from('turn');
		$this->db->where('doctor_id', $doctor_id);
		$this->db->where('DATE(date)', $date);  // Assuming the appointment date is stored in `appointment_date`
		$query = $this->db->get();
		return $query->result_array();
	}

	// Calculate available time slots considering working hours, leaves, and booked appointments
	public function calculate_available_time_slots($doctor_id, $date)
	{
		// Fetch working hours for the doctor
		$working_hours = $this->get_doctor_working_hours($doctor_id);
		if (empty($working_hours)) {
			return [];  // No working hours found for doctor
		}

		// Convert working start and end times to timestamps
		$working_start_time = strtotime($working_hours['working_start_time']);
		$working_end_time = strtotime($working_hours['working_end_time']);

		// Fetch doctor's leaves for the selected date
		$doctor_leaves = $this->get_doctor_leaves($doctor_id, $date);

		// If the doctor is on leave for the entire day, return this status
		foreach ($doctor_leaves as $leave) {
			if (strtotime($leave['leave_start_date']) <= strtotime($date) && strtotime($leave['leave_end_date']) >= strtotime($date)) {
				return [['range' => 'On Leave', 'status' => $leave['reason']]];
			}
		}

		// Fetch the booked slots (e.g., from appointments table)
		$booked_slots = $this->get_booked_appointments($doctor_id, $date);

		// Convert booked slots to timestamps for easier comparison
		$booked_ranges = [];
		foreach ($booked_slots as $slot) {
			$booked_ranges[] = [
				'start' => strtotime($slot['from_time']),
				'end' => strtotime($slot['to_time'])
			];
		}

		// Sort booked slots by start time
		usort($booked_ranges, function ($a, $b) {
			return $a['start'] <=> $b['start'];
		});

		// Generate available time slots
		$available_slots = [];
		$last_end_time = $working_start_time;  // Start at the working start time

		foreach ($booked_ranges as $slot) {
			// If there's a gap before the booked slot, we add it to the available slots
			if ($slot['start'] > $last_end_time) {
				$available_slots[] = [
					'range' => date('H:i', $last_end_time) . ' - ' . date('H:i', $slot['start']),
					'status' => 'Available'
				];
			}
			// Update the last end time after the current booked slot
			$last_end_time = max($last_end_time, $slot['end']);
		}

		// Add the final available slot after the last booked slot, if any
		if ($last_end_time < $working_end_time) {
			$available_slots[] = [
				'range' => date('H:i', $last_end_time) . ' - ' . date('H:i', $working_end_time),
				'status' => 'Available'
			];
		}

		return $available_slots;
	}

	public function check_turn_conflict($date, $doctor_id, $from_time, $to_time)
	{
		$this->db->where('date', $date);
		$this->db->where('doctor_id', $doctor_id);
		$this->db->where("('$from_time' < to_time AND '$to_time' > from_time)"); // Check for overlap
		$query = $this->db->get('turn');
		return $query->num_rows() > 0;
	}


	function turn_after_payment($turnId)
	{
		return $this->db->query("SELECT 
    patient.name, 
    patient.lname, 
    patient.serial_id, 
    CONCAT(users.fname, ' (', users.lname, ')') AS doctor_name, 
    CONCAT(paid_user.fname, ' ', paid_user.lname) AS paid_user_name, 
    turn.* 
FROM `turn` 
INNER JOIN patient ON turn.patient_id = patient.id 
INNER JOIN users AS users ON turn.doctor_id = users.id 
LEFT JOIN users AS paid_user ON turn.paid_user_id = paid_user.id 
WHERE turn.id = '$turnId'  
ORDER BY doctor_name ASC, paid_user_name ASC, turn.from_time ASC;
")->result_array();
	}


	function turn_by_patient($where = array())
	{
		return $this->db->get_where('turn', $where)->result_array();
	}

	function turns_factor($id)
	{
		return $this->db->query("SELECT 
    turn.*, 
    patient.name, 
    patient.lname, 
    patient.serial_id, 
    patient.gender, 
    CONCAT(users.fname, ' (', users.lname, ')') AS doctor_name, 
    CONCAT(paid_user.fname, ' ', paid_user.lname) AS paid_user_name
FROM `turn` 
INNER JOIN patient ON turn.patient_id = patient.id 
INNER JOIN users AS users ON turn.doctor_id = users.id 
LEFT JOIN  users AS paid_user ON turn.paid_user_id = paid_user.id  WHERE turn.id = '$id'")->result_array();
	}


	function change_status_turn($status, $data = array())
	{
		return $this->db->update('turn', array('status' => $status), $data);
	}


	function get_serial($date)
	{
		$result = $this->db->query("SELECT serial_id FROM `patient` WHERE serial_id LIKE '$date%' ORDER BY serial_id DESC")->result_array();

		if (count($result) !== 0) {
			return $result[0]['serial_id'];
		} else {
			return $date . '0';
		}
	}

	public function delete_receipt($where = array())
	{
		return $this->db->delete('balance_sheet', $where);
	}

	public function insert_patient($data = array())
	{
		$log = $this->db->insert('patient', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function insert_temp_patient($data = array())
	{
		$log = $this->db->insert('temp_patient', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}


	public function profile_patient($id)
	{
		return $this->db->query("SELECT `patient`.*, CONCAT(users.fname, ' (', users.lname, ')') AS doctor_name FROM `patient` INNER JOIN users ON patient.doctor_id = users.id WHERE patient.id = '$id'")->result_array();
	}

	public function single_temp_patient($id)
	{
		return $this->db->get_where('temp_patient', array('id' => $id))->result_array();
	}

	public function update_tooth($tooth_id, $data) {
		$this->db->where('id', $tooth_id);
		return $this->db->update('tooth', $data);
	}

	public function update_endo($tooth_id, $data) {
		$this->db->where('tooth_id', $tooth_id);
		return $this->db->update('endo', $data);
	}

	public function update_restorative($tooth_id, $data) {
		$this->db->where('tooth_id', $tooth_id);
		return $this->db->update('restorative', $data);
	}

	public function update_prosthodontics($tooth_id, $data) {
		$this->db->where('tooth_id', $tooth_id);
		return $this->db->update('prosthodontics', $data);
	}

	// **Delete Old Restorative Basic Info**
	public function delete_restorative_basic_info($tooth_id) {
		$this->db->where('restorative_id', $tooth_id);
		return $this->db->delete('restorative_has_basic_information_teeth');
	}

	// **Delete Old Prosthodontic Basic Info**
	public function delete_prosthodontics_basic_info($tooth_id) {
		$this->db->where('prosthodontics_id', $tooth_id);
		return $this->db->delete('prosthodontics_has_basic_information_teeth');
	}


	// **Delete Old Endo Services**
	public function delete_endo_services($tooth_id) {
		$this->db->where('endo_id', $tooth_id);
		return $this->db->delete('endo_has_services');
	}

	// **Delete Old Restorative Services**
	public function delete_restorative_services($tooth_id) {
		$this->db->where('restorative_id', $tooth_id);
		return $this->db->delete('restorative_has_services');
	}

	// **Delete Old Prosthodontic Services**
	public function delete_prosthodontics_services($tooth_id) {
		$this->db->where('prosthodontics_id', $tooth_id);
		return $this->db->delete('prosthodontics_has_services');
	}

	// **Delete Old Endo Basic Info**
	public function delete_endo_basic_info($tooth_id) {
		$this->db->where('endo_id', $tooth_id);
		return $this->db->delete('endo_has_basic_information_teeth');
	}


	// **Delete Old Tooth Diagnoses**
	public function delete_tooth_diagnoses($tooth_id) {
		$this->db->where('tooth_id', $tooth_id);
		return $this->db->delete('tooth_has_diagnose');
	}

	// **Insert New Tooth Diagnoses**
	public function insert_tooth_has_diagnose($data) {
		return $this->db->insert('tooth_has_diagnose', $data);
	}



	public function list_insert_tooth_basic_information($category_name, $department = 'restorative')
	{
		return $this->db->query("SELECT id, name FROM `basic_information_teeth` WHERE categories_id IN (SELECT id from categories WHERE name = '$category_name') AND department = '$department'")->result_array();
	}

	function get_teeth_by_id($id)
	{
		return $this->db->get_where('tooth', array('patient_id' => $id))->result_array();
	}

	function get_teeth_by_id_with_diagnose($id)
	{
		return $this->db->query("
		SELECT `tooth`.*,
		   endo.services               AS 'endo_services',
		   restorative.services        AS 'restorative_services',
		   prosthodontics.services        AS 'prosthodontics_services',
		   GROUP_CONCAT(diagnose.name) AS 'diagnose'
		FROM `tooth`
			 LEFT JOIN endo ON tooth.id = endo.tooth_id
			 LEFT JOIN restorative ON tooth.id = restorative.tooth_id
			 LEFT JOIN prosthodontics ON tooth.id = prosthodontics.tooth_id
			 INNER JOIN tooth_has_diagnose ON tooth.id = tooth_has_diagnose.tooth_id
			 INNER JOIN diagnose ON tooth_has_diagnose.diagnose_id = diagnose.id
		WHERE tooth.patient_id = '$id'
		GROUP BY tooth.id
		")->result_array();
	}

	function delete_tooth($where = array())
	{
		return $this->db->delete('tooth', $where);
	}

	function insert_tooth($data = array())
	{
		$log = $this->db->insert('tooth', $data);
		$id = $this->db->insert_id();
		return array($log, $id);
	}


	// Start Endo

	public function insert_endo($data = array())
	{
		$log = $this->db->insert('endo', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function insert_endo_basic_info($data = array())
	{
		$log = $this->db->insert('endo_has_basic_information_teeth', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function single_endo_by_tooth_id($tooth_id)
	{
		return $this->db->get_where('endo', array('tooth_id' => $tooth_id))->result_array();
	}

	function endo_basic_information_by_id($id)
	{
		return $this->db->query("SELECT endo_has_basic_information_teeth.*, categories.name AS 'category_name' FROM `endo_has_basic_information_teeth` INNER JOIN basic_information_teeth ON endo_has_basic_information_teeth.basic_information_teeth_id = basic_information_teeth.id INNER JOIN categories ON basic_information_teeth.categories_id = categories.id WHERE endo_has_basic_information_teeth.endo_id = '$id'")->result_array();
	}

	// End Endo


	// Start restorative

	public function insert_restorative($data = array())
	{
		$log = $this->db->insert('restorative', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function insert_restorative_basic_info($data = array())
	{
		$log = $this->db->insert('restorative_has_basic_information_teeth', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function single_restorative_by_tooth_id($tooth_id)
	{
		return $this->db->get_where('restorative', array('tooth_id' => $tooth_id))->result_array();
	}

	function restorative_basic_information_by_id($id)
	{
		return $this->db->query("SELECT restorative_has_basic_information_teeth.*, categories.name AS 'category_name' FROM `restorative_has_basic_information_teeth` INNER JOIN basic_information_teeth ON restorative_has_basic_information_teeth.basic_information_teeth_id = basic_information_teeth.id INNER JOIN categories ON basic_information_teeth.categories_id = categories.id WHERE restorative_has_basic_information_teeth.restorative_id = '$id'")->result_array();
	}

	// End restorative

	// Start prosthodontics

	public function insert_prosthodontics($data = array())
	{
		$log = $this->db->insert('prosthodontics', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	public function insert_prosthodontics_basic_info($data = array())
	{
		$log = $this->db->insert('prosthodontics_has_basic_information_teeth', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function single_prosthodontic_by_tooth_id($tooth_id)
	{
		return $this->db->get_where('prosthodontics', array('tooth_id' => $tooth_id))->result_array();
	}

	function prosthodontics_basic_information_by_id($id)
	{
		return $this->db->query("SELECT prosthodontics_has_basic_information_teeth.Prosthodontics_id, GROUP_CONCAT(prosthodontics_has_basic_information_teeth.basic_information_teeth_id) AS 'basic_information_teeth_id', categories.name AS 'category_name' FROM `prosthodontics_has_basic_information_teeth` INNER JOIN basic_information_teeth ON prosthodontics_has_basic_information_teeth.basic_information_teeth_id = basic_information_teeth.id INNER JOIN categories ON basic_information_teeth.categories_id = categories.id WHERE prosthodontics_has_basic_information_teeth.prosthodontics_id = '$id' GROUP BY category_name")->result_array();
	}

	// End prosthodontics

	function get_turn_by_id($id)
	{
		return $this->db->get_where('turn', array('patient_id' => $id))->result_array();
	}

	function update_patient($data, $id)
	{
		return $this->db->update('patient', $data, array('id' => $id));
	}

	function update_temp_patient($data, $id)
	{
		return $this->db->update('temp_patient', $data, array('id' => $id));
	}

	function turns_by_patient_id($patient_id)
	{
		return $this->db->query("SELECT 
    `turn`.*, 
    CONCAT(doctor.fname, ' ', doctor.lname) AS doctor_name,
    CONCAT(paid_user.fname, ' ', paid_user.lname) AS paid_user_name
FROM `turn` 
INNER JOIN users AS doctor ON turn.doctor_id = doctor.id
LEFT JOIN users AS paid_user ON turn.paid_user_id = paid_user.id WHERE turn.patient_id = '$patient_id' ORDER BY turn.date DESC, turn.from_time ASC")->result_array();
	}

	function table_by_patient($tablename, $patient_id)
	{
		return $this->db->get_where($tablename, array('patient_id' => $patient_id))->result_array();
	}

	function delete_patient($where = array())
	{
		return $this->db->delete('patient', $where);
	}

	function remove_temp($id)
	{
		return $this->db->delete('temp_patient', array('id' => $id));
	}

	function get_doctors()
	{
		return $this->db->get_where('users', array('role' => 'D', 'status' => 'A'))->result_array();
	}


	public function get_medicines()
	{
		return $this->db->get('medicine')->result_array();
	}

	function delete_medicine($where)
	{
		return $this->db->delete('medicine', $where);
	}

	function single_medicine($where)
	{
		return $this->db->get_where('medicine', $where)->result_array();
	}

	function check_medicine_from_prescription($medicine_id)
	{
		return $this->db->query("SELECT * FROM `prescription` WHERE medicine_1 = '$medicine_id' OR medicine_2 = '$medicine_id' OR medicine_3 = '$medicine_id' OR medicine_4 = '$medicine_id' OR medicine_5 = '$medicine_id'")->result_array();
	}

	public function insert_medicine($data = array())
	{
		$log = $this->db->insert('medicine', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function update_medicine($data = array(), $id)
	{
		return $this->db->update('medicine', $data, array('id' => $id));
	}
	// End Medicine


	// Start Diagnoses

	function get_diagnoses()
	{
		return $this->db->get('diagnose')->result_array();
	}

	function tooth_has_diagnose($id, $type = 'diagnose')
	{
		if ($type == 'diagnose') {
			return $this->db->get_where('tooth_has_diagnose', array('diagnose_id' => $id))->result_array();
		} else {
			return $this->db->get_where('tooth_has_diagnose', array('tooth_id' => $id))->result_array();
		}
	}

	function delete_diagnose($where)
	{
		return $this->db->delete('diagnose', $where);
	}

	public function insert_diagnose($data = array())
	{
		$log = $this->db->insert('diagnose', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function single_diagnose($where)
	{
		return $this->db->get_where('diagnose', $where)->result_array();
	}

	public function update_diagnose($data = array(), $id)
	{
		return $this->db->update('diagnose', $data, array('id' => $id));
	}



	// End Diagnoses

	// start prescription


	function single_prescription($where)
	{
		return $this->db->get_where('prescription', $where)->result_array();
	}

	function list_prescription_patient($patient_id)
	{
		return $this->db->query("SELECT prescription.id, CONCAT(users.fname, ' - ', users.lname) AS 'user_name', date_time FROM `prescription` INNER JOIN users ON prescription.users_id = users.id WHERE prescription.patient_id = '$patient_id'")->result_array();
	}

	public function insert_prescription($data = array())
	{
		$log = $this->db->insert('prescription', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function single_prescription_print($id)
	{
		return $this->db->query("SELECT `prescription`.*, patient.name, patient.lname, patient.address, patient.age FROM `prescription` INNER JOIN patient ON prescription.patient_id = patient.id WHERE prescription.id = '$id'")->result_array();
	}
	// end prescription


	// Start Files
	function get_files_by_patient($id)
	{
		return $this->db->get_where('files', array('patient_id' => $id))->result_array();
	}

	public function insert_files($data = array())
	{
		$log = $this->db->insert('files', $data);
		$id = $this->db->insert_id();

		return array($log, $id);
	}

	function file_by_id($id)
	{
		return $this->db->get_where('files', array('id' => $id))->result_array();
	}

	function delete_file($where)
	{
		return $this->db->delete('files', $where);
	}

	// End Files


	// start home page

	function find_sum_price_tooth($date = null)
	{
		return $this->db->query("SELECT SUM(price) AS sum_price FROM `tooth` WHERE DATE(create_date) = '$date' ORDER BY `tooth`.`create_date`  DESC")->result_array();
	}

	function find_sum_paid_turn($date = null)
	{
		return $this->db->query("SELECT SUM(cr) AS 'sum_cr' FROM `turn` WHERE DATE(pay_date) = '$date'")->result_array();
	}

	function find_sum_dr_balance_sheet($date = null)
	{
		return $this->db->query("SELECT SUM(dr) AS sum_dr FROM `balance_sheet` WHERE DATE(shamsi) = '$date'")->result_array();
	}

	// end home page
}
