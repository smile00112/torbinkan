<?php echo $header; ?>
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
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_apply; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_close; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
              <tr>
                  <td><?php echo $entry_status; ?></td>
                  <td><select name="soforp_watermark_status">
                          <?php if ($soforp_watermark_status) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                      </select></td>
              </tr>
              <tr>
                  <td><?php echo $entry_debug; ?></td>
                  <td><select name="soforp_watermark_debug">
                          <?php if ($soforp_watermark_debug) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                      </select></td>
              </tr>
              <tr>
                  <td><?php echo $entry_image; ?></td>
                  <td><div class="image"><img src="<?php echo $soforp_watermark_image_thumb; ?>" alt="" id="thumb-soforp_watermark_image" />
                          <input type="hidden" name="soforp_watermark_image" value="<?php echo $soforp_watermark_image; ?>" id="soforp_watermark_image" />
                          <br />
                          <a onclick="image_upload('soforp_watermark_image', 'thumb-soforp_watermark_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-soforp_watermark_image').attr('src', '<?php echo $no_image; ?>'); $('#soforp_watermark_image').attr('value', '');"><?php echo $text_clear; ?></a></div>
                      <?php if (!empty($error_watermark['image'])) { ?>
                      <span class="error"><?php echo $error_watermark['image']; ?></span>
                      <?php } ?></td>
              </tr>
              <tr>
                  <td><?php echo $entry_position; ?></td>
                  <td>
                      <div>
                          <table>
                              <tr>
                                  <td><input type="radio" id="soforp_watermark_position_topleft" name="soforp_watermark_position" value="topleft"<?php if ($soforp_watermark_position == 'topleft') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_topleft"><?php echo $text_topleft; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_topcenter" name="soforp_watermark_position" value="topcenter"<?php if ($soforp_watermark_position == 'topcenter') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_topcenter"><?php echo $text_topcenter; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_topright" name="soforp_watermark_position" value="topright"<?php if ($soforp_watermark_position == 'topright') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_topright"><?php echo $text_topright; ?></label></td>
                              </tr>
                              <tr>
                                  <td><input type="radio" id="soforp_watermark_position_centerleft" name="soforp_watermark_position" value="centerleft"<?php if ($soforp_watermark_position == 'centerleft') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_centerleft"><?php echo $text_centerleft; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_middle" name="soforp_watermark_position" value="middle"<?php if ($soforp_watermark_position == 'middle') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_middle"><?php echo $text_middle; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_centerright" name="soforp_watermark_position" value="centerright"<?php if ($soforp_watermark_position == 'centerright') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_centerright"><?php echo $text_centerright; ?></label></td>
                              </tr>
                              <tr>
                                  <td><input type="radio" id="soforp_watermark_position_bottomleft" name="soforp_watermark_position" value="bottomleft"<?php if ($soforp_watermark_position == 'bottomleft') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_bottomleft"><?php echo $text_bottomleft; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_bottomcenter" name="soforp_watermark_position" value="bottomcenter"<?php if ($soforp_watermark_position == 'bottomcenter') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_bottomcenter"><?php echo $text_bottomcenter; ?></label></td>
                                  <td><input type="radio" id="soforp_watermark_position_bottomright" name="soforp_watermark_position" value="bottomright"<?php if ($soforp_watermark_position == 'bottomright') echo ' checked="checked"'; ?> /> <label for="soforp_watermark_position_bottomright"><?php echo $text_bottomright; ?></label></td>
                              </tr>
                              <tr>
                                  <td><input type="radio" id="soforp_watermark_position_manual" name="soforp_watermark_position" value="manual"<?php if ($soforp_watermark_position == 'manual') echo ' checked="checked"'; ?> /> <?php echo $text_manual; ?></td>
                                  <td><input type="text" name="soforp_watermark_offset_y" value="<?php echo $soforp_watermark_offset_y; ?>" size="2" /> <?php echo $text_percent_from_top; ?></td>
                                  <td><input type="text" name="soforp_watermark_offset_x" value="<?php echo $soforp_watermark_offset_x; ?>" size="2" /> <?php echo $text_percent_from_left; ?></td>
                              </tr>
                          </table>
                      </div>
                      <?php if (!empty($error_watermark['position'])) { ?>
                      <span class="error"><?php echo $error_watermark['position']; ?></span>
                      <?php } ?></td>
              </tr>
              <tr>
                  <td><?php echo $entry_size; ?></td>
                  <td><input type="text" name="soforp_watermark_size" value="<?php echo $soforp_watermark_size; ?>" size="3" /> <?php echo $text_percent_of_image; ?>
                      <?php if (!empty($error['size'])) { ?>
                      <span class="error"><?php echo $error['size']; ?></span>
                      <?php } ?></td>
              </tr>
              <tr>
                  <td><?php echo $entry_min_size; ?></td>
                  <td>
                      <table>
                          <tr>
                              <td><label for="soforp_watermark_min_width"><?php echo $text_min_width; ?></label></td>
                              <td><input type="text" name="soforp_watermark_min_width" value="<?php echo $soforp_watermark_min_width; ?>" size="3" /></td>
                          </tr>
                          <tr>
                              <td><label for="soforp_watermark_min_height"><?php echo $text_min_height; ?></label></td>
                              <td><input type="text" name="soforp_watermark_min_height" value="<?php echo $soforp_watermark_min_height; ?>" size="3" /></td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td><?php echo $entry_exclude; ?></td>
                  <td><div class="scrollbox">
                          <?php $class = 'odd'; ?>
                          <?php foreach ($image_directories as $item) { ?>
                          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div class="<?php echo $class; ?>">
                              <input type="checkbox" name="soforp_watermark_exclude[]" value="<?php echo $item['value']; ?>"<?php if (is_array($soforp_watermark_exclude) && in_array($item['value'], $soforp_watermark_exclude)) echo ' checked="checked"'; ?> />
                              <?php echo $item['text']; ?>
                          </div>
                          <?php } ?>
                      </div>
                      <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
              </tr>
          </table>
      </form>
    </div>
  </div>
</div>
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
            width: 950,
            height: 600,
            resizable: false,
            modal: false
        });
    };
    //--></script>

<?php echo $footer; ?>