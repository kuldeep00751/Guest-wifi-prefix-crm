<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProjectType extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_types_model');
    }

    // ================================
    // Display All Project Types
    // ================================
    public function index()
    {
        if (!is_admin()) {
            access_denied('Project Types');
        }

        $data['types'] = $this->projects_types_model->get_project_types();
        $data['title'] = _l('project_types');

        $this->load->view('admin/projects/types/manage', $data);
    }

    // ================================
    // Add New or Edit Existing Project Type
    // ================================
    public function type()
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

    // ================================
    // Delete Project Type
    // ================================
    public function delete_project_type($id)
    {
        if (!is_admin()) {
            access_denied('Project Types');
        }

        if (!$id) {
            redirect(admin_url('projecttype'));
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

        redirect(admin_url('projecttype'));
    }
}
