<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_attendance');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('report/attendancereport') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
    ?>
                                            <option value="<?php echo $key ?>" <?php
if ((isset($search_type)) && ($search_type == $key)) {

        echo "selected";
    }
    ?>><?php echo $search ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>

                            </div>
                            <div class="col-sm-3 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('attendance') . " " . $this->lang->line('type'); ?></label><small class="req"> *</small>
                                    <select class="form-control" name="attendance_type" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($attendance_type as $value) {
    ?>
                                            <option value="<?php echo $value['id'] ?>" <?php
if ((isset($attendance_type_id)) && ($attendance_type_id == $value['id'])) {

        echo "selected";
    }
    ?>><?php echo $value['type'] . " (" . $value['key_value'] . ")"; ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('attendance_type'); ?></span>
                                </div>
                            </div>
                           
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('attendance') . " " . $this->lang->line('report'); ?> </h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('student') . " " . $this->lang->line('attendance') . " " . $this->lang->line('report');
$this->customlib->get_postmessage();
?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>

                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <?php if ($sch_setting->father_name) {?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php }?>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
<?php if ($sch_setting->admission_date) {?>
                                            <th><?php echo $this->lang->line('admission') . " " . $this->lang->line('date'); ?></th><?php }?>

                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <?php if ($sch_setting->category) {?>

                                        <?php }if ($sch_setting->mobile_no) {?>
                                            <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                            
<?php }?>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$count = 0;
if (empty($student_attendences)) {
    ?>

                                        <?php
} else {
    $count = 1;
    foreach ($student_attendences as $student) {
        ?>
                                            <tr>
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $student['class'] . " (" . $student['section'] . ")" ?></td>
                                                <?php if ($sch_setting->father_name) {?>
                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <?php }?>
                                                <td><?php
if (!empty($student['dob'])) {
            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
        }
        ?></td>
                                                    <?php if ($sch_setting->admission_date) {
            ?>
                                                    <td><?php
if (!empty($student['admission_date'])) {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']));
            }
            ?></td><?php }?>
                                                <td><?php echo $student['gender']; ?></td>
                                                <?php if ($sch_setting->category) {?>

                                                <?php }if ($sch_setting->mobile_no) {?>
                                                    <td><?php echo $student['mobileno']; ?></td>
        <?php }?>
        
         <td>
        
        <p onclick="showEditInput(event, '<?php echo $student['att_id'];?>')" id="<?php echo 'remarkTd'.$student['att_id'] ?>">
            
            <?php
if (!empty($student['remark'])) {
            echo $student['remark'];
        }else{
            echo "No remark";
        }
        ?>
            
        </p>
        
        <input placeholder="Enter remark" style="display: none; max-width: 100px" onkeyup="updateRemark(event, '<?php echo $student['att_id'];?>')" id="<?php echo 'remarkEdit'.$student['att_id'] ?>" type = "text">
         
        </td>
        

                                            </tr>
                                            <?php
$count++;
    }
}
?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<script>
<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}

if ($class_id != '') {
    ?>
        var class_id = <?php echo $class_id; ?>;
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + "  >" + obj.section + "</option>";
                });

                $('#section_id').html(div_data);
            }
        });
    <?php
}

if ($section_id != '') {
    ?>

    <?php
}
?>

    $(document).on('change', '#class_id', function (e) {
        var class_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + "  >" + obj.section + "</option>";
                });

                $('#section_id').html(div_data);
            }
        });
    });
    
   function showEditInput(event, id){
        console.log('remark clicked');
         console.log(id);
         $('#remarkTd'+id).hide();
         $('#remarkEdit'+id).show();
         var currentRemark =  $('#remarkTd'+id).html();
        //  $('#remarkEdit'+id).val(currentRemark);
    }
    
    function updateRemark(event, id){
         console.log('update remark');
           console.log(id);
         console.log(event.target.value);
         var remark = event.target.value;
         
         if(event.keyCode == 13)
         
         //make call to update remark
          $.ajax({
            type: "GET",
            url: base_url + "report/updateRemarks",
            data: {'remark': remark,
                'att_id': id
            },
            success: function (data) {
               console.log('success');
               console.log(id);
                 $('#remarkEdit'+id).hide();
                 $('#remarkTd'+id).show();
                 console.log(event.target.value);
                 $('#remarkTd'+id).html(event.target.value);
              
            }
        });
         
    }

</script>