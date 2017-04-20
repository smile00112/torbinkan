<div class="row">
	<label for="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>"><?php echo $title; ?><?php if ($required) { ?> <span class="required" title="<?php echo $text_required; ?>">*</span><?php } ?></label>
	<?php if ($description) { ?>
	<div class="field-description"><?php echo $description; ?></div>
	<?php } ?>
	<select name="fields[<?php echo $field_id; ?>]" <?php if ($multiple) echo 'multiple="multiple"'; ?> <?php if (!empty($class)) echo 'class="' . $class . '"'; ?> id="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
		<?php if (!$required) { ?>
		<option value=""><?php echo $text_select; ?></option>
		<?php } ?>
		<?php foreach ($option as $id => $value) { ?>
		<option <?php if (in_array($id, $selected)) echo 'selected="selected"'; ?> value="<?php echo $id; ?>"><?php echo $value['value']; ?></option>
		<?php } ?>
	</select>
	<?php if (!empty($error)) { ?>
	<?php echo $error; ?>
	<?php } ?>
</div>