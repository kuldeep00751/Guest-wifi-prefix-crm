<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProjectMaster extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_status_model');
        $this->load->model('projects_types_model');
        $this->load->model('operators_model');
    }

    // ============================================
    // Add New or Edit and delete Existing PROJECT STATUSES
    // ============================================
    public function project_statuses()
    {
        if (!is_admin()) {
            access_denied('Project Statuses');
        }

        $data['statuses'] = $this->projects_status_model->get_project_status();
        $data['title']    = 'Project Statuses';

        $this->load->view('admin/project-master/project_statuses/manage', $data);
    }


    public function project_status()
    {
        if (!is_admin()) {
            access_denied('Project Statuses');
        }

        if ($this->input->post()) {

            // Create new status
            if (!$this->input->post('id')) {
                $id = $this->projects_status_model->add_project_status($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('project_status')));
                }
            }

            // Update existing status
            else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);

                $success = $this->projects_status_model->update_project_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('project_status')));
                }
            }

            exit;
        }
    }

    public function delete_project_status($id)
    {
        if (!is_admin()) {
            access_denied('Project Statuses');
        }

        if (!$id) {
            redirect(admin_url('ProjectMaster/project_status'));
        }

        $response = $this->projects_status_model->delete_project_status($id);

        if (is_array($response) && isset($response['default'])) {
            set_alert('warning', _l('cant_delete_default', _l('project_status_lowercase')));
        } elseif (is_array($response) && isset($response['referenced'])) {
            set_alert('danger', _l('is_referenced', _l('project_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('project_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('project_status_lowercase')));
        }

        redirect(admin_url('ProjectMaster/project_statuses'));
    }

    // ================================
    // Add New or Edit and delete Existing Project Type
    // ================================

    public function project_types()
    {
        if (!is_admin()) {
            access_denied('Project Types');
        }

        $data['types'] = $this->projects_types_model->get_project_type();
        $data['title'] = _l('project_types');

        $this->load->view('admin/project-master/project_types/manage', $data);
    }

    
    public function project_type()
    {
        if (!is_admin()) {
            access_denied('Project Types');
        }

        if ($this->input->post()) {
            $post = $this->input->post();

            // CREATE
            if (!isset($post['id'])) {
                $id = $this->projects_types_model->add_project_type($post);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('project_type')));
                }
            } 
            // UPDATE
            else {
                $id = $post['id'];
                unset($post['id']);

                $success = $this->projects_types_model->update_project_type($post, $id);

                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('project_type')));
                }
            }

            exit;
        }
    }

    public function delete_project_type($id)
    {
        if (!is_admin()) {
            access_denied('Project Types');
        }

        if (!$id) {
            redirect(admin_url('ProjectMaster/project_type'));
        }

        $response = $this->projects_types_model->delete_project_type($id);

        if (is_array($response) && isset($response['default'])) {
            set_alert('warning', _l('cant_delete_default', _l('project_type_lowercase')));
        } elseif (is_array($response) && isset($response['referenced'])) {
            set_alert('danger', _l('is_referenced', _l('project_type_lowercase')));
        } elseif ($response === true) {
            set_alert('success', _l('deleted', _l('project_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('project_type_lowercase')));
        }

        redirect(admin_url('ProjectMaster/project_types'));
    }


}
