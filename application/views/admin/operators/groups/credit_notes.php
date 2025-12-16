<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= _l('credit_notes'); ?></h4>
<div class="alert alert-warning">
    <?= e(_l('x_credits_available', app_format_money($credits_available, $operator_currency))); ?>
</div>
<?php if (staff_can('create', 'credit_notes')) { ?>
<a href="<?= admin_url('credit_notes/credit_note?operator_id=' . $operator->userid); ?>"
    class="btn btn-primary mbot15<?= $operator->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('new_credit_note'); ?>
</a>
<?php } ?>
<?php if (staff_can('view', 'credit_notes') || staff_can('view_own', 'credit_notes')) { ?>
<a href="#" class="btn btn-default mbot15" data-toggle="modal" data-target="#operator_zip_credit_notes">
    <i class="fa-regular fa-file-zipper tw-mr-1"></i>
    <?= _l('zip_credit_notes'); ?>
</a>
<?php } ?>
<?php
    $this->load->view('admin/credit_notes/table_html');
    $this->load->view('admin/operators/modals/zip_credit_notes');
    ?>
<?php } ?>