<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($operator)) { ?>
<h4 class="operator-profile-group-heading">
    <?= _l('operator_expenses_tab'); ?></h4>
<?php if (staff_can('create', 'expenses')) { ?>
<a href="<?= admin_url('expenses/expense?operator_id=' . $operator->userid); ?>"
    class="btn btn-primary mbot15<?= $operator->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('new_expense'); ?>
</a>
<?php } ?>
<div id="expenses_total" class="tw-mb-5"></div>
<?php $this->load->view('admin/expenses/table_html', [
    'class'           => 'expenses-single-operator',
    'withBulkActions' => false,
]); ?>
<?php } ?>