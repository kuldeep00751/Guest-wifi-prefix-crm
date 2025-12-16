<?php

defined('BASEPATH') or exit('No direct script access allowed');

class operator_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                [
                    'name'      => 'Contact Firstname',
                    'key'       => '{contact_firstname}',
                    'available' => [
                        'operator',
                        'ticket',
                        'invoice',
                        'estimate',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                    'templates' => [
                        'gdpr-removal-request',
                        'contract-expiration',
                        'send-contract',
                        'contract-comment-to-operator',
                        'task-added-attachment-to-contacts',
                        'task-commented-to-contacts',
                        'task-status-change-to-contacts',
                        'invoices-batch-payments',
                    ],
                ],
                [
                    'name'      => 'Contact Lastname',
                    'key'       => '{contact_lastname}',
                    'available' => [
                        'operator',
                        'ticket',
                        'invoice',
                        'estimate',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                          'templates' => [
                              'gdpr-removal-request',
                              'contract-expiration',
                              'send-contract',
                              'contract-comment-to-operator',
                              'task-added-attachment-to-contacts',
                              'task-commented-to-contacts',
                              'task-status-change-to-contacts',
                              'invoices-batch-payments',
                          ],
                ],
                [
                    'name'      => 'Contact Phone Number',
                    'key'       => '{contact_phonenumber}',
                    'available' => [
                        'operator',
                        'ticket',
                        'invoice',
                        'estimate',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                        'templates' => [
                        'gdpr-removal-request',
                        'contract-expiration',
                         'send-contract',
                          'contract-comment-to-operator',

                    ],
                ],
                [
                    'name'      => 'Contact Title',
                    'key'       => '{contact_title}',
                    'available' => [
                        'operator',
                        'ticket',
                        'invoice',
                        'estimate',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                        'templates' => [
                        'contract-expiration',
                        'send-contract',
                        'contract-comment-to-operator',
                        'invoices-batch-payments',
                    ],
                ],
                [
                    'name'      => 'Contact Email',
                    'key'       => '{contact_email}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                       'templates' => [
                        'gdpr-removal-request',
                        'contract-expiration',
                         'send-contract',
                          'contract-comment-to-operator',
                           'invoices-batch-payments',
                    ],
                ],
                   [
                    'name'      => 'Set New Password URL',
                    'key'       => '{set_password_url}',
                    'available' => [
                    ],
                    'templates' => [
                        'contact-set-password',
                    ],
                ],
                [
                    'name'      => 'Email Verification URL',
                    'key'       => '{email_verification_url}',
                    'available' => [
                    ],
                    'templates' => [
                        'contact-verification-email',
                    ],
                ],
                [
                    'name'      => 'Reset Password URL',
                    'key'       => '{reset_password_url}',
                    'available' => [
                    ],
                    'templates' => [
                        'contact-forgot-password',
                    ],
                ],
                [
                    'name'      => is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1' ? 'Contact Public Consent URL' : '',
                    'key'       => is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1' ? '{contact_public_consent_url}' : '',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                          'templates' => [
                        'gdpr-removal-request',
                        'contract-expiration',
                        'send-contract',
                         'contract-comment-to-operator',

                    ],
                ],
                [
                    'name'      => 'operator Company',
                    'key'       => '{operator_company}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                          'templates' => [
                        'gdpr-removal-request',
                        'invoices-batch-payments',
                    ],
                ],
                [
                    'name'      => 'operator Phone Number',
                    'key'       => '{operator_phonenumber}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                          'templates' => [
                        'gdpr-removal-request',
                        'invoices-batch-payments',
                    ],
                ],
                [
                    'name'      => 'operator Country',
                    'key'       => '{operator_country}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                          'templates' => [
                        'gdpr-removal-request',
                          'invoices-batch-payments',
                    ],
                ],
                [
                    'name'      => 'operator City',
                    'key'       => '{operator_city}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                ],
                [
                    'name'      => 'operator Zip',
                    'key'       => '{operator_zip}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                    'templates' => [
                        'invoices-batch-payments',
                    ]
                ],
                [
                    'name'      => 'operator State',
                    'key'       => '{operator_state}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                    'templates' => [
                        'invoices-batch-payments',
                    ]
                ],
                [
                    'name'      => 'operator Address',
                    'key'       => '{operator_address}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                    'template' => [
                        'invoices-batch-payments',
                    ]
                ],
                [
                    'name'      => 'operator Vat Number',
                    'key'       => '{operator_vat_number}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                    'template' => [
                        'invoices-batch-payments',
                    ]
                ],
                [
                    'name'      => 'operator ID',
                    'key'       => '{operator_id}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                ],
                [
                    'name'      => 'Password',
                    'key'       => '{password}',
                    'available' => [
                    ],
                    'templates' => [
                        'new-operator-created',
                    ],
                ],
                [
                    'name'      => 'Statement From',
                    'key'       => '{statement_from}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'Statement To',
                    'key'       => '{statement_to}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'Statement Balance Due',
                    'key'       => '{statement_balance_due}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'Statement Amount Paid',
                    'key'       => '{statement_amount_paid}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'Statement Invoiced Amount',
                    'key'       => '{statement_invoiced_amount}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'Statement Beginning Balance',
                    'key'       => '{statement_beginning_balance}',
                    'available' => [

                    ],
                    'templates' => [
                        'operator-statement',
                    ],
                ],
                [
                    'name'      => 'operator Files Admin Link',
                    'key'       => '{operator_profile_files_admin_link}',
                    'available' => [

                    ],
                    'templates' => [
                        'new-operator-profile-file-uploaded-to-staff',
                    ],
                ],
                [
                    'name'      => 'operator Website',
                    'key'       => '{operator_website}',
                    'available' => [
                        'operator',
                        'invoice',
                        'estimate',
                        'ticket',
                        'contract',
                        'project',
                        'credit_note',
                        'subscriptions',
                    ],
                ],
            ];
    }

    /**
     * Merge fields for Contacts and operators
     * @param  mixed $operator_id
     * @param  string $contact_id
     * @param  string $password   password is used when sending welcome email, only 1 time
     * @return array
     */
    public function format($operator_id, $contact_id = '', $password = '')
    {
        $fields = [];

        if ($contact_id == '') {
            $contact_id = get_primary_contact_user_id($operator_id);
        }

        $fields['{contact_firstname}']                 = '';
        $fields['{contact_lastname}']                  = '';
        $fields['{contact_email}']                     = '';
        $fields['{contact_phonenumber}']               = '';
        $fields['{contact_title}']                     = '';
        $fields['{operator_company}']                    = '';
        $fields['{operator_phonenumber}']                = '';
        $fields['{operator_country}']                    = '';
        $fields['{operator_city}']                       = '';
        $fields['{operator_zip}']                        = '';
        $fields['{operator_state}']                      = '';
        $fields['{operator_address}']                    = '';
        $fields['{operator_website}']                    = '';
        $fields['{password}']                          = '';
        $fields['{operator_vat_number}']                 = '';
        $fields['{contact_public_consent_url}']        = '';
        $fields['{email_verification_url}']            = '';
        $fields['{operator_profile_files_admin_link}'] = '';

        if ($operator_id == '') {
            return $fields;
        }

        $operator = $this->ci->operators_model->get($operator_id);

        if (!$operator) {
            return $fields;
        }

        $this->ci->db->where('userid', $operator_id);
        $this->ci->db->where('id', $contact_id);
        $contact = $this->ci->db->get(db_prefix() . 'contacts')->row();

        if ($contact) {
            $fields['{contact_firstname}']          = e($contact->firstname, false);
            $fields['{contact_lastname}']           = e($contact->lastname, false);
            $fields['{contact_email}']              = e($contact->email);
            $fields['{contact_phonenumber}']        = e($contact->phonenumber);
            $fields['{contact_title}']              = e($contact->title, false);
            $fields['{contact_public_consent_url}'] = contact_consent_url($contact->id);
            $fields['{email_verification_url}']     = site_url('verification/verify/' . $contact->id . '/' . $contact->email_verification_key);
        }

        if (!empty($operator->vat)) {
            $fields['{operator_vat_number}'] = e($operator->vat);
        }

        $fields['{operator_profile_files_admin_link}'] = admin_url('operators/operator/' . $operator->userid . '?group=attachments');
        $fields['{operator_company}']                    = e($operator->company, false);
        $fields['{operator_phonenumber}']                = e($operator->phonenumber);
        $fields['{operator_country}']                    = e(get_country_short_name($operator->country));
        $fields['{operator_city}']                       = e($operator->city, false);
        $fields['{operator_zip}']                        = e($operator->zip);
        $fields['{operator_state}']                      = e($operator->state, false);
        $fields['{operator_address}']                    = e($operator->address, false);
        $fields['{operator_website}']                    = e($operator->website);
        $fields['{operator_id}']                         = e($operator_id);

        if ($password != '') {
            $fields['{password}'] = htmlentities($password);
        }

        $custom_fields = get_custom_fields('operators');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($operator_id, $field['id'], 'operators');
        }

        $custom_fields = get_custom_fields('contacts');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($contact_id, $field['id'], 'contacts');
        }

        return hooks()->apply_filters('operator_contact_merge_fields', $fields, [
            'operator_id' => $operator_id,
            'contact_id'  => $contact_id,
            'operator'    => $operator,
            'contact'     => $contact,
    ]);
    }

    /**
 * Statement merge fields
 * @param  array $statement
 * @return array
 */
    public function statement($statement)
    {
        $fields = [];

        $fields['{statement_from}']              = _d($statement['from']);
        $fields['{statement_to}']                = _d($statement['to']);
        $fields['{statement_balance_due}']       = app_format_money($statement['balance_due'], $statement['currency']->name);
        $fields['{statement_amount_paid}']       = app_format_money($statement['amount_paid'], $statement['currency']->name);
        $fields['{statement_invoiced_amount}']   = app_format_money($statement['invoiced_amount'], $statement['currency']->name);
        $fields['{statement_beginning_balance}'] = app_format_money($statement['beginning_balance'], $statement['currency']->name);

        return hooks()->apply_filters('operator_statement_merge_fields', $fields, [
            'statement' => $statement,
         ]);
    }

    /**
     * Password merge fields
     * @param  array $data
     * @param  string $type  template type
     * @return array
     */
    public function password($data, $type)
    {
        $fields['{reset_password_url}'] = '';
        $fields['{set_password_url}']   = '';

        if ($type == 'forgot') {
            $fields['{reset_password_url}'] = site_url('authentication/reset_password/0/' . $data['userid'] . '/' . $data['new_pass_key']);
        } elseif ($type == 'set') {
            $fields['{set_password_url}'] = site_url('authentication/set_password/0/' . $data['userid'] . '/' . $data['new_pass_key']);
        }

        return $fields;
    }
}
