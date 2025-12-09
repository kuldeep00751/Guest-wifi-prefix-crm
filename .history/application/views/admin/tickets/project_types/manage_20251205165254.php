<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="tw-mb-2">
                    <a href="#" onclick="new_type(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?= _l('new_project_type'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">

                        <?php if (count($types) > 0) { ?>
                        <table class="table dt-table">
                            <thead>
                                <th><?= _l('id'); ?></th>
                                <th><?= _l('project_type_name'); ?></th>
                                <th class="options"><?= _l('options'); ?></th>
                            </thead>

                            <tbody>
                                <?php foreach ($types as $type) { ?>
                                <tr>
                                    <td><?= e($type['projecttypeid']); ?></td>

                                    <td>
                                        <a href="#" class="tw-font-medium"
                                           onclick="edit_type(this,<?= e($type['projecttypeid']); ?>); return false"
                                           data-name="<?= e($type['name']); ?>">
                                           <?= e($type['name']); ?>
                                        </a>
                                        <br>
                                        <?= _l('total_projects', total_rows(db_prefix() . 'projects', ['projecttype' => $type['projecttypeid']])); ?>
                                    </td>

                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-2">

                                            <a href="#"
                                               onclick="edit_type(this,<?= e($type['projecttypeid']); ?>); return false"
                                               data-name="<?= e($type['name']); ?>"
                                               class="tw-text-neutral-500 hover:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>

                                            <?php if ($type['isdefault'] == 0) { ?>
                                            <a href="<?= admin_url('projects/delete_project_type/' . $type['projecttypeid']); ?>"
                                               class="tw-text-neutral-500 hover:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                            <?php } ?>

                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>

                        </table>

                        <?php } else { ?>
                        <p class="no-margin"><?= _l('no_project_types_found'); ?></p>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="project_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('projects/type')); ?>

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?= _l('project_type_edit'); ?></span>
                    <span class="add-title"><?= _l('new_project_type'); ?></span>
                </h4>
            </div>

            <div class="modal-body">
                <div id="additional"></div>
                <?= render_input('name', 'project_type_name'); ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?= _l('submit'); ?></button>
            </div>

        </div>

        <?= form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function() {
        appValidateForm($('form'), { name: 'required' }, manage_types);

        $('#project_type_modal').on('hidden.bs.modal', function() {
            $('#additional').html('');
            $('#project_type_modal input[name="name"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });

    function manage_types(form) {
        var data = $(form).serialize();
        $.post(form.action, data).done(function() {
            window.location.reload();
        });
        return false;
    }

    function new_type() {
        $('#project_type_modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_type(invoker, id) {
        var name = $(invoker).data('name');

        $('#additional').append(hidden_input('id', id));
        $('#project_type_modal input[name="name"]').val(name);

        $('#project_type_modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>

</body>
</html>
