<?php

use app\services\operatorProfileBadges;

defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Check if operator id is used in the system
 *
 * @param mixed $id operator id
 *
 * @return bool
 */
function is_operator_id_used($id)
{
    $total = 0;

    $checkCommonTables = [db_prefix() . 'subscriptions', db_prefix() . 'creditnotes', db_prefix() . 'projects', db_prefix() . 'invoices', db_prefix() . 'expenses', db_prefix() . 'estimates'];

    foreach ($checkCommonTables as $table) {
        $total += total_rows($table, [
            'operator' => $id,
        ]);
    }

    $total += total_rows(db_prefix() . 'contracts', [
        'operator' => $id,
    ]);

    $total += total_rows(db_prefix() . 'proposals', [
        'rel_id'   => $id,
        'rel_type' => 'operator',
    ]);

    $total += total_rows(db_prefix() . 'tickets', [
        'userid' => $id,
    ]);

    $total += total_rows(db_prefix() . 'tasks', [
        'rel_id'   => $id,
        'rel_type' => 'operator',
    ]);

    return hooks()->apply_filters('is_operator_id_used', $total > 0 ? true : false, $id);
}
/**
 * Check if operator has subscriptions
 *
 * @param mixed $id operator id
 *
 * @return bool
 */
function operator_has_subscriptions($id)
{
    return hooks()->apply_filters('operator_has_subscriptions', total_rows(db_prefix() . 'subscriptions', ['operatorid' => $id]) > 0);
}
/**
 * Get operator by ID or current queried operator
 *
 * @param mixed $id operator id
 *
 * @return mixed
 */
function get_operator($id = null)
{
    if (empty($id) && isset($GLOBALS['operator'])) {
        return $GLOBALS['operator'];
    }

    // operator global object not set
    if (empty($id)) {
        return null;
    }

    $operator = get_instance()->operators_model->get($id);

    return $operator;
}
/**
 * Get predefined tabs array, used in operator profile
 *
 * @return array
 */
function get_operator_profile_tabs()
{
    return get_instance()->app_tabs->get_operator_profile_tabs();
}

/**
 * Filter only visible tabs selected from the profile and add badge
 *
 * @param array $tabs available tabs
 * @param int   $id   operator
 *
 * @return array
 */
function filter_operator_visible_tabs($tabs, $id = '')
{
    $newTabs               = [];
    $operatorProfileBadges = null;

    $visible = get_option('visible_operator_profile_tabs');
    
    if ($visible != 'all') {
        $visible = unserialize($visible);
    }

    if ($id !== '') {
        $operatorProfileBadges = new operatorProfileBadges($id);
    }

    $appliedSettings = is_array($visible);

    foreach ($tabs as $key => $tab) {
        
        // Check visibility from settings too
        if ($key != 'profile' && $key != 'contacts' && $appliedSettings) {
            if (array_key_exists($key, $visible) && $visible[$key] == false) {
                continue;
            }
        }

        if (! is_null($operatorProfileBadges)) {
            $tab['badge'] = $operatorProfileBadges->getBadge($tab['slug']);
        }

        $newTabs[$key] = $tab;
    }
    
    return hooks()->apply_filters('operator_filtered_visible_tabs', $newTabs);
}
/**
 * @todo
 * Find a way to get the operator_id inside this function or refactor the hook
 *
 * @param string $group the tabs groups
 *
 * @return null
 */
