<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProjectStatus extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_status_model');
    }

    // ============================================
    // LIST ALL PROJECT STATUSES
    // ============================================
    public function statuses()
    {
        print_r("jdhfjdksh");die;
        
    }

    // ============================================
    // ADD OR EDIT PROJECT STATUS
    // ============================================
    public function status()
    {
        print_r("jdhfjdksh");die;
        
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
