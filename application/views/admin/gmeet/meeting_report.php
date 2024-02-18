<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i><?php echo $this->lang->line('live_meeting') . ' ' . $this->lang->line('report'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i><?php echo $this->lang->line('live_meeting') . ' ' . $this->lang->line('report'); ?></h3>

                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php }?>
                        <div class="table-responsive">
						<div class="download_label"><?php echo $this->lang->line("live_meeting") . " " . $this->lang->line('report') ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('meeting') . ' ' . $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('total') . ' ' . $this->lang->line('join'); ?></th>
										<th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($meetingList)) {
    ?>

                                        <?php
} else {
    foreach ($meetingList as $meeting_key => $meeting_value) {

        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $meeting_value->title; ?></a>

                                                    <div class="fee_detail_popover displaynone">
                                                        <?php
if ($meeting_value->description == "") {
            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
} else {
            ?>
                                                            <p class="text text-info"><?php echo $meeting_value->description; ?></p>
                                                            <?php
}
        ?>
                                                    </div>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $this->customlib->dateyyyymmddToDateTimeformat($meeting_value->date); ?></td>
                                                <td class="mailbox-name">

                                                    <?php
if ($meeting_value->created_id == $logged_staff_id) {
            echo $this->lang->line('self');
        } else {
            echo $meeting_value->create_by_name . " " . $meeting_value->create_by_surname;
        }
        ?>

                                                    </td>
                                                <td>
                                                    <?php echo $meeting_value->total_viewers; ?>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                <button type="button" class="btn btn-default btn-xs viewer-list" id="load"  data-recordid="<?php echo $meeting_value->id; ?>" title="<?php echo $this->lang->line('join'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-list"></i></button>
                                                </td>
                                            </tr>
                                            <?php
}
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="viewerModal" class="modal fade modalmark" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('join') . ' ' . $this->lang->line('list'); ?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
  (function($) {
    $(document).on('click', '.viewer-list', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');

        $.ajax({
            type: 'POST',
            url: baseurl + "admin/gmeet/getViewerList",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {

                $('#viewerModal .modal-body').html(data.page);
                //===============
           $(".viewer-list-datatable").DataTable({
            dom: "Bfrtip",
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.downloadlabel').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.downloadlabel').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.downloadlabel').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.downloadlabel').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.downloadlabel').html(),
                    customize: function (win) {
                        $(win.document.body)
                                .css('font-size', '10pt');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.downloadlabel').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
                //==================
                $('#viewerModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });

    });
    })(jQuery);
</script>