function app_init_operator_profile_tabs()
{
    $operator_id = null;

    $remindersText = _l('operator_reminders_tab');

    if ($operator = get_operator()) {
        $operator_id = $operator->userid;

        $total_reminders = total_rows(
            db_prefix() . 'reminders',
            [
                'isnotified' => 0,
                'staff'      => get_staff_user_id(),
                'rel_type'   => 'operator',
                'rel_id'     => $operator_id,
            ]
        );

        if ($total_reminders > 0) {
            $remindersText .= ' <span class="badge">' . $total_reminders . '</span>';
        }
    }

    $CI = &get_instance();

    $CI->app_tabs->add_operator_profile_tab('profile', [
        'name'     => _l('operator_add_edit_profile'),
        'icon'     => 'fa fa-user-circle',
        'view'     => 'admin/operators/groups/profile',
        'position' => 5,
        'badge'    => [],
    ]);

    $CI->app_tabs->add_operator_profile_tab('contacts', [
        'name'     => ! is_empty_operator_company($operator_id) || empty($operator_id) ? _l('operator_contacts') : _l('contact'),
        'icon'     => 'fa-regular fa-user',
        'view'     => 'admin/operators/groups/contacts',
        'position' => 10,
        'badge'    => [],
    ]);

    // $CI->app_tabs->add_operator_profile_tab('notes', [
    //     'name'     => _l('contracts_notes_tab'),
    //     'icon'     => 'fa-regular fa-note-sticky',
    //     'view'     => 'admin/operators/groups/notes',
    //     'position' => 15,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('statement', [
    //     'name'     => _l('operator_statement'),
    //     'icon'     => 'fa fa-area-chart',
    //     'view'     => 'admin/operators/groups/statement',
    //     'visible'  => staff_can('view', 'invoices'),
    //     'position' => 20,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('invoices', [
    //     'name'     => _l('operator_invoices_tab'),
    //     'icon'     => 'fa fa-file-text',
    //     'view'     => 'admin/operators/groups/invoices',
    //     'visible'  => (staff_can('view', 'invoices') || staff_can('view_own', 'invoices') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())),
    //     'position' => 25,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('payments', [
    //     'name'     => _l('operator_payments_tab'),
    //     'icon'     => 'fa fa-line-chart',
    //     'view'     => 'admin/operators/groups/payments',
    //     'visible'  => (staff_can('view', 'payments') || staff_can('view_own', 'invoices') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())),
    //     'position' => 30,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('proposals', [
    //     'name'     => _l('proposals'),
    //     'icon'     => 'fa-regular fa-file-powerpoint',
    //     'view'     => 'admin/operators/groups/proposals',
    //     'visible'  => (staff_can('view', 'proposals') || staff_can('view_own', 'proposals') || (get_option('allow_staff_view_proposals_assigned') == 1 && staff_has_assigned_proposals())),
    //     'position' => 35,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('credit_notes', [
    //     'name'     => _l('credit_notes'),
    //     'icon'     => 'fa-regular fa-file-lines',
    //     'view'     => 'admin/operators/groups/credit_notes',
    //     'visible'  => (staff_can('view', 'credit_notes') || staff_can('view_own', 'credit_notes')),
    //     'position' => 40,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('estimates', [
    //     'name'     => _l('estimates'),
    //     'icon'     => 'fa-regular fa-file',
    //     'view'     => 'admin/operators/groups/estimates',
    //     'visible'  => (staff_can('view', 'estimates') || staff_can('view_own', 'estimates') || (get_option('allow_staff_view_estimates_assigned') == 1 && staff_has_assigned_estimates())),
    //     'position' => 45,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('subscriptions', [
    //     'name'     => _l('subscriptions'),
    //     'icon'     => 'fa fa-repeat',
    //     'view'     => 'admin/operators/groups/subscriptions',
    //     'visible'  => (staff_can('view', 'subscriptions') || staff_can('view_own', 'subscriptions')),
    //     'position' => 50,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('expenses', [
    //     'name'     => _l('expenses'),
    //     'icon'     => 'fa-regular fa-file-lines',
    //     'view'     => 'admin/operators/groups/expenses',
    //     'visible'  => (staff_can('view', 'expenses') || staff_can('view_own', 'expenses')),
    //     'position' => 55,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('contracts', [
    //     'name'     => _l('contracts'),
    //     'icon'     => 'fa-regular fa-note-sticky',
    //     'view'     => 'admin/operators/groups/contracts',
    //     'visible'  => (staff_can('view', 'contracts') || staff_can('view_own', 'contracts')),
    //     'position' => 60,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('projects', [
    //     'name'     => _l('projects'),
    //     'icon'     => 'fa-solid fa-chart-gantt',
    //     'view'     => 'admin/operators/groups/projects',
    //     'position' => 65,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('tasks', [
    //     'name'     => _l('tasks'),
    //     'icon'     => 'fa-regular fa-circle-check',
    //     'view'     => 'admin/operators/groups/tasks',
    //     'position' => 70,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('tickets', [
    //     'name'     => _l('tickets'),
    //     'icon'     => 'fa-regular fa-life-ring',
    //     'view'     => 'admin/operators/groups/tickets',
    //     'visible'  => ((get_option('access_tickets_to_none_staff_members') == 1 && ! is_staff_member()) || is_staff_member()),
    //     'position' => 75,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('attachments', [
    //     'name'     => _l('operator_attachments'),
    //     'icon'     => 'fa fa-paperclip',
    //     'view'     => 'admin/operators/groups/attachments',
    //     'position' => 80,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('vault', [
    //     'name'     => _l('vault'),
    //     'icon'     => 'fa fa-lock',
    //     'view'     => 'admin/operators/groups/vault',
    //     'position' => 85,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('reminders', [
    //     'name'     => $remindersText,
    //     'icon'     => 'fa-regular fa-bell',
    //     'view'     => 'admin/operators/groups/reminders',
    //     'position' => 90,
    //     'badge'    => [],
    // ]);

    // $CI->app_tabs->add_operator_profile_tab('map', [
    //     'name'     => _l('operator_map'),
    //     'icon'     => 'fa-solid fa-location-dot',
    //     'view'     => 'admin/operators/groups/map',
    //     'position' => 95,
    //     'badge'    => [],
    // ]);
}

