<?php

class Employer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Employer_model');
        $this->load->model('Users_model');
        if ($this->session->userdata('logged_in') !== true) {
            $this->session->set_flashdata('er_msg', 'You are not logged in please login to access the page');
            redirect(base_url('login'));
        }

        if ($this->session->userdata('u_type') == "freelancer") {
            redirect(base_url('freelancer'));
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
        $data['jobs'] = $this->Employer_model->jobs($this->session->userdata('u_id'));
        $data['tasks'] = $this->Employer_model->tasks($this->session->userdata('u_id'));
        $data['proposals'] = $this->Employer_model->proposals($this->session->userdata('u_id'));
        $data['quotations'] = $this->Employer_model->quotations($this->session->userdata('u_id'));
        $data['title'] = "Dashboard";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('users/dashboard', $data);
        $this->load->view('users/footer');
    }

    public function insert_jobs()
    {

        $this->form_validation->set_rules('job_title', 'Title', 'required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'required');
        $this->form_validation->set_rules('job_category', 'Job Category', 'required');
        $this->form_validation->set_rules('address', 'Location', 'required');
        $this->form_validation->set_rules('vacancy', 'Vacancy No.', 'required');
        $this->form_validation->set_rules('expire', 'Expire Date', 'required');
        $this->form_validation->set_rules('province[]', 'Province', 'required');
        $this->form_validation->set_rules('skills', 'Skill', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('degree', 'Degree', 'required');
        $this->form_validation->set_rules('langs', 'Languages', 'required');
        $this->form_validation->set_rules('num_vacancy', 'Number of Vacancies', 'required');
        $this->form_validation->set_rules('year', 'Years of Experience', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('response', 'Job Responsibility', 'required');

        if ($this->form_validation->run()) {
            $data = array(
                'job_title' => $this->input->post('job_title'),
                'slug' => url_title($this->input->post('job_title'), '-', true),
                'opportunity_type' => 'job',
                'type' => $this->input->post('job_type'),
                'address' => $this->input->post('address'),
                'degree' => $this->input->post('degree'),
                'num_vacancy' => $this->input->post('num_vacancy'),
                'province' => implode(',', $this->input->post('province')),
                'skills' => substr($this->input->post('skills'), 0, -1),
                'languages' => substr($this->input->post('langs'), 0, -1),
                'gender' => $this->input->post('gender'),
                'min_exp' => $this->input->post('year'),
                'category' => $this->input->post('job_category'),
                'category_slug' => url_title($this->input->post('job_category'), '-', true),
                'min_salary' => $this->input->post('min'),
                'max_salary' => $this->input->post('max'),
                'vacancy_no' => $this->input->post('vacancy'),
                'status' => "P",
                'expire' => $this->input->post('expire'),
                'note' => $this->input->post('note'),
                'description' => $this->input->post('description'),
                'responsible' => $this->input->post('response'),
                'submit_email' => $this->input->post('submit_email'),
                'users_id' => $this->session->userdata('u_id')
            );

            if ($this->Employer_model->insert_job($data)) {
                $this->session->set_flashdata('success_msg', 'Job Posted Successfully');
                redirect(base_url('employer/manage_jobs'));
            } else {
                $this->session->set_flashdata('er_msg', 'There Was An error please check your content');
                $this->goBack();
            }
        } else {

            $data['job_types'] = [
                'Full Time',
                'Freelance',
                'Part Time',
                'Internship',
                'Temporary'
            ];

            $data['provinces'] = array(
                'Anywhere',
                'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
                'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
                'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
            );

            $data['job_categories'] = [
                'Accounting and Finance', 'Clerical & Data Entry', 'Counseling', 'Court Administration',
                'Human Resources', 'Investigative', 'IT and Computers', 'Law Enforcement', 'Management', 'Miscellaneous', 'Public Relations'
            ];

            $data['title'] = "Post A Job";
            $this->load->view('header', $data);
            $this->load->view('users/sidebar', $data);
            $this->load->view('jobs/insert', $data);
            $this->load->view('users/footer');
        }
    }


    public function manage_jobs()
    {
        $data['jobs'] = $this->Employer_model->get_jobs($this->session->userdata('u_id'));
        $data['title'] = "Manage Jobs";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('jobs/listing', $data);
        $this->load->view('users/footer');
    }

    public function edit_job($job_id)
    {
        $data['job_types'] = [
            'Full Time',
            'Freelance',
            'Part Time',
            'Internship',
            'Temporary'
        ];

        $data['provinces'] = array(
            'Anywhere',
            'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
            'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
            'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
        );

        $data['job_categories'] = [
            'Accounting and Finance', 'Clerical & Data Entry', 'Counseling', 'Court Administration',
            'Human Resources', 'Investigative', 'IT and Computers', 'Law Enforcement', 'Management', 'Miscellaneous', 'Public Relations'
        ];

        $data['jobs'] = $this->Employer_model->get_u_jobs($this->session->userdata('u_id'), $job_id);

        $data['title'] = "Edit Job";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('jobs/update', $data);
        $this->load->view('users/footer');
    }

    public function update_job()
    {
        $this->form_validation->set_rules('job_title', 'Title', 'required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'required');
        $this->form_validation->set_rules('job_category', 'Job Category', 'required');
        $this->form_validation->set_rules('address', 'Location', 'required');
        $this->form_validation->set_rules('vacancy', 'Vacancy No.', 'required');
        $this->form_validation->set_rules('province[]', 'Province', 'required');
        $this->form_validation->set_rules('skills', 'Skill', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('degree', 'Degree', 'required');
        $this->form_validation->set_rules('langs', 'Languages', 'required');
        $this->form_validation->set_rules('num_vacancy', 'Number of Vacancies', 'required');
        $this->form_validation->set_rules('year', 'Years of Experience', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('response', 'Job Responsibility', 'required');
        if ($this->form_validation->run()) {
            $data = array(
                'job_title' => $this->input->post('job_title'),
                'slug' => url_title($this->input->post('job_title'), '-', true),
                'type' => $this->input->post('job_type'),
                'address' => $this->input->post('address'),
                'degree' => $this->input->post('degree'),
                'num_vacancy' => $this->input->post('num_vacancy'),
                'province' => implode(',', $this->input->post('province')),
                'skills' => substr($this->input->post('skills'), 0, -1),
                'languages' => substr($this->input->post('langs'), 0, -1),
                'gender' => $this->input->post('gender'),
                'min_exp' => $this->input->post('year'),
                'category' => $this->input->post('job_category'),
                'category_slug' => url_title($this->input->post('job_title'), '-', true),
                'min_salary' => $this->input->post('min'),
                'max_salary' => $this->input->post('max'),
                'vacancy_no' => $this->input->post('vacancy'),
                'submit_email' => $this->input->post('submit_email'),
                'description' => $this->input->post('description'),
                'note' => $this->input->post('note'),
                'responsible' => $this->input->post('response'),
                'modify' => date('Y/m/d H:i:s')
            );

            if ($this->Employer_model->edit_job($this->session->userdata('u_id'), $this->input->post('slug'), $data)) {
                $this->session->set_flashdata('success_msg', 'Job Edited Successfully');
                redirect(base_url('employer/manage_jobs'));
            } else {
                $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Update The Job');
                $this->goBack();
            }
        } else {
            $this->session->set_flashdata('er_msg', validation_errors());
            $this->goBack();
        }
    }

    public function delete_job($job_id = null)
    {
        if (empty($job_id)) {
            redirect(base_url('employer/manage_jobs'));
        }

        $jobs = $this->Employer_model->get_u_jobs($this->session->userdata('u_id'), $job_id);

        if (count($jobs) > 0) {
            if ($this->Employer_model->delete_job($job_id, $this->session->userdata('u_id')) === true) {
                $this->session->set_flashdata('success_msg', 'Job Deleted Successfully');
                redirect(base_url('employer/manage_jobs'));
            } else {
                $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Job');
                redirect(base_url('employer/manage_jobs'));
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Job');
            redirect(base_url('employer/manage_jobs'));
        }
    }

    public function count_candidate($id)
    {
        return count($this->Employer_model->candidate($id));
    }

    public function manage_candidate($date, $slug)
    {
        $data['title'] = "Manage Candidates";

        $data['job_info'] = $this->Employer_model->job_info($date, $slug);

        $data['candidates'] = $this->Employer_model->manage_candidate($data['job_info'][0]->id);

        if (count($this->Employer_model->check_user_candidate($this->session->userdata('u_id'), $date, $slug)) == 0) {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to check this Job');
            redirect(base_url('employer/manage_jobs'));
        }

        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('jobs/manage-candidate', $data);
        $this->load->view('users/footer');
    }

    public function other_applies($id)
    {
        return $this->Employer_model->other_apply($this->session->userdata('u_id'), $id);
    }

    public function remove_candidate($id = null)
    {
        if ($id == null) {
            redirect(base_url('employer/manage_jobs'));
        }

        $check = $this->Employer_model->select_apply($id);

        if (count($this->Employer_model->check_remove_candidate($this->session->userdata('u_id'), $check[0]->opportunity_id)) > 0) {

            if ($this->Employer_model->remove_candidate($id)) {
                $this->session->set_flashdata('success_msg', 'Candidate Removed Successfully');
                $this->goBack();
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You are not allowed');
            redirect(base_url('employer/manage_jobs'));
        }
    }

    public function accept_candidate($id = null)
    {
        if ($id == null) {
            redirect(base_url('employer/manage_jobs'));
        }

        $check = $this->Employer_model->select_apply($id);

        if (count($this->Employer_model->check_remove_candidate($this->session->userdata('u_id'), $check[0]->opportunity_id)) > 0) {

            if ($this->Employer_model->accept_candidate($id)) {
                $this->session->set_flashdata('success_msg', 'Candidate Accepted Successfully');
                $this->goBack();
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You are not allowed');
            redirect(base_url('employer/manage_jobs'));
        }
    }

    public function insert_task()
    {
        $this->form_validation->set_rules('job_title', 'Title', 'required');
        $this->form_validation->set_rules('province[]', 'Province', 'required');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('address', 'Location', 'required');
        $this->form_validation->set_rules('min_salary', 'Minimal Budget', 'required');
        $this->form_validation->set_rules('max_salary', 'Minimal Budget', 'required');
        $this->form_validation->set_rules('skills', 'Skills', 'required');
        $this->form_validation->set_rules('expire', 'Expire Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() == true) {
            $data = array(
                'slug' => url_title($this->input->post('job_title'), '-', true),
                'job_title' => $this->input->post('job_title'),
                'category_slug' => url_title($this->input->post('category'), '-', true),
                'category' => $this->input->post('category'),
                'address' => $this->input->post('address'),
                'province' => implode(',', $this->input->post('province')),
                'type' => $this->input->post('type'),
                'min_salary' => $this->input->post('min_salary'),
                'max_salary' => $this->input->post('max_salary'),
                'skills' => substr($this->input->post('skills'), 0, -1),
                'expire' => date($this->input->post('expire')),
                'description' => $this->input->post('description'),
                'responsible' => $this->input->post('response'),
                'opportunity_type' => 'task',
                'users_id' => $this->session->userdata('u_id'),
                'status' => 'P'
            );

            if ($this->Employer_model->insert_job($data)) {
                $this->session->set_flashdata('success_msg', 'Task Posted Successfully');
                redirect(base_url('employer/manage_tasks'));
            } else {
                $this->session->set_flashdata('er_msg', 'There Was An error please check your content');
                $this->goBack();
            }
        } else {
            $data['provinces'] = array(
                'Anywhere',
                'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
                'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
                'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
            );
            $data['task_categories'] = [
                'Admin Support', 'Customer Service', 'Data Analytic', 'Design and Creative',
                'Legal', 'Software Developing', 'IT and Networking', 'Writing', 'Translation', 'Sales and Marketing'
            ];

            $data['title'] = 'Post a Task';
            $this->load->view('header', $data);
            $this->load->view('users/sidebar', $data);
            $this->load->view('tasks/insert');
            $this->load->view('users/footer');
        }
    }


    public function manage_tasks()
    {
        $data['tasks'] = $this->Employer_model->get_tasks($this->session->userdata('u_id'));
        $data['title'] = "Manage Tasks";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('tasks/listing', $data);
        $this->load->view('users/footer');
    }

    public function edit_task($task_id)
    {


        $data['provinces'] = array(
            'Anywhere',
            'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
            'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
            'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
        );

        $data['categories'] = [
            'Admin Support', 'Customer Service', 'Data Analytic', 'Design and Creative',
            'Legal', 'Software Developing', 'IT and Networking', 'Writing', 'Translation', 'Sales and Marketing'
        ];

        $data['tasks'] = $this->Employer_model->get_u_tasks($this->session->userdata('u_id'), $task_id);

        $data['title'] = "Edit Task";
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('tasks/update', $data);
        $this->load->view('users/footer');
    }

    public function update_task()
    {
        $this->form_validation->set_rules('job_title', 'Title', 'required');
        $this->form_validation->set_rules('province[]', 'Province', 'required');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('address', 'Location', 'required');
        $this->form_validation->set_rules('min_salary', 'Minimal Budget', 'required');
        $this->form_validation->set_rules('max_salary', 'Minimal Budget', 'required');
        $this->form_validation->set_rules('skills', 'Skills', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run()) {
            $data = array(
                'job_title' => $this->input->post('job_title'),
                'slug' => url_title($this->input->post('job_title'), '-', true),
                'type' => $this->input->post('type'),
                'skills' => substr($this->input->post('skills'), 0, -1),
                'province' => implode(',', $this->input->post('province')),
                'category' => $this->input->post('category'),
                'category_slug' => url_title($this->input->post('category'), '-', true),
                'min_salary' => $this->input->post('min_salary'),
                'max_salary' => $this->input->post('max_salary'),
                'description' => $this->input->post('description'),
                'responsible' => $this->input->post('response'),
                'modify' => date('Y/m/d H:i:s')
            );

            if ($this->Employer_model->edit_job($this->session->userdata('u_id'), $this->input->post('slug'), $data)) {
                $this->session->set_flashdata('success_msg', 'Task Edited Successfully');
                redirect(base_url('employer/manage_tasks'));
            } else {
                $this->session->set_flashdata('er_msg', 'There was a problem during update form');
                $this->goBack();
            }
        } else {
            $this->session->set_flashdata('er_msg', validation_errors());
            $this->goBack();
        }
    }

    public function delete_task($task_id = null)
    {
        if (empty($task_id)) {
            redirect(base_url('employer/manage_tasks'));
        }

        $tasks = $this->Employer_model->get_u_tasks($this->session->userdata('u_id'), $task_id);

        if (count($tasks) > 0) {
            if ($this->Employer_model->delete_task($task_id, $this->session->userdata('u_id')) === true) {
                $this->session->set_flashdata('success_msg', 'Task Deleted Successfully');
                redirect(base_url('employer/manage_tasks'));
            } else {
                $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Task');
                redirect(base_url('employer/manage_tasks'));
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Task');
            redirect(base_url('employer/manage_tasks'));
        }
    }

    public function insert_rfp()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('expire', 'Expire Date', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        //this->form_validation->set_rules('rfp_file', 'Proposal', 'required');
        if ($this->form_validation->run() == true) {
            if (!empty($_FILES['rfp_file']['name'])) {
                $config = array(
                    'upload_path' => './assets/proposals/',
                    'allowed_types' => 'pdf|doc|zip|docx',
                    'encrypt_name' => true,
                    'remove_spaces' => true,
                    'detect_mime' => true
                );
                $this->load->library('upload', $config);

                // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
                $this->upload->initialize($config);

                if ($this->upload->do_upload('rfp_file')) {
                    $data = array(
                        'filename' => $this->upload->data('file_name'),
                        'desc' => $this->input->post('desc'),
                        'title' => $this->input->post('title'),
                        'slug' => url_title($this->input->post('title'), '-', true),
                        'users_id' => $this->session->userdata('u_id'),
                        'status' => 'P',
                        'expire_date' => date($this->input->post('expire'))
                    );

                    if ($this->Employer_model->ins_rfp($data)) {
                        $this->session->set_flashdata('success_msg', 'Proposal Inserted Successfully');
                        redirect(base_url('employer/manage_rfp'));
                    }
                } else {
                    $this->session->set_flashdata('er_msg', $this->upload->display_errors());
                    $this->goBack();
                }
            } else {
                $this->session->set_flashdata('er_msg', 'Proposal Filed is Required');
                $this->goBack();
            }
        } else {
            $data['title'] = "Post A Proposal";
            $this->load->view('header', $data);
            $this->load->view('users/sidebar', $data);
            $this->load->view('rfp/insert', $data);
            $this->load->view('users/footer');
        }
    }

    public function manage_rfp()
    {
        $data['title'] = 'Manage Proposals';
        $data['proposals'] = $this->Employer_model->manage_rfp($this->session->userdata('u_id'));
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('rfp/listing', $data);
        $this->load->view('users/footer');
    }

    public function edit_rfp($id = null)
    {
        if ($id == null) {
            redirect(base_url('employer/manage_rfp'));
            exit();
        }
        $data['title'] = 'Edit Proposal';
        $data['proposal'] = $this->Employer_model->get_single_rfp($id, $this->session->userdata('u_id'));
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('rfp/update', $data);
        $this->load->view('users/footer');
    }

    public function update_rfp()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        if ($this->form_validation->run() == true) {
            $data = array(
                'desc' => $this->input->post('desc'),
                'title' => $this->input->post('title'),
                'slug' => url_title($this->input->post('title'), '-', true)
            );
            if (!empty($_FILES['rfp_file']['name'])) {
                $config = array(
                    'upload_path' => './assets/proposals/',
                    'allowed_types' => 'doc|pdf|zip|docx',
                    'encrypt_name' => true,
                    'remove_spaces' => true,
                    'detect_mime' => true
                );
                $this->load->library('upload', $config);

                // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
                $this->upload->initialize($config);

                if ($this->upload->do_upload('rfp_file')) {
                    unlink('assets/proposals/' . $this->input->post('old_proposal'));
                    $data['filename'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('er_msg', $this->upload->display_errors());
                    $this->goBack();
                }
            }

            if ($this->Employer_model->edit_rfp($data, $this->session->userdata('u_id'), $this->input->post('slug'))) {
                $this->session->set_flashdata('success_msg', 'Proposal Updated Successfully');
                redirect(base_url('employer/manage_rfp'));
            }
        } else {
            $this->session->set_flashdata('er_msg', validation_errors());
            $this->goBack();
        }
    }

    public function delete_rfp($id = null)
    {
        if (empty($id)) {
            redirect(base_url('employer/manage_rfp'));
        }

        $rfp = $this->Employer_model->get_u_rfp($this->session->userdata('u_id'), $id);

        if (count($rfp) > 0) {
            if ($this->Employer_model->delete_rfp($id, $this->session->userdata('u_id')) === true) {
                $this->session->set_flashdata('success_msg', 'Proposal Deleted Successfully');
                unlink('assets/proposals/' . $rfp[0]->filename);
                redirect(base_url('employer/manage_rfp'));
            } else {
                $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Proposal');
                redirect(base_url('employer/manage_rfp'));
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Proposal');
            redirect(base_url('employer/manage_rfp'));
        }
    }

    public function insert_rfq()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('expire', 'Expire Date', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        //this->form_validation->set_rules('rfp_file', 'Proposal', 'required');
        if ($this->form_validation->run() == true) {
            if (!empty($_FILES['rfq_file']['name'])) {
                $config = array(
                    'upload_path' => './assets/quotations/',
                    'allowed_types' => 'pdf|doc|zip|docx',
                    'encrypt_name' => true,
                    'remove_spaces' => true,
                    'detect_mime' => true
                );
                $this->load->library('upload', $config);

                // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
                $this->upload->initialize($config);

                if ($this->upload->do_upload('rfq_file')) {
                    $data = array(
                        'filename' => $this->upload->data('file_name'),
                        'desc' => $this->input->post('desc'),
                        'title' => $this->input->post('title'),
                        'slug' => url_title($this->input->post('title'), '-', true),
                        'users_id' => $this->session->userdata('u_id'),
                        'status' => 'P',
                        'expire_date' => date($this->input->post('expire'))
                    );

                    if ($this->Employer_model->ins_rfq($data)) {
                        $this->session->set_flashdata('success_msg', 'Quotations Inserted Successfully');
                        redirect(base_url('employer/manage_rfq'));
                    }
                } else {
                    $this->session->set_flashdata('er_msg', $this->upload->display_errors());
                    $this->goBack();
                }
            } else {
                $this->session->set_flashdata('er_msg', 'Quotations Filed is Required');
                $this->goBack();
            }
        } else {
            $data['title'] = "Post A Quotation";
            $this->load->view('header', $data);
            $this->load->view('users/sidebar', $data);
            $this->load->view('rfq/insert', $data);
            $this->load->view('users/footer');
        }
    }

    public function manage_rfq()
    {
        $data['title'] = 'Manage Quotations';
        $data['Quotations'] = $this->Employer_model->manage_rfq($this->session->userdata('u_id'));
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('rfq/listing', $data);
        $this->load->view('users/footer');
    }

    public function edit_rfq($id = null)
    {
        if ($id == null) {
            redirect(base_url('employer/manage_rfq'));
            exit();
        }
        $data['title'] = 'Edit Quotation';
        $data['quotation'] = $this->Employer_model->get_single_rfq($id, $this->session->userdata('u_id'));
        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('rfq/update', $data);
        $this->load->view('users/footer');
    }

    public function update_rfq()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        if ($this->form_validation->run() == true) {
            $data = array(
                'desc' => $this->input->post('desc'),
                'title' => $this->input->post('title'),
                'slug' => url_title($this->input->post('title'), '-', true)
            );
            if (!empty($_FILES['rfq_file']['name'])) {
                $config = array(
                    'upload_path' => './assets/quotations/',
                    'allowed_types' => 'doc|pdf|zip|docx',
                    'encrypt_name' => true,
                    'remove_spaces' => true,
                    'detect_mime' => true
                );
                $this->load->library('upload', $config);

                // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
                $this->upload->initialize($config);

                if ($this->upload->do_upload('rfq_file')) {
                    unlink('assets/quotations/' . $this->input->post('old_quotation'));
                    $data['filename'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('er_msg', $this->upload->display_errors());
                    $this->goBack();
                }
            }

            if ($this->Employer_model->edit_rfq($data, $this->session->userdata('u_id'), $this->input->post('slug'))) {
                $this->session->set_flashdata('success_msg', 'Quotation Updated Successfully');
                redirect(base_url('employer/manage_rfq'));
            }
        } else {
            $this->session->set_flashdata('er_msg', validation_errors());
            $this->goBack();
        }
    }

    public function delete_rfq($id = null)
    {
        if (empty($id)) {
            redirect(base_url('employer/manage_rfq'));
        }

        $rfq = $this->Employer_model->get_u_rfq($this->session->userdata('u_id'), $id);

        if (count($rfq) > 0) {
            if ($this->Employer_model->delete_rfq($id, $this->session->userdata('u_id')) === true) {
                $this->session->set_flashdata('success_msg', 'Proposal Deleted Successfully');
                unlink('assets/quotations/' . $rfq[0]->filename);
                redirect(base_url('employer/manage_rfq'));
            } else {
                $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Quotation');
                redirect(base_url('employer/manage_rfq'));
            }
        } else {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to Delete The Quotation');
            redirect(base_url('employer/manage_rfq'));
        }
    }

    public function manage_bidders($date = null, $slug = null, $sort = null)
    {
        if (empty($date) || empty($slug)) {
            redirect(base_url('employer/manage_tasks'));
        }

        $data['title'] = "Manage Bidders";

        $data['task_info'] = $this->Employer_model->task_info($date, $slug);

        if ($sort == 'Lowest') {
            $data['bidders'] = $this->Employer_model->manage_bidder_lowest($data['task_info'][0]->id);
        } elseif ($sort == 'Highest') {
            $data['bidders'] = $this->Employer_model->manage_bidder_highest($data['task_info'][0]->id);
        } else {
            $data['bidders'] = $this->Employer_model->manage_bidder($data['task_info'][0]->id);
        }



        if (count($this->Employer_model->check_user_bidder($this->session->userdata('u_id'), $date, $slug)) == 0) {
            $this->session->set_flashdata('er_msg', 'You Are Not Allowed to check this Task');
            redirect(base_url('employer/manage_tasks'));
        }

        $this->load->view('header', $data);
        $this->load->view('users/sidebar', $data);
        $this->load->view('tasks/manage-bidders', $data);
        $this->load->view('users/footer');
    }

    public function goBack()
    {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}
