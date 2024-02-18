<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .waitpage {
        background: none repeat scroll 0 0 rgba(0,0,0,0.5);
        height: 100%;
        left: 0;
        overflow: hidden;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
    }
    
    .waitpage-box {
        background: none repeat scroll center center #fff;
        border: 1px solid #999;
        border-radius: 5px 5px 5px 5px;
        box-shadow: 0 1px 4px 0 #666;
        left: 44%;
        overflow: auto;
        padding: 16px;
        position: fixed;
        text-align: center;
        top: 46%;
        z-index: 99999;
    }
    .waitpage-box p img {
        color: #d0622b;
        float: left;
        font-size: 14px;
        font-style: italic;
        font-weight: bold;
    }
    .waitpage-box p span {
        float: left;
        font-size: .8125em;
        font-weight: bold;
        padding: 6px 0 0 12px;
        white-space: nowrap;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary" style="padding-bottom: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_book'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import') . " " . $this->lang->line('book'); ?></a> 
                            <?php }
                            ?>


                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/book/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>                     
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?></label><small class="req"> *</small>
                                <input autofocus=""  id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_no'); ?></label>
                                <input id="book_no" name="book_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_no'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('isbn_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <input id="publish" name="publish" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publish'); ?>" />
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" />
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                <input id="subject" name="subject" placeholder="" type="text" class="form-control"  value="<?php echo set_value('subject'); ?>" />
                                <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('rack_no'); ?></label>
                                <input id="rack_no" name="rack_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('rack_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('rack_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty'); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('bookprice'); ?></label>
                                <input id="perunitcost" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('perunitcost'); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('postdate'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-body -->
                        
                        <div class="waitpage" id="waitpage" style="display: none;">
                            <div class="waitpage-box">
                                <p>
                                    <span>Please Wait...</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="container-fluid" style="padding-bottom: 20px;">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6"><h4>Books list</h4></div>
                                            <div class="col-md-6" align="right">
                                                <button type="button" name="clear_cart" id="clear_cart" class="btn btn-warning btn-sm" style="margin-top: 20px;" onclick="clearBookCart()"><i class="mdi mdi-cart-off"></i> Clear list</button>
                                            </div>
                                        </div>
                                        <div id="arr_length"></div>
                                        <table class="table table-bordered table-striped table-hover table-condensed">
                                          <thead>
                                              <tr>
                                                <th width="10%">Book Title</th>
                                                <th width="5%">Book #</th>
                                                <th width="5%">ISBN #</th>
                                                <th width="10%">Publisher</th>
                                                <th width="10%">Author</th>
                                                <th width="10%">Subject</th>
                                                <th width="5%">Rack #</th>
                                                <th width="5%">QTY</th>
                                                <th width="5%">Book Price</th>
                                                <th width="5%">Date</th>
                                                <th width="50%">Description</th>
                                                <th width="5%">Remove</th>
                                              </tr>
                                          </thead>
                                          <tbody id="tdata">
                                          </tbody>
                                        </table>      
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" style="padding-right: 25px;">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>

            </div><!--/.col (right) -->

        </div>
        <div class="row">

            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">


    $(document).ready(function () {



        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });


</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>