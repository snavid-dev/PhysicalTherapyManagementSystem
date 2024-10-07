<?php

class Home_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function num_opportunity($type)
    {
        return $this->db->get_where('opportunity', array('opportunity_type' => $type, 'status' => "A"))->num_rows();
    }

    public function num_users($type)
    {
        return $this->db->get_where('users', array('type' => $type, 'status' => "A"))->num_rows();
    }

    public function categories()
    {
        return $this->db->query("SELECT COUNT(opportunity.category) AS 'Count_category' , category, category_slug, opportunity_type  FROM `opportunity` WHERE opportunity.status = 'A' AND DATEDIFF(opportunity.expire, NOW()) >= 0  GROUP BY category  
            ORDER BY Count_category  DESC LIMIT 8")->result();
    }

    public function get_all_provinces()
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE opportunity.status = 'A' AND DATEDIFF(opportunity.expire, NOW()) >= 0")->result();
    }

    public function opp()
    {
        // $this->db->select('province');
        $this->db->like('province', 'Anywhere');
        return $this->db->get('opportunity')->result();
    }

    function SBC($category_slug, $type, $limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = '" . $type . "' AND  opportunity.status = 'A' AND opportunity.category_slug = '" . $category_slug . "'" . " LIMIT " . $limit . " OFFSET " . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = '" . $type . "' AND  opportunity.status = 'A' AND opportunity.category_slug = '" . $category_slug . "'" . ' LIMIT ' . $limit)->result();
        } else {
            return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = '" . $type . "' AND  opportunity.status = 'A' AND opportunity.category_slug = '" . $category_slug . "'")->result();
        }
    }


    function num_rows_SBC($category_slug, $type)
    {
        return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = '" . $type . "' AND  opportunity.status = 'A' AND opportunity.category_slug = '" . $category_slug . "'")->num_rows();
    }


    public function get_category_name($category_slug)
    {
        return $this->db->query("SELECT category FROM `opportunity` WHERE opportunity.category_slug = '" . $category_slug . "'")->result();
    }

    public function single_job($date, $slug)
    {
        return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates',  opportunity.status AS 'o_status', DATEDIFF(NOW(), opportunity.create) AS 'diff', DATEDIFF(DATE(opportunity.expire), NOW()) AS 'expires', users.company_name, users.about, users.photo, users.slug AS 'slugs', users.status FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE date(opportunity.create) = '" . $date . "' AND opportunity.slug = '" . $slug . "' ")->result();
    }

    public function similar_jobs()
    {
        return $this->db->query("SELECT opportunity.*, users.company_name,DATEDIFF(NOW(), opportunity.create) AS 'diff', users.photo FROM `opportunity` INNER JOIN users ON opportunity.users_id = users.id WHERE opportunity.opportunity_type = 'job' AND DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.status = 'A' ORDER BY `opportunity`.`featured` DESC LIMIT 2 ")->result();
    }

    public function check_slug($slug, $date)
    {
        return $this->db->query("SELECT * FROM `opportunity` WHERE opportunity.slug = '" . $slug . "' AND date(opportunity.create) = '" . date($date) . "'")->result();
    }

    public function user_status($id)
    {
        return $this->db->query("SELECT users.status FROM `users` WHERE users.id = " . $id)->result();
    }

    public function apply_before($users_id, $opportunity)
    {
        return $this->db->get_where('apply', array('users_id' => $users_id, 'opportunity_id' => $opportunity))->result();
    }

    public function apply($data)
    {
        return $this->db->insert('apply', $data);
    }

    public function show_bidders($id)
    {
        return $this->db->query("SELECT apply.*, users.id, users.photo, users.slug, users.fullname FROM `apply` INNER JOIN users ON apply.users_id = users.id WHERE apply.type = 'bid' AND opportunity_id = " . $id . " ORDER BY `apply`.`minimal_rate` ASC")->result();
    }

    public function show_rfp($limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT rfp.*, users.company_name, DATE(rfp.create) AS 'creates', DATE(rfp.expire_date) AS 'expires', users.photo, users.id FROM `rfp` INNER JOIN users ON rfp.users_id = users.id WHERE rfp.status = 'A' LIMIT " . $limit . " OFFSET " . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT rfp.*, users.company_name, DATE(rfp.create) AS 'creates', DATE(rfp.expire_date) AS 'expires', users.photo, users.id FROM `rfp` INNER JOIN users ON rfp.users_id = users.id WHERE rfp.status = 'A' LIMIT " . $limit)->result();
        }
    }

    public function num_rows_rfp()
    {
        return $this->db->query("SELECT rfp.*, users.company_name, DATE(rfp.create) AS 'creates', DATE(rfp.expire_date) AS 'expires', users.photo, users.id FROM `rfp` INNER JOIN users ON rfp.users_id = users.id WHERE rfp.status = 'A'")->num_rows();
    }

    public function single_rfp($date, $slug)
    {
        return $this->db->query("SELECT rfp.*, users.company_name, users.slug AS 'slugs', users.about, DATE(rfp.create) AS 'creates', DATEDIFF(DATE(rfp.expire_date), NOW()) AS 'diff',DATEDIFF(NOW(), rfp.create) AS 'diffs', DATE(rfp.expire_date) AS 'expires', users.photo, users.id FROM `rfp` INNER JOIN users ON rfp.users_id = users.id WHERE date(rfp.create) = '" . $date . "' AND rfp.slug = '" . $slug . "'")->result();
    }

    public function check_user_job($date, $slug, $user_id)
    {
        return $this->db->query("SELECT * FROM opportunity WHERE DATE(opportunity.create) = '" . $date . "' AND slug = '" . $slug . "' AND users_id = " . $user_id)->result();
    }

    public function check_user_rfp($date, $slug, $users_id)
    {
        return $this->db->query("SELECT rfp.*, users.company_name, DATE(rfp.create) AS 'creates', DATE(rfp.expire_date) AS 'expires', users.photo, users.id FROM `rfp` INNER JOIN users ON rfp.users_id = users.id WHERE date(rfp.create) = '" . $date . "' AND rfp.slug = '" . $slug . "' AND rfp.users_id = " . $users_id)->result();
    }

    public function show_rfq($limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT rfq.*, users.company_name, DATE(rfq.create) AS 'creates', DATE(rfq.expire_date) AS 'expires', users.photo, users.id FROM `rfq` INNER JOIN users ON rfq.users_id = users.id WHERE rfq.status = 'A' LIMIT" . $limit . " OFFSET " . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT rfq.*, users.company_name, DATE(rfq.create) AS 'creates', DATE(rfq.expire_date) AS 'expires', users.photo, users.id FROM `rfq` INNER JOIN users ON rfq.users_id = users.id WHERE rfq.status = 'A' LIMIT " . $limit)->result();
        }
    }

    public function num_rows_rfq()
    {
        return $this->db->query("SELECT rfq.*, users.company_name, DATE(rfq.create) AS 'creates', DATE(rfq.expire_date) AS 'expires', users.photo, users.id FROM `rfq` INNER JOIN users ON rfq.users_id = users.id WHERE rfq.status = 'A'")->num_rows();
    }

    public function single_rfq($date, $slug)
    {
        return $this->db->query("SELECT rfq.*, users.company_name, users.about, users.slug AS 'slugs', DATE(rfq.create) AS 'creates', DATEDIFF(DATE(rfq.expire_date), NOW()) AS 'diff',DATEDIFF(NOW(), rfq.create) AS 'diffs', DATE(rfq.expire_date) AS 'expires', users.photo, users.id FROM `rfq` INNER JOIN users ON rfq.users_id = users.id WHERE date(rfq.create) = '" . $date . "' AND rfq.slug = '" . $slug . "'")->result();
    }

    public function check_user_rfq($date, $slug, $users_id)
    {
        return $this->db->query("SELECT rfq.*, users.company_name, DATE(rfq.create) AS 'creates', DATE(rfq.expire_date) AS 'expires', users.photo, users.id FROM `rfq` INNER JOIN users ON rfq.users_id = users.id WHERE date(rfq.create) = '" . $date . "' AND rfq.slug = '" . $slug . "' AND rfq.users_id = " . $users_id)->result();
    }

    public function featured_jobs()
    {
        return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0  AND  opportunity.status = 'A' AND opportunity.featured = 1 AND opportunity_type = 'job' ORDER BY `opportunity`.`create` DESC")->result();
    }

    public function freelancers($extra = null, $limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT * FROM `users` WHERE status = 'A' AND type = 'freelancer' " . $extra . "LIMIT " . $limit . " OFFSET " . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT * FROM `users` WHERE status = 'A' AND type = 'freelancer' " . $extra . "LIMIT " . $limit)->result();
        } else {
            return $this->db->query("SELECT * FROM `users` WHERE status = 'A' AND type = 'freelancer' " . $extra)->result();
        }
    }

    public function num_rows_freelancers($extra)
    {
        return $this->db->query("SELECT * FROM `users` WHERE status = 'A' AND type = 'freelancer' " . $extra)->num_rows();
    }

    public function jobs($extra = null, $limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, users.slug AS 'slugs', opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = 'job' AND opportunity.status = 'A'" . $extra . 'LIMIT ' . $limit . ' OFFSET ' . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, users.slug AS 'slugs', opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = 'job' AND opportunity.status = 'A'" . $extra . 'LIMIT ' . $limit)->result();
        }
    }

    public function num_rows_jobs($extra = null)
    {
        return $this->db->query("SELECT opportunity.job_title, date(opportunity.create) AS 'creates', opportunity.min_salary, opportunity.max_salary, opportunity.address, opportunity.skills, opportunity.expire, opportunity.province, opportunity.slug, opportunity.type, DATEDIFF(NOW(), opportunity.create) AS 'diff', users.company_name, users.photo FROM opportunity INNER JOIN users ON opportunity.users_id = users.id WHERE DATEDIFF(opportunity.expire, NOW()) >= 0 AND opportunity.opportunity_type = 'job' AND opportunity.status = 'A'" . $extra)->num_rows();
    }

    public function tasks($extra = null, $limit = null, $offset = null)
    {
        if (!empty($limit) && !empty($offset)) {
            return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates', DATEDIFF(NOW(), opportunity.create) AS 'diff' FROM `opportunity` WHERE DATEDIFF(expire, NOW()) >=0 AND status = 'A' AND opportunity_type = 'task'" . $extra . " ORDER BY `opportunity`.`create` DESC"  . ' LIMIT ' . $limit . ' OFFSET ' . $offset)->result();
        } elseif (!empty($limit) && empty($offset)) {
            return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates', DATEDIFF(NOW(), opportunity.create) AS 'diff' FROM `opportunity` WHERE DATEDIFF(expire, NOW()) >=0 AND status = 'A' AND opportunity_type = 'task'" . $extra . " ORDER BY `opportunity`.`create` DESC"  . ' LIMIT ' . $limit)->result();
        }
    }

    public function num_rows_tasks($extra = null)
    {
        return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates', DATEDIFF(NOW(), opportunity.create) AS 'diff' FROM `opportunity` WHERE DATEDIFF(expire, NOW()) >=0 AND status = 'A' AND opportunity_type = 'task'" . $extra . " ORDER BY `opportunity`.`create` DESC")->num_rows();
    }

    public function select_company($extra = null)
    {
        return $this->db->query("SELECT * FROM `users` WHERE users.type = 'employer' AND users.status = 'A' " . $extra)->result();
    }

    public function check_company($slug)
    {
        return $this->db->get_where('users', array('slug' => $slug, 'type' => 'employer'))->result();
    }

    public function jobs_where($user_id)
    {
        return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates', DATEDIFF(NOW(), opportunity.create) AS 'diff' FROM `opportunity` WHERE users_id = " . $user_id . " AND DATEDIFF(expire, NOW()) >=0 AND status = 'A' ORDER BY `opportunity`.`create` DESC")->result();
    }

    public function job_where($user_id)
    {
        return $this->db->query("SELECT opportunity.*, DATE(opportunity.create) AS 'creates', DATEDIFF(NOW(), opportunity.create) AS 'diff' FROM `opportunity` WHERE users_id = '" . $user_id . "' ORDER BY `opportunity`.`create` DESC")->result();
    }

    public function check_freelancer($slug)
    {
        return $this->db->get_where('users', array('slug' => $slug, 'type' => 'freelancer'))->result();
    }

    public function get_user_document($id)
    {
        return $this->db->query("SELECT * FROM `users_document` WHERE users_id =" . $id)->result();
    }
}
