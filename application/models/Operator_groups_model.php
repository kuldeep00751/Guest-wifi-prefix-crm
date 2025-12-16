<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Operator_groups_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new operator group
     * @param array $data $_POST data
     */
    public function add($data)
    {
        $this->db->insert(db_prefix().'operators_groups', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New operator Group Created [ID:' . $insert_id . ', Name:' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
    * Get operator groups where operator belongs
    * @param  mixed $id operator id
    * @return array
    */
    public function get_operator_groups($id)
    {
        $this->db->where('operator_id', $id);

        return $this->db->get(db_prefix().'operator_groups')->result_array();
    }

    /**
     * Get all operator groups
     * @param  string $id
     * @return mixed
     */
    public function get_groups($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix().'operators_groups')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix().'operators_groups')->result_array();
    }

    /**
     * Edit operator group
     * @param  array $data $_POST data
     * @return boolean
     */
    public function edit($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix().'operators_groups', [
            'name' => $data['name'],
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('operator Group Updated [ID:' . $data['id'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete operator group
     * @param  mixed $id group id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'operators_groups');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('groupid', $id);
            $this->db->delete(db_prefix().'operator_groups');

            hooks()->do_action('operator_group_deleted', $id);

            log_activity('operator Group Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    /**
    * Update/sync operator groups where belongs
    * @param  mixed $id        operator id
    * @param  mixed $groups_in
    * @return boolean
    */
    public function sync_operator_groups($id, $groups_in)
    {
        if ($groups_in == false) {
            unset($groups_in);
        }
        $affectedRows    = 0;
        $operator_groups = $this->get_operator_groups($id);
        if (sizeof($operator_groups) > 0) {
            foreach ($operator_groups as $operator_group) {
                if (isset($groups_in)) {
                    if (!in_array($operator_group['groupid'], $groups_in)) {
                        $this->db->where('operator_id', $id);
                        $this->db->where('id', $operator_group['id']);
                        $this->db->delete(db_prefix().'operator_groups');
                        if ($this->db->affected_rows() > 0) {
                            $affectedRows++;
                        }
                    }
                } else {
                    $this->db->where('operator_id', $id);
                    $this->db->delete(db_prefix().'operator_groups');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
            if (isset($groups_in)) {
                foreach ($groups_in as $group) {
                    $this->db->where('operator_id', $id);
                    $this->db->where('groupid', $group);
                    $_exists = $this->db->get(db_prefix().'operator_groups')->row();
                    if (!$_exists) {
                        if (empty($group)) {
                            continue;
                        }
                        $this->db->insert(db_prefix().'operator_groups', [
                            'operator_id' => $id,
                            'groupid'     => $group,
                        ]);
                        if ($this->db->affected_rows() > 0) {
                            $affectedRows++;
                        }
                    }
                }
            }
        } else {
            if (isset($groups_in)) {
                foreach ($groups_in as $group) {
                    if (empty($group)) {
                        continue;
                    }
                    $this->db->insert(db_prefix().'operator_groups', [
                        'operator_id' => $id,
                        'groupid'     => $group,
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
        }

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }
}
