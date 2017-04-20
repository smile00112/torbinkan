<div class="row radio">
	<label for="field-edit-<?php echo $field_id; ?>-<?php echo $item_id; ?>"><?php echo $title; ?><?php if ($required) { ?> <span class="required" title="<?php echo $text_required; ?>">*</span><?php } ?></label>
	<div class="radio-wrapper">
		<?php foreach ($option as $id => $value) { ?>
		<div class="radio-item">
			<label><input <?php if ($id == $checked) echo 'checked="checked"'; ?> type="radio" name="fields[<?php echo $field_id; ?>]" value="<?php echo $id; ?>"/> <strong><?php echo $value['value']; ?></strong></label>
		</div>
		<?php } ?>
	</div>
	<?php if ($description) { ?>
	<div class="field-description"><?php echo $description; ?></div>
	<?php } ?>
	<?php if (!empty($error)) { ?>
	<?php echo $error; ?>
	<?php } ?>
</div>