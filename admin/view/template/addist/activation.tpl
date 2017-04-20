<?php echo $header; ?><?php echo $column_left; ?>
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
                    <a onclick="jQuery('#form').submit()" data-toggle="tooltip" title="<?php echo $addist_button_activate; ?>" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $addist_button_activate; ?></a>
                    <a href="<?php echo $purchase; ?>" data-toggle="tooltip" title="<?php echo $addist_button_purchase; ?>" class="btn btn-success" target="_blank" rel="nofollow"><i class="fa fa-shopping-cart"></i> <?php echo $addist_button_purchase; ?></a>
                    <a href="<?php echo $remove; ?>" onclick="return confirm('<?php echo $text_remove_confirm; ?>');" class="btn btn-danger"><i class="fa fa-trash"></i> <?php echo $text_remove; ?></a>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $addist_button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $addist_button_cancel; ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" id="form" onsubmit="return checkForm();" class="form-horizontal">
                    <div class="panel-heading"><h2 class="panel-title"><?php echo $addist_text_activation; ?></h2></div>
                    <div class="well">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-market_id"><?php echo $addist_entry_market; ?></label>
                            <div class="col-sm-4">
                                <select name="market_id" id="input-market_id" onchange="changeMarket()" class="form-control">
                                    <?php foreach ($markets as $id=>$market) { ?>
                                    <option value="<?php echo $id; ?>"<?php if ($id==$market_id){ ?> selected="true"<?php } ?>><?php echo $market['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-order_id"><?php echo $addist_entry_order_id; ?></label>
                            <div class="col-sm-4 input-group">
                                <input type="text" name="order_id" id="input-order_id" value="<?php echo $order_id; ?>" data-bind="value:replyNumber" class="form-control" />
                                <span class="input-group-btn">
                                    <a href="<?php echo $markets[1]['order_link']; ?>" id="order-link" class="btn btn-warning" target="_blank"><i class="fa fa-search"></i> <?php echo $text_get_order_id; ?></a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-email"><?php echo $addist_entry_email; ?></label>
                            <div class="col-sm-4">
                                <input type="text" name="email" id="input-email" value="<?php echo $email; ?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <?php if ($manual){ ?>
                    <div class="panel-heading"><h2 class="panel-title"><?php echo $addist_text_have_serial; ?></h2></div>
                    <div class="well">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-serial"><?php echo $addist_entry_serial; ?></label>
                            <div class="col-sm-4"><input type="text" name="<?php echo $code; ?>[extension][serial]" id="input-serial" value="<?php echo $serial; ?>" class="form-control" /></div>
                        </div>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function changeMarket()
{
    var markets = JSON.parse('<?php echo json_encode($markets); ?>');
    var market_id = jQuery('#input-market_id').val();
    jQuery('#order-link').attr('href',markets[market_id]['order_link']);
}
function checkForm()
{
    if (jQuery('#input-order_id').val() == '' && jQuery('#input-serial').val() == '')
    {
        alert('<?php echo $addist_error_form; ?>');
        return false;
    }
}
</script>
<?php echo $footer; ?>