/**
 * Get operator id by lead id
 *
 * @since  Version 1.0.1
 *
 * @param mixed $id lead id
 *
 * @return mixed operator id
 */
function get_operator_id_by_lead_id($id)
{
    $CI = &get_instance();
    $CI->db->select('userid')->from(db_prefix() . 'operators')->where('leadid', $id);

    return $CI->db->get()->row()->userid;
}


/**
 * Check if operator have invoices with multiple currencies
 *
 * @param mixed      $operatorid
 * @param mixed|null $table
 *
 * @return booelan
 */
function is_operator_using_multiple_currencies($operatorid = '', $table = null)
{
    if (! $table) {
        $table = db_prefix() . 'invoices';
    }

    $CI = &get_instance();

    $operatorid = $operatorid == '' ? get_operator_user_id() : $operatorid;
    $CI->load->model('currencies_model');
    $currencies            = $CI->currencies_model->get();
    $total_currencies_used = 0;

    foreach ($currencies as $currency) {
        $CI->db->where('currency', $currency['id']);
        $CI->db->where('operatorid', $operatorid);
        $total = $CI->db->count_all_results($table);
        if ($total > 0) {
            $total_currencies_used++;
        }
    }

    $retVal = true;
    if ($total_currencies_used > 1) {
        $retVal = true;
    } elseif ($total_currencies_used == 0 || $total_currencies_used == 1) {
        $retVal = false;
    }

    return hooks()->apply_filters('is_operator_using_multiple_currencies', $retVal, [
        'operator_id' => $operatorid,
        'table'     => $table,
    ]);
}

/**
 * Function used to check if is really empty operator company
 * Can happen user to have selected that the company field is not required and the primary contact name is auto added in the company field
 *
 * @param mixed $id
 *
 * @return bool
 */
function is_empty_operator_company($id)
{
    $CI = &get_instance();
    $CI->db->select('company');
    $CI->db->from(db_prefix() . 'operators');
    $CI->db->where('userid', $id);
    $row = $CI->db->get()->row();
    if ($row) {
        return (bool) ($row->company == '');
    }

    return true;
}

