<link rel="stylesheet" href="<?php echo base_url('backend\dist\css\gmeet_addon.css'); ?>">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('live_class') ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('live_class') ?></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
 
                    <div class="box-body">
                        <?php
                       
if (!empty($timetable)) {
    ?>
                         <div class="table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <?php
foreach ($timetable as $tm_key => $tm_value) {
        ?>
                                            <th class="text text-center"><?php echo $tm_key; ?></th>
                                            <?php
}
    ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
foreach ($timetable as $tm_key => $tm_value) {
        ?>
                                            <td class="text text-center" width="14%">

                                                <?php
if (!$timetable[$tm_key]) {
            ?>
                                                    <div class="attachment-block clearfix">
                                                        <b class="text text-center"><?php echo $this->lang->line('not'); ?> <br><?php echo $this->lang->line('scheduled'); ?></b><br>
                                                    </div>
                                                    <?php
} else {

            foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                ?>
                                                        <div class="attachment-block clearfix <?php
                                                            if ($this->rbac->hasPrivilege('gmeet_live_classes', 'can_add')) {
                                                                echo "online-timetable";
                                                            }
                                                            ?>" data-subject="<?php echo $tm_kue->subject_name . " (" . $tm_kue->subject_code . ")"; ?>" data-class="<?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?>" data-class-section-id="<?php echo $tm_kue->class_section_id; ?>"  data-class-id="<?php echo $tm_kue->class_id; ?>" data-section-id="<?php echo $tm_kue->section_id; ?>" data-time-from="<?php echo $tm_kue->time_from; ?>">
                                                            <b class="text-green"><?php echo $this->lang->line('subject') ?>: <?php echo $tm_kue->subject_name . " (" . $tm_kue->subject_code . ")"; ?>

                                                            </b>
                                                            <br>

                                                            <strong class="text-green"><?php echo $this->lang->line('class') ?>: <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?></strong><br>
                                                            <strong class="text-green"><?php echo $tm_kue->time_from ?></strong>
                                                            <b class="text text-center">-</b>
                                                            <strong class="text-green"><?php echo $tm_kue->time_to; ?></strong><br>

                                                            <strong class="text-green"><?php echo $this->lang->line('room_no'); ?>: <?php echo $tm_kue->room_no; ?></strong><br>

                                                        </div>

                                                        <?php
}
        }
        ?>

                                            </td>
                                            <?php
}
    ?>
                                    </tr>

                                </tbody>
                            </table>
                         </div>
                            <?php
} else {
    ?>
                            <div class="alert alert-info">
                                <?php echo $this->lang->line('no_record_found'); ?>
                            </div>
                            <?php
}
?>

                    </div>
               <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('scheduled') . ' ' . $this->lang->line('live_class') ?></h3>
                        <div class="box-tools pull-right">
                            <?php 
                            if($link_status){
                                   ?>
                                <a type="button" class="btn googlebtn btn-sm" href="<?php echo $auth_url ?>"><i class="fa fa-google"></i>Sign in with Google</a>
                                <?php
                            }else{
                          if ($this->rbac->hasPrivilege('gmeet_live_classes', 'can_add')) {
                            ?>
                             <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-classteacher-timetable"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button><?php
                              }
                            }
                            
                            ?>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php }?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class') . " " . $this->lang->line('title'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('created_by') ?>
                                        </th>
                                        <th><?php echo $this->lang->line('class'); ?>
                                        </th>
                                         <th><?php echo $this->lang->line('status'); ?>
                                        </th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($gmeets)) {
    ?>

                                        <?php
} else {
    foreach ($gmeets as $gmeet_key => $gmeet_value) {

        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $gmeet_value->title; ?></a>

                                                    <div class="fee_detail_popover displaynone">
                                                        <?php
if ($gmeet_value->description == "") {
            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
} else {
            ?>
                                                            <p class="text text-info"><?php echo $gmeet_value->description; ?></p>
                                                            <?php
}
        ?>
                                                    </div>
                                                </td>

                                                <td class="mailbox-name" data-sort="<?php echo strtotime($gmeet_value->date); ?>">

                                                    <?php echo $this->customlib->dateyyyymmddToDateTimeformat($gmeet_value->date); ?></td>

                                                <td class="mailbox-name">

                                                    <?php
if ($gmeet_value->created_id == $logged_staff_id) {
            echo "Self";
        } else {
            echo $gmeet_value->create_by_name . " " . $gmeet_value->create_by_surname;
        }
        ?></td>



                                                <td class="mailbox-name">
                                       <ul class="liststyle1">
                                                    <?php

        foreach ($gmeet_value->classes as $gmeet_class_key => $gmeet_class_value) {
            ?>
      <li> <i class="fa fa-check-square-o"></i> <?php echo $gmeet_class_value->class . " (" . $gmeet_class_value->section . ")" ?></li>
    <?php
}
        ?>
</ul>

                                                </td>
                                                  <td class="mailbox-name" width="110">
                                                  <form class="chgstatus_form" method="POST" action="<?php echo site_url('admin/gmeet/chgstatus') ?>">
                                                    <input type="hidden" name="gmeet_id" value="<?php echo $gmeet_value->id; ?>">
                                                 <select class="form-control chgstatus_dropdown" name="chg_status">
                                                     <option value="0" <?php if ($gmeet_value->status == 0) {
            echo "selected='selected'";
        }
        ?>><?php echo $this->lang->line('awaited'); ?></option>
                                                     <option value="1" <?php if ($gmeet_value->status == 1) {
            echo "selected='selected'";
        }
        ?>> <?php echo $this->lang->line('cancelled') ?></option>
                                                     <option value="2" <?php if ($gmeet_value->status == 2) {
            echo "selected='selected'";
        }
        ?>> <?php echo $this->lang->line('finished'); ?></option>
                                                 </select>
                                                </form>
                                                </td>

                                                <td class="mailbox-date relative text-right" width="90">
                                                     <?php
if ($gmeet_value->status == 0) {
            ?>
<a data-placement="left" href="<?php echo base_url();?>admin/gmeet/start/<?php echo $gmeet_value->id."/class"; ?>" class="btn label-success btn-xs start-mr-20"  target="_blank">
                                                        <span class="label"><i class="fa fa-sign-in"></i> <?php echo $this->lang->line('start'); ?></span>
                                                    </a>
    <?php
}
        ?>
                                                    <?php
if ($gmeet_value->created_id == $logged_staff_id) {
            if ($this->rbac->hasPrivilege('gmeet_live_classes', 'can_delete')) {
                ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/gmeet/delete/<?php echo $gmeet_value->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                        <?php
}}
        ?>

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




<!-- Modal -->
<div class="modal fade" id="modal-online-timetable">
    <div class="modal-dialog">
        <form id="form-addtimetable" action="<?php echo site_url('admin/gmeet/add'); ?>" method="POST">
            <div class="modal-content relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('live_class') ?></h4>
                    <div class="gplusright">
                    <?php 
                            if($link_status){
                                   ?>
                                <a type="button" class="btn googlebtn btn-sm" href="<?php echo $auth_url ?>"><i class="fa fa-google"></i>Sign in with Google</a>
                                <?php
                            }
                            ?>
                    </div>        
                </div>
                <div class="modal-body">
                     <input type="hidden" name="class_id" id="class_id" value="0">
                    <input type="hidden" name="section_id[]" id="section_id" value="0">
                           
                   <div class="row">
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <label for="title"> <?php echo $this->lang->line('class_title'); ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label for="date"> <?php echo $this->lang->line('class_date') ?><small class="req"> *</small></label>
                            <div class='input-group' id='meeting_date'>
                                <input type='text' class="form-control" name="date" readonly="readonly" />
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>

                            <span class="text text-danger" id="title_error"></span>
                        </div>

                        <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label for="duration"> <?php echo $this->lang->line('class_duration_minutes') ?><small class="req"> *</small></label>
                            <input type="number" class="form-control" id="duration" name="duration">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                     


                     <?php                        
                        if(empty($gmeet_setting) || !$gmeet_setting->use_api){
                            ?>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <label for="url"> <?php echo $this->lang->line('gmeet')." ".$this->lang->line('url'); ?> (<?php echo $this->lang->line('how_to_get');  ?> <a class="labelurl" href="https://smart-school.in/article/how-to-get-gmeet-url" target="_blank"> <?php echo $this->lang->line('gmeet')." ".$this->lang->line('url'); ?></a>? )<small class="req"> *</small> </label>
                           
                            <input class="form-control" name="url" id="url">

                        </div>
                            <?php
                        }
                        ?>


                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>

                        </div> 

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modal-classteacher-timetable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-addconference" action="<?php echo site_url('admin/gmeet/addByClassTeacher'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('live_class') ?></h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" class="form-control" id="password" name="password">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <div class="form-group">  
                            <label for="title"> <?php echo $this->lang->line('class_title') ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="text text-danger" id="title_error"></span>
                          </div>  
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                          <div class="form-group">  
                            <label for="date"> <?php echo $this->lang->line('class_date') ?><small class="req"> *</small></label>
                            <div class='input-group' id='meeting_classteacher_date'>
                                <input type='text' class="form-control" name="date" readonly="readonly"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <span class="text text-danger" id="title_error"></span>
                          </div>  
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                          <div class="form-group">  
                            <label for="duration"> <?php echo $this->lang->line('class_duration_minutes') ?><small class="req"> *</small></label>
                            <input type="number" class="form-control" id="duration" name="duration">
                            <span class="text text-danger" id="title_error"></span>
                          </div>  
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <div class="form-group">  
                            <label for="class"> <?php echo $this->lang->line('staff') ?><small class="req"> *</small></label>
                            <select  id="staff_id" name="staff_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($stafflist as $staff) {
    ?>
                                    <option value="<?php echo $staff['id']; ?>"><?php
echo ($staff["surname"] == "") ? $staff["name"] : $staff["name"] . " " . $staff["surname"];
    ?></option>
                                    <?php
}
?>
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                          </div>  
                        </div>

                          <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                            <label for="class_id"> <?php echo $this->lang->line('class') ?><small class="req"> *</small></label>
                                  <select id="class_id" name="class_id" class="form-control"  >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($classlist as $class) {
    ?>
                             <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                            <?php
}
?>
                            </select>
                            <span class="text text-danger" id="title_error"></span>
                          </div>  
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                            <label for="section_id"> <?php echo $this->lang->line('section') ?><small class="req"> *</small></label>
                                  <select id="section_id_timetable" name="section_id[]" class="form-control section-list-classteacher-timetable fullselectbox" multiple="multiple">

                              </select>
                          </div>    
                        </div>
                           <?php                        
                        if(empty($gmeet_setting) || !$gmeet_setting->use_api){
                            ?>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                       <div class="form-group">     
                        <label for="url"> <?php echo $this->lang->line('gmeet')." ".$this->lang->line('url'); ?> (<?php echo $this->lang->line('how_to_get');  ?> <a class="labelurl" href="https://smart-school.in/article/how-to-get-gmeet-url" target="_blank"> <?php echo $this->lang->line('gmeet')." ".$this->lang->line('url'); ?></a>? )<small class="req"> *</small> </label>
                        <input class="form-control" name="url" id="url">
                       </div>
                         </div>
                            <?php
                        }
                        ?>                    
                       <div class="clearfix"></div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>

                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

   var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'M' => "MMM", 'Y' => 'YYYY']) ?>';
 $(document).ready(function () {
       $('.section-list-classteacher-timetable').select2();
       
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

                $('#modal-classteacher-timetable,#modal-online-timetable').modal({
          backdrop: 'static',
          keyboard: false,
          show:false
    });

        $(document).on('click', '.online-timetable', function (event) {
            var class_name = $(this).data('class');
            var subject_name = $(this).data('subject');
            var classSectionId = $(this).data('classSectionId');
            var class_id = $(this).data('classId');
            var section_id = $(this).data('sectionId');
            var timeFrom = $(this).data('timeFrom');
            var format_hour = Converttimeformat(timeFrom);
            var d = new Date();
            d.setHours(format_hour.hours, format_hour.minutes, format_hour.second);
           
            $('#meeting_date').data("DateTimePicker").date(d);
            $('#class_id').val("").val(class_id);
            $('#section_id').val("").val(classSectionId);
            $('#class').val("").val(class_name);
            $('#title').val("");
            $('#password').val("").val(password);
            $('#modal-online-timetable').modal('show');

        }); 
    });
    $('#meeting_date').datetimepicker({
        format: datetime_format + " HH:mm",
        showTodayButton: true,
       locale:  moment.locale('en', {
        week: { dow: start_week }
    }),
        ignoreReadonly: true
    });
    $('#meeting_date,#meeting_classteacher_date').datetimepicker({
        format: datetime_format + " HH:mm",
        showTodayButton: true,
        locale:  moment.locale('en', {
        week: { dow: start_week }
    }),
        ignoreReadonly: true
    });
  
    //===========================form submit==========
    $("form#form-addtimetable").submit(function (event) {
        event.preventDefault();

        var $form = $(this),
        url = $form.attr('action');

        var $button = $form.find("button[type=submit]:focus");
        $.ajax({
            type: "POST",
            url: url,
            data: $form.serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $button.button('loading');

            },
            success: function (data) {
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    $('#modal-online-timetable').modal('hide');
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $button.button('reset');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $button.button('reset');
            },
            complete: function (data) {
                $button.button('reset');
            }
        });

    });
    //===========================form submit==========
    $("form#form-addconference").submit(function (event) {
        event.preventDefault();

        var $form = $(this),
        url = $form.attr('action');

        var $button = $form.find("button[type=submit]:focus");
        $.ajax({
            type: "POST",
            url: url,
            data: $form.serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $button.button('loading');

            },
            success: function (data) {
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    $('#modal-classteacher-timetable').modal('hide');
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $button.button('reset');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $button.button('reset');
            },
            complete: function (data) {
                $button.button('reset');
            }
        });

    });
    //================================================

    $('#modal-online-timetable').on('hidden.bs.modal', function () {

        $(this).find("input,textarea,select").not("input[type=radio]")
                .val('')
                .end();
        $(this).find("input[type=checkbox], input[type=radio]")
                .prop('checked', false);

    });

    function Converttimeformat(time) {


        var hrs = Number(time.match(/^(\d+)/)[1]);
        var mnts = Number(time.match(/:(\d+)/)[1]);
        var format = time.match(/\s(.*)$/)[1];
        if (format == "PM" && hrs < 12)
            hrs = hrs + 12;
        if (format == "AM" && hrs == 12)
            hrs = hrs - 12;
        var hours = hrs.toString();
        var minutes = mnts.toString();
        if (hrs < 10)
            hours = "0" + hours;
        if (mnts < 10)
            minutes = "0" + minutes;

        return {
            hours: hours,
            minutes: minutes,
            second: 0
        };
    }
</script>

<script type="text/javascript">
       $('#modal-classteacher-timetable').on('hidden.bs.modal', function (e) {
        $("#class_id",this).prop("selectedIndex", 0);
        $(".section-list-classteacher-timetable").select2("val", "");
        $(this).find("input,textarea").val('').end();
    });

         $(document).on('change', '#form-addconference #class_id', function (e) {
         
         var class_id = $(this).val();
        getSectionByClass('form-addconference',class_id, 0);
    });

    function getSectionByClass(selector,class_id, section_id) {

        if (class_id != "") {
            $('#'+selector+' #section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#'+selector+' #section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + ">" + obj.section + "</option>";
                    });

                    $('#section_id_timetable').html("").html(div_data);
                    $('.section-list-classteacher-timetable').select2('val',section_id);

                },
                complete: function () {
                    $('#'+selector+' #section_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>

<script type="text/javascript">
    $(document).on('change','.chgstatus_dropdown',function(){
        $(this).parent('form.chgstatus_form').submit();

    });

    $("form.chgstatus_form").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           dataType:"JSON",
           success: function(data)
           {
               if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
           }
         });
});
</script>