<?php

class Freelancer_model extends CI_Model{


   public function __construct()
   {
       parent::__construct();
   } 


   public function apply($users_id)
   {
       return $this->db->query("SELECT apply.*, apply.id AS 'ids',   DATE(apply.create) AS 'creates',DATE(opportunity.expire) AS 'expires', opportunity.job_title, opportunity.expire, opportunity.id, users.photo, users.slug FROM apply INNER JOIN opportunity ON apply.opportunity_id = opportunity.id INNER JOIN users ON opportunity.users_id = users.id WHERE apply.type = 'apply' AND apply.users_id = " . $users_id)->result();
   }

   public function check_user_apply($id, $users_id)
   {
       return $this->db->query("SELECT * FROM `apply` WHERE id = ". $id . " AND users_id = " . $users_id)->result();
   }

   public function remove_apply($id)
   {
       return $this->db->delete('apply', array('id' => $id));
   }

   public function bids($users_id)
   {
       return $this->db->query("SELECT apply.*, apply.id AS 'ids', opportunity.slug AS 'slugs', opportunity.type AS 'types',  DATE(apply.create) AS 'creates',DATE(opportunity.expire) AS 'expires', opportunity.job_title, opportunity.expire, opportunity.id, users.photo, users.slug FROM apply INNER JOIN opportunity ON apply.opportunity_id = opportunity.id INNER JOIN users ON opportunity.users_id = users.id WHERE apply.type = 'bid' AND apply.users_id = " . $users_id)->result();
   }

   public function tasks($id)
   {
       return $this->db->get_where('apply', array('users_id' => $id, 'status' => 'A'))->result();
   }

}