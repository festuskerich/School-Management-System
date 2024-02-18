
<div class="downloadlabel hide" id="downloadlabel"><?php echo $this->lang->line('join') . ' ' . $this->lang->line('list'); ?></div>
<?php

if (!empty($viewerDetail)) {

    ?>

    <table class="table table-hover table-striped table-bordered viewer-list-datatable">
                        <thead>
                            <tr>
                             <th><?php echo $this->lang->line('staff'); ?></th>
                             <th><?php echo $this->lang->line('staff_id'); ?></th>
                             <th class="pull-right"><?php echo $this->lang->line('role'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
foreach ($viewerDetail as $viewer_key => $viewer_value) {
        ?>
<tr>
    <td>
      <?php echo $viewer_value->staff_name . " " . $viewer_value->staff_surname; ?></td>
    <td><?php echo $viewer_value->employee_id; ?></td>
    <td class="pull-right"><?php echo $viewer_value->role_name; ?></td>
</tr>
<?php
}
    ?>
                        </tbody>
                      </table>

<?php

} else {
    ?>
 <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php
}

?>