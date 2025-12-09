<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProjectStatus extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    // ============================================
    // LIST ALL PROJECT STATUSES
    // ============================================
    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Project Statuses');
        }

        $data['statuses'] = $this->projects_status_model->get_project_status();
        $data['title']    = 'Project Statuses';

        $this->load->view('admin/projects/statuses/manage', $data);
    }

    // ============================================
    // ADD OR EDIT PROJECT STATUS
    // ============================================
    public function status()
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

    // ============================================
    // DELETE PROJECT STATUS
    // ============================================
    public function delete_project_status($id)
    {
        if (!is_admin()) {
            access_denied('Project Statuses');
        }

        if (!$id) {
            redirect(admin_url('projectstatus/statuses'));
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

        redirect(admin_url('projectstatus/statuses'));
    }
}
