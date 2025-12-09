<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_status_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get project status
     * @param mixed $id
     * @return mixed
     */
    public function get_project_status($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('projectstatusid', $id);

            return $this->db->get(db_prefix() . 'project_status')->row();
        }

        $this->db->order_by('projectstatusid', 'asc');

        return $this->db->get(db_prefix() . 'project_status')->result_array();
    }

    /**
     * Add new project status
     * @param array $data
     * @return mixed
     */
    public function add_project_status($data)
    {
        $this->db->insert(db_prefix() . 'project_status', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Project Status Added [ID: ' . $insert_id . ', ' . $data['name'] . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update project status
     * @param array $data
     * @param mixed $id
     * @return boolean
     */
    public function update_project_status($data, $id)
    {
        $this->db->where('projectstatusid', $id);
        $this->db->update(db_prefix() . 'project_status', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Status Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete project status
     * @param mixed $id
     * @return mixed
     */
    public function delete_project_status($id)
    {
        $current = $this->get_project_status($id);

        // Default statuses cannot be deleted
        if ($current->isdefault == 1) {
            return ['default' => true];
        }

        // Check if referenced in projects table
        if (is_reference_in_table('status', db_prefix() . 'projects', $id)) {
            return ['referenced' => true];
        }

        // Delete
        $this->db->where('projectstatusid', $id);
        $this->db->delete(db_prefix() . 'project_status');

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Status Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }
}
