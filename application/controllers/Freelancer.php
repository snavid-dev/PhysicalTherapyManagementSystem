<?php

class Freelancer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Users_model');
        $this->load->model('Freelancer_model');
        if ($this->session->userdata('logged_in') !== true) {
            $this->session->set_flashdata('er_msg', 'You are not logged in please login to access the page');
            redirect(base_url('login'));
        }

        if ($this->session->userdata('u_type') == "employer") {
            redirect(base_url('employer'));
        }

        $x = $this->Users_model->get_single_user($this->session->userdata('u_id'));


        if ($x[0]->password !== $this->session->userdata('u_pwd')) {
            session_destroy();
        }

        if ($x[0]->status !== "A") {
            session_destroy();
        }
    }

    public function index()
    {
        $data['applied'] = $this->Freelancer_model->apply($this->session->userdata('u_id'));
        $data['bids'] = $this->Freelancer_model->tasks($this->session->userdata('u_id'));
        $data['title'] = "Dashboard";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('users/index');
        $this->load->view('users/footer');
    }

    public function apply()
    {
        $data['applies'] = $this->Freelancer_model->apply($this->session->userdata('u_id'));
        $data['title'] = "My Applies";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('users/apply', $data);
        $this->load->view('users/footer');
    }

    public function delete_apply($id = null)
    {
        if ($id == null) {
            redirect(base_url('freelancer/apply'));
        }

        $check_user = $this->Freelancer_model->check_user_apply($id, $this->session->userdata('u_id'));

        if (count($check_user) == 0) {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed To Remove');
            redirect(base_url('freelancer/apply'));
        }else{
            if ($this->Freelancer_model->remove_apply($id)) {
                $this->session->set_flashdata('success_msg', 'Apply Removed Successfully');
                redirect(base_url('freelancer/apply'));
            }
        }

    }

    public function bid()
    {
        $data['bids'] = $this->Freelancer_model->bids($this->session->userdata('u_id'));
        $data['title'] = "My Bids";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('users/bid', $data);
        $this->load->view('users/footer');
    }

    public function delete_bid($id = null)
    {
        if ($id == null) {
            redirect(base_url('freelancer/bid'));
        }

        $check_user = $this->Freelancer_model->check_user_apply($id, $this->session->userdata('u_id'));

        if (count($check_user) == 0) {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed To Remove');
            redirect(base_url('freelancer/bid'));
        }else{
            if ($this->Freelancer_model->remove_apply($id)) {
                $this->session->set_flashdata('success_msg', 'Bid Removed Successfully');
                redirect(base_url('freelancer/bid'));
            }
        }

    }

}
