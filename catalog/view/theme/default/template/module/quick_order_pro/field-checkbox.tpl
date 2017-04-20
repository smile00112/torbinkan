<div class="row checkbox">
	<label for="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>"><?php echo $title; ?><?php if ($required) { ?> <span class="required" title="<?php echo $text_required; ?>">*</span><?php } ?></label>
	<?php if ($description) { ?>
	<div class="field-description"><?php echo $description; ?></div>
	<?php } ?>
	<div class="checkbox-wrapper">
		<?php foreach ($option as $id => $value) { ?>
		<div class="checkbox-item">
			<label><input <?php if (in_array($id, $checked)) echo 'checked="checked"'; ?> type="checkbox" name="fields[<?php echo $field_id; ?>][]" value="<?php echo $id; ?>"/> <strong><?php echo $value['value']; ?></strong></label>
		</div>
		<?php } ?>
	</div>
	<?php if (!empty($error)) { ?>
	<?php echo $error; ?>
	<?php } ?>
</div>