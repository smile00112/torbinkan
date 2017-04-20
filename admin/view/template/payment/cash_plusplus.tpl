<?php echo $header; ?>
<style type="text/css">
#cke_comment{
max-width: 900px;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
    </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="languages" class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bank; ?></td>
          <td><textarea name="cash_plusplus_bank_<?php echo $language['language_id']; ?>" cols="50" rows="3"><?php echo isset(${'cash_plusplus_bank_' . $language['language_id']}) ? ${'cash_plusplus_bank_' . $language['language_id']} : ''; ?></textarea><br />
            <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bank_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_inn; ?></td>
          <td><input name="cash_plusplus_inn_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_inn_' . $language['language_id']}) ? ${'cash_plusplus_inn_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_inn_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_inn_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_rs; ?></td>
          <td><input name="cash_plusplus_rs_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_rs_' . $language['language_id']}) ? ${'cash_plusplus_rs_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_rs_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_rs_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bankuser; ?></td>
          <td><input name="cash_plusplus_bankuser_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_bankuser_' . $language['language_id']}) ? ${'cash_plusplus_bankuser_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_bankuser_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bankuser_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bik; ?></td>
          <td><input name="cash_plusplus_bik_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_bik_' . $language['language_id']}) ? ${'cash_plusplus_bik_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_bik_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bik_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_ks; ?></td>
          <td><input name="cash_plusplus_ks_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_ks_' . $language['language_id']}) ? ${'cash_plusplus_ks_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_ks_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_ks_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_kpp; ?></td>
          <td><input name="cash_plusplus_kpp_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_kpp_' . $language['language_id']}) ? ${'cash_plusplus_kpp_' . $language['language_id']} : ''; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_ur; ?></td>
          <td><input name="cash_plusplus_ur_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_ur_' . $language['language_id']}) ? ${'cash_plusplus_ur_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_ur_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_ur_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_tel; ?></td>
          <td><input name="cash_plusplus_tel_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'cash_plusplus_tel_' . $language['language_id']}) ? ${'cash_plusplus_tel_' . $language['language_id']} : ''; ?>" /><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_etext; ?></td>
          <td><textarea name="cash_plusplus_etext_<?php echo $language['language_id']; ?>" cols="50" rows="3"><?php echo isset(${'cash_plusplus_etext_' . $language['language_id']}) ? ${'cash_plusplus_etext_' . $language['language_id']} : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_comment; ?></td>
          <td><textarea name="cash_plusplus_comment_<?php echo $language['language_id']; ?>" id="comment" cols="50" rows="3"><?php echo isset(${'cash_plusplus_comment_' . $language['language_id']}) ? ${'cash_plusplus_comment_' . $language['language_id']} : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_bank_instruction; ?></td>
          <td><textarea name="cash_plusplus_instruction_<?php echo $language['language_id']; ?>" cols="50" rows="3"><?php echo isset(${'cash_plusplus_instruction_' . $language['language_id']}) ? ${'cash_plusplus_instruction_' . $language['language_id']} : ''; ?></textarea><br /></td>
        </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
          <td><?php echo $entry_instruction; ?></td>
          <td><?php if ($cash_plusplus_instruction_attach) { ?>
            <input type="radio" name="cash_plusplus_instruction_attach" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_instruction_attach" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="cash_plusplus_instruction_attach" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_instruction_attach" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_cash_plusplus_email_attach; ?></td>
          <td><?php if ($cash_plusplus_email_attach) { ?>
            <input type="radio" name="cash_plusplus_email_attach" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_email_attach" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="cash_plusplus_email_attach" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_email_attach" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_invoi; ?></td>
          <td><select name="cash_plusplus_invoi">

              <?php if ('invoi_zakazdc' == $cash_plusplus_invoi) { ?>
              <option value="invoi_zakazdc" selected="selected"><?php echo $entry_invoi_zakazdc; ?></option>
              <?php } else { ?>
              <option value="invoi_zakazdc"><?php echo $entry_invoi_zakazdc; ?></option>
              <?php } ?>

              <?php if ('invoi_zakazd' == $cash_plusplus_invoi) { ?>
              <option value="invoi_zakazd" selected="selected"><?php echo $entry_invoi_zakazd; ?></option>
              <?php } else { ?>
              <option value="invoi_zakazd"><?php echo $entry_invoi_zakazd; ?></option>
              <?php } ?>

              <?php if ('invoi_zakaz' == $cash_plusplus_invoi) { ?>
              <option value="invoi_zakaz" selected="selected"><?php echo $entry_invoi_zakaz; ?></option>
              <?php } else { ?>
              <option value="invoi_zakaz"><?php echo $entry_invoi_zakaz; ?></option>
              <?php } ?>
              <!-- <?php /*
              <?php if ('invoi_noinvoicedc' == $cash_plusplus_invoi) { ?>
              <option value="invoi_noinvoicedc" selected="selected"><?php echo $entry_invoi_noinvoicedc; ?></option>
              <?php } else { ?>
              <option value="invoi_noinvoicedc"><?php echo $entry_invoi_noinvoicedc; ?></option>
              <?php } ?>

              <?php if ('invoi_noinvoiced' == $cash_plusplus_invoi) { ?>
              <option value="invoi_noinvoiced" selected="selected"><?php echo $entry_invoi_noinvoiced; ?></option>
              <?php } else { ?>
              <option value="invoi_noinvoiced"><?php echo $entry_invoi_noinvoiced; ?></option>
              <?php } ?>

              <?php if ('invoi_noinvoice' == $cash_plusplus_invoi) { ?>
              <option value="invoi_noinvoice" selected="selected"><?php echo $entry_invoi_noinvoice; ?></option>
              <?php } else { ?>
              <option value="invoi_noinvoice"><?php echo $entry_invoi_noinvoice; ?></option>
              <?php } ?>
              */ ?> -->
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_chelpay; ?></td>
          <td><select name="cash_plusplus_chelpay">
              
              <?php if ('chelpay_fio' == $cash_plusplus_chelpay) { ?>
              <option value="chelpay_fio" selected="selected"><?php echo $entry_chelpay_fio; ?></option>
              <?php } else { ?>
              <option value="chelpay_fio"><?php echo $entry_chelpay_fio; ?></option>
              <?php } ?>

              <?php if ('chelpay_company' == $cash_plusplus_chelpay) { ?>
              <option value="chelpay_company" selected="selected"><?php echo $entry_chelpay_company; ?></option>
              <?php } else { ?>
              <option value="chelpay_company"><?php echo $entry_chelpay_company; ?></option>
              <?php } ?>

              <?php if ('chelpay_company_fio' == $cash_plusplus_chelpay) { ?>
              <option value="chelpay_company_fio" selected="selected"><?php echo $entry_chelpay_company_fio; ?></option>
              <?php } else { ?>
              <option value="chelpay_company_fio"><?php echo $entry_chelpay_company_fio; ?></option>
              <?php } ?>

              <?php if ('chelpay_fio_company' == $cash_plusplus_chelpay) { ?>
              <option value="chelpay_fio_company" selected="selected"><?php echo $entry_chelpay_fio_company; ?></option>
              <?php } else { ?>
              <option value="chelpay_fio_company"><?php echo $entry_chelpay_fio_company; ?></option>
              <?php } ?>

              <?php if ('chelpay_custom' == $cash_plusplus_chelpay) { ?>
              <option value="chelpay_custom" selected="selected"><?php echo $entry_chelpay_custom; ?></option>
              <?php } else { ?>
              <option value="chelpay_custom"><?php echo $entry_chelpay_custom; ?></option>
              <?php } ?>

            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_gruzpay; ?></td>
          <td><select name="cash_plusplus_gruzpay">
              
              <?php if ('gruzpay_fio' == $cash_plusplus_gruzpay) { ?>
              <option value="gruzpay_fio" selected="selected"><?php echo $entry_gruzpay_fio; ?></option>
              <?php } else { ?>
              <option value="gruzpay_fio"><?php echo $entry_gruzpay_fio; ?></option>
              <?php } ?>

              <?php if ('gruzpay_company' == $cash_plusplus_gruzpay) { ?>
              <option value="gruzpay_company" selected="selected"><?php echo $entry_gruzpay_company; ?></option>
              <?php } else { ?>
              <option value="gruzpay_company"><?php echo $entry_gruzpay_company; ?></option>
              <?php } ?>

              <?php if ('gruzpay_company_fio' == $cash_plusplus_gruzpay) { ?>
              <option value="gruzpay_company_fio" selected="selected"><?php echo $entry_gruzpay_company_fio; ?></option>
              <?php } else { ?>
              <option value="gruzpay_company_fio"><?php echo $entry_gruzpay_company_fio; ?></option>
              <?php } ?>

              <?php if ('gruzpay_fio_company' == $cash_plusplus_gruzpay) { ?>
              <option value="gruzpay_fio_company" selected="selected"><?php echo $entry_gruzpay_fio_company; ?></option>
              <?php } else { ?>
              <option value="gruzpay_fio_company"><?php echo $entry_gruzpay_fio_company; ?></option>
              <?php } ?>

              <?php if ('gruzpay_custom' == $cash_plusplus_gruzpay) { ?>
              <option value="gruzpay_custom" selected="selected"><?php echo $entry_gruzpay_custom; ?></option>
              <?php } else { ?>
              <option value="gruzpay_custom"><?php echo $entry_gruzpay_custom; ?></option>
              <?php } ?>

            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_custom; ?></td>
          <td>
          <p><?php echo $entry_custom_opis; ?></p>
          <p><?php echo $entry_custom_text; ?></p>
          <textarea name="cash_plusplus_custom" cols="80" rows="10"><?php echo isset($cash_plusplus_custom) ? $cash_plusplus_custom : ''; ?></textarea><br />
          <p><?php echo $entry_custom_text_2; ?></p>
          <textarea name="cash_plusplus_custom_2" cols="80" rows="10"><?php echo isset($cash_plusplus_custom_2) ? $cash_plusplus_custom_2 : ''; ?></textarea><br />
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_cash_plusplus_nds; ?></td>
          <td><?php if ($cash_plusplus_nds) { ?>
            <input type="radio" name="cash_plusplus_nds" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_nds" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="cash_plusplus_nds" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="cash_plusplus_nds" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_copey; ?></td>
          <td><?php if ($cash_plusplus_copey) { ?>
            <input type="checkbox" name="cash_plusplus_copey" checked value="1" />
            <?php } else { ?>
            <input type="checkbox" name="cash_plusplus_copey" value="1"  />
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_logo; ?></td>
          <td valign="middle">
      <input name="cash_plusplus_logo" type="text" size="27" value="<?php echo $cash_plusplus_logo; ?>" id="logo" />&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="image_upload('logo', 'thumb');"><?php echo $text_browse; ?></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_image; ?></td>
          <td valign="middle">
      <input name="cash_plusplus_image" type="text" size="27" value="<?php echo $cash_plusplus_image; ?>" id="image" />&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="cash_plusplus_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $cash_plusplus_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="cash_plusplus_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $cash_plusplus_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="cash_plusplus_status">
              <?php if ($cash_plusplus_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="cash_plusplus_sort_order" value="<?php echo $cash_plusplus_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</div>
<script type="text/javascript"><!--
$('#languages a').tabs();
//--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('comment', {
  filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
  $('#dialog').remove();
  
  $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
  
  $('#dialog').dialog({
    title: '<?php echo $text_image_manager; ?>',
    close: function (event, ui) {
      if ($('#' + field).attr('value')) {
        $.ajax({
          url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
          dataType: 'text',
          success: function(data) {
            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
          }
        });
      }
    },  
    bgiframe: false,
    width: 960,
    height: 550,
    resizable: false,
    modal: false,
    dialogClass: 'dlg'
  });
};
//--></script>
<?php echo $footer; ?>