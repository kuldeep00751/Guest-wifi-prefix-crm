<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="operator-profile-group-heading">
    <?= _l('tasks'); ?></h4>
<?php if (isset($operator)) {
    init_relation_tasks_table(['data-new-rel-id' => $operator->userid, 'data-new-rel-type' => 'operator']);
} ?>