/**
 * Get ids to check what files with contacts are shared
 *
 * @param array $where
 *
 * @return array
 */
function get_operator_profile_file_sharing($where = [])
{
    $CI = &get_instance();
    $CI->db->where($where);

    return $CI->db->get(db_prefix() . 'shared_operator_files')->result_array();
}


/**
 * Used in:
 * Search contact tickets
 * Project dropdown quick switch
 * Calendar tooltips
 *
 * @param  [type] $userid [description]
 * @param mixed $prevent_empty_company
 *
 * @return [type]         [description]
 */
function get_operator_company_name($userid, $prevent_empty_company = false)
{
    $_userid = get_operator_user_id();
    if ($userid !== '') {
        $_userid = $userid;
    }
    $CI = &get_instance();

    $select = ($prevent_empty_company == false ? get_sql_select_operator_company() : 'company');

    $operator = $CI->db->select($select)
        ->where('userid', $_userid)
        ->from(db_prefix() . 'operators')
        ->get()
        ->row();
    if ($operator) {
        return $operator->company;
    }

    return '';
}

/**
 * Get operator default language
 *
 * @param mixed $operatorid
 *
 * @return mixed
 */
function get_operator_default_language($operatorid = '')
{
    if (! is_numeric($operatorid)) {
        $operatorid = get_operator_user_id();
    }

    $CI = &get_instance();
    $CI->db->select('default_language');
    $CI->db->from(db_prefix() . 'operators');
    $CI->db->where('userid', $operatorid);
    $operator = $CI->db->get()->row();
    if ($operator) {
        return $operator->default_language;
    }

    return '';
}

/**
 * Function is operator admin
 *
 * @param mixed    $id       operator id
 * @param staff_id $staff_id staff id to check
 *
 * @return bool
 */
function is_operator_admin($id, $staff_id = '')
{
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $CI       = &get_instance();
    $cache    = $CI->app_object_cache->get($id . '-is-operator-admin-' . $staff_id);

    if ($cache) {
        return $cache['retval'];
    }

    $total = total_rows(db_prefix() . 'operator_admins', [
        'operator_id' => $id,
        'staff_id'    => $staff_id,
    ]);

    $retval = $total > 0 ? true : false;
    $CI->app_object_cache->add($id . '-is-operator-admin-' . $staff_id, ['retval' => $retval]);

    return $retval;
}
/**
 * Check if staff member have assigned operators
 *
 * @param mixed $staff_id staff id
 *
 * @return bool
 */
function have_assigned_operators($staff_id = '')
{
    $CI       = &get_instance();
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $cache    = $CI->app_object_cache->get('staff-total-assigned-operators-' . $staff_id);

    if (is_numeric($cache)) {
        $result = $cache;
    } else {
        $result = total_rows(db_prefix() . 'operator_admins', [
            'staff_id' => $staff_id,
        ]);
        $CI->app_object_cache->add('staff-total-assigned-operators-' . $staff_id, $result);
    }

    return $result > 0 ? true : false;
}


/**
 * Check if operator have transactions recorded
 *
 * @param mixed $id operatorid
 *
 * @return bool
 */
function operator_have_transactions($id)
{
    $total = 0;

    foreach ([db_prefix() . 'invoices', db_prefix() . 'creditnotes', db_prefix() . 'estimates'] as $table) {
        $total += total_rows($table, [
            'clientid' => $id,
        ]);
    }

    $total += total_rows(db_prefix() . 'expenses', [
        'clientid' => $id,
        'billable' => 1,
    ]);

    $total += total_rows(db_prefix() . 'proposals', [
        'rel_id'   => $id,
        'rel_type' => 'operator',
    ]);

    return hooks()->apply_filters('operator_have_transactions', $total > 0, $id);
}

/**
 * Predefined contact permission
 *
 * @return array
 */
