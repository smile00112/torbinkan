<div class="row">
	<label for="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>"><?php echo $title; ?><?php if ($required) { ?> <span class="required" title="<?php echo $text_required; ?>">*</span><?php } ?></label>
	<input<?php if (!empty($class)) echo ' class="' . $class . '"'; ?> type="password" autocomplete="off" maxlength="<?php echo $maxlength; ?>" name="fields[<?php echo $field_id; ?>]" id="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>" value="<?php echo $value; ?>" />
	<?php if ($description) { ?>
	<div class="field-description"><?php echo $description; ?></div>
	<?php } ?>
	<?php if (!empty($error)) { ?>
	<?php echo $error; ?>
	<?php } ?>
</div>