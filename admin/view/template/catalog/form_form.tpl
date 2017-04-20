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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-items"><?php echo $tab_items; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
        <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input type="text" name="form_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($form_description[$language['language_id']]) ? $form_description[$language['language_id']]['name'] : ''; ?>" size="100" />
                <?php if (isset($error_name[$language['language_id']]) ) { ?>
                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_button; ?></td>
              <td><input type="text" name="form_description[<?php echo $language['language_id']; ?>][button]" value="<?php echo isset($form_description[$language['language_id']]) ? $form_description[$language['language_id']]['button'] : ''; ?>" size="100" />
                </td>
            </tr>
          </table>
          </div> 
          <?php } ?> 
          </div>
          <div id="tab-data">
          <table class="form">
			<tr>
				<td><?php echo $entry_prefix;?></td>
				<td><input type="text" name="prefix" value="<?php echo $prefix;?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_email;?></td>
				<td><input type="text" name="email" value="<?php echo $email;?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_use;?></td>
				<td><select name="use_type">
					<?php if ($use_type == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_fastorder;?></option>
                        <option value="2"><?php echo $entry_opdercart;?></option>
						<option value="0"><?php echo $text_none; ?></option>
					<?php } elseif ($use_type == 2) { ?>
						<option value="1"><?php echo $entry_fastorder;?></option>
                        <option selected="selected" value="2"><?php echo $entry_opdercart;?></option>
						<option value="0"><?php echo $text_none; ?></option>
					<?php } else { ?>
                        <option value="1"><?php echo $entry_fastorder;?></option>
                        <option value="2"><?php echo $entry_opdercart;?></option>
						<option selected="selected" value="0"><?php echo $text_none; ?></option>
                    <?php } ?>
				</select></td>
			</tr>
            <tr>
                <td><?php echo $entry_database; ?></td>
                <td><select name="databaseon">
					<?php if ($databaseon == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_on;?></option>
						<option value="0"><?php echo $entry_off;?></option>
					<?php } else { ?>
						<option value="1"><?php echo $entry_on;?></option>
						<option selected="selected" value="0"><?php echo $entry_off;?></option>
					<?php } ?>
				</select></td>
            </tr>
            <tr>
                <td><?php echo $entry_newsletter; ?></td>
                <td><select name="newsletteron">
					<?php if ($newsletteron == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_on;?></option>
						<option value="0"><?php echo $entry_off;?></option>
					<?php } else { ?>
						<option value="1"><?php echo $entry_on;?></option>
						<option selected="selected" value="0"><?php echo $entry_off;?></option>
					<?php } ?>
				</select></td>
            </tr>
            <tr>
                <td><?php echo $entry_useron; ?></td>
                <td><select name="useron">
					<?php if ($useron == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_on;?></option>
						<option value="0"><?php echo $entry_off;?></option>
					<?php } else { ?>
						<option value="1"><?php echo $entry_on;?></option>
						<option selected="selected" value="0"><?php echo $entry_off;?></option>
					<?php } ?>
				</select></td>
            </tr>
            <tr>
				<td><?php echo $entry_file;?></td>
				<td><select name="file">
					<?php if ($fileon == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_on;?></option>
						<option value="0"><?php echo $entry_off;?></option>
					<?php } else { ?>
						<option value="1"><?php echo $entry_on;?></option>
						<option selected="selected" value="0"><?php echo $entry_off;?></option>
					<?php } ?>
				</select></td>
			</tr>
			<tr>
				<td><?php echo $entry_status;?></td>
				<td><select name="status">
					<?php if ($status == 1) { ?>
						<option selected="selected" value="1"><?php echo $entry_on;?></option>
						<option value="0"><?php echo $entry_off;?></option>
					<?php } else { ?>
						<option value="1"><?php echo $entry_on;?></option>
						<option selected="selected" value="0"><?php echo $entry_off;?></option>
					<?php } ?>
				</select></td>
			</tr>
            
          </table>
          
          
        </div>
        <div id="tab-items">
            <div id="vtab-item" class="vtabs">
            <?php $items_row = 0;?>
            <?php foreach ($items as $item) { ?>
            <a href="#tab-item-<?php echo $items_row; ?>" id="item-<?php echo $items_row; ?>"><?php echo (!empty($item['description'][$this->config->get('config_language_id')]['label']) ? $item['description'][$this->config->get('config_language_id')]['label'] : $this->language->get('button_item').' '.$items_row); ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#item-<?php echo $items_row; ?>').remove(); $('#tab-item-<?php echo $items_row; ?>').remove(); return false;" /></a>
            <?php $items_row++; ?>
            <?php } ?>
            <span id="item-add">
            <input name="item_add" value="" style="width: 130px;" />
            &nbsp;<img src="view/image/add.png" alt="<?php echo $button_add_item; ?>" title="<?php echo $button_add_item; ?>" /></span>
            </div>
            <?php $items_row = 0;?>
            <?php foreach ($items as $item) { ?>
            <div id="tab-item-<?php echo $items_row; ?>" class="vtabs-content">
                <div id="is<?php echo $items_row;?>_languages" class="htabs">
                <?php foreach ($languages as $language) { ?>
                <a href="#item<?php echo $items_row;?>_language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <div id="item<?php echo $items_row;?>_language<?php echo $language['language_id']; ?>">
                  <table class="form">
                    <tr>
                      <td><span class="required">*</span> <?php echo $entry_item_name; ?></td>
                      <td><input type="text" name="items[<?php echo $items_row;?>][description][<?php echo $language['language_id']; ?>][label]" value="<?php echo isset($item['description'][$language['language_id']]) ? $item['description'][$language['language_id']]['label'] : ''; ?>" size="100" />
                        <?php if (isset($error_label[$items_row][$language['language_id']]) ) { ?>
                        <span class="error"><?php echo $error_label[$items_row][$language['language_id']]; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
        				<td><?php echo $entry_pattern; ?></td>
        				<td><input type="text" name="items[<?php echo $items_row;?>][description][<?php echo $language['language_id']; ?>][pattern]" value="<?php echo isset($item['description'][$language['language_id']]) ? $item['description'][$language['language_id']]['pattern'] : ''; ?>" size="100" /></td>
        			</tr>
                    <tr>
        				<td><?php echo $entry_value; ?></td>
        				<td>
        					<textarea cols="100" rows="6" name="items[<?php echo $items_row;?>][description][<?php echo $language['language_id']; ?>][value]"><?php echo isset($item['description'][$language['language_id']]) ? $item['description'][$language['language_id']]['value'] : ''; ?></textarea>
        				</td>
        			</tr>
                  </table>
                </div>
                <?php } ?>
                <div>
                    <table class="form">
                        <input type="hidden" name="items[<?php echo $items_row;?>][item_id]" value="<?php echo $item['item_id']; ?>" />
                        <tr>
                          <td><?php echo $entry_item_type; ?></td>
                          <td><select name="items[<?php echo $items_row;?>][item_type]">
                              <option <?php if ($item['item_type'] == 'input') {echo 'selected="selected"'; } ?> value="input"><?php echo $entry_item_type1; ?></option>
                              <option <?php if ($item['item_type'] == 'textarea') {echo 'selected="selected"'; } ?> value="textarea"><?php echo $entry_item_type2; ?></option>
                              <option <?php if ($item['item_type'] == 'radio') {echo 'selected="selected"'; } ?> value="radio"><?php echo $entry_item_type3; ?></option>
                              <option <?php if ($item['item_type'] == 'dropdown') {echo 'selected="selected"'; } ?> value="dropdown"><?php echo $entry_item_type4; ?></option>
                              <option <?php if ($item['item_type'] == 'checkbox') {echo 'selected="selected"'; } ?> value="checkbox"><?php echo $entry_item_type5; ?></option>
                              <option <?php if ($item['item_type'] == 'multiselect') {echo 'selected="selected"'; } ?> value="multiselect"><?php echo $entry_item_type6; ?></option>
                              <option <?php if ($item['item_type'] == 'file') {echo 'selected="selected"'; } ?> value="file"><?php echo $entry_item_type7; ?></option>
                              <option <?php if ($item['item_type'] == 'html') {echo 'selected="selected"'; } ?> value="html"><?php echo $entry_item_type8; ?></option>
                              <option <?php if ($item['item_type'] == 'date') {echo 'selected="selected"'; } ?> value="date"><?php echo $entry_item_type10; ?></option>
                              <option <?php if ($item['item_type'] == 'time') {echo 'selected="selected"'; } ?> value="time"><?php echo $entry_item_type11; ?></option>
                              <option <?php if ($item['item_type'] == 'datetime') {echo 'selected="selected"'; } ?> value="datetime"><?php echo $entry_item_type12; ?></option>
                              <option <?php if ($item['item_type'] == 'capcha') {echo 'selected="selected"'; } ?> value="capcha"><?php echo $entry_item_type9; ?></option>
                              
                            </select></td>
                        </tr>
                        
                        <tr>
                          <td><?php echo $entry_sort; ?></td>
                          <td><input type="text" name="items[<?php echo $items_row;?>][sort_order]" value="<?php echo $item['sort_order']; ?>" size="1" /></td>
                        </tr>
                        
                        <tr>
                          <td><?php echo $entry_required; ?></td>
                          <td><select name="items[<?php echo $items_row;?>][required]">
                              <?php if ($item['required']) { ?>
                              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                              <option value="0"><?php echo $text_disabled; ?></option>
                              <?php } else { ?>
                              <option value="1"><?php echo $text_enabled; ?></option>
                              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                              <?php } ?>
                            </select></td>
                        </tr>
                        
                        <tr>
                          <td><?php echo $entry_validation; ?></td>
                          <td><select name="items[<?php echo $items_row;?>][validation]">
                              <option value="0"><?php echo $text_none; ?></option>
                              <option <?php if ($item['validation'] == 'email') {echo 'selected="selected"'; } ?> value="email"><?php echo $entry_validation1; ?></option>
                              <option <?php if ($item['validation'] == 'file') {echo 'selected="selected"'; } ?> value="file"><?php echo $entry_validation2; ?></option>
                              <option <?php if ($item['validation'] == 're') {echo 'selected="selected"'; } ?> value="re"><?php echo $entry_validation3; ?></option>
                              <option <?php if ($item['validation'] == 'int') {echo 'selected="selected"'; } ?> value="int"><?php echo $entry_validation4; ?></option>
                              
                              
                            </select></td>
                        </tr>
                        <tr>
            				<td><?php echo $entry_setsender;?></td>
            				<td><select name="items[<?php echo $items_row;?>][setsender]">
            					<?php if ($item['setsender'] == 1) { ?>
            						<option selected="selected" value="1"><?php echo $text_enabled;?></option>
            						<option value="0"><?php echo $text_disabled;?></option>
            					<?php } else { ?>
            						<option value="1"><?php echo $text_enabled;?></option>
            						<option selected="selected" value="0"><?php echo $text_disabled;?></option>
            					<?php } ?>
            				</select></td>
            			</tr>
                        <tr class="email-on">
            				<td><?php echo $entry_setfrom;?></td>
            				<td><select name="items[<?php echo $items_row;?>][setfrom]">
            					<?php if ($item['setfrom'] == 1) { ?>
            						<option selected="selected" value="1"><?php echo $text_enabled;?></option>
            						<option value="0"><?php echo $text_disabled;?></option>
            					<?php } else { ?>
            						<option value="1"><?php echo $text_enabled;?></option>
            						<option selected="selected" value="0"><?php echo $text_disabled;?></option>
            					<?php } ?>
            				</select></td>
            			</tr>
                        <tr class="letter-on" <?php echo ($item['setfrom'] == 1 ? '' : 'style="display: none;"'); ?>>
                            <td><?php echo $entry_letter; ?></td>
                            <td><select name="items[<?php echo $items_row;?>][letter]">
            					<?php if ($item['letter'] == 1) { ?>
            						<option selected="selected" value="1"><?php echo $text_enabled;?></option>
            						<option value="0"><?php echo $text_disabled;?></option>
            					<?php } else { ?>
            						<option value="1"><?php echo $text_enabled;?></option>
            						<option selected="selected" value="0"><?php echo $text_disabled;?></option>
            					<?php } ?>
            				</select></td>
                        </tr>           
                        <tr>
                          <td><?php echo $entry_status; ?></td>
                          <td><select name="items[<?php echo $items_row;?>][status]">
                              <?php if ($item['status']) { ?>
                              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                              <option value="0"><?php echo $text_disabled; ?></option>
                              <?php } else { ?>
                              <option value="1"><?php echo $text_enabled; ?></option>
                              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                              <?php } ?>
                            </select></td>
                        </tr>
                      </table>
                </div>
            </div>
            <?php $items_row++; ?>
            <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.email-on select').live('change', function(){
    var email_on = $(this).find(':selected').val();
    if(email_on == 0) {
        $(this).parents('.email-on').next('.letter-on').hide();
        
    } else {
        $(this).parents('.email-on').next('.letter-on').show();
        
    }
    
});
//--></script>
<script type="text/javascript"><!--
var items_row = <?php echo $items_row; ?>;
var cl_id = <?php echo $this->config->get('config_language_id'); ?>;
$('#item-add img').live('click', function() {
    var item_label = $('#item-add input').val();
    if(item_label.length != 0) {
        $('#item-add input').val('');
        html = '<div id="tab-item-'+items_row+'" class="vtabs-content" style="display: block;">';	
        html += '<div id="is'+items_row+'_languages" class="htabs">';
        <?php foreach ($languages as $language) { ?>
        html += '<a href="#item'+items_row+'_language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
        <?php } ?>
        html += '</div>';
        var l_id = '';
        var value = '';
        <?php foreach ($languages as $language) { ?>
        l_id = <?php echo $language['language_id'];?>;
        if (l_id == cl_id) {
            value = item_label;
        } else {
            value = '';
        }
        html += '<div id="item'+items_row+'_language<?php echo $language['language_id']; ?>">';
        html += '<table class="form">';
        html += '<tbody>';
        html += '<tr>';
        html += '<td>';
        html += '<span class="required">*</span> <?php echo $entry_item_name; ?>';
        html += '</td>';
        html += '<td>';
        html += '<input type="text" size="100" value="'+value+'" name="items['+items_row+'][description][<?php echo $language['language_id']; ?>][label]">';
        html += '</td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td>';
        html += '<?php echo $entry_pattern; ?>';
        html += '</td>';
        html += '<td>';
        html += '<input type="text" size="100" value="" name="items['+items_row+'][description][<?php echo $language['language_id']; ?>][pattern]">';
        html += '</td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td>';
        html += '<?php echo $entry_value; ?>';
        html += '</td>';
        html += '<td>';
        html += '<textarea name="items['+items_row+'][description][<?php echo $language['language_id']; ?>][value]" rows="6" cols="100"></textarea>';
        html += '</td>';
        html += '</tr>';
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
        <?php } ?>
        html += '<div>';
        html += '<table class="form">';
        html += '<tbody>';
        html += '<tr>';
        html += '<td><?php echo $entry_item_type; ?></td>';
        html += '<td><select name="items['+items_row+'][item_type]">';
        html += '<option value="input"><?php echo $entry_item_type1; ?></option>';
        html += '<option value="textarea"><?php echo $entry_item_type2; ?></option>';
        html += '<option value="radio"><?php echo $entry_item_type3; ?></option>';
        html += '<option value="dropdown"><?php echo $entry_item_type4; ?></option>';
        html += '<option value="checkbox"><?php echo $entry_item_type5; ?></option>';
        html += '<option value="multiselect"><?php echo $entry_item_type6; ?></option>';
        html += '<option value="file"><?php echo $entry_item_type7; ?></option>';
        html += '<option value="html"><?php echo $entry_item_type8; ?></option>';
        html += '<option value="date"><?php echo $entry_item_type10; ?></option>';
        html += '<option value="time"><?php echo $entry_item_type11; ?></option>';
        html += '<option value="datetime"><?php echo $entry_item_type12; ?></option>';
        html += '<option value="capcha"><?php echo $entry_item_type9; ?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_sort; ?></td>';
        html += '<td><input type="text" name="items['+items_row+'][sort_order]" value="'+items_row+'" size="1" /></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_required; ?></td>';
        html += '<td><select name="items['+items_row+'][required]">';
        html += '<option value="1"><?php echo $text_enabled; ?></option>';
        html += '<option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_validation; ?></td>';
        html += '<td><select name="items['+items_row+'][validation]">';
        html += '<option value="0"><?php echo $text_none; ?></option>';
        html += '<option value="email"><?php echo $entry_validation1; ?></option>';
        html += '<option value="file"><?php echo $entry_validation2; ?></option>';
        html += '<option value="re"><?php echo $entry_validation3; ?></option>';
        html += '<option value="int"><?php echo $entry_validation4; ?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_setsender;?></td>';
        html += '<td><select name="items['+items_row+'][setsender]">';
        html += '<option value="1"><?php echo $text_enabled;?></option>';
        html += '<option selected="selected" value="0"><?php echo $text_disabled;?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr class="email-on">';
        html += '<td><?php echo $entry_setfrom;?></td>';
        html += '<td><select name="items['+items_row+'][setfrom]">';
        html += '<option value="1"><?php echo $text_enabled;?></option>';
        html += '<option selected="selected" value="0"><?php echo $text_disabled;?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr class="letter-on" style="display: none;">';
        html += '<td><?php echo $entry_letter;?></td>';
        html += '<td><select name="items['+items_row+'][letter]">';
        html += '<option value="1"><?php echo $text_enabled;?></option>';
        html += '<option selected="selected" value="0"><?php echo $text_disabled;?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_status; ?></td>';
        html += '<td><select name="items['+items_row+'][status]">';
        html += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '<option value="0"><?php echo $text_disabled; ?></option>';
        html += '</select></td>';
        html += '</tr>';
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
        html += '</div>';
		
		
		$('#tab-items').append(html);
        
        
        $('#item-add').before('<a href="#tab-item-' + items_row + '" id="item-' + items_row + '">' + item_label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-item a:first\').trigger(\'click\'); $(\'#item-' + items_row + '\').remove(); $(\'#tab-item-' + items_row + '\').remove(); return false;" /></a>');
		
		$('#vtab-item a').tabs();
        $('#is'+items_row+'_languages a').tabs(); 
		
		$('#item-' + items_row).trigger('click');	
        
        items_row++;	
    } 
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#vtab-item a').tabs();
$('#languages a').tabs(); 
<?php $items_row = 0;?>
<?php foreach ($items as $item) { ?>
$('#is<?php echo $items_row;?>_languages a').tabs(); 
<?php $items_row++; ?>
<?php } ?>
//--></script> 
<?php echo $footer; ?>