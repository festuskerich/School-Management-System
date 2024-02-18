<style type="text/css">
    .qty_error{
        display: none;
    }

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

                    <div class="box-body">
                    
                        <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                    
                            <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                }
                                echo $this->customlib->getCSRF();    
                            ?>

                            <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />
                            
                            <input id="return_date" name="return_date"  type="hidden" class="form-control" />
                            
                            <input id="book_id" name="book_id"  type="hidden" class="form-control" />
                            
                        </form>
                        

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Book ISBN <small class="req"> *</small></label>
                                        <div class="form-group">
                                            <input type="text" name="muema" id="muema" onkeypress="memSort(event);" class="form-control" placeholder="Scan / Type ISBN">
                                         </div>
                                     </div>
                                    <div class="col-md-1">
                                        <label style="font-size: 17px;">Issuing</label><br>
                                        <input type="radio" id="issue" name="type">
                                    </div>
                                    <div class="col-md-1">
                                        <label style="font-size: 17px;">Returning</label><br>
                                        <input type="radio" id="return" name="type">
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="error" style="color: red; display: none; font-size: 20px;"></div>
                                     <div id="success" style="color: green; display: none; font-size: 20px;"></div>
                                </div>
                            </div>
                            
                            
                            <div class="waitpage" id="waitpage" style="display: none;">
                                <div class="waitpage-box">
                                    <p>
                                        <span>Please Wait...</span>
                                    </p>
                                </div>
                            </div>
                            
                            
                            
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
                                                <th width="5%">Book ID</th>
                                                <th width="15%">Book Title</th>
                                                <th width="10%">Stock Count</th>
                                                <th width="10%">
                                                    Available Units
                                                </th>
                                                <th width="10%" style="text-align:center">Return Date</th>
                                                <th style="text-align:right" width="5%">
                                                    User ID
                                                </th>
                                                <th style="text-align:right" width="5%">
                                                    Remove
                                                </th>
                                              </tr>
                                          </thead>
                                          <tbody id="tdata">
                                          </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" onclick="issueReturnSelectedBooks()" id="issueSelectedBooksBtn"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    <!-- old form closing point -->
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
    var issue_books_html_array = []; // array to hold scanned books list in HTML
    var issue_books_array = []; // array to hold scanned books plain list
    var all_data; // variable to hold single book info while adding to issue cart
    document.getElementById("muema").focus(); // place cursor in textinput for ISBN scanning
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        
        $('#confirm-return').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        
        listBooks();
    });

    function memSort(e) {
        var key = e.keyCode || e.which;
        if (key==13) {
            var book_isbn = document.getElementById('muema').value;
            fetch_book_function(book_isbn);
            document.getElementById("muema").value = "";
            document.getElementById("muema").focus();
        }
    }
    
    function fetch_book_function(book_isbn) {
        $.ajax({
            url: base_url + "admin/book/getBookById",
            method:"POST",
            data: {'book_isbn': book_isbn},
            success:function(data) {
                var values = data.split(',');
                document.getElementById("muema").focus();
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/book/getAvailQuantity",
                    data: {'book_id': values[0]},
                    dataType: "json",
                    success: function (qty) {
                        if(qty.qty <= 0) {
                            $('#error').show();
                            var data = "Book not available. Only return operation will be allowed";
                            document.getElementById("issue").disabled = true;
                            document.getElementById("issue").checked = false;
                            document.getElementById("return").checked = true;
                            $('#error').html(data);
                            setTimeout(function() {
                                $('#error').hide();
                            }, 5000);
                            var today = new Date();
                            var dd = String(today.getDate()).padStart(2, '0');
                            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = today.getFullYear();
                            today = mm + '/' + dd + '/' + yyyy;
                            addBookToDOM(values[0],values[1],values[2],qty.qty,issue_books_html_array.length,today,book_isbn);
                        }
                        else {
                            var today = new Date();
                            var dd = String(today.getDate()).padStart(2, '0');
                            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = today.getFullYear();
                            today = mm + '/' + dd + '/' + yyyy;
                            addBookToDOM(values[0],values[1],values[2],qty.qty,issue_books_html_array.length,today,book_isbn);
                        }
                    },
                    complete: function () {
                    }
                });
            }
        });
    }
    
    function addBookToDOM(book_id,book_title,stock_count,available_units,length,date,isbn)
    {
        var duedate = 'due'+length;
        all_data = "<tr><td>"+book_id+"</td><td>"+book_title+"</td><td align='left'>"+stock_count+"</td><td align='left'>"+available_units+"</td><td align='left'><input type='date' id='"+duedate+"' class='form-control' value='<?php echo date("Y-m-d"); ?>' min='<?php echo date("Y-m-d"); ?>' max='<?php date_default_timezone_set("Africa/Nairobi"); $date=date("Y-m-d"); echo date("Y-m-d", strtotime($date. " + 5 days")); ?>' /></td><td align='center'>" + <?php echo $memberList->lib_member_id; ?> + '</td><td align="center"><input type="checkbox" id="'+length+'" onclick="unlistFunction(this.id,'+book_id+')" />' + "</td></tr>";
        issue_books_html_array.push(all_data);
        document.getElementById("tdata").innerHTML = "";
        document.getElementById("muema").focus();
        listBooks();
        var data = book_id+","+date+","+<?php echo $memberList->lib_member_id; ?>+","+isbn;
        issue_books_array.push(data);
    }
    
    function listBooks() {
        if(issue_books_html_array.length > 0) {
            for (var i=0; i<issue_books_html_array.length; i++) {
                document.getElementById("tdata").innerHTML += issue_books_html_array[i];
            }
            document.getElementById("clear_cart").disabled = false;
            document.getElementById("issueSelectedBooksBtn").disabled = false;
        }
        else {
            document.getElementById("tdata").innerHTML = '<tr><td colspan="8" style="text-align:center; font-weight:bold;">No scanned books</td></tr>';
            document.getElementById("clear_cart").disabled = true;
            document.getElementById("issueSelectedBooksBtn").disabled = true;
        }
    }
    
    function unlistFunction(id,bid) {
        /* loop through all books in cart to remove checked book */
        if(id >= issue_books_html_array.length) {
            issue_books_html_array.splice((issue_books_html_array.length - id), 1);
        }
        else {
            issue_books_html_array.forEach(function (value, i) {
                const index = value.indexOf(bid);
                if(index > -1) {
                    issue_books_html_array.splice(id, 1);
                }
            });
        }
        document.getElementById("tdata").innerHTML = "";
        document.getElementById("muema").focus();
        listBooks();
    }
    
    function clearBookCart() {
        issue_books_html_array.splice(0,issue_books_html_array.length);
        document.getElementById("tdata").innerHTML = "";
        document.getElementById("muema").focus();
        listBooks();
    }
    
    function issueReturnSelectedBooks() {
        if(!document.getElementById('issue').checked && !document.getElementById('return').checked) {
            $('#error').show();
            $('#error').html("Please select issue or return");
            document.getElementById("muema").focus();
            setTimeout(function() {
                $('#error').hide();
            }, 4000);
            return;
        }
        if(document.getElementById('issue').checked == true) {
            /* Issue book */
            $('.waitpage').show();
            var dates = "";
            var new_str = "";
            $("#tdata input[type=date]").each(function() {
                dates = dates + "," + this.value;
            });
            dates = dates.split(',');
            for(var i=0; i<=issue_books_array.length; i++) {
                var str = issue_books_array[i].split(',');
                str[1] = dates[i+1];
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/member/issuebook",
                    data: {'book_id': str[0], 'return_date': str[1], 'member_id': str[2], 'book_isbn': str[3]},
                    success: function (data) {
                        if(data == "success") {
                            $('.waitpage').hide();
                            $('#success').show();
                            $('#success').html("Books issued sucsessfully");
                            document.getElementById("muema").focus();
                            setTimeout(function() {
                                $('#success').hide();
                            }, 4000);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                        else {
                            $('.waitpage').hide();
                            $('#error').show();
                            $('#error').html("Books issue failed");
                            document.getElementById("muema").focus();
                            setTimeout(function() {
                                $('#error').hide();
                            }, 4000);
                            return;
                        }
                    },
                    complete: function () {}
                });
            }
        }
        else if(document.getElementById('return').checked == true) {
            /* Return book */
            $('.waitpage').show();
            var dates = "";
            var new_str = "";
            $("#tdata input[type=date]").each(function() {
                dates = dates + "," + this.value;
            });
            dates = dates.split(',');
            for(var i=0; i<=issue_books_array.length; i++) {
                var str = issue_books_array[i].split(',');
                str[1] = dates[i+1];
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/member/returnbook",
                    data: {'book_id': str[0], 'return_date': str[1], 'member_id': str[2], 'book_isbn': str[3]},
                    success: function (data) {
                        if(data == "success") {
                            $('.waitpage').hide();
                            $('#success').show();
                            $('#success').html("Books returned sucsessfully");
                            document.getElementById("muema").focus();
                            setTimeout(function() {
                                $('#success').hide();
                            }, 4000);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                        else {
                            $('.waitpage').hide();
                            $('#error').show();
                            $('#error').html("Books return failed");
                            document.getElementById("muema").focus();
                            setTimeout(function() {
                                $('#error').hide();
                            }, 4000);
                            return;
                        }
                    },
                    complete: function () {}
                });
            }
        }
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