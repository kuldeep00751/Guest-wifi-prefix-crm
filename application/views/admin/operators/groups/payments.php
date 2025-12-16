<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= _l('operator_payments_tab'); ?></h4>
<a href="#" class="btn btn-primary mbot15" data-toggle="modal" data-target="#operator_zip_payments">
    <i class="fa-regular fa-file-zipper tw-mr-1"></i>
    <?= _l('zip_payments'); ?>
</a>
<?php
    $this->load->view('admin/payments/table_html', ['class' => 'payments-single-operator']);
    $this->load->view('admin/operators/modals/zip_payments');
    ?>
<?php } ?>