function get_operator_contact_permissions()
{
    $permissions = [
        [
            'id'         => 1,
            'name'       => _l('operator_permission_invoice'),
            'short_name' => 'invoices',
        ],
        [
            'id'         => 2,
            'name'       => _l('operator_permission_estimate'),
            'short_name' => 'estimates',
        ],
        [
            'id'         => 3,
            'name'       => _l('operator_permission_contract'),
            'short_name' => 'contracts',
        ],
        [
            'id'         => 4,
            'name'       => _l('operator_permission_proposal'),
            'short_name' => 'proposals',
        ],
        [
            'id'         => 5,
            'name'       => _l('operator_permission_support'),
            'short_name' => 'support',
        ],
        [
            'id'         => 6,
            'name'       => _l('operator_permission_projects'),
            'short_name' => 'projects',
        ],
    ];

    return hooks()->apply_filters('get_operator_contact_permission', $permissions);
}

function get_operator_contact_permission($name)
{
    $permissions = get_contact_permissions();

    foreach ($permissions as $permission) {
        if ($permission['short_name'] == $name) {
            return $permission;
        }
    }

    return false;
}

/**
 * Additional checking for operators area, when contact edit his profile
 * This function will check if the checkboxes for email notifications should be shown
 *
 * @return bool
 */
function can_operator_contact_view_email_notifications_options()
{
    return has_contact_permission('invoices')
        || has_contact_permission('estimates')
        || has_contact_permission('projects')
        || has_contact_permission('contracts');
}

/**
 * With this function staff can login as operator in the operators area
 *
 * @param mixed $id operator id
 */
function login_as_operator($id)
{
    $CI = &get_instance();

    $CI->db->select(db_prefix() . 'contacts.id, active')
        ->where('userid', $id)
        ->where('is_primary', 1);

    $primary = $CI->db->get(db_prefix() . 'contacts')->row();

    if (! $primary) {
        set_alert('danger', _l('no_primary_contact'));
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    } elseif ($primary->active == '0') {
        set_alert('danger', 'operator primary contact is not active, please set the primary contact as active in order to login as operator');
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }

    $CI->load->model('announcements_model');
    $CI->announcements_model->set_announcements_as_read_except_last_one($primary->id);

    $user_data = [
        'operator_user_id'      => $id,
        'contact_user_id'     => $primary->id,
        'operator_logged_in'    => true,
        'logged_in_as_operator' => true,
    ];

    $CI->session->set_userdata($user_data);
}

function send_operator_registered_email_to_administrators($operator_id)
{
    $CI = &get_instance();
    $CI->load->model('staff_model');
    $admins = $CI->staff_model->get('', ['active' => 1, 'admin' => 1]);

    foreach ($admins as $admin) {
        send_mail_template('operator_new_registration_to_admins', $admin['email'], $operator_id, $admin['staffid']);
    }
}



/**
 *  Get operator attachment
 *
 * @param mixed $id operator id
 *
 * @return array
 */
