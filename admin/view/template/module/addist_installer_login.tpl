<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="container-fluid">
        <div id="message"<?php if (!$success && !$error){ ?> style="display: none;"<?php } ?>>
            <?php foreach($success as $item) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $item; ?> <button type="button" class="close" data-dismiss="alert">×</button></div>
            <?php } ?>
            <?php foreach($error as $item) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $item; ?> <button type="button" class="close" data-dismiss="alert">×</button></div>
            <?php } ?>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
                <div class="pull-right btn-group">
                    <button form="form-login" data-toggle="tooltip" title="<?php echo $text_login; ?>" class="btn btn-primary"><i class="fa fa-sign-in"></i> <?php echo $text_login; ?></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="panel-body">
                <span class="help"><?php echo $help_login; ?></span>
                <hr />
                <form action="<?php echo $action; ?>" method="post" id="form-login" onsubmit="return checkLogin()" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                        <div class="col-sm-4"><input type="text" name="addist_installer[customer]" id="input-customer" value="<?php echo $customer; ?>" class="form-control" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <div class="col-sm-4"><input type="text" name="addist_installer[email]" id="input-email" value="<?php echo $email; ?>" class="form-control" /></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="password" name="addist_installer[password]" id="input-password" value="<?php echo $password; ?>" class="form-control" />
                                <span class="input-group-btn"><a id="button-send" data-toggle="tooltip" title="<?php echo $text_send; ?>" data-loading-text="..."  class="btn btn-warning" onclick="sendPassword()"><i class="fa fa-send"></i> <?php echo $text_send; ?></a></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
function checkLogin()
{
    if (jQuery('#input-customer').val() == '' || jQuery('#input-email').val() == '' || jQuery('#input-password').val() == '')
    {
        alert('<?php echo $error_fields; ?>');
        return false;
    }
    
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(jQuery('#input-email').val()))
    {
        alert('<?php echo $error_fields; ?>');
        return false;
    }
}

function sendPassword()
{
    if (jQuery('#input-customer').val() == '' || jQuery('#input-email').val() == '')
    {
        alert('<?php echo $error_fields; ?>');
    }
    else
    {
    	$.ajax({
    		url: 'index.php?route=module/addist_installer/send_pass&token=<?php echo $token; ?>',
    		type: 'POST',
            data: jQuery('#form-login').serialize(),
            dataType: 'json',
            beforeSend: function() {
            	jQuery('#button-send').button('loading');
    		},      
            complete: function() {
    			jQuery('#button-send').button('reset');
            }, 
    		success: function(json) {
                if (json !== null)
    			{
                    alert(json.message);
    			}
                else
                {
                    alert('<?php echo $error_connection; ?>');
                }
    		},
    		error: function(e) {
                alert('<?php echo $error_connection; ?>');
    		},
    	});
    }
}
//--></script>
<?php echo $footer; ?>