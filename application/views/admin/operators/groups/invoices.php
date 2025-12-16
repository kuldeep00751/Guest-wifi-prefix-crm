<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= _l('operator_invoices_tab'); ?></h4>
<?php if (staff_can('create', 'invoices')) { ?>
<a href="<?= admin_url('invoices/invoice?operator_id=' . $operator->userid); ?>"
    class="btn btn-primary mbot15<?= $operator->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('create_new_invoice'); ?>
</a>
<?php } ?>
<?php if (staff_can('view', 'invoices') || staff_can('view_own', 'invoices') || get_option('allow_staff_view_invoices_assigned') == '1') { ?>
<a href="#" class="btn btn-default mbot15" data-toggle="modal" data-target="#operator_zip_invoices">
    <i class="fa-regular fa-file-zipper tw-mr-1"></i>
    <?= _l('zip_invoices'); ?>
</a>
<div id="invoices_total" class="tw-mb-5"></div>
<?php
        $this->load->view('admin/invoices/table_html', ['class' => 'invoices-single-operator']);
    $this->load->view('admin/operators/modals/zip_invoices');
    ?>
<?php } ?>
<?php } ?>