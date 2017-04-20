<div class="row captcha">
	<label for="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
		<?php echo $title; ?> 
		<?php if ($required) { ?>
		<span class="required" title="<?php echo $text_required; ?>">*</span>
		<?php } ?>
	</label>
	<span class="captcha-wrapper cf">
		<img src="index.php?route=module/quick_order_pro/captcha&key=<?php echo $form_id; ?>" alt="" />
		<a class="captcha icon" title="<?php echo $text_captcha_reload; ?>"></a>
	</span>
	<input <?php if (!empty($class)) echo 'class="' . $class . '" '; ?>type="text" maxlength="<?php echo $maxlength; ?>" size="<?php echo $maxlength; ?>" name="fields[<?php echo $field_id; ?>]" id="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>" value="<?php echo $value; ?>">
	<?php if (!empty($error)) { ?>
	<?php echo $error; ?>
	<?php } ?>
</div>