<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= _l('contracts_invoices_tab'); ?>
</h4>
<?php if (staff_can('create', 'contracts')) { ?>
<a href="<?= admin_url('contracts/contract?operator_id=' . $operator->userid); ?>"
    class="btn btn-primary mbot15<?= $operator->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('new_contract'); ?>
</a>
<div class="clearfix"></div>
<?php } ?>
<?php $this->load->view('admin/contracts/table_html', ['class' => 'contracts-single-operator']); ?>
<?php } ?>