<?php


class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function select_user($id)
	{
		return $this->db->get_where('users', array('id' => $id))->result();
	}

	public function auth($email, $password)
	{
		$this->db->select("*");
		$this->db->from("users");
		$this->db->where("username", $email);
		$this->db->where("password", $password);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
}