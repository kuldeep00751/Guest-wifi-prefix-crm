<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="operator-profile-group-heading">
    <?= _l('operator_reminders_tab'); ?>
</h4>
<?php if (isset($operator)) { ?>
<a href="#" data-toggle="modal"
    data-target=".reminder-modal-operator-<?= e($operator->userid); ?>"
    class="btn btn-primary mbot15">
    <i class="fa-regular fa-bell"></i>
    <?= _l('set_reminder'); ?>
</a>
<div class="clearfix"></div>

<?php render_datatable([_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders');
    $this->load->view('admin/includes/modals/reminder', ['id' => $operator->userid, 'name' => 'operator', 'members' => $members, 'reminder_title' => _l('set_reminder')]);
} ?>