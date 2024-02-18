<style type="text/css">
    .qty_error{
        display: none;
    }

</style>
<style type="text/css">

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px !important; border-radius: 0 !important; padding-left: 0 !important;}
    .input-group-addon .glyphicon{font-size: 12px;}    

    .show{
        display : block;
        z-index: 100;
        background-image : url('../../backend/images/timeloader.gif');
        opacity : 0.6;
        background-repeat : no-repeat;
        background-position : center;
    }
    /* .tab-pane{min-height: 200px;}*/
    .commentForm .input-group {position: relative;display: block;border-collapse: separate;}
    .commentForm .input-group-addon{
        position: absolute;
        right: 26px;
        top: 0px;
        z-index: 3;
    }
    .relative{position: relative;}
    .commentForm .input-group-addon i,
    .commentForm .input-group-addon span{padding-left: 13px;}
    .commentForm .relative label.text-danger{position: absolute; bottom: 5px;}
    .addbtnright{ position: absolute;right: 0;top: -46px;}

    @media(max-width:767px){
        .timeresponsive{overflow-x: auto;     overflow-y: hidden;}
        .timeresponsive .dropdown-menu{z-index: 1060;    bottom: 0 !important; height: 250px; padding: 20px;}
        .tablewidthRS{width: 690px;}
    }
    
    input[type=text]{
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
         border-radius: 5px;
         border: 1px solid #757575;
         color: #000000;
         width: 250px;
         height: 30px;
         padding-left: 10px;
         border-bottom: 1px solid #757575 !important;
        }
        
    input[type=text]:focus {
         outline: none;
         border: 1px solid #757575;
         color: #000000;
         border-bottom: 1px solid #757575 !important;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i>  <?php echo $this->lang->line('library'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <?php
                if ($memberList->member_type == "student") {
                    $this->load->view('admin/librarian/_student');
                } else {
                    $this->load->view('admin/librarian/_teacher');
                }
                ?>
            </div>
            <div class="col-md-9">
                <div class="box box-primary" style="padding: 1rem 2rem 0 1rem;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('issue_book'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">

                        <div class="box-body">
                            <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                }
                                echo $this->customlib->getCSRF();    
                            ?>

                            <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />

                            <div class="form-group">
                                
                                <div class="row">
                                    <label for="exampleInputEmail1">Book ISBN <small class="req"> *</small></label>
                                    <div class="form-group">
                                     <input type="text" name="muema" id="muema" onkeypress="memSort(event);" class="form-control" placeholder="Scan / Type ISBN"></div>
                                </div>
                                
                                <span class="text-danger"><?php echo form_error('book_id'); ?></span>
                                <span class="text text-danger qty_error"><b><?php echo $this->lang->line('available_quantity'); ?></b>: <span class="ava_quantity">0</span></span>
                            </div>
                            
                            
                            
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6"><h3>Books list</h3></div>
                                            <div class="col-md-6" align="right">
                                                <button type="button" name="clear_cart" id="clear_cart" class="btn btn-warning btn-sm"><i class="mdi mdi-cart-off"></i> Clear list</button>
                                            </div>
                                        </div>
                                        <div class="panel-body" id="shopping_cart" style="padding-top: 5px;">
                                            <div id="shopping_cart_no_data">
                                            <center><h3>No Data</h3></center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
                
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_issued'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('book_issued'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_no'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due') . ' ' . $this->lang->line('return_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($issued_books)) {
                                        ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($issued_books as $book) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_no'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['duereturn_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($book['return_date'] != '') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['return_date']));
                                                    }
                                                    ?></td>
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($book['is_returned'] == 0) {
                                                        ?> 

                                                        <a href="#" class="btn btn-default btn-xs"  data-record-id="<?php echo $book['id'] ?>" data-record-member_id="<?php echo $memberList->lib_member_id; ?>" title="<?php echo $this->lang->line('return') . " " . $book['book_title'] ?>" data-toggle="modal" data-target="#confirm-return">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="confirm-return" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_return'); ?></h4>
            </div>
            <form action="<?php echo site_url('admin/member/bookreturn') ?>" method="POST" id="return_book">
                <div class="modal-body issue_retrun_modal-body">

                    <input type="hidden" name="id" id="return_model_id" value="0">
                    <input type="hidden" name="member_id" id="return_model_member_id" value="0">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('return_date'); ?></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control date" id="input-date" name="date" placeholder="Date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                        </div>

                        <div id="error" class="text text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        
        $('#confirm-return').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        
        document.getElementById("muema").focus();
    });

    function memSort(e) {
        var key = e.keyCode || e.which;
        if (key==13) {
            var text = document.getElementById('muema').value;
            test_function(text);
            document.getElementById("muema").value = "";
            document.getElementById("muema").focus();
        }
    }
    
    function test_function(text) {
        $.ajax({
            url:"fetch_item.php?q="+text,
            method:"GET",
            success:function(data) {
                var myarr = data.split("#");
                var action = "add";
                var item_id = myarr[0];
                var item_name = myarr[1];
                var item_price = myarr[2];
                $.ajax({
                url:"action.php",
                method:"POST",
                data:{product_id:item_id, product_name:item_name, product_price:item_price, action:action},
                success:function(data){
                load_cart_data();
                }
                });
                document.getElementById("muema").focus();
            }
        });
    }

    $(document).on('change', '#book_id', function (e) {
        var book_id = $(this).val();
        // $('#div_avail').hide();
        availableQuantity(book_id);
    });

    function availableQuantity(book_id) {
        $('.ava_quantity').html(0);
        if (book_id != "") {
            $.ajax({
                type: "POST",
                url: base_url + "admin/book/getAvailQuantity",
                data: {'book_id': book_id},
                dataType: "json",
                beforeSend: function () {
                    $('.qty_error').show();
                    $('.ava_quantity').empty().html('Loading...');
                },
                success: function (data) {
                    $('.ava_quantity').html(data.qty);
                    console.log(data.qty);
                },
                complete: function () {
                }

            });
        } else {
            $('.qty_error').hide();
        }
    }


    $('#confirm-return').on('show.bs.modal', function (e) {

        var data = $(e.relatedTarget).data();
        $('#return_model_member_id').val(data.recordMember_id);
        $('#return_model_id').val(data.recordId);
    });


    $("form#return_book").submit(function (e) {

        var form = $(this);
        var url = form.attr('action');
        console.log(form);
        var $this = $(this);
        var $btn = $this.find("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: 'JSON',
            beforeSend: function () {

                $btn.button('loading');
            },
            success: function (response, textStatus, xhr) {

                if (response.status == 'fail') {
                    $.each(response.error, function (key, value) {
                        $('#input-' + key).parents('.form-group').find('#error').html(value);
                    });
                }
                if (response.status == 'success') {
                    successMsg(response.message);
                    location.reload();
                }


            },
            error: function (xhr, status, error) {
                $btn.button('reset');

            },
            complete: function () {
                $btn.button('reset');
            },
        });
        e.preventDefault();
    });



</script>