<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
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
            <div class="col-md-5">
                <div class="box box-primary" style="padding: 1rem; padding-top: 0 !important;">
                    <h3 class="box-title"><?php echo 'Inventory Barcodes'; ?></h3>
                    <div id="success" style="display: none; padding-top: 1rem; padding-bottom: 1rem; text-align: center;"></div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="table">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-7">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo 'Create & Print Inventory Barcode'; ?></h3>
                        <div id="response" class="pull-right" style="display: none; padding-right: 10rem !important;"></div>
                    </div>

                    <div class="mt-5" style="background-color: #ffffff;">
                	    <div class="row">
                    		<form method="POST">
                    		    <div class="col-md-4" style="padding-left: 2rem;">
                    				<div class="form-group">
                    					<label>Characters To Encode</label>
                    					<input type="text" class="form-control" autofocus id="barcode" name="barcode" required style="border: 1px solid #C5C6D0; border-radius: 3px; border-bottom: 1px solid #C5C6D0 !important; padding-left: 1rem;" placeholder="e.g Book name, class, count"/>
                    				</div>
                    		    </div>
                    		    <div class="col-md-3">
                    				<div class="form-group">
                    					<label>Description</label>
                    					<input type="text" class="form-control" id="description" name="description" required style="border: 1px solid #C5C6D0; border-radius: 3px; border-bottom: 1px solid #C5C6D0 !important; padding-left: 1rem;" placeholder="Barcode meaning"/>
                    				</div>
                    		    </div>
                    		    <div class="col-md-3">
                    		        <label>Barcode QTY</label>
                    		        <input type="number" id="qty" name="qty" min=1 step=1 class="form-control" required style="border: 1px solid #C5C6D0; border-radius: 3px; border-bottom: 1px solid #C5C6D0 !important; padding-left: 1rem;" placeholder="Barcode number to print"/>
                    		    </div>
                    		    <div class="col-md-2">
                    		        <label>Generate</label><br />
                    		        <button class="btn btn-primary" name="generate" onclick="saveInventoryBarcode()">Generate</button>
                    		    </div>
                    		</form>
                		</div>
                		<div class="row" id="printArea">
                		    <?php
                		        $status = 0;
                		        if(isset($_POST['generate'])) {
                					$text = $_POST['barcode'];
                					$qty  = $_POST['qty'];
                					if ($qty > 0) {
                					   $status = 1;
                						for ($i=1; $i <= $qty; $i++) { ?>
                							<div class='col-md-4' style="padding-top: 3rem !important; margin-left: 0rem !important;">
                								<img alt='<?php echo $text; ?>' src='<?php echo "https://snippet.io.ke/barcode/barcode.php" ?>/?size=100&text=<?php echo $text; ?>&print=true'/>
                							</div><?php
                						}
                					}
                				}
            					else { ?>
            					    <center><h1>No Data</h1></center><?php
            					}
                            ?>
                		</div>
                		<?php
                		    if($status) { ?>
                    		    <div style="padding-top: 1rem !important; padding-bottom: 10rem !important; margin-left: 3rem !important;">
                                    <button type="button" id="btnPrint" onclick="PrintElem('printArea')" class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Print</button>
                                </div><?php
                		    }
                        ?>
                        <script>
                            function PrintElem(elem) {
                                const printContents = document.getElementById(elem).innerHTML;
                                const originalContents = document.body.innerHTML;
                                document.body.innerHTML = printContents;
                                window.print();
                                document.body.innerHTML = originalContents;
                            }
                            
                            function saveInventoryBarcode() {
                                var item_barcode = document.getElementById('barcode').value;
                                var barcode_description = document.getElementById('description').value;
                                $.ajax({
                                    type: "POST",
                                    url: base_url + "admin/isbn/saveInventoryBarcode",
                                    data: {'item_barcode': item_barcode, 'barcode_description': barcode_description},
                                    success: function (data) {
                                        if(data == "success") {
                                            $('#response').show();
                                            $('#response').css('color', 'green');
                                            $('#response').html("Success");
                                            document.getElementById("muema").focus();
                                            setTimeout(function() {
                                                $('#response').hide();
                                            }, 2500);
                                        }
                                        else {
                                            $('#response').show();
                                            $('#response').css('color', 'red');
                                            $('#response').html("Failed");
                                            setTimeout(function() {
                                                $('#response').hide();
                                            }, 2500);
                                            return;
                                        }
                                    },
                                    complete: function () {}
                                });
                            }
                        </script>
	                </div>
                </div>
            </div>
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

        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
        
        $("#barcode").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        
        $("#qty").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        
        $("#description").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        
        $("#btnPrint").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        
        $.ajax({
    		url: "<?php echo base_url().'/admin/book/getinventorybarcode';?>",
    		type: "POST",
    		cache: false,
    		success: function(data) {
    		    if(data == null || data == "") {
    		        $('#table').html("<tr><td colspan='3'><h5 style='text-align: center; padding-top: 2rem;'>No Data</h5></td></tr>");
    		    }
    			else {
    			    $('#table').html(data);
    			}
    		}
    	});
    	
    	$(document).on("click", ".delete_button", function() {
    		var $ele = $(this).parent().parent();
    		$.ajax({
    			url: "<?php echo base_url().'/admin/book/deleteisbn';?>",
    			type: "POST",
    			cache: false,
    			data:{id: $(this).attr("data-id")},
    			success: function(dataResult) {
    				var dataResult = JSON.parse(dataResult);
    				if(dataResult.statusCode==200) {
    					$('#success').show();
    					$('#success').css({'color':'green'});
                        $('#success').html("Book ISBN deleted sucsessfully");
                        setTimeout(function() {
                            $('#success').hide();
                        }, 2500);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
    				}
    				else {
    				    $('#success').show();
    				    $('#success').css({'color':'red'});
                        $('#success').html("Error! Try again later");
                        setTimeout(function() {
                            $('#success').hide();
                        }, 2500);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
    				}
    			}
    		});
    	});
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>