function get_all_operator_attachments($id)
{
    $CI = &get_instance();

    $attachments = [];

    $attachment_types = [
        'invoice' => [
            'url'                => site_url() . 'download/file/sales_attachment/',
            'upload_path'        => get_upload_path_by_type('invoice'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'estimate' => [
            'url'                => site_url() . 'download/file/sales_attachment/',
            'upload_path'        => get_upload_path_by_type('estimate'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'credit_note' => [
            'url'                => site_url() . 'download/file/sales_attachment/',
            'upload_path'        => get_upload_path_by_type('credit_note'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'proposal' => [
            'url'                => site_url() . 'download/file/sales_attachment/',
            'upload_path'        => get_upload_path_by_type('proposal'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'contract' => [
            'url'                => site_url() . 'download/file/contract/',
            'upload_path'        => get_upload_path_by_type('contract'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'lead' => [
            'url'                => site_url() . 'download/file/lead_attachment/',
            'upload_path'        => get_upload_path_by_type('lead'),
            'download_indicator' => 'id',
            'key_indicator'      => 'rel_id',
        ],
        'task' => [
            'url'                => site_url() . 'download/file/taskattachment/',
            'upload_path'        => get_upload_path_by_type('task'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'operator' => [
            'url'                => site_url() . 'download/file/operator/',
            'upload_path'        => get_upload_path_by_type('operator'),
            'download_indicator' => 'attachment_key',
            'key_indicator'      => 'rel_id',
        ],
        'ticket' => [
            'url'                => site_url() . 'download/file/ticket/',
            'upload_path'        => get_upload_path_by_type('ticket'),
            'download_indicator' => 'id',
            'key_indicator'      => 'ticketid',
        ],
        'expense' => [
            'url'                => site_url() . 'download/file/expense/',
            'upload_path'        => get_upload_path_by_type('expense'),
            'download_indicator' => 'rel_id',
            'key_indicator'      => 'rel_id',
        ],
    ];

    foreach ($attachment_types as $type => $settings) {
        $attachments[$type] = []; // create empty array for each type
    }

    $can_view_expenses     = staff_can('view', 'expenses');
    $can_view_own_expenses = staff_can('view_own', 'expenses');
    if ($can_view_expenses || $can_view_own_expenses) {
        $CI->db->select('operatorid,id')->where('operatorid', $id);
        if (! $can_view_expenses) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }
        $CI->db->from(db_prefix() . 'expenses');
        $expenses = $CI->db->get()->result_array();
        $ids      = array_column($expenses, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'expense');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['expense']['url'] . $_att[$attachment_types['expense']['download_indicator']];
                $_att['upload_path']  = $attachment_types['expense']['upload_path'] . $_att[$attachment_types['expense']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['expense'], $_att);
            }
        }
    }

    if (staff_can('view', 'invoices') || staff_can('view_own', 'invoices') || get_option('allow_staff_view_invoices_assigned') == 1) {
        $noPermissionQuery = get_invoices_where_sql_for_staff(get_staff_user_id());
        $CI->db->select('operatorid,id')->where('operatorid', $id);

        if (! staff_can('view', 'invoices')) {
            $CI->db->where($noPermissionQuery);
        }

        $CI->db->from(db_prefix() . 'invoices');
        $invoices = $CI->db->get()->result_array();
        $ids      = array_column($invoices, 'id');

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'invoice');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['invoice']['url'] . $_att[$attachment_types['invoice']['download_indicator']];
                $_att['upload_path']  = $attachment_types['invoice']['upload_path'] . $_att[$attachment_types['invoice']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['invoice'], $_att);
            }
        }
    }
    if (staff_can('view', 'credit_notes') || staff_can('view_own', 'credit_notes')) {
        $CI->db->select('operatorid,id')->where('operatorid', $id);

        if (! staff_can('view', 'credit_notes')) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }

        $CI->db->from(db_prefix() . 'creditnotes');
        $credit_notes = $CI->db->get()->result_array();
        $ids          = array_column($credit_notes, 'id');

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'credit_note');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['credit_note']['url'] . $_att[$attachment_types['credit_note']['download_indicator']];
                $_att['upload_path']  = $attachment_types['credit_note']['upload_path'] . $_att[$attachment_types['credit_note']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['credit_note'], $_att);
            }
        }
    }

    if (staff_can('view', 'estimates') || staff_can('view_own', 'estimates') || get_option('allow_staff_view_proposals_assigned') == 1) {
        $noPermissionQuery = get_estimates_where_sql_for_staff(get_staff_user_id());
        $CI->db->select('operatorid,id')->where('operatorid', $id);

        if (! staff_can('view', 'estimates')) {
            $CI->db->where($noPermissionQuery);
        }

        $CI->db->from(db_prefix() . 'estimates');
        $estimates = $CI->db->get()->result_array();
        $ids       = array_column($estimates, 'id');

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'estimate');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['estimate']['url'] . $_att[$attachment_types['estimate']['download_indicator']];
                $_att['upload_path']  = $attachment_types['estimate']['upload_path'] . $_att[$attachment_types['estimate']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['estimate'], $_att);
            }
        }
    }

    if (staff_can('view', 'proposals') || staff_can('view_own', 'proposals') || get_option('allow_staff_view_proposals_assigned') == 1) {
        $noPermissionQuery = get_proposals_sql_where_staff(get_staff_user_id());
        $CI->db->select('rel_id,id')->where('rel_id', $id)->where('rel_type', 'operator');

        if (! staff_can('view', 'proposals')) {
            $CI->db->where($noPermissionQuery);
        }

        $CI->db->from(db_prefix() . 'proposals');
        $proposals = $CI->db->get()->result_array();
        $ids       = array_column($proposals, 'id');

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'proposal');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['proposal']['url'] . $_att[$attachment_types['proposal']['download_indicator']];
                $_att['upload_path']  = $attachment_types['proposal']['upload_path'] . $_att[$attachment_types['proposal']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['proposal'], $_att);
            }
        }
    }

    $can_view_contracts = staff_can('view', 'contracts');
    if ($can_view_contracts || staff_can('view_own', 'contracts')) {
        $CI->db->select('operator,id')->where('operator', $id);

        if (! $can_view_contracts) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }

        $CI->db->from(db_prefix() . 'contracts');
        $contracts = $CI->db->get()->result_array();
        $ids       = array_column($contracts, 'id');

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'contract');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['contract']['url'] . $_att[$attachment_types['contract']['download_indicator']];
                $_att['upload_path']  = $attachment_types['contract']['upload_path'] . $_att[$attachment_types['contract']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['contract'], $_att);
            }
        }
    }

    $CI->db->select('leadid')->where('userid', $id);
    $operator = $CI->db->get(db_prefix() . 'operators')->row();

    if (! empty($operator->leadid)) {
        $CI->db->where('rel_id', $operator->leadid)->where('rel_type', 'lead');
        $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

        foreach ($_attachments as $_att) {
            $_att['download_url'] = $attachment_types['lead']['url'] . $_att[$attachment_types['lead']['download_indicator']];
            $_att['upload_path']  = $attachment_types['lead']['upload_path'] . $_att[$attachment_types['lead']['key_indicator']] . '/' . $_att['file_name'];
            array_push($attachments['lead'], $_att);
        }
    }

    $CI->db->select('ticketid,userid')->where('userid', $id);
    $tickets = $CI->db->get(db_prefix() . 'tickets')->result_array();

    $ids = array_column($tickets, 'ticketid');

    if (! empty($ids)) {
        $CI->db->where_in('ticketid', $ids);
        $_attachments = $CI->db->get(db_prefix() . 'ticket_attachments')->result_array();

        foreach ($_attachments as $_att) {
            $_att['download_url'] = $attachment_types['ticket']['url'] . $_att[$attachment_types['ticket']['download_indicator']];
            $_att['upload_path']  = $attachment_types['ticket']['upload_path'] . $_att[$attachment_types['ticket']['key_indicator']] . '/' . $_att['file_name'];
            array_push($attachments['ticket'], $_att);
        }
    }

    if (staff_can('view', 'tasks')) {
        $noPermissionQuery = get_tasks_where_string(false);
        $CI->db->select('rel_id, id')->where('rel_id', $id)->where('rel_type', 'operator');

        if (! staff_can('view', 'tasks')) {
            $CI->db->where($noPermissionQuery);
        }

        $tasks = $CI->db->get(db_prefix() . 'tasks')->result_array();
        $ids   = array_column($tasks, 'id'); // Corrected to fetch 'id' (was 'ticketid')

        if (! empty($ids)) {
            $CI->db->where_in('rel_id', $ids)->where('rel_type', 'task');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                $_att['download_url'] = $attachment_types['task']['url'] . $_att[$attachment_types['task']['download_indicator']];
                $_att['upload_path']  = $attachment_types['task']['upload_path'] . $_att[$attachment_types['task']['key_indicator']] . '/' . $_att['file_name'];
                array_push($attachments['task'], $_att);
            }
        }
    }

    $CI->db->where('rel_id', $id)->where('rel_type', 'operator');
    $operator_main_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

    foreach ($operator_main_attachments as &$attachment) {
        $attachment['total_shares'] = total_rows(db_prefix() . 'shared_operator_files', ['file_id' => $attachment['id']]);
        $attachment['shared_with']  = $attachment['total_shares'] > 0 ?
            get_operator_profile_file_sharing(['file_id' => $attachment['id']]) :
            [];

        $attachment['download_url'] = $attachment_types['operator']['url'] . $attachment[$attachment_types['operator']['download_indicator']];
        $attachment['upload_path']  = $attachment_types['operator']['upload_path'] . $attachment[$attachment_types['operator']['key_indicator']] . '/' . $attachment['file_name'];
    }

    $attachments['operator'] = $operator_main_attachments;

    return hooks()->apply_filters('all_operator_attachments', $attachments, $id);
}


