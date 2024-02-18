<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-gears"></i> <small class="pull-right">
                <a type="button" class="btn btn-primary btn-sm"><?php echo $this->lang->line('setting') ?></a>
            </small>
        </h1>
    </section>   
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('setting') ?></h3>
                    </div>   
                    <form id="form1" action="<?php echo site_url('admin/gmeet') ?>"   name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">
                        <div class="box-body">
                                    <?php
                                     if (!$this->auth->addonchk('ssglc',false)) {
                                                ?>
                                               <div class="alert alert-danger">
 <a href="#" class="displayinline align-text-top" data-addon-version="<?php echo $version;?>" data-addon="ssglc" data-toggle="modal" data-target="#addonModal"></a> 
</div>
                                                <?php
                                            }
                                            ?> 

                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>  
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"> <?php echo $this->lang->line('api_key');?>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" name="api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('api_key', $setting->api_key); ?>" />
                                    <span class="text-danger"><?php echo form_error('api_key'); ?></span>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"> <?php echo $this->lang->line('api_secret')?>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" name="api_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('api_secret', $setting->api_secret); ?>" />
                                    <span class="text-danger"><?php echo form_error('api_secret'); ?></span>
                                </div>
                            </div>
                                   <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"><?php echo $this->lang->line('use_google_api') ?><small class="req"> *</small>
                                </label>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="radio-inline">
                                                    <input type="radio" name="use_api" value="0" <?php
                                                    if (!$setting->use_api) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="use_api" value="1" <?php
                                                    if ($setting->use_api) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>

                                    <span class="text-danger"><?php echo form_error('use_api'); ?></span>
                                </div>
                            </div> 
                         
                        </div>
                            <div class="box-footer">
                            <div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3">
                                <?php
                                if ($this->rbac->hasPrivilege('gmeet_setting', 'can_edit')) {
                                    ?>
                                    <button type="submit" class="btn btn-info pull-left"><?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>                           
                            </div>
                            <div class="pull-right"><?php echo $this->lang->line('version') . " " . $version; ?></div>
                        </div>
                    </form>
                </div>
            </div>           
        </div>
    </section>
</div>