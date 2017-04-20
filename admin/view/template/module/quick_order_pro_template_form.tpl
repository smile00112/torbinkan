<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?>
        <?php if ($breadcrumb['href']) { ?>
        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } else { ?>
        <span><?php echo $breadcrumb['text']; ?></span>
        <?php } ?>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<?php if (isset($attention)) { ?>
	<div class="attention"><?php echo $attention; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a onclick="$('#form input[name=apply]').val(1); $('#form').submit();" class="button"><?php echo $button_apply; ?></a>
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            	<div id="tabs" class="htabs">
                    <a href="#tab-general"><?php echo $tab_general; ?></a>
					<?php if (!$new) { ?>
					<a href="#tab-fields"><?php echo $tab_fields; ?></a>
					<?php } ?>
                    <a href="#tab-additional"><?php echo $tab_additional; ?></a>
                </div>
                <div id="tab-general">
                    <table class="form">
                        <tbody>
                            <tr>
                            	<td colspan="2"><span class="help id">ID:</span> template_<?php echo $template_id; ?></td>
                            </tr>
                            <tr>
                                <td><label for="template-machine-name"><?php echo $entry_template_title; ?></label></td>
                                <td>
                                    <input class="machine-name" id="template-machine-name" type="text" name="template[machine_name]" value="<?php echo $machine_name; ?>" maxlength="64" size="50" />
                                    <p class="help"><?php echo $text_help_machine_name; ?> <b><?php echo $text_template . ' ' . $template_id; ?></b></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="language" class="htabs">
                        <?php foreach ($languages as $language) { ?>
                        <a href="#tab-language-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                        <?php } ?>
                    </div>
                    <?php foreach ($languages as $language) { ?>
                    <div id="tab-language-<?php echo $language['language_id']; ?>">
                        <table class="form">
                            <tbody>
                                <tr>
                                    <td><label for="template-title-<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label></td>
                                    <td><input id="template-title-<?php echo $language['language_id']; ?>" type="text" name="template[title][<?php echo $language['language_id']; ?>]" value="<?php if(isset($title[$language['language_id']])) echo $title[$language['language_id']]; ?>" /></td>
                                </tr>
                                <tr>
                                    <td class="description">
                                        <label for="template-description-<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label><br /><a class="js show-hide-editor help"><?php echo (isset($editor[$language['language_id']]) && $editor[$language['language_id']]) ? $text_disable_editor : $text_enabled_editor; ?></a>
                                        </td>
                                    <td>
                                        <textarea class="<?php echo (isset($editor[$language['language_id']]) && $editor[$language['language_id']]) ? 'enabled' : 'disable'; ?>" id="template-description-<?php echo $language['language_id']; ?>" rows="19" cols="120" name="template[description][<?php echo $language['language_id']; ?>]" ><?php if (isset($description[$language['language_id']])) echo $description[$language['language_id']]; ?></textarea><input type="hidden" name="template[editor][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($editor[$language['language_id']]) ? $editor[$language['language_id']] : 0); ?>" />
                                    </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
				<?php if (!$new) { ?>
				<div id="tab-fields">
					<table class="list">
						<thead>
							<tr>
								<td class="left" colspan="2" ><?php echo $column_field_title; ?></td>
								<td class="left"><?php echo $column_field_id; ?></td>
								<td class="left"><?php echo $column_field_type; ?></td>
								<td class="left"><?php echo $column_required; ?></td>
								<td class="right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($fields) { ?>
							<?php foreach ($fields as $field) { ?>
							<tr id="row-<?php echo $field['field_id']; ?>">
								<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
								<td class="left"><?php echo $field['title']; ?></td>
								<td class="left">
									<?php echo $field['field_id']; ?>
									<input type="hidden" class="order" name="template[fields][<?php echo $field['field_id']; ?>][weight]" value="<?php echo $field['weight']; ?>" />
								</td>
								<td class="left" width="20%"><?php echo $field['type']; ?></td>
								<td class="left"><?php echo $field['required']; ?></td>
								<td class="right">
									<?php foreach ($field['action'] as $action) { ?>
									[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $text_select_field; ?>&nbsp;
					<select class="available-fields">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($available_fields as $key => $value) { ?>
						<option <?php if (array_key_exists($key, $fields)) echo 'class="hidden"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?>
					</select>&nbsp;
					<a class="button" style="display: none;" id="add-field"><?php echo $button_insert; ?></a>
					<p class="help"><?php echo $text_help_custom_fields; ?></p>
				</div>
				<?php } ?>
                <div id="tab-additional">
                    <table class="form">
                        <tbody>
                            <tr>
                                <td><?php echo $entry_store; ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $class = 'even'; ?>
                                        <?php foreach ($stores as $store) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="template[store][]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($template_store) && in_array($store['store_id'], $template_store)) echo 'checked="checked"'; ?> />
                                        <?php echo $store['name']; ?>
                                        
                                        </div>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="left"><label for="template-stock-checkout"><?php echo $entry_stock_checkout; ?></label></td>
                                <td>
                                    <select id="template-stock-checkout" name="template[stock_checkout]">
                                        <?php foreach($status_variables as $key => $variable) { ?>
                                        <option <?php if ($stock_checkout == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="left"><label for="template-minimum"><?php echo $entry_minimum; ?></label></td>
                                <td>
                                    <select id="template-minimum" name="template[minimum]">
                                        <?php foreach($status_variables as $key => $variable) { ?>
                                        <option <?php if ($minimum == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="slider-group parent">
                                <td class="left"><label for="template-use-option"><?php echo $entry_use_option; ?></label></td>
                                <td>
                                    <select id="template-use-option" name="template[use_option]" class="slider">
                                        <?php foreach($status_variables as $key => $variable) { ?>
                                        <option <?php if ($use_option == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="status"><?php echo (!isset($template_info['use_option']) || !$template_info['use_option']) ? "&#9660;" : "&#9650;"; ?></span>
                                </td>
                            </tr>
                            <tr class="slider-group children">
                                <td colspan="2" class="include">
                                    <div class="slider-content<?php if (!$use_option) echo " hidden"; ?>" >
                                        <table class="form">
                                            <tbody>
                                                <tr>
                                                    <td class="left"><label for="template-option-required"><?php echo $entry_option_required; ?></label></td>
                                                    <td>
                                                        <select id="template-option-required" name="template[option_required]">
                                                            <?php foreach($status_variables as $key => $variable) { ?>
                                                            <option <?php if ($option_required == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><label for="template-show-option"><?php echo $entry_show_option; ?></label></td>
                                                    <td>
                                                        <select id="template-show-option" name="template[show_option]">
                                                            <?php foreach($status_variables as $key => $variable) { ?>
                                                            <option <?php if ($show_option == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="left"><label for="template-show-total"><?php echo $entry_show_total; ?></label></td>
                                <td>
                                    <select id="template-show-total" name="template[show_total]">
                                        <?php foreach($status_variables as $key => $variable) { ?>
                                        <option <?php if ($show_total == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="slider-group parent">
                                <td><label for="template-redirect"><?php echo $entry_redirect_success; ?></label></td>
                                <td>
                                    <select id="template-redirect" name="template[redirect]" class="slider">
                                        <?php foreach($status_variables as $key => $variable) { ?>
                                        <option <?php if ($redirect == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="status"><?php echo (isset($template_info['redirect']) && $template_info['redirect']) ? "&#9660;" : "&#9650;"; ?></span>
                                </td>
                            </tr>
                            <tr class="slider-group children">
                                <td colspan="2" class="include">
                                    <div class="slider-content<?php if ($redirect) echo " hidden"; ?>" >
                                        <table class="form">
                                            <tbody>
                                                <tr>
                                                    <td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="template-success-message"><?php echo $entry_success_message; ?></label></td>
                                                    <td>
                                                        <textarea id="template-success-message" rows="5" cols="70" name="template[success_message]" <?php if (isset($error['success_message'])) echo 'class="error_field"'; ?>"><?php echo  $success_message; ?></textarea>
                                                        <?php if (isset($error['success_message'])) { ?>
                                                        <span class="error"><?php echo $error['success_message']; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
							<tr>
								<td><?php echo $entry_button_label; ?></td>
								<td>
									<?php foreach ($languages as $language) { ?>
									<input type="text" name="template[button][<?php echo $language['language_id']; ?>]" value="<?php echo isset($button[$language['language_id']]) ? $button[$language['language_id']] : ''; ?>" />
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
									<?php } ?>
								</td>
							</tr>
                            <tr>
                                <td><label for="quick-order-pro-class"><?php echo $entry_css_class; ?></label></td>
                                <td><input id="quick-order-pro-class" type="text" name="template[class]" value="<?php echo $class_name; ?>" /></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="info"><sup>[1]</sup> <span class="help"><?php echo $text_help_layout_product; ?></span></p>
                </div>
            	<input type="hidden" name="apply" value="0" />
            </form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--

	function addCkeditor(el) {
		CKEDITOR.replace(el, {
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		}); 
	}
	
	$('#add-field').click(function(event){
		
		event.preventDefault();
		
		var field_key = $('select.available-fields').val();
		
		if (field_key == 0) return;
		
		window.location = '<?php echo $base_url; ?>' + field_key;
	});
	
	$('select.available-fields').change(function(event){
	
		event.preventDefault();
		var field_key = $(this).val();
		
		if (field_key == 0) {
			$('#add-field').hide();
		} else {
			$('#add-field').show();
		}
		
	});
	
	$('.show-hide-editor').click(function(event){
		event.preventDefault();
		var context = $(this).parents('tr');
		var textarea = $('textarea', context);
		if (CKEDITOR.instances[$(textarea).attr('id')]) {
			CKEDITOR.instances[$(textarea).attr('id')].destroy(true);
			var help_text = '<?php echo $text_enabled_editor; ?>';
			var val = 0;
		} else {
			addCkeditor($(textarea).attr('id'));
			var help_text = '<?php echo $text_disable_editor; ?>';
			var val = 1;
		}
		$('input[type=hidden]', context).val(val);
		$(this).text(help_text);
	});
	
	<?php foreach ($languages as $language) { ?>
		if ($('#template-description-<?php echo $language['language_id']; ?>').hasClass('enabled')) {
			addCkeditor('template-description-<?php echo $language['language_id']; ?>');
		}
	<?php } ?>
	
	$('#tab-fields .list').tableDnD({
		onDrop: function(table, row) {
			
			$('tbody tr', table).each(function(){
				$('td input.order', this).val($(this).index());
			});
			
			$(row).addClass('changed').find('td:eq(1)');
		},
		onDragClass: 'draggable',
		dragHandle: ".drag"
	}).addClass('table-dnd');

	$('#tabs a').tabs();
	$('#language a').tabs();
</script>
<?php echo $footer; ?>