/**
 * Default SQL select for selecting the company
 *
 * @param string $as
 *
 * @return string
 */
function get_sql_select_operator_company($as = 'company')
{
    return 'CASE ' . db_prefix() . 'operators.company WHEN \' \' THEN (SELECT CONCAT(firstname, \' \', lastname) FROM ' . db_prefix() . 'contacts WHERE userid = ' . db_prefix() . 'operators.userid and is_primary = 1) ELSE ' . db_prefix() . 'operators.company END as ' . $as;
}


/**
 * @since  2.7.0
 * check if logged in operator is an individual
 *
 * @return bool
 */
function is_individual_operator()
{
    return is_empty_operator_company(get_operator_user_id())
        && total_rows(db_prefix() . 'contacts', ['userid' => get_operator_user_id()]) == 1;
}


/**
 * @since 3.1.2
 *
 * Get the required fields for registration.
 *
 * @return array
 */
function get_operator_required_fields_for_registration()
{
    $option = get_option('required_register_fields');

    $required = $option ? json_decode($option) : [];

    return [
        'contact' => [
            'contact_firstname'           => ['label' => _l('operators_firstname'), 'is_required' => true, 'disabled' => true],
            'contact_lastname'            => ['label' => _l('operators_lastname'), 'is_required' => true, 'disabled' => true],
            'contact_email'               => ['label' => _l('operators_email'), 'is_required' => true, 'disabled' => true],
            'contact_contact_phonenumber' => ['label' => _l('operators_phone'), 'is_required' => in_array('contact_contact_phonenumber', $required), 'disabled' => false],
            'contact_website'             => ['label' => _l('operator_website'), 'is_required' => in_array('contact_website', $required), 'disabled' => false],
            'contact_title'               => ['label' => _l('contact_position'), 'is_required' => in_array('contact_title', $required), 'disabled' => false],
        ],
        'company' => [
            'company_company'     => ['label' => _l('operators_company'), 'is_required' => (bool) get_option('company_is_required'), 'disabled' => true],
            'company_vat'         => ['label' => _l('operators_vat'), 'is_required' => in_array('company_vat', $required), 'disabled' => false],
            'company_phonenumber' => ['label' => _l('operators_phone'), 'is_required' => in_array('company_phonenumber', $required), 'disabled' => false],
            'company_country'     => ['label' => _l('operators_country'), 'is_required' => in_array('company_country', $required), 'disabled' => false],
            'company_city'        => ['label' => _l('operators_city'), 'is_required' => in_array('company_city', $required), 'disabled' => false],
            'company_address'     => ['label' => _l('operators_address'), 'is_required' => in_array('company_address', $required), 'disabled' => false],
            'company_zip'         => ['label' => _l('operators_zip'), 'is_required' => in_array('company_zip', $required), 'disabled' => false],
            'company_state'       => ['label' => _l('operators_state'), 'is_required' => in_array('company_state', $required), 'disabled' => false],
        ],
    ];
}
