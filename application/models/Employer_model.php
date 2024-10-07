<?php

class Employer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function jobs($user_id)
    {
        return $this->db->get_where('opportunity', array('users_id' => $user_id, 'opportunity_type' => 'job'))->result();
    }

    public function tasks($user_id)
    {
        return $this->db->get_where('opportunity', array('users_id' => $user_id, 'opportunity_type' => 'task'))->result();
    }

    public function proposals($user_id)
    {
        return $this->db->get_where('rfp', array('users_id' => $user_id))->result();
    }
    public function quotations($user_id)
    {
        return $this->db->get_where('rfq', array('users_id' => $user_id))->result();
    }

    public function insert_job($data)
    {
        return $this->db->insert('opportunity', $data);
    }

    public function get_jobs($id)
    {
        return $this->db->query("SELECT opportunity.job_title, opportunity.id, opportunity.slug, opportunity.status, date(opportunity.expire) AS `expire`, date(opportunity.create) AS 'creates' , DATEDIFF(opportunity.expire, NOW()) as 'diff', date(opportunity.create) AS `posted` FROM `opportunity` WHERE opportunity.users_id = " . $id . " AND opportunity_type = 'job' ORDER BY `opportunity`.`expire` DESC")->result();
    }

    public function get_tasks($id)
    {
        return $this->db->query("SELECT opportunity.job_title, DATE(opportunity.create) AS 'creates', opportunity.slug, opportunity.id, opportunity.type, opportunity.min_salary, opportunity.max_salary, opportunity.status, date(opportunity.expire) AS `expire` , DATEDIFF(opportunity.expire, NOW()) as 'diff', date(opportunity.create) AS `posted` FROM `opportunity` WHERE opportunity.users_id = " . $id . " AND opportunity_type = 'task' ORDER BY `opportunity`.`expire` DESC")->result();
    }

    public  function candidate($id)
    {
        return $this->db->query("SELECT * FROM `apply` WHERE (apply.status = 'P' OR apply.status = 'A') AND opportunity_id = " . $id . "")->result();
    }

    public function get_u_jobs($id, $job_id)
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE id=" . $job_id . " && users_id =" . $id . " && opportunity_type = 'job'")->result();
    }

    public function get_u_tasks($id, $job_id)
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE id=" . $job_id . " && users_id =" . $id . " && opportunity_type = 'task'")->result();
    }

    public function edit_job($user_id, $post_id, $data)
    {
        return $this->db->update('opportunity', $data, array('users_id' => $user_id, 'id' => $post_id));
    }

    public function delete_job($job_id, $user_id)
    {
        return $this->db->delete('opportunity', array('users_id' => $user_id, 'id' => $job_id, 'opportunity_type' => 'job'));
    }

    public function delete_task($task_id, $user_id)
    {
        return $this->db->delete('opportunity', array('users_id' => $user_id, 'id' => $task_id, 'opportunity_type' => 'task'));
    }

    public function ins_rfp($data)
    {
        return $this->db->insert('RFP', $data);
    }

    public function manage_rfp($id)
    {
        return $this->db->query("SELECT id, title, status, expire_date , slug, date(rfp.create) as 'creates', DATEDIFF(rfp.expire_date, NOW()) as 'diff' FROM `rfp` WHERE users_id = " . $id)->result();
    }

    public function get_single_rfp($id, $user_id)
    {
        return $this->db->query("SELECT * FROM rfp WHERE id = " . $id . " AND users_id = " . $user_id)->result();
    }

    public function edit_rfp($data, $user_id, $id)
    {
        return $this->db->update('rfp', $data, array('users_id' => $user_id, 'id' => $id));
    }

    public function get_u_rfp($id, $rfp_id)
    {
        return $this->db->query("SELECT * FROM `rfp` WHERE id=" . $rfp_id . " && users_id =" . $id)->result();
    }

    public function delete_rfp($rfp_id, $user_id)
    {
        return $this->db->delete('rfp', array('users_id' => $user_id, 'id' => $rfp_id));
    }

    public function ins_rfq($data)
    {
        return $this->db->insert('rfq', $data);
    }

    public function manage_rfq($id)
    {
        return $this->db->query("SELECT id, title, status, expire_date , date(rfq.create) as 'creates', DATEDIFF(rfq.expire_date, NOW()) as 'diff' FROM `rfq` WHERE users_id = " . $id)->result();
    }

    public function get_u_rfq($id, $rfq_id)
    {
        return $this->db->query("SELECT * FROM `rfq` WHERE id=" . $rfq_id . " && users_id =" . $id)->result();
    }

    public function get_single_rfq($id, $user_id)
    {
        return $this->db->query("SELECT * FROM `rfq` WHERE id = " . $id . " AND users_id = " . $user_id)->result();
    }

    public function edit_rfq($data, $user_id, $id)
    {
        return $this->db->update('rfq', $data, array('users_id' => $user_id, 'id' => $id));
    }

    public function delete_rfq($rfq_id, $user_id)
    {
        return $this->db->delete('rfq', array('users_id' => $user_id, 'id' => $rfq_id));
    }

    public function job_info($date, $slug)
    {
        return $this->db->query("SELECT job_title, id, slug, date(opportunity.create) AS 'creates' FROM `opportunity` WHERE date(opportunity.create) = '" . $date . "' AND opportunity.opportunity_type = 'job' AND  
        slug = '" . $slug . "'")->result();
    }

    public function manage_candidate($id)
    {
        return $this->db->query("SELECT `apply`.*, `apply`.id AS 'ids', `users`.*, `apply`.status AS 'u_status', `users_document`.`filename` FROM `apply` INNER JOIN `users` ON `apply`.users_id = `users`.id LEFT JOIN users_document ON users.id = users_document.users_id WHERE (apply.status = 'P' OR apply.status = 'A') AND opportunity_id = " . $id . " AND apply.type = 'apply'")->result();
    }

    public function other_apply($employer_id, $freelancer_id)
    {
        return $this->db->query("SELECT opportunity.job_title, DATE(opportunity.create) AS 'creates', opportunity.slug FROM `apply` INNER JOIN opportunity ON opportunity.id = apply.opportunity_id WHERE opportunity.users_id = '". $employer_id ."' AND apply.users_id = '". $freelancer_id ."' AND DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.status = 'A'")->result();
    }

    public function check_user_candidate($user_id, $date, $slug)
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE users_id = " . $user_id . " AND DATE(opportunity.create) = '" . $date . "' AND slug = '" . $slug . "'")->result();
    }

    public function check_remove_candidate($user_id, $job_id)
    {
        return $this->db->get_where('opportunity', array('users_id' => $user_id, 'id' => $job_id))->result();
    }

    public function select_apply($id)
    {
        return $this->db->get_where('apply', array('id' => $id))->result();
    }

    public function remove_candidate($id)
    {
        return $this->db->update('apply', array('status' => 'B'), array('id' => $id));
    }

    public function accept_candidate($id)
    {
        return $this->db->update('apply', array('status' => 'A'), array('id' => $id));
    }

    public function task_info($date, $slug)
    {
        return $this->db->query("SELECT job_title, id, slug, date(opportunity.create) AS 'creates' FROM `opportunity` WHERE date(opportunity.create) = '" . $date . "' AND opportunity.opportunity_type = 'task' AND  
        slug = '" . $slug . "'")->result();
    }

    public function manage_bidder_lowest($id)
    {
        return $this->db->query("SELECT `apply`.*, `apply`.id AS 'ids', `users`.*,`apply`.status AS 'u_status', `users_document`.`filename` FROM `apply` INNER JOIN `users` ON `apply`.users_id = `users`.id LEFT JOIN users_document ON users.id = users_document.users_id WHERE (apply.status = 'P' OR apply.status = 'A') AND opportunity_id = " . $id . " AND apply.type = 'bid' ORDER BY `apply`.`minimal_rate` ASC")->result();
    }

    public function manage_bidder_highest($id)
    {
        return $this->db->query("SELECT `apply`.*, `apply`.id AS 'ids', `users`.*,`apply`.status AS 'u_status', `users_document`.`filename` FROM `apply` INNER JOIN `users` ON `apply`.users_id = `users`.id LEFT JOIN users_document ON users.id = users_document.users_id WHERE (apply.status = 'P' OR apply.status = 'A') AND opportunity_id = " . $id . " AND apply.type = 'bid' ORDER BY `apply`.`minimal_rate` DESC")->result();
    }

    public function manage_bidder($id)
    {
        return $this->db->query("SELECT `apply`.*, `apply`.id AS 'ids', `users`.*, `apply`.status AS 'u_status', `users_document`.`filename` FROM `apply` INNER JOIN `users` ON `apply`.users_id = `users`.id LEFT JOIN users_document ON users.id = users_document.users_id WHERE (apply.status = 'P' OR apply.status = 'A') AND opportunity_id = " . $id . " AND apply.type = 'bid' ORDER BY `apply`.`create` DESC")->result();
    }

    public function check_user_bidder($user_id, $date, $slug)
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE users_id = " . $user_id . " AND DATE(opportunity.create) = '" . $date . "' AND slug = '" . $slug . "'")->result();
    }
}
