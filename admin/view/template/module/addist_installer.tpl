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
                    <a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $text_refresh; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $text_refresh; ?></a>
                    <a href="<?php echo $logout; ?>" data-toggle="tooltip" title="<?php echo $text_logout; ?>" class="btn btn-warning" onclick="return confirm('<?php echo $text_confirm_logout; ?>');"><i class="fa fa-sign-out"></i> <?php echo $text_logout; ?></a>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="panel-body">
                <span class="help"><?php echo $help_main; ?></span>
                <hr />
                <form action="<?php echo $action; ?>" method="post" id="form" class="form-horizontal">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td class="text-left"><?php echo $text_name; ?></td>
                                    <td class="text-left"><?php echo $text_price; ?></td>
                                    <td class="text-left"><?php echo $text_version; ?></td>
                                    <td class="text-left"><?php echo $text_release; ?></td>
                                    <td class="text-left"><?php echo $text_status; ?></td>
                                    <td class="text-left"><?php echo $text_license; ?></td>
                                    <td class="text-left"><?php echo $text_expiry; ?></td>
                                    <td class="text-center" style="width: 240px;"><?php echo $text_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($extensions) { ?>
                                <?php foreach ($extensions as $extension) { ?>
                                <tr>
                                    <td class="text-left"><?php echo $extension['name']; ?></td>
                                    <td class="text-left"><?php echo $extension['price']; ?></td>
                                    <td class="text-left"<?php if ($extension['hasupdate']){ ?> style="color: #f00;"<?php } ?>><?php if ($extension['hasupdate']){ ?><span data-toggle="tooltip" title="<?php echo $text_version_old; ?>"><?php echo $extension['version']; ?></span><?php } else { ?><?php echo $extension['version']; ?><?php } ?></td>
                                    <td class="text-left"><?php echo $extension['release']; ?></td>
                                    <td class="text-left"><?php echo $extension['installed'] ? ($extension['installed'] == 2 ? $text_installed : $text_downloaded) : '-'; ?></td>
                                    <td class="text-left"><?php echo $extension['license'] ? $text_yes : '-'; ?></td>
                                    <td class="text-left"<?php if (!$extension['license']){ ?> style="color: #f00;"<?php } ?>><?php echo $extension['date_expiry']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo $extension['view']; ?>" data-toggle="tooltip" title="<?php echo $text_more; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                                            <a href="<?php echo $extension['purchase']; ?>" data-toggle="tooltip" title="<?php echo $text_purchase; ?>" class="btn btn-success" target="_blank"><i class="fa fa-shopping-cart"></i></a>
                                            <a data-toggle="tooltip" title="<?php echo $text_download; ?>" class="btn btn-warning btn-download" data-extension-id="<?php echo $extension['extension_id']; ?>"><i class="fa fa-<?php if ($extension['installed']){ ?>refresh<?php } else { ?>download<?php } ?>"></i></a>
                                            <a href="<?php echo $extension['remove']; ?>" onclick="return confirm('<?php echo $text_confirm_remove; ?>');" data-toggle="tooltip" title="<?php echo $text_remove; ?>" class="btn btn-danger<?php if (!$extension['installed']){ ?> disabled<?php } ?>"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-container"></div>
</div>
<script type="text/javascript">
jQuery('.btn-download').click(function(){
    jQuery('#modal-container').load('index.php?route=module/addist_installer/get_modal&token=<?php echo $token; ?>','extension_id='+jQuery(this).attr('data-extension-id'),function(){
        jQuery('#download-modal').modal({
            show: true,
        });
    });
});
</script>
<?php echo $footer; ?>