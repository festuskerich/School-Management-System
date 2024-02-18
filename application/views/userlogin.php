<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title>Login : <?php echo $name; ?></title>
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/jquery.mCustomScrollbar.min.css">
        <style type="text/css">
            body{background:linear-gradient(to right,#676767 0,#dadada 100%);}
            /*.loginbg {background: #455a64;}*/
            .top-content{position: relative;}
            .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
                background: rgb(53, 170, 71);}
            .bgoffsetbgno{background: transparent; border-right:0 !important; box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.29); border-radius: 4px;}

            .loginradius{border-radius: 4px;}

        </style>
    </head>

    <body>
   
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mCustomScrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mousewheel.min.js"></script>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
            $(this).removeClass('input-error');
        });
        $('.login-form').on('submit', function (e) {
            $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
                if ($(this).val() == "") {
                    e.preventDefault();
                    $(this).addClass('input-error');
                } else {
                    $(this).removeClass('input-error');
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function refreshCaptcha(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/refreshCaptcha'); ?>",
            data: {},
            success: function(captcha){
                $("#captcha_image").html(captcha);
            }
        });
    }
</script>





































<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V15</title>
	<meta charset="UTF-8">
	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/bootstrap/css/bootstrap.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/"fonts/font-awesome-4.7.0/css/font-awesome.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/animate/animate.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/css-hamburgers/hamburgers.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/animsition/css/animsition.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/select2/select2.min.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/vendor/daterangepicker/daterangepicker.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/"css/util.css">	
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/theme/css/main.css">	
	
	
	
	
	
	
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->

<!--===============================================================================================-->

<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href=">
	<link rel="stylesheet" type="text/css" href="">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(<?php echo base_url(); ?>backend/theme/images/msingi.jpeg);">
					<span class="login100-form-title-1">
						Sign In To<br> <?php echo $name; ?>
					</span>
				</div>

			  <!--<img src="<?php echo base_url(); ?>uploads/school_content/admin_logo/<?php $this->setting_model->getAdminlogo();?>" />-->
                      
                                    <div class="form-bottom">
                                        <h3 class="font-white"><?php echo $this->lang->line('user_login'); ?></h3>
                                        <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                                        <?php
if ($this->session->flashdata('message')) {
    echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>";
}
;
?>
                                        <form action="<?php echo site_url('site/userlogin') ?>" method="post">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-username">
                                                    <?php echo $this->lang->line('username'); ?></label>
                                                <input type="text" name="username" value="<?php echo set_value("username"); ?>" placeholder="<?php echo $this->lang->line('username'); ?>" class="form-username form-control" id="email">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                                            </div>
                                            <div class="form-group has-feedback">
                                                <input type="password" name="password" value="<?php echo set_value("password"); ?>" placeholder="<?php echo $this->lang->line('password'); ?>" class="form-password form-control" id="password">
                                                <span class="fa fa-lock form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                                            </div>
                                            <?php if ($is_captcha) {?>
                                            <div class="form-group has-feedback row">
                                                <div class='col-lg-6 col-md-12 col-sm-6'>
                                                    <span id="captcha_image"><?php echo $captcha_image; ?></span>
                                                    <span class="fa fa-refresh catpcha" title='Refresh Catpcha' onclick="refreshCaptcha()"></span>
                                                </div>
                                                <div class='col-lg-6 col-md-12 col-sm-6'>
                                                    <input type="text" name="captcha" placeholder="<?php echo $this->lang->line('captcha'); ?>" autocomplete="off" class=" form-control" id="captcha">
                                                    <span class="text-danger"><?php echo form_error('captcha'); ?></span>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <button type="submit" class="btn">
                                                <?php echo $this->lang->line('sign_in'); ?></button>
                                        </form>

                                        <p><a href="<?php echo site_url('site/ufpassword') ?>" class="forgot"> <i class="fa fa-key"></i> <?php echo $this->lang->line('forgot_password'); ?></a> </p>
                               
                            </div>
                         

                                           



                        </div>
                    </div>
                </div>
            </div>
        </div>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>