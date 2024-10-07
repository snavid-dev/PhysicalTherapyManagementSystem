<?php


	class Users_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_user($id)
		{
			return $this->db->query("SELECT `users`.*, `users_document`.* FROM `users` LEFT JOIN `users_document` ON users.id = users_document.users_id WHERE users.id = " . $id)->result();
		}

		public function single_user($id)
		{
			return $this->db->get_where('users', array('id' => $id))->result();
		}

		public function edit_user($id, $data)
		{
			return $this->db->where('id', $id)->update('users', $data);
		}
		
		public function upload_document($datas)
		{
			return $this->db->insert('users_document', $datas);
		}

		public function get_single_user($id)
		{
			return $this->db->get_where('users', array('id' => $id))->result();
		}

		public function edit_document($datas, $old_cv)
		{
			return $this->db->update('users_document', $datas, array('filename' => $old_cv));
		}

	}
