<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_types_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ================================================================
    // GET PROJECT TYPE (single or full list)
    // ================================================================
    public function get_project_type($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('projecttypeid', $id);
            return $this->db->get(db_prefix() . 'project_types')->row();
        }

        return $this->db->get(db_prefix() . 'project_types')->result_array();
    }

    // ================================================================
    // ADD PROJECT TYPE
    // ================================================================
    public function add_project_type($data)
    {
        $this->db->insert(db_prefix() . 'project_types', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Project Type Added [ID: ' . $insert_id . ', ' . $data['name'] . ']');
            return $insert_id;
        }

        return false;
    }

    // ================================================================
    // UPDATE PROJECT TYPE
    // ================================================================
    public function update_project_type($data, $id)
    {
        $this->db->where('projecttypeid', $id);
        $this->db->update(db_prefix() . 'project_types', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Type Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    // ================================================================
    // DELETE PROJECT TYPE
    // ================================================================
    public function delete_project_type($id)
    {
        $current = $this->get_project_type($id);

        // Cannot delete default type
        if ($current->isdefault == 1) {
            return ['default' => true];
        }

        // Check if referenced in tblprojects.project_type
        if (is_reference_in_table('project_type', db_prefix() . 'projects', $id)) {
            return ['referenced' => true];
        }

        // Delete
        $this->db->where('projecttypeid', $id);
        $this->db->delete(db_prefix() . 'project_types');

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Type Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }
}
