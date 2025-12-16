<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="operator_profile">
    <div class="content">
        <div class="md:tw-w-[calc(100%-theme(width.64)+theme(spacing.16))] [&_div:last-child]:tw-mb-6">
            <?php if (isset($operator) && $operator->registration_confirmed == 0 && is_admin()) { ?>
            <div class="alert alert-warning">
                <h4>
                    <?= _l('operator_requires_registration_confirmation'); ?>
                </h4>
                <a href="<?= admin_url('operators/confirm_registration/' . $operator->userid); ?>"
                    class="alert-link">
                    <?= _l('confirm_registration'); ?>
                </a>
            </div>
            <?php } elseif (isset($operator) && $operator->active == 0 && $operator->registration_confirmed == 1) { ?>
            <div class="alert alert-warning">
                <?= _l('operator_inactive_message'); ?>
                <br />
                <a href="<?= admin_url('operators/mark_as_active/' . $operator->userid); ?>"
                    class="alert-link">
                    <?= _l('mark_as_active'); ?>
                </a>
            </div>
            <?php } ?>
            <?php if (isset($operator) && (staff_cant('view', 'operators') && is_operator_admin($operator->userid))) {?>
            <div class="alert alert-info">
                <?= e(_l('operator_admin_login_as_operator_message', get_staff_full_name(get_staff_user_id()))); ?>
            </div>
            <?php } ?>
        </div>

        <?php if (isset($operator) && $operator->leadid != null) { ?>
        <small class="tw-block">
            <b><?= e(_l('operator_from_lead', _l('lead'))); ?></b>
            <a href="<?= admin_url('leads/index/' . $operator->leadid); ?>"
                onclick="init_lead(<?= e($operator->leadid); ?>); return false;">
                -
                <?= _l('view'); ?>
            </a>
        </small>
        <?php } ?>

        <div class="md:tw-max-w-64 tw-w-full">
            <?php if (isset($operator)) { ?>
            <h4 class="tw-text-lg tw-font-bold tw-text-neutral-800 tw-mt-0">
                <div class="tw-space-x-3 tw-flex tw-items-center">
                    <span class="tw-truncate">
                        #<?= e($operator->userid . ' ' . $title); ?>
                    </span>
                    <?php if (staff_can('delete', 'operators') || is_admin()) { ?>
                    <div class="btn-group">
                        <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php if (is_admin()) { ?>
                            <li>
                                <a href="<?= admin_url('operators/login_as_operator/' . $operator->userid); ?>"
                                    target="_blank">
                                    <i class="fa-regular fa-share-from-square"></i>
                                    <?= _l('login_as_operator'); ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if (staff_can('delete', 'operators')) { ?>
                            <li>
                                <a href="<?= admin_url('operators/delete/' . $operator->userid); ?>"
                                    class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                    <?= _l('delete'); ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </h4>
            <?php } ?>
        </div> 

        <div class="md:tw-flex md:tw-gap-6">
            <?php if (isset($operator)) { ?>
            <div class="md:tw-max-w-64 tw-w-full">
                <?php $this->load->view('admin/operators/tabs'); ?>
            </div>
            <?php } ?>
            <div
                class="tw-mt-12 md:tw-mt-0 tw-w-full <?= isset($operator) ? 'tw-max-w-6xl' : 'tw-mx-auto tw-max-w-4xl'; ?>">

                <?php if (! isset($operator)) {?>
                <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700">
                    <?= $title ?>
                </h4>
                <?php } ?>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (isset($operator)) { ?>
                        <?= form_hidden('isedit'); ?>
                        <?= form_hidden('userid', $operator->userid); ?>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <div>
                            <div class="tab-content">
                                <?php $this->load->view((isset($tab) ? $tab['view'] : 'admin/operators/groups/profile')); ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($group == 'profile') { ?>
                    <div class="panel-footer text-right tw-space-x-1" id="profile-save-section">
                        <?php if (! isset($operator)) { ?>
                        <button class="btn btn-default save-and-add-contact operator-form-submiter">
                            <?= _l('save_operator_and_add_contact'); ?>
                        </button>
                        <?php } ?>
                        <button class="btn btn-primary only-save operator-form-submiter">
                            <?= _l('submit'); ?>
                        </button>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<?php if (isset($operator)) { ?>
<script>
    $(function() {
        init_rel_tasks_table( <?= e($operator->userid); ?> ,
            'operator');
    });
</script>
<?php } ?>
<?php $this->load->view('admin/operators/operator_js'); ?>
</body>

</html>