<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= e(is_empty_operator_company($operator->userid) ? _l('contact') : _l('operator_contacts')); ?>
</h4>
<?php if ($this->session->flashdata('gdpr_delete_warning')) { ?>
<div class="alert alert-warning">
    [GDPR] The contact you removed has associated proposals using the email address of the contact and other personal
    information. You may want to re-check all proposals related to this operator and remove any personal data from
    proposals linked to this contact.
</div>
<?php } ?>
<?php if ((staff_can('create', 'operators') || is_operator_admin($operator->userid)) && $operator->registration_confirmed == '1') {
    $disable_new_contacts = false;
    if (is_empty_operator_company($operator->userid) && total_rows(db_prefix() . 'contacts', ['userid' => $operator->userid]) == 1) {
        $disable_new_contacts = true;
    } ?>
<div class="inline-block new-contact-wrapper"
    data-title="<?= _l('operator_contact_person_only_one_allowed'); ?>"
    <?php if ($disable_new_contacts) { ?> data-toggle="tooltip"
    <?php } ?>>
    <a href="#"
        onclick="contact(<?= e($operator->userid); ?>); return false;"
        class="btn btn-primary new-contact mbot15<?php if ($disable_new_contacts) {
            echo ' disabled';
        } ?>">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?= _l('new_contact'); ?>
    </a>
</div>
<?php
} ?>
<?php
    $table_data = [_l('operators_list_full_name')];
    if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
        array_push($table_data, [
            'name'     => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
            'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
        ]);
    }
    $table_data    = array_merge($table_data, [_l('operator_email'), _l('contact_position'), _l('operator_phonenumber'), _l('contact_active'), _l('operators_list_last_login')]);
    $custom_fields = get_custom_fields('contacts', ['show_on_table' => 1]);

    foreach ($custom_fields as $field) {
        array_push($table_data, [
            'name'     => $field['name'],
            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
        ]);
    }
    echo render_datatable($table_data, 'contacts'); ?>
<?php } ?>
<div id="contact_data"></div>
<div id="consent_data"></div>