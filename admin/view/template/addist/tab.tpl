<div class="form-group">
    <label class="control-label col-sm-3" for="store-selector"><?php echo $addist_entry_store; ?></label>
    <div class="col-sm-9">
        <select id="store-selector" class="form-control">
            <?php foreach($stores as $store){ ?>
            <option value="<?php echo $store['store_id']; ?>"<?php if ($store['store_id'] == $store_id){ ?>selected="true"<?php } ?>><?php echo $store['name']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<?php if (!empty($serial)){ ?>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-serial"><?php echo $addist_entry_serial; ?></label>
    <div class="col-sm-9"><input type="text" id="input-serial" value="<?php echo $serial; ?>" class="form-control" readonly="true" /></div>
</div>
<?php } ?>
<?php if (!empty($expires)){ ?>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-expires"><?php echo $addist_entry_expires; ?></label>
    <div class="col-sm-9"><input type="text" id="input-expires" value="<?php echo $expires; ?>" class="form-control" readonly="true" /></div>
</div>
<?php } ?>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-autoupdate"><?php echo $addist_entry_update; ?></label>
    <div class="col-sm-9 input-group">
        <select name="<?php echo $code; ?>[extension][autoupdate]" id="input-autoupdate" class="form-control">
            <option value="1"<?php if ($autoupdate == 1) { ?> selected="selected"<?php } ?>><?php echo $addist_text_auto; ?></option>
            <option value="2"<?php if ($autoupdate == 2) { ?> selected="selected"<?php } ?>><?php echo $addist_text_auto_required; ?></option>
            <option value="3"<?php if ($autoupdate == 3) { ?> selected="selected"<?php } ?>><?php echo $addist_text_update_confirm; ?></option>
            <option value="0"<?php if ($autoupdate == 0) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
        </select>
        <span class="input-group-btn">
            <a href="<?php echo $updatecheck; ?>" class="btn btn-info"><i class="fa fa-refresh"></i> <?php echo $addist_text_update_check; ?></a>
        </span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-debug"><?php echo $addist_entry_debug; ?></label>
    <div class="col-sm-9">
        <select name="addist_installer[debug]" id="input-debug" class="form-control">
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1"<?php if ($debug == 1) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-reinstall"><?php echo $addist_entry_install; ?></label>
    <div class="col-sm-9 btn-group" role="group">
        <a href="<?php echo $reinstall; ?>" data-toggle="tooltip" title="<?php echo $addist_text_reinstall_h; ?>" class="btn btn-warning col-sm-3"><i class="fa fa-medkit"></i> <?php echo $addist_text_reinstall; ?></a>
        <a href="<?php echo $install_mods; ?>" data-toggle="tooltip" title="<?php echo $addist_text_install_mods_h; ?>" onclick="<?php if (OC_VERSION == '2.0.x'){ ?>return confirm('<?php echo $addist_text_mods_info; ?>');<?php } ?>" class="btn btn-success col-sm-5"><i class="fa fa-gears"></i> <?php echo $addist_text_install_mods; ?></a>
        <a href="<?php echo $remove; ?>" data-toggle="tooltip" title="<?php echo $addist_text_remove_h; ?>" onclick="return confirm('<?php echo $addist_text_remove_confirm; ?>');" class="btn btn-danger col-sm-4"><i class="fa fa-trash"></i> <?php echo $addist_text_remove; ?></a>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3" for="input-backup"><?php echo $addist_entry_backup; ?></label>
    <div class="col-sm-9 btn-group" role="group">
        <a href="<?php echo $backup; ?>" class="btn btn-default col-sm-6"><i class="fa fa-download"></i> <?php echo $addist_text_backup; ?></a>
        <a href="<?php echo $restore; ?>" onclick="jQuery('#config-file').click();return false;" class="btn btn-default col-sm-6"><i class="fa fa-upload"></i> <?php echo $addist_text_restore; ?></a>
        <input type="file" id="config-file" onchange="restoreConfig()" style="display: none;" />
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#store-selector').change(function(){
        if (jQuery(this).val() != '0')
        {
            window.location = '<?php echo html_entity_decode($action); ?>&store_id='+jQuery(this).val();
        }
        else
        {
            window.location = '<?php echo html_entity_decode($action); ?>';
        }
    });
    
    initMultiSelect('.multiselect','<?php echo $text_select_all; ?>','<?php echo $text_select; ?>');
});

function restoreConfig()
{
    if (jQuery('#config-file').val()!='')
    {
        var up_form = new FormData();
        var file = jQuery("#config-file")[0].files[0];
        up_form.append("file", file);
        
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "<?php echo html_entity_decode($restore); ?>");
        oReq.send(up_form);
        oReq.onreadystatechange = function()
        {
            if (oReq.readyState === 4)
            {
                window.location = '<?php echo html_entity_decode($action); ?>';
            }
        };
    }
}
</script>