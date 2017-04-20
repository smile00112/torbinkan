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
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<?php if ($attention) { ?>
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
            <div id="tabs" class="htabs">
                <a href="#tab-block"><?php echo $tab_block; ?></a>
                <a href="#tab-templates"><?php echo $tab_templates; ?></a>
                <a href="#tab-setting"><?php echo $tab_setting; ?></a>
            </div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            	<div id="tab-block">
                    <table id="module" class="list">
                        <thead>
                            <tr>
                                <td class="left"><span class="required" title="<?php echo $text_required; ?>">*</span> <?php echo $entry_template; ?></td>
                                <td class="left"><?php echo $entry_layout; ?></td>
                                <td class="left"><?php echo $entry_position; ?></td>
                                <td class="left"><?php echo $entry_status; ?></td>
                                <td class="left"><?php echo $entry_sort_order; ?></td>
                                <td class="right"><?php echo $entry_operations; ?></td>
                            </tr>
                        </thead>
                        <?php $module_row = 0; ?>
                        <?php foreach ($modules as $module) { ?>
                        <tbody id="module-row<?php echo $module_row; ?>">
                            <tr>
                                <td class="left template_id">
                                    <select name="quick_order_pro_module[<?php echo $module_row; ?>][template_id]">
                                        <option value=""><?php echo $text_select; ?></option>
                                        <?php foreach ($templates as $template_info) { ?>
                                        <option <?php if ($template_info['template_id'] == $module['template_id']) echo 'selected="selected"'; ?> value="<?php echo $template_info['template_id']; ?>"><?php echo $template_info['base_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($error['content'][$module_row])) { ?>
                                    <div class="error"><?php echo $error['content'][$module_row]; ?></div>
                                    <?php } ?>
                                </td>
                                <td class="left"><select name="quick_order_pro_module[<?php echo $module_row; ?>][layout_id]">
                                <?php foreach ($layouts as $layout) { ?>
                                <option <?php if ($layout['layout_id'] == $module['layout_id']) echo 'selected="selected"'; ?> value="<?php echo $layout['layout_id']; ?>" ><?php echo $layout['name']; ?></option>
                                <?php } ?>
                                </select></td>
                                <td class="left"><select name="quick_order_pro_module[<?php echo $module_row; ?>][position]">
                                <?php foreach ($positions as $position_key => $position) { ?>
                                <option <?php if ($module['position'] == $position_key) echo 'selected="selected"'; ?> value="<?php echo $position_key; ?>"><?php echo $position; ?></option>
                                <?php } ?>
                                </select></td>
                                <td class="left"><select name="quick_order_pro_module[<?php echo $module_row; ?>][status]">
                                <?php foreach($status_variables as $key => $variable) { ?>
                                <option <?php if ($module['status'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                <?php } ?>
                                </select></td>
                                <td class="left"><input type="text" name="quick_order_pro_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                                <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                        </tbody>
                        <?php $module_row++; ?>
                        <?php } ?>
                    </table>
                    <a class="button" onclick="addModule();"><?php echo $button_add_module; ?></a>
                   </div>
				<div id="tab-templates">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $column_template_id; ?></td>
								<td class="left"><?php echo $column_machine_name; ?></td>
								<td class="left"><?php echo $column_store; ?></td>
								<td class="right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($templates) { ?>
							<?php foreach ($templates as $template_info) { ?>
							<tr>
								<td class="left">template_<?php echo $template_info['template_id']; ?></td>
								<td class="left"><?php echo $template_info['base_name']; ?></td>
								<td class="left"><?php echo $template_info['store']; ?></td>
								<td class="right">
								<?php foreach ($template_info['action'] as $action) { ?>
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
					<a href="<?php echo $insert_template; ?>" class="button"><?php echo $button_insert_template; ?></a>
				</div>
                   <div id="tab-setting">
                        <div id="stabs" class="vtabs">
                            <a href="#tab-general"><?php echo $tab_general; ?></a>
							<a href="#tab-catcha"><?php echo $tab_catcha; ?></a>
                            <a href="#tab-notifications"><?php echo $tab_notifications; ?></a>
                        </div>
                        <div id="tab-general" class="vtabs-content">
                            <table class="form">
                                <tbody>
                                    <tr>
                                        <td><label for="quick-order-pro-setting-order-status-id"><?php echo $entry_order_status; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-order-status-id" name="quick_order_pro_setting[order_status_id]">
                                                <?php foreach ($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" <?php if (!empty($setting['order_status_id']) && $setting['order_status_id'] == $order_status['order_status_id']) echo 'selected="selected"'; ?>><?php echo $order_status['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_customer_group; ?></td>
                                        <td class="left">
                                            <div class="scrollbox">
                                                    <?php $class = 'even'; ?>
                                                    <?php foreach ($customer_groups as $customer_group) { ?>
                                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                                    <div class="<?php echo $class; ?>">
                                                    <input type="checkbox" name="quick_order_pro_setting[customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (isset($setting['customer_group']) && in_array($customer_group['customer_group_id'], $setting['customer_group'])) echo 'checked="checked"'; ?> />
                                                    <?php echo $customer_group['name']; ?>
                                                    
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                        </td>
                                    </tr>
                                    <tr class="slider-group parent">
                                        <td><label for="quick-order-pro-setting-guest-checkout"><?php echo $entry_guest_checkout; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-guest-checkout" name="quick_order_pro_setting[guest_checkout]" class="slider">
                                                <?php foreach($status_variables as $key => $variable) { ?>
                                                <option <?php if (!empty($setting['guest_checkout']) && $setting['guest_checkout'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="status"><?php echo (empty($setting['guest_checkout']) || !$setting['guest_checkout']) ? "&#9660;" : "&#9650;"; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="slider-group children">
                                        <td colspan="2" class="include">
                                            <div class="slider-content<?php if (empty($setting['guest_checkout']) || !$setting['guest_checkout']) echo " hidden"; ?>" >
                                                <table class="form">
                                                    <tbody>
                                                        <tr class="slider-group parent">
                                                            <td><label for="quick-order-pro-setting-create-customer"><?php echo $entry_create_customer; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-create-customer" name="quick_order_pro_setting[create_customer]" class="slider">
                                                                    <?php foreach($status_variables as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['create_customer']) && $setting['create_customer'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
																<span class="status"><?php echo (empty($setting['create_customer']) || !$setting['create_customer']) ? "&#9660;" : "&#9650;"; ?></span>
                                                            </td>
                                                        </tr>
														<tr class="slider-group children">
														<td colspan="2" class="include">
															<div class="slider-content<?php if (empty($setting['create_customer']) || !$setting['create_customer']) echo " hidden"; ?>" >
																<table class="form">
																	<tbody>
																		<tr>
																			<td><label for="quick-order-pro-setting-new-customer-group"><?php echo $entry_new_customer_group; ?></label></td>
																			<td>
																				<select id="quick-order-pro-setting-new-customer-group" name="quick_order_pro_setting[new_customer_group]">
																					<?php foreach ($customer_groups as $customer_group) { ?>
																					<option <?php if (!empty($setting['new_customer_group']) && $setting['new_customer_group'] == $customer_group['customer_group_id']) echo 'selected="selected"'; ?> value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																					<?php } ?>
																				</select>
																			</td>
																		</tr>
																		<tr>
																			<td><label for="quick-order-pro-setting-new-customer-password"><?php echo $entry_new_customer_password; ?></label></td>
																			<td>
																				<input type="text" id="quick-order-pro-setting-new-customer-password" name="quick_order_pro_setting[new_customer_password]" value="<?php if (isset($setting['new_customer_password'])) echo $setting['new_customer_password']; ?>" <?php if (isset($setting['new_customer_random_password']) && $setting['new_customer_random_password']) echo 'disabled="disabled"'; ?> /> <label><input type="checkbox" value="1" id="quick-order-pro-setting-new-customer-random-password" name="quick_order_pro_setting[new_customer_random_password]" <?php if (isset($setting['new_customer_random_password']) && $setting['new_customer_random_password']) echo 'checked="checked"'; ?> /><?php echo $entry_new_customer_random_password; ?></label>
																				<?php if (isset($error['new_customer_password'])) { ?>
																				<span class="error"><?php echo $error['new_customer_password']; ?></span>
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
                                        </td>
                                    </tr>
									<tr>
                                        <td><label for="quick-order-pro-setting-autch-checkout"><?php echo $entry_autch_checkout; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-autch-checkout" name="quick_order_pro_setting[autch_checkout]">
                                                <?php foreach($status_variables as $key => $variable) { ?>
                                                <option <?php if (isset($setting['autch_checkout']) && $setting['autch_checkout'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="quick-order-pro-setting-subtract"><?php echo $entry_subtract; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-subtract" name="quick_order_pro_setting[subtract]">
                                                <?php foreach($status_variables + array('default' => $text_product_setting) as $key => $variable) { ?>
                                                <option <?php if (!empty($setting['subtract']) && $setting['subtract'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
    
                                </tbody>
                            </table>
                        </div>
						<div id="tab-catcha" class="vtabs-content">
							<table class="form">
                                <tbody>
									<tr class="slider-group parent">
                                        <td><label for="quick-order-pro-setting-show-lines"><?php echo $entry_captcha_show_lines; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-show-lines" name="quick_order_pro_setting[show_lines]" class="slider">
                                                <?php foreach($status_variables as $key => $variable) { ?>
                                                <option <?php if (!empty($setting['show_lines']) && $setting['show_lines'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="status"><?php echo (empty($setting['show_lines']) || !$setting['show_lines']) ? "&#9660;" : "&#9650;"; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="slider-group children">
                                        <td colspan="2" class="include">
                                            <div class="slider-content<?php if (empty($setting['show_lines']) || !$setting['show_lines']) echo " hidden"; ?>" >
                                                <table class="form">
                                                    <tbody>
														<tr>
                                                            <td><?php echo $entry_captcha_color_line; ?></td>
                                                            <td>
																<div class="colorpicker_item"><div style="background-color: rgb(<?php echo $setting['color_line']; ?>);">&nbsp;</div></div>
                                                               	<input id="quick-order-pro-setting-color-line" type="hidden" name="quick_order_pro_setting[color_line]" value="<?php echo $setting['color_line']; ?>" />
                                                                <?php if (isset($error['color_line'])) { ?>
                                                                <span class="error"><?php echo $error['color_line']; ?></span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
									<tr>
										<td><?php echo $entry_captcha_text_color; ?></td>
										<td>
											<div class="colorpicker_item"><div style="background-color: rgb(<?php echo $setting['captcha_color']; ?>);">&nbsp;</div></div>
										   	<input id="quick-order-pro-setting-captcha-color" type="hidden" name="quick_order_pro_setting[captcha_color]" value="<?php echo $setting['captcha_color']; ?>" />
											<?php if (isset($error['captcha_color'])) { ?>
											<span class="error"><?php echo $error['captcha_color']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td><label for="quick-order-pro-setting-captcha-count-items"><?php echo $entry_captcha_count_items; ?></label></td>
										<td>
											 <select id="quick-order-pro-setting-captcha-count-items" name="quick_order_pro_setting[captcha_count_items]" class="slider">
                                                <?php foreach($captcha_count_items as $key) { ?>
                                                <option <?php if (!empty($setting['captcha_count_items']) && $setting['captcha_count_items'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                                <?php } ?>
                                            </select>
										</td>
									</tr>
									<tr>
										<td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="quick-order-pro-setting-captcha-alphabet"><?php echo $entry_captcha_alphabet; ?></label></td>
										<td>
											<input id="quick-order-pro-setting-captcha-alphabet" type="text" name="quick_order_pro_setting[captcha_alphabet]" value="<?php echo $setting['captcha_alphabet']; ?>" />
											<?php if (isset($error['captcha_alphabet'])) { ?>
											<span class="error"><?php echo $error['captcha_alphabet']; ?></span>
											<?php } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
                        <div id="tab-notifications" class="vtabs-content">
                            <table class="form">
                                <tbody>
                                    <tr class="slider-group parent">
                                        <td><label for="quick-order-pro-setting-alert-customer"><?php echo $entry_alert_customer; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-alert-customer" name="quick_order_pro_setting[alert_customer]" class="slider">
                                                <?php foreach($status_variables as $key => $variable) { ?>
                                                <option <?php if (!empty($setting['alert_customer']) && $setting['alert_customer'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
											<span class="status"><?php echo (empty($setting['alert_customer']) || !$setting['alert_customer']) ? "&#9660;" : "&#9650;"; ?></span>
                                        </td>
                                    </tr>
									<tr class="slider-group children">
                                        <td colspan="2" class="include">
                                            <div class="slider-content<?php if (empty($setting['alert_customer']) || !$setting['alert_customer']) echo " hidden"; ?>" >
                                                <table class="form">
                                                    <tbody>
                                                        <tr>
                                                            <td><label for="quick-order-pro-setting-customer-email-show-ip"><?php echo $entry_customer_email_show_ip; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-customer-email-show-ip" name="quick_order_pro_setting[customer_email_show_ip]">
                                                                    <?php foreach($status_variables as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['customer_email_show_ip']) && $setting['customer_email_show_ip'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
														<tr>
                                                            <td><label for="quick-order-pro-setting-customer-email-show-ip"><?php echo $entry_customer_email_show_date_added; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-customer-email-show-ip" name="quick_order_pro_setting[customer_email_show_date_added]">
                                                                    <?php foreach($status_variables as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['customer_email_show_date_added']) && $setting['customer_email_show_date_added'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="slider-group parent">
                                        <td><label for="quick-order-pro-setting-alert-admin"><?php echo $entry_alert_admin; ?></label></td>
                                        <td>
                                            <select id="quick-order-pro-setting-alert-admin" name="quick_order_pro_setting[alert_admin]" class="slider">
                                                <?php foreach($status_variables as $key => $variable) { ?>
                                                <option <?php if (!empty($setting['alert_admin']) && $setting['alert_admin'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="status"><?php echo (empty($setting['alert_admin']) || !$setting['alert_admin']) ? "&#9660;" : "&#9650;"; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="slider-group children">
                                        <td colspan="2" class="include">
                                            <div class="slider-content<?php if (empty($setting['alert_admin']) || !$setting['alert_admin']) echo " hidden"; ?>" >
                                                <table class="form">
                                                    <tbody>
                                                        <tr>
                                                            <td><span class="required" title="<?php echo $text_required; ?>">*</span> <label for="quick-order-pro-setting-admin-email-type"><?php echo $entry_admin_email_type; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-admin-email-type" name="quick_order_pro_setting[admin_email_type]">
                                                                    <?php foreach($email_types as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['admin_email_type']) && $setting['admin_email_type'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input class="<?php if (empty($setting['admin_email_type']) || $setting['admin_email_type'] == 'config') echo 'hidden '; ?>" placeholder="<?php echo $text_alert_email; ?>" type="text" size="35" maxlength="120" name="quick_order_pro_setting[alert_email]" value="<?php if (!empty($setting['alert_email'])) echo $setting['alert_email']; ?>" />
                                                                <?php if (isset($error['alert_email'])) { ?>
                                                                <span class="error"><?php echo $error['alert_email']; ?></span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="quick-order-pro-setting-admin-emails"><?php echo $entry_admin_emails; ?></label></td>
                                                            <td>
                                                                <textarea id="quick-order-pro-setting-admin-emails" rows="5" cols="70" name="quick_order_pro_setting[admin_emails]"><?php if (!empty($setting['admin_emails'])) echo $setting['admin_emails']; ?></textarea>
                                                            </td>
                                                        </tr>
														<tr>
                                                            <td><label for="quick-order-pro-setting-admin-email-same-customer"><?php echo $entry_admin_email_same_customer; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-admin-email-same-customer" name="quick_order_pro_setting[admin_email_same_customer]">
                                                                    <?php foreach($status_variables as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['admin_email_same_customer']) && $setting['admin_email_same_customer'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
														<tr>
                                                            <td><label for="quick-order-pro-setting-admin-email-send-password"><?php echo $entry_admin_email_send_password; ?></label></td>
                                                            <td>
                                                                <select id="quick-order-pro-setting-admin-email-send-password" name="quick_order_pro_setting[admin_email_send_password]">
                                                                    <?php foreach($status_variables as $key => $variable) { ?>
                                                                    <option <?php if (!empty($setting['admin_email_send_password']) && $setting['admin_email_send_password'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
                                                                    <?php } ?>
                                                                </select>
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
				</div>
				<input type="hidden" name="apply" value="0" />
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--

$('.colorpicker_item').each(function(){
	
	var self = this;
	var input = $(self).next('input');
	var rgb_parts = $(input).attr('value').split(',');
	var rgb = {
		r: rgb_parts[0],
		g: rgb_parts[1],
		b: rgb_parts[2]
	};
	
	$(this).ColorPicker({
		color: rgb,
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('div', self).css('backgroundColor', '#' + hex);
			$(input).attr('value', rgb.r + ',' + rgb.g + ',' + rgb.b);
		}
	});
	
});

$('#quick-order-pro-setting-new-customer-random-password').change(function(event){
	
	if ($(this).is(':checked')) {
		$('#quick-order-pro-setting-new-customer-password').attr('disabled', 'disabled');
	} else {
		$('#quick-order-pro-setting-new-customer-password').attr('disabled', '').removeAttr('disabled');
	}
	
});

$('#quick-order-pro-setting-admin-email-type').change(function(event){
	
	var type = $(this).val();
	
	if (type == 'other') {
		$("input[name='quick_order_pro_setting[alert_email]']").show();
	} else {
		$("input[name='quick_order_pro_setting[alert_email]']").hide().removeClass('error_field').next('.error').remove();
	}
	
});

var module_row = <?php echo $module_row; ?>;

function addModule() {
	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '	<tr>';
	html += '		<td class="left template_id">';
	html += '			<select name="quick_order_pro_module[' + module_row + '][template_id]">';
	html += '				<option value=""><?php echo $text_select; ?></option>';
	<?php foreach ($templates as $template_id => $template_info) { ?>
	html += '      <option value="<?php echo $template_info['template_id']; ?>"><?php echo $template_info['base_name']; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '		</td>';
	html += '	<td class="left"><select name="quick_order_pro_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="quick_order_pro_module[' + module_row + '][position]">';
	<?php foreach ($positions as $position_key => $position) { ?>
	html += '    	<option value="<?php echo $position_key; ?>"><?php echo $position; ?></option>';
	<?php } ?>
	html += '    	</select></td>';
	html += '    <td class="left"><select name="quick_order_pro_module[' + module_row + '][status]">';
    <?php foreach($status_variables as $key => $variable) { ?>
	html += '    <option value="<?php echo $key; ?>"><?php echo $variable; ?></option>';
	<?php } ?>
    html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="quick_order_pro_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	if ($('#module tbody').length) {
		$('#module tbody:last').after(html);
	} else {
		$('#module thead').after(html);
	}
	
	module_row++;
}

$('#tabs a').tabs();
$('#stabs a').tabs();
//--></script>
<?php echo $footer; ?>