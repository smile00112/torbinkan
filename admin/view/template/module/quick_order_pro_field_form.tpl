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
					<a href="#tab-data"><?php echo $tab_data; ?></a>
					<a href="#tab-type"><?php echo $tab_type; ?></a>
                    <a href="#tab-validate"><?php echo $tab_validate; ?></a>
                </div>
                <div id="tab-general">
                    <table class="form">
                        <tbody>
                            <tr>
                            	<td colspan="2"><span class="help id">ID:</span> <?php echo $field_id; ?></td>
                            </tr>
                        </tbody>
                    </table>
					<?php if (isset($text_help_field)) { ?>
					<p class="help field"><?php echo $text_help_field; ?></p>
					<?php } ?>
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
                                    <td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-title-<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label></td>
                                    <td>
										<input id="field-title-<?php echo $language['language_id']; ?>" type="text" name="field[title][<?php echo $language['language_id']; ?>]" value="<?php if(isset($title[$language['language_id']])) echo $title[$language['language_id']]; ?>" />
										<?php if (isset($error['title'][$language['language_id']])) { ?>
										<span class="error"><?php echo $error['title'][$language['language_id']]; ?></span>
										<?php } ?>
									</td>
                                </tr>
                                <tr>
                                    <td class="description field"><label for="field-description-<?php echo $language['language_id']; ?>"><?php echo $entry_field_description; ?></label></td>
                                    <td>
                                        <textarea id="field-description-<?php echo $language['language_id']; ?>" rows="7" cols="60" name="field[description][<?php echo $language['language_id']; ?>]" ><?php if (isset($description[$language['language_id']])) echo $description[$language['language_id']]; ?></textarea>
                                    </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
				<div id="tab-data">
					<table class="form">
						<tbody>
							<tr>
								<td><label for="field-required"><?php echo $entry_required; ?></label></td>
								<td style="width: 150px;">
									<?php if ($required_field) { ?>
									<?php echo $text_yes; ?>
									<input type="hidden" name="field[required]" value="1" />
									<?php } else { ?>
									<select name="field[required]" id="field-required">
									<?php foreach ($boolean_variables as $key => $value) { ?>
									<option <?php if ($required == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
									</select>
									<?php } ?>
								</td>
								<td>
									<div class="attention"><?php echo $text_help_field_required; ?></div>
								</td>
							</tr>
							<tr>
                                <td><label for="uick-order-pro-class"><?php echo $entry_css_class_field; ?></label></td>
                                <td colspan="2"><input id="uick-order-pro-class" type="text" name="field[class]" value="<?php echo $class_name; ?>" /></td>
                            </tr>
							<tr>
								<td><label for="field-status"><?php echo $entry_status; ?></label></td>
								<td colspan="2">
									<?php if ($required_field) { ?>
									<?php echo $text_enabled; ?>
									<input type="hidden" name="field[status]" value="1" />
									<?php } else { ?>
									<select id="field-status" name="field[status]">
										<?php foreach($status_variables as $key => $variable) { ?>
										<option <?php if ($status == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="field-weight"><?php echo $entry_weight; ?></label></td>
								<td colspan="2">
									<input id="field-weight" type="text" name="field[weight]" value="<?php echo $weight; ?>" size="2" />
									<?php if (isset($error['weight'])) { ?>
									<span class="error"><?php echo $error['weight']; ?></span>
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-type">
					<table class="form type-switcher">
						<tbody>
							<tr>
								<td><label for="field-type"><?php echo $entry_field_type; ?></label></td>
								<td style="width: 100px;">
									<select id="field-type" name="field[type][type]">
										<?php foreach ($field_types as $key => $type_info) { ?>
										<option <?php if ($type == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $key; ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<?php foreach ($field_types as $key => $type_info) { ?>
									<div id="help-item-<?php echo $key; ?>" class="help-item help<?php if ($key != $type) echo ' hidden'; ?>"><?php echo $type_info['description']; ?></div>
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
					<div id="field-type-text" class="field-type<?php if ($type != 'text') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								
								<tr>
									<td><label for="field-type-text-maxlength"><?php echo $entry_field_maxlength; ?></label></td>
									<td>
										<?php if ($order_field) { ?>
										<input type="hidden" name="field[type][option][text][maxlength]" value="128" />
										128
										<?php } else { ?>
										<input id="field-type-text-maxlength" type="text" name="field[type][option][text][maxlength]" value="<?php if (!empty($type_option['text']['maxlength'])) echo $type_option['text']['maxlength']; ?>" size="2" maxlength="2" />
										<?php } ?>						
										<?php if (isset($error['type']['text']['maxlength'])) { ?>
										<span class="error"><?php echo $error['type']['text']['maxlength']; ?></span>
										<?php } ?>
									</td>
								</tr>
								
								<tr>
									<td><label for="field-type-text-placeholder"><?php echo $entry_field_placeholder; ?></label></td>
									<td><input id="field-type-text-placeholder" type="text" name="field[type][option][text][placeholder]" value="<?php if (!empty($type_option['text']['placeholder'])) echo $type_option['text']['placeholder']; ?>" /></td>
								</tr>
								<tr class="slider-group parent">
									<td><label for="field-type-text-use-mask"><?php echo $entry_field_use_mask; ?></label></td>
									<td>
										<select id="field-type-text-use-mask" name="field[type][option][text][use_mask]" class="slider">
											<?php foreach($status_variables as $key => $variable) { ?>
											<option <?php if (!empty($type_option['text']['use_mask']) && $type_option['text']['use_mask'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
											<?php } ?>
										</select>
										<span class="status"><?php echo (empty($type_option['text']['use_mask']) || !$type_option['text']['use_mask']) ? "&#9660;" : "&#9650;"; ?></span>
									</td>
								</tr>
								<tr class="slider-group children">
									<td colspan="2" class="include">
										<div class="slider-content<?php if (empty($type_option['text']['use_mask']) || !$type_option['text']['use_mask']) echo " hidden"; ?>" >
											<table class="form">
												<tbody>
													<tr>
														<td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-type-text-mask"><?php echo $entry_field_mask; ?></label></td>
														<td width="100">
															<input id="field-type-text-mask" type="text" name="field[type][option][text][mask]" value="<?php if (!empty($type_option['text']['mask'])) echo $type_option['text']['mask']; ?>" />
															<?php if (isset($error['type']['text']['mask'])) { ?>
															<span class="error"><?php echo $error['type']['text']['mask']; ?></span>
															<?php } ?>
														</td>
														<td class="left"><?php echo $text_mash_example; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>				
					</div>
					<div id="field-type-textarea" class="field-type<?php if ($type != 'textarea') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-type-textarea-placeholder"><?php echo $entry_field_placeholder; ?></label></td>
									<td><input id="field-type-textarea-placeholder" type="text" name="field[type][option][textarea][placeholder]" value="<?php if (!empty($type_option['textarea']['placeholder'])) echo $type_option['textarea']['placeholder']; ?>" /></td>
								</tr>
								<tr>
									<td><label for="field-type-textarea-rows"><?php echo $entry_field_textarea_rows; ?></label></td>
									<td>
										<input id="field-type-textarea-rows" type="text" name="field[type][option][textarea][rows]" value="<?php if (!empty($type_option['textarea']['placeholder'])) echo $type_option['textarea']['rows']; ?>" size="2" />
										<?php if (isset($error['type']['textarea']['rows'])) { ?>
										<span class="error"><?php echo $error['type']['textarea']['rows']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-type-select" class="field-type<?php if ($type != 'select') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-type-select-multiple"><?php echo $entry_field_select_multiple; ?></label></td>
									<td>
										<select id="field-type-select-multiple" name="field[type][option][select][multiple]" class="slider">
											<?php foreach($boolean_variables as $key => $variable) { ?>
											<option <?php if (!empty($type_option['select']['multiple']) && $type_option['select']['multiple'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<?php if (isset($error['type']['select']['option']) && is_scalar($error['type']['select']['option'])) { ?>
						<span class="error"><?php echo $error['type']['select']['option']; ?></span>
						<?php } ?>
						<table class="list">
							<thead>
								<tr>
									<td class="left" colspan="2"><?php echo $column_value; ?></td>
									<td class="left"><?php echo $column_active_item; ?></td>
									<td class="right">&nbsp;</td>
								</tr>
							</thead>
							<tbody>
								<?php $select_option_id = 1; ?>
								<?php if (!empty($type_option['select']['option'])) { ?>
								<?php foreach ($type_option['select']['option'] as $select_option_id => $option) { ?>
									<tr id="row-select-<?php echo $select_option_id; ?>">
										<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
										<td class="left">
											<input type="text" value="<?php echo $option['value']; ?>" name="field[type][option][select][option][<?php echo $select_option_id; ?>][value]" />
											<input type="hidden" class="weight" value="<?php echo $option['weight']; ?>" name="field[type][option][select][option][<?php echo $select_option_id; ?>][weight]" />
											<?php if (isset($error['type']['select']['option'][$select_option_id])) { ?>
											<span class="error"><?php echo $error['type']['select']['option'][$select_option_id]; ?></span>
											<?php } ?>
										</td>
										<td class="left"><input <?php if (!empty($type_option['select']['selected']) && $type_option['select']['selected'] == $select_option_id) echo 'checked="checked"'; ?> type="radio" value="<?php echo $select_option_id; ?>" name="field[type][option][select][selected]" /></td>
										<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>
									</tr>
									
								<?php } ?>
								<?php $select_option_id++; ?>
								<?php } ?>
							</tbody>
						</table>
						<input type="text" value="" class="add-value" /> <a id="add-select-option" class="button"><?php echo $button_add_value; ?></a>
						<br />
					</div>
					<div id="field-type-checkbox" class="field-type<?php if ($type != 'checkbox') echo ' hidden'; ?>">
						<?php if (isset($error['type']['checkbox']['option']) && is_scalar($error['type']['checkbox']['option'])) { ?>
						<br />
						<span class="error"><?php echo $error['type']['checkbox']['option']; ?></span>
						<?php } ?>
						<table class="list">
							<thead>
								<tr>
									<td class="left" colspan="2"><?php echo $column_value; ?></td>
									<td class="left"><?php echo $column_active_item; ?></td>
									<td class="right">&nbsp;</td>
								</tr>
							</thead>
							<tbody>
								<?php $checkbox_item_id = 1; ?>
								<?php if (!empty($type_option['checkbox']['option'])) { ?>
								<?php foreach ($type_option['checkbox']['option'] as $checkbox_item_id => $item) { ?>
									<tr id="row-checkbox-<?php echo $checkbox_item_id; ?>">
										<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
										<td class="left">
											<input type="text" value="<?php echo $item['value']; ?>" name="field[type][option][checkbox][option][<?php echo $checkbox_item_id; ?>][value]" />
											<input type="hidden" class="weight" value="<?php echo $item['weight']; ?>" name="field[type][option][checkbox][option][<?php echo $checkbox_item_id; ?>][weight]" />
											<?php if (isset($error['type']['checkbox']['option'][$checkbox_item_id])) { ?>
											<span class="error"><?php echo $error['type']['checkbox']['option'][$checkbox_item_id]; ?></span>
											<?php } ?>
										</td>
										<td class="left"><input <?php if (!empty($type_option['checkbox']['checked']) &&  is_array($type_option['checkbox']['checked']) && in_array($checkbox_item_id, $type_option['checkbox']['checked'])) echo  'checked="checked"'; ?> type="checkbox" value="<?php echo $checkbox_item_id; ?>" name="field[type][option][checkbox][checked][]" /></td>
										<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>
									</tr>
								<?php } ?>
								<?php $checkbox_item_id++; ?>
								<?php } ?>
							</tbody>
						</table>
						<input type="text" value="" class="add-value" /> <a id="add-checkbox-item" class="button"><?php echo $button_add_value; ?></a>
						<br />
					</div>
					<div id="field-type-radio" class="field-type<?php if ($type != 'radio') echo ' hidden'; ?>">
						<?php if (isset($error['type']['radio']['option']) && is_scalar($error['type']['radio']['option'])) { ?>
						<br />
						<span class="error"><?php echo $error['type']['radio']['option']; ?></span>
						<?php } ?>
						<table class="list">
							<thead>
								<tr>
									<td class="left" colspan="2"><?php echo $column_value; ?></td>
									<td class="left"><?php echo $column_active_item; ?></td>
									<td class="right">&nbsp;</td>
								</tr>
							</thead>
							<tbody>
								<?php $radio_item_id = 1; ?>
								<?php if (!empty($type_option['radio']['option'])) { ?>
								<?php foreach ($type_option['radio']['option'] as $radio_item_id => $item) { ?>
									<tr id="row-checkbox-<?php echo $radio_item_id; ?>">
										<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
										<td class="left">
											<input type="text" value="<?php echo $item['value']; ?>" name="field[type][option][radio][option][<?php echo $radio_item_id; ?>][value]" />
											<input type="hidden" class="weight" value="<?php echo $item['weight']; ?>" name="field[type][option][radio][option][<?php echo $radio_item_id; ?>][weight]" />
											<?php if (isset($error['type']['radio']['option'][$radio_item_id])) { ?>
											<span class="error"><?php echo $error['type']['radio']['option'][$radio_item_id]; ?></span>
											<?php } ?>
										</td>
										<td class="left"><input type="radio" <?php if (!empty($type_option['radio']['checked']) && $type_option['radio']['checked'] == $radio_item_id) echo 'checked="checked"'; ?> value="<?php echo $radio_item_id; ?>" name="field[type][option][radio][checked]" /></td>
										<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>
									</tr>
								<?php } ?>
								<?php $radio_item_id++; ?>
								<?php } ?>
							</tbody>
						</table>
						<input type="text" value="" class="add-value" /> <a id="add-radio-item" class="button"><?php echo $button_add_value; ?></a>
						<br />
					</div>
				</div>
                <div id="tab-validate">
					<p class="help validate"><?php echo $text_help_validate; ?></p>
					<table class="form validate-switcher">
						<tbody>
							<tr>
								<td><label for="field-validate"><?php echo $entry_field_validate; ?></label></td>
								<td style="width: 170px;">
									<select id="field-validate" name="field[validate][type]">
										<option value=""><?php echo $text_none; ?></option>
										<?php foreach ($validate_types as $key => $validate_info) { ?>
										<option class="<?php echo implode(' ', $validate_info['destination']); ?>"<?php if (reset($validate_info['destination']) != '*' && !in_array($type, $validate_info['destination'])) echo ' disabled="disabled"'; ?> <?php if ($validate == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $validate_info['title']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<?php foreach ($validate_types as $key => $validate_info) { ?>
									<div id="help-validate-<?php echo $key; ?>" class="help-validate help<?php if ($key != $validate) echo ' hidden'; ?>"><?php echo $validate_info['description']; ?></div>
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
					<div id="field-validate-pcre" class="field-validate<?php if ($validate != 'pcre') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-pcre-pattern"><?php echo $entry_pcre_pattern; ?></label></td>
									<td>
										<input id="field-validate-pcre-pattern" type="text" name="field[validate][option][pcre][pattern]" value="<?php if (!empty($validate_option['pcre']['pattern'])) echo $validate_option['pcre']['pattern']; ?>" />
										<?php if (isset($error['validate']['pcre']['pattern'])) { ?>
										<span class="error"><?php echo $error['validate']['pcre']['pattern']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-pcre-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-pcre-error-message" name="field[validate][option][pcre][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['pcre']['error_message'])) echo $validate_option['pcre']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['pcre']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['pcre']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-length" class="field-validate<?php if ($validate != 'length') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-validate-length-min"><?php echo $entry_int_min_value; ?></label></td>
									<td>
										<input id="field-validate-length-min" type="text" name="field[validate][option][length][min]" value="<?php if (isset($validate_option['length']['min'])) echo $validate_option['length']['min']; ?>" size="2" />
										<?php if (isset($error['validate']['length']['min'])) { ?>
										<span class="error"><?php echo $error['validate']['length']['min']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><label for="field-validate-length-max"><?php echo $entry_int_max_value; ?></label></td>
									<td>
										<input id="field-validate-length-max" type="text" name="field[validate][option][length][max]" value="<?php if (isset($validate_option['length']['max'])) echo $validate_option['length']['max']; ?>" size="2" />
										<?php if (isset($error['validate']['length']['max'])) { ?>
										<span class="error"><?php echo $error['validate']['length']['max']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-length-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
													<tr>
														<td class="left">{field_min}</td>
														<td class="left"><?php echo $text_token_field_min; ?></td>
													</tr>
													<tr>
														<td class="left">{field_max}</td>
														<td class="left"><?php echo $text_token_field_max; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-length-error-message" name="field[validate][option][length][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['length']['error_message'])) echo $validate_option['length']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['length']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['length']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-number_of_words" class="field-validate<?php if ($validate != 'number_of_words') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
							
							
								<tr>
									<td><label for="field-validate-number-of-words-min"><?php echo $entry_int_min_value; ?></label></td>
									<td>
										<input id="field-validate-number-of-words-min" type="text" name="field[validate][option][number_of_words][min]" value="<?php if (isset($validate_option['number_of_words']['min'])) echo $validate_option['number_of_words']['min']; ?>" size="2" />
										<?php if (isset($error['validate']['number_of_words']['min'])) { ?>
										<span class="error"><?php echo $error['validate']['number_of_words']['min']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><label for="field-validate-number-of-words-max"><?php echo $entry_int_max_value; ?></label></td>
									<td>
										<input id="field-validate-number-of-words-max" type="text" name="field[validate][option][number_of_words][max]" value="<?php if (isset($validate_option['number_of_words']['max'])) echo $validate_option['number_of_words']['max']; ?>" size="2" />
										<?php if (isset($error['validate']['number_of_words']['max'])) { ?>
										<span class="error"><?php echo $error['validate']['number_of_words']['max']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-number-of-words-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
													<tr>
														<td class="left">{field_min}</td>
														<td class="left"><?php echo $text_token_field_min; ?></td>
													</tr>
													<tr>
														<td class="left">{field_max}</td>
														<td class="left"><?php echo $text_token_field_max; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-number-of-words-error-message" name="field[validate][option][number_of_words][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['number_of_words']['error_message'])) echo $validate_option['number_of_words']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['number_of_words']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['number_of_words']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-words_blacklist" class="field-validate<?php if ($validate != 'words_blacklist') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-validate-words-blacklist-list"><?php echo $entry_blacklist; ?></label></td>
									<td>
										<textarea id="field-validate-words-blacklist-list" name="field[validate][option][words_blacklist][list]" rows="3" cols="65"><?php if (!empty($validate_option['words_blacklist']['list'])) echo $validate_option['words_blacklist']['list']; ?></textarea>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-words-blacklist-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-words-blacklist-error-message" name="field[validate][option][words_blacklist][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['words_blacklist']['error_message'])) echo $validate_option['words_blacklist']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['words_blacklist']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['words_blacklist']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-specific_value" class="field-validate<?php if ($validate != 'specific_value') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-validate-specific-value-list"><?php echo $entry_specific_value_list; ?></label></td>
									<td>
										<textarea id="field-validate-specific-value-list" name="field[validate][option][specific_value][list]" rows="3" cols="65"><?php if (!empty($validate_option['specific_value']['list'])) echo $validate_option['specific_value']['list']; ?></textarea>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-specific-value-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-specific-value-error-message" name="field[validate][option][specific_value][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['specific_value']['error_message'])) echo $validate_option['specific_value']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['specific_value']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['specific_value']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-numeric" class="field-validate<?php if ($validate != 'numeric') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-validate-numeric-min"><?php echo $entry_int_min_value; ?></label></td>
									<td>
										<input id="field-validate-numeric-min" type="text" name="field[validate][option][numeric][min]" value="<?php if (isset($validate_option['numeric']['min'])) echo $validate_option['numeric']['min']; ?>" size="2" />
										<?php if (isset($error['validate']['numeric']['min'])) { ?>
										<span class="error"><?php echo $error['validate']['numeric']['min']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><label for="field-validate-numeric-max"><?php echo $entry_int_max_value; ?></label></td>
									<td>
										<input id="field-validate-numeric-max" type="text" name="field[validate][option][numeric][max]" value="<?php if (isset($validate_option['numeric']['max'])) echo $validate_option['numeric']['max']; ?>" size="2" />
										<?php if (isset($error['validate']['numeric']['max'])) { ?>
										<span class="error"><?php echo $error['validate']['numeric']['max']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-numeric-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
													<tr>
														<td class="left">{field_min}</td>
														<td class="left"><?php echo $text_token_field_min; ?></td>
													</tr>
													<tr>
														<td class="left">{field_max}</td>
														<td class="left"><?php echo $text_token_field_max; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-numeric-error-message" name="field[validate][option][numeric][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['numeric']['error_message'])) echo $validate_option['numeric']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['numeric']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['numeric']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-int" class="field-validate<?php if ($validate != 'int') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td><label for="field-validate-int-min"><?php echo $entry_int_min_value; ?></label></td>
									<td>
										<input id="field-validate-int-min" type="text" name="field[validate][option][int][min]" value="<?php if (isset($validate_option['int']['min'])) echo $validate_option['int']['min']; ?>" size="2" />
										<?php if (isset($error['validate']['int']['min'])) { ?>
										<span class="error"><?php echo $error['validate']['int']['min']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><label for="field-validate-int-max"><?php echo $entry_int_max_value; ?></label></td>
									<td>
										<input id="field-validate-int-max" type="text" name="field[validate][option][int][max]" value="<?php if (isset($validate_option['int']['max'])) echo $validate_option['int']['max']; ?>" size="2" />
										<?php if (isset($error['validate']['int']['max'])) { ?>
										<span class="error"><?php echo $error['validate']['int']['max']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-int-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
													<tr>
														<td class="left">{field_min}</td>
														<td class="left"><?php echo $text_token_field_min; ?></td>
													</tr>
													<tr>
														<td class="left">{field_max}</td>
														<td class="left"><?php echo $text_token_field_max; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-int-error-message" name="field[validate][option][int][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['int']['error_message'])) echo $validate_option['int']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['int']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['int']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-email" class="field-validate<?php if ($validate != 'email') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-email-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-email-error-message" name="field[validate][option][email][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['email']['error_message'])) echo $validate_option['email']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['email']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['email']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr class="slider-group parent">
									<td><label for="field-validate-email-check-domain"><?php echo $entry_field_email_check_domain; ?></label></td>
									<td>
										<select id="field-validate-email-check-domain" name="field[validate][option][email][check_domain]" class="slider">
											<?php foreach($status_variables as $key => $variable) { ?>
											<option <?php if (!empty($validate_option['email']['check_domain']) && $validate_option['email']['check_domain'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
											<?php } ?>
										</select>
										<span class="status"><?php echo (empty($validate_option['email']['check_domain']) || !$validate_option['email']['check_domain']) ? "&#9660;" : "&#9650;"; ?></span>
									</td>
								</tr>
								<tr class="slider-group children">
									<td colspan="2" class="include">
										<div class="slider-content<?php if (empty($validate_option['email']['check_domain']) || !$validate_option['email']['check_domain']) echo " hidden"; ?>" >
											<table class="form">
												<tbody>
													<tr>
														<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-email-error-message-domain"><?php echo $entry_error_message_email_domain; ?></label></td>
														<td class="top">
															<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
															<div class="content" style="display: none;">
																<table class="list token">
																	<thead>
																		<tr>
																			<td width="30%" class="left"><?php echo $column_token; ?></td>
																			<td width="70%" class="left"><?php echo $column_value; ?></td>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td class="left">{field_name}</td>
																			<td class="left"><?php echo $text_token_field_name; ?></td>
																		</tr>
																		<tr>
																			<td class="left">{field_domain}</td>
																			<td class="left"><?php echo $text_token_field_domain; ?></td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<textarea id="field-validate-url-error-message-domain" name="field[validate][option][email][error_message_domain]" rows="3" cols="65"><?php if (!empty($validate_option['email']['error_message_domain'])) echo $validate_option['email']['error_message_domain']; ?></textarea>
															<?php if (isset($error['validate']['email']['error_message_domain'])) { ?>
															<span class="error"><?php echo $error['validate']['email']['error_message_domain']; ?></span>
															<?php } ?>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-url" class="field-validate<?php if ($validate != 'url') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-url-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-url-error-message" name="field[validate][option][url][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['url']['error_message'])) echo $validate_option['url']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['url']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['url']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="field-validate-plain_text" class="field-validate<?php if ($validate != 'plain_text') echo ' hidden'; ?>">
						<table class="form">
							<tbody>
								<tr>
									<td class="top"><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="field-validate-plain-text-error-message"><?php echo $entry_error_message; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list token">
												<thead>
													<tr>
														<td width="30%" class="left"><?php echo $column_token; ?></td>
														<td width="70%" class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">{field_name}</td>
														<td class="left"><?php echo $text_token_field_name; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="field-validate-plain-text-error-message" name="field[validate][option][plain_text][error_message]" rows="3" cols="65"><?php if (!empty($validate_option['plain_text']['error_message'])) echo $validate_option['plain_text']['error_message']; ?></textarea>
										<?php if (isset($error['validate']['plain_text']['error_message'])) { ?>
										<span class="error"><?php echo $error['validate']['plain_text']['error_message']; ?></span>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
                </div>
				<input type="hidden" name="field[code]" value="<?php echo $code; ?>" />
				<input type="hidden" name="field[field_id]" value="<?php echo $field_id; ?>" />
            	<input type="hidden" name="apply" value="0" />
            </form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--

	$('#form').delegate('p.link a.js', 'click', function(event){
		event.preventDefault();
		$(this).parent().next('.content').slideToggle('fast');
	});

	$('select#field-type').change(function(event){
		
		var type = $(this).val();
		
		$('.field-type').hide();
		$('#field-type-' + type).fadeIn('fast');
		
		// help
		$('.help-item').hide();
		$('#help-item-' + type).fadeIn('fast');
		
		// validation
		$("select#field-validate option:gt(0)[class!='*'][class!=" + type + "]").attr('disabled', 'disabled');
		$('select#field-validate option[class*=' + type + ']').removeAttr('disabled');
		
		if ($("select#field-validate option:selected").is(':disabled')) {
			
			$('select#field-validate option:selected').each(function(){
				this.selected=false;
			});
			
			$('select#field-validate').trigger('change');
		}
	});
	
	$('select#field-validate').change(function(event){
		
		var validate_type = $(this).val();
		
		$('.field-validate').hide();
		$('#field-validate-' + validate_type).fadeIn('fast');
		
		// help
		$('.help-validate').hide();
		$('#help-validate-' + validate_type).fadeIn('fast');
	});
	
	var select_option_id = <?php echo $select_option_id; ?>;

	$('#add-select-option').click(function(event){
		event.preventDefault();
		
		var source = $(this).prev('input.add-value');
		
		var value = source.val();
		
		if (value != '') {
			
			var table = $('#field-type-select table.list');
			var weight = getWeight(table);
			
			var html = '<tr id="row-select-' + select_option_id + '">';
			html += '		<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>';
			html += '		<td class="left">';
			html += '			<input type="text" value="' + value + '" name="field[type][option][select][option][' + select_option_id + '][value]" />';
			html += '			<input type="hidden" class="weight" value="' + weight + '" name="field[type][option][select][option][' + select_option_id + '][weight]" />';
			html += '		</td>';
			html += '		<td class="left"><input type="radio" value="' + select_option_id + '" name="field[type][option][select][selected]" /></td>';
			html += '		<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>';
			html += '	</tr>';
			
			$('tbody', table).append(html);
			source.val('');
			
			select_option_id++;
			
			addTableDnD(table);
		}
	});
	
	var checkbox_item_id = <?php echo $checkbox_item_id; ?>;
	
	$('#add-checkbox-item').click(function(event){
		event.preventDefault();
		
		var source = $(this).prev('input.add-value');
		
		var value = source.val();
		
		if (value != '') {
			
			var table = $('#field-type-checkbox table.list');
			var weight = getWeight(table);
			
			var html = '<tr id="row-checkbox-' + checkbox_item_id + '">';
			html += '		<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>';
			html += '		<td class="left">';
			html += '			<input type="text" value="' + value + '" name="field[type][option][checkbox][option][' + checkbox_item_id + '][value]" />';
			html += '			<input type="hidden" class="weight" value="' + weight + '" name="field[type][option][checkbox][option][' + checkbox_item_id + '][weight]" />';
			html += '		</td>';
			html += '		<td class="left"><input type="checkbox" value="' + checkbox_item_id + '" name="field[type][option][checkbox][checked][]" /></td>';
			html += '		<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>';
			html += '	</tr>';
			
			$('tbody', table).append(html);
			source.val('');
			
			checkbox_item_id++;
			
			addTableDnD(table);
		}
	});
	
	var radio_item_id = <?php echo $radio_item_id; ?>;
	
	$('#add-radio-item').click(function(event){
		event.preventDefault();
		
		var source = $(this).prev('input.add-value');
		
		var value = source.val();
		
		if (value != '') {
			
			var table = $('#field-type-radio table.list');
			var weight = getWeight(table);
			
			var html = '<tr id="row-radio-' + radio_item_id + '">';
			html += '		<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>';
			html += '		<td class="left">';
			html += '			<input type="text" value="' + value + '" name="field[type][option][radio][option][' + radio_item_id + '][value]" />';
			html += '			<input type="hidden" class="weight" value="' + weight + '" name="field[type][option][radio][option][' + radio_item_id + '][weight]" />';
			html += '		</td>';
			html += '		<td class="left"><input type="radio" value="' + radio_item_id + '" name="field[type][option][radio][checked]" /></td>';
			html += '		<td class="right"><a class="remove-value button"><?php echo $button_remove; ?></a></td>';
			html += '	</tr>';
			
			$('tbody', table).append(html);
			source.val('');
			
			radio_item_id++;
			
			addTableDnD(table);
		}
	});
	
	function getWeight(table) {
		
		var weight = -1;
		
		$('tbody tr', table).each(function(i, el){
			var item_weight = $('input.weight', el).val();
			if (item_weight > weight) weight = item_weight;
		});
		
		return ++weight;
	}
	
	$('.field-type table.list').each(function(){
		addTableDnD(this);
	});
	
	function addTableDnD(el) {
		$(el).tableDnD({
			onDrop: function(table, row) {
				
				$('tbody tr', table).each(function(){
					$('td input.weight', this).val($(this).index());
				});
				
				$(row).addClass('changed').find('td:eq(1)');
				
				var change = $(row).find('td:eq(1)');
				
				if (!$('span.required', change).length) {
					$(change).append(' <span class="required" title="<?php echo $text_edit_sort_value; ?>">*</span>');
				}
				
			},
			onDragClass: 'draggable',
			dragHandle: ".drag"
		}).addClass('table-dnd');
	}

	$('.remove-value').live('click', function(event){
		event.preventDefault();
		$(this).closest('tr').remove();
	});
	
	$('#tabs a').tabs();
	$('#language a').tabs();
	
</script>
<?php echo $footer; ?>